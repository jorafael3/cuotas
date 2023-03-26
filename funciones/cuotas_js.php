<?php

$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos/';
$url_Buscar_Producto = constant('URL') . 'principal/Buscar_Producto/';

?>

<script>
    // Cambiar las URL por la nueva del archivo //    

    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';


    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    function Buscar_Producto() {
        let Codigo = $("#CU_CODIGO").val();

        let param = {
            codigo: Codigo
        }
        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {

            console.log('x: ', x);

        });
    }

    function Cargar_Datos() {

        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {

            console.log('x: ', x);

        });
    }
    Cargar_Datos();

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