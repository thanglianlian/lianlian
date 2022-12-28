{{-- <div style="width:100%;text-align: center"><label style="text-align: center">TOP 10 PRODUCTS REVENUE</label></div> --}}
<div class="barChartPanel divChart">

    <canvas id="myChartBar" class="canvasChart"></canvas>

</div>

<style>

</style>

<script>


var dataBar = @json($dataBarChart);
//console.log(dataMonth);

const barLabel = dataBar["label"];
const barData = dataBar["revenue"];

const ctxBar = document.getElementById('myChartBar').getContext('2d');;

var chartBar = new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: barLabel,
      datasets: [{
        label: 'Revenue',
        //data: [12, 19, 3, 5, 2, 3],
        data: barData,

        barPercentage: 0.5,
        barThickness: 8,
        maxBarThickness: 10,
        minBarLength: 2,

        borderWidth: 1,
        backgroundColor: [
            'rgba(54, 99, 2, 0.2)',
            'rgba(61, 112, 1, 0.2)',
            'rgba(70, 128, 1, 0.2)',
            'rgba(81, 148, 1, 0.2)',
            'rgba(92, 168, 2, 0.2)',
            'rgba(102, 186, 2, 0.2)',
            'rgba(113, 207, 2, 0.2)',
            'rgba(124, 227, 2, 0.2)',
            'rgba(134, 245, 2, 0.2)',
            'rgba(148, 245, 32, 0.2)'
            ],
        borderColor: [
            'rgba(54, 99, 2)',
            'rgba(61, 112, 1)',
            'rgba(70, 128, 1)',
            'rgba(81, 148, 1)',
            'rgba(92, 168, 2)',
            'rgba(102, 186, 2)',
            'rgba(113, 207, 2)',
            'rgba(124, 227, 2)',
            'rgba(134, 245, 2)',
            'rgba(148, 245, 32)'
            ]
      }]
    },
    options: {
        indexAxis: 'y',
        maintainAspectRatio: false,
        responsive: true,
        layout: {
            padding: {

                right: 40

            }
        },
        plugins: {
            legend: {
                    display: false,
                    labels: {
                        // This more specific font property overrides the global property
                        font: {
                            size: 8
                        }
                    }
            },
            datalabels: {
                anchor: 'end', // remove this line to get label in middle of the bar
                align: 'end',
                //formatter: (val) => (`${val}%`),
                labels: {
                    value: {
                        color: 'black',
                        font: {
                            size: 8,
                            weight: 'bold'
                        }
                    }

                }


            },

            title: {
                display: true,
                text: 'TOP 10 PRODUCTS REVENUE'
            }

        },

        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 10,
                    }
                }


            },
            y: {
                beginAtZero: true,
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 10,
                    }
                }
            }
        }


    }
  });
  //chartBar.register(ChartDataLabels);

function removeDataChartBar(chart) {

    var countNumberLabel = chart.data.labels.length;
    for(i=0;i<countNumberLabel;i++){
        chart.data.labels.pop();
    }

    chart.data.datasets.forEach((dataset) => {
        for(i=0;i<countNumberLabel;i++){
            dataset.data.pop();
        }
    });
    chart.update();
}

function addDataChartBar(chart, label, data) {
    for(j=0;j<label.length;j++){


        chart.data.labels.push(label[j]);
    }
    chart.data.datasets.forEach((dataset) => {
        for(j=0;j<data.length;j++){
            //console.log(data[j]);
            dataset.data.push(data[j]);
        }
    });
    chart.update();
}

</script>
