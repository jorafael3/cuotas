<?php


class informecompletos extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/informecompletos');
    }

    function Cargar_informe()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Cargar_informe($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Guardar_Datos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Guardar_Datos($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Buscar_Deudas()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Buscar_Deudas($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Actualizar_Bancos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Actualizar_Bancos($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Guardar_Deudas()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Guardar_Deudas($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Girar_Cheque()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Girar_Cheque($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Detalles_Deudor()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Detalles_Deudor($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Confirmar_Rechazado()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Confirmar_Rechazado($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Guardar_Documento()
    {

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
            $ID = explode(".", $fileName);
            $ID = $ID[0];
            // var_dump($_FILES['file']);
            // echo $destination_folder;
            // chmod($destination_folder, 0755);
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination_folder . $fileName)) {
                // echo "guardado";
                $log = $this->model->Guardar_archivo($fileName, $ID);
            } else {
                echo 0;
            }
        } else {
            echo "nada";
        }
    }
}
