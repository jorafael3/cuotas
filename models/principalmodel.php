<?php

// require_once "models/logmodel.php";
// require('public/fpdf/fpdf.php');
use LDAP\Result;

class principalmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Buscar_Producto($param)
    {
        try {
            $codigo = $param["codigo"];
            $sucursal = "";
            $query = $this->db->connect_dobra()->prepare('{CALL WEB_Select_Productos_Like (?)}');
            $query->bindParam(1, $codigo, PDO::PARAM_STR);
            // $query->bindParam(2, $sucursal, PDO::PARAM_STR);
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

            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Cargar_Datos($parametros)
    {
        try {
            $parametro = "l3250";
            $query = $this->db->connect_dobra()->prepare('{CALL WEB_Select_Producto_2 (?)}');
            $query->bindParam(1, $parametro, PDO::PARAM_STR);
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

            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }
}
