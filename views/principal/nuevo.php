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

                <div class="col-lg-6 ">
                    <h1>Buscar Producto</h1>

                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Nombre / Codigo del producto</label>
                    <!--begin::Input group-->
                    <div class="mb-5">
                        <!-- <input type="text" class="form-control form-control-solid" placeholder="Cedula" /> -->
                        <div class="input-group mb-0">

                            <input id="CU_CODIGO" type="text" class="form-control form-control-solid" placeholder="cedula / nombre" aria-label="cedula / nombre del cliente" aria-describedby="basic-addon2">

                            <div class="input-group-append">
                                <button onclick="Buscar_Producto()" class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                        <div id="CLI_COINCIDENCIAS" class="input-group mb-0 pt-3 d-none">
                            <h6 id="CLI_COINCIDENCIAS_TEXT" class="text-danger"></h6>
                            <select id="CLI_SEL_SUG" class=" form-select form-select-solid" placeholder="cedula / nombre">

                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Deudas">
                    <tfoot align="center">
                        <tr>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kt_modal_Productos" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px" data-select2-id="select2-data-351-tkl4">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <div class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#" id="kt_modal_new_address_form">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_new_address_header">
                    <h2>Lista de Proveedores</h2>
                </div>
                <div class="modal-body py-10 px-lg-17" data-select2-id="select2-data-350-hr16">
                    <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Proveedores">
                    </table>
                </div>

            </div>
            <!--end::Form-->
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>

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
<?php require 'funciones/cuotas_js.php'; ?>

<script>

</script>