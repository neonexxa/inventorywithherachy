@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form action="{{route('transaction.index')}}" method="GET">
				<input type="date" name="date">	
				<button type="submit" class="btn btn-success">VIEW</button>
			</form>
			<br>
			<form align="right">
				<a href="{{ route('transaction.create') }}" class="btn btn-primary">NEW TRANSACTION</a>
			</form>
		</div>
	</div>
	{{-- begining of my self --}}
	<label for="useralocation">
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
		        OPS TOO POWERFUL -
		@endswitch
		{{ Auth::user()->name }} (My Transactions)
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
						<th scope="col" width="30">#</th>
						<th scope="col" width="100">Quantity</th>
						<th scope="col" width="200">Selling Price</th>
						<th scope="col" width="200">Modal Price</th>
						<th scope="col" width="200">Profit</th>
						<th scope="col" width="300">Transaction Date/Time</th>
						<th scope="col" width="200">Buyer Type</th>
						<th scope="col" width="200">Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach ($allocation->transactions as $trans_key => $transaction)
					
					<tr>
						<th scope="row">{{$transaction->id}}</th>
						<td>{{$transaction->quantity}}</td>
						<td>RM{{$transaction->price}}</td>
						<td>RM{{$allocation->modal_price}}</td>
						<td>RM{{$transaction->profit*$transaction->quantity}}</td>
						<td>{{$transaction->created_at}}</td>
						<td>{{$transaction->buyer_type}}</td>
						<td>
							
							<form action="{{route('transaction.destroy',['transaction'=>$transaction->id])}}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="delete">
								<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>

								<a class="btn btn-warning" href="{{ route('transaction.edit',['transaction'=> $transaction->id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
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
	<br>
	{{-- end of myself --}}
	@foreach ($users_allocations as $user_id => $allocations) 
		<label for="useralocation">
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
        @foreach ($allocations as $allocationkey => $allocation) 
			<div class="row">
				<div class="col-md-10">
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
								<th scope="col" width="30">#</th>
								<th scope="col" width="100">Quantity</th>
								<th scope="col" width="200">Selling Price</th>
								<th scope="col" width="200">Modal Price</th>
								<th scope="col" width="200">Profit</th>
								<th scope="col" width="300">Transaction Date/Time</th>
								<th scope="col" width="200">Buyer Type</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($allocation->transactions as $trans_key => $transaction)
							<tr>
								<th scope="row">{{$transaction->id}}</th>
								<td>{{$transaction->quantity}}</td>
								<td>RM{{$transaction->price}}</td>
								<td>RM{{$allocation->modal_price}}</td>
								<td>RM{{$transaction->profit*$transaction->quantity}}</td>
								<td>{{$transaction->created_at}}</td>
								<td>{{$transaction->buyer_type}}</td>
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