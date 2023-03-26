<?php

// require_once "models/logmodel.php";

class Principalmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Consultar_Bancos($param)
    {
        // echo json_encode($param);
        // exit();       
        try {
            $query = $this->db->connect_dobra()->prepare("
            select ID,Nombre,'CARTIMEX' as empresa from BAN_BANCOS
            where clase = '01' and Nombre != 'no'
			union all
			  select ID,Nombre,'COMPUTRONSA' as empresa from COMPUTRONSA..BAN_BANCOS
            where clase = '01' and Nombre != 'no' --and nombre like '%banco%'");
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

    function Cargar_Datos($param)
    {
        // echo json_encode($param);
        // exit();       
        try {

            $query = $this->db->connect_dobra()->prepare("
            select 
            a.Saldo_id,
            a.banco_ID,
            a.saldo_contable,
            a.saldo_disponible,
            a.deposito_dia, 
            a.empresa,
            a.Banco_nombre as Nombre,
            a.Comentario,
            a.posicion 
            from SGO_PROV_BANCOS a
            
            ");
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

            $banco = $param[$i]["Banco"];
            $s_contable = $param[$i]["Saldo_Contable"];
            $s_disponible = $param[$i]["Saldo_Disponible"];
            $deposito_dia = $param[$i]["Deposita_Dia"];
            $creado = $_SESSION["usuario"];
            $empresa = $param[$i]["Empresa"];
            $banco_nombre = $param[$i]["Banco_nombre"];
            $saldo_id = $param[$i]["Saldo_id"];
            $Comentario = $param[$i]["Comentario"];
            $Contador = $param[$i]["Contador"];
            $query = $this->db->connect_dobra()->prepare('{CALL SGO_PROV_BANCOS_GUARDAR  
                (?,?,?,?,?,?,?,?,?,?)}');
            $query->bindParam(1, $banco, PDO::PARAM_STR);
            $query->bindParam(2, $s_contable, PDO::PARAM_STR);
            $query->bindParam(3, $s_disponible, PDO::PARAM_STR);
            $query->bindParam(4, $deposito_dia, PDO::PARAM_STR);
            $query->bindParam(5, $creado, PDO::PARAM_STR);
            $query->bindParam(6, $empresa, PDO::PARAM_STR);
            $query->bindParam(7, $banco_nombre, PDO::PARAM_STR);
            $query->bindParam(8, $saldo_id, PDO::PARAM_STR);
            $query->bindParam(9, $Comentario, PDO::PARAM_STR);
            $query->bindParam(10, $Contador, PDO::PARAM_STR);

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
}
