<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>
<style>
    .icon {
        animation: notification 2s infinite;
    }

    @keyframes notification {

        5% {
            transform: rotate(10deg);
        }

        10% {
            transform: rotate(-10deg);
        }

        15% {
            transform: rotate(10deg);
        }

        20% {
            transform: rotate(0deg);
        }


    }
</style>

<div class="card">
    <div id="SECCION_TODO">

        <div class="col-12 p-12">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Pendientes">

                    <tfoot align="center">
                        <tr>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
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
<div id="kt_app_content" class="app-content flex-column-fluid mt-55">
    <div class="col-12">
        <div class="card">
            <!-- <div class="card-header">
                <h3 class="card-title"></h3>
            </div> -->
            <div class="card-body">
                <div id="SECC_SCROLL">

                    <h3 class="fw-bold text-gray-800 mb-5">Empresa</h3>
                    <ul class="nav nav-pills nav-pills-custom" role="tablist">
                        <li class="nav-item me-3 me-lg-6" role="presentation">
                            <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-primary btn-active-light-primary w-100 px-4 ch active" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                                <input class="btn-check" type="radio" name="asd" value="0" id="customSwitch3">
                                <span class="fs-4 fw-bold d-block">Cartimex</span>
                            </label>
                        </li>
                        <li class="nav-item me-3 me-lg-6" role="presentation">
                            <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-warning btn-active-light-warning w-100 px-4 ch" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                                <input class="btn-check" type="radio" name="asd" value="0" id="customSwitch4">
                                <span class="fs-4 fw-bold d-block">Computron</span>
                            </label>
                        </li>
                        <li class="nav-item me-3 me-lg-6">
                            <!-- <select name="" id="TIPOS_DOC_CARTIMEX" class="form-select">
                            <option value="3">TODAS</option>
                            <option value="1">DEUDAS PROVEEDORES</option>
                            <option value="2">ORDENES</option>
                            <option value="4">VALES DE CAJA</option>
                        </select>
                        <select name="" id="TIPOS_DOC_COMPUTRON" class="form-select" style="display: none;">
                            <option value="3">TODAS</option>
                            <option value="1">VALES DE CAJA</option>

                        </select> -->
                        </li>
                        <li class="nav-item me-3 me-lg-6">

                            <!-- <h2 class="text-danger">Pago Prioritario
                            <i class="bi bi-circle-fill text-success fs-1"></i>
                        </h2> -->
                        </li>

                    </ul>

                    <h4>Tipo de Documento</h4>
                    <ul class="nav nav-pills nav-pills-custom" role="tablist">
                        <li class="nav-item me-3 me-lg-6" role="presentation">
                            <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-danger btn-active-light-danger w-100 px-4 ch active" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                                <input class="btn-check" type="radio" name="asd" value="0" id="sw_DP">
                                <span class="fs-4 fw-bold d-block">Deudas Proveedores Prioritarias
                                    <i class="bi bi-arrow-clockwise fs-2"></i>
                                </span>
                            </label>
                        </li>
                        <!-- <li class="nav-item me-3 me-lg-6" role="presentation">
                        <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-success btn-active-light-success w-100 px-4 ch" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                            <input class="btn-check" type="radio" name="asd" value="0" id="sw_DV">
                            <span class="fs-4 fw-bold d-block">Deudas Proveedores Vencidas</span>
                        </label>
                    </li> -->
                        <li class="nav-item me-3 me-lg-6" role="presentation">
                            <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-success btn-active-light-success w-100 px-4 ch" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                                <input class="btn-check" type="radio" name="asd" value="0" id="sw_O">
                                <span class="fs-4 fw-bold d-block">Ordenes (Varios)
                                    <i class="bi bi-arrow-clockwise fs-2"></i>
                                </span>
                            </label>
                        </li>
                        <li class="nav-item me-3 me-lg-6" role="presentation">
                            <label class="btn bg-light btn-color-gray-600 btn-active-text-gray-800 border border-2 border-gray-100 border-active-success btn-active-light-success w-100 px-4 ch" id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1" aria-selected="true" role="tab">
                                <input class="btn-check" type="radio" name="asd" value="0" id="sw_V">
                                <span class="fs-4 fw-bold d-block">Vales de Caja
                                    <i class="bi bi-arrow-clockwise fs-2"></i>
                                </span>
                            </label>
                        </li>
                    </ul>
                    <div id="SECC_TABLA_RESUMEN">
                        <div class="table-responsive">
                            <table class="table align-middle table-striped table-row-dashed fs-6 gy-3 dataTable no-footer" id="Tabla_Resumen">

                            </table>
                        </div>
                    </div>
                    <div id="SECC_TABLA_DEUDAS">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed table-hover fs-6 gy-3 dataTable no-footer" id="Tabla_Deudas">

                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="Modal_comentario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Comentario</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <h4>Datos Ingresados</h4>
                <h4>Valor:
                    <span id="VALOR"></span>
                </h4>
                <h4>Banco:
                    <span id="BANCO"></span>
                </h4>
                <h4>Tipo Pago:
                    <span id="T_PAGO"></span>
                </h4>
                <div id="SECC_F" style="display:none">
                    <h4>Fecha:
                        <span id="FECHA"></span>
                    </h4>
                </div>

                <textarea name="" id="COMENTARIO" class="form-control" readonly></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="Modal_Agruapdo">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Seleccione datos</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="row g-9 mb-8">
                    <!--begin::Col-->
                    <div class="col-md-12 fv-row fv-plugins-icon-container">
                        <label class="required fs-6 fw-semibold mb-2">Banco</label>
                        <div id="SET_SELECT">

                        </div>
                    </div>
                    <div class="col-md-12 fv-row fv-plugins-icon-container">
                        <label class="required fs-6 fw-semibold mb-2">Tipo de pago</label>
                        <div id="SET_Tipo">

                        </div>
                    </div>
                    <div class="col-md-12 fv-row fv-plugins-icon-container">
                        <label class="required fs-6 fw-semibold mb-2">Fecha de pago</label>
                        <input disabled type="date" id="AGR_FECHA" name="birthday" value="">

                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button id="AGR_GUARDAR" type="button" class="btn btn-light-success" data-bs-dismiss="modal">Guardar</button>

                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="Modal_Deudor" tabindex="-1" role="dialog" aria-labelledby="modalOneLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
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
                                <div class="separator separator-dashed my-3"></div>

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



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php require 'views/footer.php'; ?>
<?php require 'funciones/informe_js.php'; ?>

