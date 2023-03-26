<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>

<div class="card">
  <h2 class="card-header pt-5" id="formulario">Listado de Documentos por Asignar</h2>
  <div class="card-body">


    <div class="table-responsive">
      <table class="table table-striped" id="tablasignar">
      </table>
    </div>
  </div>

</div>



<!-- Modal -->
<div class="modal fade" id="Modal_asignar" tabindex="-1" role="dialog" aria-labelledby="modalOneLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Actividad de Marca</h3>
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <span class="svg-icon svg-icon-1">X</span>
        </div>
        <!--end::Close-->
      </div>
      <div class="modal-body">
        <div class="form-group col-6 pt-4">
          <div class="d-flex flex-stack">
            <div class="text-gray-700 fw-semibold fs-6 me-2">NUMERO DE DOCUMENTO</div>
            <div class="d-flex align-items-senter">
              <span id="VL_NUMERO_DOCUMENTO" class="text-gray-900 fw-bolder fs-6"></span>
            </div>
          </div>
          <div class="separator separator-dashed my-3"></div>
          <div class="d-flex flex-stack">
            <div class="text-gray-700 fw-semibold fs-6 me-2">FECHA CREACION </div>
            <div class="d-flex align-items-senter">
              <span id="VL_FECHA_CREACION" class="text-gray-900 fw-bolder fs-6"></span>
            </div>
          </div>
          <div class="separator separator-dashed my-3"></div>
          <div class="d-flex flex-stack">
            <div class="text-gray-700 fw-semibold fs-6 me-2">Valor</div>
            <div class="d-flex align-items-senter">
              <span id="VL_Valor" class="text-gray-900 fw-bolder fs-6"></span>
            </div>
          </div>
          <div class="separator separator-dashed my-3"></div>
          <div class="d-flex flex-stack">
            <div class="text-gray-700 fw-semibold fs-6 me-2">PROVEEDOR</div>
            <div class="d-flex align-items-senter">
              <span id="VL_Proveedor" class="text-gray-900 fw-bolder fs-6"></span>
            </div>
          </div>
        </div>
        <button id="BTN_NUEVA_LINEA" onclick="crear_tabla()" class="btn btn-info btn-sm ">Nueva Linea +</button>
        <h3 id="NUM_PROT"></h3>
        <hr>
        <div class="col-12 mt-6">
          <div class="table-responsive">
            <table id="TABLA_PROTECCION_PRECIO" class="" style="width:100%">
              <thead id="TABLA_PROTECCION_PRECIO_THEAD" class="pb-5">
                <tr>
                  <td>Actividad</td>
                  <td>valor</td>
                </tr>
              </thead>
              <tbody id="TABLA_PROTECCION_PRECIO_BODY">
              </tbody>
            </table>
            <button id="BTN_GUARDAR_" onclick="BTN_GUARDAR()" class="btn btn-dark btn-sm mt-8">Guardar<i class="bi bi-save2 fs-2"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require 'views/footer.php'; ?>
<?php require 'funciones/asignar_js.php'; ?>
<script>

</script>