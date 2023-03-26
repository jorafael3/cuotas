<?php

$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos_Llenos/';

?>
<script>
    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    function Cargar_Datos() {
        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {
            console.log('x: ', x);
            x.map(function(obj) {
                if (obj.fecha_trans == null) {
                    obj.fecha_trans = ""
                }
                if (obj.fecha_habil_cas == null) {
                    obj.fecha_habil_cas = ""
                }
                if (obj.fecha_bodega == null) {
                    obj.fecha_bodega = ""
                }
                return obj;
            })
            Tabla_Cargas(x)
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
            }, 'excel'],
            columns: [{
                    data: "fecha_creado",
                    title: "FECHA DE CREADO",
                    render: function(data) {
                        return moment(data).format("YYYY-MM-DD hh:mm:ss")
                    },
                    width: 130
                }, {
                    data: "proveedor",
                    title: "PROVEEDOR",
                    width: 150
                }, {
                    data: "descripcion",
                    title: "DESCRIPCION",
                    visible: false
                },
                {
                    data: "Detalle",
                    title: "Detalle",
                    width: 150
                }, {
                    data: "Valor",
                    title: "VALOR FACTURA",
                    // visible: false,
                    render: $.fn.dataTable.render.number(',', '.', 2, "$")
                }, {
                    data: "agente_carga",
                    title: "AGENTE DE CARGA",
                    width: 150
                    // render: function(x) {
                    //     if (x == null) {
                    //         return "SIN REGISTRAR"
                    //     } else {
                    //         return x;
                    //     }
                    // }
                }, {
                    data: "Puerto",
                    title: "Puerto",
                    visible: false
                }, {
                    data: "Embarque",
                    title: "FECHA DE EMBARQUE",
                    width: 150
                }, {
                    data: "EditadoDate",
                    title: "FECHA DE ARRIBO APROX",
                    visible: false
                }, {
                    data: "liquidacion",
                    title: "LIQUIDACION",
                }, {
                    data: "TipoImport",
                    title: "INCOTERM",
                    visible: false
                }, {
                    data: "DUI",
                    title: "DAU",
                }, {
                    data: "fecha_trans",
                    title: "F. TRANS",
                    width: 150
                }, {
                    data: "fecha_habil_cas",
                    title: "F. HABIL CAS",
                    visible: false
                }, {
                    data: "fecha_bodega",
                    title: "F. BODEGA",
                    visible: false
                },
                {
                    data: "orden",
                    title: "ORDEN",
                    width: 150

                }, {
                    data: "observacion",
                    title: "observacion",
                    visible: false
                }
            ],
            "createdRow": function(row, data, index) {
                let fecha = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-7">` + moment(data["Fecha_Creado"]).format("hh:mm") + `</span>
                        <span class="text-gray-600 fw-semibold d-block fs-6">` + data["CreadoPor"] + `</span>
                    
                    </div>
                `;
                let proveedor = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + data["proveedor"] + `</a>
                        <span class="text-gray-600 fw-semibold d-block fs-7">Descripcion: </span>
                        <span class="text-gray-600 fw-semibold d-block fs-6">` + data["descripcion"] + `</span>
                    </div>
                `;
                if (data["pedido_id"] == null) {
                    data["pedido_id"] = "SIN REGISTRAR"
                }
                let factura = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + data["pedido_id"] + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Valor:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + formatter.format(data["Valor"]) + `</span>
                    </div>
                `;
                let agente = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + data["agente_carga"] + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-7">Puerto de embarque:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-7">` + data["Puerto"] + `</span>
                    </div>
                `;
                let liquidacion = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + (data["DUI"]) + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Incoterm:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + data["TipoImport"] + `</span>
                    </div>
                `;
                let FE_EM = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + moment(data["Fecha_Creado"]).format("YYYY-MM-DD") + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Fecha Arribo:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + moment(data["EditadoDate"]).format("YYYY-MM-DD") + `</span>
                    </div>
                `;
                let ORden = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + data["orden"] + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Observacion:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + data["observacion"] + `</span>
                    </div>
                `;
                let fecha_trans;
                let fecha_habil_cas;
                let fecha_bodega;
                if (data["fecha_trans"] != "") {
                    fecha_trans = moment(data["fecha_trans"]).format("YYYY-MM-DD");
                } else {
                    fecha_trans = "- - - -"
                }
                if (data["fecha_habil_cas"] != "") {
                    fecha_habil_cas = moment(data["fecha_trans"]).format("YYYY-MM-DD");
                } else {
                    fecha_habil_cas = "- - - -"
                }
                if (data["fecha_bodega"] != "") {
                    fecha_bodega = moment(data["fecha_trans"]).format("YYYY-MM-DD");
                } else {
                    fecha_bodega = "- - - -"
                }

                let FE_EM_ = `
                    <div class="d-flex justify-content-start flex-column">
                        <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">` + fecha_trans + `</a>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Fecha Habil Cas:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + fecha_habil_cas + `</span>
                        <span class="text-gray-800 fw-semibold d-block fs-6">Fecha Bodega:</span>
                        <span class="text-gray-700 fw-semibold d-block fs-6">` + fecha_bodega + `</span>
                    </div>
                `;

                $('td', row).eq(0).html(fecha);
                $('td', row).eq(1).html(proveedor);
                // $('td', row).eq(2).html(factura);
                $('td', row).eq(4).html(agente);
                $('td', row).eq(5).html(FE_EM);
                $('td', row).eq(8).html(FE_EM_);
                $('td', row).eq(9).html(ORden);
                $('td', row).eq(7).html(liquidacion);
                $('td', row).eq(1).addClass("text-gray-600 fw-bolder text-hover-primary bg-light-warning");
                $('td', row).eq(2).addClass("text-gray-600 fw-bolder text-hover-primary bg-light-primary");
                $('td', row).eq(3).addClass("text-gray-700 fw-bolder text-hover-primary fs-5");
                $('td', row).eq(4).addClass("text-gray-800 fw-bolder ");
                $('td', row).eq(5).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(6).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(7).addClass("text-gray-600 fw-bolder text-hover-primary bg-light-success");
                $('td', row).eq(8).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(9).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(10).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(11).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(12).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(13).addClass("text-gray-600 fw-bolder text-hover-primary");
                $('td', row).eq(14).addClass("text-gray-600 fw-bolder text-hover-primary");
            },

        }).clear().rows.add(datos).draw();

        setTimeout(function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
        }, 1000);

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