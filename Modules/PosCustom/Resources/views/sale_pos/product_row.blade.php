	@php
		$common_settings = session()->get('business.common_settings');
		$multiplier = 1;

		$action = !empty($action) ? $action : '';
	@endphp

	@foreach($sub_units as $key => $value)
		@if(!empty($product->sub_unit_id) && $product->sub_unit_id == $key)
			@php
				$multiplier = $value['multiplier'];
			@endphp
		@endif
	@endforeach

	<tr class="product_row" data-row_index="{{$row_count}}" @if(!empty($so_line)) data-so_id="{{$so_line->transaction_id}}" @endif>
		<td >
			@if(!empty($so_line))
				<input type="hidden" 
				name="products[{{$row_count}}][so_line_id]" 
				value="{{$so_line->id}}">
			@endif
			@php
				$product_name = $product->product_name . '<br/>' . $product->sub_sku ;
				if(!empty($product->brand)){ $product_name .= ' ' . $product->brand ;}
			@endphp

			@if( ($edit_price || $edit_discount) && empty($is_direct_sell) )
			<div title="@lang('lang_v1.pos_edit_product_price_help')">
				<span class="text-link text-info cursor-pointer" data-toggle="modal" data-target="#row_edit_product_price_modal_{{$row_count}}" style="font-size: 18px; font-weight: bold;">
					{!! $product_name !!}
					&nbsp;<i class="fa fa-info-circle"></i>
				</span>
				
			</div>
			@else
				{!! $product_name !!}
			@endif
			<input type="hidden" class="enable_sr_no" value="{{$product->enable_sr_no}}">
			<input type="hidden" 
				class="product_type" 
				name="products[{{$row_count}}][product_type]" 
				value="{{$product->product_type}}">

			@php
				$hide_tax = 'hide';
				if(session()->get('business.enable_inline_tax') == 1){
					$hide_tax = '';
				}
				
				$tax_id = $product->tax_id;
				$item_tax = !empty($product->item_tax) ? $product->item_tax : 0;
				$unit_price_inc_tax = $product->sell_price_inc_tax;

				if($hide_tax == 'hide'){
					$tax_id = null;
					$unit_price_inc_tax = $product->default_sell_price;
				}

				if(!empty($so_line) && $action !== 'edit') {
					$tax_id = $so_line->tax_id;
					$item_tax = $so_line->item_tax;
					$unit_price_inc_tax = $so_line->unit_price_inc_tax;
				}

				$discount_type = !empty($product->line_discount_type) ? $product->line_discount_type : 'fixed';
				$discount_amount = !empty($product->line_discount_amount) ? $product->line_discount_amount : 0;
				
				if(!empty($discount)) {
					$discount_type = $discount->discount_type;
					$discount_amount = $discount->discount_amount;
				}

				if(!empty($so_line) && $action !== 'edit') {
					$discount_type = $so_line->line_discount_type;
					$discount_amount = $so_line->line_discount_amount;
				}

				$sell_line_note = '';
				if(!empty($product->sell_line_note)){
					$sell_line_note = $product->sell_line_note;
				}
				if(!empty($so_line)){
					$sell_line_note = $so_line->sell_line_note;
				}
			@endphp

			@if(!empty($discount))
				{!! Form::hidden("products[$row_count][discount_id]", $discount->id); !!}
			@endif

			@php
				$warranty_id = !empty($action) && $action == 'edit' && !empty($product->warranties->first())  ? $product->warranties->first()->id : $product->warranty_id;

				if($discount_type == 'fixed') {
					$discount_amount = $discount_amount * $multiplier;
				}
			@endphp

			@if(empty($is_direct_sell))
			<div class="modal fade row_edit_product_price_model" id="row_edit_product_price_modal_{{$row_count}}" tabindex="-1" role="dialog">
				@include('sale_pos.partials.row_edit_product_price_modal')
			</div>
			@endif

			<!-- Description modal end -->
			@if(in_array('modifiers' , $enabled_modules))
				<div class="modifiers_html">
					@if(!empty($product->product_ms))
						@include('restaurant.product_modifier_set.modifier_for_product', array('edit_modifiers' => true, 'row_count' => $loop->index, 'product_ms' => $product->product_ms ) )
					@endif
				</div>
			@endif

			@php
				$max_quantity = $product->qty_available;
				$formatted_max_quantity = $product->formatted_qty_available;

				if(!empty($action) && $action == 'edit') {
					if(!empty($so_line)) {
						$qty_available = $so_line->quantity - $so_line->so_quantity_invoiced + $product->quantity_ordered;
						$max_quantity = $qty_available;
						$formatted_max_quantity = number_format($qty_available, session('business.quantity_precision', 2), session('currency')['decimal_separator'], session('currency')['thousand_separator']);
					}
				} else {
					if(!empty($so_line) && $so_line->qty_available <= $max_quantity) {
						$max_quantity = $so_line->qty_available;
						$formatted_max_quantity = $so_line->formatted_qty_available;
					}
				}
				

				$max_qty_rule = $max_quantity;
				$max_qty_msg = __('validation.custom-messages.quantity_not_available', ['qty'=> $formatted_max_quantity, 'unit' => $product->unit  ]);
			@endphp

			@if( session()->get('business.enable_lot_number') == 1 || session()->get('business.enable_product_expiry') == 1)
			@php
				$lot_enabled = session()->get('business.enable_lot_number');
				$exp_enabled = session()->get('business.enable_product_expiry');
				$lot_no_line_id = '';
				if(!empty($product->lot_no_line_id)){
					$lot_no_line_id = $product->lot_no_line_id;
				}
			@endphp
			@if(!empty($product->lot_numbers) && empty($is_sales_order))
				<select class="form-control lot_number input-sm" name="products[{{$row_count}}][lot_no_line_id]" @if(!empty($product->transaction_sell_lines_id)) disabled @endif>
					<option value="">@lang('lang_v1.lot_n_expiry')</option>
					@foreach($product->lot_numbers as $lot_number)
						@php
							$selected = "";
							if($lot_number->purchase_line_id == $lot_no_line_id){
								$selected = "selected";

								$max_qty_rule = $lot_number->qty_available;
								$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ]);
							}

							$expiry_text = '';
							if($exp_enabled == 1 && !empty($lot_number->exp_date)){
								if( \Carbon::now()->gt(\Carbon::createFromFormat('Y-m-d', $lot_number->exp_date)) ){
									$expiry_text = '(' . __('report.expired') . ')';
								}
							}

							//preselected lot number if product searched by lot number
							if(!empty($purchase_line_id) && $purchase_line_id == $lot_number->purchase_line_id) {
								$selected = "selected";

								$max_qty_rule = $lot_number->qty_available;
								$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ]);
							}
						@endphp
						<option value="{{$lot_number->purchase_line_id}}" data-qty_available="{{$lot_number->qty_available}}" data-msg-max="@lang('lang_v1.quantity_error_msg_in_lot', ['qty'=> $lot_number->qty_formated, 'unit' => $product->unit  ])" {{$selected}}>@if(!empty($lot_number->lot_number) && $lot_enabled == 1){{$lot_number->lot_number}} @endif @if($lot_enabled == 1 && $exp_enabled == 1) - @endif @if($exp_enabled == 1 && !empty($lot_number->exp_date)) @lang('product.exp_date'): {{@format_date($lot_number->exp_date)}} @endif {{$expiry_text}}</option>
					@endforeach
				</select>
			@endif
		@endif
		@if(!empty($is_direct_sell))
			<br>
			<textarea class="form-control" name="products[{{$row_count}}][sell_line_note]" rows="2">{{$sell_line_note}}</textarea>
			<p class="help-block"><small>@lang('lang_v1.sell_line_description_help')</small></p>
		@endif
		</td>
		<td class="text-center">
			<input type="text" disabled name="products[{{$row_count}}][qty_available]" class="form-control stock_quantity input_number" value="{{@num_format($product->qty_available)}}" readonly>
		</td>
		<td>
			{{-- الحقول المخفية --}}
			@if(!empty($product->transaction_sell_lines_id))
				<input type="hidden" name="products[{{$row_count}}][transaction_sell_lines_id]" value="{{$product->transaction_sell_lines_id}}">
			@endif
		
			<input type="hidden" name="products[{{$row_count}}][product_id]" value="{{$product->product_id}}">
			<input type="hidden" value="{{$product->variation_id}}" name="products[{{$row_count}}][variation_id]">
			<input type="hidden" value="{{$product->enable_stock}}" name="products[{{$row_count}}][enable_stock]">
		
			@php
				$allow_decimal = $product->unit_allow_decimal == 1;
				$max_qty_rule = $max_qty_rule / $multiplier;
				$unit_name = $product->unit;
				$max_qty_msg = __('validation.custom-messages.quantity_not_available', ['qty' => $max_qty_rule, 'unit' => $unit_name]);
				if (!empty($product->lot_no_line_id)) {
					$max_qty_msg = __('lang_v1.quantity_error_msg_in_lot', ['qty' => $max_qty_rule, 'unit' => $unit_name]);
				}
			@endphp
			{{-- مجموعة إدخال الكمية والوحدة --}}
			<div class="input-group" style="width: 150px;">
				<input type="number" 
					class="form-control pos_quantity input-sm" 
					name="products[{{$row_count}}][quantity]" 
					value="1" 
					min="1" 
					style="width: 60px;"
					data-allow-overselling="@if(empty($pos_settings['allow_overselling'])){{'false'}}@else{{'true'}}@endif" 
					@if($allow_decimal)
						step="0.01"
					@else
						step="1"
						data-rule-abs_digit="true" 
						data-msg-abs_digit="@lang('lang_v1.decimal_value_not_allowed')" 
					@endif
					data-rule-required="true" 
					data-msg-required="@lang('validation.custom-messages.this_field_is_required')" 
					@if($product->enable_stock && empty($pos_settings['allow_overselling']))
						data-rule-max-value="{{$max_qty_rule}}" 
						data-qty_available="{{$product->qty_available}}" 
						data-msg-max-value="{{$max_qty_msg}}" 
					@endif 
					data-qty-available="{{ $product->qty_available }}" 
					placeholder="الكمية"
					oninput="checkQuantity(this)"
				>
				<select name="products[{{$row_count}}][sub_unit_id]" class="form-control input-sm" style="width: 90px;">
					@foreach($sub_units as $key => $value)
						<option value="{{$key}}" 
								data-multiplier="{{$value['multiplier']}}" 
								data-unit_name="{{$value['name']}}" 
								data-allow_decimal="{{$value['allow_decimal']}}" 
								@if(!empty($product->sub_unit_id) && $product->sub_unit_id == $key) selected @endif>
							{{$value['name']}}
						</option>
					@endforeach
				</select>
			</div>
			<small class="text-danger qty-warning" style="display: none;">
				الكمية المدخلة تتجاوز الكمية المتوفرة في المخزون ({{ @num_format($product->qty_available) }})
			</small>
			{{-- الحقول المخفية الإضافية --}}
			<input type="hidden" name="products[{{$row_count}}][product_unit_id]" value="{{$product->unit_id}}">
			<input type="hidden" class="base_unit_multiplier" name="products[{{$row_count}}][base_unit_multiplier]" value="{{$multiplier}}">
			<input type="hidden" class="hidden_base_unit_sell_price" value="{{$product->default_sell_price / $multiplier}}">
		</td>
			
		@if(!empty($is_direct_sell))
			@if(!empty($pos_settings['inline_service_staff']))
				<td>
					<div class="form-group">
						<div class="input-group">
							{!! Form::select("products[" . $row_count . "][res_service_staff_id]", $waiters, !empty($product->res_service_staff_id) ? $product->res_service_staff_id : null, ['class' => 'form-control select2 order_line_service_staff', 'placeholder' => __('restaurant.select_service_staff'), 'required' => (!empty($pos_settings['is_service_staff_required']) && $pos_settings['is_service_staff_required'] == 1) ? true : false ]); !!}
						</div>
					</div>
				</td>
			@endif
			
			

			@php
				$pos_unit_price = !empty($product->unit_price_before_discount) ? $product->unit_price_before_discount : $product->default_sell_price;

				if(!empty($so_line) && $action !== 'edit') {
					$pos_unit_price = $so_line->unit_price_before_discount;
				}
			@endphp
			<td class="@if(!auth()->user()->can('edit_product_price_from_sale_screen')) hide @endif">
				<input type="text" name="products[{{$row_count}}][unit_price]" class="form-control pos_unit_price input_number mousetrap" value="{{@num_format($pos_unit_price)}}" @if(!empty($pos_settings['enable_msp'])) data-rule-min-value="{{$pos_unit_price}}" data-msg-min-value="{{__('lang_v1.minimum_selling_price_error_msg', ['price' => @num_format($pos_unit_price)])}}" @endif> 

				@if(!empty($last_sell_line))
					<br>
					<small class="text-muted">@lang('lang_v1.prev_unit_price'): @format_currency($last_sell_line->unit_price_before_discount)</small>
				@endif
			</td>

			<td @if(!$edit_discount) class="hide" @endif>
				<div class="input-group" style="width: 150px;">
					<!-- حقل إدخال قيمة الخصم -->
					{!! Form::text(
						"products[$row_count][line_discount_amount]",
						@num_format($discount_amount),
						[
							'class' => 'form-control input-sm input_number row_discount_amount input-sm',
							'placeholder' => __('lang_v1.discount_amount'),
							'style' => 'border-top-right-radius: 0; border-bottom-right-radius: 0;width: 60px;'
						]
					); !!}
			
					<!-- قائمة اختيار نوع الخصم -->
					{!! Form::select(
						"products[$row_count][line_discount_type]",
						['fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')],
						$discount_type,
						[
							'class' => 'form-control input-sm row_discount_type',
							'style' => 'border-top-left-radius: 0; border-bottom-left-radius: 0;width: 90px;'
						]
					); !!}
				</div>
			
				<!-- عرض نصوص إضافية -->
				@if(!empty($discount))
					<p class="help-block mt-1">
						{!! __('lang_v1.applied_discount_text', [
							'discount_name' => $discount->name,
							'starts_at' => $discount->formated_starts_at,
							'ends_at' => $discount->formated_ends_at
						]) !!}
					</p>
				@endif
			
				@if(!empty($last_sell_line))
					<small class="text-muted">
						@lang('lang_v1.prev_discount'): 
						@if($last_sell_line->line_discount_type == 'percentage')
							{{@num_format($last_sell_line->line_discount_amount)}}%
						@else
							@format_currency($last_sell_line->line_discount_amount)
						@endif
					</small>
				@endif
			</td>
			
			
			<td class="text-center {{$hide_tax}}">
				{!! Form::hidden("products[$row_count][item_tax]", @num_format($item_tax), ['class' => 'item_tax']); !!}
			
				{!! Form::select("products[$row_count][tax_id]", $tax_dropdown['tax_rates'], $tax_id, ['placeholder' => 'Select', 'class' => 'form-control tax_id'], $tax_dropdown['attributes']); !!}
			</td>

		@else
		
			@if(!empty($pos_settings['inline_service_staff']))
				<td>
					<div class="form-group">
						<div class="input-group">
							{!! Form::select("products[" . $row_count . "][res_service_staff_id]", $waiters, !empty($product->res_service_staff_id) ? $product->res_service_staff_id : null, ['class' => 'form-control select2 order_line_service_staff', 'placeholder' => __('restaurant.select_service_staff'), 'required' => (!empty($pos_settings['is_service_staff_required']) && $pos_settings['is_service_staff_required'] == 1) ? true : false ]); !!}
						</div>
					</div>
				</td>
			@endif
		@endif
		
		<td class="{{$hide_tax}}">
			<input type="text" name="products[{{$row_count}}][unit_price_inc_tax]"
			class="form-control pos_unit_price_inc_tax input_number"
			data-original-price="{{@num_format($unit_price_inc_tax)}}"
			value="{{@num_format($unit_price_inc_tax)}}" readonly>
			</td>
		@if(!empty($common_settings['enable_product_warranty']) && !empty($is_direct_sell))
			<td>
				{!! Form::select("products[$row_count][warranty_id]", $warranties, $warranty_id, ['placeholder' => __('messages.please_select'), 'class' => 'form-control']); !!}
			</td>
		@endif
		
		<td class="">
			<div class="input-group" style="width: 160px;">
				
		
				<!-- حقل إدخال قيمة الخصم -->
	<input type="text" 
	name="products[{{$row_count}}][discount_value]" 
	class="form-control discount_value_input input_number" 
	value="{{@num_format($discount_amount)}}" 
	
	style="width: 65px;">

	<!-- قائمة اختيار نوع الخصم -->
	<select name="products[{{$row_count}}][discount_method]" 
	class="form-control input-sm discount_method_select" 
	
	style="width: 95px;">

	<option value="percentage" 
		@if($discount_type === 'percentage') selected @endif>
	خصم بالنسبة المئوية
	</option>
	<option value="fixed" 
		@if($discount_type === 'fixed') selected @endif>
	خصم ثابت
	</option>
	</select>


			</div>
		</td>
		
		
		<td class="">
			<input type="text" name="products[{{$row_count}}][unit_price_inc_tax]"
			class="form-control pos_unit_price_inc_tax input_number"
			data-original-price="{{@num_format($unit_price_inc_tax)}}"
			value="{{@num_format($unit_price_inc_tax)}}" readonly>
			</td>
		
		
			<td class="">
				@php
					$subtotal_type = !empty($pos_settings['is_pos_subtotal_editable']) ? 'text' : 'hidden';
		
				@endphp
				<input type="{{$subtotal_type}}" class="form-control pos_line_total pt-10 @if(!empty($pos_settings['is_pos_subtotal_editable'])) input_number @endif" value="{{@num_format($product->quantity_ordered*$unit_price_inc_tax )}}">
				<span class="display_currency pos_line_total_text pt-10 @if(!empty($pos_settings['is_pos_subtotal_editable'])) hide @endif" data-currency_symbol="true">{{$product->quantity_ordered*$unit_price_inc_tax}}</span>
			</td>

		
		<td class="">
			<i class="fa fa-times text-danger pos_remove_row cursor-pointer" aria-hidden="true"></i>
		</td>
		
	</tr>

	<script>
	function checkQuantity(input) {
		// الحصول على الكمية المتاحة
		const qtyAvailable = parseFloat(input.getAttribute('data-qty-available')) || 0;
		// الكمية المدخلة
		const qtyEntered = parseFloat(input.value) || 0;

		// إيجاد رسالة التحذير المرتبطة بالحقل
		const warningMessage = input.closest('td').querySelector('.qty-warning');

		if (qtyEntered > qtyAvailable) {
			// عرض التحذير إذا تجاوزت الكمية المدخلة الكمية المتاحة
			warningMessage.style.display = 'block';
		} else {
			// إخفاء التحذير إذا كانت الكمية المدخلة ضمن المتاح
			warningMessage.style.display = 'none';
		}
	}
	$(document).ready(function () {
		// عند تغيير أي قيمة تتعلق بالخصم أو الكمية
		$('table#pos_table tbody').on('input change', 'input.discount_value_input, select.discount_method_select, input.pos_quantity', function () {
			updateRow($(this).closest('tr')); // تحديث الصف الحالي
		});

		// تحديث صف محدد
		function updateRow(tr) {
			// الحصول على القيم المطلوبة
			var discountMethod = tr.find('select.discount_method_select').val(); // نوع الخصم
			var discountValue = parseFloat(tr.find('input.discount_value_input').val()) || 0; // قيمة الخصم
			var unitPrice = parseFloat(tr.find('input.pos_unit_price_inc_tax').data('original-price')) || 0; // السعر الأصلي
			var quantity = parseFloat(tr.find('input.pos_quantity').val()) || 1; // الكمية

			// التأكد من أن الكمية لا تكون أقل من 1
			quantity = Math.max(quantity, 1);

			// حساب إجمالي المبلغ قبل الخصم
			var totalBeforeDiscount = unitPrice * quantity;

			// حساب الخصم بناءً على نوعه
			var discountAmount = 0;
			if (discountMethod === 'percentage') {
				discountAmount = totalBeforeDiscount * (discountValue / 100); // خصم بنسبة مئوية
			} else if (discountMethod === 'fixed') {
				discountAmount = discountValue; // خصم ثابت
			}

			// التأكد من أن الخصم لا يتجاوز المجموع
			discountAmount = Math.min(discountAmount, totalBeforeDiscount);

			// حساب المجموع النهائي بعد الخصم
			var totalAfterDiscount = totalBeforeDiscount - discountAmount;

			// التأكد من أن الإجمالي النهائي غير سالب
			totalAfterDiscount = Math.max(totalAfterDiscount, 0);

			// تحديث الحقول في الصف
			tr.find('input.pos_line_total').val(totalAfterDiscount.toFixed(2)); // تحديث إجمالي الصف
			tr.find('span.pos_line_total_text').text(totalAfterDiscount.toFixed(2)); // عرض المجموع النصي للصف
			tr.find('input.pos_unit_price_inc_tax').val(unitPrice.toFixed(2)); // الاحتفاظ بسعر الوحدة كما هو
		}

	
	});


	</script>