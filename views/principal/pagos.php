<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>

<div id="kt_app_content" class="app-content flex-column-fluid mt-55">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pagos por Realizar</h3>
            </div>

            <div class="card-body">
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
                        <select name="" id="TIPOS_DOC_CARTIMEX" class="form-select">
                            <option value="3">TODAS</option>
                            <option value="1">DEUDAS PROVEEDORES</option>
                            <option value="2">ORDENES</option>
                            <option value="4">VALES DE CAJA</option>
                        </select>
                        <select name="" id="TIPOS_DOC_COMPUTRON" class="form-select" style="display: none;">
                            <option value="3">TODAS</option>
                            <option value="1">VALES DE CAJA</option>
                        </select>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Deudas">
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed table-striped fs-6 gy-3 dataTable no-footer" id="Tabla_Cheques">
                    </table>
                </div>

            </div>
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
                    <input type="file" class="form-control" id="Archivo_pfd" lang="es">
                    <!-- <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label> -->

                </div>
            </div>
            <div class="modal-footer">

                <button type="button" onclick="cambiarEstado()" class="btn btn-primary">Guardar</button>

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

                <textarea name="" id="COMENTARIO" class="form-control"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php require 'views/footer.php'; ?>
<?php require 'funciones/pagos_js.php'; ?>

<script>
    // $('#BTN_AYUDA').popover({
    //     html: true,
    //     trigger: 'focus',
    //     content: `<h5>Escribir el codigo del producto en el campo 
    //                 <code>Codigo</code>, 
    //                 luego presionar <b>ENTER<b>, los campos no pueden estar vacios,
    //                 y lo campos de <code>PROTECCION y FECHAS</code> son obligatorios </h5>`,
    //     template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    // });
    // Cargar_Informe();
    Cargar_Deudas(1);
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