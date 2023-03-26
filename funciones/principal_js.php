<?php


$url_nueva_orden = constant('URL') . 'principal/Guardar_datos';


?>

<script>
    // Cambiar las URL por la nueva del archivo //    


    var $url_nueva_orden = '<?php echo $url_nueva_orden ?>';


    // CREAR UNA FUNCION   //   


    function grabar_datos() {

        var Tipo = $("#Tipo").val();
        var Marca = $("#Marca").val();
        var Referencia = $("#Referencia").val();
        var Fecha = $("#Fecha").val();
        var Periodo = $("#Periodo").val();
        var Concepto = $("#Concepto").val();
        var valor = $("#Valor").val();


        let Guardar = {

            Tipo: Tipo,
            Marca: Marca,
            Referencia: Referencia,
            Fecha: Fecha,
            Periodo: Periodo,
            Concepto: Concepto,
            valor: valor,

        }

        if (Tipo == "") {

            Swal.fire(
                'Error ',
                'Seleccione una Tipo',
                'error'
            )


        } else if (Marca == "") {

            Swal.fire(
                'Error ',
                'Escriba una Marca',
                'error'
            )


        } else if (Referencia == "") {
            Swal.fire(
                'Error ',
                'Escriba una Referencia',
                'error'
            )

        } else if (Valor == "") {
            Swal.fire(
                'Error ',
                'Escriba una Valor',
                'error'
            )

        } else if (Concepto == "") {

            Swal.fire(

                'Error ',
                'Escriba una Concepto',
                'error'
            )

        } else if (Periodo == "") {

            Swal.fire(

                'Error ',
                'Seleccione una Concepto',
                'error'
            )

        } else {

            AjaxSendReceiveData($url_nueva_orden, Guardar, function(respuesta) {

                console.log('respuesta: ', respuesta);



                $(document).ready(function() {

                    $("#grabar_datos").prop('disabled', true)

                    
                    Swal.fire(
                        'Exito',
                        'Datos Guardados',
                        'success'
                    );
                })
            })
        }
    };

    let refresh = document.getElementById('Ingresar');
                    refresh.addEventListener('click', _ => {
                        location.reload();
                    })
      
    //--- Funcion Creacion Fecha automarica 

    window.onload = function() {
        var fecha = new Date(); //Fecha actual
        var mes = fecha.getMonth() + 1; //obteniendo mes
        var dia = fecha.getDate(); //obteniendo dia
        var ano = fecha.getFullYear(); //obteniendo año
        if (dia < 10)
            dia = '0' + dia; //agrega cero si el menor de 10
        if (mes < 10)
            mes = '0' + mes //agrega cero si el menor de 10
        document.getElementById('Fecha').value = ano + "-" + mes + "-" + dia;
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