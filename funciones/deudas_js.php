<?php

$url_Buscar_Proveedor = constant('URL') . 'deudas/Buscar_Proveedor/';
$url_Actualizar_Deuda = constant('URL') . 'deudas/Actualizar_Deuda/';
$url_Nueva_Orden = constant('URL') . 'deudas/Nueva_Orden/';
$url_Cargar_Ordenes = constant('URL') . 'deudas/Cargar_Ordenes/';
$url_Validar_Proveedor = constant('URL') . 'deudas/Validar_Proveedor/';
$url_Validar_Cuenta = constant('URL') . 'deudas/Validar_Cuenta/';
$url_Anular_Orden = constant('URL') . 'deudas/Anular_Orden/';
$url_Detalles_Deudor = constant('URL') . 'informe/Detalles_Deudor/';
$url_Buscar_Orden = constant('URL') . 'deudas/Buscar_Orden/';
$url_Guardar_Documento = constant('URL') . 'deudas/Guardar_Documento/';
$url_Guardar_Documento_deuda = constant('URL') . 'deudas/Guardar_Documento_deudas/';





?>

<script>
    var url_Buscar_Proveedor = '<?php echo $url_Buscar_Proveedor ?>';
    var url_Actualizar_Deuda = '<?php echo $url_Actualizar_Deuda ?>';
    var url_Nueva_Orden = '<?php echo $url_Nueva_Orden ?>';
    var url_Cargar_Ordenes = '<?php echo $url_Cargar_Ordenes ?>';
    var url_Validar_Proveedor = '<?php echo $url_Validar_Proveedor ?>';
    var url_Validar_Cuenta = '<?php echo $url_Validar_Cuenta ?>';
    var url_Anular_Orden = '<?php echo $url_Anular_Orden ?>';
    var url_Detalles_Deudor = '<?php echo $url_Detalles_Deudor ?>';
    var url_Buscar_Orden = '<?php echo $url_Buscar_Orden ?>';
    var url_Guardar_Documento = '<?php echo $url_Guardar_Documento ?>';
    var url_Guardar_Documento_deuda = '<?php echo $url_Guardar_Documento_deuda ?>';



    var RUC_PROVEEDOR;
    var PROVEEDOR_ID;
    var ID_PROVEEDOR;
    var TIPO_BUSQUEDA;
    var CODIGO_PROV_NU_ORDEN;
    var CODIGO_CUENTA_NU_ORDEN;
    var CODIGO_CUENTA_NOMBRE_NU_ORDEN;
    var ORDEN_DOBRA_ID = "";
    var ARRAY_DATOS_PROV_DEUDA = [];

    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',

    });

    function Mensaje(t1, t2, ic) {
        Swal.fire(
            t1,
            t2,
            ic
        )
    }

    function Buscar_Proveedor() {
        let ruc = $("#Proveedor").val();
        RUC_PROVEEDOR = ruc;
        let param = {
            ruc: RUC_PROVEEDOR,
            tipo: 1

        }


        if (ruc == "") {
            Mensaje("Debe Escribir el nombre o ruc del proveedor", "", "error");

        } else {
            AjaxSendReceiveData(url_Buscar_Proveedor, param, function(x) {

                let DATOS = x[0];
                let DEUDAS = x[1];
                RUC_PROVEEDOR = DATOS[0]["Código"];
                if (DEUDAS == "00000") {
                    $("#kt_modal_Proveedores").modal("show");
                    Tabla_Lista_Proveedores(DATOS);
                    TIPO_BUSQUEDA = 1;
                } else {
                    if (DEUDAS.length == 0) {
                        Mensaje("El proveedor no tiene deudas pendientes", "", "info")
                    } else {
                        PROVEEDOR_ID = DATOS[0]["ID"]
                        $("#PR_NOM").text(DATOS[0]["Nombre"]);
                        $("#S_NOm").show(100);
                        Tabla_Deudas(DEUDAS);
                    }

                }
            });
        }
    }

    function Tabla_Lista_Proveedores(data) {
        $('#Tabla_Proveedores').empty();
        var table = $('#Tabla_Proveedores').DataTable({
            destroy: true,
            data: data,
            dom: 'frtip',
            // responsive: true,
            deferRender: true,
            // scrollY: '40vh',
            // scrollCollapse: true,

            columns: [

                {
                    data: "Código",
                    title: "Codigo",

                },
                {
                    data: "Nombre",
                    title: "Nombre",

                },
                {
                    data: "Ruc",
                    title: "Ruc",

                },
                {
                    data: null,
                    title: "Seleccionar",
                    className: "btn_confirmar",
                    defaultContent: `
                    <button class="btn btn-success btn-sm btn_confirmar"> + 
                    </button>
                    `,
                    // orderable: false,
                    width: 20
                }
            ],

            "createdRow": function(row, data, index) {
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Proveedores tbody').on('click', 'td.btn_confirmar', function(e) {
            var data = table.row(this).data();


            RUC_PROVEEDOR = data["Código"];
            ID_PROVEEDOR = data["ID"];
            if (TIPO_BUSQUEDA == 1) {

                let param = {
                    ruc: data["ID"],
                    tipo: 2
                }



                AjaxSendReceiveData(url_Buscar_Proveedor, param, function(x) {


                    $("#kt_modal_Proveedores").modal("hide");
                    let DATOS = x[0];
                    let DEUDAS = x[1];
                    // if (DEUDAS == "00000") {
                    //     $("#kt_modal_Proveedores").modal("show");
                    //     Tabla_Lista_Proveedores(DATOS)
                    // } else {
                    if (DEUDAS.length == 0) {
                        Mensaje("El proveedor no tiene deudas pendientes", "", "info")
                    } else {
                        PROVEEDOR_ID = data["ID"]
                        $("#PR_NOM").text(data["Nombre"]);
                        $("#S_NOm").show(100);
                        Tabla_Deudas(DEUDAS);
                    }
                    // }
                });
            } else {
                CODIGO_PROV_NU_ORDEN = data["ID"];
                CODIGO_CUENTA_NU_ORDEN = data["Código"];
                CODIGO_CUENTA_NOMBRE_NU_ORDEN = data["Nombre"];
                $("#kt_modal_Proveedores").modal("hide");
                $("#SECC_NO_NOMB_PROV").show();
                $("#NO_NOMB_PROV").text(data["Nombre"]);
                $("#SECCION_NO_PRO").show();

            }
        });
    }

    function BTN_ORDEN() {
        $('#OR_AsUNTO').val("");
        $('#OR_VALOR').val("");
        $('#OR_PROV').val("");
        $('#OR_CUENTA').val("");
        $('#NO_NUM_ORDEN').val("");
        $('#NO_NUM_Nombre').val("");
        $('#NO_NUM_tipo').val("");
        $('#NO_NUM_valor').val("");
        $('#archivo_pdf').val("");
        $("#CH_AGR_ORDEN").prop("checked", false);
        $('#kt_modal_new_address').modal('show');
        TIPO_BUSQUEDA == 2
        $("#SECCION_NO_PRO").hide();
        $("#SECC_NO_NOMB_PROV").hide();
        $("#SECCION_NO_PRO").hide();

        CODIGO_PROV_NU_ORDEN = -1;
    }

    function Tabla_Deudas(data) {
        var format = $.fn.dataTable.render.number(',', '.', 2, '$');

        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function(data, row, column, node) {
                        //check if type is input using jquery
                        if (column == 5) {
                            var elm = $(node).text()
                            //val = val[0];
                            return elm;
                            //return $(data).is("td") ? $(data).text() : data
                        } else {
                            return node.firstChild.tagName === "INPUT" ?
                                node.firstElementChild.value :
                                data;
                        }
                    }
                },
                //columns: cl
            }
        };

        $('#Tabla_Deudas').empty();
        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'rtip',
            // responsive: true,
            deferRender: true,
            buttons: [$.extend(!0, {}, buttonCommon, {
                extend: "excel",
                title: "TextoSucursal",
                messageTop: "Datos de Empleados Sucursal: ",
                // exportOptions: {
                //     columns: [0, 1]
                // }
            })],
            scrollY: '70vh',
            scrollCollapse: true,
            paging: false,
            // info: false,
            "order": [
                [10, "asc"],
                [5, "asc"]
            ],

            columns: [{
                    data: "Fecha",
                    title: "Fecha Deuda",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    }
                },
                {
                    data: "Número",
                    title: "Número",
                    render: function(data, type, rw) {
                        if (type == "display") {
                            let ARR_D = [rw["Tipo"], rw["Número"], rw["Empresa"], rw.Saldo];

                            return `<a href="#!" onclick="datos_Deudor('` + ARR_D + `')" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (rw["Número"]) + `</a>`

                        } else {
                            return data;
                        }
                    }

                },
                {
                    data: "Tipo",
                    title: "Tipo",

                },
                {
                    data: "Detalle",
                    title: "Detalle",

                },
                {
                    data: "Vencimiento",
                    title: "Vencimiento",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    }
                },
                {
                    data: "Vencimiento",
                    title: "Dias Vencido",
                    render: function(x) {
                        let hoy = moment().format("YYYY-MM-DD");
                        let ven = moment(x).format("YYYY-MM-DD");
                        var date_1 = new Date(hoy);
                        var date_2 = new Date(ven);

                        var day_as_milliseconds = 86400000;
                        var diff_in_millisenconds = date_2 - date_1;
                        var diff_in_days = diff_in_millisenconds / day_as_milliseconds;
                        if (diff_in_days < 0) {
                            return `
                                <span class="text-danger">` + diff_in_days + `</span>
                            `;
                        } else {
                            return `
                                <span class="text-primary">` + diff_in_days + `</span>
                            `;
                        }
                    }
                },
                {
                    data: "Valor",
                    title: "Valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }, {
                    data: "Saldo",
                    title: "Saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }, {
                    data: null,
                    title: "Abono",
                    className: "dt-center  input-sas",
                    width: 150,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;

                            if (row.SGO_ABONO == null) {
                                data = '<input type="number" min="0"step="0.1" value="' + parseFloat(row.Saldo).toFixed(2) + '" class="form-control input-sas">'
                            } else {
                                data = '<input type="number" min="0"step="0.1" value="' + parseFloat(row.SGO_ABONO).toFixed(2) + '" class="form-control input-sas">'

                            }

                        }
                        if (type === 'export') {
                            var d = data;
                            if (d == null) {
                                data = d;
                            }
                        }
                        return data;
                    },
                    //defaultContent: '<input type="number" min="0" class="form-control input-sas">',
                    orderable: false
                },
                {
                    data: null,
                    title: "Confirmar",
                    className: "btn_confirmar",
                    defaultContent: `
                    <button class="btn btn-success btn-sm btn_confirmar">Aprobar 
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </button>
                    `,
                    // orderable: false,
                    width: 20
                },
                {
                    data: "SGO_CONFIRMAR",
                    title: "CON",
                    visible: false
                }
            ],

            "createdRow": function(row, data, index) {
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(4).addClass("fw-bolder bg-light-success");
                $('td', row).eq(6).addClass("fw-bolder bg-light-success");
                if (data["SGO_CONFIRMAR"] == 1) {
                    $('td', row).eq(9).removeClass("btn_confirmar");
                    $('td', row).eq(9).addClass("text-danger fw-bold");
                    $('td', row).eq(9).html("CONFIRMADA");
                }
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Deudas tbody').on('click', 'td.btn_confirmar', function(e) {

            $("#Modal_archivo").modal("show");
            $("#archivo_deudas").val('');
            $("#SGO_COMENTARIO_PENDIENTE").val('');

            var data = table.row(this).data();
            var columns = $(this).closest("tr").children();
            var abono = columns.eq(8).children().val();
            data.Abono = abono;
            ARRAY_DATOS_PROV_DEUDA = [];
            ARRAY_DATOS_PROV_DEUDA = data;

            // Actualizar_Deuda(data)
        });

    }


    function calculo() {


        var hoy = Date.now(); //Fecha de hoy 
        var fecha1 = moment(document.getElementById('SOAT_DESDE').value, "YYYY-MM-DD");
        var fecha2 = moment(hoy);

        document.getElementById('VENCE').value = fecha2.diff(fecha1, 'days');
        var dias = document.getElementById('VENCE').value;
        if (document.getElementById('VENCE').value > 335) {
            VENCE.style.backgroundColor = "red";
            VENCE.style.color = "white";
        } else {
            VENCE.style.backgroundColor = "green";
            VENCE.style.color = "white";
        }

    }

    function datos_Deudor(data) {

        data = data.split(",");
        let param = {
            tipo: data[0].trim(),
            numero: data[1].trim(),
            empresa: data[2].trim(),
        }

        // Create our number formatter.
        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',

        });


        AjaxSendReceiveData(url_Detalles_Deudor, param, function(x) {

            let CAB = x[0][0];
            let DET1 = x[1];
            let DET2 = x[2];
            $("#Modal_Deudor").modal("show");
            $("#Fecha").text(moment(CAB.Fecha).format("YYYY-MM-DD"));
            $("#Codigo").text(CAB["Código"]);
            $("#Nombre").text(CAB.Nombre);
            $("#Tipo").text(CAB.Tipo);
            $("#Dias").text(CAB["Días"]);
            $("#Saldo").text(formatter.format(data[3]));
            $("#Valor").text(formatter.format(CAB.Valor));
            $("#Vencimiento").text(moment(CAB.Vencimiento).format("YYYY-MM-DD"));

            Tabla_DEUDAS1(DET1);
            Tabla_DEUDAS2(DET2);
        });

    }

    function Tabla_DEUDAS1(data) {

        $('#tabla_deudas1').empty();
        var table = $('#tabla_deudas1').DataTable({
            destroy: true,
            data: data,
            dom: 'rtip',
            // responsive: true,
            deferRender: true,

            scrollY: '30vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            "order": [
                [0, "desc"]
            ],
            columns: [
                // {
                //     data: "Detalle",
                //     title: "Detalle",
                // },
                {
                    data: "Código",
                    title: "Código",
                }, {
                    data: "Nombre",
                    title: "Nombre",
                }, {
                    data: "Stock",
                    title: "Stock Actual",
                    render: $.fn.dataTable.render.number(',', '.', 0, "")

                }, {
                    data: "Comprado",
                    title: "Comprado",
                    render: $.fn.dataTable.render.number(',', '.', 0, "")

                }, {
                    data: "Venta",
                    title: "Venta",
                    render: $.fn.dataTable.render.number(',', '.', 0, "")

                }, {
                    data: "Cantidad",
                    title: "Cantidad",
                    render: $.fn.dataTable.render.number(',', '.', 0, "")

                }, {
                    data: "Precio",
                    title: "Precio",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }, {
                    data: "Subtotal",
                    title: "Subtotal",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }
            ],
            "createdRow": function(row, data, index) {
                $('td', row).eq(0).addClass("fw-bolder");
                $('td', row).eq(1).addClass("fw-bolder");
                $('td', row).eq(2).addClass("fw-bolder bg-light-warning");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(4).addClass("fw-bolder bg-light-success");
                $('td', row).eq(5).addClass("fw-bolder");
                $('td', row).eq(6).addClass("fw-bolder bg-light-primary");

            }
        });
        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);



    }

    function Tabla_DEUDAS2(data) {

        // $('#tabla_deudas2').empty();
        var table = $('#tabla_deudas2').DataTable({
            destroy: true,
            data: data,
            dom: 'rtip',
            // responsive: true,
            deferRender: true,

            scrollY: '30vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            "order": [
                [0, "desc"]
            ],
            columns: [
                // {
                //     data: "Detalle",
                //     title: "Detalle",
                // },
                // {
                //     data: "Nombre",
                //     title: "Nombre",
                // }, 
                {
                    data: "Empresa",
                    title: "Empresa",
                }, {
                    data: "SaldoCobrar",
                    title: "Saldo Cobrar",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")


                }, {
                    data: "SaldoVencido",
                    title: "Saldo Vencido",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                }, {
                    data: "SaldoAfavorCL",
                    title: "Saldo A favor Cliente",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")


                }, {
                    data: "SaldoPagar",
                    title: "Saldo Pagar",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }, {
                    data: "SaldoVencido",
                    title: "Saldo Vencido",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                }, {
                    data: "SaldoAfavorAc",
                    title: "Saldo A favor Acreedor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")


                }, {
                    data: "NoFactVencidas",
                    title: "Facturas Vencidas",
                    render: $.fn.dataTable.render.number(',', '.', 0, "")

                },
            ],
            "createdRow": function(row, data, index) {
                $('td', row).eq(0).addClass("fw-bolder");
                $('td', row).eq(1).addClass("fw-bolder bg-light-warning");
                $('td', row).eq(2).addClass("fw-bolder bg-light-warning");
                $('td', row).eq(3).addClass("fw-bolder text-danger bg-light-warning");
                $('td', row).eq(4).addClass("fw-bolder bg-light-success");
                $('td', row).eq(5).addClass("fw-bolder bg-light-success");
                $('td', row).eq(6).addClass("fw-bolder text-danger bg-light-success");
                $('td', row).eq(7).addClass("fw-bolder bg-light-success");

            }
        });
        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);

    }

    function Actualizar_Deuda() {

        Swal.fire({
            title: 'Estas seguro?',
            text: "Asegurese que la la deuda seleccionada se la correcta!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Continuar!',

        }).then((result) => {

            if (result.isConfirmed) {

                let COMENTARIO = $("#SGO_COMENTARIO_PENDIENTE").val();
                let archivo = $("#archivo_deudas")[0].files;;

                let param = {
                    ID: ARRAY_DATOS_PROV_DEUDA["ID"],
                    Abono: ARRAY_DATOS_PROV_DEUDA["Abono"],
                    SGO_COMENTARIO_PENDIENTE: COMENTARIO
                }


                AjaxSendReceiveData(url_Actualizar_Deuda, param, function(res) {

                    if (res == true) {
                        let param = {
                            ruc: PROVEEDOR_ID,
                            tipo: 2
                        }
                        if (archivo.length != 0) {
                            Guardar_Adjunto_deuda(archivo[0], ARRAY_DATOS_PROV_DEUDA["ID"]);
                        }


                        AjaxSendReceiveData(url_Buscar_Proveedor, param, function(x) {

                            let DEUDAS = x[1];
                            Tabla_Deudas(DEUDAS)
                        });
                        $("#Modal_archivo").modal("hide");

                    }

                })

                // Swal.fire({
                //     title: 'Ingrese concepto',
                //     input: 'textarea',
                //     inputAttributes: {
                //         autocapitalize: 'off'
                //     },
                //     showCancelButton: true,
                //     confirmButtonText: 'Confirmar',
                //     showLoaderOnConfirm: true,
                //     preConfirm: (login) => {
                //         return login;
                //     },
                //     allowOutsideClick: () => !Swal.isLoading()

                // }).then((result) => {

                //     if (result.isConfirmed) {
                //     }
                // });

            }
        })
    }

    //************************************************** */

    function Nueva_Orden() {
        let asunto = $("#OR_AsUNTO").val();
        let valor = $("#OR_VALOR").val();
        let orden = $("#OR_ORDEN").val();
        let r = $('#CH_PROV').is(':checked');
        let c = $('#CH_OTRO').is(':checked');
        var archivo = $("#archivo_pdf")[0].files;


        let ruc = CODIGO_PROV_NU_ORDEN;
        let cuenta = $('#OR_CUENTA').val();
        let param = {
            asunto: asunto,
            valor: valor,
            ruc: ruc,
            cuenta: cuenta,
        }

        if (asunto == "") {
            Mensaje("Ingrese un Detalle", "", "error");
        } else if (valor == "") {
            Mensaje("Ingrese un Valor", "", "error");
        } else {
            if (r == true) {
                // if (ORDEN_DOBRA_ID == "") {
                //     Mensaje("Debe agragra una orden", "", "error")
                // } else {
                //     // let ruc = $("#OR_PROV").val();

                // }
                if (ruc == "") {
                    Mensaje("Ingrese un Ruc", "", "error");
                } else {

                    if (CODIGO_PROV_NU_ORDEN == -1) {

                    } else {
                        let AGR = $('#CH_AGR_ORDEN').is(':checked');
                        let ORD_D = ORDEN_DOBRA_ID;
                        if (AGR == false) {
                            ORD_D = ""
                        } else {
                            ORD_D = ORDEN_DOBRA_ID;
                        }

                        let param3 = {
                            ID: CODIGO_PROV_NU_ORDEN,
                            cuenta: "",
                            asunto: asunto,
                            valor: valor,
                            ruc: ruc,
                            cuenta_codigo: "",
                            ORDEN_DOBRA: ORD_D
                        };

                        AjaxSendReceiveData(url_Nueva_Orden, param3, function(x) {
                            console.log('x: ', x);

                            if (x[0] == true) {
                                Mensaje("Orden Creada", "", "success");
                                $("#kt_modal_new_address").modal("hide");
                                Cargar_Ordenes(1);
                                if (archivo.length != 0) {
                                    Guardar_Adjunto(archivo[0], x[1][0]["Orden_ID"]);
                                }
                            }
                        });
                    }
                }

            } else {
                let param3 = {
                    ID: "",
                    cuenta: CODIGO_CUENTA_NOMBRE_NU_ORDEN,
                    asunto: asunto,
                    valor: valor,
                    ruc: ruc,
                    cuenta_codigo: CODIGO_CUENTA_NU_ORDEN,
                    ORDEN_DOBRA: ORDEN_DOBRA_ID
                };

                AjaxSendReceiveData(url_Nueva_Orden, param3, function(x) {

                    if (x[0] == true) {
                        Mensaje("Orden Creada", "", "success");
                        $("#kt_modal_new_address").modal("hide");
                        if (archivo.length != 0) {
                            Guardar_Adjunto(archivo[0], x[1][0]["Orden_ID"]);
                        }
                        Cargar_Ordenes(1);

                    }
                });
            }
        }
    }

    function Guardar_Adjunto(archivo, ID) {

        function renameFile(originalFile, newName) {
            return new File([originalFile], newName, {
                type: originalFile.type,
                lastModified: originalFile.lastModified,
            });
        };

        let ar1 = renameFile(archivo, ID);
        guardarImgpdf(ar1);
    }

    function guardarImgpdf(data) {
        // var files = $('#' + campo)[0].files[0];
        var formData = new FormData();
        formData.append('file', data);
        // formData.append('doc', SAM_CASO);
        var param = {
            'file': data
        }
        $.ajax({
            url: url_Guardar_Documento,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('response: ', response);
                // response = response.trim();
                // response = response.replace(/^"(.+(?="$))"$/, '$1');
            }
        });
    }

    //******* ARCHIVO DEUDA */
    function Guardar_Adjunto_deuda(archivo, ID) {

        function renameFile(originalFile, newName) {
            return new File([originalFile], newName, {
                type: originalFile.type,
                lastModified: originalFile.lastModified,
            });
        };

        let fecha = moment().format("YYYYMMDDhhmmss")
        let ar1 = renameFile(archivo, ID + "_" + fecha);
        guardarImgpdf_deuda(ar1);
    }

    function guardarImgpdf_deuda(data) {
        // var files = $('#' + campo)[0].files[0];
        var formData = new FormData();
        formData.append('file', data);
        // formData.append('doc', SAM_CASO);
        var param = {
            'file': data
        }
        // 
        $.ajax({
            url: url_Guardar_Documento_deuda,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                // response = response.trim();
                // response = response.replace(/^"(.+(?="$))"$/, '$1');


            }
        });
    }


    function Buscar_Ordenes() {
        $("#kt_modal_Ordenes").modal("show");
        let orden = $("#OR_ORDEN").val();
        ORDEN_DOBRA_ID = "";
        let param = {
            PROVEEDOR_ID: ID_PROVEEDOR
        }


        AjaxSendReceiveData(url_Buscar_Orden, param, function(x) {

            Tabla_Ordenes_dobra(x)
        });
    }

    function Tabla_Ordenes_dobra(data) {
        $('#Tabla_Ordenes_Dobra').empty();
        var table = $('#Tabla_Ordenes_Dobra').DataTable({
            destroy: true,
            data: data,
            dom: 'frtip',
            // responsive: true,
            deferRender: true,
            // scrollY: '40vh',
            // scrollCollapse: true,

            columns: [

                {
                    data: "Número",
                    title: "Número",

                }, {
                    data: "Detalle",
                    title: "Detalle",

                },
                {
                    data: "Tipo",
                    title: "Tipo",
                    width: 100

                }, {
                    data: "Total",
                    title: "Total",
                    width: 100,
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                },
                {
                    data: null,
                    title: "Seleccionar",
                    className: "btn_confirmar",
                    defaultContent: `
                    <button class="btn btn-success btn-sm btn_confirmar"> + 
                    </button>
                    `,
                    // orderable: false,
                    width: 20
                }
            ],

            "createdRow": function(row, data, index) {
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(0).addClass("fw-bolder");
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Ordenes_Dobra tbody').on('click', 'td.btn_confirmar', function(e) {
            var data = table.row(this).data();

            ORDEN_DOBRA_ID = data.ID;
            $("#NO_NUM_ORDEN").text(ORDEN_DOBRA_ID);
            $("#NO_NUM_Nombre").text(data.Detalle);
            $("#NO_NUM_tipo").text(data.Tipo);
            $("#NO_NUM_valor").text(formatter.format(data.Total));
            $("#kt_modal_Ordenes").modal("hide");

        });
    }

    $("#CH_PROV").on("change", function(x) {
        $("#S_R").show();
        $("#S_C").hide()
        $("#SECC_NO_NOMB_PROV").hide();
        $("#SECCION_NO_PRO").hide();
        $("#NO_NUM_Nombre").text("");
        $("#NO_NUM_tipo").text("");
        $("#NO_NUM_valor").text("");
        $("#NO_NUM_ORDEN").text("");
        ORDEN_DOBRA_ID = ""
        $("#SECC_BTN_ORDEN").show();

    });

    $("#CH_OTRO").on("change", function(x) {
        $("#S_R").hide();
        $("#S_C").show();
        $("#SECC_NO_NOMB_PROV").hide();
        $("#SECCION_NO_PRO").hide();
        $("#NO_NUM_Nombre").text("");
        $("#NO_NUM_tipo").text("");
        $("#NO_NUM_valor").text("");
        $("#NO_NUM_ORDEN").text("");
        ORDEN_DOBRA_ID = ""
        $("#SECC_BTN_ORDEN").hide();


    });

    $("#CH_AGR_ORDEN").on("change", function(x) {
        if ($('#CH_AGR_ORDEN').is(':checked')) {
            $("#SECCION_AGREGAR_ORDEN").show();

        } else {
            $("#SECCION_AGREGAR_ORDEN").hide();

        }
    });

    function Buscar_Proveedor_No() {
        let ruc = $("#OR_PROV").val();
        RUC_PROVEEDOR = ruc;
        let param = {
            ruc: RUC_PROVEEDOR
        }
        if (ruc == "") {
            Mensaje("Debe Escribir el nombre o ruc del proveedor", "", "error");

        } else {
            AjaxSendReceiveData(url_Validar_Proveedor, param, function(x) {

                if (x.length == 1) {
                    $("#SECC_NO_NOMB_PROV").show();
                    $("#NO_NOMB_PROV").text(x[0]["Nombre"]);

                    CODIGO_PROV_NU_ORDEN = x[0]["ID"];
                    ID_PROVEEDOR = x[0]["ID"];
                } else {
                    $("#kt_modal_Proveedores").modal("show");
                    Tabla_Lista_Proveedores(x)
                }
                $('#NO_NUM_ORDEN').text("");
                $('#NO_NUM_Nombre').text("");
                $('#NO_NUM_tipo').text("");
                $('#NO_NUM_valor').text("");
                $('#OR_AsUNTO').val("");
                $('#OR_VALOR').val("");

                $("#CH_AGR_ORDEN").prop("checked", false);
                $("#SECCION_NO_PRO").show();
                $("#SECCION_AGREGAR_ORDEN").hide();
                ORDEN_DOBRA_ID = ""

            });
        }
    }

    function Buscar_Cuenta_No() {
        let ruc = $("#OR_CUENTA").val();
        RUC_PROVEEDOR = ruc;
        let param = {
            ruc: RUC_PROVEEDOR
        }
        if (ruc == "") {
            Mensaje("Debe Escribir el nombre o codigo de la cuenta", "", "error");

        } else {
            AjaxSendReceiveData(url_Validar_Cuenta, param, function(x) {

                if (x.length == 1) {
                    $("#SECC_NO_NOMB_PROV").show();
                    $("#NO_NOMB_PROV").text(x[0]["Nombre"]);
                    $("#SECCION_NO_PRO").show();
                    CODIGO_PROV_NU_ORDEN = x[0]["ID"];
                    CODIGO_CUENTA_NU_ORDEN = x[0]["Código"];
                    CODIGO_CUENTA_NOMBRE_NU_ORDEN = x[0]["Nombre"];

                } else {
                    $("#kt_modal_Proveedores").modal("show");
                    Tabla_Lista_Proveedores(x)
                }
            });
        }
    }

    function Cargar_Ordenes(tipo) {
        let param = {
            tipo: tipo
        }

        AjaxSendReceiveData(url_Cargar_Ordenes, param, function(x) {
            Tabla_Ordenes(x)
        });
    }

    function Tabla_Ordenes(data) {


        $('#Tabla_Ordenes').empty();
        var table = $('#Tabla_Ordenes').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            // responsive: true,
            deferRender: true,
            scrollY: '40vh',
            scrollCollapse: true,
            paging: false,
            // info: false,
            "order": [
                [0, "desc"]
            ],
            buttons: [{
                text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                className: 'btn btn-success',
                action: function(e, dt, node, config) {
                    Cargar_Ordenes(1);

                }
            }, {
                text: `<span class"fw-bolder">PENDIENTES</span>`,
                className: 'btn btn-light-info',
                action: function(e, dt, node, config) {
                    Cargar_Ordenes(1);
                }
            }, {
                text: `<span class"fw-bolder">PAGADOS</span>`,
                className: 'btn btn-light-danger',
                action: function(e, dt, node, config) {
                    Cargar_Ordenes(2);
                }
            }],
            columns: [{
                    data: "Fecha_Creado",
                    title: "Fecha Deuda",
                    width: 120
                }, {
                    data: "TIPO_DOC",
                    title: "tipo",
                    visible: false
                },
                {
                    data: "Proveedor_nombre",
                    title: "Nombre / Cuenta",
                    render: function(data, tipo, row) {
                        if (data == null) {
                            return row.cuenta_nombre;
                        } else {
                            return data;
                        }
                    }
                }, {
                    data: "ruc",
                    title: "ruc",
                    visible: false
                },
                {
                    data: "cuenta_codigo",
                    title: "cuenta_codigo",
                    visible: false
                },
                {
                    data: "numero",
                    title: "Orden / Número",
                    // render: function(data, tipo, row) {
                    //     if (row.tipo == 'ORD-PAGO') {
                    //         return row.orden_dobra;
                    //     } else {
                    //         return "";
                    //     }
                    // }
                },
                // {
                //     data: "Nombre",
                //     title: "Tipo",
                //     render: function(data, tipo, row) {
                //         if (data == null) {
                //             return "CUENTA";
                //         } else {
                //             return "RUC";
                //         }
                //     }
                // },
                {
                    data: "Asunto",
                    title: "Asunto",
                }, {
                    data: "valor",
                    title: "Valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 130

                }, {
                    data: "abono",
                    title: "Abono",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                }, {

                    data: "Empresa",
                    title: "Empresa",
                    visible: false
                }, {

                    data: "estado",
                    title: "estado",
                    // visible: false
                }, {
                    data: null,
                    title: "Anular",
                    className: "btn_Anular",
                    defaultContent: `
                    <button class="btn btn-danger btn-sm btn_Anular">Anular 
                            <i class="bi bi-trash-fill fs-2"></i>                       
                    </button>
                    `,
                    orderable: false,
                    width: 20
                }
            ],
            "createdRow": function(row, data, index) {

                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-7">Tipo:</span>
                        <span class="text-gray-800 fw-semibold d-block fs-7">` + (data["TIPO_DOC"]) + `</span>
                    </div>
                `;
                let tipo = "Ruc:";
                if (data["Proveedor_nombre"] == null) {
                    data["Proveedor_nombre"] = data["cuenta_nombre"]
                    data["ruc"] = data["cuenta_codigo"]
                    tipo = "Cuenta:";
                }
                let Nombre;

                if (data["TIPO_DOC"] == 'PROV-ORDEN' || data["TIPO_DOC"] == "VALE") {
                    Nombre = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Proveedor_nombre"]) + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-7">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-7">` + (data["ruc"]) + `</span>
                        <span class="text-success fw-semibold d-block fs-6">` + (data["Empresa"]) + `</span>
                    </div>
                `;
                } else {
                    let ARR_D = [data["TIPO_DOC"], data["numero"], data["Empresa"], data.saldo];

                    Nombre = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#!" onclick = "datos_Deudor('` + ARR_D + `')" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Proveedor_nombre"]) + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-7">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-7">` + (data["ruc"]) + `</span>
                        <span class="text-success fw-semibold d-block fs-6">` + (data["Empresa"]) + `</span>
                    </div>
                `;
                }
                let valor = `
                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-700 fw-semibold d-block fs-4">Valor deuda</span>
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + formatter.format(data["valor"]) + `</a>
                        <span class="text-info fw-semibold d-block fs-4">Saldo a Pagar</span>
                        <span class="text-gray-700 fw-semibold d-block fs-4">` + formatter.format(data["saldo"]) + `</span>
                    </div>
                `;

                if (data["estado"] == null) {
                    $('td', row).eq(6).html("PENDIENTE");
                    $('td', row).eq(6).addClass("text-info");
                } else {
                    if (data["estado"] == 1) {
                        $('td', row).eq(6).html(`
                        <div class="d-flex justify-content-start flex-column">
                            <span class="text-primary fw-bold d-block fs-5">APROBADO</span>
                            <span class="text-gray-700 fw-semibold d-block fs-4">motivo:</span>
                            <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-7">` + data["comentario_confirmar"] + `</a>
                        </div>
                        `);
                        $('td', row).eq(6).addClass("text-primary");
                    } else if (data["estado"] == 3) {
                        $('td', row).eq(6).html(`
                        <div class="d-flex justify-content-start flex-column">
                            <span class="text-danger fw-bold d-block fs-5">RECHAZADO</span>
                            <span class="text-gray-700 fw-semibold d-block fs-4">motivo:</span>
                            <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-7">` + data["comentario_rechazo"] + `</a>
                        </div>
                        `);
                        $('td', row).eq(6).addClass("text-danger");
                    }
                }

            
                let asunto
                if (data["TIPO_DOC"] == "PROV-ORDEN") {
                    let link = '<?php echo constant("URL") ?>recursos/documentos/' + data["archivo"];
                    asunto = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + data["Asunto"] + `</a>
                        <span class="text-info fw-semibold d-block fs-4">Documento</span>
                        <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + data["archivo"] + `</a>
                    </div>
                `;
                }else{
                    let link = '<?php echo constant("URL") ?>recursos/deudas/' + data["archivo"];
                    asunto = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + data["Asunto"] + `</a>
                        <span class="text-info fw-semibold d-block fs-4">Documento</span>
                        <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + data["archivo"] + `</a>
                    </div>
                `;
                }
                
                $('td', row).eq(0).html(fecha);
                $('td', row).eq(1).html(Nombre);
                $('td', row).eq(3).html(asunto);
                $('td', row).eq(4).html(valor);
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder text-gray-600");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(4).addClass("fw-bolder fs-3");
                $('td', row).eq(5).addClass("fw-bolder bg-light-success");
                if (data["estado"] == 0) {
                    $('td', row).eq(7).removeClass("btn_Anular");
                    $('td', row).eq(7).html("ANULADO");
                    $('td', row).eq(7).addClass("text-danger fw-bold");

                }
                if (data["girado"] == 1) {
                    $('td', row).eq(6).html("PAGADO");
                    $('td', row).eq(6).addClass("text-danger");
                    $('td', row).eq(7).removeClass("btn_Anular");
                    $('td', row).eq(7).html("");

                }
                if (data["Empresa"] == "COMPUTRON") {
                    $('td', row).eq(6).addClass("fw-bolder bg-light-warning");
                } else {
                    $('td', row).eq(6).addClass("fw-bolder bg-light-success");

                }
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Ordenes tbody').on('click', 'td.btn_Anular', function(e) {
            var data = table.row(this).data();

            if (data["estado"] == null) {
                Anular_Orden(data);
            } else {
                Mensaje("No se puede anular, ya se encuentra aprobado o creada", "solo se pueden anular ordenes pendientes", "error");
            }
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        })

    }
    Cargar_Ordenes(1);



    function Anular_Orden(data) {

        let param = {
            Orden_id: data.Orden_ID,
            Orden_tipo: 1
        }
        if (data["TIPO_DOC"] == "PROV-ORDEN") {
            param.Orden_tipo = 1;
        } else {
            param.Orden_tipo = 2;

        }



        Swal.fire({
            title: 'Va a anular esta orden, desea continuar?',
            // showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Anular',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                AjaxSendReceiveData(url_Anular_Orden, param, function(x) {

                    Cargar_Ordenes(1)
                });

            }
        })


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

    $("#Proveedor").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $("#BTN_BUSACR_PROVEEDOR").click();
        }
    });

    $("#OR_PROV").on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $("#BTN_NO_PR").click();
        }
    });
</script>