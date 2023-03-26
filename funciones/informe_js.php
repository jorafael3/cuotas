<?php

$url_Cargar_informe = constant('URL') . 'informe/Cargar_informe/';
$url_Guardar_Datos = constant('URL') . 'principal/Guardar_Datos/';
$url_Buscar_Deudas = constant('URL') . 'informe/Buscar_Deudas/';
$url_Guardar_Deudas = constant('URL') . 'informe/Guardar_Deudas/';
$url_Girar_Cheque = constant('URL') . 'informe/Girar_Cheque/';
$url_Consultar_Bancos = constant('URL') . 'principal/Cargar_Bancos/';
$url_Detalles_Deudor = constant('URL') . 'informe/Detalles_Deudor/';
$url_Confirmar_Rechazado = constant('URL') . 'informe/Confirmar_Rechazado/';

?>

<script>
    var url_Cargar_informe = '<?php echo $url_Cargar_informe ?>';
    var url_Buscar_Deudas = '<?php echo $url_Buscar_Deudas ?>';
    var url_Guardar_Deudas = '<?php echo $url_Guardar_Deudas ?>';
    var url_Girar_Cheque = '<?php echo $url_Girar_Cheque ?>';
    var url_Consultar_Bancos = '<?php echo $url_Consultar_Bancos ?>';
    var url_Detalles_Deudor = '<?php echo $url_Detalles_Deudor ?>';
    var url_Confirmar_Rechazado = '<?php echo $url_Confirmar_Rechazado ?>';

    var BANCOS_OPTION;
    var ESTADO_CARGA;
    var TOTAL_COL;
    var COL;
    var ARRAY_DATA_TOTAL = [];
    var PRV_FILTRO;
    var DEUDAS_FILTRO;

    var EMPRESA = "CARTIMEX";
    var TIPO_DOC;

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
    $("#sw_O").on('click', function(x) {
        Cargar_Deudas(2);

    });
    $("#sw_V").on('click', function(x) {
        Cargar_Deudas(4);

    });
    $("#sw_DP").on('click', function(x) {

        Cargar_Deudas(1);
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
            TIPO: ESTADO_CARGA
        }
        console.log('param: ', param);

        if (ESTADO_CARGA == 5) {
            param.TIPO = 5;
            console.log('param: ', param);

            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
                console.log('x: ', x);
                Tabla_Deudas_pagadas(x);
            });
        } else {
            AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
                console.log('x: ', x);
                // if (ESTADO_CARGA == "todo") {
                ARRAY_DATA_TOTAL = [];
                ARRAY_DATA_TOTAL = x;

                let unique = [...new Set(x.map(item => item.Proveedor_ID + "|" + item.proveedor_nombre + "|" + item.Tipo))];
                let DATA_AGRUPADA = [];
                unique.map(function(data) {
                    let cod = data.split("|")[0];
                    let nombre = data.split("|")[1];
                    let tipo = data.split("|")[2];
                    // let detalle = data.split("|")[3];
                    let dataFiltrada;
                    dataFiltrada = x.filter(cat => cat.Proveedor_ID == cod);
                    var suma = dataFiltrada.reduce((sum, value) => (sum + parseFloat(value.Saldo)), 0);
                    var PENDIENTES = dataFiltrada.filter(cat => cat.estado == null).length;
                    var APROBADAS = dataFiltrada.filter(cat => cat.estado == 1).length;
                    var RECHAZADAS = dataFiltrada.filter(cat => cat.estado == 3).length;
                    if (nombre == 'null') {
                        console.log('nombre: ', nombre);
                        nombre = dataFiltrada[0]["Detalle"]
                    }
                    let fecha_pedido = new Date(Math.max(...dataFiltrada.map(ele => {
                        if (ele.SGO_FECHA_SOLICITADO != null) {
                            let f = new Date(ele.SGO_FECHA_SOLICITADO);
                            return f;
                        } else {
                            return "";
                        }
                    })));

                    let b = {
                        proveedor_id: cod,
                        proveedor_nombre: nombre,
                        Saldo: suma,
                        cantidad: dataFiltrada.length,
                        fecha_pedido: moment(fecha_pedido).format("YYYY-MM-DD hh:mm A"),
                        hora_pedido: moment(fecha_pedido).format("hh:mm A"),
                        PENDIENTES: PENDIENTES,
                        APROBADAS: APROBADAS,
                        RECHAZADAS: RECHAZADAS,
                    }

                    DATA_AGRUPADA.push(b);
                    $("#SECC_TABLA_RESUMEN").show()
                    $("#SECC_TABLA_DEUDAS").hide()
                    //     }
                    // }
                });


                DATA_AGRUPADA.sort((a, b) => {

                    if (a.fecha_pedido < b.fecha_pedido) {
                        return -1;
                    }
                    if (a.fecha_pedido > b.fecha_pedido) {
                        return 1;
                    }
                })
                console.log('DATA_AGRUPADA: ', DATA_AGRUPADA.reverse());
                DATA_AGRUPADA.map(function(x, y) {
                    x.pos = y;
                    return x
                })
                Table_resumen(DATA_AGRUPADA);

                // Tabla_Deudas(x);
                // } else {
                //     ARRAY_DATA_TOTAL = x;
                //     let dataFiltrada = x.filter(cat => cat.estado == ESTADO_CARGA);
                //     // Tabla_Deudas(dataFiltrada);
                // }
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

    function Table_resumen(data) {
        $('#Tabla_Resumen').empty();
        var table = $('#Tabla_Resumen').DataTable({
            destroy: true,
            data: data,
            dom: 'frtip',
            responsive: true,
            deferRender: true,
            scrollY: '50vh',
            scrollCollapse: true,
            paging: false,
            info: false,
            "order": [
                [0, "desc"]
            ],
            columns: [{
                    data: null,
                    title: "",
                    className: "btn_next",
                    defaultContent: `
                    <button class="btn btn-success btn-sm btn_confirmar"> 
                        <i class="bi bi-arrow-right fs-2"></i>
                    </button>`,
                }, {
                    data: "proveedor_nombre",
                    title: "Proveedor",
                },
                {
                    data: "cantidad",
                    title: "Cantidad de Facturas",
                },
                {
                    data: "PENDIENTES",
                    title: "PENDIENTES",
                },
                {
                    data: "APROBADAS",
                    title: "APROBADAS",
                },
                {
                    data: "RECHAZADAS",
                    title: "RECHAZADAS",
                },
                {
                    data: "fecha_pedido",
                    title: "Fecha",
                }, {
                    data: "Saldo",
                    title: "Saldo Total",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                }
            ],
            "createdRow": function(row, data, index) {
                $('td', row).eq(0).addClass("fw-bolder");
                $('td', row).eq(1).addClass("fw-bolder fs-3");
                $('td', row).eq(2).addClass("fw-bolder fs-2 bg-light-success");
                $('td', row).eq(3).addClass("fw-bolder fs-5 bg-light-info");
                $('td', row).eq(4).addClass("fw-bolder fs-5 bg-light-primary");
                $('td', row).eq(5).addClass("fw-bolder fs-5 bg-light-danger");
                $('td', row).eq(7).addClass("fw-bolder fs-3");


                if (data["pos"] == 0) {
                    let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(new Date(data["fecha_pedido"])).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-5">` + moment(new Date(data["fecha_pedido"])).format("hh:mm A") + `  </span>
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                         <i class="fa fa-bell icon fs-1 text-danger"></i>
                    </div>

                        `;
                    $('td', row).eq(6).html(fecha);

                } else {
                    let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(new Date(data["fecha_pedido"])).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-5">` + moment(new Date(data["fecha_pedido"])).format("hh:mm A") + `</span>
                    </div>
                        `;
                    $('td', row).eq(6).html(fecha);
                    if (data["fecha_pedido"] <= '1969-12-31 07:00 PM') {
                        $('td', row).eq(6).html("");
                    }

                }
            }
        });
        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 500);

        $('#Tabla_Resumen tbody').on('click', 'td.btn_next', function(e) {
            var data = table.row(this).data();
            PRV_FILTRO = data["proveedor_id"];
            Filtrar_por_proveedor(PRV_FILTRO)
            $("#SECC_TABLA_RESUMEN").hide()
            $("#SECC_TABLA_DEUDAS").show()

            $('html, body').animate({
                scrollTop: $("#SECC_SCROLL").offset().top
            }, 0);
        });

    }

    function Filtrar_por_proveedor(pr_i) {
        let dataFiltrada = ARRAY_DATA_TOTAL.filter(cat => cat.Proveedor_ID == pr_i);
        console.log('dataFiltrada: ', dataFiltrada);
        Tabla_Deudas(dataFiltrada);

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
                    text: `<span class"fw-bolder"></span> 
                    <i class="bi bi-arrow-90deg-left"></i>`,
                    className: 'btn btn-success fs-2',
                    action: function(e, dt, node, config) {
                        $("#SECC_TABLA_RESUMEN").show()
                        $("#SECC_TABLA_DEUDAS").hide()
                        $('html, body').animate({
                            scrollTop: $("#SECC_SCROLL").offset().top
                        }, 0);
                    }
                },
                // {
                //     text: `<span class"fw-bolder">Refrescar</span> <i class="bi bi-arrow-clockwise"></i>`,
                //     className: 'btn btn-ligth fs-2',
                //     action: function(e, dt, node, config) {
                //         Tabla_deudas_Filtros(ESTADO_CARGA)

                //     }
                // },
                {
                    text: `<span class"fw-bolder">Todos</span>`,
                    className: 'btn btn-dark fs-2',
                    action: function(e, dt, node, config) {
                        Tabla_deudas_Filtros("todo")
                    }
                }, {
                    text: `<span class"fw-bolder">Pendientes</span>`,
                    className: 'btn btn-light-info fw-bold fs-2',
                    action: function(e, dt, node, config) {
                        // Cargar_Deudas(null)
                        Tabla_deudas_Filtros(null);

                    }
                }, {
                    text: `<span  class"fw-bolder">Aprobados</span>`,
                    className: 'btn btn-light-success fs-2',
                    action: function(e, dt, node, config) {
                        Tabla_deudas_Filtros(1);

                    }
                }, {
                    text: `<span class"fw-bolder">Rechazados</span>`,
                    className: 'btn btn-light-danger fs-2',
                    action: function(e, dt, node, config) {
                        Tabla_deudas_Filtros(3)

                    }
                },
                // {
                //     text: `<span class"fw-bolder">Pagados</span>`,
                //     className: 'btn btn-danger fs-2',
                //     action: function(e, dt, node, config) {
                //         Cargar_Deudas(5)
                //     }
                // },
                // {
                //     text: `<span class"fw-bolder">APROBAR AGRUPADOS</span>`,
                //     className: 'btn btn-light fs-2',
                //     action: function(e, dt, node, config) {

                //         var rows_selected = table.rows('.selected').data().toArray();
                //         Guardar_Agrupados(rows_selected);

                //     }
                // }
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
            "order": [
                [1, "desc"],
                // [0, "desc"],
            ],
            drawCallback: function() {
                $('.select22').select2();
            },
            // columnDefs: [{
            //     orderable: false,
            //     className: 'select-checkbox',
            //     targets: 16,
            // }],
            // select: {
            //     "style": "multi",
            //     //  selector: 'td:first-child',
            // },
            columns: [{
                    data: "Fecha",
                    title: "Fecha Deuda",
                    width: 130,

                }, {
                    data: "SGO_FECHA_SOLICITADO",
                    title: "Fecha Solicitado",
                    width: 130,

                }, {
                    data: "SGO_ASIGNADA_POR",
                    title: "Solicitado Por",
                    visible: false
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
                    data: "SGO_COMENTARIO_PENDIENTE",
                    title: "Comentario Solicitante",
                    width: 200,

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
                    width: 130,

                },
                {
                    data: "Valor",
                    title: "Valor",
                    // render: $.fn.dataTable.render.number(',', '.', 2, "$")
                    width: 200,

                }, {
                    data: "Saldo",
                    title: "Saldo",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                    visible: false

                }, {
                    data: null,
                    title: "Abono",
                    className: "dt-center  input-sas",
                    // width: 190,
                    "render": function(data, type, row, meta) {
                        if (type === 'display') {
                            var d = data;
                            let formatCurrency = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            });
                            if (d != null) {
                                if (data.estado == null) {
                                    data = '<input class="fs-4" style="width:120px;" onkeypress="return valideKey(event);"type="tex" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'
                                } else if (data.estado == 3) {
                                    data = '<input class="fs-4" style="width:120px; onkeypress="return valideKey(event);"type="text" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'
                                } else {
                                    data = '<input class="fs-4" disabled style="width:120px; onkeypress="return valideKey(event);"type="text" min="0"step="0.1" value="' + formatCurrency.format(data.SGO_ABONO).split("$")[1] + '" class="form-control input-sas">'
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

                            return `<a href="#!" class="text-hover">
                            <span class="text-info fw-bold text-hover d-block fs-4">PENDIENTE</span>
                                    </a>`
                        } else if (data == 1) {
                            let ARR = [rw.comentario_confirmar, rw.Valor, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                            return `<a href="#!"  class="text-hover">
                            <span class="text-primary fw-bold text-hover d-block fs-4">APROBADO</span>
                                    </a>`
                        } else if (data == 3) {
                            let ARR = [rw.comentario_rechazo, rw.Valor, rw.Banco_nombre, rw.Fcheque, rw.ischeque];

                            return `<a href="#!" class="text-hover">
                            <span class="text-danger fw-bold text-hover d-block fs-4">RECHAZADO</span>
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
                },
                // {
                //     data: null,
                //     title: "Seleccionar",
                //     render: function(x) {
                //         return "";
                //     }
                // },
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
                    </div>
                    `;
                let FECHA_MOSTRAR;
                let HORA_MOSTRAR;
                if (data["SGO_FECHA_SOLICITADO"] != null) {
                    FECHA_MOSTRAR = moment(data["SGO_FECHA_SOLICITADO"]).format("YYYY-MM-DD");
                    HORA_MOSTRAR = moment(data["SGO_FECHA_SOLICITADO"]).format("hh:mm")
                } else {
                    FECHA_MOSTRAR = "";
                    HORA_MOSTRAR = "";
                }

                let fecha_sol = `
                    <div class="d-flex justify-content-start flex-column">
                        <span  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + FECHA_MOSTRAR + `</span>
                        <span  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + HORA_MOSTRAR + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-5">Solicitado por:</span>
                        <span class="text-gray-800 fw-semibold d-block fs-4">` + (data["SGO_ASIGNADA_POR"]) + `</span>
                    </div>
                    `;
                // var VEN;
                // var VEN_T = "Vencimiento";
                // if (data["Tipo"] == 'VALE' || data["Tipo"] == 'PROV-ORDEN') {
                //     VEN = "";
                //     VEN_T = ""
                // } else {
                //     VEN = moment(data["Vencimiento"]).format("YYYY-MM-DD");
                // }
                // let vencimiento = `
                //     <div class="d-flex justify-content-start flex-column">
                //         <span class="text-gray-700 fw-semibold d-block fs-5">` + VEN_T + `</span>
                //         <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + VEN + `</a>
                //         <span class="text-gray-700 fw-semibold d-block fs-5">Tipo</span>
                //         <span class="text-gray-600 fw-semibold d-block fs-5">` + (data["Tipo"]) + `</span>
                //     </div>
                // `;
                // let color = "warning";
                // if (data["Empresa"] == "CARTIMEX") {
                //     color = "primary";
                // }
                // let ARR_D = [data["Tipo"], data["Número"], data["Empresa"], data["Saldo"]];
                let detalle;
                // if (data["SGO_COMENTARIO_PENDIENTE"] == -1 && data["Empresa"] == 'CARTIMEX') {
                if (data["Tipo"] == "VALE") {
                    detalle = `
                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                            <span  class="text-success fw-bold text-hover-primary mb-1 fs-5">` + (data["Tipo"]) + `</span>
                        </div>
                        `;
                } else if (data["Tipo"] == "PROV-ORDEN") {
                    detalle = `
                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["proveedor_nombre"]) + `</a>
                            <a  class="text-gray-700 fw-bold text-hover-primary mb-1 fs-7">Detalle:</a>
                            <a  class="text-gray-700 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                            <span  class="text-success fw-bold text-hover-primary mb-1 fs-5">` + (data["Tipo"]) + `</span>

                        </div>
                        `;
                } else {
                    detalle = `
                        <div class="d-flex justify-content-start flex-column">
                            <a  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["proveedor_nombre"]) + `</a>
                            <span  class="text-success fw-bold text-hover-primary mb-1 fs-5">` + (data["Tipo"]) + `</span>
                        </div>
                        `;
                }

                // } else {
                //     if (data["Empresa"] == 'COMPUTRON') {
                //         if (data["Tipo"] == "PROV-ORDEN") {
                //             detalle = `
                //             <div class="d-flex justify-content-start flex-column">
                //                 <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["proveedor_nombre"]) + `</a>
                //                 <span class="text-gray-600 fw-semibold d-block fs-7">` + (data["Detalle"]) + `</span>
                //                 <span class="text-` + color + ` fw-semibold d-block fs-4">` + (data["Empresa"]) + `</span>
                //             </div>
                //         `;
                //         } else {
                //             detalle = `
                //             <div class="d-flex justify-content-start flex-column">
                //                 <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                //                 <span class="text-` + color + ` fw-semibold d-block fs-4">` + (data["Tipo"]) + `</span>
                //             </div>
                //         `;
                //         }

                //     } else {
                //         detalle = `
                //             <div class="d-flex justify-content-start flex-column">
                //                 <a href="#!" onclick="datos_Deudor('` + ARR_D + `')" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                //                 <span class="text-` + color + ` fw-semibold d-block fs-4">` + (data["Tipo"]) + `</span>
                //             </div>
                //         `;
                //     }

                // }

                let hoy = moment().format("YYYY-MM-DD");
                let ven = moment(data["Vencimiento"]).format("YYYY-MM-DD");
                var date_1 = new Date(hoy);
                var date_2 = new Date(ven);

                var day_as_milliseconds = 86400000;
                var diff_in_millisenconds = date_2 - date_1;
                var diff_in_days = diff_in_millisenconds / day_as_milliseconds;
                let color_dias = "text-primary";
                let ven_texto = "Sin Vencer:";
                if (diff_in_days < 0) {
                    color_dias = "text-danger";
                    ven_texto = "Dias vencidos:";
                }
                let Vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-5">` + ven + `</a>
                        <span class="` + color_dias + ` fw-bold d-block fs-4">` + ven_texto + `</span>
                        <span class="` + color_dias + ` fw-bold d-block fs-3">` + diff_in_days + `</span>
                    </div>
                `;


                let valor = `
                    <div class="d-flex justify-content-start flex-column">
                    <span class="text-gray-600 fw-bold d-block fs-3">Valor deuda</span>
                        <a href="#!"  class="text-gray-800 fw-bold text-hover-primary mb-1 fs-4">` + formatCurrency.format(data["Valor"]) + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-2">Saldo a Pagar</span>
                        <span class="text-primary fw-bold d-block fs-3">` + formatCurrency.format(data["Saldo"]) + `</span>
                    </div>
                `;

                if (data["Tipo"] == "VALE" || data["Tipo"] == "PROV-ORDEN") {
                    $('td', row).eq(3).html(data["Detalle"]);
                    let hoy = moment().format("YYYY-MM-DD");
                    let ven = moment(data["SGO_FECHA_SOLICITADO"]).format("YYYY-MM-DD");
                    var date_1 = new Date(hoy);
                    var date_2 = new Date(ven);

                    var day_as_milliseconds = 86400000;
                    var diff_in_millisenconds = date_2 - date_1;
                    var diff_in_days = diff_in_millisenconds / day_as_milliseconds;
                    let color_dias = "text-primary";
                    let ven_texto = "Sin Vencer:";
                    if (diff_in_days < 0) {
                        color_dias = "text-danger";
                        ven_texto = "Dias vencidos:";
                    }
                    Vencimiento = `
                    <div class="d-flex justify-content-start flex-column">
                        <span class="` + color_dias + ` fw-bold d-block fs-4">` + ven_texto + `</span>
                        <span class="` + color_dias + ` fw-bold d-block fs-3">` + diff_in_days + `</span>
                    </div>
                `;
                }

                $('td', row).eq(0).html(fecha);
                $('td', row).eq(1).html(fecha_sol);
                $('td', row).eq(1).addClass("bg-light-warning");
                // $('td', row).eq(2).html(vencimiento);
                $('td', row).eq(2).html(detalle);
                $('td', row).eq(4).html(Vencimiento);
                $('td', row).eq(5).html(valor);
                $('td', row).eq(3).addClass("fw-bold");
                // $('td', row).eq(5).addClass("fs-4 fw-bolder");
                // $('td', row).eq(1).addClass("fw-bolder");
                // $('td', row).eq(2).addClass("fw-bolder");
                // $('td', row).eq(3).addClass("fw-bolder");
                // $('td', row).eq(8).addClass("fw-bolder");

                if (data["estado"] != null) {
                    $('td', row).eq(8).removeClass("btn_confirmar");
                    $('td', row).eq(8).html("");
                    // $('td', row).eq(12).html(`
                    //     <button class="btn btn-primary btn-sm btn_girar">
                    //         <i class="bi bi-arrow-clockwise fs-2"></i>
                    //     </button>
                    // `);
                    // $('td', row).eq(12).addClass("btn_girar");
                }

                if (data["estado"] == 3) {
                    $('td', row).eq(9).removeClass("btn_rechazar");
                    $('td', row).eq(9).html("");
                    // $('td', row).eq(12).removeClass("btn_girar");
                    // $('td', row).eq(12).html("");
                    $('td', row).eq(8).html(`
                      <button class="btn btn-success btn-sm btn_confirmar"> 
                        <i class="bi bi-check-circle-fill fs-2"></i>
                    </button>
                    `);
                    $('td', row).eq(8).addClass("btn_confirmar");

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
            var abono = columns.eq(6).children().val();
            abono = abono.replaceAll(",", "");
            console.log('abono: ', abono);
            console.log('Saldo: ', data.Saldo);


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
                                        tipo: "",
                                        fecha: "",
                                        banco: "",
                                        banco_nombre: "",
                                        estado: 1,
                                        comentario_confirmar: motivo,
                                        comentario_rechazo: "",
                                        rechazado: 0,
                                        valor_deuda_total: data.Valor,
                                        acr_deuda_id: data.ID,
                                        deuda_numero: data["Número"]
                                    }
                                    console.log('param: ', param);

                                    Guardar_Deuda(param);
                                } else {
                                    let param = {
                                        ID: data.ID,
                                        deuda_ID: data.deuda_ID,
                                        abono: abono,
                                        tipo: "",
                                        fecha: "",
                                        banco: "",
                                        banco_nombre: "",
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



        });

        $("#Tabla_Deudas tbody").on("keyup", 'input', function(event) {
            var val = table.row(this).$(this).val();
            $(event.target).val(function(index, value) {
                return value.replace(/\D/g, "")
                    .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
            });
        });
        $("#Tabla_Deudas tbody").on("change", 'input.FECHA_INPUT', function(event) {
            var val = table.row(this).$(this).val();
            var data = table.row($(this).closest('tr')).data();
            console.log('data: ', data);
            val = val.replaceAll(",", "");
            // data["Saldo"] = val;

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_girar', function(e) {
            var data = table.row(this).data();

            Girar_Cheque(data);

        });

        $('#Tabla_Deudas tbody').on('click', 'td.btn_rechazar', function(e) {
            var data = table.row(this).data();
            var columns = $(this).closest("tr").children();
            var abono = columns.eq(6).children().val();
            abono = abono.replaceAll(",", "");

            let param = {
                ID: data.ID,
                deuda_ID: data.deuda_ID,
                abono: abono,
                tipo: "",
                fecha: "",
                banco: "",
                banco_nombre: "",
                estado: 3,
                comentario_confirmar: "",
                comentario_rechazo: "",
                rechazado: 1,
                valor_deuda_total: data.Valor,
                acr_deuda_id: data.ID,
                deuda_numero: data["Número"]
            }
            console.log('param: ', param);

            Rechazar(param);
        });


    }

    function Tabla_deudas_Filtros(tipo) {
        let param = {
            EMPRESA: EMPRESA,
            TIPO: ESTADO_CARGA
        }
        AjaxSendReceiveData(url_Buscar_Deudas, param, function(x) {
            console.log('x: ', x);
            let dataFiltrada = x.filter(cat => cat.Proveedor_ID == PRV_FILTRO);
            if (tipo != "todo") {
                let dataFiltrada_t = dataFiltrada.filter(cat => cat.estado == tipo);
                console.log('dataFiltrada: ', dataFiltrada_t);
                Tabla_Deudas(dataFiltrada_t)
            } else {
                Tabla_Deudas(dataFiltrada)

            }

            // Tabla_Deudas_pagadas(x);
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
                if (data["Tipo"] == 'PROV-ORDEN') {
                    data["Detalle"] = data["proveedor_nombre"]
                }
                let detalle = `
                    <div class="d-flex justify-content-start flex-column">
                        <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["Detalle"]) + `</a>
                        <span class="text-` + color + ` fw-semibold d-block fs-5">` + (data["Empresa"]) + `</span>
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
        console.log('data: ', data);

        data = data.split(",");
        let param = {
            tipo: data[0].trim(),
            numero: data[1].trim(),
            empresa: data[2].trim(),
        }

        AjaxSendReceiveData(url_Detalles_Deudor, param, function(x) {
            console.log('xddd: ', x);

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
                Tabla_deudas_Filtros("todo");
            }
        });
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
                                console.log('x: ', x);
                                Tabla_deudas_Filtros("todo");
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
        console.log('data: ', data);
        if (data.length == 0) {
            Mensaje("No ha seleccionado datos para agrupar", "", "info")
        } else {
            let dataFiltrada = data.filter(cat => cat.estado == null);
            if (dataFiltrada.length == 0) {
                Mensaje("No hay datos validos para guardar", "solo se pueden agrupar ordenes pendientes", "info")
            } else {
                console.log('dataFiltrada: ', dataFiltrada);

                let select = `<select class="form-select select22" id="AGR_BANCO">
                                    <option value="">Seleccione</option>
                                     ` + BANCOS_OPTION + `
                            </select>`
                let tipo = `<select class="form-select select_ch select22" id="AGR_TIPO">
                                    <option value="">Seleccione</option>
                                    <option value="banco"> Banco </option>
                                    <option value="transferencia"> Transferencia </option>
                                    <option value="cheque"> Cheque</option>
                                    <option value="cheque_al_dia"> Cheque al Dia </option>
                            </select>`;
                $("#SET_SELECT").append(select);
                $("#SET_Tipo").append(tipo);
                $('.select22').select2();
                $("#Modal_Agruapdo").modal("show");

                $("#AGR_TIPO").on("change", function(x) {
                    let val = $("#AGR_TIPO").val();
                    if (val == "cheque") {
                        $("#AGR_FECHA").prop('disabled', false)
                    } else {
                        $("#AGR_FECHA").prop('disabled', true)

                    }

                });
                $("#AGR_GUARDAR").on("click", function(x) {
                    let banco = $("#AGR_BANCO").val();
                    let tipo = $("#AGR_TIPO").val();
                    let fecha = $("#AGR_FECHA").val();

                    // let param{

                    // }

                });

            }


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