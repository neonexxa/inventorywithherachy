@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<a href="{{ route('stock.create') }}" class="btn btn-primary">ADD STOCK</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col" width="300">Item Name</th>
						<th scope="col">Item Code</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
			<tbody>
				{{-- begin loop --}}
				@foreach($stocks as $stock)
				<tr>
					<th scope="row">{{$stock->id}}</th>
					<td>{{$stock->item_name}}</td>
					<td>{{$stock->item_code}}</td>
					<td>
						<!-- <form class="form-inline"> -->
						

						<form action="{{route('stock.destroy',['stock'=>$stock->id])}}" method="POST">
							{{ csrf_field() }} 
							<input type="hidden" name="_method" value="delete">
							<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>

							<a class="btn btn-warning" href="{{ route('stock.edit',['stock'=> $stock->id]) }}">
							<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

						</form>
						<!-- </form> -->
					</td>
				</tr>
				@endforeach
				{{-- end loop --}}
			</tbody>
			</table>
		</div>
	</div>
</div>


@endsection