<?php

$url_Cargar_Datos = constant('URL') . 'principal/Cargar_Datos/';

?>

<script>
    var url_Cargar_Datos = '<?php echo $url_Cargar_Datos ?>';

    function Mensaje(t1, t2, ic) {
        Swal.fire(
            t1,
            t2,
            ic
        );
    }

    function Buscar_cedula() {
        let cedula = $("#PROF_CLI_CEDULA").val();
        let param = {
            cedula: cedula
        }
        if (cedula == "") {
            Mensaje("Debe ingresar un numero de cedula", "", "error");
            $("#CON").hide();

        } else {
            AjaxSendReceiveData(url_Cargar_Datos, param, function(x) {
                console.log('x: ', x);
                if (x == null) {
                    Mensaje("Cedula no Encontrada", "", "info");
                    $("#CON").hide();

                } else {
                    let newArray = x.slice(0, 5)
                    newArray.map(function(x) {
                        if (x.importance >= 0) {
                            x.importance = x.importance * 100
                            x.color = am4core.color("#dc6788")
                        } else {
                            x.importance = x.importance * -100
                            x.color = am4core.color("#67dc98")
                        }
                        x.valor = x.values
                        return x
                    })

                    x.reverse()
                    let newArray2 = x.slice(0, 5)
                    newArray2.map(function(x) {
                        if (x.importance >= 0) {
                            x.importance = x.importance * 100
                            x.color = am4core.color("#dc6788")
                        } else {
                            x.importance = x.importance * -100
                            x.color = am4core.color("#67dc98")
                        }
                        x.valor = x.values
                        return x
                    })

                    newArray = newArray.concat(newArray2)
                    console.log('newArray: ', newArray);


                    Grafico(newArray);
                    $("#CON").show(100);
                    $("#ENTRE").text(newArray[0]["prediccion"])
                    
                }
            })
        }


    }
    // Grafico()
    function Grafico(data) {
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            var chart = am4core.create("chartdiv", am4charts.XYChart);
            chart.padding(40, 40, 40, 40);

            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.dataFields.category = "features";
            categoryAxis.renderer.minGridDistance = 1;
            categoryAxis.renderer.inversed = true;
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.logarithmic = true;

            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;

            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryY = "features";
            series.dataFields.valueX = "importance";
            series.tooltipText = "{valueX.value}"
            series.columns.template.strokeOpacity = 0;
            series.columns.template.column.cornerRadiusBottomRight = 5;
            series.columns.template.column.cornerRadiusTopRight = 5;

            var labelBullet = series.bullets.push(new am4charts.LabelBullet())
            labelBullet.label.horizontalCenter = "left";
            labelBullet.label.dx = 10;
            labelBullet.label.text = "{importance}";
            labelBullet.locationX = 1;

            // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
            // series.columns.template.adapter.add("fill", function(fill, target) {
            //     return chart.colors.getIndex(target.dataItem.index);
            // });
            var columnTemplate = series.columns.template;
            columnTemplate.strokeOpacity = 0;
            columnTemplate.propertyFields.fill = "color";

            categoryAxis.sortBySeries = series;
            chart.data = data



        }); // end am4core.ready()
    }
    // function Grafico(data) {
    //     am4core.ready(function() {

    //         // Themes begin
    //         var chart = am4core.create("chartdiv", am4charts.XYChart);

    //         // using math in the data instead of final values just to illustrate the idea of Waterfall chart
    //         // a separate data field for step series is added because we don't need last step (notice, the last data item doesn't have stepValue)
    //         chart.data = data;

    //         var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    //         categoryAxis.dataFields.category = "features";
    //         categoryAxis.renderer.minGridDistance = 10;

    //         var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    //         valueAxis.logarithmic = true;

    //         var columnSeries = chart.series.push(new am4charts.ColumnSeries());
    //         columnSeries.dataFields.categoryX = "features";
    //         columnSeries.dataFields.valueY = "importance";
    //         // columnSeries.dataFields.openValueY = "importance";

    //         var columnTemplate = columnSeries.columns.template;
    //         columnTemplate.strokeOpacity = 0;
    //         columnTemplate.propertyFields.fill = "color";

    //         var label = columnTemplate.createChild(am4core.Label);
    //         label.align = "center";
    //         label.valign = "middle";
    //         // label.adapter.add("text", function(text, target) {
    //         //     if (!target.dataItem) {
    //         //         return text;
    //         //     }
    //         //     var value = Math.abs(target.dataItem.dataContext.value - target.dataItem.dataContext.open);
    //         //     return target.numberFormatter.format(value, "$#,## a");
    //         // });

    //         var stepSeries = chart.series.push(new am4charts.StepLineSeries());
    //         stepSeries.dataFields.categoryX = "features";
    //         stepSeries.dataFields.valueY = "importance";
    //         stepSeries.noRisers = true;
    //         stepSeries.stroke = am4core.color("#000");
    //         stepSeries.strokeDasharray = "3,3";

    //         // because column width is 80%, we modify start/end locations so that step would start with column and end with next column
    //         stepSeries.startLocation = 0.1;
    //         stepSeries.endLocation = 1.1;



    //     }); // end am4core.ready()
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