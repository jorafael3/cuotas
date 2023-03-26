<?php

$url_Cargar_informe = constant('URL') . 'informe/Cargar_informe/';
$url_Guardar_Datos = constant('URL') . 'principal/Guardar_Datos/';
$url_Buscar_Deudas = constant('URL') . 'informecompletos/Buscar_Deudas/';
$url_Guardar_Deudas = constant('URL') . 'informecompletos/Guardar_Deudas/';
$url_Girar_Cheque = constant('URL') . 'informe/Girar_Cheque/';
$url_Consultar_Bancos = constant('URL') . 'principal/Cargar_Bancos/';
$url_Detalles_Deudor = constant('URL') . 'informe/Detalles_Deudor/';
$url_Confirmar_Rechazado = constant('URL') . 'informe/Confirmar_Rechazado/';
$url_Actualizar_Bancos = constant('URL') . 'informecompletos/Actualizar_Bancos/';


?>

<script>
    var url_Cargar_informe = '<?php echo $url_Cargar_informe ?>';
    var url_Buscar_Deudas = '<?php echo $url_Buscar_Deudas ?>';
    var url_Guardar_Deudas = '<?php echo $url_Guardar_Deudas ?>';
    var url_Girar_Cheque = '<?php echo $url_Girar_Cheque ?>';
    var url_Consultar_Bancos = '<?php echo $url_Consultar_Bancos ?>';
    var url_Detalles_Deudor = '<?php echo $url_Detalles_Deudor ?>';
    var url_Confirmar_Rechazado = '<?php echo $url_Confirmar_Rechazado ?>';
    var url_Actualizar_Bancos = '<?php echo $url_Actualizar_Bancos ?>';


    var BANCOS_OPTION;
    var ESTADO_CARGA;
    var TOTAL_COL;
    var COL;
    var archivo;
    var EMPRESA = "CARTIMEX";
    var TIPO_DOC;
    var ARRAY_AGRUPADOS;

    function Mensaje(t1, t2, ic) {
        Swal.fire(
            t1,
            t2,
            ic
        );
    }

    const formatCurrency = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    });

    $("#customSwitch3").on('change', function(x) {
        EMPRESA = "CARTIMEX"
        // $("#TIPOS_DOC_COMPUTRON").hide();
        // $("#TIPOS_DOC_CARTIMEX").show()
    });

    $("#customSwitch4").on('change', function(x) {
        EMPRESA = "COMPUTRON"
        // $("#TIPOS_DOC_COMPUTRON").show();
        // $("#TIPOS_DOC_CARTIMEX").hide()
    });

    function Cargar_Informe() {
        let ini = $("#Fecha_desde").val();
        let fin = $("#Fecha_hasta").val();
        let param = {
            inicio: '20221227',
            fin: moment().format("YYYYMMDD")
        }
        AjaxSendReceiveData(url_Cargar_informe, param, function(x) {

            let informes = x[0];
            // let deudas = x[1];
            Tabla_informes(informes);


            // Tabla_Deudas(deudas)
        });
        AjaxSendReceiveData(url_Consultar_Bancos, [], function(bancos) {

            BANCOS_OPTION = "";
            bancos.map(function(x) {
                BANCOS_OPTION = BANCOS_OPTION + "<option value=" + x.ID + ">" + x.Nombre + "</option>";



            });
        });
    }

    function Cargar_Deudas(estado) {
        let tipo = $("#TIPOS_DOC_CARTIMEX").val()
        ESTADO_CARGA = estado
        console.log('ESTADO_CARGA: ', ESTADO_CARGA);
        let param = {
            EMPRESA: EMPRESA,
            TIPO: tipo
        }
        console.log('param: ', param);

        if (ESTADO_CARGA == 5) {
            param.TIPO = 5;
            console.log('param: ', param);

            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
                console.log('x: ', x);
                Tabla_Deudas_pagadas(x)

            });
        } else {
            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
                console.log('xdddd: ', x);
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

    function Tabla_informes(data) {
        console.log('data: ', data);
        var format = $.fn.dataTable.render.number(',', '.', 2, '$');
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function(data, row, column, node) {
                        //check if type is input using jquery
                        if (column == 5) {
                            return node.firstChild.tagName === "INPUT" ?
                                node.firstElementChild.value :
                                data;
                        } else if (column == 6) {
                            return node.innerText

                        } else {

                            return data
                        }

                    }
                },
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        };
        var disponile = data.reduce((sum, value) => (sum + parseFloat(value.saldo_disponible)), 0);
        var girar = data.reduce((sum, value) => (sum + parseFloat(value.deposito_dia)), 0);
        var TOTAL = disponile + girar;

        $('#Tabla_Pendientes').empty();
        let foot = `
            <tfoot>
                <tr>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                    <th style="font-size: 16px;" class="font-weight-bolder "></th>
                </tr>
            </tfoot>
        `
        $("#Tabla_Pendientes").append(foot);
        var table = $('#Tabla_Pendientes').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            // responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar </span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-success',
                    action: function(e, dt, node, config) {
                        Cargar_Informe()
                    }
                },
                $.extend(!0, {}, buttonCommon, {
                    extend: "excel",
                    title: "Saldos",
                    //messageTop: "Datos de Empleados Sucursal: ",
                    footer: true,
                    // exportOptions: {
                    //     columns: [0, 1]
                    // }
                })
            ],
            scrollY: '30vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            "order": [
                [7, "asc"]
            ],
            columns: [{
                    data: "Nombre",
                    title: "BANCO",
                    width: 400
                },
                {
                    data: "Empresa",
                    title: "Empresa",
                },

                {
                    data: "saldo_disponible",
                    title: "SALDO DISPONIBLE",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 200

                },
                {
                    data: "deposito_dia",
                    title: "A GIRAR",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 200

                }, {
                    data: "saldo_contable",
                    title: "SALDO CONTABLE",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    width: 200

                },
                {
                    data: "movimiento",
                    title: "MOVIMIENTO",
                    width: 150,
                    className: "dt-center  input-sas",
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            if (d == null) {

                                data = '<input onkeypress="return valideKey(event);" type="text" value="0.00" class="form-control input-sas">'
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
                    title: "TOTAL",
                    className: "tto",
                    render: function(data, type) {
                        if (type === 'display') {
                            let formatter = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                            });
                            let s = parseFloat(data["saldo_disponible"]) + parseFloat(data["deposito_dia"])
                            return formatter.format(s);
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: "posicion",
                    title: "posicion",
                    visible: false
                }
            ],
            "createdRow": function(row, data, index) {
                $('td', row).eq(0).addClass("fs-4 fw-bolder");
                $('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
                $('td', row).eq(1).addClass("fw-bolder bg-light-info");
                $('td', row).eq(2).addClass("fw-bolder bg-light-warning");
                $('td', row).eq(3).addClass("fw-bolder bg-light-success");
                $('td', row).eq(4).addClass("fw-bolder bg-light-primary");
                $('td', row).eq(6).addClass("fw-bolder");
                // let s = parseFloat(data["saldo_disponible"]) + parseFloat(data["deposito_dia"])
                // let formatCurrency = new Intl.NumberFormat('en-US', {
                //     style: 'currency',
                //     currency: 'USD'
                // });
                // $('td', row).eq(6).html(formatCurrency.format(s));
            },

            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                var disponible = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));

                    }, 0);
                var girar = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));

                    }, 0);
                var contable = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));

                    }, 0);
                var tot = api
                    .cells(null, 6, {
                        page: 'current'
                    })
                    .render('display')
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0)
                TOTAL_COL = tot;


                let formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                });

                $(api.column(0).footer()).html('Total');
                $(api.column(2).footer()).html(formatter.format(disponible));
                $(api.column(3).footer()).html(formatter.format(girar));
                $(api.column(4).footer()).html(formatter.format(contable));
                $(api.column(6).footer()).html(formatter.format(tot));
                //$(api.column(3).footer()).html(format(wedTotal));
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);

        $("#Tabla_Pendientes tbody").on("change", 'input', function(event) {
            var val = table.row(this).$(this).val();
            var valor = table.row(this).$(this).val();
            var data = table.row($(this).parents('tr')).data();
            var columns = $(this).closest("tr").children();
            let formatCurrency = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });
            val = val.replaceAll(",", "")

            console.log('val: ', val);
            if (val == "") {

                let s = parseFloat(data["saldo_disponible"]) + parseFloat(data["deposito_dia"])
                console.log('s: ', s);
                columns.eq(6).text(formatCurrency.format(s));
            } else {
                let B = parseFloat(data["saldo_disponible"]);
                let C = parseFloat(data["deposito_dia"]);
                let TOT = B + C + parseFloat(val)
                console.log('TOT: ', TOT);
                columns.eq(6).text(formatCurrency.format(TOT));

                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                var total = 0;
                table.cells(null, 5, {
                        page: 'all'
                    }).nodes()
                    .each(function(n) {
                        total += intVal($('input', n).val());
                    });

                $(table.column(5).footer()).html(formatCurrency.format(total));

                let TOTAL_ = 0;
                table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    var rowNode = this.node();
                    $(rowNode).find("td.tto").each(function() {
                        var cellData = $(this).html();
                        cellData = cellData.split("$")[1];
                        cellData = cellData.replace(",", "");

                        TOTAL_ = TOTAL_ + parseFloat(cellData)
                        //do something
                    });
                });

                $(table.column(6).footer()).html(formatCurrency.format(TOTAL_));

                // var api = table.row(this)

                // var tot = api
                //     .cells(null, 6, {
                //         page: 'current'
                //     })
                //     .render('display')
                //     .reduce(function(a, b) {
                //         
                //         return intVal(a) + intVal(b);
                //     }, 0)
                // 
            }
        });

        $("#Tabla_Pendientes tbody").on("keyup", 'input', function(event) {
            var val = table.row(this).$(this).val();

            var data = table.row($(this).parents('tr')).data();
            var columns = $(this).closest("tr").children();
            let formatCurrency = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });
            // $(event.target).val(function(index, value) {
            //     return value
            //         .replace(/\D/g, "")
            //         .replace(/([0-9])([0-9]{2})$/, '$1.$2')
            //         .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            // });
            val = val.replaceAll(",", "");
            console.log('val: ', val);

            if (val == "") {
                let s = parseFloat(data["saldo_disponible"]) + parseFloat(data["deposito_dia"])
                columns.eq(6).text(formatCurrency.format(s));
            } else {
                let B = parseFloat(data["saldo_disponible"]);
                let C = parseFloat(data["deposito_dia"]);
                let TOT = B + C + parseFloat(val)
                columns.eq(6).text(formatCurrency.format(TOT));
            }

        });
    }

    function valideKey(evt) {
        // code is the decimal ASCII representation of the pressed key.
        var code = (evt.which) ? evt.which : evt.keyCode;

        if (code == 8) { // backspace.
            return true;
        } else if (code >= 48 && code <= 57) { // is a number.
            return true;
        } else if (code == 45) { // is a number.
            return true;
        } else { // other keys.
            return false;
        }
    }

    function Tabla_Deudas(data) {

        var format = $.fn.dataTable.render.number(',', '.', 2, '$');
        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function(data, row, column, node) {
                        //check if type is input using jquery
                        if (column == 8 || column == 11) {
                            return node.firstChild.tagName === "INPUT" ?
                                node.firstElementChild.value :
                                data;
                        } else if (column == 9 || column == 10) {
                            console.log('node.firstElementChild.nodeName : ', node.firstElementChild);

                            if (node.firstChild.tagName === "SELECT") {
                                let a = node.firstElementChild;
                                a = $("#SEL_BAN option:selected").text();
                                return a;
                            } else {
                                return data;
                            }
                            // return node.firstChild.tagName === "SELECT" ?
                            //     node.firstElementChild.innerHTML :
                            //     data;
                        } else {
                            return data
                        }

                    }
                },

                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            }
        };
        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }
        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            // responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-ligth fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(ESTADO_CARGA)

                    }
                }, {
                    text: `<span class"fw-bolder">Todos</span>`,
                    className: 'btn btn-dark fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas("todo")

                    }
                }, {
                    text: `<span class"fw-bolder">Pendientes</span>`,
                    className: 'btn btn-light-info fw-bold fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(null)

                    }
                }, {
                    text: `<span  class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(1)

                    }
                }, {
                    text: `<span class"fw-bolder">Rechazados</span>`,
                    className: 'btn btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(3)

                    }
                }, {
                    text: `<span class"fw-bolder">Pagados</span>`,
                    className: 'btn btn-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(5)
                    }
                },
                {
                    text: 'Seleccionar todos',
                    className: 'btn btn-light-warning fs-2',
                    action: function() {
                        table.rows({
                            search: 'applied'
                        }).select();
                    }
                },
                {
                    text: 'cancelar seleccionados',
                    className: 'btn btn-light fs-2',
                    action: function() {
                        table.rows({
                            page: 'applied'
                        }).deselect();
                    }
                },
                {
                    text: `<span class"fw-bolder">APROBAR AGRUPADOS</span>`,
                    className: 'btn btn-success fs-2',
                    action: function(e, dt, node, config) {

                        var rows_selected = table.rows({
                            selected: true
                        }).data().toArray();
                        Guardar_Agrupados(rows_selected);

                    }
                }
                // $.extend(!0, {}, buttonCommon, {
                //     extend: "excel",
                //     title: "Deudas",
                //     messageTop: "Deudas",
                //     // exportOptions: {
                //     //     columns: [0, 1]
                //     // }
                // })
            ],
            // scrollY: '70vh',
            scrollCollapse: true,
            // paging: false,
            // info: false,
            pagelength: 50,
            "order": [
                [17, "desc"],
                // [2, "asc"],
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 18,
            }],
            select: {
                "style": "multi",
                //  selector: 'td:first-child',
            },
            drawCallback: function() {
                $('.select22').select2();
            },
            columns: [{
                    data: "Fecha",
                    title: "Fecha Deuda",
                    width: 150,

                },
                {
                    data: "Número",
                    title: "Número",
                    width: 150,
                    visible: false

                },
                {
                    data: "Detalle",
                    title: "Detalle",
                    width: 250

                }, {
                    data: "Empresa",
                    title: "Empresa",
                    // width: 250,
                    visible: false

                },
                {
                    data: "Tipo",
                    title: "Tipo",
                    // width: 150,
                    visible: false

                },
                {
                    data: "Vencimiento",
                    title: "Vencimiento",
                    width: 180,

                },
                {
                    data: "Valor",
                    title: "Valor",
                    // render: $.fn.dataTable.render.number(',', '.', 2, "$")
                    width: 220,

                }, {
                    data: "Saldo",
                    title: "Saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    visible: false

                }, {
                    data: null,
                    title: "Abono",
                    className: "dt-center  input-sas",
                    width: 190,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            let formatCurrency = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            });
                            if (d != null) {
                                if (data.estado == null) {
                                    data = '<input style="width:120px;" onkeypress="return valideKey(event);"type="tex" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'
                                } else if (data.estado == 3) {
                                    data = '<input  style="width:120px; onkeypress="return valideKey(event);"type="text" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'

                                } else if (data.estado == 1) {
                                    data = '<input disabled style="width:120px; onkeypress="return valideKey(event);"type="text" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'
                                } else {
                                    data = '<input disabled style="width:120px; onkeypress="return valideKey(event);"type="text" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'

                                }
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
                }, {
                    data: null,
                    title: "Bancos",
                    className: "dt-center  select_ban",
                    width: 180,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            if (d != null) {
                                if (data.estado == null || data.estado == 3) {
                                    data = `<select class="form-select select22">
                                                 <option value="">Seleccione</option>

                                                ` + BANCOS_OPTION + `
                                            </select>`

                                } else if (data.estado == 1 && row.Banco_id == '') {
                                    data = `<select class="form-select select22">
                                                 <option value="">Seleccione</option>

                                                ` + BANCOS_OPTION + `
                                            </select>`
                                } else {
                                    data = `<select disabled class="form-select">
                                                 <option selected value=" ` + row.Banco_id + `"> ` + row.Banco_nombre + `</option>

                                               
                                            </select>
                                            <span title=" ` + row.Banco_nombre + `">(?)</span>`
                                }
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
                }, {
                    data: null,
                    title: "Tipo de Pago",
                    className: "dt-center  select_ch",
                    width: 180,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            if (d != null) {
                                if (data.estado == null || data.estado == 3) {

                                    data = `<select class="form-select select_ch select22">
                                    <option value="">Seleccione</option>
                                    <option value="transferencia"> Transferencia </option>
                                    <option value="Efectivo"> Efectivo </option>
                                    <option value="cheque"> Cheque </option>
                                    <option value="cheque_al_dia"> Cheque al Dia </option>

                                    </select>`;


                                } else if (data.estado == 1 && row.Banco_id == '') {
                                    data = `<select class="form-select select_ch select22">
                                    <option value="">Seleccione</option>
                                    <option value="transferencia"> Transferencia </option>
                                    <option value="Efectivo"> Efectivo </option>
                                    <option value="cheque"> Cheque </option>
                                    <option value="cheque_al_dia"> Cheque al Dia </option>

                                    </select>`;
                                } else {
                                    data = `<select disabled class="form-select">
                                                 <option selected  value=" ` + row.ischeque + `"> ` + row.ischeque + `</option>                                              
                                            </select>`
                                }
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
                    title: "Fecha",
                    className: "dt-center  input-date",
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            let de = "";
                            if (d != null) {
                                if (data.estado != null) {
                                    if (data.ischeque == "cheque") {
                                        de = moment(data.Fcheque).format("YYYY-MM-DD")
                                    } else {
                                        de = "";
                                    }
                                    //    d = data.
                                }
                                data = `
                                <input disabled type="date" id="birthday" name="birthday" value="` + de + `">
                              `
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
                }, {
                    data: "estado",
                    title: "Estado",
                    visible: false
                },
                {
                    data: "estado",
                    title: "Estado",
                    render: function(data, row, rw) {

                        if (data == null) {

                            let ARR = [rw.SGO_COMENTARIO_PENDIENTE, rw.Valor, rw.Banco_nombre, rw.Fcheque, rw.ischeque];
                            let comen = rw.SGO_COMENTARIO_PENDIENTE;
                            if (comen == '-1') {
                                comen = "";
                            }

                            // }
                            return `<a href="#!" onclick="mostrar_comentario_Pendiente('` + ARR + `')" class="text-hover">
                                        <span class="text-info fw-bold text-hover d-block fs-5">PENDIENTE </span>
                                        <span class="text-gray-800 fw-bold text-hover d-block fs-6">` + comen + ` </span>
                                    </a>`
                        } else if (data == 1) {

                            let ARR = [rw.comentario_confirmar, rw.Valor, rw.Banco_nombre, rw.Fcheque, rw.ischeque];
                            let ban = "";
                            if (rw.Banco_id == "") {
                                ban = "(DEBE SELECCIONAR BANCO)"
                            }
                            return `<a href="#!" onclick="mostrar_comentario_rechazo('` + ARR + `')" class="text-hover">
                            <span class="text-primary fw-bold text-hover d-block fs-5">APROBADO POR:</span>
                            <span class="text-success fw-bold text-hover d-block fs-5">` + rw.SGO_APROBADO_POR + `</span>
                            <span class="text-danger fw-bold text-hover d-block fs-6">` + ban + `</span>
                            <span class="text-gray-800 fw-bold text-hover d-block fs-6">(` + rw.comentario_confirmar + `)</span>

                                    </a>`

                        } else if (data == 3) {

                            let ARR = [rw.comentario_rechazo, rw.Valor, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                            return `<a href="#!" onclick="mostrar_comentario_confirmado('` + ARR + `')" class="text-hover">
                            <span class="text-danger fw-bold text-hover d-block fs-5">RECHAZADO POR:</span>
                            <span class="text-success fw-bold text-hover d-block fs-5">` + rw.rechazado_por + `</span>
                            <span class="text-gray-800 fw-bold text-hover d-block fs-6">(` + rw.comentario_rechazo + `)</span>

                                    </a>`

                        }
                    }
                }, {
                    data: null,
                    title: "Aprobar",
                    className: "btn_confirmar",
                    defaultContent: `
                    <button class="btn btn-success btn-sm btn_confirmar"> 
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </button>
                    `,
                    orderable: false,
                    width: 20
                },
                {
                    data: null,
                    title: "Rechazar",
                    className: "btn_rechazar",
                    defaultContent: `
                    <button class="btn btn-danger btn-sm btn_rechazar"> 
                        <i class="bi bi-x-square-fill fs-2"></i>
                    </button>
                    `,
                    orderable: false,
                    width: 20
                }, {
                    data: "SGO_CONFIRMAR",
                    title: "SGO_CONFIRMAR",
                    visible: false
                }, {
                    data: "SGO_FECHA_SOLICITADO",
                    title: "SGO_FECHA_SOLICITADO",
                    visible: false
                },
                {
                    data: null,
                    title: "Seleccionar",
                    render: function(x) {
                        return "";
                    }
                },
                // {
                //     data: null,
                //     title: "Girar",
                //     render: function(x) {
                //         return "";

                //     }
                // }

            ],

            "createdRow": function(row, data, index) {

                if (data["SGO_CONFIRMAR"] == 1) {
                    $(row).addClass('bg-light-success');
                }
                let tipo = "Deuda numero";
                if (data["Tipo"] == "VALE") {
                    tipo = "Vale numero";
                } else if (data["Tipo"] == "PROV-ORDEN") {
                    tipo = "Orden dobra";
                }

                let fecha = `
                    <div class="d-flex justify-content-start flex-column">

                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-5">` + tipo + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-6">` + (data["Número"]) + `</span>
                        <span class="text-info fw-semibold d-block fs-6">SOLICITADO POR:</span>
                        <span class="text-success fw-semibold d-block fs-6">` + (data["DOC_SOLICITADO_POR"]) + `</span>

                    </div>
                `;

                var VEN;
                var VEN_T = "Vencimiento";
                if (data["Tipo"] == 'VALE' || data["Tipo"] == 'PROV-ORDEN') {
                    VEN = "";
                    VEN_T = ""

                } else {

                    VEN = moment(data["Vencimiento"]).format("YYYY-MM-DD");
                }

                let vencimiento = `

                    <div class="d-flex justify-content-start flex-column">
                        <span class="text-gray-700 fw-semibold d-block fs-5">` + VEN_T + `</span>
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + VEN + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                           
                    </div>
                `;

                let color = "warning";
                if (data["Empresa"] == "CARTIMEX") {
                    color = "primary";
                }

                let link = '<?php echo constant("URL") ?>recursos/documentos/' + data["archivo"];
                if (data["archivo"] == null || data["archivo"] == "") {
                    link = "#!";
                    data["archivo"] = "";
                }

                let ARR_D = [data["Tipo"], data["Número"], data["Empresa"], data["Saldo"], data["archivo"], data["SGO_COMENTARIO_PENDIENTE"]];

                let detalle;

                if (data["SGO_COMENTARIO_PENDIENTE"] == -1 && data["Empresa"] == 'CARTIMEX') {

                    if (data["Tipo"] == "VALE") {
                        detalle = `

                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                            <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                            <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + (data["archivo"]) + `</a>
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["SGO_COMENTARIO_PENDIENTE"]) + `</a>
                        </div>

                        `;

                    } else if (data["Tipo"] == "PROV-ORDEN") {
                        detalle = `
                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + (data["proveedor_nombre"]) + `</a>
                            <span class="text-gray-600 fw-semibold d-block fs-6">` + (data["Detalle"]) + `</span>
                            <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                             <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + (data["archivo"]) + `</a>
                        </div>
                        `;

                    } else {

                        detalle = `
                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["proveedor_nombre"]) + `</a>
                            <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                            <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + (data["archivo"]) + `</a>
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["SGO_COMENTARIO_PENDIENTE"]) + `</a>
                        </div>
                        `;
                    }

                } else {

                    if (data["Empresa"] == 'COMPUTRON') {
                        detalle = `
                            <div class="d-flex justify-content-start flex-column">
                                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                                <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                                <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + (data["archivo"]) + `</a>
                            </div>
                        `;

                    } else {
                        link2 = '<?php echo constant("URL") ?>recursos/deudas/' + data["archivo"];

                        detalle = `
                            <div class="d-flex justify-content-start flex-column">
                                <a href="#!" onclick="datos_Deudor('` + ARR_D + `')" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                                <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                                <a href="` + link2 + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["archivo"]) + `</a>
                            </div>
                        `;
                    }

                }

                let valor = `

                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-700 fw-semibold d-block fs-4">Valor deuda</span>
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + formatCurrency.format(data["Valor"]) + `</a>
                        <span class="text-info fw-semibold d-block fs-4">Saldo a Pagar</span>
                        <span class="text-gray-700 fw-semibold d-block fs-4">` + formatCurrency.format(data["Saldo"]) + `</span>
                    </div>
                `;

                $('td', row).eq(0).html(fecha);
                $('td', row).eq(2).html(vencimiento);
                $('td', row).eq(1).html(detalle);
                $('td', row).eq(3).html(valor);
                $('td', row).eq(5).addClass("fs-4 fw-bolder");
                $('td', row).eq(1).addClass("fw-bolder");
                $('td', row).eq(2).addClass("fw-bolder");
                $('td', row).eq(3).addClass("fw-bolder");
                $('td', row).eq(8).addClass("fw-bolder");

                if (data["estado"] != null) {
                    $('td', row).eq(9).removeClass("btn_confirmar");
                    $('td', row).eq(9).html("");
                    if (data["Banco_id"] == '') {
                        let G_banco = `
                            <button class="btn btn-warning btn-sm btn_confirmar"> 
                                <i class="bi bi-check-circle-fill fs-2"></i>
                            </button>
                    `;
                        $('td', row).eq(9).html(G_banco);
                        $('td', row).eq(9).addClass("btn_actualizar_b");

                    }
                    // $('td', row).eq(12).html(`
                    //     <button class="btn btn-primary btn-sm btn_girar">
                    //         <i class="bi bi-arrow-clockwise fs-2"></i>
                    //     </button>
                    // `);
                    // $('td', row).eq(12).addClass("btn_girar");
                }

                if (data["estado"] == 3) {
                    $('td', row).eq(10).removeClass("btn_rechazar");
                    $('td', row).eq(10).html("");
                    // $('td', row).eq(12).removeClass("btn_girar");
                    // $('td', row).eq(12).html("");
                    $('td', row).eq(9).html(`
                      <button class="btn btn-success btn-sm btn_confirmar"> 
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </button>
                    `);
                    $('td', row).eq(9).addClass("btn_confirmar");

                }

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
                columns.eq(7).children().prop('disabled', true)
            } else if (val == "cheque") {
                columns.eq(7).children().prop('disabled', false)
            } else {
                columns.eq(7).children().prop('disabled', true)
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
            console.log('data: ', data);
            // 
            var columns = $(this).closest("tr").children();
            var abono = columns.eq(4).children().val();
            abono = abono.replaceAll(",", "");
            console.log('abono: ', abono);
            console.log('Saldo: ', data.Saldo);
            var fecha = columns.eq(7).children().val();
            if (fecha != "") {
                fecha = moment(fecha).format("YYYYMMDD");
            }
            // var cheque = columns.eq(7).children().children().prop('checked');
            var tipo = columns.eq(6).children()[0]["selectedOptions"][0]["value"];
            var banco = columns.eq(5).children()[0]["selectedOptions"][0]["value"];
            var banco_nombre = columns.eq(5).children()[0]["selectedOptions"][0]["innerText"];



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
                        confirmButtonText: 'Si, continuar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Ingrese el motivo',
                                input: 'textarea',
                                inputAttributes: {
                                    autocapitalize: 'off'
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Confirmar',
                                showLoaderOnConfirm: true,
                                preConfirm: (login) => {
                                    return login;
                                },
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    let motivo = result.value;
                                    if (data.deuda_ID == null) {
                                        let param = {
                                            ID: data.ID,
                                            deuda_ID: data.deuda_ID,
                                            abono: abono,
                                            tipo: tipo,
                                            fecha: fecha,
                                            banco: banco,
                                            banco_nombre: banco_nombre,
                                            estado: 1,
                                            comentario_confirmar: motivo,
                                            comentario_rechazo: "",
                                            rechazado: 0,
                                            valor_deuda_total: data.Valor,
                                            saldo_agrupado: data.Saldo,
                                            acr_deuda_id: data.ID,
                                            deuda_numero: data["Número"],
                                            sgo_confirmar: data.SGO_CONFIRMAR,
                                            agrupado: 0

                                        }
                                        console.log('param: ', param);

                                        if (tipo == "cheque") {
                                            fecha = moment(fecha).format("YYYYMMDD")
                                        } else {
                                            fecha = "";
                                        }


                                        Guardar_Deuda(param);
                                    } else {
                                        let param = {
                                            ID: data.ID,
                                            deuda_ID: data.deuda_ID,
                                            abono: abono,
                                            tipo: tipo,
                                            fecha: fecha,
                                            banco: banco,
                                            banco_nombre: banco_nombre,
                                            estado: 1,
                                            comentario_confirmar: motivo,
                                            comentario_rechazo: "",
                                            rechazado: 0
                                        }
                                        AjaxSendReceiveData(url_Confirmar_Rechazado, param, function(x) {
                                            console.log('x: ', x);

                                            Cargar_Deudas(ESTADO_CARGA);
                                        });
                                    }

                                }
                            });

                        }
                    })
                }

            }

        });

        $("#Tabla_Deudas tbody").on("keyup", 'input', function(event) {
            var val = table.row(this).$(this).val();
            var data = table.row().data();
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        });

        $("#Tabla_Deudas tbody").on("change", 'input', function(event) {
            var val = table.row(this).$(this).val();
            var data = table.row($(this).parents('tr')).data();
            data.SGO_ABONO = val;
        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_girar', function(e) {
            var data = table.row(this).data();

            Girar_Cheque(data);

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_actualizar_b', function(e) {
            var data = table.row(this).data();
            console.log('data: ', data);
            var columns = $(this).closest("tr").children();
            var tipo = columns.eq(6).children()[0]["selectedOptions"][0]["value"];
            var banco = columns.eq(5).children()[0]["selectedOptions"][0]["value"];
            var banco_nombre = columns.eq(5).children()[0]["selectedOptions"][0]["innerText"];
            var fecha = columns.eq(7).children().val();
            if (fecha != "") {
                fecha = moment(fecha).format("YYYYMMDD");
            }

            let param = {
                tipo: tipo,
                banco: banco,
                banco_nombre: banco_nombre,
                deuda_ID: data.deuda_ID,
                fecha: fecha
            };

            if (banco == "") {
                Mensaje("Debe seleccionar un banco", "", "info");
            } else if (tipo == "") {
                Mensaje("Debe seleccionar un Tipo", "", "info");
            } else {
                if (tipo == "cheque") {
                    console.log('tipo: ', tipo);
                    if (fecha == "") {
                        Mensaje("Debe seleccionar una fecha", "", "info");
                    } else {
                        Actualizar_bancos(param)
                    }
                } else {
                    param.fecha = "";
                    Actualizar_bancos(param)
                }
            }

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_rechazar', function(e) {
            var data = table.row(this).data();
            var columns = $(this).closest("tr").children();
            var abono = columns.eq(4).children().val();
            abono = abono.replaceAll(",", "");
            var tipo = columns.eq(6).children()[0]["selectedOptions"][0]["value"];
            var banco = columns.eq(5).children()[0]["selectedOptions"][0]["value"];
            var banco_nombre = columns.eq(5).children()[0]["selectedOptions"][0]["innerText"];
            var fecha = columns.eq(7).children().val();
            if (fecha != "") {
                fecha = moment(fecha).format("YYYYMMDD");
            }
            let param = {
                ID: data.ID,
                deuda_ID: data.deuda_ID,
                abono: abono,
                tipo: tipo,
                fecha: fecha,
                banco: banco,
                banco_nombre: banco_nombre,
                estado: 3,
                comentario_confirmar: "",
                comentario_rechazo: "",
                rechazado: 1,
                valor_deuda_total: data.Valor,
                acr_deuda_id: data.ID,
                deuda_numero: data["Número"]
            }
            Rechazar(param);
        });

    }

    function Tabla_Deudas_pagadas(data) {
        console.log('data: ', data);

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
        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }
        // $('#Tabla_Deudas').empty();
        var table = $('#Tabla_Deudas').DataTable({
            destroy: true,
            data: data,
            dom: 'Bfrtip',
            // responsive: true,
            deferRender: true,
            buttons: [{
                    text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                    className: 'btn btn-ligth fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(ESTADO_CARGA)

                    }
                }, {
                    text: `<span class"fw-bolder">Todos</span>`,
                    className: 'btn btn-dark fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas("todo")

                    }
                }, {
                    text: `<span class"fw-bolder">Pendientes</span>`,
                    className: 'btn btn-light-info fw-bold fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(null)

                    }
                }, {
                    text: `<span  class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(1)

                    }
                }, {
                    text: `<span class"fw-bolder">Rechazados</span>`,
                    className: 'btn btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(3)

                    }
                }, {
                    text: `<span class"fw-bolder">Pagados</span>`,
                    className: 'btn btn-danger fs-2',
                    action: function(e, dt, node, config) {
                        Cargar_Deudas(5)
                    }
                }
                // $.extend(!0, {}, buttonCommon, {
                //     extend: "excel",
                //     title: "Deudas",
                //     messageTop: "Deudas",
                //     // exportOptions: {
                //     //     columns: [0, 1]
                //     // }
                // })
            ],
            scrollY: '70vh',
            scrollCollapse: true,
            paging: false,
            // info: false,
            "order": [
                [11, "asc"],
                [0, "desc"],
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
                    width: 150

                },
                {
                    data: "Tipo",
                    title: "Tipo",
                    visible: false
                },
                {
                    data: "Valor",
                    title: "Valor",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")

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

                }, {
                    data: "fecha_girado",
                    title: "Fecha Pagado",

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
                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Orden #</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Número"]) + `</span>
                        
                    </div>
                `;
                let vencimiento;

                if (data["Tipo"] == "VALE") {
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
                } else if (data["Tipo"] == 'PROV-ORDEN') {
                    data["Detalle"] = data["proveedor_nombre"]
                }

                let link = '<?php echo constant("URL") ?>recursos/documento/' + data["archivo"];
                if (data["archivo"] == null || data["archivo"] == "") {
                    link = "#!";
                    data["archivo"];
                }

                let detalle = `

                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                        <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
                        <a href="` + link + `" target="_blank"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + data["archivo"] + `</a>
                    </div>
                `;

                let valor = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + formatCurrency.format(data["Valor"]) + `</a>
                        <span class="text-gray-700 fw-semibold d-block fs-5">Saldo:</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">` + formatCurrency.format(data["Saldo"]) + `</span>
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
                let Estado = `

                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                        <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span> 
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

                if (data["girado"] == 1) {
                    $('td', row).eq(8).removeClass("btn_girar");
                    $('td', row).eq(8).html("<span>PAGADO</span>");

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
            console.log('data: ', data);

            Girar_Cheque(data);

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_rechazar', function(e) {
            var data = table.row(this).data();

            Rechazar(data);
        });
    }

    function mostrar_comentario_rechazo(data) {
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

    function mostrar_comentario_confirmado(data) {
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

    function mostrar_comentario_Pendiente(data) {
        console.log('data: ', data);
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

    function datos_Deudor(data) {

        data = data.split(",");
        let param = {
            tipo: data[0].trim(),
            numero: data[1].trim(),
            empresa: data[2].trim(),
        }

        AjaxSendReceiveData(url_Detalles_Deudor, param, function(x) {

            let formatCurrency = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });
            let CAB = x[0][0];
            let DET1 = x[1];
            let DET2 = x[2];
            $("#Modal_Deudor").modal("show");
            $("#Fecha").text(moment(CAB.Fecha).format("YYYY-MM-DD"));
            $("#Codigo").text(CAB["Código"]);
            $("#Nombre").text(CAB.Nombre);
            $("#Tipo").text(CAB.Tipo);
            $("#Dias").text(CAB["Días"]);
            $("#Valor").text(formatCurrency.format(CAB.Valor));
            $("#Saldo").text(formatCurrency.format(data[3]));
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
            responsive: true,
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
                    title: "Stock actual",
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
                $('td', row).eq(3).addClass("fw-bolder bg-light-warning");
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
            responsive: true,
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

    function Girar_Cheque(data) {
        let param = {
            deuda_id: data.deuda_ID,
            estado: null,
            comentario_rechazo: "",
            estado_girado: 1,
            rechazado: 0

        }
        Swal.fire({
            title: 'Esta Seguro!',
            text: "Los Datos se Guardaran",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, continuar'
        }).then((result) => {
            AjaxSendReceiveData(url_Girar_Cheque, param, function(x) {

                Cargar_Deudas(ESTADO_CARGA);
            });
        })

    }

    function Guardar_Deuda(data) {
        AjaxSendReceiveData(url_Guardar_Deudas, data, function(x) {
            console.log('x: ', x);
            if (x == true) {
                Cargar_Deudas(ESTADO_CARGA);
            }
        });
    }

    function Actualizar_bancos(datos) {
        console.log('datos: ', datos);
        Swal.fire({
            title: 'Estas Segurp?',
            text: "Se actualizara la informacion seleccionada!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Continuar!'
        }).then((result) => {
            if (result.isConfirmed) {
                AjaxSendReceiveData(url_Actualizar_Bancos, datos, function(x) {
                    if (x == true) {
                        Cargar_Deudas(ESTADO_CARGA);
                    } else {
                        Mensaje("Error al guardar", "", "error");
                    }
                });
            }
        })


    }

    function Rechazar(data) {
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

                Swal.fire({
                    title: 'Ingrese el motivo',
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Rechazar',
                    showLoaderOnConfirm: true,
                    preConfirm: (login) => {
                        return login;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        let motivo = result.value;
                        let ID_VALE = data.Valecaja_ID;

                        if (data.deuda_ID == null) {
                            data.comentario_rechazo = motivo;

                            console.log('param: ', data);

                            Guardar_Deuda(data);

                        } else {
                            let param = {
                                deuda_id: data.deuda_ID,
                                estado: 3,
                                comentario_rechazo: motivo,
                                estado_girado: 0,
                                rechazado: 1
                            }
                            console.log('param: ', param);

                            AjaxSendReceiveData(url_Girar_Cheque, param, function(x) {

                                Cargar_Deudas(ESTADO_CARGA);
                            });
                        }
                        // AjaxSendReceiveData(url_Rechazar_Vale, param, function(x) {
                        //     
                        //     if (x == true) {
                        //         Cargar_Vales(FECHA);
                        //     }
                        // });
                    }
                });
            }
        })
    }

    function Guardar_Agrupados(data) {
        $("#AGR_FECHA").prop('disabled', true)
        $("#AGR_FECHA").val("");
        console.log('data: ', data);
        if (data.length == 0) {
            Mensaje("No ha seleccionado datos para agrupar", "", "info")
        } else {
            let dataFiltrada = [];
            dataFiltrada = data.filter(cat => cat.estado == null || cat.estado == 3);
            if (dataFiltrada.length == 0) {
                Mensaje("No hay datos validos para guardar", "solo se pueden agrupar ordenes pendientes", "info")
            } else {
                $("#Modal_Agruapdo").modal("show");
                console.log('dataFiltrada: ', dataFiltrada);
                ARRAY_AGRUPADOS = [];
                ARRAY_AGRUPADOS = dataFiltrada;
                $("#SET_SELECT").empty();
                $("#SET_Tipo").empty();
                let select = `<select class="form-select select22" id="AGR_BANCO">
                                    <option value="">Seleccione</option>
                                     ` + BANCOS_OPTION + `
                            </select>`
                let tipo = `<select class="form-select select22" id="AGR_TIPO">
                                    <option value="">Seleccione</option>
                                    <option value="banco"> Banco </option>
                                    <option value="transferencia"> Transferencia </option>
                                    <option value="cheque"> Cheque</option>
                                    <option value="cheque_al_dia"> Cheque al Dia </option>
                            </select>`;
                $("#SET_SELECT").append(select);
                // $("#SET_Tipo").append(tipo);

                $("#AGR_TIPO").on("change", function(x) {
                    let val = $("#AGR_TIPO").val();
                    if (val == "cheque") {
                        $("#AGR_FECHA").prop('disabled', false)
                    } else {
                        $("#AGR_FECHA").prop('disabled', true)

                    }

                });
                $('.select22').select2({
                    dropdownParent: $('#Modal_Agruapdo')
                });


            }


        }

    }

    $("#AGR_GUARDAR").on("click", function(x) {
        let banco = $("#AGR_BANCO").val();
        let banco_nombre = $("#AGR_BANCO option:selected").text();
        let tipo = $("#AGR_TIPO").val();
        let fecha = $("#AGR_FECHA").val();
        let comen = $("#AGR_COMEN").val();

        let Acreedor_ID = [...new Set(ARRAY_AGRUPADOS.map(item => (item.ID).trim()))];
        let numero = [...new Set(ARRAY_AGRUPADOS.map(item => (item["Número"]).trim()))];
        let SUMA_ABONOS = ARRAY_AGRUPADOS.reduce((sum, value) => (sum + parseFloat(value.SGO_ABONO)), 0);
        let SUMA_SALDO = ARRAY_AGRUPADOS.reduce((sum, value) => (sum + parseFloat(value.Saldo)), 0);
        let SUMA_DEUDA = ARRAY_AGRUPADOS.reduce((sum, value) => (sum + parseFloat(value.Valor)), 0);


        let param = {
            ID: Acreedor_ID[0],
            // deuda_ID: data.deuda_ID,
            abono: SUMA_ABONOS,
            tipo: tipo,
            fecha: fecha,
            banco: banco,
            banco_nombre: banco_nombre,
            estado: 1,
            comentario_confirmar: comen,
            comentario_rechazo: "",
            rechazado: 0,
            valor_deuda_total: SUMA_DEUDA,
            saldo_agrupado: SUMA_SALDO,
            acr_deuda_id: Acreedor_ID.toString(),
            deuda_numero: numero.toString(),
            agrupado: 1,
            empresa: ARRAY_AGRUPADOS[0]["Empresa"]

            // sgo_confirmar: data.SGO_CONFIRMAR
        }
        // console.log('param: ', param);

        if (tipo == "cheque") {
            param.fecha = moment(fecha).format("YYYYMMDD")
        } else {
            param.fecha = "";
        }
        if (banco == "") {
            Mensaje("Debe seleccionar un Banco", "", "error");

        } else if (tipo == "") {
            Mensaje("Debe seleccionar un tipo", "", "error");
        } else {
            console.log('param: ', param);
            Guardar_Deuda_Agrupados(param);
        }
    });

    function Guardar_Deuda_Agrupados(data) {
        AjaxSendReceiveData(url_Guardar_Deudas, data, function(x) {
            console.log('x: ', x);
            if (x[0] == true) {
                Cargar_Deudas(ESTADO_CARGA);
            }
        });
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