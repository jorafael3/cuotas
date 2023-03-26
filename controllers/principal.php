<?php


class Principal extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/principal');
    }

    
    function Cargar_Bancos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Consultar_Bancos($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Cargar_Datos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Cargar_Datos($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Guardar_Datos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Guardar_Datos($array);
        //$this->CrecimientoCategoriasIndex();
    }

}
