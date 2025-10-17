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

function loadDataDocAdmin(categoria, valores ) {
    Highcharts.chart('graficoDescargasDocAdmin', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Doc. Administrativa'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Categorías'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Categoría: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Categorías',
                data: valores
            }
        ]
    });
}

function loadDataDocFin(categoria, valores ) {
    Highcharts.chart('graficoDescargasDocFin', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Doc. Financiera'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Años'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Años',
                data: valores
            }
        ]
    });
}

function loadDataDocOpt(categoria, valores ) {
    Highcharts.chart('graficoDescargasDocOpt', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Doc. Operativa'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Años'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Años',
                data: valores
            }
        ]
    });
}

function loadDataDocLab(categoria, valores ) {
    Highcharts.chart('graficoDescargasDocLab', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Doc. Laboral'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Años'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Años',
                data: valores
            }
        ]
    });
}

function loadDataLey(categoria, valores ) {
    Highcharts.chart('graficoDescargasLey', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Reglamentos'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Documentos'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Documentos',
                data: valores
            }
        ]
    });
}

function loadDataDocAudt(categoria, valores ) {
    Highcharts.chart('graficoDescargasDocAud', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - Doc. Auditoría'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Años'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Años',
                data: valores
            }
        ]
    });
}

function loadDataRC(categories, valores ) {
    Highcharts.chart('graficoDescargasRC', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Número de Descargas - Rendición de Cuentas'
        },
        xAxis: {
            categories: categories,
            crosshair: true,
            accessibility: {
                description: 'Años'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            },
            gridLineWidth: 0
        },
        dataLabels: {
            enabled: true,
            format: '{point.y:.0f}'
        },
        plotOptions: {
            bar: {
                borderRadius: '50%',
                dataLabels: {
                    enabled: true
                },
                groupPadding: 0.1
            }
        },
        tooltip: {
            formatter: function () {
                return `<b>Año: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: 'var(--highcharts-background-color, #ffffff)',
            shadow: true
        },
        series: valores
    });
}

function loadDatLotaip(valores ) {
    Highcharts.chart('graficoDescargasLotaip', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - LOTAIP'
        },
        xAxis: {
            categories: ['Enero', 'Febrero','Marzo',
                    'Abril','Mayo','Junio','Julio',
                    'Agosto', 'Septiembre','Octubre',
                    'Noviembre','Diciembre'
                ],
            crosshair: true,
            accessibility: {
                description: 'Meses'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año:</b> ${this.series.name}<br><b>Mes: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: valores
    });
}

function loadDatLotaipv2(valores ) {
    Highcharts.chart('graficoDescargasLotaip', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas - LOTAIP V2'
        },
        xAxis: {
            categories: ['Enero', 'Febrero','Marzo',
                    'Abril','Mayo','Junio','Julio',
                    'Agosto', 'Septiembre','Octubre',
                    'Noviembre','Diciembre'
                ],
            crosshair: true,
            accessibility: {
                description: 'Meses'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Año:</b> ${this.series.name}<br><b>Mes: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: valores
    });
}

function loadDataBiblioVirtual(categoria, valores ) {
    Highcharts.chart('graficoDescargasBibliotecaVirtual', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Descargas -  Biblioteca Virtual'
        },
        xAxis: {
            categories: categoria,
            crosshair: true,
            accessibility: {
                description: 'Categorías'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Número de Descargas'
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
                return `<b>Categoría: </b>${this.category}<br><b>Descargas: </b>${this.y}`;
            },
            style: {
                fontSize: '12px'
            }
        },
        series: [
            {
                name: 'Categorías',
                data: valores
            }
        ]
    });
}