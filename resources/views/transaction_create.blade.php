@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @if(array_sum(array_column($allocations->toArray(), 'availability')) == 0)
                <div class="panel-heading">Opps No allocation available</div>   
                <div class="panel-body">
                    <a href="{{ route('allocation.create') }}" class="btn btn-primary">ADD ALLOCATION</a>
                </div>
                @else
                <div class="panel-heading">New Transaction</div>
                <div class="panel-body">
                	<form action="{{ route('transaction.store') }}" method="POST">
                		{{ csrf_field() }}
                        <div class="form-group">
                            <select class="form-control" id="buyer_type" name="buyer_type">
                                <option value="outstock_subagent">outstock subagent</option>
                                <option value="outstock_panel">outstock panel</option>
                                <option value="outstock_outlet">outstock outlet</option>
                                <option value="outstock_counter">outstock counter</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="allocation_id" name="allocation_id">
                                @foreach($allocations as $allocation)
                                    @if($allocation->availability)
                                    <option value="{{$allocation->id}}">{{$allocation->stock->item_name}} : RM {{$allocation->modal_price}} : {{ $allocation->availability }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="quantity" name="quantity" type="number" placeholder="Quantity" class="form-control input-md"> 
                        </div>
                        <div class="form-group">
                            <input id="price" name="price" type="number" placeholder="Selling Price" class="form-control input-md"> 
                        </div>
						<button type="submit" class="btn btn-success">Create</button>
					</form>
                </div>
                @endif
            </div>
        </div>
	</div>
</div>

@endsection