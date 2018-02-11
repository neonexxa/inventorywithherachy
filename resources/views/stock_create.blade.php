@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">New Stock</div>

                <div class="panel-body">
                	<form action="{{ route('stock.store') }}" method="POST">
                		{{ csrf_field() }}
	                    <div class="form-group">
							<input id="item_name" name="item_name" type="text" placeholder="Item Name" class="form-control input-md"> 
						</div>

						<!-- Text input-->
						<div class="form-group">
							<input id="item_code" name="item_code" type="text" placeholder="Item Code" class="form-control input-md">
						</div>
						<button type="submit" class="btn btn-success">Create</button>
					</form>
                </div>
            </div>
        </div>
	</div>
</div>

@endsection