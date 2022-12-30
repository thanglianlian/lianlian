{{-- <div style="width:580px;text-align: center"><label style="text-align: center">PERCENT REFUND</label></div> --}}
<div class="barChartPieRefundPercentPanel divChart">
    <canvas id="myChartPieRefundPercent" class="canvasChart" style="min-height: 80px !important"></canvas>

</div>

<style>

</style>

<script>


var dataPieRefundPercent = @json($dataPieChartRefundPercent);


const pieRefundPercentLabel = dataPieRefundPercent["label"];
const pieRefundPercentData = dataPieRefundPercent["value"];
const pieRefundPercentColor = ["Blue", "Red"];

const ctxPieRefundPercent = document.getElementById('myChartPieRefundPercent').getContext('2d');

var chartPieRefundPercent = new Chart(ctxPieRefundPercent, {
    type: 'pie',
    data: {
        labels: pieRefundPercentLabel,

        datasets: [{
            label: '%', //this one,
            data: pieRefundPercentData,
            backgroundColor: pieRefundPercentColor,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                    display: false,
                    text: 'PERCENT REFUND'
            },
            legend: {
                position: "right",
                align: "middle"
            }
        },
    }

  });
  //chartBar.register(ChartDataLabels);

function removeDataChartPieRefundPercent(chart) {

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

function addDataChartPieRefundPercent(chart, label, data) {
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
