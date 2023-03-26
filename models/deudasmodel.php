<?php

// require_once "models/logmodel.php";

use LDAP\Result;

class Deudasmodel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function Buscar_Proveedor($param)
    {
        // echo json_encode($param);
        // exit();       
        try {
            $ruc = $param["ruc"];
            $tipo = $param["tipo"];
            if ($tipo == 1) {
                $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_BUSCAR_PROVEEDOR (?)}");
                $query->bindParam(1, $ruc, PDO::PARAM_STR);

                if ($query->execute()) {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    if (count($result) >= 2) {
                        echo json_encode([$result, "00000"]);
                        exit();
                    } else {
                        $deuda = $this->Buscar_Deudas($result[0]["ID"]);
                        echo json_encode([$result, $deuda]);
                        exit();
                    }
                } else {
                    $err = $query->errorInfo();
                    echo json_encode(0);
                    exit();
                }
            } else {
                $deuda = $this->Buscar_Deudas($ruc);
                echo json_encode([0, $deuda]);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode(0);
            exit();
        }
    }

    function Buscar_Deudas($id)
    {
        try {
            $query = $this->db->connect_dobra()->prepare("
            select ID,Fecha,Número,Detalle,Tipo,Vencimiento,Valor,Saldo,
            SGO_CONFIRMAR,
            detalle,
            'CARTIMEX' as Empresa,
            SGO_ABONO
            from ACR_ACREEDORES_DEUDAS
            where AcreedorID = :id
            and Débito = 0 and Anulado = 0 and Saldo > 0
            order by Vencimiento 
                ");
            $query->bindParam(":id", $id, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                $err = $query->errorInfo();
                return -1;
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode($e);
            exit();
        }
    }

    function Actualizar_Deuda($param)
    {
        try {
            $ID = $param["ID"];
            $Abono = $param["Abono"];
            $creadopor = $_SESSION["usuario"];
            $SGO_COMENTARIO_PENDIENTE = $param["SGO_COMENTARIO_PENDIENTE"];
            $query = $this->db->connect_dobra()->prepare("
            update ACR_ACREEDORES_DEUDAS
            SET 
            SGO_CONFIRMAR = 1,
            SGO_ABONO = :SGO_ABONO,
            SGO_COMENTARIO_PENDIENTE = :SGO_COMENTARIO_PENDIENTE,
            SGO_ASIGNADA_POR = :SGO_ASIGNADA_POR,
            SGO_FECHA_SOLICITADO = GETDATE()
            Where ID = :ID");
            $query->bindParam(":ID", $ID, PDO::PARAM_STR);
            $query->bindParam(":SGO_ABONO", $Abono, PDO::PARAM_STR);
            $query->bindParam(":SGO_COMENTARIO_PENDIENTE", $SGO_COMENTARIO_PENDIENTE, PDO::PARAM_STR);
            $query->bindParam(":SGO_ASIGNADA_POR", $creadopor, PDO::PARAM_STR);
            if ($query->execute()) {
                // $result = $query->fetchAll(PDO::FETCH_ASSOC);
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

    function Nueva_Orden($param)
    {
        try {
            $ASUNTO = $param["asunto"];
            $DETALLE = "";
            $valor = $param["valor"];
            $PROVEEDORID = $param["ID"];
            $cuenta = $param["cuenta"];
            $cuenta_codigo = $param["cuenta_codigo"];
            $ORDEN_DOBRA = $param["ORDEN_DOBRA"];
            $usuario = $_SESSION["usuario"];
            $Empresa = "CARTIMEX";
            $tipo = "PROV-ORDEN";
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_CREAR_ORDEN 
                (?,?,?,?,?
                ,?,?,?,?,?)}");
            $query->bindParam(1, $PROVEEDORID, PDO::PARAM_STR);
            $query->bindParam(2, $ASUNTO, PDO::PARAM_STR);
            $query->bindParam(3, $cuenta, PDO::PARAM_STR);
            $query->bindParam(4, $cuenta_codigo, PDO::PARAM_STR);
            $query->bindParam(5, $valor, PDO::PARAM_STR);
            $query->bindParam(6, $usuario, PDO::PARAM_STR);
            $query->bindParam(7, $Empresa, PDO::PARAM_STR);
            $query->bindParam(8, $DETALLE, PDO::PARAM_STR);
            $query->bindParam(9, $ORDEN_DOBRA, PDO::PARAM_STR);
            $query->bindParam(10, $tipo, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([true, $result]);
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

    function Guardar_Documento($filename, $ID)
    {
        try {
            $query = $this->db->connect_dobra()->prepare("UPDATE
                CARTIMEX..SGO_PROV_BANCOS_ORDENES SET archivo = :archivo
                WHERE Orden_ID = :Orden_ID
                ");
            $query->bindParam("archivo", $filename, PDO::PARAM_STR);
            $query->bindParam("Orden_ID", $ID, PDO::PARAM_STR);

            if ($query->execute()) {
                // $result = $query->fetchAll(PDO::FETCH_ASSOC);
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

    function Guardar_Documento_deuda($filename, $ID)
    {
        try {

            $query = $this->db->connect_dobra()->prepare("UPDATE
                CARTIMEX..ACR_ACREEDORES_DEUDAS SET Archivo = :archivo
                WHERE ID = :ID
                ");

            $query->bindParam("archivo", $filename, PDO::PARAM_STR);
            $query->bindParam("ID", $ID, PDO::PARAM_STR);

            if ($query->execute()) {
                // $result = $query->fetchAll(PDO::FETCH_ASSOC);
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

    function Cargar_Ordenes($param)
    {
        try {
            $usu = $_SESSION["usuario"];
            $tipo = $param["tipo"];
            if($tipo == 1){
                $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_MIS_ORDENES (?)}");
                $query->bindParam(1, $usu, PDO::PARAM_STR);
            }else{
                $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_ORDENES_MIS_ORDENES_PAGADAS (?)}");
                $query->bindParam(1, $usu, PDO::PARAM_STR);
            }
           

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

    function Validar_Proveedor($param)
    {
        // echo json_encode($param);
        // exit();       
        try {
            $ruc = $param["ruc"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_BUSCAR_PROVEEDOR (?)}");
            $query->bindParam(1, $ruc, PDO::PARAM_STR);

            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
            } else {
                $err = $query->errorInfo();
                echo json_encode(0);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode(0);
            exit();
        }
    }

    function Validar_Cuenta($param)
    {
        // echo json_encode($param);
        // exit();       
        try {
            $ruc = $param["ruc"];
            $query = $this->db->connect_dobra()->prepare("{CALL SGO_PROV_BANCOS_BUSCAR_CUENTAS (?)}");
            $query->bindParam(1, $ruc, PDO::PARAM_STR);
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($result);
                exit();
            } else {
                $err = $query->errorInfo();
                echo json_encode(0);
                exit();
            }
        } catch (PDOException $e) {
            //return [];
            $e = $e->getMessage();
            echo json_encode(0);
            exit();
        }
    }

    function Anular_Orden($param)
    {
        try {
            $Orden_id = $param["Orden_id"];
            $Orden_tipo = $param["Orden_tipo"];
            if ($Orden_tipo == 1) {
                $sql = "
                UPDATE SGO_PROV_BANCOS_ORDENES SET
                Estado = 0
                WHERE Orden_ID = :Orden_ID";
            } else if ($Orden_tipo == 2) {
                $sql = "
                UPDATE ACR_ACREEDORES_DEUDAS SET
                SGO_CONFIRMAR = 0,
                SGO_ABONO = null,
                SGO_ASIGNADA_POR = NULL,
                SGO_FECHA_SOLICITADO = NULL,
                SGO_COMENTARIO_PENDIENTE = NULL
                WHERE ID = :Orden_ID";
            }

            $query = $this->db->connect_dobra()->prepare($sql);
            $query->bindParam(":Orden_ID", $Orden_id, PDO::PARAM_STR);
            if ($query->execute()) {
                // $result = $query->fetchAll(PDO::FETCH_ASSOC);
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

    function Buscar_Orden($param)
    {
        try {
            $ProveedorID = $param["PROVEEDOR_ID"];
            $ProveedorID2 = $param["PROVEEDOR_ID"];
            $query = $this->db->connect_dobra()->prepare("SELECT 
            ID,Detalle,Tipo,Número ,Total
            from COM_ORDENES
            WHERE ProveedorID = :ProveedorID
            and Anulado = 0
            union all
            select ID,Detalle,Tipo,Número,Total
            from PRV_ORDENES
            WHERE ProveedorID = :ProveedorID2
            and Anulado = 0

            ");
            $query->bindParam(":ProveedorID", $ProveedorID, PDO::PARAM_STR);
            $query->bindParam(":ProveedorID2", $ProveedorID2, PDO::PARAM_STR);
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
