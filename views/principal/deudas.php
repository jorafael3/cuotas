<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>

<div id="kt_app_content" class="app-content flex-column-fluid mt-55">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Solicitar Pagos Proveedores</h3>
            </div>
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-md-3 fv-row fv-plugins-icon-container">
                        <label class="fs-5 fw-semibold mb-2">Proveedor</label>
                        <input id="Proveedor" type="text" class="form-control form-control-solid" placeholder="000000000" name="first-name">
                    </div>
                    <div class="col-md-3 fv-row fv-plugins-icon-container">
                        <button onclick="Buscar_Proveedor()" id="BTN_BUSACR_PROVEEDOR" class="btn btn-success mt-9">buscar</button>
                    </div>
                    <div class="col-md-3 fv-row fv-plugins-icon-container">
                        <button id="BTN_OR" onclick="BTN_ORDEN()" class="btn btn-info mt-9">Crear Orden de Pago</button>
                    </div>
                </div>
                <ul class="nav nav-pills nav-pills-custom mb-3" role="tablist">
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <a data-toggle="tab" class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-80px h-85px py-4 active" data-bs-toggle="pill" href="#kt_stats_widget_2_tab_1" aria-selected="false" role="tab" tabindex="-1">
                            <div class="nav-icon">
                                <i class="bi bi-cash-coin fs-1"></i>
                            </div>
                            <span class="nav-text text-gray-700 fw-bold fs-6 lh-1">Deudas</span>
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                        </a>
                    </li>
                    <li class="nav-item mb-3 me-3 me-lg-6" role="presentation">
                        <a data-toggle="tab" class="nav-link d-flex justify-content-between flex-column flex-center overflow-hidden w-120px h-85px py-4" data-bs-toggle="pill" href="#kt_stats_widget_2_tab_2" aria-selected="false" role="tab" tabindex="-1">
                            <div class="nav-icon">
                                <i class="bi bi-clipboard2-plus-fill fs-1"></i>
                            </div>
                            <span class="nav-text text-gray-700 fw-bold fs-6 lh-1">Mis Pagos Solicitados</span>
                            <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="kt_stats_widget_2_tab_1" role="tabpanel">
                        <div class="row mb-5">
                            <h2 id="S_NOm" style="display: none;">Nombre:
                                <span id="PR_NOM">asdasd</span>
                            </h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Deudas">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 350px;"></th>
                                        <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-125px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 50.5px;"></th>
                                        <th class=" min-w-50px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" sorting_disabled" rowspan="1" colspan="1" style="width: 25.8125px;"></th>
                                        <th class=" sorting_disabled" rowspan="1" colspan="1" style="width: 25.8125px;"></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>

                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="kt_stats_widget_2_tab_2" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Ordenes">
                                <!--begin::Table head-->
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-5 text-uppercase gs-0">
                                        <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 350px;"></th>
                                        <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-125px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" min-w-100px sorting_disabled" rowspan="1" colspan="1" style="width: 50.5px;"></th>
                                        <th class=" min-w-50px sorting_disabled" rowspan="1" colspan="1" style="width: 150px;"></th>
                                        <th class=" sorting_disabled" rowspan="1" colspan="1" style="width: 25.8125px;"></th>
                                        <th class=" sorting_disabled" rowspan="1" colspan="1" style="width: 25.8125px;"></th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>

                            </table>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="kt_modal_new_address" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px" data-select2-id="select2-data-351-tkl4">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <div class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#" id="kt_modal_new_address_form">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_new_address_header">
                    <h2>Nueva Orden de Pago</h2>
                </div>
                <div class="modal-body py-10 px-lg-17" data-select2-id="select2-data-350-hr16">
                    <!--begin::Scroll-->
                    <div class="card-toolbar">
                        <label class="form-check form-switch form-switch-sm form-check-solid">
                            <input id="CH_PROV" class="form-check-input" type="radio" name="a" checked="checked" value="1">
                            <span class="form-check-label fs-5">Proveedor</span>
                        </label>
                        <label class="form-check form-switch form-switch-sm form-check-solid">
                            <input id="CH_OTRO" class="form-check-input" type="radio" value="1" name="a">
                            <span class="form-check-label fs-5">Otros</span>
                        </label>
                    </div>
                    <div id="S_R">
                        <label class="required fs-5 fw-semibold mb-2">Ruc / Nombre Proveedor</label>
                        <div class="mb-5">
                            <div class="input-group mb-0">
                                <input id="OR_PROV" type="text" class="form-control form-control-solid" placeholder="ruc / nombre" name="address2">
                                <div class="input-group-append">
                                    <button onclick="Buscar_Proveedor_No()" id="BTN_NO_PR" class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="S_C" style="display: none;">
                        <label class="required fs-5 fw-semibold mb-2">Cuenta Contable (codigo)</label>
                        <div class="mb-5">
                            <div class="input-group mb-0">
                                <input id="OR_CUENTA" type="text" class="form-control form-control-solid" placeholder="0.0.0.0.0" name="address2">
                                <div class="input-group-append">
                                    <button onclick="Buscar_Cuenta_No()" id="BTN_NO_CU" class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div style="display: none;" id="SECC_NO_NOMB_PROV">
                        <h3 id="NO_NOMB_PROV"></h3>

                    </div>
                    <div id="SECCION_NO_PRO" style="display: none;">
                        <div class="card-toolbar">
                            <label class="form-check form-switch form-switch-sm form-check-solid">
                                <input id="CH_AGR_ORDEN" class="form-check-input" type="checkbox" name="a" value="1">
                                <span class="form-check-label fw-bold fs-4">Agregar Orden</span>
                            </label>
                        </div>

                        <div id="SECCION_AGREGAR_ORDEN" style="display: none;">
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_new_address_header" data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px" style="max-height: 429px;" data-select2-id="select2-data-kt_modal_new_address_scroll">
                                <div class="row" id="SECC_BTN_ORDEN">
                                    <div class="mb-5 col-4">
                                        <!-- <label class="required fs-5 fw-semibold mb-2">Agregar Orden</label> -->
                                        <div class="input-group mb-0">
                                            <!-- <input id="OR_ORDEN" type="text" class="form-control form-control-solid" placeholder="0000000" name="address2"> -->
                                            <div class="input-group-append">
                                                <button onclick="Buscar_Ordenes()" id="BTN_NO_PR" class="btn btn-info" type="button"><i class="bi bi-file-text fs-1"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5 col-8">
                                        <h3>Orden #
                                            <span class="text-gray-600" id="NO_NUM_ORDEN"></span>
                                        </h3>
                                        <h3>Nombre:
                                            <span class="text-gray-600" id="NO_NUM_Nombre"></span>
                                        </h3>
                                        <h3>Tipo:
                                            <span class="text-gray-600" id="NO_NUM_tipo"></span>
                                        </h3>
                                        <h3>Valor:
                                            <span class="text-gray-600" id="NO_NUM_valor"></span>
                                        </h3>
                                    </div>
                                </div>




                            </div>

                        </div>
                        <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container pt-3">
                            <label class="fs-5 fw-semibold mb-2">subir Documento</label>

                            <input type="file" class="form-control" id="archivo_pdf">
                        </div>

                        <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                            <label class="required fs-5 fw-semibold mb-2">Detalle</label>
                            <textarea id="OR_AsUNTO" class="form-control form-control-solid" placeholder="" name="address1"></textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row fv-plugins-icon-container">
                            <label class="required fs-5 fw-semibold mb-2">Valor</label>
                            <input id="OR_VALOR" type="number" class="form-control form-control-solid" placeholder="0.00" name="address2">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                    </div>

                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button onclick="Nueva_Orden()" type="submit" id="kt_modal_new_address_submit" class="btn btn-primary">
                        <span class="indicator-label">Guardar</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>

                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
                <div></div>
            </div>
            <!--end::Form-->


        </div>
    </div>
