<?php


class Deudas extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/deudas');
    }


    function Buscar_Proveedor()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Buscar_Proveedor($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Actualizar_Deuda()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Actualizar_Deuda($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Nueva_Orden()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Nueva_Orden($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Cargar_Ordenes()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Cargar_Ordenes($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Validar_Proveedor()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Validar_Proveedor($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Anular_Orden()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Anular_Orden($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Validar_Cuenta()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Validar_Cuenta($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Buscar_Orden()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Buscar_Orden($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Guardar_Documento(){

        // echo $_POST["file"]; 
        if (($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "application/pdf")
        ) {
            $SO = PHP_OS;
            // echo $SO;
            if ($SO  == "Linux") {
                $destination_folder = '/var/www/html/SGO/Cartimex/bancos/recursos/documentos/';
            } else {
                $destination_folder = 'C:/xampp/htdocs/SGO/Cartimex/bancos/recursos/documentos/';
            }
            // $destination_folder = '/var/www/html/cdn/samsung/';

            $tipo = $_FILES['file']['type'];
            $tipo = explode("/", $tipo);
            $tipo = $tipo[1];
            $fil = $_FILES['file']['name'];
            $fileName = $_FILES['file']['name'] . "." . $tipo;
            $fil = explode("_", $fil);

            // echo $fileName;
            // echo $fil[0];
            // echo $fil[1];
            //echo $fil[1];
            // var_dump($_FILES['file']);
            // echo $destination_folder;
            // chmod($destination_folder, 0755);

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination_folder . $fileName)) {

                $log = $this->model->Guardar_Documento($fileName, $fil[0]);
            } else {
                echo 0;
            }
        } else {
            echo "nada";
        }
    }


    function Guardar_Documento_deudas()
    {
        // echo $_POST["file"]; 
        if (($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "application/pdf")
        ) {
            $SO = PHP_OS;
            // echo $SO;
            if ($SO  == "Linux") {
                $destination_folder = '/var/www/html/SGO/Cartimex/bancos/recursos/deudas/';
            } else {
                $destination_folder = 'C:/xampp/htdocs/SGO/Cartimex/bancos/recursos/deudas/';
            }
            // $destination_folder = '/var/www/html/cdn/samsung/';

            $tipo = $_FILES['file']['type'];
            $tipo = explode("/", $tipo);
            $tipo = $tipo[1];
            $fil = $_FILES['file']['name'];
            $fileName = $_FILES['file']['name'] . "." . $tipo;
            $fil = explode("_", $fil);

            // echo $fileName;
            // echo $fil[0];
            // echo $fil[1];
            //echo $fil[1];
            // var_dump($_FILES['file']);
            // echo $destination_folder;
            // chmod($destination_folder, 0755);

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination_folder . $fileName)) {

                $log = $this->model->Guardar_Documento_deuda($fileName, $fil[0]);

            } else {
                echo 0;
            }
        } else {
            echo "nada";
        }
    }


    
   


}

