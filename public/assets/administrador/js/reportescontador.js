function loadData() {
    Highcharts.chart('graficoVisitas', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Visitas en la Página Web entre '+fstart+' y '+fend
        },
        xAxis: {
            categories: labels,
            crosshair: true,
            accessibility: {
                description: 'Visitas'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de visitas'
            }
        },
        dataLabels: {
            enabled: true,
            format: '{point.y:.0f}'
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },
        tooltip: {
            formatter: function () {
                return `<b>Fecha: </b>${this.category}<br><b>Visitas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Visitas',
                data: numberArray
            }
        ]
    });
}

function configChart() {
    Highcharts.setOptions({
        lang: {
            // Números y fechas
            decimalPoint: ',',
            thousandsSep: '.',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],

            // Menú de exportación
            contextButtonTitle: 'Menú de exportación',
            downloadPNG: 'Descargar PNG',
            downloadJPEG: 'Descargar JPEG',
            downloadPDF: 'Descargar PDF',
            downloadSVG: 'Descargar SVG',
            printChart: 'Imprimir gráfico',

            // Opciones extra del menú
            viewFullscreen: 'Ver en pantalla completa',
            exitFullscreen: 'Salir de pantalla completa',
            viewData: 'Ver tabla de datos',
            hideData: 'Ocultar tabla de datos',

            // Interacciones
            resetZoom: 'Reiniciar zoom',
            resetZoomTitle: 'Reiniciar zoom 1:1',

            // Estados
            loading: 'Cargando...',
            noData: 'No hay datos para mostrar'
        }
    });
}

function loadDatPicker() {
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
        locale: {
            format: 'MM/DD/YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            weekLabel: 'S',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        },
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(1, 'days'),
        endDate: moment()
    }, function (start, end, label) {
        // Guardamos la instancia
    });
}

function getfiltroFechas() {
    var token = $("#token").val();
    let picker = $("#reservationtime").data("daterangepicker");

    let start = picker.startDate.format("YYYY-MM-DD");
    let end = picker.endDate.format("YYYY-MM-DD");

    const diasTranscurridos = contarDiasTranscurridos(start, end);

    if(diasTranscurridos <= 10){
        $('#graficoVisitas').html('');
        $("#modalCargando").modal("show");
        $.ajax({
            url: "/repcon/filtrar",
            type: "POST",
            dataType: 'json',
            headers: {
                "X-CSRF-TOKEN": token,
            },
            data: {
                fecha_inicio: start,
                fecha_fin: end
            },
            success: function (res) {
                var numberArray = res.data.map(Number);
                setTimeout(() => {
                    $('#h3ContSubCat').html(res.totalValor);
                    loadDatafromfilter(start, end, res.labels, numberArray);
                    $("#modalCargando").modal("hide");
                }, 1500);
            },
            error: function () {
                $("#modalCargando").modal("hide");
                Swal.fire({
                    title: "Ha ocurrido un Error",
                    html: "<p>Error al Filtrar los Datos",
                    type: "error",
                });
            },
            statusCode: {
                400: function () {
                    Swal.fire({
                        title: "Ha ocurrido un Error",
                        html:
                            "<p>Al momento no hay conexión con el <strong>Servidor</strong>.<br>" +
                            "Intente nuevamente</p>",
                        type: "error",
                    });
                },
            },
        });
    }else{
        swal('Debe seleccionar un rango de fecha de 10 días', '', 'error');
    }
}

function loadDatafromfilter(start, end, labelfil, infodato) {
    Highcharts.chart('graficoVisitas', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Visitas en la Página Web entre '+start+' y '+end
        },
        xAxis: {
            categories: labelfil,
            crosshair: true,
            accessibility: {
                description: 'Visitas'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de visitas'
            }
        },
        dataLabels: {
            enabled: true,
            format: '{point.y:.0f}'
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.0f}'
                }
            }
        },
        tooltip: {
            formatter: function () {
                return `<b>Fecha: </b>${this.category}<br><b>Visitas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Visitas',
                data: infodato
            }
        ]
    });
}