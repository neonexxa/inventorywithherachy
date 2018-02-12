<?php

namespace App\Http\Controllers;

use App\Allocation;
use App\Stock;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class AllocationController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $param = $request->all();
        if (empty($param['date'])) {
            $param['date'] = Carbon::now('Asia/Singapore')->format('Y-m-d');;
        }
        $users = User::where('role','>',Auth::user()->role)->orderBy('role','asc')->get();
        $users_allocations = [];
        foreach ($users as $key => $user) {
            $users_allocations[$user->id] = $user->allocations;
        }
        // dd($users_allocations);
        foreach ($users_allocations as $user_id => $allocations) {
            foreach ($allocations as $allocationkey => $allocation) {
                $allocation->outstock_subagent = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
                $allocation->outstock_panel = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
                $allocation->outstock_outlet = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
                $allocation->outstock_counter = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
                $allocation->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $allocation->created_at)->format('Y-m-d').'00:00:00';
            }
        }

        // group allocation of each user by date
        foreach ($users_allocations as $user_id => $allocations) {
            $users_allocations[$user_id] = $allocations->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by years
            });
        }

        // select only the date we want to view
        foreach ($users_allocations as $user_id => $allocations) {
            $users_allocations[$user_id] = (empty($allocations[$param['date']]))? Null : $allocations[$param['date']];
        }
        // end of filter

        // myself
        $myself_allocations = Auth::user()->allocations()->whereDate('created_at', '=', date($param['date']))->get();
        foreach ($myself_allocations as $allocationkey => $allocation) {
            $allocation->outstock_subagent = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
            $allocation->outstock_panel = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
            $allocation->outstock_outlet = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
            $allocation->outstock_counter = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
        }
        return view('allocation',compact('users_allocations','myself_allocations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stocks = Stock::all();
        return view('allocation_create',compact('stocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $param = $request->all();

        $allocation = new Allocation;
        $allocation->stock_id       = $param['stock_id'];
        $allocation->total_stock    = $param['total_stock'];
        $allocation->modal_price    = $param['modal_price'];
        $allocation->user_id        = Auth::user()->id;
        $allocation->save();

        return redirect('allocation');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function show(Allocation $allocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function edit(Allocation $allocation)
    {
        $stocks = Stock::all();
        return view('allocation_edit',compact('allocation','stocks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Allocation $allocation)
    {
        $param = $request->all();
        $allocation->stock_id       = $param['stock_id'];
        $allocation->total_stock    = $param['total_stock'];
        $allocation->modal_price    = $param['modal_price'];
        $allocation->user_id        = Auth::user()->id;
        $allocation->save();
        return redirect('allocation');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Allocation $allocation)
    {
        $allocation->delete();
        return redirect('allocation');
    }
}
