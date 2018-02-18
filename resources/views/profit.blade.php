@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<form action="{{ route('profit') }}" method="GET">
			<div class="form-group">
				<select class="form-control" id="year_select" name="year" onchange="this.form.submit()">
				    @foreach(range( \Carbon\Carbon::now()->year, 1990 ) as $yearlist)
				    	<option value="{{$yearlist}}" @if($desiredyear == $yearlist) selected @endif>{{$yearlist}}</option>
				    @endforeach
				</select>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-md-12">

			@switch($filter_type)
			    @case('year')
			        <table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col" width="300">Bulan</th>
								<th scope="col">Jualan</th>
								<th scope="col">Modal</th>
								<th scope="col">Profit</th>
								<th scope="col">Subagent</th>
								<th scope="col">Panel</th>
								<th scope="col">Outlet</th>
								<th scope="col">Counter</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($selectedtransaction as $year => $transinyear) 
								@if($year == $desiredyear)
									<?php 
		        		    			ksort($transinyear);
		        		    		?>
				        		    @foreach ($transinyear as $month => $transinmonth) 
										<tr class='clickable-row' data-href='{{route('profit',['year'=>$year,'month'=>$month])}}'>
											<th scope="row">#</th>
											<td>{{date("F", mktime(0, 0, 0, $month, 10))}}</td>
											<td>
												<?php
												$totalsellthismonth = 0;
												foreach ($transinmonth as $day => $transinday){
													$totalsellthismonth += array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_sell'));
												}
												?>
												{{$totalsellthismonth}}
											</td>
											<td>
												<?php
												$totalmodalthismonth = 0;
												foreach ($transinmonth as $day => $transinday){
													$totalmodalthismonth += array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_modal'));
												}
												?>
												{{$totalmodalthismonth}}
											</td>
											<td>{{$totalsellthismonth-$totalmodalthismonth}}</td>

											{{-- begin filter in year --}}
											<?php
											$totalsellthismonth_filtered_subagent = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalsellthismonth_filtered_subagent += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_subagent')->toArray(), 'trans_total_sell'));
											}
											$totalmodalthismonth_filtered_subagent = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalmodalthismonth_filtered_subagent += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_subagent')->toArray(), 'trans_total_modal'));
											}
											?>
											<td>{{$totalsellthismonth_filtered_subagent-$totalmodalthismonth_filtered_subagent}}</td>
											<?php
											$totalsellthismonth_filtered_panel = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalsellthismonth_filtered_panel += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_panel')->toArray(), 'trans_total_sell'));
											}
											$totalmodalthismonth_filtered_panel = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalmodalthismonth_filtered_panel += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_panel')->toArray(), 'trans_total_modal'));
											}
											?>
											<td>{{$totalsellthismonth_filtered_panel-$totalmodalthismonth_filtered_panel}}</td>
											<?php
											$totalsellthismonth_filtered_outlet = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalsellthismonth_filtered_outlet += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_outlet')->toArray(), 'trans_total_sell'));
											}
											$totalmodalthismonth_filtered_outlet = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalmodalthismonth_filtered_outlet += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_outlet')->toArray(), 'trans_total_modal'));
											}
											?>
											<td>{{$totalsellthismonth_filtered_outlet-$totalmodalthismonth_filtered_outlet}}</td>
											<?php
											$totalsellthismonth_filtered_counter = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalsellthismonth_filtered_counter += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_counter')->toArray(), 'trans_total_sell'));
											}
											$totalmodalthismonth_filtered_counter = 0;
											foreach ($transinmonth as $day => $transinday){
												$totalmodalthismonth_filtered_counter += array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_counter')->toArray(), 'trans_total_modal'));
											}
											?>
											<td>{{$totalsellthismonth_filtered_counter-$totalmodalthismonth_filtered_counter}}</td>
										</tr>
				        		    @endforeach
			        		    @endif
			        		@endforeach
						</tbody>
					</table>
			        @break

			    @case('month')
			        <table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col" width="300">Hari Bulan</th>
								<th scope="col">Jualan</th>
								<th scope="col">Modal</th>
								<th scope="col">Profit</th>
								<th scope="col">Subagent</th>
								<th scope="col">Panel</th>
								<th scope="col">Outlet</th>
								<th scope="col">Counter</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($selectedtransaction as $year => $transinyear) 
			        		    @foreach ($transinyear as $month => $transinmonth)
			        		    	@if($month == $desiredmonth)
			        		    		<?php 
			        		    			ksort($transinmonth);
			        		    		?>
				        		    	@foreach ($transinmonth as $day => $transinday) 
											<tr>
												<th scope="row">#</th>
												<td>{{$day}}</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_sell'))}}
												</td>
												<td>
													{{array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_modal'))}}
												</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_sell'))-array_sum(array_column($transinday['transactions_in_one_day']->toArray(), 'trans_total_modal'))}}</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_subagent')->toArray(), 'trans_total_sell'))-array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_subagent')->toArray(), 'trans_total_modal'))}}</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_panel')->toArray(), 'trans_total_sell'))-array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_panel')->toArray(), 'trans_total_modal'))}}</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_outlet')->toArray(), 'trans_total_sell'))-array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_outlet')->toArray(), 'trans_total_modal'))}}</td>
												<td>{{array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_counter')->toArray(), 'trans_total_sell'))-array_sum(array_column($transinday['transactions_in_one_day']->where('buyer_type','outstock_counter')->toArray(), 'trans_total_modal'))}}</td>
											</tr>
										@endforeach
									@endif
			        		    @endforeach
			        		@endforeach
						</tbody>
					</table>
			        @break

			    @default
			        <span>Something went wrong, please try again or year is missing</span>
			@endswitch
			
		</div>
	</div>
</div>


@endsection
@push('scripts')
<script>
	$(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });	
</script>

@endpush