<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 
use App\Transaction;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
    *
    *   Changes for profit summary
    *   Description :   
    *   Last edited by : Firdausneonexxa
    *
    */
        
    public function profitsum (Request $request)
    {
        $param = $request->all();
        if (empty($param['year'])) {
            $desiredyear = Carbon::now()->year;
            $desiredmonth = '';
            $desiredday = '';
            $filter_type = 'year';
        }elseif(empty($param['month'])){
            $desiredyear = $param['year'];
            $desiredmonth = '';
            $desiredday = '';
            $filter_type = 'year';
        }elseif(empty($param['day'])){
            $desiredyear = $param['year'];
            $desiredmonth = $param['month'];
            $desiredday = '';
            $filter_type = 'month';
        }else{
            $desiredyear = $param['year'];
            $desiredmonth = $param['month'];
            $desiredday = $param['day'];
            $filter_type = 'day';
        }
        $transactions_bydate = Transaction::all()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by years
        });
        $selectedtransaction = [];
        // switch filter begin
        switch($filter_type){
            case 'none':
                break;
            default:
                foreach ($transactions_bydate as $transdate => $transaction_in_one_day) {
                    $date = explode("-",$transdate); //0 year, 1 month, 2 day
                    if ($date[0] == $desiredyear) {
                        $selectedtransaction[$date[0]][$date[1]][$date[2]]['transactions_in_one_day'] = $transaction_in_one_day;
                    }
                }

                // how to loop among transaction
                foreach ($selectedtransaction as $year => $transinyear) {
                    foreach ($transinyear as $month => $transinmonth) {
                        foreach ($transinmonth as $day => $transinday) {
                            foreach ($transinday['transactions_in_one_day'] as $key => $value) {
                                // trans_total_sell
                                $value->trans_total_sell = $value->quantity*$value->price;
                                $value->trans_total_modal = $value->quantity*$value->allocation->modal_price;
                            }
                        }
                    }
                }
        }
        
        // dd($selectedtransaction);
        return view('profit',compact('filter_type','selectedtransaction','desiredyear','desiredmonth','desiredday'));
    }
        
}
