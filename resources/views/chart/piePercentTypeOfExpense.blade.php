{{-- <div style="width:580px;text-align: center"><label style="text-align: center">PERCENT EXPENSES</label></div> --}}
<div class="barChartPiePercentTypeOfExpensePanel divChart">
    <canvas id="myChartPiePercentTypeOfExpense" class="canvasChart" ></canvas>

</div>

<style>

</style>

<script>


var dataPieTypeOfExpenseInPercent = @json($dataPieChartPercentTypeOfExpense);


const pieTypeOfExpenseInPercentLabel = dataPieTypeOfExpenseInPercent["label"];
const pieTypeOfExpenseInPercentData = dataPieTypeOfExpenseInPercent["value"];
const pieTypeOfExpenseInPercentColor = ["Blue","Red","Green","Yellow","Grey","Orange"];

const ctxPiePercentTypeOfExpense = document.getElementById('myChartPiePercentTypeOfExpense').getContext('2d');


var chartPieTypeOfExpenseInPercent = new Chart(ctxPiePercentTypeOfExpense, {
    type: 'pie',
    data: {
        //display:'outside',
        labels: pieTypeOfExpenseInPercentLabel,
        datasets: [{
            label: '%', //this one,
            data: pieTypeOfExpenseInPercentData,
            //fontcolor : ["white","white","white","white","white","white"],
            backgroundColor: pieTypeOfExpenseInPercentColor,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            datalabels: {
                align: 'end',
                anchor: 'end',
                //color: ["white","white","white","black","white","white"],
                //size: 14,
                font: function(context) {
                    var w = context.chart.width;
                    return {
                        size: w < 512 ? 10 : 12,
                        weight: 'bold',
                    };
                },
                formatter: function(value, context) {
                    return value + '%';
                }

            },
            title: {
                    display: true,
                    text: '',
                    padding: {
                        top: 10,
                        bottom: 30
                    }
            },

            legend: {
                position: "bottom",
                labels: {
                    // This more specific font property overrides the global property
                    font: {
                        size: 10
                    },
                    //padding: 10

                },
                title: {
                    display: true,
                    padding: 5,
                }
                //align: "middle"

            },
            // labels: {
            //     render: 'label',
            //     fontColor: '#000',
            //     position: 'outside'
            // }

        },

        // layout: {
        //     padding: {
        //         left: 40,
        //         right : 40,
        //         bottom : 10
        //     }
        // },


    }
  }

  );
  //Chart.defaults.global.fontColor = '#fff';

function removeDataChartPieTypeOfExpenseInPercent(chart) {

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

function addDataChartPieTypeOfExpenseInPercent(chart, label, data) {
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
