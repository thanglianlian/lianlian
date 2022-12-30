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
            var monthArray = [];
            $("input:checkbox[name=selMonth]:checked").each(function(){
                monthArray.push($(this).val());
            });


            $.ajax({
                type: "GET",
                url: "/admin/customers/{{$id}}/ajaxChart",
                data: {monthList: monthArray,year:2022},
                success: function(dataReturn){

                    var dataChart = JSON.parse(dataReturn);

                    //console.log(dataChart);

                    var dataLineChart = dataChart["line"];
                    var dataLineBar = dataChart["bar"];
                    var dataOtherExpense = dataChart["otherExpense"];

                    var dataRefundPercent = dataChart["pieRefundPercent"];
                    var dataExpensePercent = dataChart["pieExpensePercent"];
                    var lineTotalExpense = dataChart["lineTotalExpense"];
                    var lineTotalProfit = dataChart["lineProfit"];

                    var percentRefundDetail = dataChart["percentRefundDetail"];



                    // if(monthArray.length==1){
                    //     var monthLabel = dataLineChart["dateNumber"];
                    //     var monthData = dataLineChart["dateData"];
                    // }else{
                    //     var monthLabel = dataLineChart["monthName"];
                    //     var monthData = dataLineChart["monthData"];
                    // }

                    var monthLabel = dataLineChart["monthName"];
                    var monthData = dataLineChart["monthData"];


                    var barLabel = dataLineBar["label"];
                    var barData = dataLineBar["revenue"];

                    var barOtherExpenseLabel = dataOtherExpense["label"];
                    var barOtherExpenseData = dataOtherExpense["value"];

                    var pieRefundLabel = dataRefundPercent["label"];
                    var pieRefundData = dataRefundPercent["value"];

                    var pieExpenseLabel = dataExpensePercent["label"];
                    var pieExpenseData = dataExpensePercent["value"];

                    var monthLabelTotalExpense = lineTotalExpense["monthName"];
                    var monthDataTotalExpense = lineTotalExpense["monthData"];

                    var monthLabelProfit = lineTotalProfit["monthName"];
                    var monthDataProfit = lineTotalProfit["monthData"];

                    //console.log(dataLineChart);

                    removeDataChartLine(chartLine);
                    addDataChartLine(chartLine,monthLabel,monthData);

                    //removeDataChartBar(chartBar);
                    removeDataChartLine(chartBar);
                    addDataChartLine(chartBar,barLabel,barData);

                    removeDataChartLine(chartBarTypeOfExpense);
                    addDataChartLine(chartBarTypeOfExpense,barOtherExpenseLabel,barOtherExpenseData);

                    removeDataChartLine(chartPieRefundPercent);
                    addDataChartLine(chartPieRefundPercent,pieRefundLabel,pieRefundData);

                    removeDataChartLine(chartPieTypeOfExpenseInPercent);
                    addDataChartLine(chartPieTypeOfExpenseInPercent,pieExpenseLabel,pieExpenseData);

                    removeDataChartLine(chartLineTotalExpense);
                    addDataChartLine(chartLineTotalExpense,monthLabelTotalExpense,monthDataTotalExpense);

                    removeDataChartLine(chartLineProfit);
                    addDataChartLine(chartLineProfit,monthLabelProfit,monthDataProfit);

                    removeDataChartLine(chartBarRefundPercent);
                    addDataBarStacked(chartBarRefundPercent,percentRefundDetail);

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
