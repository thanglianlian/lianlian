<h1>Filter</h1>
<div class="filterPanel" style="height: 120px">


    <div class="col-md-8" style="margin-left: 100px">
        <label>Year</label>
        <select style="margin-left: 18px">
            {{-- <option value="All">All</option>
            <option value="2021">2021</option> --}}
            <option value="2021">2022</option>
        </select>
    </div>

    {{-- <div>
        <label>View as</label>
        <select name="rangeType" id="rangeType">
            <option value="quarter">Quarter</option>
            <option value="Month">Month</option>
        </select>
    </div>


    <div class="d-inline" style="margin-bottom:5px">
        <label style="display: inline">Quarter</label>
        <div class="cat action quarter" style="display: inline">
            <label>
               <input type="checkbox" value="1"><span>Quarter 1</span>
            </label>
         </div>

         <div class="cat action quarter" style="display: inline">
            <label>
               <input type="checkbox" value="1"><span>Quarter 2</span>
            </label>
         </div>

         <div class="cat action quarter" style="display: inline">
            <label>
               <input type="checkbox" value="1"><span>Quarter 3</span>
            </label>
         </div>

         <div class="cat action quarter" style="display: inline">
            <label>
               <input type="checkbox" value="1"><span>Quarter 4</span>
            </label>
         </div>
    </div> --}}



    <div class="d-inline col-md-8" style="margin-left: 100px">
        <label style="display: inline">Month</label>

        @for ($i = 1; $i < 13; $i++)
            <div class="cat action month" style="display: inline">
                <label>
                <input type="checkbox" name="selMonth" value="{{$i}}"><span>{{$i}}</span>
                </label>
            </div>
        @endfor

    </div>

    <div class="col-md-8" style="margin-left: 250px;margin-top:20px">
        <button id="btnSubmit" >Send</button>
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

                    if(monthArray.length==1){
                        var monthLabel = dataLineChart["dateNumber"];
                        var monthData = dataLineChart["dateData"];
                    }else{
                        var monthLabel = dataLineChart["monthName"];
                        var monthData = dataLineChart["monthData"];
                    }

                    var barLabel = dataLineBar["label"];
                    var barData = dataLineBar["revenue"];

                    var barOtherExpenseLabel = dataOtherExpense["label"];
                    var barOtherExpenseData = dataOtherExpense["value"];

                    var pieRefundLabel = dataRefundPercent["label"];
                    var pieRefundData = dataRefundPercent["value"];

                    var pieExpenseLabel = dataExpensePercent["label"];
                    var pieExpenseData = dataExpensePercent["value"];



                    //console.log(dataLineChart);

                    removeDataChartLine(chartLine);
                    addDataChartLine(chartLine,monthLabel,monthData);

                    removeDataChartBar(chartBar);
                    addDataChartBar(chartBar,barLabel,barData);

                    removeDataChartBarTypeOfExpense(chartBarTypeOfExpense);
                    addDataDataChartBarTypeOfExpense(chartBarTypeOfExpense,barOtherExpenseLabel,barOtherExpenseData);

                    removeDataChartPieRefundPercent(chartPieRefundPercent);
                    addDataChartPieRefundPercent(chartPieRefundPercent,pieRefundLabel,pieRefundData);

                    removeDataChartPieTypeOfExpenseInPercent(chartPieTypeOfExpenseInPercent);
                    addDataChartPieTypeOfExpenseInPercent(chartPieTypeOfExpenseInPercent,pieExpenseLabel,pieExpenseData);
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