</div>

<div class="modal fade" id="Modal_archivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Archivo para subir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="custom-file">
                    <!-- <input type="file" class="form-control" id="Archivo_pfd"> -->
                    <input type="file" class="form-control" id="archivo_deudas" lang="es">
                    <!-- <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label> -->
                </div>
                <div class="modal-body pt-9">
                    <label class="fs-3">Comentario</label>
                    <textarea class="form-control" id="SGO_COMENTARIO_PENDIENTE" rows="4" placeholder="Comentario"></textarea>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" onclick="Actualizar_Deuda()" class="btn btn-primary">Guardar</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="kt_modal_Proveedores" tabindex="-1" style="display: none;" aria-hidden="true">
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

<div class="modal fade" id="kt_modal_Ordenes" tabindex="-1" style="display: none;" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px" data-select2-id="select2-data-351-tkl4">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <div class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#" id="kt_modal_new_address_form">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_new_address_header">
                    <h2>Buscar Ordenes</h2>
                </div>
                <div class="modal-body py-10 px-lg-17" data-select2-id="select2-data-350-hr16">
                    <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Ordenes_Dobra">

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

<div class="modal fade" id="Modal_Deudor" tabindex="-1" role="dialog" aria-labelledby="modalOneLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detalles Proveedor</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1">X</span>
                </div>
                <!--end::Close-->

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="row">
                        <div class="card  card-flush col-4">
                            <!--begin::List widget 1-->
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">Detalle</h3>

                            </div>
                            <div class="card-body pt-5">


                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Codigo</div>
                                    <div class="d-flex align-items-senter">


                                        <span id="Codigo" class="text-gray-900 fw-bolder fs-6"></span>

                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3 pt-4"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Nombre</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Nombre" class="text-gray-900 fw-bolder fs-6"></span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3 pt-4"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Fecha de compra</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Fecha" class="text-gray-900 fw-bolder fs-6"></span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3 pt-4"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2 ">Fecha Vencimiento</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Vencimiento" class="text-gray-900 fw-bolder fs-6"></span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3 pt-4"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2 ">Dias</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Dias" class="text-gray-900 fw-bolder fs-6"></span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3 pt-4"></div>

                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Tipo</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Tipo" class="text-gray-900 fw-bolder fs-6"></span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2 ">Valor</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Valor" class="text-dark fw-bolder fs-5"></span>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2 ">Saldo</div>
                                    <div class="d-flex align-items-senter">

                                        <span id="Saldo" class="text-dark fw-bolder fs-5"></span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card card-flush col-8">
                            <div class="card-header mt-3">
                                <div class="card-title flex-column">
                                    <!-- <h3 class="fw-bold mb-1">Archivos</h3> -->

                                </div>
                            </div>

                            <h1>DETALLES FACTURA</h1>

                            <div class="fv-row ">
                                <table class="table table-border" id="tabla_deudas1">
                                </table>
                            </div>
                            <div class="separator bg-gray-800 separator-dashed my-3 "></div>

                            <div class="fv-row card shadow-sm">
                                <table class="table" id="tabla_deudas2">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="fw-bold">Name</th>
                                            <th colspan="3" class="fw-bold fs-3">CLIENTE</th>
                                            <th colspan="3" class="fw-bold fs-3">ACREEDOR</th>
                                        </tr>
                                        <tr>
                                            <th>Position</th>
                                            <th>Salary</th>
                                            <th>Office</th>
                                            <th>Extn.</th>
                                            <th>E-mail</th>
                                            <th>E-mail</th>
                                            <th>E-mail</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>


                </div>


            </div>

        </div>
    </div>

</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require 'views/footer.php'; ?>
<?php require 'funciones/deudas_js.php'; ?>
<script>

</script>