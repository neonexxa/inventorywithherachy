@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-12">
			
			<form action="{{route('allocation.index')}}" method="GET">
				<input type="date" name="date">	
				<button type="submit" class="btn btn-success">VIEW</button>
			</form>
			<br>

			<form align="right" >
			<a href="{{ route('allocation.create') }}" class="btn btn-primary">ADD ALLOCATION</a>
			</form>
			
		</div>
	</div>
	{{-- begining of myself --}}
	<div class="row">
		<div class="col-md-12">
			<label for="usertable">
				@switch(Auth::user()->role)
				    @case(1)
				        AGENT -
				        @break

				    @case(2)
				        SUBAGENT -
				        @break

				    @case(3)
				        OUTLET -
				        @break

				    @case(4)
				        PANEL -
				        @break

				    @default
				        OPS TOO POWERFUL
				@endswitch
				{{ Auth::user()->name }} (My Allocations)
			</label>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<!-- <th scope="col">User</th> -->
						<th scope="col" width="300">Item Name</th>
						<th scope="col">QTY</th>
						<th scope="col">Modal Price</th>
						<th scope="col">Outstock-Counter</th>
						<th scope="col">Outstock-Outlet</th>
						<th scope="col">Outstock-Panel</th>
						<th scope="col">Outstock-Subagent</th>
						<th scope="col" width="130">Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($myself_allocations as $allocation)
					<tr>
						<th scope="row">{{$allocation->id}}</th>
						<!-- <td>{{ $allocation->user->name }}</td> -->
						<td>{{ $allocation->stock->item_name }}</td>
						<td>{{$allocation->total_stock}}</td>
						<td>RM {{$allocation->modal_price}}</td>
						<td>{{ (is_null($allocation->outstock_counter))? 0 : $allocation->outstock_counter }}</td>
						<td>{{ (is_null($allocation->outstock_outlet))? 0 : $allocation->outstock_outlet }}</td>
						<td>{{ (is_null($allocation->outstock_panel))? 0 : $allocation->outstock_panel }}</td>
						<td>{{ (is_null($allocation->outstock_subagent))? 0 : $allocation->outstock_subagent }}</td>
						<td>
							
							<form action="{{route('allocation.destroy',['allocation'=>$allocation->id])}}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="delete">
								<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>

								<a class="btn btn-warning" href="{{ route('allocation.edit',['allocation'=> $allocation->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	{{-- end of myself --}}
			
			

	@foreach ($users_allocations as $user_id => $allocations)
	<div class="row">
		<div class="col-md-12">
			<label for="usertable">
				@switch(\App\User::find($user_id)->role)
				    @case(1)
				        AGENT -
				        @break

				    @case(2)
				        SUBAGENT - 
				        @break

				    @case(3)
				        OUTLET -
				        @break

				    @case(4)
				        PANEL -
				        @break

				    @default
				        OPS TOO POWERFUL
				@endswitch
				{{ \App\User::find($user_id)->name }}
			</label>
			<br>
			@if(is_null($allocations))
				<label for="null_val">User has no allocation for today</label>
			@else
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">User</th>
						<th scope="col">Item Name</th>
						<th scope="col">QTY</th>
						<th scope="col">MODAL</th>
						<th scope="col">Outstock-Counter</th>
						<th scope="col">Outstock-Outlet</th>
						<th scope="col">Outstock-Panel</th>
						<th scope="col">Outstock-Subagent</th>
					</tr>
				</thead>
				<tbody>

					@foreach($allocations as $allocation)
					<tr>
						<th scope="row">{{$allocation->id}}</th>
						<td>{{ $allocation->user->name }}</td>
						<td>{{ $allocation->stock->item_name }}</td>
						<td>{{$allocation->total_stock}}</td>
						<td>RM {{$allocation->modal_price}}</td>
						<td>{{ (is_null($allocation->outstock_counter))? 0 : $allocation->outstock_counter }}</td>
						<td>{{ (is_null($allocation->outstock_outlet))? 0 : $allocation->outstock_outlet }}</td>
						<td>{{ (is_null($allocation->outstock_panel))? 0 : $allocation->outstock_panel }}</td>
						<td>{{ (is_null($allocation->outstock_subagent))? 0 : $allocation->outstock_subagent }}</td>
						{{-- 
						<td>
							
							<form class="form-inline" action="{{route('stock.destroy',['stock'=>$stock->id])}}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="delete">
								<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>

								<a class="btn btn-warning" href="{{ route('stock.edit',['stock'=> $stock->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
							</form>
						</td> --}}
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</div>
	</div>
	@endforeach
</div>

@endsection