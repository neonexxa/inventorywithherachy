@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Stock</div>

                <div class="panel-body">
                	<form action="{{ route('stock.update',['stock'=>$stock->id]) }}" method="POST">
                		{{ csrf_field() }}
                		<input type="hidden" name="_method" value="put">
	                    <div class="form-group">
							<input id="item_name" name="item_name" type="text" placeholder="Item Name" class="form-control input-md" value="{{ $stock->item_name }}"> 
						</div>

						<!-- Text input-->
						<div class="form-group">
							<input id="item_code" name="item_code" type="text" placeholder="Item Code" class="form-control input-md" value="{{ $stock->item_code }}">
						</div>
						<button type="submit" class="btn btn-success">Update</button>
					</form>
                </div>
            </div>
        </div>
	</div>
</div>

@endsection