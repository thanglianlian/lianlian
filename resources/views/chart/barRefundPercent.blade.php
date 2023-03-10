{{-- <div style="width:100%;text-align: center"><label style="text-align: center">TOP 10 PRODUCTS REVENUE</label></div> --}}
<div class="barChartRefundPercentPanel divChart">

    <canvas id="myChartBarRefundPercent" class=""></canvas>

</div>

<style>

</style>

<script>


//var dataBar = @json($dataBarChart);
//console.log(dataMonth);

//const barRefundPercentLabel = dataBar["label"];
//const barRefundPercentData = dataBar["revenue"];

var dataBarRefundPercent = @json($percentRefundDetail);
const barRefundPercentLabel = dataBarRefundPercent["label"];
const barRefundPercentDataRefundPercent = dataBarRefundPercent["percentRefund"];
const barRefundPercentDataRevenuePercent = dataBarRefundPercent["percentRevenue"];
var barRefundPercentDataRefund = dataBarRefundPercent["refund"];
var barRefundPercentDataRevenue = dataBarRefundPercent["revenue"];

const ctxBarRefundPercent = document.getElementById('myChartBarRefundPercent').getContext('2d');;



var chartBarRefundPercent = new Chart(ctxBarRefundPercent, {
    type: 'bar',
    data :{
        labels: barRefundPercentLabel,
        datasets: [
            {
                label: "Refund",
                backgroundColor: "red",
                data: barRefundPercentDataRefundPercent,
                barPercentage: 0.5,
                barThickness: 30,
                maxBarThickness: 20,
                minBarLength: 2,
            },
            {
                label: "Revenue",
                backgroundColor: "blue",
                data: barRefundPercentDataRevenuePercent,
                barPercentage: 0.5,
                barThickness: 30,
                maxBarThickness: 20,
                minBarLength: 10,
            }

        ]
    },
    options: {
        //barValueSpacing: 10,
        scales: {
            y: {
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, ticks) {
                        return value + "%";
                    },
                    min : 0
                },
                stacked: true
            },
            x: {
                stacked: true
            }
        },
        plugins: {
            datalabels: {
                align: 'end',
                anchor: 'end',
                color: '#fff',
                font: {
                    weight: 'bold',
                    size: 14,
                },
                formatter: function(value, context) {
                    return "";
                },
            },

            tooltip: {
                callbacks: {
                    label: function(context) {



                        let indexItem = context.dataIndex;

                        let label = context.dataset.label || '';
                        //console.log(label);
                        // if (label) {
                        //     label += ': ';
                        // }
                        // if (context.parsed.y !== null) {
                        //     label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(context.parsed.y);
                        // }

                        if(label == "Revenue"){
                            label += " : " + barRefundPercentDataRevenue[indexItem];
                        }

                        if(label == "Refund"){
                            label += " : " + barRefundPercentDataRefund[indexItem] + " = " + barRefundPercentDataRefundPercent[indexItem] + "% of Revenue" ;
                        }
                        return label;
                    }
                }
            },
            title: {
                display: false,
                text: 'PERCENT REFUND ON REVENUE'
            }
        },


    }
  });
  //chartBar.register(ChartDataLabels);

  function addDataBarStacked(chart, dataUpdate) {

        var label = dataUpdate.label;
        var refundPercent = dataUpdate.percentRefund;
        var revenuePercent = dataUpdate.percentRevenue;

        // for(i=0;i<barRefundPercentDataRefund;i++){
        //     barRefundPercentDataRefund.unshift(barRefundPercentDataRefund[i]);
        // }

        // console.log(barRefundPercentDataRefund);

        barRefundPercentDataRefund = dataUpdate.refund;

        //console.log(barRefundPercentDataRefund);

        barRefundPercentDataRevenue = dataUpdate.revenue;

        //console.log(dataUpdate);

        //chart.data.labels.push(label);
        for(j=0;j<label.length;j++){

            chart.data.labels.push(label[j]);
        }
        chart.data.datasets.forEach((dataset) => {
            //console.log(dataset);
            if(dataset.label == "Refund"){
                for(j=0;j<refundPercent.length;j++){
                    dataset.data.push(refundPercent[j]);
                }
                //dataset.data.push(refundPercent);
            }
            if(dataset.label == "Revenue"){
                for(j=0;j<refundPercent.length;j++){
                    dataset.data.push(revenuePercent[j]);
                }
                //dataset.data.push(revenuePercent);
            }

            // for(j=0;j<data.length;j++){
            //     //console.log(data[j]);
            //     dataset.data.push(data[j]);
            // }
        });


        //console.log(chart.data.datasets);
        chart.update();
    }


</script>
