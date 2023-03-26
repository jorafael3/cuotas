<?php

require 'views/header.php';
?>

<div class="col-12">
    <div class="card bg-light shadow-sm">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Credito</span>
            </h3>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">

            <div class="col-lg-12 row">

                <div class="col-lg-4 ">

                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Cédula</label>
                    <!--begin::Input group-->
                    <div class="mb-5">
                        <!-- <input type="text" class="form-control form-control-solid" placeholder="Cedula" /> -->
                        <div class="input-group mb-0">

                            <input id="PROF_CLI_CEDULA" type="text" class="form-control form-control-solid" placeholder="cedula" aria-label="cedula / nombre del cliente" aria-describedby="basic-addon2">

                            <div class="input-group-append">
                                <button onclick="Buscar_cedula()" class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                            </div>

                        </div>

                    </div>
                </div>



            </div>
            <div id="CON" style="display: none;">
                <h1 style="font-size: 48px;" id="ENTRE" ></h1>
                <div id="chartdiv" style="width: 100%;height: 800px;"></div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<?php require 'views/footer.php'; ?>
<?php require 'funciones/minimos_js.php'; ?>

<script>

</script>