<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>

<div class="card">
    <div class="card-header pt-8 ">
        <h3>Ingreso de Actividad</h3>
    </div>
    <div class="card ">
    <div class="pt-3">
        <div class="card-body">
            <div class="col-6">
                <h4>Tipo</h4>
                <select class="form-select" aria-label="Default select example" id="Tipo">
                    <option selected></option>
                    <option value="1">Proteccion </option>
                    <option value="2">Descuento Cliente </option>
                    <option value="3">Spiff</option>
                    <option value="3">Plan/Mercado</option>
                    <option value="3">Promo</option>
                </select>
            </div>

            <div class="card-body">

                <div class="row pt-2">
                    <div class="col">
                        <h4>Marca</h4>
                        <input class="form-control form-control" placeholder=""  id="Marca">
                    </div>
                    <div class="col">
                        <h4>Referencia</h4>
                        <input class="form-control form-control" placeholder=""  id="Referencia">
                    </div>
                </div>

                <div class="row pt-8">
                    <div class="col">
                        <h4>Valor</h4>
                        <input type="number" class="form-control" placeholder="" id="Valor">
                    </div>
                    <div class="col">
                        <h4 >Fecha</h4>
                        <input type="text" class="form-control" id="Fecha" aria-describedby="emailHelp" disabled readonly>
                    </div>
                </div>


                <div class="row pt-8">
                    <div class="col">
                        <h4>Periodo</h4>
                        <select class="form-select" aria-label="Default select example" id="Periodo">
                            <option selected></option>
                            <option value="1">semestral </option>
                            <option value="2">bimestral</option>
                            <option value="3">trimestral</option>
                            <option value="3">cuatrimestral</option>
                        </select>
                    </div>

                    <div class="col">
                        <h4>Concepto</h4>
                        <input type="text" class="form-control" placeholder="" id="Concepto">
                    </div>
                </div>
                <div class="row pt-8">
                    <div class="col-4">
                        <button type="button" class="btn btn-primary" id="Ingresar" onclick="grabar_datos()">Igresar</button>
                    </div>
                

<?php require 'views/footer.php'; ?>
<?php require 'funciones/principal_js.php'; ?>
<script>

</script>