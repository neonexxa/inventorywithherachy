@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Transaction : {{\App\Allocation::find($transaction->allocation_id)->stock->item_name}}</div>

                <div class="panel-body">
                	<form action="{{ route('transaction.update',['transaction'=>$transaction->id]) }}" method="POST">
                		{{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="old_buyer_type" value="{{$transaction->buyer_type}}">
                        <input type="hidden" name="old_quantity" value="{{$transaction->quantity}}">
                        <input type="hidden" name="allocation_id" value="{{$transaction->allocation_id}}">
                        <div class="form-group">
                            <select class="form-control" id="buyer_type" name="buyer_type">
                                <option @if($transaction->buyer_type == 'outstock_subagent') selected @endif value="outstock_subagent">outstock subagent</option>
                                <option @if($transaction->buyer_type == 'outstock_panel') selected @endif value="outstock_panel">outstock panel</option>
                                <option @if($transaction->buyer_type == 'outstock_outlet') selected @endif value="outstock_outlet">outstock outlet</option>
                                <option @if($transaction->buyer_type == 'outstock_counter') selected @endif value="outstock_counter">outstock counter</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="quantity" name="quantity" type="number" placeholder="Quantity" class="form-control input-md" value="{{$transaction->quantity}}"> 
                        </div>
                        <div class="form-group">
                            <input id="price" name="price" type="number" placeholder="Selling Price" class="form-control input-md" value="{{$transaction->price}}"> 
                        </div>
						<button type="submit" class="btn btn-success">Create</button>
					</form>
                </div>
            </div>
        </div>
	</div>
</div>

@endsection