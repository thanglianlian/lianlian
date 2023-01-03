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

    public function dataRevenue($id,$type="month",$timeList=array(),$year=2022){
        $data = array();

        if($type == "month"){
            $timeArray = array();
            $timeArray[1] = "Jan";
            $timeArray[2] = "Feb";
            $timeArray[3] = "Mar";
            $timeArray[4] = "Apr";
            $timeArray[5] = "May";
            $timeArray[6] = "Jun";
            $timeArray[7] = "Jul";
            $timeArray[8] = "Aug";
            $timeArray[9] = "Sep";
            $timeArray[10] = "Oct";
            $timeArray[11] = "Nov";
            $timeArray[12] = "Dec";

            if(isset($timeList) && sizeof($timeList) > 0){
                $timeSelected = array();
                foreach($timeList as $key=>$val){
                    $data['timeName'][] =  $timeArray[$val];
                    $data['timeData'][] =  Expense::whereMonth('order_date',"=", $val)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
                }
                return $data;
            }
            $data['timeName'] = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
            for($i=1;$i<13;$i++){
                $data['timeData'][] =  Expense::whereMonth('order_date',"=", $i)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
            }
            return $data;
        }

        if($type == "quarter"){
            $timeArray = array();
            $timeArray[1] = "Quarter 1";
            $timeArray[2] = "Quarter 2";
            $timeArray[3] = "Quarter 3";
            $timeArray[4] = "Quarter 4";
            $data = array();
            //$data['timeName'] = array("Quarter 1","Quarter 2","Quarter 3","Quarter 4");

            if(isset($timeList) && sizeof($timeList) > 0){
                $timeSelected = array();


                foreach($timeList as $key=>$val){
                    $data['timeName'][] =  $timeArray[$val];
                    //$data['monthData'][] =  Expense::whereMonth('order_date',"=", $val)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');


                    $quarterData = Expense::select(DB::raw('quarter(order_date) timeData'),DB::raw('sum(product_sales) as totalValue'))
                                ->where(DB::raw('quarter(order_date)'),$val)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Order")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw('quarter(order_date)'))
                                ->get();
                    $quarterDataArray = $quarterData->toArray();

                    $quarterNumber = 0;
                    if($quarterDataArray){
                        $quarterNumber = $quarterDataArray[0]["totalValue"];
                    }

                    $data['timeData'][] = $quarterNumber;
                }

                return $data;
            }
            $data['timeName'] = array("Quarter 1","Quarter 2","Quarter 3","Quarter 4");
            for($i=1;$i<5;$i++){
                //$data['monthData'][] =  Expense::whereMonth('order_date',"=", $i)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
                $quarterData = Expense::select(DB::raw('quarter(order_date) timeData'),DB::raw('sum(product_sales) as totalValue'))
                    ->where(DB::raw('quarter(order_date)'),$i)
                    ->whereYear('order_date',"=", $year)
                    ->where("type","Order")
                    ->where("customer_id",$id)
                    ->groupBy(DB::raw('quarter(order_date)'))
                    ->get();
                $quarterDataArray = $quarterData->toArray();

                $quarterNumber = 0;
                if($quarterDataArray){
                    $quarterNumber = $quarterDataArray[0]["totalValue"];
                }

                $data['timeData'][] = $quarterNumber;
            }


            return $data;


        }




    }

    // public function dataLineChartByQuarter($id,$quarterList=array(),$year=2022){
    //     $data = array();
    //     $quarterArray = array();
    //     $quarterArray[1] = "Quarter 1";
    //     $quarterArray[2] = "Quarter 2";
    //     $quarterArray[3] = "Quarter 3";
    //     $quarterArray[4] = "Quarter 4";

    //     // if(isset($quarterList) && sizeof($quarterList) > 0){
    //     //     $quarterSelected = array();
    //     //     foreach($quarterList as $key=>$val){
    //     //         $data['monthName'][] =  $monthArray[$val];
    //     //         $data['monthData'][] =  Expense::whereMonth('order_date',"=", $val)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
    //     //     }
    //     //     return $data;
    //     // }


    //     return $data;
    // }



    public function dataLineChartByDate($id,$month=1,$year=2022){
        $numberDay = cal_days_in_month(CAL_GREGORIAN, $month, 2022); // 31
        $data = array();
        for($i=1;$i<$numberDay+1;$i++){
            $data['dateNumber'][] = $i;
            $data['dateData'][] =  Expense::whereDay('order_date',"=", $i)->whereMonth('order_date',"=", $month)->whereYear('order_date',"=", $year)->where("type","Order")->where("customer_id",$id)->sum('product_sales');
        }

        return $data;

    }

    public function dataTopTenRevenueProduct($id,$type="month",$timeList=array(),$year=2022){
        //$data = array();
        $defaultTimeList = array();


        $timeGroup = "month(order_date)";

        if($type=="month"){
            for($i=1;$i<13;$i++){
                $defaultTimeList[]=$i;
            }

            if($timeList){
                $defaultTimeList = $timeList;
            }
            $timeGroup = "month(order_date)";
        }


        if($type=="quarter"){
            for($i=1;$i<5;$i++){
                $defaultTimeList[]=$i;
            }
            // echo "<pre>";
            // print_r($timeList);die;
            // echo count($timeList);die;
            if($timeList){
                $defaultTimeList = $timeList;
            }
            $timeGroup = "quarter(order_date)";
        }



        $dataTopProduct = DB::table('expenses')
                    ->selectRaw('sku, sum(product_sales) as revenue')
                    ->whereIn(DB::raw($timeGroup),$defaultTimeList)
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


    public function getDataTypeOfExpense($id,$type="month",$timeList=array(),$year=2022){
        //$data = array();
        $defaultTimeList = array();

        $timeGroup = "month(order_date)";

        if($type=="month"){
            for($i=1;$i<13;$i++){
                $defaultTimeList[]=$i;
            }

            if($timeList){
                $defaultTimeList = $timeList;
            }
            $timeGroup = "month(order_date)";
        }


        if($type=="quarter"){
            for($i=1;$i<5;$i++){
                $defaultTimeList[]=$i;
            }
            if($timeList){
                $defaultTimeList = $timeList;
            }
            $timeGroup = "quarter(order_date)";
        }




        $promotionalRebates = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('promotional_rebates');

        $sellingFees = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('selling_fees');

        $fbaTransactionFees = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->sum('fba_fees');

        $fbaInventoryFees = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","FBA Inventory Fee")
                                ->where("customer_id",$id)
                                ->sum('other');

        $costOfAdvertising = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        // calculate for other expenses
        $shippingLabel = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Shipping Services")
                                ->where("customer_id",$id)
                                ->sum('other');

        $serviceFeesOne = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->sum('other');

        $serviceFeesTwo = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Deal Fee")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        $serviceFeesThree = Expense::
                                whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","")
                                ->where("customer_id",$id)
                                ->sum('other_transaction_fees');

        $serviceFees = $serviceFeesOne + $serviceFeesTwo + $serviceFeesThree;

        $adjustments = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description","FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->sum('other');

        $liquidationFees = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Liquidations")
                        ->where("customer_id",$id)
                        ->sum('other_transaction_fees');

        $fbaInventoryCredit = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description",'!=',"FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->sum('other');

        $fbsLiquidationProceeds = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("customer_id",$id)
                        ->sum('product_sales');

        $shippingCredits = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->sum('shipping_credits');

        $giftWrapCredits = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->sum('gift_wrap_credits');

        $shippingCreditRefunds = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->sum('shipping_credits');

        $giftWrapCreditRefunds = Expense::
                        whereIn(DB::raw($timeGroup),$defaultTimeList)
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




    private function formatExpenseData($dataInput,$defaultTimeList){
        $data = array();
        foreach($defaultTimeList as $keyTime=>$itemTime){
            $data[$itemTime] = 0;
            foreach($dataInput as $key=>$value){

                if($itemTime == $value["timeData"]){
                    $data[$itemTime] = $value["totalValue"];
                }
            }
        }
        return $data;
    }

    public function getTotalExpenseByTime($id,$type="month",$timeList=array(),$year=2022){
        //$data = array();
        $defaultTimeList = array();

        $timeGroup = "month(order_date)";
        $timeArray = array();

        if($type=="month"){
            for($i=1;$i<13;$i++){
                $defaultTimeList[]=$i;
            }


            $timeArray[1] = "Jan";
            $timeArray[2] = "Feb";
            $timeArray[3] = "Mar";
            $timeArray[4] = "Apr";
            $timeArray[5] = "May";
            $timeArray[6] = "Jun";
            $timeArray[7] = "Jul";
            $timeArray[8] = "Aug";
            $timeArray[9] = "Sep";
            $timeArray[10] = "Oct";
            $timeArray[11] = "Nov";
            $timeArray[12] = "Dec";

            if($timeList){
                $defaultTimeList = $timeList;
            }
            $timeGroup = "month(order_date)";
        }


        if($type=="quarter"){
            for($i=1;$i<5;$i++){
                $defaultTimeList[]=$i;
            }
            if($timeList){
                $defaultTimeList = $timeList;
            }

            $timeArray[1] = "Quarter 1";
            $timeArray[2] = "Quarter 2";
            $timeArray[3] = "Quarter 3";
            $timeArray[4] = "Quarter 4";

            $timeGroup = "quarter(order_date)";
        }




        $promotionalRebates = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(promotional_rebates) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();



        $promotionalRebates = $this->formatExpenseData($promotionalRebates->toArray(),$defaultTimeList);



        $sellingFees = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(selling_fees) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();

        $sellingFees = $this->formatExpenseData($sellingFees->toArray(),$defaultTimeList);


        $fbaTransactionFees = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(fba_fees) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                //->where("type","Order")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $fbaTransactionFees = $this->formatExpenseData($fbaTransactionFees->toArray(),$defaultTimeList);




        $fbaInventoryFees = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","FBA Inventory Fee")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();

        $fbaInventoryFees = $this->formatExpenseData($fbaInventoryFees->toArray(),$defaultTimeList);



        $costOfAdvertising = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other_transaction_fees) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $costOfAdvertising = $this->formatExpenseData($costOfAdvertising->toArray(),$defaultTimeList);



        // calculate for other expenses
        $shippingLabel = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Shipping Services")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $shippingLabel = $this->formatExpenseData($shippingLabel->toArray(),$defaultTimeList);


        $serviceFeesOne = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Service Fee")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $serviceFeesOne = $this->formatExpenseData($serviceFeesOne->toArray(),$defaultTimeList);




        $serviceFeesTwo = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other_transaction_fees) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","Deal Fee")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $serviceFeesTwo = $this->formatExpenseData($serviceFeesTwo->toArray(),$defaultTimeList);



        $serviceFeesThree = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other_transaction_fees) as totalValue'))
                                ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                                ->whereYear('order_date',"=", $year)
                                ->where("type","")
                                ->where("customer_id",$id)
                                ->groupBy(DB::raw($timeGroup))
                                ->get();
        $serviceFeesThree = $this->formatExpenseData($serviceFeesThree->toArray(),$defaultTimeList);





        //$serviceFees = $serviceFeesOne + $serviceFeesTwo + $serviceFeesThree;

        $adjustments = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description","FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $adjustments = $this->formatExpenseData($adjustments->toArray(),$defaultTimeList);

        $liquidationFees = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other_transaction_fees) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Liquidations")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $liquidationFees = $this->formatExpenseData($liquidationFees->toArray(),$defaultTimeList);

        $fbaInventoryCredit = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(other) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("description",'!=',"FBA Inventory Reimbursement - General Adjustment")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $fbaInventoryCredit = $this->formatExpenseData($fbaInventoryCredit->toArray(),$defaultTimeList);

        $fbsLiquidationProceeds = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(product_sales) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Adjustment")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $fbsLiquidationProceeds = $this->formatExpenseData($fbsLiquidationProceeds->toArray(),$defaultTimeList);

        $shippingCredits = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(shipping_credits) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $shippingCredits = $this->formatExpenseData($shippingCredits->toArray(),$defaultTimeList);

        $giftWrapCredits = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(gift_wrap_credits) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $giftWrapCredits = $this->formatExpenseData($giftWrapCredits->toArray(),$defaultTimeList);

        $shippingCreditRefunds = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(shipping_credits) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();
        $shippingCreditRefunds = $this->formatExpenseData($shippingCreditRefunds->toArray(),$defaultTimeList);

        $giftWrapCreditRefunds = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(gift_wrap_credits) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();

        $giftWrapCreditRefunds = $this->formatExpenseData($giftWrapCreditRefunds->toArray(),$defaultTimeList);



        // echo "<pre>";
        // print_r($giftWrapCreditRefunds);
        // echo "<pre>";
        // print_r($shippingCreditRefunds);
        // echo "<pre>";
        // print_r($giftWrapCredits);
        // echo "<pre>";
        // print_r($shippingCredits);
        // echo "<pre>";
        // print_r($fbsLiquidationProceeds);
        // echo "<pre>";
        // print_r($fbaInventoryCredit);
        // echo "<pre>";
        // print_r($liquidationFees);
        // echo "<pre>";
        // print_r($adjustments);
        // echo "<pre>";
        // print_r($serviceFeesThree);
        // echo "<pre>";
        // print_r($serviceFeesTwo);
        // echo "<pre>";
        // print_r($serviceFeesOne);
        // echo "<pre>";
        // print_r($promotionalRebates);
        // echo "<pre>";
        // print_r($fbaTransactionFees);
        // echo "<pre>";
        // print_r($fbaInventoryFees);
        // echo "<pre>";
        // print_r($costOfAdvertising);
        // echo "<pre>";
        // print_r($shippingLabel);
        // echo "<pre>";
        // print_r($sellingFees);die;

        // echo "<pre>";
        // print_r($defaultMonthList);die;



        $data = array();

        //$data['monthName'] = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");




        $i=0;



        foreach($defaultTimeList as $keyMonth=>$valueMonth){

            $data["timeName"][$i] = $timeArray[$valueMonth];
            //$serviceFees = $serviceFeesOne + $serviceFeesTwo + $serviceFeesThree;
            $defaultMonthList[$keyMonth] = 0;
            $otherExpense = $shippingLabel[$valueMonth] + $serviceFeesOne[$valueMonth]
                            + $serviceFeesTwo[$valueMonth] + $serviceFeesThree[$valueMonth]
                            + $adjustments[$valueMonth] + $liquidationFees[$valueMonth]
                            + $fbaInventoryCredit[$valueMonth] + $fbsLiquidationProceeds[$valueMonth]
                            + $shippingCredits[$valueMonth] + $giftWrapCredits[$valueMonth]
                            + $shippingCreditRefunds[$valueMonth] + $giftWrapCreditRefunds[$valueMonth];
            $total = abs($otherExpense) + abs($promotionalRebates[$valueMonth]) + abs($sellingFees[$valueMonth])
                    + abs($fbaTransactionFees[$valueMonth]) + abs($fbaInventoryFees[$valueMonth]) + abs($costOfAdvertising[$valueMonth]);

            $defaultMonthList[$valueMonth] = $total;
            $data["timeData"][$i] = $total;
            $i++;
        }


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

    public function getRefundPercent($id,$type="month",$timeList=array(),$year=2022,){
        //$data = array();
        $defaultTimeList = array();
        $timeGroup = 'month(order_date)';


        if($type=="month"){
            for($i=1;$i<13;$i++){
                $defaultTimeList[]=$i;
            }
            $timeGroup = 'month(order_date)';
            if($timeList){
                $defaultTimeList = $timeList;
            }
        }
        if($type=="quarter"){
            for($i=1;$i<5;$i++){
                $defaultTimeList[]=$i;
            }
            $timeGroup = 'quarter(order_date)';
            if($timeList){
                $defaultTimeList = $timeList;
            }
        }




        $totalRevenue = Expense::
                            whereIn(DB::raw($timeGroup),$defaultTimeList)
                            ->whereYear('order_date',"=", $year)
                            ->where("type","Order")
                            ->where("customer_id",$id)
                            ->sum('product_sales');

        $totalRefund = Expense::
                            whereIn(DB::raw($timeGroup),$defaultTimeList)
                            ->whereYear('order_date',"=", $year)
                            ->where("type","Refund")
                            ->where("customer_id",$id)
                            ->sum('product_sales');
        //$totalRefund = abs($totalRefund);
        $data = array();


        $refund = number_format((abs($totalRefund) / abs($totalRevenue)) * 100 ,2);
        $subRevenue = number_format((abs($totalRevenue) - abs($totalRefund)) / abs($totalRevenue) * 100 ,2);



        $data["label"][] = "Revenue : " . $totalRevenue;
        //$data["value"][] = abs($totalRevenue);
        $data["value"][] = $subRevenue;
        $data["label"][] = "Refunds : " . abs($totalRefund);
        $data["value"][] = $refund;
        //$data["value"][] = abs($totalRefund);



        return $data;

        //return $data;
    }


    public function getRefundPercentDetail($id,$type="month",$timeList=array(),$year=2022){
        //$data = array();
        $defaultTimeList = array();
        $timeGroup = 'month(order_date)';

        $timeArray = array();


        if($type=="month"){
            $timeArray[1] = "Jan";
            $timeArray[2] = "Feb";
            $timeArray[3] = "Mar";
            $timeArray[4] = "Apr";
            $timeArray[5] = "May";
            $timeArray[6] = "Jun";
            $timeArray[7] = "Jul";
            $timeArray[8] = "Aug";
            $timeArray[9] = "Sep";
            $timeArray[10] = "Oct";
            $timeArray[11] = "Nov";
            $timeArray[12] = "Dec";
            for($i=1;$i<13;$i++){
                $defaultTimeList[]=$i;
            }
            $timeGroup = 'month(order_date)';
            if($timeList){
                $defaultTimeList = $timeList;
            }
        }
        if($type=="quarter"){
            $timeArray[1] = "Quarter 1";
            $timeArray[2] = "Quarter 2";
            $timeArray[3] = "Quarter 3";
            $timeArray[4] = "Quarter 4";
            for($i=1;$i<5;$i++){
                $defaultTimeList[]=$i;
            }
            $timeGroup = 'quarter(order_date)';
            if($timeList){
                $defaultTimeList = $timeList;
            }
        }


        $revenue = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(product_sales) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Order")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();



        $revenue = $this->formatExpenseData($revenue->toArray(),$defaultTimeList);



        $refund = Expense::select(DB::raw($timeGroup.' timeData'),DB::raw('sum(product_sales) as totalValue'))
                        ->whereIn(DB::raw($timeGroup),$defaultTimeList)
                        ->whereYear('order_date',"=", $year)
                        ->where("type","Refund")
                        ->where("customer_id",$id)
                        ->groupBy(DB::raw($timeGroup))
                        ->get();

        $refund = $this->formatExpenseData($refund->toArray(),$defaultTimeList);


        $data = array();



        $i=0;



        foreach($defaultTimeList as $keyTime=>$valueTime){
            $data["label"][$i] = $timeArray[$valueTime];
            $refundData = abs($refund[$valueTime]);
            $revenueData = $revenue[$valueTime];

            // $percentRefund = 0;

            $data["percentRefund"][$i] = 0;
            $data["percentRevenue"][$i] = 0;
            if($revenueData > 0){
                $percentRefund = number_format($refundData/$revenueData*100,2);
                $data["percentRefund"][$i] = $percentRefund;
                $data["percentRevenue"][$i] = 100;
            }


            $data["refund"][$i] = $refundData;
            $data["revenue"][$i] = $revenueData;

            //echo
            $i++;
        }


        return $data;

        //return $data;
    }

    public function getProfit($id, $revenue, $expense){

        $data = $revenue;

        foreach($data["timeData"] as $key=>$value){
            $profit = $revenue["timeData"][$key] - $expense["timeData"][$key];
            $data["timeData"][$key] = $profit;
        }
        return $data;
    }
}
