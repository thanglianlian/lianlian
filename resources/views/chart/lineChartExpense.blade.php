    {{-- <div style="width:400px;text-align: center"><label style="text-align: center">Revenue Report</label></div> --}}
    <div class="lineCharTotalExpensePanel divChart" style="padding-top: 20%">
        <canvas id="myChartLineTotalExpense" class=""></canvas>

    </div>

<style>

</style>

<script>


    var dataLineChartTotalExpenseByTime = @json($dataLineChartTotalExpenseByTime);
    //console.log(dataLineChartTotalExpenseByTime);

    const monthTotalExpenseLabel = dataLineChartTotalExpenseByTime["timeName"];
    const monthTotalExpenseData = dataLineChartTotalExpenseByTime["timeData"];

    //console.log(monthTotalExpenseLabel.sort());

    const ctxLineTotalExpense = document.getElementById('myChartLineTotalExpense').getContext('2d');;

    var chartLineTotalExpense = new Chart(ctxLineTotalExpense, {
        type: 'line',
        data: {
          labels: monthTotalExpenseLabel,
          datasets: [{
            label: 'Expenses',
            //data: [12, 19, 3, 5, 2, 3],
            data: monthTotalExpenseData,
            borderWidth: 1
          }]
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                    labels: {
                        // This more specific font property overrides the global property
                        font: {
                            size: 5
                        }
                    }
                },
                datalabels: {
                    display: false

                },
                title: {
                    display: false,
                    text: 'EXPENSES REPORT'
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
                    },
                    offset: true
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

        //   scales: {
        //     y: {
        //       beginAtZero: true
        //     }
        //   }
        }
      });

    function removeDataChartLineTotalExpense(chart) {

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

    function addDataChartLineTotalExpense(chart, label, data) {
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
