    {{-- <div style="width:400px;text-align: center"><label style="text-align: center">Revenue Report</label></div> --}}
    <div class="lineChartPanel divChart" style="padding-top: 10%">
        <canvas id="myChartLine" class=""></canvas>

    </div>

<style>

</style>

<script>


    var dataLine = @json($dataLineChart);
    //console.log(dataMonth);

    const monthLabel = dataLine["monthName"];
    const monthData = dataLine["monthData"];

    const ctxLine = document.getElementById('myChartLine').getContext('2d');;

    var chartLine = new Chart(ctxLine, {
        type: 'line',
        data: {
          labels: monthLabel,
          datasets: [{
            label: 'Revenue',
            //data: [12, 19, 3, 5, 2, 3],
            data: monthData,
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
                    text: 'REVENUE REPORT'
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

    function removeDataChartLine(chart) {

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

    function addDataChartLine(chart, label, data) {
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
