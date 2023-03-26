<?php


class principal extends Controller
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


    function Guardar_datos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model-> Guardar_datos($array);
        //$this->CrecimientoCategoriasIndex();
    }
    function Guardar_D()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model-> Guardar_D($array);
        //$this->CrecimientoCategoriasIndex();
    }

}
