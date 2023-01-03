<div class="col-md-4" style="padding-left:0px">

    <div class="card card-search col-m">
        <div class="card-header">
        <h4 class="card-title">Customer Information : </h4>
        </div>
        <div class="card-body">
        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
        {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

            <div class="form-group">
                <label for="exampleInputEmail1">Name : {{$customer["name"]}}</label>
                <label></label>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1"Address : </label>
                <label></label>
            </div>
        </div>
        </div>

    </div>
</div>


<div style="float: right"><a style="cursor: pointer;font-weight:bold;font-size:20px" href="/admin/customers/{{$id}}/data">Import Data from Excel File</a></div>
<div class="clearfix"></div>
<div class="card card-search">
    <div class="card-header">
    <h4 class="card-title">Search</h4>
    </div>
    <div class="card-body">
    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
    {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

        <div class="form-group">
            <label for="exampleInputEmail1">Year</label>
            <select style="margin-left: 18px" class="form-select">
            {{-- <option value="All">All</option>
            <option value="2021">2021</option> --}}
            <option value="2021">2022</option>
            </select>
        </div>
        <div class="form-group">
            <input type="radio" name="selQuaterView" id="radioQuarter" value="quarter" checked>
            <label style="display: inline">Quarter</label>

            @for ($j = 1; $j < 5; $j++)
                <div class="cat action quarter" style="display: inline">
                    <label>
                    <input type="checkbox" name="selQuarter" value="{{$j}}"><span>{{$j}}</span>
                    </label>
                </div>
            @endfor
        </div>
        <div class="form-group">
            <input type="radio" name="selQuaterView" id="radioMonth" value="quarter">
            <label style="display: inline">Month</label>

            @for ($i = 1; $i < 13; $i++)
                <div class="cat action month" style="display: inline">
                    <label>
                    <input type="checkbox" name="selMonth" value="{{$i}}"><span>{{$i}}</span>
                    </label>
                </div>
            @endfor
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
        </div>

    </div>
    </div>

</div>






<script>
    $(document).ready(function(){
        $("#btnSubmit").click(function(){
            var timeArray = [];
            var type = "month";
            if ($("#radioQuarter").prop("checked")) {
                type = "quarter";
                $("input:checkbox[name=selQuarter]:checked").each(function(){
                    timeArray.push($(this).val());
                });
            }
            if ($("#radioMonth").prop("checked")) {
                type = "month";
                $("input:checkbox[name=selMonth]:checked").each(function(){
                    timeArray.push($(this).val());
                });
            }
            //console.log(timeArray);return;

//console.log(timeArray);return;
            //if()



            $.ajax({
                type: "GET",
                url: "/admin/customers/{{$id}}/ajaxChart",
                data: {timeList: timeArray,year:2022,type: type},
                success: function(dataReturn){

                    var dataChart = JSON.parse(dataReturn);

                    //console.log(dataChart);

                    var dataLineChart = dataChart["line"];
                    var revenueLabel = dataLineChart["timeName"];
                    var revenueData = dataLineChart["timeData"];
                    removeDataChartLine(chartLine);
                    addDataChartLine(chartLine,revenueLabel,revenueData);


                    var dataLineBar = dataChart["bar"];
                    var topTenProductLabel = dataLineBar["label"];
                    var topTenProductData = dataLineBar["revenue"];

                    removeDataChartLine(chartBar);
                    addDataChartLine(chartBar,topTenProductLabel,topTenProductData);

                    var dataRefundPercent = dataChart["pieRefundPercent"];
                    var pieRefundLabel = dataRefundPercent["label"];
                    var pieRefundData = dataRefundPercent["value"];

                    removeDataChartLine(chartPieRefundPercent);
                    addDataChartLine(chartPieRefundPercent,pieRefundLabel,pieRefundData);

                    var percentRefundDetail = dataChart["percentRefundDetail"];
                    removeDataChartLine(chartBarRefundPercent);
                    addDataBarStacked(chartBarRefundPercent,percentRefundDetail);

                    var dataOtherExpense = dataChart["otherExpense"];
                    var barOtherExpenseLabel = dataOtherExpense["label"];
                    var barOtherExpenseData = dataOtherExpense["value"];
                    removeDataChartLine(chartBarTypeOfExpense);
                    addDataChartLine(chartBarTypeOfExpense,barOtherExpenseLabel,barOtherExpenseData);




                    var dataExpensePercent = dataChart["pieExpensePercent"];
                    var pieExpenseLabel = dataExpensePercent["label"];
                    var pieExpenseData = dataExpensePercent["value"];
                    removeDataChartLine(chartPieTypeOfExpenseInPercent);
                    addDataChartLine(chartPieTypeOfExpenseInPercent,pieExpenseLabel,pieExpenseData);

                    var lineTotalExpense = dataChart["lineTotalExpense"];
                    var timeLabelTotalExpense = lineTotalExpense["timeName"];
                    var labelDataTotalExpense = lineTotalExpense["timeData"];
                    removeDataChartLine(chartLineTotalExpense);
                    addDataChartLine(chartLineTotalExpense,timeLabelTotalExpense,labelDataTotalExpense);


                    var lineTotalProfit = dataChart["lineProfit"];
                    var labelProfit = lineTotalProfit["timeName"];
                    var dataProfit = lineTotalProfit["timeData"];
                    removeDataChartLine(chartLineProfit);
                    addDataChartLine(chartLineProfit,labelProfit,dataProfit);



                    // // if(monthArray.length==1){
                    // //     var monthLabel = dataLineChart["dateNumber"];
                    // //     var monthData = dataLineChart["dateData"];
                    // // }else{
                    // //     var monthLabel = dataLineChart["monthName"];
                    // //     var monthData = dataLineChart["monthData"];
                    // // }




                    // var barLabel = dataLineBar["label"];
                    // var barData = dataLineBar["revenue"];











                    // //console.log(dataLineChart);



                    // //removeDataChartBar(chartBar);
                    // removeDataChartLine(chartBar);
                    // addDataChartLine(chartBar,barLabel,barData);










                    // removeDataChartLine(chartBarRefundPercent);
                    // addDataBarStacked(chartBarRefundPercent,percentRefundDetail);

                    //chartBarRefundPercent
                }
            });

            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });


            //console.log(monthArray);
        });
    });
</script>

<style>

.filterPanel{
    margin-bottom:10px;
    border: 3px solid #fff;
    padding: 10px;
}

.quarter{
  margin: 4px;
  background-color: transparent;

  width: 6.0em;
  height: 1.6em;
  /* overflow: hidden;
  float: left; */
}

.quarter span{
  border-radius: 4px;
  border: 3px solid grey;
}

.quarter label {
  line-height: 1.0em;
  width: 6.0em; height: 1.0em;
}


.month{
  margin: 4px;
  background-color: transparent;
  width: 6.0em;
  height: 1.6em;
  /* overflow: hidden;
  float: left; */
}


.month span{
  border-radius: 4px;
  border: 3px solid grey;
}

.month label {
  line-height: 1.0em;
  width: 2.0em; height: 1.0em;
}


.cat label span {
  text-align: center;
  padding: 3px 0;
  display: block;
}

.cat label input {
  position: absolute;
  display: none;
  color: #000;
}

.cat label input + span{color: #000;}
    /* This will declare how a selected input will look giving generic properties */
.quarter input:checked + span {
    color: #ffffff;
    background-color: red;
    text-shadow: 0 0  6px rgba(0, 0, 0, 0.8);
}

.month input:checked + span {
    color: #ffffff;
    background-color: orange;
    text-shadow: 0 0  6px rgba(0, 0, 0, 0.8);
}

</style>
