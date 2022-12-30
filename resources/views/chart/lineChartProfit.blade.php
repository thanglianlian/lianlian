    {{-- <div style="width:400px;text-align: center"><label style="text-align: center">Revenue Report</label></div> --}}
    <div class="lineCharProfitPanel divChart" style="padding-top: 20%">
        <canvas id="myChartLineProfit" class=""></canvas>

    </div>

<style>

</style>

<script>


    var dataProfit = @json($dataLineChartProfit);
    //console.log(dataLineChartTotalExpenseByTime);

    const monthProfitLabel = dataProfit["monthName"];
    const monthProfitData = dataProfit["monthData"];

    //console.log(monthTotalExpenseLabel.sort());

    const ctxLineProfit = document.getElementById('myChartLineProfit').getContext('2d');;

    var chartLineProfit = new Chart(ctxLineProfit, {
        type: 'line',
        data: {
          labels: monthProfitLabel,
          datasets: [{
            label: 'Profit',
            //data: [12, 19, 3, 5, 2, 3],
            data: monthProfitData,
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
                    display: true,
                    text: 'PROFIT'
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

        //   scales: {
        //     y: {
        //       beginAtZero: true
        //     }
        //   }
        }
      });



    </script>
