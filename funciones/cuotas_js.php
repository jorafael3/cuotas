<?php

$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos/';
$url_Buscar_Producto = constant('URL') . 'principal/Buscar_Producto/';

?>

<script>
    // Cambiar las URL por la nueva del archivo //    

    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';
    var url_Buscar_Producto = '<?php echo $url_Buscar_Producto ?>';
    var ARRAY_LISTA_PRODUCTOS = [];


    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    $('#CU_CODIGO').on('keypress', function(e) {
        if (e.which === 13) {

            Buscar_Producto();
        }
    });

    function Buscar_Producto() {
        let Codigo = $("#CU_CODIGO").val();

        let param = {
            codigo: Codigo
        }
        AjaxSendReceiveData(url_Buscar_Producto, param, function(x) {
            console.log('x: ', x);
            if (x.length > 0) {
                $("#kt_modal_Productos").modal("show");

                Tabla_productos(x);
            }

        });
    }

    function Tabla_productos(datos) {
        $('#Tabla_Proveedores').empty();
        if ($.fn.dataTable.isDataTable('#Tabla_Proveedores')) {
            $('#Tabla_Proveedores').DataTable().destroy();
            $('#Tabla_Proveedores').empty();
        }
        // $("#Tabla_Pendientes").addClass("table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer");
        var tabla = $('#Tabla_Proveedores').DataTable({
            destroy: true,
            data: datos,
            dom: 'frtip',
            scrollY: '50vh',
            scrollCollapse: true,
            paging: true,
            order: [
                [0, "desc"]
            ],
            columns: [{
                    data: "Codigo",
                    title: "Codigo",
                }, {
                    data: "Producto",
                    title: "Producto",
                    width: 200
                }, {
                    data: "Precio",
                    title: "Precio",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                }, {
                    data: "Stock",
                    title: "Stock",
                    render: $.fn.dataTable.render.number(',', '.', 0, ""),
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

                $('td', row).eq(0).addClass("text-gray-600 fw-bolder");
                $('td', row).eq(1).addClass("text-gray-600 fw-bolder");
                $('td', row).eq(2).addClass("text-gray-700 fw-bolder bg-light-primary");
                $('td', row).eq(3).addClass("text-gray-600 fw-bolder");
                $('td', row).eq(4).addClass("text-gray-800 fw-bolder bg-light-warning");
                $('td', row).eq(5).html(data["texto"]);

            },
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 1000);
        $('#kt_modal_Productos').on('shown.bs.modal', function() {
            // $('#retiro').focus();
            tabla.columns.adjust();
        })
        $('#Tabla_Proveedores tbody').on('click', 'td.btn_subir', function(e) {
            e.preventDefault();
            var data = tabla.row(this).data();
            console.log('data: ', data);
            ARRAY_LISTA_PRODUCTOS.push(data);
            console.log('ARRAY_LISTA_PRODUCTOS: ', ARRAY_LISTA_PRODUCTOS);
            Tabla_LISTA(ARRAY_LISTA_PRODUCTOS);
        });
    }

    function Cargar_Datos() {

        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {

            console.log('x: ', x);

        });
    }
    // Cargar_Datos();

    function Tabla_LISTA(datos) {
        $('#Tabla_Deudas').empty();
        if ($.fn.dataTable.isDataTable('#Tabla_Deudas')) {
            $('#Tabla_Deudas').DataTable().destroy();
            $('#Tabla_Deudas').empty();
        }
        Footer();

        // $("#Tabla_Pendientes").addClass("table align-middle table-row-dashed fs-6 gy-3 dataTable no-footer");
        var tabla = $('#Tabla_Deudas').DataTable({
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
                extend: "excel",
                title: "Cotizacion",
                footer: true
            }, {
                extend: "print",
                title: "Cotizacion",
                footer: true
            }, {
                text: `<span class"fw-bolder">Limpiar Tabla </span> <i class="bi bi-arrow-clockwise"></i>`,
                className: 'btn btn-light-success',
                action: function(e, dt, node, config) {
                    ARRAY_LISTA_PRODUCTOS = [];
                    Footer();
                    Tabla_LISTA(ARRAY_LISTA_PRODUCTOS);
                }
            }],
            columns: [{
                    data: "Producto",
                    title: "Producto",
                }, {
                    data: "PrecioFinal",
                    title: "PrecioFinal",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C3",
                    title: "C3",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C6",
                    title: "C6",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C9",
                    title: "C9",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C12",
                    title: "C12",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C18",
                    title: "C18",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),

                }, {
                    data: "C24",
                    title: "C24",
                    render: $.fn.dataTable.render.number(',', '.', 2, "$"),
                }

            ],
            "createdRow": function(row, data, index) {

                $('td', row).eq(0).addClass("text-gray-700 fw-bolder bg-light-warning");
                $('td', row).eq(1).addClass("text-gray-700 fw-bolder bg-light-success");
                $('td', row).eq(2).addClass("text-gray-700 fw-bolder ");
                $('td', row).eq(3).addClass("text-gray-700 fw-bolder ");
                $('td', row).eq(4).addClass("text-gray-700 fw-bolder ");
                $('td', row).eq(5).addClass("text-gray-700 fw-bolder ");
                $('td', row).eq(6).addClass("text-gray-700 fw-bolder ");
                $('td', row).eq(7).addClass("text-gray-700 fw-bolder ");

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
                var pf = api
                    .column(1)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                var c3 = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);

                var c6 = api
                    .column(3)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                var c9 = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                var c12 = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                var c18 = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                var c24 = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b));
                    }, 0);
                $(api.column(0).footer()).html('Total');
                $(api.column(1).footer()).html(formatter.format(pf));
                $(api.column(2).footer()).html(formatter.format(c3));
                $(api.column(3).footer()).html(formatter.format(c6));
                $(api.column(4).footer()).html(formatter.format(c9));
                $(api.column(5).footer()).html(formatter.format(c12));
                $(api.column(6).footer()).html(formatter.format(c18));
                $(api.column(7).footer()).html(formatter.format(c24));
                //$(api.column(3).footer()).html(format(wedTotal));
            }
        });

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 1000);


    }

    function Mensaje(texto1, texto2, icon) {
        Swal.fire(
            texto1,
            texto2,
            icon
        )
    }

    function Footer() {
        let fo = `
        <tfoot align="center">
                        <tr>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-1"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                            <th style="font-size: 16px;" class="fw-bold fs-2"></th>
                        </tr>
                    </tfoot>
        `;
        $("#Tabla_Deudas").append(fo);
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