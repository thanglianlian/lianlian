<link rel="stylesheet" href="{{asset('css/draw.css')}}">
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
    <div class="box grid-box">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12" style="float: left">

                        @include('chart.lineChart', ['dataLineChart' => $dataLineChart])

                    </div>
                </div>
                <div class="col-md-6">

                    <div class="col-md-12" style="float:right">

                        @include('chart.barChart', ['dataBarChart' => $dataBarChart])

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.pieRefundPercent', ['dataPieChartRefundPercent' => $dataPieChartRefundPercent])

                        </div>

                    </div>
                </div>

                <div class="col-md-6">

                    <div class="col-md-12" style="float:right">

                        @include('chart.barChartTypeOfExpense', ['dataBarChartTypeOfExpense' => $dataBarChartTypeOfExpense])

                    </div>
                </div>

            </div>

            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="col-md-12" style="float: left">

                        <div class="col-md-12" style="float:right">

                            @include('chart.piePercentTypeOfExpense', ['dataPieChartPercentTypeOfExpense' => $dataPieChartPercentTypeOfExpense])

                        </div>

                    </div>
                </div>

                <div class="col-md-6">

                                   </div>

            </div>

        </div>
    </div>
</section>






