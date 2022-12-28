<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'order_date',
        'settlement_id',
        'type',
        'order_id',
        'sku',
        'description',
        'quantity',
        'marketplace',
        'account_type',
        'fulfillment',
        'order_city',
        'order_state',
        'order_postal',
        'tax_collection_model',
        'product_sales',
        'product_sales_tax',
        'shipping_credits',
        'shipping_credits_tax',
        'gift_wrap_credits',
        'gift_wrap_credits_tax',
        'regulatory_fee',
        'tax_on_regulatory_fee',
        'promotional_rebates',
        'promotional_rebates_tax',
        'marketplace_withheld_tax',
        'selling_fees',
        'fba_fees',
        'other_transaction_fees',
        'other',
        'other'
    ];

    public function dataLineChartByMonth($id,$monthList=array(),$year=2022){
        $data = array();
        $monthArray = array();
        $monthArray[1] = "Jan";
        $monthArray[2] = "Feb";
        $monthArray[3] = "Mar";
        $monthArray[4] = "Apr";
        $monthArray[5] = "May";
        $monthArray[6] = "Jun";
        $monthArray[7] = "Jul";
        $monthArray[8] = "Aug";
        $monthArray[9] = "Sep";
        $monthArray[10] = "Oct";
        $monthArray[11] = "Nov";
        $monthArray[12] = "Dec";
        if(isset($monthList) && sizeof($monthList) > 0){
            $monthSelected = array();
            foreach($monthList as $key=>$val){
                $data['monthName'][] =  $monthArray[$val];
                $data['monthData'][] =  Expense::whereMonth('order_date',"=", $val)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
            }
            return $data;
        }
        $data['monthName'] = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
        for($i=1;$i<13;$i++){
            $data['monthData'][] =  Expense::whereMonth('order_date',"=", $i)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
        }
        return $data;
    }



    public function dataLineChartByDate($id,$month=1,$year=2022){
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, 2022); // 31
        $data = array();
        for($i=1;$i<$numberDay+1;$i++){
            $data['dateNumber'][] = $i;
            $data['dateData'][] =  Expense::whereDay('order_date',"=", $i)->whereMonth('order_date',"=", $month)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
        }

        return $data;

    }

    public function dataTopTenByMonth($id,$monthList=array(),$year=2022){
        //$data = array();
        $defaultMonthList = array();

        if(count($monthList) > 0){
            $defaultMonthList = $monthList;
        }else{
            for($i=1;$i<13;$i++){
                $defaultMonthList[]=$i;
            }
        }

        $defaultMonthListString = "(".implode(",",$defaultMonthList).")";

        //$dataTopProduct = Expense::whereIn(DB::raw('month(order_date)'),$defaultMonthList)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->groupBy("sku")->sum('product_sales');


        $dataTopProduct = DB::table('expenses')
                    ->selectRaw('sku, sum(product_sales) as revenue')
                    ->whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                    ->whereYear('order_date',"=", $year)
                    ->where("type","Order")
                    ->groupBy("sku")
                    ->orderBy("revenue","desc")
                    ->limit(10)
                    ->get();


        $data = array();
        foreach($dataTopProduct as $key=>$value){
            $data["label"][] = $value->sku;
            $data["revenue"][] = $value->revenue;
        }




        return $data;
    }

    public function getDataTypeOfExpenseByMonth($id,$monthList=array(),$year=2022){
        //$data = array();
        $defaultMonthList = array();

        if(count($monthList) > 0){
            $defaultMonthList = $monthList;
        }else{
            for($i=1;$i<13;$i++){
                $defaultMonthList[]=$i;
            }
        }

        $defaultMonthListString = "(".implode(",",$defaultMonthList).")";

        //$dataTopProduct = Expense::whereIn(DB::raw('month(order_date)'),$defaultMonthList)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->groupBy("sku")->sum('product_sales');


        $promotionalRebates = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('promotional_rebates');

        $sellingFees = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('selling_fees');

        $fbaTransactionFees = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('fba_fees');

        $fbaInventoryFees = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","FBA Inventory Fee")
                                ->where("customer_id",$id)
                                ->sum('other');

        $costOfAdvertising = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        // calculate for other expenses
        $shippingLabel = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Shipping Services")
                                ->where("customer_id",$id)
                                ->sum('other');

        $serviceFeesOne = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->sum('other');

        $serviceFeesTwo = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Deal Fee")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        $serviceFeesThree = Expense::
                                whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        $serviceFees = $serviceFeesOne + $serviceFeesTwo + $serviceFeesThree;

        $adjustments = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description","FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->sum('other');

        $liquidationFees = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Liquidations")
                        ->where("customer_id",$id)
                        ->sum('other_transaction_fees');

        $fbaInventoryCredit = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description",'!=',"FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->sum('other');

        $fbsLiquidationProceeds = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("customer_id",$id)
                        ->sum('product_sales');

        $shippingCredits = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->sum('shipping_credits');

        $giftWrapCredits = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->sum('gift_wrap_credits');

        $shippingCreditRefunds = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->sum('shipping_credits');

        $giftWrapCreditRefunds = Expense::
                        whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->sum('gift_wrap_credits');

        $otherExpense = $shippingLabel + $serviceFees + $adjustments +
                        $liquidationFees + $fbaInventoryCredit + $fbsLiquidationProceeds
                        + $shippingCredits + $giftWrapCredits + $shippingCreditRefunds +
                        $giftWrapCreditRefunds;

        //echo $otherExpense;die;

        // end other expenses


        $data = array();

        $data["label"][] = "Promotional rebates";
        $data["value"][] = abs($promotionalRebates);
        $data["label"][] = "Selling fees";
        $data["value"][] = abs($sellingFees);
        $data["label"][] = "FBA transaction fees";
        $data["value"][] = abs($fbaTransactionFees);
        $data["label"][] = "FBA inventory fees";
        $data["value"][] = abs($fbaInventoryFees);
        $data["label"][] = "Cost of advertising";
        $data["value"][] = abs($costOfAdvertising);
        $data["label"][] = "Other expenses";
        $data["value"][] = abs($otherExpense);

        $data["total"] = abs($promotionalRebates) + abs($sellingFees) +
                         abs($fbaTransactionFees) + abs($fbaInventoryFees) +
                         abs($costOfAdvertising) + abs($otherExpense);

        return $data;
    }

    function convertExpenseDataToPercent($dataExpense){

        $data = array();

        if($dataExpense){
            for($i=0;$i<6;$i++){
                $data["label"][] = $dataExpense["label"][$i] . " : ".$dataExpense["value"][$i];
                $data["value"][] = number_format($dataExpense["value"][$i] / $dataExpense["total"] * 100 ,2);
            }
        }

        return $data;
    }

    public function getRefundPercent($id,$monthList=array(),$year=2022){
        //$data = array();
        $defaultMonthList = array();

        if(count($monthList) > 0){
            $defaultMonthList = $monthList;
        }else{
            for($i=1;$i<13;$i++){
                $defaultMonthList[]=$i;
            }
        }

        $totalRevenue = Expense::
                            whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                            ->whereYear('order_date',"=", $year)
                            ->where("type","Order")
                            ->where("customer_id",$id)
                            ->sum('product_sales');

        $totalRefund = Expense::
                            whereIn(DB::raw('month(order_date)'),$defaultMonthList)
                            ->whereYear('order_date',"=", $year)
                            ->where("type","Refund")
                            ->where("customer_id",$id)
                            ->sum('product_sales');
        //$totalRefund = abs($totalRefund);
        $data = array();

        $data["label"][] = "Revenue : " . $totalRevenue;
        //$data["value"][] = abs($totalRevenue);
        $data["value"][] = 100;
        $data["label"][] = "Refunds : " . abs($totalRefund);
        $data["value"][] = number_format((abs($totalRefund) / abs($totalRevenue)) * 100 ,2);
        //$data["value"][] = abs($totalRefund);



        return $data;

        //return $data;
    }
}