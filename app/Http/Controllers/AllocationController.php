<?php

namespace App\Http\Controllers;

use App\Allocation;
use App\Stock;
use App\User;
use Illuminate\Http\Request;
use Auth;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            }
        }
        // dd($users_allocations);

        // myself
        $myself_allocations = Auth::user()->allocations;
        foreach ($allocations as $allocationkey => $allocation) {
            $myself_allocations->outstock_subagent = array_sum(array_column($myself_allocations->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
            $myself_allocations->outstock_panel = array_sum(array_column($myself_allocations->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
            $myself_allocations->outstock_outlet = array_sum(array_column($myself_allocations->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
            $myself_allocations->outstock_counter = array_sum(array_column($myself_allocations->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Allocation  $allocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Allocation $allocation)
    {
        //
    }
}
