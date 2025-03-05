<!-- Edit Order tax Modal -->
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">@lang('lang_v1.suspended_sales')</h4>
		</div>
		<div class="modal-body">
			<div class="row mb-3">
				<form class="col-sm-4" method="get" action="{{route('sells.index')}}" id="location-filter-form">
					<input type="hidden" name="suspended" value="1">
					<div class="form-group">
						<label>@lang('lang_v1.Branch'): </label>
						<div class="input-group">
							<select class="select2 form-control" name="location_id">
								@foreach($locations as $location)
									<option @selected(request('location_id') == $location->id) value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
							<span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat search"><i class="fa fa-search text-primary fa-lg"></i></button>
							</span>
						</div>
					</div>
				</form>
			</div>
			<div class="row">
				@php
					$c = 0;
					$subtype = '';
				@endphp
				@if(!empty($transaction_sub_type))
					@php
						$subtype = '?sub_type='.$transaction_sub_type;
					@endphp
				@endif
				@forelse($sales as $sale)
					@if($sale->is_suspend)
						<div class="col-xs-6 col-sm-3">
							<div class="small-box bg-yellow">
					            <div class="inner text-center">
						            @if(!empty($sale->additional_notes))
						            	<p><i class="fa fa-edit"></i> {{$sale->additional_notes}}</p>
						            @endif
					              <p>{{$sale->invoice_no}}<br>
					              {{@format_date($sale->transaction_date)}}<br>
					              <strong><i class="fa fa-user"></i> {{$sale->name}}</strong></p>
					              <p><i class="fa fa-cubes"></i>@lang('lang_v1.total_items'): {{count($sale->sell_lines)}}<br>
					              <i class="fas fa-money-bill-alt"></i> @lang('sale.total'): <span class="display_currency" data-currency_symbol=true>{{$sale->final_total}}</span>
					              </p>
					              @if($is_tables_enabled && !empty($sale->table->name))
					              	@lang('restaurant.table'): {{$sale->table->name}}
					              @endif
					              @if($is_service_staff_enabled && !empty($sale->service_staff))
					              	<br>@lang('restaurant.service_staff'): {{$sale->service_staff->user_full_name}}
					              @endif
					            </div>
								@if(auth()->user()->can('sell.update') || auth()->user()->can('direct_sell.update'))
									<a href="{{action([\App\Http\Controllers\SellPosController::class, 'edit'], ['po' => $sale->id]).$subtype}}" class="small-box-footer bg-blue p-10">
									@lang('sale.edit_sale') <i class="fa fa-arrow-circle-right"></i>
									</a>
								@endif
								@if(auth()->user()->can('sell.delete') || auth()->user()->can('direct_sell.delete'))
									<a href="{{action([\App\Http\Controllers\SellPosController::class, 'destroy'], ['po' => $sale->id])}}" class="small-box-footer delete-sale bg-red is_suspended">
										@lang('messages.delete') <i class="fas fa-trash"></i>
					            	</a>
								@endif
								@if(!auth()->user()->can('sell.update') && auth()->user()->can('edit_pos_payment'))
									<a href="{{route('edit-pos-payment', ['po' => $sale->id])}}"
									class="small-box-footer bg-blue p-10">
									@lang('lang_v1.add_edit_payment') <i class="fas fa-money-bill-alt"></i>
									</a>
								@endif
					         </div>
				         </div>
				        @php
				         	$c++;
				        @endphp
					@endif

					@if($c%4==0)
						<div class="clearfix"></div>
					@endif
				@empty
					<p class="text-center">@lang('purchase.no_records_found')</p>
				@endforelse
			</div>
		</div>
		<div class="modal-footer">
		    <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">@lang('messages.close')</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
	$(function(){
		$("#location-filter-form .search").on('click', function(e){
			e.preventDefault();
			const form = $(this).closest("form");
			$.ajax({
				url: form.attr('action'),
				data: form.serialize(),
				success: function (data){
					$(".modal-dialog").parent().html(data);
				}
			})
		})
	})
</script>