<script>
    $("#customSwitch3").on('change', function(x) {
        console.log('x: ', x);

        $("#TIPOS_DOC_COMPUTRON").hide();
        $("#TIPOS_DOC_CARTIMEX").show()
    });
    $("#customSwitch4").on('change', function(x) {
        console.log('x: ', x);
        $("#TIPOS_DOC_COMPUTRON").show();
        $("#TIPOS_DOC_CARTIMEX").hide()
    });
    // $('#BTN_AYUDA').popover({
    //     html: true,mfun
    //     trigger: 'focus',
    //     content: `<h5>Escribir el codigo del producto en el campo 
    //                 <code>Codigo</code>, 
    //                 luego presionar <b>ENTER<b>, los campos no pueden estar vacios,
    //                 y lo campos de <code>PROTECCION y FECHAS</code> son obligatorios </h5>`,
    //     template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    // });
    Cargar_Informe();
    setTimeout(() => {
        Cargar_Deudas(1);

    }, 800);
    let mes = moment().startOf("month").format("YYYY-MM-DD")
    let hoy = moment().format("YYYY-MM-DD")

    function BTN_GUARDAR() {
        Guardar_DATOS_S();
    }
    var fl = $("#Fecha_desde").flatpickr({
        dateFormat: "Y-m-d",
        defaultDate: [mes]
    });
    var fl2 = $("#Fecha_hasta").flatpickr({
        dateFormat: "Y-m-d",
        defaultDate: [hoy]
    });
</script>