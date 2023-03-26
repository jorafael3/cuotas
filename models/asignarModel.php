<?php

// require_once "models/logmodel.php";

use Symfony\Component\VarExporter\Internal\Values;

class asignarModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function mostar_asignado($param)
    {
        try {

            $query = $this->db->connect_dobra()->prepare('SGO_ActividadMarcas_Select_Gastos');

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
            } else {
                $err = $query->errorInfo();
                echo json_encode($err);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Guardar_Datos($param)

    {
        $val = 0;

        for ($i = 0; $i < count($param); $i++) {

            $Creado_por = $_SESSION["usuario"];
            $Documento = $param[$i]["Documento"];
            $Actividad = $param[$i]["Actividad"];
            $Valor = $param[$i]["Valor"];

            $query = $this->db->connect_dobra()->prepare('INSERT INTO SGO_Actividades_Marcas_Creadas(Creado_por,DocumentoID,ActividadID,Valor)
            values (:usuario,:Documento,:Actividad,:Valor)');

            $query->bindParam(":usuario", $Creado_por, PDO::PARAM_STR);
            $query->bindParam(":Documento", $Documento, PDO::PARAM_STR);
            $query->bindParam(":Actividad", $Actividad, PDO::PARAM_STR);
            $query->bindParam(":Valor", $Valor, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $val = $val + 1;
            } else {
                $err = $query->errorInfo();
                $val = $err;
            }
        }
        echo json_encode($val);
        exit();
    }


    function mostrar_ingresado($param)
    {
        try {
            $marca = $param["documento"];
            
            $query = $this->db->connect_dobra()->prepare('select * from SGO_Actividades_Marcas
            where ID_Marca  = :ID_Marca');

            $query->bindParam(":ID_Marca", $marca, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
            } else {
                $err = $query->errorInfo();
                echo json_encode($err);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    

    function mostar_marca($param)
    {
        try {

            $query = $this->db->connect_dobra()->prepare('SELECT SGO_Actividades_Marcas.Marca,SGO_Actividades_Marcas_Creadas.Valor
            FROM SGO_Actividades_Marcas
            JOIN SGO_Actividades_Marcas_Creadas
            ON SGO_Actividades_Marcas.ID = SGO_Actividades_Marcas_Creadas.ID;
            ');

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
            } else {
                $err = $query->errorInfo();
                echo json_encode($err);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }
}
