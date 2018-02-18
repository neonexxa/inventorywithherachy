@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col" width="300">User Name</th>
						<th scope="col">Email</th>
						<th scope="col">Role</th>
					</tr>
				</thead>
			<tbody>
				{{-- begin loop --}}
				@foreach($users as $user)
				<tr>
					<th scope="row">{{$user->id}}</th>
					<td>{{$user->name}}</td>
					<td>{{$user->email}}</td>
					<td>
						<form action="{{ route('role.update',['role'=>$user->id]) }}" method="POST">
                		{{ csrf_field() }}
                		<input type="hidden" name="_method" value="put">
						<div class="form-group">
							<select class="form-control" id="role_select" name="role_id" onchange="this.form.submit()">
							    @foreach($rolelist as $rolekey => $role)
							    	<option value="{{$rolekey}}" @if($user->role == $rolekey) selected @endif>{{$role}}</option>
							    	{{-- <option value="{{$rolekey}}" @if($stock->id == $allocation->stock_id) selected @endif>{{$stock->item_name}} : {{$stock->item_code}}</option> --}}
							    @endforeach
							</select>
						</div>
						</form>
						
						<!-- <form class="form-inline"> -->
						

						{{-- <form action="{{route('stock.destroy',['stock'=>$stock->id])}}" method="POST">
							{{ csrf_field() }} 
							<input type="hidden" name="_method" value="delete">
							<button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>

							<a class="btn btn-warning" href="{{ route('stock.edit',['stock'=> $stock->id]) }}">
							<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

						</form> --}}
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