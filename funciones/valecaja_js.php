<?php

$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos/';
$url_Nueva_Carga = constant('URL') . 'principal/Nueva_Carga/';
$url_Actualizar_Carga = constant('URL') . 'principal/Actualizar_Carga/';
$url_Buscar_Importacion = constant('URL') . 'principal/Buscar_Importacion/';
$url_Buscar_Liquidacion = constant('URL') . 'principal/Buscar_Liquidacion/';

?>

<script>
    // Cambiar las URL por la nueva del archivo //    

    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';
    var url_Nueva_Carga = '<?php echo $url_Nueva_Carga ?>';
    var url_Actualizar_Carga = '<?php echo $url_Actualizar_Carga ?>';
    var url_Buscar_Importacion = '<?php echo $url_Buscar_Importacion ?>';
    var url_Buscar_Liquidacion = '<?php echo $url_Buscar_Liquidacion ?>';
    $("#CHECK_MI").attr("checked", false);
    var ID_CARGA;
    var ID_PEDIDO = "";
    var ID_LIQUIDACION = "";
    // CREAR UNA FUNCION PARA LA TABLA  //   
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    function is_c() {
        let c = $("#CHECK_MI").is(":checked")
        // let someCheckbox = document.getElementById('CHECK_MI').checked;
        if (c == true) {
            $("#SECC_MIAMI").show(50);
            $("#SECC_NORMAL").hide();
        } else {
            $("#SECC_MIAMI").hide();
            $("#SECC_NORMAL").show(50);
        }
    }

    function Cargar_Datos() {
        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {
            console.log('x: ', x);
            Tabla_Cargas(x)
        });
    }
    
    Cargar_Datos()

    function Tabla_Cargas(datos) {
        if ($.fn.dataTable.isDataTable('#Tabla_Cargas')) {
            $('#Tabla_Cargas').DataTable().destroy();
            $('#Tabla_Cargas').empty();
        }
        // $("#Tabla_Pendientes").addClass("table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer");
        var tabla = $('#Tabla_Cargas').DataTable({
            destroy: true,
            data: datos,
            dom: 'Bfrtip',
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            order: [
                [0, "desc"]
            ],
            buttons: [{
                text: 'Refrescar',
                action: function() {
                    Cargar_Datos()
                }
            }, {
                text: `<span class"fw-bolder">Nuevo <i class="bi bi-plus-square-fill fs-2"></i></span>`,
                className: 'btn btn-success',
                action: function() {
                    $("#MODAL_NUEVO").modal("show");
                    $("#N_PROVEEDOR").val("");
                    $("#N_DESCRIPCION").val("");
                }
            }],
            columns: [{
                    data: "Fecha_Creado",
                    title: "fecha_creado",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD hh:mm:ss")
                    }
                }, {
                    data: "proveedor",
                    title: "Proveedor"
                }, {
                    data: "descripcion",
                    title: "descripcion"
                },
                {
                    data: null,
                    title: "Agregar",
                    className: "btn_subir",
                    defaultContent: `
                    <button type="button" class=" btn_subir btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px" data-kt-table-widget-4="expand_row">
                    <i class="bi bi-plus-square-fill fs-2"></i>
                    </button>
                    `,
                    orderable: false,
                    width: 20
                },

            ],
            "createdRow": function(row, data, index) {

                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-7">` + moment(data["Fecha_Creado"]).format("hh:mm") + `</span>
                    </div>
                `;
                $('td', row).eq(0).html(fecha);
                $('td', row).eq(1).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(2).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(3).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(4).addClass("text-gray-800 fw-bolder bg-light-warning");
                $('td', row).eq(5).html(data["texto"]);

            },

            // "footerCallback": function(row, data, start, end, display) {
            //     var api = this.api(),
            //         data;
            //     var intVal = function(i) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,]/g, '') * 1 :
            //             typeof i === 'number' ?
            //             i : 0;
            //     };
            //     var Total = api
            //         .column(4)
            //         .data()
            //         .reduce(function(a, b) {
            //             return (intVal(a) + intVal(b));
            //         }, 0);
            //     let formatter = new Intl.NumberFormat('en-US', {
            //         style: 'currency',
            //         currency: 'USD',
            //     });

            //     $(api.column(0).footer()).html('Total');
            //     $(api.column(4).footer()).html(formatter.format(Total));
            //     //$(api.column(3).footer()).html(format(wedTotal));
            // }
        }).clear().rows.add(datos).draw();

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 1000);

        $('#Tabla_Cargas tbody').on('click', 'td.btn_subir', function(e) {
            e.preventDefault();
            var data = tabla.row(this).data();
            console.log('data: ', data);
            Limpiar();
            $('#MODAL_AGREGAR').modal('show');
            $("#D_PROVEEDOR").text(data["proveedor"]);
            $("#D_DESCRIPCION").text(data["descripcion"]);
            $("#VL_AGENTE_DE_CARGA").val(data["agente_carga"]);
            $("#VL_TIPO_CARGA").val(data["tipo_carga"]);
            $("#VL_LIQUIDACION").val(formatter.format(data["liquidacion"]).split("$")[1]);
            $("#VL_FECHA_TRANSFERENCIA").val(data["fecha_trans"]);
            $("#VL_FECHA_HABIL").val(data["fecha_habil"]);
            $("#VL_ORDEN").val(data["orden"]);
            $("#VL_FECHA_BODEGA").val(data["fecha_bodega"]);
            $("#VL_OBSERVACION").val(data["observacion"]);
            // if (data["pedido_id"] != "" || data["pedido_id"] != null) {
            //     $("#Factura").val(data["pedido_id"]);
            //     $("#BTN_BUSCAR_IMP").click()
            // }
            if (data["liquidacion_id"] != "" || data["liquidacion_id"] != null) {
                $("#liquidacion").val(data["liquidacion_id"]);
                $("#BTN_BUSCAR_LIQ").click()
            }
            ID_CARGA = data["ID"];
            // Tabla_Nueva_info([data]);
        });
    }


    function Nuevo_Carga() {
        let PROVEEDOR = $("#N_PROVEEDOR").val();
        let DESCRIPCION = $("#N_DESCRIPCION").val();

        let N_WR_NUMBER = $("#N_WR_NUMBER").val();
        let N_Shipper = $("#N_Shipper").val();
        let N_PC = $("#N_PC").val();
        let N_Weight = $("#N_Weight").val();
        let N_Volume = $("#N_Volume").val();

        let c = $("#CHECK_MI").is(":checked")
        console.log('c: ', c);

        if (c == true) {
            let param = {
                PROVEEDOR: PROVEEDOR,
                DESCRIPCION: DESCRIPCION
            }
            if (PROVEEDOR == "") {
                Mensaje("Debe ingresar un nombre de provvedor", "", "error");
            } else if (DESCRIPCION == "") {
                Mensaje("Debe ingresar una descripcion", "", "error");

            } else {
                AjaxSendReceiveData(url_Nueva_Carga, param, function(x) {
                    console.log('x: ', x);
                    if (x == true) {
                        $("#MODAL_NUEVO").modal("hide");
                        Cargar_Datos()
                    }
                });
            }
        } else {


        }


    }

    function Guardar_Datos() {
        let VL_LIQUIDACION = $("#VL_LIQUIDACION").val();
        let VL_FECHA_TRANSFERENCIA = $("#VL_FECHA_TRANSFERENCIA").val();
        let VL_FECHA_HABIL = $("#VL_FECHA_HABIL").val();
        let VL_FECHA_BODEGA = $("#VL_FECHA_BODEGA").val();
        let VL_OBSERVACION = $("#VL_OBSERVACION").val();
        let VL_ORDEN = $("#VL_ORDEN").val();

        let param = {
            ID: ID_CARGA,
            ID_LIQUIDACION: ID_LIQUIDACION,
            VL_LIQUIDACION: VL_LIQUIDACION,
            VL_FECHA_TRANSFERENCIA: VL_FECHA_TRANSFERENCIA,
            VL_FECHA_HABIL: VL_FECHA_HABIL,
            VL_FECHA_BODEGA: VL_FECHA_BODEGA,
            VL_OBSERVACION: VL_OBSERVACION,
            VL_ORDEN: VL_ORDEN
        }

        console.log('param: ', param);
        AjaxSendReceiveData(url_Actualizar_Carga, param, function(x) {
            console.log('x: ', x);
            if (x[0] == true && x[1] == true) {
                Mensaje("Datos Guardado", "", "success");
                $('#MODAL_AGREGAR').modal('hide');
                Cargar_Datos();
            } else {
                Mensaje("Error al Guardar", "", "error");
            }
        });
    }

    function Buscar_Importacion() {
        let factura = $("#Factura").val();

        let param = {
            factura: factura
        }
        if (factura != "") {
            AjaxSendReceiveData(url_Buscar_Importacion, param, function(x) {
                console.log('x: ', x);
                if (x.length == 0) {
                    Mensaje("el numero no tienes datos registrados", "", "error")

                } else {
                    ID_PEDIDO = factura;
                    $("#VC_FACTURA").text(x[0]["Referencia"]);
                    $("#VL_VALOR_FACTURA").text(formatter.format(x[0]["Valor"]));
                    $("#VL_PUERTO_EMBARQUE").text(x[0]["PuertoEmbarque"]);
                    $("#VL_FECHA_EMBARQUE").text(x[0]["FechaEmbarque"]);
                    $("#VL_FECHA_ARRIBO").text(x[0]["FechaLlegada"]);
                    $("#VL_INCOTERM").text(x[0]["TipoImport"]);

                }
            })
        }



    }

    function Buscar_Liquidacion() {
        let liquidacion = $("#liquidacion").val();
        let param = {
            liquidacion: liquidacion
        }
        if (liquidacion != "") {
            AjaxSendReceiveData(url_Buscar_Liquidacion, param, function(x) {
                console.log('x: ', x);
                if (x.length == 0) {
                    Mensaje("el numero no tienes datos registrados", "", "error")
                } else {

                    ID_LIQUIDACION = x[0]["ID"];
                    $("#VL_DAU").text(x[0]["DUI"]);
                    $("#VC_FACTURA").text(x[0]["Detalle"]);
                    $("#VL_VALOR_FACTURA").text(formatter.format(x[0]["Valor"]));
                    $("#VL_PUERTO_EMBARQUE").text(x[0]["Puerto"]);
                    $("#VL_FECHA_EMBARQUE").text(moment(x[0]["Embarque"]).format("YYYY-MM-DD hh:mm A"));
                    $("#VL_FECHA_ARRIBO").text(moment(x[0]["fecha_arribo"]).format("YYYY-MM-DD hh:mm A"));
                    $("#VL_INCOTERM").text(x[0]["TipoImport"]);
                    $("#VL_AGENTE_DE_CARGA").text(x[0]["agente_carga"]);
                    $("#VL_TIPO_CARGA").text(x[0]["tipo_carga"]);
                    $("#VL_LIQUIDACION").val(parseFloat(x[0]["liquidacion"]).toFixed(2));
                    $("#VL_FECHA_TRANSFERENCIA").val(moment(x[0]["fecha_trans"]).format("YYYY-MM-DD"));
                    $("#VL_FECHA_HABIL").val(moment(x[0]["fecha_habil_cas"]).format("YYYY-MM-DD"));
                    $("#VL_ORDEN").val(x[0]["orden"]);
                    $("#VL_FECHA_BODEGA").val(moment(x[0]["fecha_bodega"]).format("YYYY-MM-DD"));
                    $("#VL_OBSERVACION").val(x[0]["observacion"]);

                }
            })
        }

    }

    function Limpiar() {
        ID_PEDIDO = "";
        ID_LIQUIDACION = "";
        $("#VC_FACTURA").text("");
        $("#VL_VALOR_FACTURA").text("");
        $("#VL_AGENTE_DE_CARGA").val("");
        $("#VL_PUERTO_EMBARQUE").text("");
        $("#VL_FECHA_EMBARQUE").text("");
        $("#VL_FECHA_ARRIBO").text("");
        $("#VL_TIPO_CARGA").val("");
        $("#VL_LIQUIDACION").val("");
        $("#VL_INCOTERM").text("");
        $("#VL_DAU").text("");
        $("#VL_FECHA_TRANSFERENCIA").val("");
        $("#VL_FECHA_HABIL").val("");
        $("#VL_ORDEN").val("");
        $("#VL_FECHA_BODEGA").val("");
        $("#VL_OBSERVACION").val("");
    }

    $('#Factura').keypress(function(e) {
        if (e.which == 13) {
            //e.preventDefault();
            $("#BTN_BUSCAR_IMP").click();
        }
    });

    $('#liquidacion').keypress(function(e) {
        if (e.which == 13) {
            //e.preventDefault();
            $("#BTN_BUSCAR_LIQ").click();
        }
    });

    function valideKey(evt) {
        // code is the decimal ASCII representation of the pressed key.
        var code = (evt.which) ? evt.which : evt.keyCode;

        if (code == 8) { // backspace.
            return true;
        } else if (code >= 48 && code <= 57) { // is a number.
            return true;
        } else { // other keys.
            return false;
        }
    }

    function VAlidar_Input() {
        $("#VL_LIQUIDACION").on({
            "focus": function(event) {
                $(event.target).select();
            },
            "keyup": function(event) {
                $(event.target).val(function(index, value) {
                    return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                });
            }
        });
    }

    function Mensaje(texto1, texto2, icon) {
        Swal.fire(
            texto1,
            texto2,
            icon
        )
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