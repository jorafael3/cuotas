<?php


class cargar extends Controller
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


    function cargar_combo()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->cargar_combo($array);
        //$this->CrecimientoCategoriasIndex();
    }

}
