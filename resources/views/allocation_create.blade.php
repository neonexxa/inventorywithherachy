@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Allocation</div>

                <div class="panel-body">
                	<form action="{{ route('allocation.store') }}" method="POST">
                		{{ csrf_field() }}
						<div class="form-group">
							<select class="form-control" id="stock_id" name="stock_id">
							    @foreach($stocks as $stock)
							    <option value="{{$stock->id}}">{{$stock->item_name}} : {{$stock->item_code}}</option>
							    @endforeach
							</select>
						</div>
						<div class="form-group">
							<input id="total_stock" name="total_stock" type="text" placeholder="total_stock" class="form-control input-md">
						</div>
						<div class="form-group">
							<input id="modal_price" name="modal_price" type="text" placeholder="modal_price" class="form-control input-md">
						</div>
						<button type="submit" class="btn btn-success">Create</button>
					</form>
                </div>
            </div>
        </div>
	</div>
</div>

@endsection