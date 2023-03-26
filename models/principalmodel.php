<?php

// require_once "models/logmodel.php";

class principalmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Guardar_datos($param)

    {
        try {

            $Creado_por = $_SESSION["usuario"];
            $Tipo = $param["Tipo"];
            $Marca = $param["Marca"];
            $Referencia = $param["Referencia"];
            $Fecha = $param["Fecha"];
            $Periodo = $param["Periodo"];   
            $Concepto = $param["Concepto"];
            $valor = $param["valor"];    

            $query = $this->db->connect_dobra()->prepare('INSERT INTO SGO_Actividades_Marcas (creado_por,Tipo,Marca,Referencia,Fecha,Periodo,Concepto,valor)
            values (:usuario,:Tipo,:Marca,:Referencia,:Fecha,:Periodo,:Concepto,:valor)');

            $query->bindParam(":usuario", $Creado_por, PDO::PARAM_STR);
            $query->bindParam(":Tipo", $Tipo, PDO::PARAM_STR);
            $query->bindParam(":Marca", $Marca, PDO::PARAM_STR);
            $query->bindParam(":Referencia", $Referencia, PDO::PARAM_STR);
            $query->bindParam(":Fecha", $Fecha, PDO::PARAM_STR);
            $query->bindParam(":Periodo", $Periodo, PDO::PARAM_STR);
            $query->bindParam(":Concepto", $Concepto, PDO::PARAM_STR);
            $query->bindParam(":valor", $valor, PDO::PARAM_STR);

            
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // $this->Generador_pdf();
                echo json_encode(true);
                exit();
            } else { 
                $err = $query->errorInfo();
                echo json_encode($err);
                exit();
            }
        } catch (PDOException $e) {

            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }
}