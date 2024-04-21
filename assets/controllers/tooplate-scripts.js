const width_threshold = 480;

function drawLineChart(xLabels, totalReports, totalClosedReports) {
    if ($("#lineChart").length) {

        ctxLine = document.getElementById("lineChart").getContext("2d");
        optionsLine = {
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 20
                        }
                    }
                }
            },
            scales: {
                yAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "Nombre"
                        },
                        ticks: {
                            fontSize: 20
                        }
                    }
                ],
                title: {
                    font: {
                        size: 20
                    }

                },
                xAxes: [
                    {
                        ticks: {
                            fontSize: 20
                        }
                    }
                ]
            }
        }
        ;

        // Set aspect ratio based on window width
        optionsLine.maintainAspectRatio =
            $(window).width() >= width_threshold;

        configLine = {
            type: "line",
            data: {
                labels: xLabels,
                datasets: [
                    {
                        label: "Signalements traités",
                        data: [2, 3, 4, 12, totalClosedReports], // oui mdr
                        fill: true,
                        backgroundColor: "rgba(139, 21, 56, 0.3)",
                        borderColor: "rgb(139, 21, 56)",
                        cubicInterpolationMode: "monotone",
                        pointRadius: 0
                    },
                    {
                        label: "Signalements reçus",
                        data: [2, 5, 10, 20, totalReports],
                        fill: true,
                        backgroundColor: "rgba(75, 192, 192, 0.3)",
                        borderColor: "rgb(75, 192, 192)",
                        cubicInterpolationMode: "monotone",
                        pointRadius: 0
                    }
                ]
            },
            options: optionsLine
        };

        lineChart = new Chart(ctxLine, configLine);
    }
}

function updateLineChart() {
    if (lineChart) {
        lineChart.options = optionsLine;
        lineChart.update();
    }
}
