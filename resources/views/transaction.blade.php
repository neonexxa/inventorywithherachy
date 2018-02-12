@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-2">
			<a href="{{ route('transaction.create') }}" class="btn btn-primary">NEW TRANSACTION</a>
			<form action="{{route('transaction.index')}}" method="GET">
				<input type="date" name="date">	
				<button type="submit" class="btn btn-success">VIEW</button>
			</form>
		</div>
	</div>
	{{-- begining of my self --}}
	<label for="useralocation">
		@switch(Auth::user()->role)
		    @case(1)
		        KAMAL
		        @break

		    @case(2)
		        SUBAGENT
		        @break

		    @case(3)
		        OUTLET
		        @break

		    @case(4)
		        PANEL
		        @break

		    @default
		        OPS TOO POWERFUL
		@endswitch
		{{ Auth::user()->name }} (myself)
	</label>
	@foreach ($myself_allocations as $allocationkey => $allocation) 
	<div class="row">
		<div class="col-md-12">
			<label for="allocation">{{ $allocation->stock->item_name }} , Stock/Allocation Date : {{\Carbon\Carbon::parse($allocation->created_at)->format('Y-m-d')}}</label>
			<br>
			{{-- date filter for transaction --}}
			<?php
			$allocation->transactions = $allocation->transactions->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by years
            });
            $allocation->transactions = (empty($allocation->transactions[$transaction_date]))? Null : $allocation->transactions[$transaction_date];
            ?>
            @if(is_null($allocation->transactions))
				<label for="null_val">This allocation has no transaction for today</label>
			@else
            {{-- date filter end for transaction --}}
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">QTY</th>
						<th scope="col">Selling Price</th>
						<th scope="col">Modal</th>
						<th scope="col">Profit</th>
						<th scope="col">Transaction Date</th>
						<th scope="col">Buyer Type</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($allocation->transactions as $trans_key => $transaction)
					
					<tr>
						<th scope="row">{{$transaction->id}}</th>
						<td>{{$transaction->quantity}}</td>
						<td>{{$transaction->price}}</td>
						<td>{{$allocation->modal_price}}</td>
						<td>{{$transaction->profit*$transaction->quantity}}</td>
						<td>{{$transaction->created_at}}</td>
						<td>{{$transaction->buyer_type}}</td>
						<td>
							<a class="btn btn-warning" href="{{ route('transaction.edit',['transaction'=> $transaction->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
							<form action="{{route('transaction.destroy',['transaction'=>$transaction->id])}}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="delete">
								<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</div>
	</div>
	@endforeach
	{{-- end of myself --}}
	@foreach ($users_allocations as $user_id => $allocations) 
		<label for="useralocation">
			@switch(\App\User::find($user_id)->role)
			    @case(1)
			        KAMAL
			        @break

			    @case(2)
			        SUBAGENT
			        @break

			    @case(3)
			        OUTLET
			        @break

			    @case(4)
			        PANEL
			        @break

			    @default
			        OPS TOO POWERFUL
			@endswitch
			{{ \App\User::find($user_id)->name }} 
		</label>
        @foreach ($allocations as $allocationkey => $allocation) 
			<div class="row">
				<div class="col-md-12">
					<label for="allocation">{{ $allocation->stock->item_name }} , Stock/Allocation Date : {{\Carbon\Carbon::parse($allocation->created_at)->format('Y-m-d')}}</label>
					<br>
					{{-- transaction filter begin --}}
					<?php
					$allocation->transactions = $allocation->transactions->groupBy(function($date) {
		                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by years
		            });
		            $allocation->transactions = (empty($allocation->transactions[$transaction_date]))? Null : $allocation->transactions[$transaction_date];
		            ?>
		            {{-- transaction filter end --}}
		            @if(is_null($allocation->transactions))
						<label for="null_val">This allocation has no transaction for today</label>
					@else
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">QTY</th>
								<th scope="col">Selling Price</th>
								<th scope="col">Modal</th>
								<th scope="col">Profit</th>
								<th scope="col">Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($allocation->transactions as $trans_key => $transaction)
							<tr>
								<th scope="row">{{$transaction->id}}</th>
								<td>{{$transaction->quantity}}</td>
								<td>{{$transaction->price}}</td>
								<td>{{$allocation->modal_price}}</td>
								<td>{{$transaction->profit*$transaction->quantity}}</td>
								<td>{{$transaction->created_at}}</td>
								{{-- <td>
									<a class="btn btn-warning" href="{{ route('stock.edit',['stock'=> $stock->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
									<form action="{{route('stock.destroy',['stock'=>$stock->id])}}" method="POST">
										{{ csrf_field() }}
										<input type="hidden" name="_method" value="delete">
										<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
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
		<br>
	@endforeach
</div>


@endsection