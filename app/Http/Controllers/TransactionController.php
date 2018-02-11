<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Allocation;
use App\User;
use Illuminate\Http\Request;
use Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $transactions = Transaction::all();
        $users = User::where('role','>',Auth::user()->role)->orderBy('role','asc')->get();
        $users_allocations = [];
        foreach ($users as $key => $user) {
            $users_allocations[$user->id] = $user->allocations;
        }
        foreach ($users_allocations as $user_id => $allocations) {
            foreach ($allocations as $allocationkey => $allocation) {
                $allocation->outstock_subagent = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
                $allocation->outstock_panel = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
                $allocation->outstock_outlet = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
                $allocation->outstock_counter = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
                foreach ($allocation->transactions as $trans_key => $transaction) {
                    $transaction->profit = $transaction->price-$allocation->modal_price;
                }
            }
        }
        return view('transaction',compact('users_allocations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allocations = Auth::user()->allocations;

        // find sum
        foreach ($allocations as $key => $allocation) {
            $allocation->outstock_subagent = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
            $allocation->outstock_panel = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
            $allocation->outstock_outlet = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
            $allocation->outstock_counter = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
            $allocation->availability = ($allocation->outstock_subagent+$allocation->outstock_panel+$allocation->outstock_outlet+$allocation->outstock_counter == $allocation->total_stock)? 0:$allocation->total_stock-$allocation->outstock_subagent+$allocation->outstock_panel+$allocation->outstock_outlet+$allocation->outstock_counter;
        }
        // end sum

        return view('transaction_create',compact('allocations'));
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

        // use allocation id to find whether qty exceed allocation availability
        $allocation = Allocation::find($param['allocation_id']);
        $allocation->outstock_subagent = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_subagent')->toArray(), 'quantity'));
        $allocation->outstock_panel = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_panel')->toArray(), 'quantity'));
        $allocation->outstock_outlet = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_outlet')->toArray(), 'quantity'));
        $allocation->outstock_counter = array_sum(array_column($allocation->transactions->where('buyer_type','outstock_counter')->toArray(), 'quantity'));
        $allocation->exceed = ($allocation->outstock_subagent+$allocation->outstock_panel+$allocation->outstock_outlet+$allocation->outstock_counter+$param['quantity'] > $allocation->total_stock)? 1 : 0;

        if ($allocation->exceed) {
            return back();
        }

        $newtransaction = new Transaction;
        $newtransaction->allocation_id      = $param['allocation_id'];
        $newtransaction->quantity           = $param['quantity'];
        $newtransaction->price              = $param['price'];
        $newtransaction->buyer_type         = $param['buyer_type'];
        $newtransaction->user_id            = Auth::user()->id;
        $newtransaction->save();

        return redirect('transaction');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
