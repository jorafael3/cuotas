<?php

// require_once "models/logmodel.php";

class PagosModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }



    function Pagos_Realizados()
    {
        try {

            $query = $this->db->connect_dobra()->prepare(" SELECT * FROM SGO_PROV_BANCOS_DEUDAS_PENDIENTES");

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
                // }
            } else {
                $err = $query->errorInfo();

                // if ($t == 1) {
                //     return -1;
                // } else {
                echo json_encode($err);
                exit();
                // }
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function cheques($param)
    {
        try {
            $empresa = $param["empresa"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_Informe_ChequeXEntregar (?)}");
            $query->bindParam(1, $empresa, PDO::PARAM_STR);
           
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
                // }
            } else {
                $err = $query->errorInfo();

                // if ($t == 1) {
                //     return -1;
                // } else {
                echo json_encode($err);
                exit();
                // }
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Actualizar($param)
    {
        try {

            $Usuario = $_SESSION["usuario"];
            $ID = $param["ID"];
            $EMPRESA = $param["EMPRESA"];

            if ($EMPRESA == 'CARTIMEX') {

                // print_r("CARTIMEX");
                $query = $this->db->connect_dobra()->prepare("UPDATE BAN_EGRESOS 
                set SGO_ENTREGADO = 1 , SGO_ENTREGADO_POR = :SGO_ENTREGADO_POR , SGO_FECHA_ENTREGADO = GETDATE()
                where ID = :ID");
            } else {
                // printf("COMPUTRON");
                $query = $this->db->connect_dobra()->prepare("UPDATE COMPUTRONSA..BAN_EGRESOS 
                set SGO_ENTREGADO = 1 , SGO_ENTREGADO_POR = :SGO_ENTREGADO_POR , SGO_FECHA_ENTREGADO = GETDATE()
                where ID = :ID");
            }

            $query->bindParam(":ID", $ID, PDO::PARAM_STR);
            $query->bindParam(":SGO_ENTREGADO_POR", $Usuario, PDO::PARAM_STR);


            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(true);
                exit();
                // }
            } else {
                $err = $query->errorInfo();

                // if ($t == 1) {
                //     return -1;
                // } else {
                echo json_encode($err);
                exit();
                // }
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }


    function Guardar_Documento($filename,  $ID, $val)
    {
        try {
            if ($val == 'CA') {
                // print_r("CARTIMEX");
                $query = $this->db->connect_dobra()->prepare("UPDATE BAN_EGRESOS
                    SET Ruta = :ruta 
                    where ID = :ID");
            } else {
                // printf("COMPUTRON");
                $query = $this->db->connect_dobra()->prepare("UPDATE COMPUTRONSA..BAN_EGRESOS 
                SET Ruta = :ruta 
                where ID = :ID");
            }

            $query->bindParam(":ID", $ID, PDO::PARAM_STR);
            $query->bindParam(":ruta", $filename, PDO::PARAM_STR);


            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(true);
                exit();
                // }
            } else {
                $err = $query->errorInfo();

                // if ($t == 1) {
                //     return -1;
                // } else {
                echo json_encode($err);
                exit();
                // }
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Girar_Cheque($param)
    {
        try {
            $deuda_id = $param["deuda_id"];
            $estado = $param["estado"];
            $estado_girado = $param["estado_girado"];
            $comentario_rechazo = $param["comentario_rechazo"];
            $rechazado = $param["rechazado"];
            $girado_por = $_SESSION["usuario"];
            $rechazado_por = "";
            if ($estado == 3) {
                $rechazado_por = $_SESSION["usuario"];
            }
            $this->db->connect_dobra()->beginTransaction();
            $query = $this->db->connect_dobra()->prepare("UPDATE 
            SGO_PROV_BANCOS_DEUDAS_PENDIENTES
            SET 
                estado = :estado ,comentario_rechazo=:comentario_rechazo,
                girado = :estado_girado, rechazado = :rechazado,
                fecha_girado = GETDATE(),
                girado_por = :girado_por,
                rechazado_por = :rechazado_por
                where deuda_ID = :deuda_ID");
            $query->bindParam(":deuda_ID", $deuda_id, PDO::PARAM_STR);
            $query->bindParam(":estado", $estado, PDO::PARAM_STR);
            $query->bindParam(":comentario_rechazo", $comentario_rechazo, PDO::PARAM_STR);
            $query->bindParam(":estado_girado", $estado_girado, PDO::PARAM_STR);
            $query->bindParam(":rechazado", $rechazado, PDO::PARAM_STR);
            $query->bindParam(":girado_por", $girado_por, PDO::PARAM_STR);
            $query->bindParam(":rechazado_por", $rechazado_por, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                if ($estado == null) {
                    $d = [];
                    $d = $this->Actulizar_SGO_CONFIRMAR($param);
                    echo json_encode([true, $d]);
                    exit();
                } else {
                    echo json_encode([true, true]);
                    exit();
                }
            } else {
                $this->db->connect_dobra()->rollBack();
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

    public function Actulizar_SGO_CONFIRMAR($param)
    {
        try {
            $ID = explode(",", $param["DEUDA_ID"]);
            $empresa = $param["empresa"];
            if ($empresa == "CARTIMEX") {
                $sql = 'UPDATE 
                CARTIMEX..ACR_ACREEDORES_DEUDAS
                SET 
                    SGO_CONFIRMAR = 0,
                    SGO_ABONO = null,
                    SGO_ASIGNADA_POR = null,
                    SGO_ID_AGRUPADO = null
                    where ID = :ID';
            } else {
                $sql = 'UPDATE 
                COMPUTRONSA..ACR_ACREEDORES_DEUDAS
                SET 
                    SGO_CONFIRMAR = 0,
                    SGO_ABONO = null,
                    SGO_ASIGNADA_POR = null,
                    SGO_ID_AGRUPADO = null
                    where ID = :ID';
            }
            $val = 0;
            $errr = "";
            for ($i = 0; $i < count($ID); $i++) {
                $query = $this->db->connect_dobra()->prepare($sql);
                $query->bindParam(":ID", $ID[$i], PDO::PARAM_STR);
                if ($query->execute()) {
                    $val++;
                } else {
                    $err = $query->errorInfo();
                    $errr = $err;
                }
            }
            if ($val == count($ID)) {
                $this->db->connect_dobra()->commit();
            } else {
                $this->db->connect_dobra()->rollBack();
            }
            return [$val, $errr];
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }


    function Buscar_Deudas($param)
    {
        try {
            $EMPRESA = $param["EMPRESA"];
            $TIPO = $param["TIPO"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_PAGOS_APROBADOS (?,?)}");
            $query->bindParam(1, $EMPRESA, PDO::PARAM_STR);
            $query->bindParam(2, $TIPO, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // if ($t == 1) {
                //     return $result;
                // } else {
                echo json_encode($result);
                exit();
                // }
            } else {
                $err = $query->errorInfo();

                // if ($t == 1) {
                //     return -1;
                // } else {
                echo json_encode($err);
                exit();
                // }
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }
}
