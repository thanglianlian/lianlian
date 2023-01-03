<link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
<div class="card card-primary">
    <div class="card-header">
    <h4 class="card-title">IMPORT DATA FORM</h4>
    </div>
    <div class="card-body">
    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
    {{-- <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 444px;" width="666" height="375" class="chartjs-render-monitor"></canvas> --}}

            <div class="outer-container">
                <form action="/admin/customers/{{$id}}/importData" method="post"
                    name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div style="display: inline-block">
                        <label>Choose Excel File</label>
                        <input type="file" name="file"
                            id="file" accept=".xls,.xlsx" style="display: inline-block">
                        <button type="submit" id="submit" name="import"
                            class="btn-submit" style="display: inline-block">Import</button>

                    </div>
                    {{ csrf_field() }}
                </form>

            </div>


    </div>
    </div>

</div>




