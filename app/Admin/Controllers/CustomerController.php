<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customer());

        $grid->column('Chart Data')->display(function () {

            return '<a href="/admin/customers/'.$this->id.'/data">Import data</a> || ' . '<a href="/admin/customers/'.$this->id.'/chart">View chart</a>';
        });

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));



        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Customer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Customer());

        $form->text('name', __('Name'));

        return $form;
    }


    public function data($id,Content $content)
    {
       return $content
        ->title($this->title())
        ->description('Data')
        ->body(view('admin.data', ['id' => $id]));

       // return ;
    }

    public function importData($id,Content $content)
    {
        set_time_limit(0);
        if (isset($_POST["import"])) {
            $allowedFileType = [
                'application/vnd.ms-excel',
                'text/xls',
                'text/xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];

            if (in_array($_FILES["file"]["type"], $allowedFileType)) {
                $targetPath = 'uploads/' . time().$_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadSheet = $Reader->load($targetPath);
                $sheetCount = $spreadSheet->getSheetCount();



                // delete data in time range in Excel file
                $expenseModel = new Expense();
                $startSheet = $spreadSheet->getSheet(0);
                $startSheetArray = $startSheet->toArray();
                $startSheetTotalRow = count($startSheetArray);
                $startRowSheet = 0;
                for($countRow=0;$countRow<11;$countRow++){
                        if($startSheetArray[$countRow][18]=="gift wrap credits"){
                            $startRowSheet = $countRow+1;
                        }
                }

                $startCheckDateString = $startSheetArray[$startRowSheet][0];
                $startCheckDate = Carbon::parse($startCheckDateString)->format("Y-m-d H:i:s");


                $endSheet = $spreadSheet->getSheet($sheetCount-1);
                $endSheetArray = $endSheet->toArray();
                $endSheetTotalRow = count($endSheetArray);
                $endCheckDateString = $endSheetArray[$endSheetTotalRow - 1][0];
                $endCheckDate = Carbon::parse($endCheckDateString)->format("Y-m-d H:i:s");


                $expenseModel::where('customer_id', '=', $id)
                            ->where('order_date', '>=', $startCheckDate)
                            ->where('order_date', '<=', $endCheckDate)->delete();

                // End delete data in time range in Excel file

                //echo $endCheckDate;die;


                for($i=0;$i<$sheetCount;$i++){
                    $excelSheet = $spreadSheet->getSheet($i);




                    $spreadSheetAry = $excelSheet->toArray();
                    $totalRow = count($spreadSheetAry);


                    $startRow = 0;
                    for($j=0;$j<11;$j++){
                        if($spreadSheetAry[$j][18]=="gift wrap credits"){
                            $startRow = $j+1;
                        }
                    }

                    for($k=$startRow;$k<$totalRow;$k++){
                        $orderDateString = $spreadSheetAry[$k][0];
                        $orderDate = Carbon::parse($orderDateString)->format("Y-m-d H:i:s");







                        // $checkData = Expense::where("customer_id","=",$id)
                        //                     ->where("order_date","=",$orderDate)
                        //                     ->where("settlement_id","=",$spreadSheetAry[$k][1])
                        //                     ->where("type","=",$spreadSheetAry[$k][2])
                        //                     ->where("order_id","=",$spreadSheetAry[$k][3])
                        //                     ->first();
                        // echo "<pre>";
                        // print_r($checkData->toArray());die;


                        $expenseModel = new Expense();

                        $expenseModel->customer_id = $id;
                        $expenseModel->order_date = $orderDate;
                        $expenseModel->settlement_id = $spreadSheetAry[$k][1];
                        $expenseModel->type = $spreadSheetAry[$k][2];
                        $expenseModel->order_id = $spreadSheetAry[$k][3];
                        $expenseModel->sku = $spreadSheetAry[$k][4];
                        $expenseModel->description = $spreadSheetAry[$k][5];
                        $expenseModel->quantity = $spreadSheetAry[$k][6];
                        $expenseModel->marketplace = $spreadSheetAry[$k][7];
                        $expenseModel->account_type = $spreadSheetAry[$k][8];
                        $expenseModel->fulfillment = $spreadSheetAry[$k][9];
                        $expenseModel->order_city = $spreadSheetAry[$k][10];
                        $expenseModel->order_state = $spreadSheetAry[$k][11];
                        $expenseModel->order_postal = $spreadSheetAry[$k][12];
                        $expenseModel->tax_collection_model = $spreadSheetAry[$k][13];
                        $expenseModel->product_sales = $spreadSheetAry[$k][14];
                        $expenseModel->product_sales_tax = $spreadSheetAry[$k][15];
                        $expenseModel->shipping_credits = $spreadSheetAry[$k][16];
                        $expenseModel->shipping_credits_tax = $spreadSheetAry[$k][17];
                        $expenseModel->gift_wrap_credits = $spreadSheetAry[$k][18];
                        $expenseModel->gift_wrap_credits_tax = $spreadSheetAry[$k][19];
                        $expenseModel->regulatory_fee = $spreadSheetAry[$k][20];
                        $expenseModel->tax_on_regulatory_fee = $spreadSheetAry[$k][21];
                        $expenseModel->promotional_rebates = $spreadSheetAry[$k][22];
                        $expenseModel->promotional_rebates_tax = $spreadSheetAry[$k][23];
                        $expenseModel->marketplace_withheld_tax = $spreadSheetAry[$k][24];
                        $expenseModel->selling_fees = $spreadSheetAry[$k][25];
                        $expenseModel->fba_fees = $spreadSheetAry[$k][26];
                        $expenseModel->other_transaction_fees = $spreadSheetAry[$k][27];
                        $expenseModel->other = $spreadSheetAry[$k][28];
                        $expenseModel->total = $spreadSheetAry[$k][29];
                        //$expenseModel->sheet_in_import_file = $i;
                        //$expenseModel->row_in_import_file = $k;
                        // try{
                        //     $expenseModel->save();
                        // }
                        // catch(\Illuminate\Database\QueryException $exception){

                        // }

                        $expenseModel->save();

                    }



                }
                return redirect()->route('admin.customers.index')->withSuccessMessage('Import Data successfully');

                //$excelSheet = $spreadSheet->getActiveSheet();

            }
        }
    }


    public function dataChart($id,Content $content)
    {
       // DB::enableQueryLog();


       $customerObject = new Customer();
       $customer = $customerObject->find($id)->toArray();

       $expense = new Expense();

        //month data
        // $dataLineChartByMonth = $expense->dataLineChartByMonth($id);
        // $dataTopProduct = $expense->dataTopTenByMonth($id);
        // $dataTypeOfExpense = $expense->getDataTypeOfExpenseByMonth($id);
        // $percentRefund = $expense->getRefundPercent($id);
        // $percentRefundDetail = $expense->getRefundPercentDetail($id);
        // $dataTypeOfExpenseInPercent = $expense->convertExpenseDataToPercent($dataTypeOfExpense);
        // $dataTotalExpenseByTime = $expense->getTotalExpenseByTime($id);
        // $dataProfit = $expense->getProfit($id, $dataLineChartByMonth, $dataTotalExpenseByTime);


        // return $content
        // ->title($this->title())
        // ->description('Data Chart')
        // ->body(view('admin.chart', [
        //     'id' => $id,
        //     'dataLineChart' => $dataLineChartByMonth,
        //     'dataBarChart' => $dataTopProduct,
        //     'dataBarChartTypeOfExpense' => $dataTypeOfExpense,
        //     'dataPieChartRefundPercent' => $percentRefund,
        //     'percentRefundDetail' => $percentRefundDetail,
        //     'dataPieChartPercentTypeOfExpense' => $dataTypeOfExpenseInPercent,
        //     'dataLineChartTotalExpenseByTime' => $dataTotalExpenseByTime,
        //     'dataLineChartProfit' => $dataProfit
        // ]));
        // end month data


        $dataLineChartByQuarter = $expense->dataRevenue($id,'quarter');
        $dataTopProduct = $expense->dataTopTenRevenueProduct($id,'quarter');
        $percentRefund = $expense->getRefundPercent($id,'quarter');

        $percentRefundDetail = $expense->getRefundPercentDetail($id,'quarter');

        $dataTypeOfExpense = $expense->getDataTypeOfExpense($id,'quarter');

        $dataTypeOfExpenseInPercent = $expense->convertExpenseDataToPercent($dataTypeOfExpense);


        $dataTotalExpenseByTime = $expense->getTotalExpenseByTime($id,'quarter');


        $dataProfit = $expense->getProfit($id, $dataLineChartByQuarter, $dataTotalExpenseByTime);



        return $content
        ->title($this->title())
        ->description('Data Chart')
        ->body(view('admin.chart', [
            'id' => $id,
            'customer' => $customer,
            'dataLineChart' => $dataLineChartByQuarter,
            'dataBarChart' => $dataTopProduct,
            'dataPieChartRefundPercent' => $percentRefund,
            'percentRefundDetail' => $percentRefundDetail,
            'dataBarChartTypeOfExpense' => $dataTypeOfExpense,
            'dataPieChartPercentTypeOfExpense' => $dataTypeOfExpenseInPercent,
            'dataLineChartTotalExpenseByTime' => $dataTotalExpenseByTime,
            'dataLineChartProfit' => $dataProfit
        ]));

    }

    public function ajaxChart($id,Request $request)
    {
        $timeList = $request->get('timeList');
        $type = $request->get('type');
        $year = 2022;



        $expense = new Expense();
        // if(count($monthList) == 1){
        //     $dataLineChart = $expense->dataLineChartByDate($id,$monthList[0],$year);
        // }else{

        //     $dataLineChart = $expense->dataLineChartByMonth($id,$monthList,$year);
        // }

        //$dataLineChart = $expense->dataLineChartByMonth($id,$monthList,$year);


        $dataLineChart = $expense->dataRevenue($id,$type,$timeList,$year);

        $dataTopProduct = $expense->dataTopTenRevenueProduct($id,$type,$timeList,$year);

        $percentRefund = $expense->getRefundPercent($id,$type,$timeList,$year);

        $percentRefundDetail = $expense->getRefundPercentDetail($id,$type,$timeList,$year);

        $dataTypeOfExpense = $expense->getDataTypeOfExpense($id,$type,$timeList,$year);


        $dataTypeOfExpenseInPercent = $expense->convertExpenseDataToPercent($dataTypeOfExpense);

        $dataTotalExpenseByTime = $expense->getTotalExpenseByTime($id,$type,$timeList,$year);

        $dataProfit = $expense->getProfit($id, $dataLineChart, $dataTotalExpenseByTime);



        $data = array();
        $data["line"] = $dataLineChart;
        $data["bar"] = $dataTopProduct;
        $data["pieRefundPercent"] = $percentRefund;
        $data["percentRefundDetail"] = $percentRefundDetail;
        $data["otherExpense"] = $dataTypeOfExpense;

        $data["pieExpensePercent"] = $dataTypeOfExpenseInPercent;
        $data["lineTotalExpense"] = $dataTotalExpenseByTime;
        $data["lineProfit"] = $dataProfit;



        return json_encode($data);
    }

}
