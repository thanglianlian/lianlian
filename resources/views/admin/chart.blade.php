<link rel="stylesheet" href="{{asset('css/draw.css')}}">
<link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
<script src="{{asset('js/utils.js')}}"></script>
<script src="{{asset('js/chart.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


{{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-piechart-outlabels"></script> --}}
<script>
    Chart.register(ChartDataLabels);
    //Chart.register(ChartLabels);
</script>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            @include('chart.filter')
        </div>
    </div>
    <div style="background-color: transparent ">
        <div class="row">
            <div class="col-md-12">







                <div class="col-md-6">

                    <div class="card card-primary">
                        <div class="card-header">
                        <h4 class="card-title">REVENUE</h4>
                        </div>
                        <div class="card-body">
                        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

                        @include('chart.lineChart', ['dataLineChart' => $dataLineChart])

                        </div>
                        </div>

                    </div>




                    {{-- <div class="col-md-12" style="float: left">

                        @include('chart.lineChart', ['dataLineChart' => $dataLineChart])

                    </div> --}}
                </div>
                <div class="col-md-6">


                    <div class="card card-primary">
                        <div class="card-header">
                        <h4 class="card-title">TOP 10 PRODUCTS REVENUE</h4>
                        </div>
                        <div class="card-body">
                        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

                        @include('chart.barChart', ['dataBarChart' => $dataBarChart])

                        </div>
                        </div>

                    </div>


                    {{-- <div class="col-md-12" style="float:right">

                        @include('chart.barChart', ['dataBarChart' => $dataBarChart])

                    </div> --}}
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-6">

                    <div class="card card-danger">
                        <div class="card-header">
                        <h4 class="card-title">PERCENT REFUND</h4>
                        </div>
                        <div class="card-body">
                        <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

                        @include('chart.pieRefundPercent', ['dataPieChartRefundPercent' => $dataPieChartRefundPercent])

                        </div>
                        </div>

                    </div>




                    {{-- <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.pieRefundPercent', ['dataPieChartRefundPercent' => $dataPieChartRefundPercent])

                        </div>

                    </div> --}}
                </div>



            </div>

            <div class="col-md-12">
                <div class="col-md-12">


                    <div class="card card-danger">
                        <div class="card-header">
                        <h4 class="card-title">PERCENT REFUND ON REVENUE</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" style="float:right;text-align:center">
                                @include('chart.barRefundPercent', ['percentRefundDetail'=>$percentRefundDetail])
                            </div>
                        </div>

                    </div>



                    {{-- <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.barRefundPercent', ['percentRefundDetail'=>$percentRefundDetail])

                        </div>

                    </div> --}}
                </div>
            </div>

            <div class="col-md-12">

                <div class="col-md-6">

                    <div class="card card-info">
                        <div class="card-header">
                        <h4 class="card-title">TYPE OF EXPENSE</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" style="float:right">

                                @include('chart.barChartTypeOfExpense', ['dataBarChartTypeOfExpense' => $dataBarChartTypeOfExpense])

                            </div>
                        </div>

                    </div>

                    {{-- <div class="col-md-12" style="float:right">

                        @include('chart.barChartTypeOfExpense', ['dataBarChartTypeOfExpense' => $dataBarChartTypeOfExpense])

                    </div> --}}
                </div>

                <div class="col-md-6">

                    <div class="card card-info">
                        <div class="card-header">
                        <h4 class="card-title">PERCENT EXPENSES</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" style="float:right">

                                @include('chart.piePercentTypeOfExpense', ['dataPieChartPercentTypeOfExpense' => $dataPieChartPercentTypeOfExpense])

                            </div>
                        </div>

                    </div>


                    {{-- <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.piePercentTypeOfExpense', ['dataPieChartPercentTypeOfExpense' => $dataPieChartPercentTypeOfExpense])

                        </div>

                    </div> --}}
                </div>



            </div>

            <div class="col-md-12">
                <div class="col-md-6">

                    <div class="card card-success">
                        <div class="card-header">
                        <h4 class="card-title">EXPENSES REPORT</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" style="float:right">

                                @include('chart.lineChartExpense', ['dataLineChartTotalExpenseByTime' => $dataLineChartTotalExpenseByTime])

                            </div>
                        </div>

                    </div>


                    {{-- <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.lineChartExpense', ['dataLineChartTotalExpenseByTime' => $dataLineChartTotalExpenseByTime])

                        </div>

                    </div> --}}
                </div>

                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                        <h4 class="card-title">PROFIT</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12" style="float:right">

                                @include('chart.lineChartProfit', ['dataLineChartProfit' => $dataLineChartProfit])

                            </div>
                        </div>

                    </div>




                    {{-- <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.lineChartProfit', ['dataLineChartProfit' => $dataLineChartProfit])

                        </div>

                    </div> --}}
                </div>




            </div>

        </div>
    </div>
</section>






