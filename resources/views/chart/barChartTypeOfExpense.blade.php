{{-- <div style="width:580px;text-align: center"><label style="text-align: center">TYPE OF EXPENSES</label></div> --}}
<div class="barChartTypeOfExpensePanel divChart">
    <canvas id="myChartBarTypeOfExpense" class="canvasChart"></canvas>

</div>

<style>

</style>

<script>


var dataBarChartTypeOfExpense = @json($dataBarChartTypeOfExpense);
//console.log(dataMonth);

const barExpenseLabel = dataBarChartTypeOfExpense["label"];
const barExpenseData = dataBarChartTypeOfExpense["value"];

const ctxChartBarTypeOfExpense = document.getElementById('myChartBarTypeOfExpense').getContext('2d');;

var chartBarTypeOfExpense = new Chart(ctxChartBarTypeOfExpense, {
    type: 'bar',
    data: {
      labels: barExpenseLabel,
      datasets: [{
        label: 'Revenue',
        data: barExpenseData,



        barPercentage: 0.5,
        barThickness: 10,
        maxBarThickness: 20,
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
        //barPercentage: 0.98,  // here

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
                text: 'TYPE OF EXPENSES'
            }
        },

        scales: {
            x: {
                barThickness: 20,
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

function removeDataChartBarTypeOfExpense(chart) {

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

function addDataDataChartBarTypeOfExpense(chart, label, data) {
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
