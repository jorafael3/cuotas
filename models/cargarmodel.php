<?php

// require_once "models/logmodel.php";

class cargarmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function cargar_combo($param)
    {
        try {
            
            $query = $this->db->connect_dobra()->prepare('select ID_Marca , Concepto , Marca from SGO_Actividades_Marcas');

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
