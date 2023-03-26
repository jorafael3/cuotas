<?php


class Informe extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/informe');
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
}
