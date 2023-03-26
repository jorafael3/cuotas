<?php

$url_Consultar_Bancos = constant('URL') . 'principal/Cargar_Bancos/';
$url_Guardar_Datos = constant('URL') . 'principal/Guardar_Datos/';
$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos/';

?>
<script>
    var url_Consultar_Bancos = '<?php echo $url_Consultar_Bancos ?>';
    var url_Guardar_Datos = '<?php echo $url_Guardar_Datos ?>';
    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';

    var ESTADO_NUEVO_EDIT = 0;
    var NUM_PROTECCION_EDIT;
    var ARRAY_BANCOS_CARTIMEX = [];
    var ARRAY_BANCOS_COMPUTROM = [];
    var ARRAY_DATA = [];
    var ARRAY_DATA_COMPUTRON = [];
    var COMP_CARGADOS = [];
    var PR_AGREGAR_NUEVA_COLUMNA_Computron

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

    function Cargar_Bancos() {

        AjaxSendReceiveData(url_Consultar_Bancos, [], function(x) {

            ARRAY_BANCOS_CARTIMEX = x

            // ARRAY_BANCOS_CARTIMEX = x.filter(cat => cat.empresa == "CARTIMEX");
            // 
            // ARRAY_BANCOS_COMPUTROM = x.filter(cat => cat.empresa == "COMPUTRONSA");
            // 
        });

        AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {


            let CART_CARGADOS = x

            if (CART_CARGADOS.length == 0) {
                NUEVO_();
            } else {
                Crear_Lineas_Bancos_Existentes(CART_CARGADOS);
            }

        });
    }
    Cargar_Bancos();

    function VAlidar_Input(i) {

        $("#input_con_" + i).on({
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

    function VAlidar_Input_2(i) {

        $("#input_dis_" + i).on({
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

    function VAlidar_Input_3(i) {

        $("#input_d_" + i).on({
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

    function VAlidar_Input_4(i) {

        $("#input_d_" + i).on({
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

    function Guardar_DATOS_S() {

        var table = document.querySelector("#TABLA_PROTECCION_PRECIO");
        var data = parseTable(table);
        console.log('data: ', data);

        if (data.length == 0) {
            Mensaje("Debe agregar Lineas", "", "info");
        } else {
            let VAL_LINEAS = data.filter(function(x) {
                return x.lleno == 0 ? x : 0
            });

            if (VAL_LINEAS.length > 0) {
                Mensaje("Hay lineas en blanco", "", "error")
            } else {

                let DATA_TO_SEND = data.filter(function(x) {
                    return x.lleno == 1 ? x : 0
                });
                console.log('DATA_TO_SEND: ', DATA_TO_SEND);

                AjaxSendReceiveData(url_Guardar_Datos, DATA_TO_SEND, function(x) {
                    console.log('x: ', x);
                    if (x <= 0) {
                        Mensaje("Error al guardar", "", "error");
                    } else if (x < DATA_TO_SEND) {
                        Mensaje("Error al guardar", "", "error");
                    } else {
                        Mensaje("Datos guardado", "", "success");
                        Cargar_Bancos();

                    }
                });
            }
        }
        // let t = 0;
        // // let t = Validar_TAbla();

        // if (t == 0) {

        //     DATA_TO_SEND(function(x) {
        //         setTimeout(send(x), 1000);
        //     });

        //     function send(ARRAY_DATA) {
        //         
        //         Swal.fire({
        //             title: 'Antes de Continuar',
        //             text: "Verifique que los datos esten correctos",
        //             icon: 'info',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Si, continuar!',
        //             cancelButtonText: 'Cancelar'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 ARRAY_DATA.map(function(x) {
        //                     x.Empresa = "CARTIMEX"
        //                 });

        //                 AjaxSendReceiveData(url_Guardar_Datos, ARRAY_DATA, function(x) {
        //                     

        //                     if (x <= 0) {
        //                         Mensaje("Error al guardar", "", "error");
        //                     } else if (x < ARRAY_DATA) {
        //                         Mensaje("Error al guardar", "", "error");
        //                     } else {
        //                         Mensaje("Datos guardado", "", "success");
        //                         Cargar_Bancos();
        //                         setTimeout(() => {
        //                             // location.reload()
        //                         }, 1000);
        //                     }
        //                 });
        //             }
        //         });
        //     }

        // } else {
        //     Mensaje("Hay campos sin completar, Porfavor verifique", "", "error")
        // }
    }

    function Crear_Lineas_Bancos_Existentes(data) {
        console.log('data: ', data);


        $("#TABLA_PROTECCION_PRECIO_THEAD").empty();
        $("#TABLA_PROTECCION_PRECIO_BODY").empty();
        var thead = `
        <tr style="font-weight: bold;">
                    <td style="width:350px">Banco</td>
                    <td >Empresa</td>
                    <td >Saldo_Contable</td>
                    <td >Saldo_Disponible</td>
                    <td >Deposita_Dia</td>
                    <td >Comentario</td>
                    <td >Borrar</td>
                    <td style='display:none;' >Actualizar</td>
                    <td style='display:none;' >Saldo_id</td>
                    <td style='display:none;' >Contador</td>
                    <td style='display:none;' >lleno</td>
                    <td style='display:none;' >Banco_nombre</td>
                </tr>
        `;

        $("#TABLA_PROTECCION_PRECIO_THEAD").append(thead);

        let formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        });

        let total1 = 0
        let total2 = 0
        let total3 = 0

        data.map(function(x, i) {

            total1 = total1 + parseFloat(x.saldo_disponible)
            total2 = total2 + parseFloat(x.saldo_contable)
            total3 = total3 + parseFloat(x.deposito_dia)

            let i1 = "input_con_";
            let i2 = "input_emp_";
            let i3 = "input_dis_";
            let i4 = "input_d_";
            let i5 = "input_coment_";
            var tabla = `
                <tr style="font-size:12px">
                    <td class="fs-4 fw-bold">
                    ` + x.Nombre + `
                    </td>
                        <td>
                            <input disabled style="font-size:12px" type="text" id="input_con_` + i + `"
                            class="form-control form-control-solid "value="` + x.empresa + `">
                        </td>
                    <td>
                        <input onkeyup="VAlidar_Input(` + i + `)" style="font-size:12px" type="text" id="input_con_` + i + `"
                          class="form-control " value="` + formatter.format(x.saldo_contable).split("$")[1] + `">
                    </td>
                    <td>
                        <input  style="font-size:12px" type="text" min="0" id="input_dis_` + i + `"
                         step="0.1" class="form-control " value="` + formatter.format(x.saldo_disponible).split("$")[1] + `">
                    </td>
                    <td>
                        <input  style="font-size:12px" type="text" min="0.01" id="input_d_` + i + `"
                         step="0.1" class="form-control " value="` + formatter.format(x.deposito_dia).split("$")[1] + `">
                    </td>
                    <td>
                        <input onkeyup="VAlidar_Input_4(` + i + `)" style="font-size:12px" type="text" min="0.01" id="input_coment_` + i + `"
                         step="0.1" class="form-control form-control" value="` + x.Comentario + `">
                    </td>
                    <td>
                        <button style="font-size:12px;display:none;" class="btn btn_remove"><i class="bi bi-trash-fill"></i></button>
                    </td>
                    <td style='display:none;'>1
                    
                    </td>
                    <td style='display:none;'>
                    ` + x.Saldo_id + `
                    </td>
                    <td style='display:none;'>
                        
                    </td>
                    <td style='display:none;'>1
                    </td>
                    <td style='display:none;'>
                    </td>
            </tr>
        `;
            $("#TABLA_PROTECCION_PRECIO_BODY").append(tabla);
        });

        $("#total1").text(new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'USD'
        }).format(total1));
        $("#total2").text(new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'USD'
        }).format(total2));
        $("#total3").text(new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'USD'
        }).format(total3));

    }

    function Validar_TAbla() {

        var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_BODY tr').length;

        if (tbl <= 1) {
            Mensaje("Tabla vacia", "No hay elementos en la tabla", "error");
        } else {
            var tabla = document.getElementById("TABLA_PROTECCION_PRECIO_BODY");
            var VAL_COUNTER = 0;

            for (var i = 1; i < tbl; i++) {

                var banco
                var s_contable
                var s_disponible
                var deposito_dia

                try {

                    var banco = tabla.rows[i].cells[0].children[0].value;
                    var s_contable = tabla.rows[i].cells[1].children[0].value;
                    var s_disponible = (tabla.rows[i].cells[2].children[0].value);
                    var deposito_dia = (tabla.rows[i].cells[3].children[0].value);


                } catch (error) {

                    banco = tabla.rows[i].cells[0].innerText;
                    s_contable = tabla.rows[i].cells[1].innerText;
                    s_disponible = tabla.rows[i].cells[2].innerText;
                    deposito_dia = tabla.rows[i].cells[3].innerText;

                }
                if (banco == "") {
                    tabla.rows[i].className = "bg-danger";

                    VAL_COUNTER++;
                }
                if (s_contable == "") {
                    tabla.rows[i].className = "bg-danger";

                    VAL_COUNTER++;
                }
                if (s_disponible == "") {
                    tabla.rows[i].className = "bg-danger";
                    VAL_COUNTER++;
                }

                if (deposito_dia == "") {
                    tabla.rows[i].className = "bg-danger";
                    VAL_COUNTER++;
                }
            }

            return VAL_COUNTER;
        }
    }

    function DATA_TO_SEND(callback) {

        var tabla = document.getElementById("TABLA_PROTECCION_PRECIO_THEAD");
        var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_THEAD tr').length;

        var ARRAY_DATA = [];

        for (var i = 1; i < tbl; i++) {
            var banco;
            var banco_nombre;
            var saldo_id;
            var Cometario;

            try {

                banco = tabla.rows[i].cells[0].getElementsByTagName("select")[0].value;
                banco_nombre = tabla.rows[i].cells[0].getElementsByTagName("select")[0]["selectedOptions"][0]["innerText"];
                saldo_id = tabla.rows[i].cells[9].innerText;
                Cometario = tabla.rows[i].cells[5].innerText;

            } catch (error) {

                banco = "";
                banco_nombre = tabla.rows[i].cells[9].innerText;
                saldo_id = tabla.rows[i].cells[8].innerText;
                empresa = tabla.rows[i].cells[1].innerText;
                Cometario = tabla.rows[i].cells[5].innerText;
            }

            var s_contable = tabla.rows[i].cells[3].children[0].value;
            var s_disponible = tabla.rows[i].cells[2].children[0].value;
            var deposito_dia = tabla.rows[i].cells[4].children[0].value;
            var empresa = tabla.rows[i].cells[1].children[0].value;
            var Cometario = tabla.rows[i].cells[5].children[0].value;

            var b = {

                banco: banco.trim(),
                s_contable: s_contable,
                s_disponible: s_disponible,
                deposito_dia: deposito_dia,
                banco_nombre: banco_nombre.trim(),
                saldo_id: saldo_id.trim(),
                empresa: empresa,
                Comentario: Cometario
            }
            ARRAY_DATA.push(b);
        }
        callback(ARRAY_DATA);
    }

    function NUEVO_() {

        NUEVO_TABLA_();
        // PR_AGREGAR_NUEVA_COLUMNA();
    }

    function NUEVO_TABLA_() {

        $("#TABLA_PROTECCION_PRECIO_THEAD").empty();
        $("#TABLA_PROTECCION_PRECIO_BODY").empty();

        var thead =
            `
        <tr style="font-weight: bold;">
                    <td style="width:350px">Banco</td>
                    <td >Empresa</td>
                    <td >Saldo_Contable</td>
                    <td >Saldo_Disponible</td>
                    <td >Deposita_Dia</td>
                    <td >Comentario</td>
                    <td >Borrar</td>
                    <td style='display:none;' >Actualizar</td>
                    <td style='display:none;' >Saldo_id</td>
                    <td style='display:none;' >Contador</td>
                    <td style='display:none;' >lleno</td>
                    <td style='display:none;' >Banco_nombre</td>
                </tr>
        `;
        $("#TABLA_PROTECCION_PRECIO_THEAD").append(thead);

        // PR_AGREGAR_NUEVA_COLUMNA();

        // var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO tr').length;

        // var tabla = `
        //         <tr style="font-size:12px">
        //             <td>
        //                 <select name="" id="BANCO" class="form-select form-select-solid s">
        //                     <option value="">Seleccione</option>
        //                 </select>
        //             </td>
        //             <td>
        //                 <input disabled style="font-size:12px" type="text" min="0" step="0.1" class="form-control form-control-solid">
        //             </td>
        //             <td>
        //                 <input  style="font-size:12px" type="text" min="0" step="0.1" class="form-control " value="0.00">
        //             </td>
        //             <td>
        //                 <input style="font-size:12px" type="text" min="0.01" step="0.1" class="form-control " value="0.00">
        //             </td>
        //             <td>
        //                 <input style="font-size:12px" type="text" min="0.01" step="0.1" class="form-control " value="0.00">
        //             </td>
        //             <td>
        //                 <input style="font-size:12px" type="text" min="0" step="0.1" class="form-control ">
        //             </td>
        //             <td>
        //                 <button style="font-size:12px" class="btn btn_remove"><i class="bi bi-trash-fill"></i></button>
        //             </td>
        //             <td style='display:none;'>0
        //             </td>
        //             <td style='display:none;'>nn
        //             </td>
        //             <td style='display:none;'>
        //             </td>
        //             <td style='display:none;'>
        //             </td>
        //     </tr>
        // `;
        // $("#TABLA_PROTECCION_PRECIO_BODY").append(tabla);

        // setTimeout(() => {
        //     d();
        // }, 1000);

        // function d() {
        //     let x = [{
        //         Rubro_ID: "asd",
        //         Nombre: "Banco bolivaridao"
        //     }, {
        //         Rubro_ID: "sdas",
        //         Nombre: "Banco bolis"
        //     }]
        //     var CbLogusuarios = document.getElementById("BANCO");
        //     $('#BANCO option').remove(); // clear all values 
        //     // $("#BANCO")[0].selectedIndex = 0;
        //     $("#BANCO").append("<option class='fw-bold' value=''>Seleccione</option>")
        //     jQuery.each(ARRAY_BANCOS_CARTIMEX, function(key, value) {
        //         option = document.createElement("option");
        //         option.value = value.ID;
        //         option.text = value.Nombre;
        //         option.className = "fs-5 fw-bold";
        //         CbLogusuarios.appendChild(option);
        //     });
        // }

        // var fl = $("#Fecha_desde").flatpickr({
        //     minDate: "today"
        // });
        // var fl2 = $("#Fecha_hasta").flatpickr({
        //     minDate: "today"

        // });
        // $('#BANCO').select2();

        // // feather.replace();
    }

    function empresa(ID) {

        var banco = $('option:selected', "#BANCO_" + ID).attr("empresa");
        let banco_nombre = $("#BANCO_" + ID + " option:selected").text();
        $("#input_emp_" + ID).val(banco);
        if (banco == undefined) {
            $("#input_lleno_" + ID).text(0);
            $("#input_bc_nombre_" + ID).text("");

        } else {
            $("#input_lleno_" + ID).text(1);
            $("#input_bc_nombre_" + ID).text(banco_nombre);

        }
    }

    function PR_AGREGAR_NUEVA_COLUMNA() {
        LINEA();

        function LINEA() {
            var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_BODY tr').length;
            var c = `
       <tr style="font-size:12px">
            <td>
                <select name="" id="BANCO_` + tbl + `" onchange = "empresa(` + tbl + `)" class="form-select form-select-solid s">
                </select>
            </td>
            <td>
                <input disabled id="input_emp_` + tbl + `"  style="font-size:12px" type="text"  step="0.1" class="form-control form-control-solid" >
            </td>
            <td>
                <input id="input_con_` + tbl + `"  style="font-size:12px" type="text"  step="0.1" class="form-control" value="0.00">
            </td>
            <td>
                <input id="input_dis_` + tbl + `" style="font-size:12px" type="text" class="form-control " value="0.00">
            </td>
            <td>
                <input id="input_d_` + tbl + `"  onkeyup="VAlidar_Input_3(` + tbl + `)" style="font-size:12px" type="text" class="form-control " value="0.00">
            </td>
            <td>
                <input id="input_coment_` + tbl + `"  onkeyup="VAlidar_Input_4(` + tbl + `)" style="font-size:12px" type="text" class="form-control ">
            </td>
            
            <td>
                <button style="font-size:12px" class="btn btn_remove"><i class="bi bi-trash-fill fs-2"></i></button>
            </td>
            
            <td style='display:none;'>0
            </td>
            <td style='display:none;'>-1
            </td>
            <td style='display:none'>` + tbl + `
            </td>
            <td id="input_lleno_` + tbl + `" style='display:none'>0
            </td>
            <td id="input_bc_nombre_` + tbl + `" style='display:none'>
            </td>
        </tr>
       `;
            setTimeout(() => {
                d();
            }, 100);

            function d() {
                let x = [{
                    Rubro_ID: "asd",
                    Nombre: "Banco bolivaridao"
                }, {
                    Rubro_ID: "sdas",
                    Nombre: "Banco bolis"
                }]
                var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_BODY tr').length - 1;

                var CbLogusuarios = document.getElementById("BANCO_" + tbl + "");
                // $('#BANCO_"' + tbl + '" option').remove(); // clear all values 
                // $("#BANCO")[0].selectedIndex = 0;
                $('#BANCO_' + tbl + '').append("<option class='fw-bold' value=''>Seleccione</option>")
                jQuery.each(ARRAY_BANCOS_CARTIMEX, function(key, value) {
                    option = document.createElement("option");
                    option.value = value.ID;
                    option.text = value.Nombre;
                    option.setAttribute("empresa", value.empresa);
                    option.className = "fs-5 fw-bold";
                    CbLogusuarios.appendChild(option);
                });

            }
            $("#TABLA_PROTECCION_PRECIO_BODY").append(c);
            // feather.replace();
            var fl = $("#Fecha_desde_" + tbl).flatpickr({
                minDate: "today"

            });
            var fl = $("#Fecha_desde_" + tbl).flatpickr({
                minDate: "today"

            });
            $('#BANCO_' + tbl).select2();
        }


    }



    // *----------------------------------------------------------------------------------//*-----------
    // *----------------------------------------------------------------------------------//*-----------
    // *----------------------------------------------------------------------------------//*-----------
    // *----------------------------------------------------------------------------------//*-----------


    // function Cargar_Bancos_computron() {

    //     AjaxSendReceiveData(url_Consultar_Bancos, [], function(x) {

    //         ARRAY_BANCOS = x;
    //     });
    //     AjaxSendReceiveData(url_Cargar_Datos, [], function(x) {

    //         if (x.length == 0) {
    //             NUEVO_();
    //         } else {
    //             Crear_Lineas_Bancos_Existentes_computron(x);
    //         }
    //     });
    // }
    // // Cargar_Bancos_computron();


    // function Crear_Lineas_Bancos_Existentes_computron(data) {

    //     $("#TABLA_PROTECCION_PRECIO_CUERPO").empty();
    //     $("#TABLA_PROTECCION_PRECIO_CABEZERA").empty();
    //     var thead = `
    //     <tr style="font-weight: bold;">
    //                 <td style="width: 170px;">Banco</td>
    //                 <td style="width: 80px;">Saldo Contable</td>
    //                 <td style="width: 80px;">Saldo Disponible</td>
    //                 <td style="width: 80px;">Deposita Dia</td>
    //                 <td style="width: 80px;">Borrar Dia</td>
    //             </tr>
    //     `;

    //     $("#TABLA_PROTECCION_PRECIO_CUERPO").append(thead);
    //     let formatter = new Intl.NumberFormat('en-US', {
    //         style: 'currency',
    //         currency: 'USD',
    //     });
    //     data.map(function(x, i) {
    //         var tabla = `
    //             <tr style="font-size:12px">
    //                 <td>
    //                     <h3 name="" id="BANCO" >
    //                         ` + x.Nombre + `
    //                     </h3>
    //                 </td>
    //                 <td>
    //                     <input id="input_con_com` + i + `" onkeyup="VAlidar_Input_4(` + i + `)" style="font-size:12px" type="text" step="0.1" 
    //                       class="form-control form-control-solid" value="` + formatter.format(x.saldo_contable).split("$")[1] + `">
    //                 </td>
    //                 <td>
    //                     <input id="input_dis_com` + i + `" onkeyup="VAlidar_Input_5(` + i + `)" style="font-size:12px" type="text" min="0"
    //                      step="0.1" class="form-control form-control-solid" value="` + formatter.format(x.saldo_disponible).split("$")[1] + `">
    //                 </td>
    //                 <td>
    //                     <input id="input_d_com` + i + `" onkeyup="VAlidar_Input_6(` + i + `)" style="font-size:12px" type="text" min="0.01"
    //                      step="0.1" class="form-control form-control-solid" value="` + formatter.format(x.deposito_dia).split("$")[1] + `">
    //                 </td>
    //                 <td>
    //                     <button style="font-size:12px" class="btn btn_remove"><i class="bi bi-trash-fill"></i></button>
    //                 </td>
    //                 <td style='display:none;'>
    //                 ` + x.Saldo_id + `
    //                 </td>
    //                 <td style='display:none;'>
    //                 ` + x.Saldo_id + `
    //                 </td>
    //                 <td style='display:none;'>
    //                 ` + x.Nombre + `
    //                 </td>
    //                 <td style='display:none;'>
    //                 </td>
    //         </tr>
    //     `;
    //         $("#TABLA_PROTECCION_PRECIO_CUERPO").append(tabla);
    //     });
    // }

    // function VAlidar_Input_4(i) {
    //     
    //     $("#input_con_com" + i).on({
    //         "focus": function(event) {
    //             $(event.target).select();
    //         },
    //         "keyup": function(event) {
    //             $(event.target).val(function(index, value) {
    //                 return value.replace(/\D/g, "")
    //                     .replace(/([0-9])([0-9]{2})$/, '$1.$2')
    //                     .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    //             });
    //         }
    //     });
    // }

    // function VAlidar_Input_5(i) {
    //     
    //     $("#input_dis_com" + i).on({
    //         "focus": function(event) {
    //             $(event.target).select();
    //         },
    //         "keyup": function(event) {
    //             $(event.target).val(function(index, value) {
    //                 return value.replace(/\D/g, "")
    //                     .replace(/([0-9])([0-9]{2})$/, '$1.$2')
    //                     .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    //             });
    //         }
    //     });
    // }

    // function VAlidar_Input_6(i) {
    //     
    //     $("#input_d_com" + i).on({
    //         "focus": function(event) {
    //             $(event.target).select();
    //         },
    //         "keyup": function(event) {
    //             $(event.target).val(function(index, value) {
    //                 return value.replace(/\D/g, "")
    //                     .replace(/([0-9])([0-9]{2})$/, '$1.$2')
    //                     .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    //             });
    //         }
    //     });
    // }

    // function Validar_TAbla_c() {
    //     var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_COMPUTRON tr').length;

    //     if (tbl <= 1) {
    //         Mensaje("Tabla vacia", "No hay elementos en la tabla", "error");
    //     } else {
    //         var tabla = document.getElementById("TABLA_PROTECCION_PRECIO_COMPUTRON");
    //         var VAL_COUNTER = 0;
    //         for (var i = 1; i < tbl; i++) {
    //             var banco = tabla.rows[i].cells[0].children[0].value;
    //             var s_contable = tabla.rows[i].cells[1].children[0].value;
    //             var s_disponible = (tabla.rows[i].cells[2].children[0].value);
    //             var deposito_dia = (tabla.rows[i].cells[3].children[0].value);

    //             if (banco == "") {
    //                 tabla.rows[i].className = "bg-danger";

    //                 VAL_COUNTER++;
    //             }
    //             if (s_contable == "") {
    //                 tabla.rows[i].className = "bg-danger";

    //                 VAL_COUNTER++;
    //             }
    //             if (s_disponible == "") {
    //                 tabla.rows[i].className = "bg-danger";
    //                 VAL_COUNTER++;
    //             }
    //             if (deposito_dia == "") {
    //                 tabla.rows[i].className = "bg-danger";
    //                 VAL_COUNTER++;
    //             }
    //         }
    //         return VAL_COUNTER;
    //     }
    // }

    // function DATA_TO_SEND_C(callback) {
    //     var tabla = document.getElementById("TABLA_PROTECCION_PRECIO_COMPUTRON");
    //     var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_COMPUTRON tr').length;

    //     var ARRAY_DATA = [];
    //     for (var i = 1; i < tbl; i++) {
    //         var banco;
    //         var banco_nombre;
    //         var saldo_id;

    //         try {
    //             banco = tabla.rows[i].cells[0].getElementsByTagName("select")[0].value;
    //             banco_nombre = tabla.rows[i].cells[0].getElementsByTagName("select")[0]["selectedOptions"][0]["innerText"];
    //             saldo_id = tabla.rows[i].cells[6].innerText;

    //         } catch (error) {
    //             banco = tabla.rows[i].cells[5].innerText;
    //             banco_nombre = tabla.rows[i].cells[7].innerText;
    //             saldo_id = tabla.rows[i].cells[6].innerText;

    //         }
    //         var s_contable = tabla.rows[i].cells[1].children[0].value;
    //         var s_disponible = tabla.rows[i].cells[2].children[0].value;
    //         var deposito_dia = tabla.rows[i].cells[3].children[0].value;
    //         var b = {
    //             banco: banco.trim(),
    //             s_contable: s_contable,
    //             s_disponible: s_disponible,
    //             deposito_dia: deposito_dia,
    //             banco_nombre: banco_nombre.trim(),
    //             saldo_id: saldo_id.trim()
    //         }
    //         ARRAY_DATA.push(b);
    //     }
    //     callback(ARRAY_DATA);
    // }

    // function PR_AGREGAR_NUEVA_COLUMNA_COMPUTRON() {

    //     var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_COMPUTRON tr').length;


    //     var c = `
    //    <tr style="font-size:12px">
    //         <td>
    //             <select name="" id="BANCO_C_` + tbl + `" class="form-select form-select-solid s">

    //             </select>
    //         </td>
    //         <td>
    //             <input id="input_con_com` + tbl + `" onkeyup="VAlidar_Input_4(` + tbl + `)" style="font-size:12px" type="text"  step="0.1" class="form-control form-control-solid" value="0.00">
    //         </td>
    //         <td>
    //             <input id="input_dis_com` + tbl + `" onkeyup="VAlidar_Input_5(` + tbl + `)"style="font-size:12px" type="text" class="form-control form-control-solid" value="0.00">
    //         </td>
    //         <td>
    //             <input id="input_d_com` + tbl + `" onkeyup="VAlidar_Input_6(` + tbl + `)" style="font-size:12px" type="text" class="form-control form-control-solid" value="0.00">
    //         </td>

    //         <td>
    //             <button style="font-size:12px" class="btn btn_remove"><i class="bi bi-trash-fill"></i></button>
    //         </td>

    //         <td style='display:none;'>
    //         </td>
    //         <td style='display:none;'>nn
    //         </td>
    //         <td style='display:none;'>
    //         </td>
    //         <td style='display:none;'>
    //         </td>
    //     </tr>
    //    `;
    //     setTimeout(() => {
    //         d();
    //     }, 100);

    //     function d() {
    //         let x = [{
    //             Rubro_ID: "asd",
    //             Nombre: "Banco bolivaridao"
    //         }, {
    //             Rubro_ID: "sdas",
    //             Nombre: "Banco bolis"
    //         }]
    //         var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_COMPUTRON tr').length - 1;

    //         var CbLogusuarios = document.getElementById("BANCO_C_" + tbl + "");
    //         // $('#BANCO_"' + tbl + '" option').remove(); // clear all values 
    //         // $("#BANCO")[0].selectedIndex = 0;

    //         $('#BANCO_C_' + tbl + '').append("<option class='fw-bold' value=''>Seleccione</option>")
    //         jQuery.each(ARRAY_BANCOS_COMPUTROM, function(key, value) {
    //             option = document.createElement("option");
    //             option.value = value.ID;
    //             option.text = value.Nombre;
    //             option.className = "fs-5 fw-bold";
    //             CbLogusuarios.appendChild(option);
    //         });
    //     }
    //     $("#TABLA_PROTECCION_PRECIO_COMPUTRON").append(c);
    //     // feather.replace();
    //     var fl = $("#Fecha_desde_" + tbl).flatpickr({
    //         minDate: "today"

    //     });
    //     var fl2 = $("#Fecha_hasta_" + tbl).flatpickr({
    //         minDate: "today"
    //     });
    //     $('#BANCO_C_' + tbl).select2();

    // }

    // function NUEVO_TABLA_COMPUTRON() {

    // function NUEVO_TABLA_COMPUTRON() {

    //     $("#TABLA_PROTECCION_PRECIO_CUERPO").empty();
    //     $("#TABLA_PROTECCION_PRECIO_CABEZERA").empty();
    //     var thead = `
    //     <tr style="font-weight: bold;">
    //                 <td style="width: 170px;">Banco</td>
    //                 <td style="width: 80px;">Saldo Contable</td>
    //                 <td style="width: 80px;">Saldo Disponible</td>
    //                 <td style="width: 80px;">Deposita Dia</td>
    //                 <td style="width: 80px;">Borrar</td>
    //             </tr>
    //     `;
    //     $("#TABLA_PROTECCION_PRECIO_CUERPO").append(thead);
    //     var tbl = document.querySelectorAll('#TABLA_PROTECCION_PRECIO_COMPUTRON tr').length;

    //     var tabla = `
    //             <tr style="font-size:12px">
    //                 <td>
    //                     <select name="" id="BANCO_C" class="form-select form-select-solid s">
    //                         <option value="">Seleccione</option>
    //                     </select>
    //                 </td>
    //                 <td>
    //                     <input id="input_con_com` + tbl + `" onkeyup="VAlidar_Input_4(` + tbl + `)" style="font-size:12px" type="text"   class="form-control form-control-solid" value="0.00">
    //                 </td>
    //                 <td>
    //                     <input id="input_dis_com` + tbl + `" onkeyup="VAlidar_Input_5(` + tbl + `)" style="font-size:12px" type="text"  class="form-control form-control-solid" value="0.00">
    //                 </td>
    //                 <td>
    //                     <input id="input_d_com` + tbl + `" onkeyup="VAlidar_Input_6(` + tbl + `)" style="font-size:12px" type="text"  class="form-control form-control-solid" value="0.00">
    //                 </td>
    //                 <td>
    //                     <button style="font-size:12px" class="btn btn_remove"><i class="bi bi-trash-fill"></i></button>
    //                 </td>
    //                 <td style='display:none;'>
    //                 </td>
    //                 <td style='display:none;'>nn
    //                 </td>
    //                 <td style='display:none;'>
    //                 </td>
    //                 <td style='display:none;'>
    //                 </td>
    //         </tr>
    //     `;
    //     setTimeout(() => {
    //         d();
    //     }, 1000);

    //     function d() {

    //         var CbLogusuarios = document.getElementById("BANCO_C");
    //         $('#BANCO_C option').remove(); // clear all values 
    //         // $("#BANCO")[0].selectedIndex = 0;
    //         $("#BANCO_C").append("<option class='fw-bold' value=''>Seleccione</option>")
    //         jQuery.each(ARRAY_BANCOS_COMPUTROM, function(key, value) {
    //             option = document.createElement("option");
    //             option.value = value.ID;
    //             option.text = value.Nombre;
    //             option.className = "fs-5 fw-bold";
    //             CbLogusuarios.appendChild(option);
    //         });
    //     }
    //     $("#TABLA_PROTECCION_PRECIO_CABEZERA").append(tabla);
    //     var fl = $("#Fecha_desde").flatpickr({
    //         minDate: "today"
    //     });
    //     var fl2 = $("#Fecha_hasta").flatpickr({
    //         minDate: "today"
    //     });
    //     $('#BANCO_C').select2();

    //     // feather.replace();
    // }

    // function Guardar_DATOS_S_C() {
    //     let t = Validar_TAbla_c();

    //     if (t == 0) {

    //         DATA_TO_SEND_C(function(x) {

    //             setTimeout(send(x), 1000);
    //         });

    //         function send(ARRAY_DATA) {
    //             Swal.fire({
    //                 title: 'Antes de Continuar',
    //                 text: "Verifique que los datos esten correctos",
    //                 icon: 'info',
    //                 showCancelButton: true,
    //                 confirmButtonColor: '#3085d6',
    //                 cancelButtonColor: '#d33',
    //                 confirmButtonText: 'Si, continuar!',
    //                 cancelButtonText: 'Cancelar'
    //             }).then((result) => {
    //                 if (result.isConfirmed) {
    //                     ARRAY_DATA.map(function(x) {

    //                         x.Empresa = "COMPUTRONSA"
    //                     });
    //                     
    //                     AjaxSendReceiveData(url_Guardar_Datos, ARRAY_DATA, function(x) {

    //                         if (x <= 0) {
    //                             Mensaje("Error al guardar", "", "error");
    //                         } else if (x < ARRAY_DATA) {
    //                             Mensaje("Error al guardar", "", "error");
    //                         } else {
    //                             Mensaje("Datos guardado", "", "success");
    //                             Cargar_Bancos();

    //                             setTimeout(() => {
    //                                 // location.reload()
    //                             }, 1000);
    //                         }
    //                     });
    //                 }
    //             });
    //         }

    //     } else {
    //         Mensaje("Hay campos sin completar, Porfavor verifique", "", "error")
    //     }
    // }



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