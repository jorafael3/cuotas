<?php


class Pagos extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/pagos');
    }

    function Buscar_Deudas()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Buscar_Deudas($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Buscar_Proveedor()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Buscar_Proveedor($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Girar_Cheque()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Girar_Cheque($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Pagos_Realizados()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Pagos_Realizados($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function cheques()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->cheques($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Actualizar()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Actualizar($array);
        //$this->CrecimientoCategoriasIndex();
    }


    function Guardar_Documento()
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
                $destination_folder = '/var/www/html/SGO/Cartimex/bancos/recursos/pagos/';
            } else {
                $destination_folder = 'C:/xampp/htdocs/SGO/Cartimex/bancos/recursos/pagos/';
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

                $log = $this->model->Guardar_Documento($fileName, $fil[0], $fil[1]);
            } else {
                echo 0;
            }
        } else {
            echo "nada";
        }
    }
}
