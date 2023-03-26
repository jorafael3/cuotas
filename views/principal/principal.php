<?php

require 'views/header.php';

$usuario =  $_SESSION["usuario"];
?>


<div id="kt_app_content" class="app-content flex-column-fluid">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Actualizar Saldos</h3>
                <!-- <div class="card-toolbar">
                    <button onclick="BTN_NUEVO_()" type="button" class="btn btn-sm btn-dark">
                        Nuevo Proteccion <i class="bi bi-file-earmark-plus fs-2"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-backdrop="true" data-target="#Edit_Proteccion">
                        Editar Proteccion <i class="bi bi-pencil-square fs-2"></i>
                    </button>
                </div> -->

            </div>
            <div class="card-body">
                <h1>Crear / Actualizar Bancos</h1>
                <h2 id="PRT_TEXT_EDIT" class="h3 text-danger mt-2"></h2>
                <div class="row">
                    <div id="SECCION_TODO">
                        <div class="col-12 mt-6">
                            <button onclick="fnExcelReport()">Export HTML Table to EXCEL</button>
                            <button id="BTN_NUEVA_LINEA" onclick=" PR_AGREGAR_NUEVA_COLUMNA()" class="btn btn-info btn-sm ">Nueva Linea +<i data-feather="plus-circle"></i></button>
                            <h3 id="NUM_PROT"></h3>
                            <hr>
                            <div class="table-responsive">
                                <table id="TABLA_PROTECCION_PRECIO" class="" style="width:100%">
                                    <thead id="TABLA_PROTECCION_PRECIO_THEAD" class="pb-5">
                                    </thead>

                                    <tbody id="TABLA_PROTECCION_PRECIO_BODY">
                                    </tbody>
                                    <footer>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <th class="h3" id="total1"></th>
                                            <th class="h3" id="total2"></th>
                                            <th class="h3" id="total3"></th>
                                        </tr>
                                    </footer>
                                </table>
                            </div>
                            <button id="BTN_GUARDAR_" onclick=" BTN_GUARDAR()" class="btn btn-dark btn-sm mt-8">Guardar<i class="bi bi-save2 fs-2"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------------------------------------------------------------------------- -->
<!------------------------------------------------------------------------------------------------------------- -->

<!-- <div class="col-12">
    <div class="card shadow-sm">
       
        <div class="card-body">
            <h1>COMPUTRON</h1>
            <h2 id="PRT_TEXT_EDIT" class="h3 text-danger mt-2"></h2>
            <div class="row">
                <div id="SECCION_TODO">
                    <div class="col-12 mt-3">
                        <button id="BTN_NUEVA_LINEA" onclick=" PR_AGREGAR_NUEVA_COLUMNA_COMPUTRON()" class="btn btn-info btn-sm ">Nueva Linea +<i data-feather="plus-circle"></i></button>
                        <h3 id="NUM_PROT"></h3>
                        <hr>
                        <div class="table-responsive">
                            <table id="TABLA_PROTECCION_PRECIO_COMPUTRON" class="" style="width:100%">
                                <thead id="TABLA_PROTECCION_PRECIO_CUERPO" class="pb-5">
                                </thead>
                                <tbody id="TABLA_PROTECCION_PRECIO_CABEZERA">
                                </tbody>
                            </table>
                        </div>
                        <button id="BTN_GUARDAR_" onclick=" Guardar_DATOS_S_C()" class="btn btn-dark btn-sm mt-8">Guardar<i class="bi bi-save2 fs-2"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<script src="<?php echo constant('URL') ?>funciones/parsetable.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<?php require 'views/footer.php'; ?>
<?php require 'funciones/principal_js.php'; ?>

<script>
    // NUEVO_();
    // $('#BTN_AYUDA').popover({
    //     html: true,
    //     trigger: 'focus',
    //     content: `<h5>Escribir el codigo del producto en el campo 
    //                 <code>Codigo</code>, 
    //                 luego presionar <b>ENTER<b>, los campos no pueden estar vacios,
    //                 y lo campos de <code>PROTECCION y FECHAS</code> son obligatorios </h5>`,
    //     template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    // });
    function fnExcelReport()
  {
      var tab_text="<table border='2px'><tr style='font-size: 11px !important; font-family: Calibri;'>";
      var textRange; var j=0;
      tab = document.getElementById('TABLA_PROTECCION_PRECIO'); // id of table
  
      for(j = 0 ; j < tab.rows.length ; j++) 
      {     
          tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
          console.log('*****************************************');
          let a   = tab.rows[j];
          a = $(a)[0];
          console.log('tab_text: ', a);
          //tab_text=tab_text+"</tr>";
      }
  
      tab_text=tab_text+"</table>";
      tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");
      tab_text= tab_text.replace(/<img[^>]*>/gi,"");
      tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); 
  
    //   var ua = window.navigator.userAgent;
    //   var msie = ua.indexOf("MSIE "); 
    //   var link = document.createElement("a");
    //   link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
    //   link.download = "Bancos.xlsx";
    //   link.click();

  }
    function BTN_GUARDAR() {
        Guardar_DATOS_S();
    }

  
</script>