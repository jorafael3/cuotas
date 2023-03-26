<?php


$url_tablasignar = constant('URL') . 'asignar/mostar_asignado/';
$url_tablamostrar = constant('URL') . 'asignar/mostar_asignado/';
$ulr_Actividad_Ingresada = constant('URL') . 'asignar/mostrar_ingresado';
$Guardar_datos = constant('URL') . 'asignar/Guardar_Datos';
$cargar_combo = constant('URL') . 'cargar/cargar_combo/';
$mostar_marca = constant('URL') . 'asignar/mostar_marca/';
?>

<script>
    // Cambiar las URL por la nueva del archivo //    
    var $url_tablasignar = '<?php echo $url_tablasignar ?>';
    var $url_tablamostrar = '<?php echo $url_tablamostrar ?>';
    var $ulr_Actividad_Ingresada = '<?php echo $ulr_Actividad_Ingresada ?>';
    var $Guardar_datos = '<?php echo $Guardar_datos ?>';
    var $cargar_combo = '<?php echo $cargar_combo ?>';
    var Documento;
    var $mostar_marca = '<?php echo $mostar_marca ?>';


    function Mensaje(texto1, texto2, icon) {
        Swal.fire(
            texto1,
            texto2,
            icon
        )
    }

    $("body").on("click", ".btn_remove", function() {
        Swal.fire({
            title: 'Antes de Continuar',
            text: "Desea eliminar esta linea",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest("tr").remove();

            }
        });
    });

    //--------------------------CREAR UNA FUNCION-----------------------------//   

    function Onload() {
        AjaxSendReceiveData($url_tablasignar, [], function(respuesta) {
            tablasignar(respuesta);
            console.log('respuesta: ', respuesta);
        });
    }
    Onload()

    function tablasignar(datos) {

        $("#tablasignar").empty();

        // if ($.fn.dataTable.isDataTable('#tablasignar')) {
        //     $('#tablasignar').DataTable().destroy();
        //     $('#tablasignar').empty();
        // }

        var tablaproveedores = $('#tablasignar').DataTable({
            destroy: true,
            data: datos,
            dom: 'Brtip',
            paging: true,
            // pagelenght :30,
            buttons: [],
            columns: [{
                    data: "Fecha",
                    title: "Fecha de Creacion",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    }
                },
                {
                    data: "Valor",
                    title: "valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                },
                {
                    data: "Proveedor",
                    title: "Proveedor"
                }, {
                    data: "Marca",
                    title: "Marca"
                },
                {
                    data: "Tipo",
                    title: "Tipo"
                },
                {
                    data: "Documento",
                    title: "Documento"
                },
                {
                    data: null,
                    title: "Detalles",
                    className: "btn_detalles",
                    defaultContent: '<button  type="button" class="btn_detalles btn btn-warning"><i class="bi bi-eye-fill"></i></button>',
                    orderable: "",
                    width: 20
                },
            ],

            "createdRow": function(row, data, index) {
                $('td', row).eq(0).addClass("text-dark fs-5  fw-bolder");
                $('td', row).eq(1).addClass("text-dark fs-5 fw-bolder");
                $('td', row).eq(3).addClass("text-dark bg-light-success fs-5 fw-bolder");
                $('td', row).eq(5).addClass("text-dark fs-5 ");
                $('td', row).eq(6).addClass("text-dark fs-5 ");

                // $('td', row).eq(4).addClass("text-dark fs-5 fw-bolder")       
            }
        });

        $('#tablasignar').on('click', 'td.btn_detalles', function(respuesta) {
            respuesta.preventDefault();
            var data = tablaproveedores.row(this).data();
            console.log('data: ', data);

            documento = data['Documento'];

            $("#Modal_asignar").modal("show");
            
            $("#VL_NUMERO_DOCUMENTO").text(data["Documento"])
            $("#VL_FECHA_CREACION").text(data["Fecha"])
            $("#VL_Valor").text(data["Valor"])
            $("#VL_Proveedor").text(data["Proveedor"])

            let parametros = {
                documento: data["Documento"]
            }

            Cargar_Actividades(parametros);

        })


    }
    // ------------------------------- Segunda tabla modal -------------------------------------------// 

    // function Cargar_Actividades(param) {

    //     AjaxSendReceiveData($mostar_marca, param, function(x) {

    //         console.log('aqui: ', x);

    //         Crear_Lineas_Actividades(x)

    //         console.log('x: ', x);

    //     });
    // }


    function Cargar_Actividades(param) {

        AjaxSendReceiveData($mostar_marca, param, function(x) {

            console.log('aqui: ', x);

            Crear_Lineas_Actividades(x)

            console.log('x: ', x);

        });
    }

    function Crear_Lineas_Actividades(data) {

        $("#TABLA_PROTECCION_PRECIO_THEAD").empty();
        $("#TABLA_PROTECCION_PRECIO_BODY").empty();

        var thead = `

                <tr style="font-weight: bold;">
                    <td style="width: 80px;">Actividad</td>
                    <td style="width: 80px;">Valor</td>
                </tr>
                    `;

        $("#TABLA_PROTECCION_PRECIO_THEAD").append(thead);

        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        });

        data.map(function(x, i) {

            console.log(x.Marca);

            let i1 = "input_con_";
            let i2 = "input_dis_";

            var tabla = `
            
                <tr style="font-size:12px">
                        <td>
                            <input style="font-size:12px" type="text" id="input_con_` + i + `"
                            class="form-control form-control-solid " disabled value="` + x.Marca + `">
                        </td>
                        <td>
                            <input style="font-size:12px" type="text" min="0" id="input_dis_` + i + `"
                            step="0.1" class="form-control form-control-solid " value="` + formatter.format(x.Valor).split("$")[1] + `">
                        </td>
                        <td style='display:none;'>0
                        </td>
                        <td style='display:none;'>` + x.ID + `
                        </td>
                </tr>
                `;

            $("#TABLA_PROTECCION_PRECIO_BODY").append(tabla);
        });
    }



    function crear_tabla() {

        AjaxSendReceiveData($cargar_combo, [], function(respuesta) {
            console.log('respuesta: ', respuesta);
            var vacio = ''
            respuesta.map(function(e) {
                vacio = vacio + '<option value=' + e.ID_Marca + '>' + e.Concepto + '</option>'
            })

            var string = ` 
            <tr>
            <td> <select class="form-select" aria-label="Default select example id="Actividad"">
            <option value="">Seleccione</option>
            ` + vacio + `
            </select></td>
            <td> <input type="number" class="form-control" id="valor" aria-describedby="emailHelp" "></td>
            <td style='display:none;'>1
            </td>
            <td style='display:none;'>
            </td>
            </tr>`
            $('#TABLA_PROTECCION_PRECIO_BODY').append(string);
        });
    }


    function BTN_GUARDAR() {

        DATA_TO_SEND_C(function(respuesta) {

            console.log('respuesta: ', respuesta);

            AjaxSendReceiveData($Guardar_datos, respuesta, function(respuesta) {

                console.log('respuesta: ', respuesta);

                if (respuesta > 0) {

                    Swal.fire(
                        'Exito',
                        'Datos Guardados',
                        'success'
                    );
                    
                } else {

                    Swal.fire(
                        'Error',
                        'Error al guardar',
                        'error'
                    );

                }

            })
        })
    }


    function DATA_TO_SEND_C(callback) {
        
        var tabla = document.getElementById("TABLA_PROTECCION_PRECIO");
        var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO tr').length;

        var ARRAY_DATA = [];
        for (var i = 1; i < tbl; i++) {
            var Actividad;
            var Valor;
            let tipo = (tabla.rows[i].cells[2].innerText).trim();
            try {
                Actividad = tabla.rows[i].cells[0].getElementsByTagName("select")[0].value;
            } catch (error) {
                Actividad = tabla.rows[i].cells[0].children[0].value;
            }
            Valor = tabla.rows[i].cells[1].children[0].value;
            let ID = (tabla.rows[i].cells[3].innerText).trim();
            var b = {

                Documento: documento,
                Actividad: Actividad,
                Valor: Valor,
                tipo: tipo,
                ID: ID,
            }
            ARRAY_DATA.push(b);
        }

        callback(ARRAY_DATA);
    }

    function AjaxSendReceiveData(url, data, callback) {
        var xmlhttp = new XMLHttpRequest();
        $.blockUI({
            message: '<div class="d-flex justify-content-center align-items-center"><p class="mr-50 mb-0">Cargando ...</p> <div class="spinner-grow spinner-grow-sm text-white" role="status"></div> </div>',
            css: {
                backgroundColor: 'transparent',
                color: '#fff',
                border: '0'
            },
            overlayCSS: {
                opacity: 0.5
            }
        });

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                data = JSON.parse(data);
                callback(data);
            }
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = this.responseText;
                data = JSON.parse(data);
                callback(data);
            }
        }
        xmlhttp.onload = () => {
            $.unblockUI();
            // 
        };
        xmlhttp.onerror = function() {
            $.unblockUI();
        };
        data = JSON.stringify(data);
        xmlhttp.open("POST", url, true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send(data);

    }
    
</script>