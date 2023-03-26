<?php

$url_Buscar_Deudas = constant('URL') . 'pagos/Buscar_Deudas/';
$url_Girar_Cheque = constant('URL') . 'pagos/Girar_Cheque/';
$url_Consultar_Bancos = constant('URL') . 'principal/Cargar_Bancos/';
$url_Pagos_Realizados = constant('URL') . 'pagos/Pagos_Realizados/';
$url_cheques = constant('URL') . 'pagos/cheques/';
$url_Actualizar = constant('URL') . 'pagos/Actualizar/';
$url_Guardar_Documento  = constant('URL') . 'pagos/Guardar_Documento/';

?>
<script>
    var url_Buscar_Deudas = '<?php echo $url_Buscar_Deudas ?>';
    var url_Girar_Cheque = '<?php echo $url_Girar_Cheque ?>';
    var url_Consultar_Bancos = '<?php echo $url_Consultar_Bancos ?>';

    var $url_cheques = '<?php echo $url_cheques ?>';
    var $url_Actualizar = '<?php echo $url_Actualizar ?>';
    var url_Guardar_Documento = '<?php echo $url_Guardar_Documento ?>';

    var BANCOS_OPTION;
    var EMPRESA = "CARTIMEX";
    var TIPO_DOC;
    var ID;
    var Empresa_guardar;


    $("#customSwitch3").on('change', function(x) {
        EMPRESA = "CARTIMEX"
        $("#TIPOS_DOC_COMPUTRON").hide();
        $("#TIPOS_DOC_CARTIMEX").show()
    });
    $("#customSwitch4").on('change', function(x) {
        EMPRESA = "COMPUTRON"
        $("#TIPOS_DOC_COMPUTRON").show();
        $("#TIPOS_DOC_CARTIMEX").hide()
    });

    function Mensaje(t1, t2, ic) {
        Swal.fire(
            t1,
            t2,
            ic
        );
    }
    const formatter = new Intl.NumberFormat('ec-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });


    function Cargar_Informe() {

        AjaxSendReceiveData(url_Consultar_Bancos, [], function(bancos) {

            BANCOS_OPTION = "";
            bancos.map(function(x) {
                BANCOS_OPTION = BANCOS_OPTION + "<option value=" + x.ID + ">" + x.Nombre + "</option>";
            });
        });
    }

    Cargar_Informe()

    function Cargar_Deudas(estado) {

        ESTADO_CARGA = estado
        let tipo = $("#TIPOS_DOC_CARTIMEX").val();
        let param = {
            EMPRESA: EMPRESA,
            TIPO: tipo
        }


        if (ESTADO_CARGA == 5) {
            param.TIPO = 5;

            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {

                Tabla_Deudas_pagadas(x)
            });
        } else {
            console.log('param: ', param);

            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
                console.log('x: ', x);

                if (ESTADO_CARGA == "todo") {
                    Tabla_Deudas(x)
                } else {
                    let dataFiltrada = x.filter(cat => cat.estado == ESTADO_CARGA);
                    console.log('dataFiltrada: ', dataFiltrada);
                    Tabla_Deudas(dataFiltrada)
                }
            });
        }

    }


    function Cargar_cheques() {

        let param = {
            empresa: EMPRESA
        }

        AjaxSendReceiveData($url_cheques, param, function(respuesta) {
            console.log('respuesta: ', respuesta);

            Tabla_Cheques_girados(respuesta);

        });
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
        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }

        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-ligth',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(ESTADO_CARGA)

                    }
                }, {
                    text: `<span class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(1)

                    }
                },
                //  {
                //     text: `<span class"fw-bolder">Pagados</span>`,
                //     className: 'btn btn-light-danger fs-2',
                //     action: function(e, dt, node, config) {
                //         Cargar_Deudas(5)
                //     }
                // },
                {
                    text: `<span class"fw-bolder">Cheques Girados</span>`,
                    className: 'btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_cheques()

                    }
                }, , $.extend(!0, {}, buttonCommon, {
                    extend: "excel",
                    title: "TextoSucursal",
                    messageTop: "Datos de Empleados Sucursal: ",
                    // exportOptions: {
                    //     columns: [0, 1]
                    // }
                })
            ],
            scrollY: '70vh',
            scrollCollapse: true,
            paging: false,
            // info: false,
            "order": [
                [0, "desc"],
                [1, "desc"],
            ],
            columnDefs: [{
                    target: 0,
                    visible: false,
                    searchable: false,
                }, {
                    target: 1,
                    visible: false,
                    searchable: false,
                },
                {
                    target: 3,
                    visible: false,
                    searchable: false,
                }, {
                    target: 4,
                    visible: false,
                    searchable: false,
                },
            ],

            columns: [{
                    data: "estado",
                    title: "estado",

                }, {
                    data: "Fecha",
                    title: "Fecha",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    }
                },
                {
                    data: "Número",
                    title: "Número",

                },
                {
                    data: null,
                    title: "Detalle",
                    render: function(x, t, r) {
                        if (x == "") {
                            return r.proveedor_nombre
                        } else {
                            return x
                        }
                    },
                    width: 250

                }, {
                    data: "Empresa",
                    title: "Empresa",
                    width: 250

                },
                {
                    data: "Tipo",
                    title: "Detalle",
                    width: 150

                },
                {
                    data: "Vencimiento",
                    title: "Vencimiento",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    },
                    width: 120
                },
                {
                    data: "Valor",
                    title: "Valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 200
                }, {
                    data: "Saldo",
                    title: "Saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    visible: false
                }, {
                    data: "abono",
                    title: "Abono",
                    className: "dt-center  input-sas",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                }, {
                    data: "Banco_nombre",
                    title: "Bancos",
                    className: "dt-center  select_ban",
                    width: 250,
                }, {
                    data: "ischeque",
                    title: "Tipo de Pago",
                    width: 200

                },
                {
                    data: "Fcheque",
                    title: "Fecha",
                    className: "dt-center  input-date",
                    width: 150,
                    visible: false

                }, {
                    data: "estado",
                    title: "Estado",

                    render: function(data, row, rw) {
                        if (data == 1) {
                            let ARR = [rw.comentario_confirmar, rw.abono, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                            return `<a href="#" onclick="mostrar_comentario('` + ARR + `')" class="text-hover">
                                        <span class="text-primary fw-bold text-hover d-block fs-5">APROBADO POR:</span>
                                        <span class="text-success fw-bold text-hover d-block fs-5">` + rw.SGO_APROBADO_POR + `</span>
                                        <span class="text-gray-700 fw-bold text-hover d-block fs-5">` + rw.comentario_confirmar + `</span>
                                    </a>`
                        } else {
                            if (rw.girado == 1) {
                                let ARR = [rw.comentario_confirmar, rw.abono, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                                return `<a href="#" onclick="mostrar_comentario('` + ARR + `')" class="text-hover">
                                <span class="text-danger fw-bold text-hover d-block fs-5">PAGADO (?)</span>
                                <span class="text-dark fw-bold text-hover d-block fs-5">` + moment(rw.fecha_girado).format("YYYY-MM-DD hh:mm A") + `</span>
                                    </a>`
                            }

                        }
                    }
                },
                {
                    data: null,
                    title: "Registrar en Dobra",
                    className: "btn_girar",
                    defaultContent: `
                    <button class="btn btn-primary btn-sm btn_girar">
                             <i class="bi bi-arrow-clockwise fs-2"></i>
                    </button>
                    `,
                    orderable: false,
                    width: 100
                },


            ],
            "createdRow": function(row, data, index) {
                let tipo = "Deuda numero";
                if (data["Tipo"] == "VALE") {
                    tipo = "Vale numero";
                } else if (data["Tipo"] == "PROV-ORDEN") {
                    tipo = "Orden dobra";
                }

                let fecha = `

                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Número"]) + `</span>
                    </div>
                `;

                let vencimiento;
                if (data["Tipo"] == "VALE" || data["Tipo"] == "PROV-ORDEN") {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"></a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;
                } else {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <span class="text-gray-700 fw-semibold d-block fs-5">Vencimiento</span>
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Vencimiento"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;
                }

                let color = "warning";
                if (data["Empresa"] == "CARTIMEX") {
                    color = "primary";
                }
                let prv_nombre;
                if (data["Tipo"] == 'PROV-ORDEN') {
                    prv_nombre = data["proveedor_nombre"];
                } else {
                    prv_nombre = data["Detalle"];
                    data["Detalle"] = ""
                }

                detalle = `
                
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + prv_nombre + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["Detalle"] + `</span>
                        <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                    </div>
                `;


                let tipo_f = "";
                if (moment(data["Fcheque"]).format("YYYY-MM-DD") == '1900-01-01') {
                    // $('td', row).eq(7).html("");
                    tipo_f = "";
                } else {
                    tipo_f = "Fecha:";
                    // $('td', row).eq(7).html(moment(data["Fcheque"]).format("YYYY-MM-DD"));
                }

                let valor = `

                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-700 fw-semibold d-block fs-4">Valor deuda</span>
                        <a href="#!"  class="text-gray-800 fw-semibold text-hover-primary mb-1 fs-4">` + formatter.format(data["Valor"]) + `</a>
                        <span class="text-info fw-semibold d-block fs-4">Saldo a Pagar</span>
                        <span class="text-gray-800 fw-bold d-block fs-4">` + formatter.format(data["Saldo"]) + `</span>
                    </div>
                `;

                let fe = moment(data["Fcheque"]).format("YYYY-MM-DD");
                if (moment(data["Fcheque"]).format("YYYY-MM-DD") <= '1900-01-01') {
                    fe = "";
                }
                let cheque = `

                    <div class="d-flex justify-content-start flex-column">
                        <a href="#!"  class="text-gray-700 fw-bold text-hover-primary mb-1 fs-4">` + data["ischeque"] + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-4">` + tipo_f + `</span>
                        <span class="text-gray-600 fw-bold d-block fs-4">` + fe + `</span>
                    </div>
                `;



                $('td', row).eq(0).html(fecha);
                $('td', row).eq(2).html(vencimiento);
                $('td', row).eq(1).html(detalle);
                $('td', row).eq(3).html(valor);
                $('td', row).eq(6).html(cheque);
                $('td', row).eq(5).addClass("fs-6 fw-bolder");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder bg-light-warning");
                $('td', row).eq(4).addClass("fw-bolder fs-3");
                $('td', row).eq(6).addClass("fw-bolder");
                $('td', row).eq(7).addClass("fw-bolder");
                $('td', row).eq(8).addClass("fw-bolder");

                if (data["girado"] == 1) {
                    $('td', row).eq(10).removeClass("btn_girar");
                    $('td', row).eq(10).html();

                }



                // if (data["estado"] != null) {
                //     $('td', row).eq(10).removeClass("btn_confirmar");
                //     $('td', row).eq(10).html("");
                //     $('td', row).eq(12).html(`
                //         <button class="btn btn-primary btn-sm btn_girar">
                //             <i class="bi bi-arrow-clockwise fs-2"></i>
                //         </button>
                //     `);
                //     $('td', row).eq(12).addClass("btn_girar");
                // }

                // if (data["estado"] == 3) {
                //     $('td', row).eq(11).removeClass("btn_rechazar");
                //     $('td', row).eq(11).html("");
                //     $('td', row).eq(12).removeClass("btn_girar");
                //     $('td', row).eq(12).html("");
                //     $('td', row).eq(10).html(`
                //       <button class="btn btn-success btn-sm btn_confirmar"> 
                //         <i class="bi bi-check-circle-fill fs-2"></i>
                //     </button>
                //     `);
                //     $('td', row).eq(10).addClass("btn_confirmar");

                // }

            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);

        $('#Tabla_Deudas tbody').on('change', 'td.select_ch', function(e) {

            var val = $(this).children()[0]["selectedOptions"][0]["value"]


            // $(val).val();
            var columns = $(this).closest("tr").children();


            if (val == "") {
                columns.eq(8).children().prop('disabled', true)
            } else if (val == "cheque") {
                columns.eq(8).children().prop('disabled', false)
            } else {
                columns.eq(8).children().prop('disabled', true)
            }

            // var val = $(this).children().children().prop('checked');
            // var columns = $(this).closest("tr").children();
            // if (val == true) {
            //     columns.eq(9).children().prop('disabled', false)
            // } else {
            //     columns.eq(9).children().prop('disabled', true)
            // }
        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_confirmar', function(e) {
            var data = table.row(this).data();
            // 
            var columns = $(this).closest("tr").children();

            var abono = columns.eq(5).children().val();
            var fecha = columns.eq(8).children().val();
            // var cheque = columns.eq(7).children().children().prop('checked');
            var tipo = columns.eq(7).children()[0]["selectedOptions"][0]["value"];
            var banco = columns.eq(6).children()[0]["selectedOptions"][0]["value"];
            var banco_nombre = columns.eq(6).children()[0]["selectedOptions"][0]["innerText"];

            let param = {
                ID: data.ID,
                abono: abono,
                tipo: tipo,
                fecha: fecha,
                banco: banco,
                banco_nombre: banco_nombre,
                estado: 1,
                comentario_rechazo: ""
            }


            if (banco == "") {
                Mensaje("Debe seleccionar un banco", "", "error");
            } else if (tipo == "") {
                Mensaje("Debe seleccionar un tipo", "", "error");
            } else {

                if (parseFloat(abono) > parseFloat(data.Saldo)) {
                    Mensaje("El Valor a Abonar no puede ser mayor al saldo");

                } else if (abono <= 0) {
                    Mensaje("El Valor a Abonar no puede ser menor o igual a 0", "", "error");

                } else {
                    Swal.fire({
                        title: 'Esta Seguro!',
                        text: "Los Datos se Guardaran",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, continuar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (tipo == "cheque") {
                                fecha = moment(fecha).format("YYYYMMDD")
                            } else {
                                fecha = "";
                            }


                            Guardar_Deuda(param);
                        }
                    })
                }

            }

        });

        $("#Tabla_Deudas tbody").on("keyup", 'input', function(event) {
            var val = table.row(this).$(this).val();


        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_girar', function(e) {
            var data = table.row(this).data();


            if (data.Banco_id == "") {
                Mensaje("No se ha registrado Banco en esta orden", "", "info");
            } else {
                Girar_Cheque(data);
            }


        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_rechazar', function(e) {
            var data = table.row(this).data();

            Rechazar(data);
        });
    }

    function Tabla_Deudas_pagadas(data) {


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

        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }
        // $('#Tabla_Deudas').empty();
        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-ligth',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(ESTADO_CARGA)

                    }
                }, {
                    text: `<span class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(1)

                    }
                },
                //  {
                //     text: `<span class"fw-bolder">Pagados</span>`,
                //     className: 'btn btn-light-danger fs-2',
                //     action: function(e, dt, node, config) {
                //         Cargar_Deudas(5)

                //     }
                // }, 
                {
                    text: `<span class"fw-bolder">Cheques Girados</span>`,
                    className: 'btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_cheques()

                    }
                },
                $.extend(!0, {}, buttonCommon, {
                    extend: "excel",
                    title: "TextoSucursal",
                    messageTop: "Datos de Empleados Sucursal: ",
                    // exportOptions: {
                    //     columns: [0, 1]
                    // }
                })
            ],
            scrollY: '70vh',
            scrollCollapse: true,
            paging: false,
            // info: false,
            "order": [
                [0, "desc"],
                [1, "desc"],
            ],

            columns: [{
                    data: "Fecha",
                    title: "Fecha Deuda",
                },
                {
                    data: "Número",
                    title: "Número",
                    visible: false
                },
                {
                    data: "Detalle",
                    title: "Detalle",
                    width: 250

                }, {
                    data: "Empresa",
                    title: "Empresa",
                    width: 250,
                    visible: false
                },
                {
                    data: "Vencimiento",
                    title: "Vencimiento",
                    width: 130

                },
                {
                    data: "Tipo",
                    title: "Tipo",
                    visible: false
                },
                {
                    data: "Valor",
                    title: "Valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 130

                }, {
                    data: "Saldo",
                    title: "Saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    visible: false

                }, {
                    data: "abono",
                    title: "Abono",
                    // width: 150,
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                }, {
                    data: "Banco_nombre",
                    title: "Bancos",
                    width: 250,
                },
                {
                    data: "Fecha_Creado",
                    title: "Fecha Solicitado",
                    width: 150,

                }, {
                    data: "fecha_girado",
                    title: "Fecha Pagado",
                    width: 150,

                }, {
                    data: "estado",
                    title: "Estado",
                    // render: function(data, row, rw) {
                    //     if (data == 1) {
                    //         let ARR = [rw.comentario_confirmar, rw.abono, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                    //         return `<a href="#" onclick="mostrar_comentario('` + ARR + `')" class="text-hover">
                    // <span class="text-primary fw-bold text-hover d-block fs-5">APROBADO (?)</span>
                    //         </a>`
                    //     } else {
                    //         if (rw.girado == 1) {
                    //             let ARR = [rw.comentario_confirmar, rw.abono, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                    //             return `<a href="#" onclick="mostrar_comentario('` + ARR + `')" class="text-hover">
                    //     <span class="text-danger fw-bold text-hover d-block fs-5">PAGADO (?)</span>
                    //     <span class="text-dark fw-bold text-hover d-block fs-5">` + moment(rw.fecha_girado).format("YYYY-MM-DD hh:mm A") + `</span>
                    //         </a>`
                    //         }

                    //     }
                    // }
                },

                // {
                //     data: null,
                //     title: "Confirmar",
                //     className: "btn_confirmar",
                //     defaultContent: `
                //     <button class="btn btn-success btn-sm btn_confirmar"> 
                //         <i class="bi bi-check-circle-fill fs-2"></i>
                //     </button>
                //     `,
                //     orderable: false,
                //     width: 20
                // },
            ],

            "createdRow": function(row, data, index) {
                let tipo = "Deuda numero";
                if (data["Tipo"] == "VALE") {
                    tipo = "Vale numero";
                } else if (data["Tipo"] == "PROV-ORDEN") {
                    tipo = "Orden dobra";
                }
                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Número"]) + `</span>
                    </div>
                `;
                let vencimiento;
                if (data["Tipo"] == "VALE" || data["Tipo"] == "PROV-ORDEN") {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"></a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;
                } else {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Vencimiento"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;
                }

                let color = "warning";
                if (data["Empresa"] == "CARTIMEX") {
                    color = "primary";
                }
                let prv_nombre;
                if (data["Tipo"] == 'PROV-ORDEN') {
                    prv_nombre = data["proveedor_nombre"];
                } else {
                    prv_nombre = data["Detalle"];
                    data["Detalle"] = ""
                }
                let detalle = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + prv_nombre + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["Detalle"] + `</span>
                        <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                    </div>
                `;
                let valor = `
                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-700 fw-semibold d-block fs-5">Valor deuda:</span>
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + formatter.format(data["Valor"]) + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Saldo:</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + formatter.format(data["Saldo"]) + `</span>
                    </div>
                `;
                let fecha_sol = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + moment(data["Fecha_Creado"]).format("hh:mm") + `</span>
                    </div>
                `;
                let fecha_paga = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["fecha_girado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + moment(data["fecha_girado"]).format("hh:mm") + `</span>
                    </div>
                `;
                $('td', row).eq(0).html(fecha);
                $('td', row).eq(2).html(vencimiento);
                $('td', row).eq(1).html(detalle);
                $('td', row).eq(3).html(valor);
                $('td', row).eq(6).html(fecha_sol);
                $('td', row).eq(7).html(fecha_paga);
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(4).addClass("fw-bolder");
                $('td', row).eq(8).addClass("fw-bolder text-danger");

                let estado = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-danger fw-bold text-hover-primary mb-1 fs-6">PAGADO</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["girado_por"] + `</span>
                    </div>
                `;

                if (data["girado"] == 1) {
                    $('td', row).eq(8).removeClass("btn_girar");
                    $('td', row).eq(8).html(estado);

                }



                // if (data["estado"] != null) {
                //     $('td', row).eq(10).removeClass("btn_confirmar");
                //     $('td', row).eq(10).html("");
                //     $('td', row).eq(12).html(`
                //         <button class="btn btn-primary btn-sm btn_girar">
                //             <i class="bi bi-arrow-clockwise fs-2"></i>
                //         </button>
                //     `);
                //     $('td', row).eq(12).addClass("btn_girar");
                // }

                // if (data["estado"] == 3) {
                //     $('td', row).eq(11).removeClass("btn_rechazar");
                //     $('td', row).eq(11).html("");
                //     $('td', row).eq(12).removeClass("btn_girar");
                //     $('td', row).eq(12).html("");
                //     $('td', row).eq(10).html(`
                //       <button class="btn btn-success btn-sm btn_confirmar"> 
                //         <i class="bi bi-check-circle-fill fs-2"></i>
                //     </button>
                //     `);
                //     $('td', row).eq(10).addClass("btn_confirmar");

                // }

            }

        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Deudas tbody').on('change', 'td.select_ch', function(e) {

            var val = $(this).children()[0]["selectedOptions"][0]["value"]


            // $(val).val();
            var columns = $(this).closest("tr").children();


            if (val == "") {
                columns.eq(8).children().prop('disabled', true)
            } else if (val == "cheque") {
                columns.eq(8).children().prop('disabled', false)
            } else {
                columns.eq(8).children().prop('disabled', true)
            }

            // var val = $(this).children().children().prop('checked');
            // var columns = $(this).closest("tr").children();
            // if (val == true) {
            //     columns.eq(9).children().prop('disabled', false)
            // } else {
            //     columns.eq(9).children().prop('disabled', true)
            // }
        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_confirmar', function(e) {
            var data = table.row(this).data();
            // 
            var columns = $(this).closest("tr").children();

            var abono = columns.eq(5).children().val();
            var fecha = columns.eq(8).children().val();
            // var cheque = columns.eq(7).children().children().prop('checked');
            var tipo = columns.eq(7).children()[0]["selectedOptions"][0]["value"];
            var banco = columns.eq(6).children()[0]["selectedOptions"][0]["value"];
            var banco_nombre = columns.eq(6).children()[0]["selectedOptions"][0]["innerText"];

            let param = {
                ID: data.ID,
                abono: abono,
                tipo: tipo,
                fecha: fecha,
                banco: banco,
                banco_nombre: banco_nombre,
                estado: 1,
                comentario_rechazo: ""
            }


            if (banco == "") {
                Mensaje("Debe seleccionar un banco", "", "error");
            } else if (tipo == "") {
                Mensaje("Debe seleccionar un tipo", "", "error");
            } else {

                if (parseFloat(abono) > parseFloat(data.Saldo)) {
                    Mensaje("El Valor a Abonar no puede ser mayor al saldo");

                } else if (abono <= 0) {
                    Mensaje("El Valor a Abonar no puede ser menor o igual a 0", "", "error");

                } else {
                    Swal.fire({
                        title: 'Esta Seguro!',
                        text: "Los Datos se Guardaran",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, continuar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (tipo == "cheque") {
                                fecha = moment(fecha).format("YYYYMMDD")
                            } else {
                                fecha = "";
                            }


                            Guardar_Deuda(param);
                        }
                    })
                }

            }

        });

        $("#Tabla_Deudas tbody").on("keyup", 'input', function(event) {
            var val = table.row(this).$(this).val();


        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_girar', function(e) {
            var data = table.row(this).data();


            Girar_Cheque(data);

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_rechazar', function(e) {
            var data = table.row(this).data();

            Rechazar(data);
        });
    }

    function Tabla_Cheques_girados(data) {

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
        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }
        // $('#Tabla_Deudas').empty();
        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-ligth',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(ESTADO_CARGA)

                    }
                }, {
                    text: `<span class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(1)

                    }
                },
                // {
                //     text: `<span class"fw-bolder">Pagados</span>`,
                //     className: 'btn btn-light-danger fs-2',
                //     action: function(e, dt, node, config) {
                //         Cargar_Deudas(5)

                //     }
                // }, 
                {
                    text: `<span class"fw-bolder">Cheques Girados</span>`,
                    className: 'btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_cheques()

                    }
                },
                $.extend(!0, {}, buttonCommon, {
                    extend: "excel",
                    title: "TextoSucursal",
                    messageTop: "Datos de Empleados Sucursal: ",
                    // exportOptions: {
                    //     columns: [0, 1]
                    // }
                })
            ],
            scrollY: '70vh',
            scrollCollapse: true,
            // paging: false,
            // info: false,
            "order": [
                [0, "desc"],
                [1, "desc"],
            ],

            columns: [{
                    data: "Fecha",
                    title: "Fecha de Creacion",
                    width: 120,
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD")
                    }

                }, {
                    data: "Número",
                    title: "Detalle",
                    width: 160,

                },

                {
                    data: "Tipo",
                    title: "Tipo",
                    width: 150,
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

                },
                {
                    data: "Valor",
                    title: "valor Cheque",
                    width: 120,
                },
                // {
                //     data: "Tipo",
                //     title: "Tipo",
                //     width: 120,

                // },
                {
                    data: "CreadoPor",
                    title: "Creado ",
                    width: 120,
                },
                {
                    data: null,
                    title: "",
                    className: "btn_detalles",
                    // id: "Boton()",
                    defaultContent: '<button type="button" id="btn_detalles" class="btn btn-danger">Entregado</button>',
                    orderable: "",
                    width: 20


                },

            ],


            "createdRow": function(row, data, index) {
                let tipo = "Numero";
                if (data["Tipo"] == "VALE") {
                    tipo = "Vale numero";
                } else if (data["Tipo"] == "PROV-ORDEN") {
                    tipo = "Orden dobra";
                }
                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Número"]) + `</span>
                    </div>
                `;

                let vencimiento;
                if (data["Tipo"] == "VALE" || data["Tipo"] == "PROV-ORDEN") {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6"></a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;

                } else {
                    vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                      
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                    </div>
                `;

                }

                let color = "warning";
                if (data["Empresa"] == "CARTIMEX") {
                    color = "primary";
                }
                let prv_nombre;
                if (data["Tipo"] == 'PROV-ORDEN') {
                    prv_nombre = data["proveedor_nombre"];
                } else {
                    prv_nombre = data["Detalle"];
                    data["Detalle"] = ""
                }


                let detalle = `

                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold mb-1 fs-6">` + prv_nombre + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["Detalle"] + `</span>
                        <span class="text-primary fw-semibold d-block fs-5">` + data["Empres"] + `</span>
                    </div>
                `;

                let valor = `
                
                    <div class="d-flex justify-content-start flex-column">
                         <span class="text-gray-700 fw-semibold d-block fs-5">Valor Entregado:</span>
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + formatter.format(data["Valor"]) + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Banco :</span>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["Banco"] + `</span>
                
                    </div>
                `;

                let fecha_sol = `

                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + moment(data["Fecha_Creado"]).format("hh:mm") + `</span>
                    </div>
                `;

                let CreadoPor = `

                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-700 fw-semibold d-block fs-5">` + data["CreadoPor"] + `</span>
                    <span class="text-gray-700 fw-semibold d-block fs-5">Nota  :</span>
                    <span class="text-gray-700 fw-semibold d-block fs-5">` + data["Nota"] + `</span>
                    </div>
                `;

                $('td', row).eq(0).html(fecha);
                $('td', row).eq(2).html(vencimiento);
                $('td', row).eq(1).html(detalle);
                $('td', row).eq(3).html(valor);
                $('td', row).eq(6).html(fecha_sol);
                $('td', row).eq(4).html(CreadoPor);
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(4).addClass("fw-bolder bg-light-info");
                $('td', row).eq(8).addClass("fw-bolder text-danger");

                let estado = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-danger fw-bold text-hover-primary mb-1 fs-6">PAGADO</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + data["girado_por"] + `</span>
                    </div>
                `;

                if (data["girado"] == 1) {
                    $('td', row).eq(8).removeClass("btn_girar");
                    $('td', row).eq(8).html(estado);
                }
            }

        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);


        $('#Tabla_Deudas tbody').on('click', 'td.btn_detalles', function(respuesta) {
            var data = table.row(this).data();
            console.log('data: ', data);
            Empresa_guardar = data["Empres"];
            ID = data["ID"];

            $("#Modal_archivo").modal("show");
            $("#Archivo_pfd").val('');
        })
    }

    //--------------------------
    function cambiarEstado() {

        let param = {
            EMPRESA: Empresa_guardar,
            ID: ID,
        }

        var archivo = $("#Archivo_pfd")[0].files;

        console.log('archivo: ', archivo.length);

        if (archivo.length == 0) {

            Mensaje("Debe Subir un archivo", "", "error");

        } else {

            console.log('param: ', param);
            Swal.fire({
                title: 'Esta Seguro!',
                text: "Los Datos se Guardaran",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continuar'
            }).then((result) => {

                if (result.isConfirmed) {
                    AjaxSendReceiveData($url_Actualizar, param, function(x) {
                        console.log('x: ', x);

                        Guardar_Adjunto(archivo[0]);
                    });

                    console.log('archivo: ', archivo);
                }
            });
        }
    }

    function Guardar_Adjunto(archivo) {
        function renameFile(originalFile, newName) {
            return new File([originalFile], newName, {
                type: originalFile.type,
                lastModified: originalFile.lastModified,
            });
        };

        var CON = 0;
        var _Carti = "";

        if (archivo == undefined) {

        } else {

            console.log('Empresa_guardar: ', Empresa_guardar);
            if (Empresa_guardar == 'CARTIMEX') {
                _Carti = "_CA"
            } else {
                _Carti = "_CO"
            }

            let ar1 = renameFile(archivo, ID + _Carti)
            guardarImgpdf(ar1);
            CON = CON + 1;

        }

        Mensaje("Datos Guardados con exito", "", "success");
        // location.reload();
        // setInterval(() => {
        //     setInterval(() => {
        //         // location.reload();
        //     }, 500);
        // }, 500);
    }

    function guardarImgpdf(data) {
        // var files = $('#' + campo)[0].files[0];
        var formData = new FormData();
        formData.append('file', data);
        // formData.append('doc', SAM_CASO);
        var param = {
            'file': data
        }
        // 

        $.ajax({
            url: url_Guardar_Documento,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('response: ', response);
                response = response.trim();
                if (response == "true") {
                    $("#Modal_archivo").modal("hide");
                    Cargar_cheques();

                }

                // response = response.replace(/^"(.+(?="$))"$/, '$1');
            }
        });
    }

    function Girar_Cheque(data) {
        console.log('data: ', data);

        let estado = null;
        if (data.Tipo == "VALE" || data.Tipo == "PROV-ORDEN") {
            estado = 2;
        }
        let param = {
            deuda_id: data.deuda_ID,
            estado: estado,
            comentario_rechazo: "",
            estado_girado: 1,
            rechazado: 0,
            DEUDA_DOBRA_ID: data.ID,
            DEUDA_ID: data.DEUDA_ID,
            tipo: (data.Tipo).trim(),
            empresa: data.Empresa
        }
        console.log('param: ', param);

        Swal.fire({
            title: 'Esta Seguro!',
            text: "Los Datos se Guardaran",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                AjaxSendReceiveData(url_Girar_Cheque, param, function(x) {
                    console.log('x: ', x);
                    Cargar_Deudas(ESTADO_CARGA);
                });
            }

        })

    }

    function mostrar_comentario(data) {
        // let ARR = [rw.comentario_rechazo,rw.Valor,rw.Banco_nombre,rw.Fcheque,rw.ischeque];
        data = data.split(",");

        $("#Modal_comentario").modal("show");
        $("#COMENTARIO").text(data[0]);
        $("#VALOR").text(parseFloat(data[1]).toFixed(2));
        $("#BANCO").text(data[2]);
        $("#FECHA").text(data[3]);
        $("#T_PAGO").text(data[4]);
        if (data[4] == "cheque") {
            $("#SECC_F").show();

        }
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