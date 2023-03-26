<?php

require 'views/header.php';
?>

<div class="col-12">
    <div class="card bg-light shadow-sm">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold text-dark">Vale de Caja Nuevo</span>
            </h3>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-7 gy-3 dataTable no-footer" id="Tabla_Cargas">

                </table>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<?php require 'views/footer.php'; ?>
<?php require 'funciones/cargar_js.php'; ?>

<script>
    var fl2 = $(".fl_fecha").flatpickr({
        // minDate: "today"
    });
    var fl22 = $("#Fecha_hasta_2").flatpickr({

    });
</script>