<?php


class asignar extends Controller
{

    function __construct()
    {

        parent::__construct();
        //$this->view->render('principal/index');
        //echo "nuevo controlaodr";
    }
    function render()
    {
        $this->view->render('principal/asignar');
    }


    function mostar_asignado()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model-> mostar_asignado($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function Guardar_Datos()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->Guardar_Datos($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function mostrar_ingresado()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->mostrar_ingresado($array);
        //$this->CrecimientoCategoriasIndex();
    }

    function mostar_marca()
    {
        $array = json_decode(file_get_contents("php://input"), true);
        $Ventas =  $this->model->mostar_marca($array);
        //$this->CrecimientoCategoriasIndex();
    }

}
