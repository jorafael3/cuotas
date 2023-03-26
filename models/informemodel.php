<?php

// require_once "models/logmodel.php";

class Informemodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Cargar_informe($param)
    {

        try {
            $inicio = $param["inicio"];
            $fin = $param["fin"];
            $query = $this->db->connect_dobra()->prepare("
            SELECT * FROM SGO_PROV_BANCOS pr
            left join BAN_BANCOS b
            on b.ID = pr.banco_ID
            where  convert(date, Fecha_Creado) >= :ini and convert(date, Fecha_Creado) <= :fin ");
            $query->bindParam(":ini", $inicio, PDO::PARAM_STR);
            $query->bindParam(":fin", $fin, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                //$deudas = $this->Buscar_Deudas(1);
                echo json_encode([$result, 0]);
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

    function Buscar_Deudas($param)
    {
        try {
            $EMPRESA = $param["EMPRESA"];
            $TIPO = $param["TIPO"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_PAGOS_GERENCIAL (?,?)}");
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

    function Guardar_Datos($param)
    {

        $val = 0;
        for ($i = 0; $i < count($param); $i++) {
            $banco = $param[$i]["banco"];
            $s_contable = $param[$i]["s_contable"];
            $s_disponible = $param[$i]["s_disponible"];
            $deposito_dia = $param[$i]["deposito_dia"];
            $creado = $_SESSION["usuario"];
            $query = $this->db->connect_dobra()->prepare('{CALL SGO_PROV_BANCOS_GUARDAR  
                (?,?,?,?,?)}');
            $query->bindParam(1, $banco, PDO::PARAM_STR);
            $query->bindParam(2, $s_contable, PDO::PARAM_STR);
            $query->bindParam(3, $s_disponible, PDO::PARAM_STR);
            $query->bindParam(4, $deposito_dia, PDO::PARAM_STR);
            $query->bindParam(5, $creado, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $val = $val + 1;
            } else {
                $err = $query->errorInfo();
            }
        }
        echo json_encode($val);
        exit();
    }

    function Guardar_Deudas($param)
    {
        try {
            $ID = $param["ID"];
            $abono = $param["abono"];
            $tipo = $param["tipo"];
            $banco = $param["banco"];
            $banco_nombre = $param["banco_nombre"];
            $fecha = $param["fecha"];
            $estado = $param["estado"];
            $comentario_rechazo = $param["comentario_rechazo"];
            $comentario_confirmar = $param["comentario_confirmar"];
            $rechazado = $param["rechazado"];
            $valor_deuda_total = $param["valor_deuda_total"];
            $acr_deuda_id = $param["acr_deuda_id"];
            $deuda_numero = $param["deuda_numero"];
            $usuario = $_SESSION["usuario"];

            if ($estado == 3) {
                $rechazado_por = $_SESSION["usuario"];
                $rechazado_fecha = date('Y-d-m');
            } else {
                $rechazado_por = "";
                $rechazado_fecha = "";
            }

            $query = $this->db->connect_dobra()->prepare("INSERT INTO
                SGO_PROV_BANCOS_DEUDAS_PENDIENTES(
                    acreedor_ID,
                    abono,
                    tipo,
                    fecha_cheque,
                    Creado_por,
                    Banco_nombre,
                    Banco_id,
                    estado,
                    comentario_rechazo,
                    comentario_confirmar,
                    rechazado,
                    valor_deuda_total,
                    deuda_numero,
                    acr_deuda_id,
                    rechazado_por,
                    fecha_rechazado
                )VALUES(
                    :acreedor_ID,
                    :abono,
                    :tipo,
                    :fecha_cheque,
                    :Creado_por,
                    :Banco_nombre,
                    :Banco_id,
                    :estado,
                    :comentario_rechazo,
                    :comentario_confirmar,
                    :rechazado,
                    :valor_deuda_total,
                    :deuda_numero,
                    :acr_deuda_id,
                    :rechazado_por,
                    :fecha_rechazo
                )");
            $query->bindParam(":acreedor_ID", $ID, PDO::PARAM_STR);
            $query->bindParam(":abono", $abono, PDO::PARAM_STR);
            $query->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $query->bindParam(":fecha_cheque", $fecha, PDO::PARAM_STR);
            $query->bindParam(":Creado_por", $usuario, PDO::PARAM_STR);
            $query->bindParam(":Banco_nombre", $banco_nombre, PDO::PARAM_STR);
            $query->bindParam(":Banco_id", $banco, PDO::PARAM_STR);
            $query->bindParam(":estado", $estado, PDO::PARAM_STR);
            $query->bindParam(":comentario_rechazo", $comentario_rechazo, PDO::PARAM_STR);
            $query->bindParam(":comentario_confirmar", $comentario_confirmar, PDO::PARAM_STR);
            $query->bindParam(":rechazado", $rechazado, PDO::PARAM_STR);
            $query->bindParam(":valor_deuda_total", $valor_deuda_total, PDO::PARAM_STR);
            $query->bindParam(":deuda_numero", $acr_deuda_id, PDO::PARAM_STR);
            $query->bindParam(":acr_deuda_id", $deuda_numero, PDO::PARAM_STR);
            $query->bindParam(":rechazado_por", $rechazado_por, PDO::PARAM_STR);
            $query->bindParam(":fecha_rechazo", $rechazado_fecha, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(true);
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
                if($estado_girado == 1){
                    
                }
                echo json_encode(true);
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

    function ACtualizar_EStado_D_P(){

    }

    function Detalles_Deudor($param)
    {
        try {
            // $DET2 = $this->Detalles_Deudor2($param);
            // $DET3 = $this->Detalles_Deudor3($param);
            // echo json_encode([0, $DET2, $DET3]);
            // exit();
            $tipo = $param["tipo"];
            $numero = $param["numero"];
            $empresa = $param["empresa"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_ACR_Acreedores_Select_Deuda (?,?,?)}");
            $query->bindParam(1, $tipo, PDO::PARAM_STR);
            $query->bindParam(2, $numero, PDO::PARAM_STR);
            $query->bindParam(3, $empresa, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $DET2 = $this->Detalles_Deudor2($param);
                $DET3 = $this->Detalles_Deudor3($param);
                echo json_encode([$result, $DET2, $DET3]);
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
    function Detalles_Deudor2($param)
    {
        try {
            $tipo = $param["tipo"];
            $numero = $param["numero"];
            $empresa = $param["empresa"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_ACR_Acreedores_Select_DetalleFactura  (?,?,?)}");
            $query->bindParam(1, $tipo, PDO::PARAM_STR);
            $query->bindParam(2, $numero, PDO::PARAM_STR);
            $query->bindParam(3, $empresa, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                $err = $query->errorInfo();
                return $err;
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Detalles_Deudor3($param)
    {
        try {
            $tipo = $param["tipo"];
            $numero = $param["numero"];
            $empresa = $param["empresa"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_ACR_Acreedores_Select_Deuda_Total   (?,?,?)}");
            $query->bindParam(1, $tipo, PDO::PARAM_STR);
            $query->bindParam(2, $numero, PDO::PARAM_STR);
            $query->bindParam(3, $empresa, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                $err = $query->errorInfo();
                return $err;
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Confirmar_Rechazado($param)
    {
        try {
            $deuda_id = $param["deuda_ID"];
            $abono = $param["abono"];
            $fecha = $param["fecha"];
            $banco_nombre = $param["banco_nombre"];
            $banco = $param["banco"];
            $comentario_confirmar = $param["comentario_confirmar"];
            $tipo = $param["tipo"];
            $rechazado = $param["rechazado"];
            $estado = $param["estado"];
            $query = $this->db->connect_dobra()->prepare("UPDATE 
            SGO_PROV_BANCOS_DEUDAS_PENDIENTES
            SET 
                abono = :abono ,fecha_cheque=:fecha_cheque,
                Banco_nombre = :Banco_nombre, Banco_id = :Banco_id,
                comentario_confirmar = :comentario_confirmar, tipo = :tipo,
                rechazado = :rechazado,estado=:estado
                where deuda_ID = :deuda_ID");
            $query->bindParam(":deuda_ID", $deuda_id, PDO::PARAM_STR);
            $query->bindParam(":abono", $abono, PDO::PARAM_STR);
            $query->bindParam(":fecha_cheque", $fecha, PDO::PARAM_STR);
            $query->bindParam(":Banco_nombre", $banco_nombre, PDO::PARAM_STR);
            $query->bindParam(":Banco_id", $banco, PDO::PARAM_STR);
            $query->bindParam(":comentario_confirmar", $comentario_confirmar, PDO::PARAM_STR);
            $query->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $query->bindParam(":rechazado", $rechazado, PDO::PARAM_STR);
            $query->bindParam(":estado", $estado, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(true);
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
