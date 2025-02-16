<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #000;
        }
        .container {
            width: 70%;
            margin: auto;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .logo img {
            max-height: 120px;
            width: auto;
        }
        .hm-p {
            line-height: 1.5;
            font-size: 14px;
            color: #4a4a4a;
        }
        .invoice-type {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <!-- Logo and Header Section -->
            @if(empty($receipt_details->letter_head))
            <div>
                @if(!empty($receipt_details->logo))
                <div class="logo">
                    <img src="{{$receipt_details->logo}}" alt="Logo">
                </div>
                @endif
<!-- اللوجو -->
@if(!empty(session('business.logo')))
    <div style="display: flex; justify-content: flex-end; padding: 10px; direction: ltr;">
        <img src="{{ url('uploads/business_logos/' . session('business.logo')) }}" 
             alt="Logo" 
             style="width: 50px; height: 50px; object-fit: contain;">
    </div>
@endif

<!-- تفاصيل المنشأة -->
<div class="hm-p" style="text-align: center; font-size: 16px;">
    <p style="font-size: 20px;" class="bold">مصنع إنطلاقة فاشون للملابس الجاهزة</p>

    @if(!empty($receipt_details->address))
        <p><i class="fas fa-map-marker-alt"></i> شارع حوش النخلة رقم 12 - درب السماكين - الحسنية - القاهرة</p>
    @endif

    @if(!empty($receipt_details->sub_heading_line2))
        <p><i class="fas fa-phone"></i> {{ $receipt_details->sub_heading_line2 }}</p>
    @else
        <p><i class="fas fa-phone"></i> رقم الهاتف: 01002776259</p>
        <p><i class="fab fa-whatsapp"></i> واتس اب: 01270538787</p>
    @endif

    @if(!empty($receipt_details->sub_heading_line3))
        <p>{{ $receipt_details->sub_heading_line3 }}</p>
    @endif
</div>


<!-- تضمين Font Awesome للأيقونات -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



                <!-- Invoice Type -->
                <div class="invoice-type">
                    @if(!empty($receipt_details->total_due))
                    <h4>فاتورة مبيعات أجل</h4>
                    @elseif(!empty($receipt_details->total_paid))
                    <h4>فاتورة نقدية</h4>
                    @else
                    <h4>عرض سعر</h4>
                    @endif
                </div>

                <!-- Invoice Details -->
                <table class="table">
                    <tr>
                        <td style="width: 30%;">رقم الفاتورة:</td>
                        <td>{{$receipt_details->invoice_no}}</td>
                    </tr>
                    <tr>
                        <td>تاريخ الفاتورة:</td>
                        <td>{{$receipt_details->invoice_date}}</td>
                    </tr>
                    <tr>
                        <td>اسم العميل:</td>
                        <td>{!! $receipt_details->customer_name !!}</td>
                    </tr>
                </table>

                @if(!empty($receipt_details->sales_person_label))
                <br/>
                <b>اعدها لك :</b> {{ $receipt_details->sales_person }}
                @endif

            </div>
            @else
            <div class="text-center">
                <img style="width: 100%; margin-bottom: 10px;" src="{{$receipt_details->letter_head}}" alt="Letter Head">
            </div>
            @endif
        </div>

		
    </div>
	
<div class="row" style="color: #000000 !important;">
	@includeIf('sale_pos.receipts.partial.common_repair_invoice')
	</div>
	
	<div class="row" style="color: #000000 !important;">
	<div class="col-xs-12">
		<br/>
		@php
			$p_width = 20;
		@endphp
		@if(!empty($receipt_details->item_discount_label))
			@php
				$p_width -= 10;
			@endphp
		@endif
		@if(!empty($receipt_details->discounted_unit_price_label))
			@php
				$p_width -= 10;
			@endphp
		@endif
		<table class="table table-bordered table-responsive" style="border:0 !important;">
		<tr>
    <td style="background-color: #59a7fb !important; font-size: 14px; color: #fff !important; width: 0.2% !important; text-align: center;">
        S.N
    </td>

    <td style="background-color: #F1F2F2 !important; color: #000 !important; font-weight: bold;" class="text-center" width="30%">الصنف <br></td>

    <td style="background-color: #F1F2F2 !important;" class="text-center" width="15%">الكمية <br></td>
    
    <td style="background-color: #F1F2F2 !important;" class="text-center" width="15%">السعر <br></td>
    
    @if(!empty($receipt_details->discounted_unit_price_label))
        <td class="text-center" width="10%">{{$receipt_details->discounted_unit_price_label}}</td>
    @endif
    
    @if(!empty($receipt_details->item_discount_label))
        <td style="background-color: #F1F2F2 !important;" class="text-center" width="10%"> {{$receipt_details->item_discount_label}}</td>
    @endif

    <td style="background-color: #F1F2F2 !important;" class="text-center" width="15%">المجموع الفرعي<br></td>
</tr>

			<tbody>
				@forelse($receipt_details->lines as $line)
					<tr>
						<td style="background-color: #F1F2F2 !important;" class="text-center">
							{{$loop->iteration}}
						</td>
						<td>
							@if(!empty($line['image']))
								<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
							@endif
							{{$line['name']}} {{$line['product_variation']}} {{$line['variation']}} 
							@if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
							@if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
							@if(!empty($line['product_description']))
								<small>
									{!!$line['product_description']!!}
								</small>
							@endif 
							@if(!empty($line['sell_line_note']))
							<br>
							<small>
								{!!$line['sell_line_note']!!}
							</small>
							@endif 
							@if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
							@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif
	
							@if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
							@if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif
	
							@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
							<br><small>
								1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
								{{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
							</small>
							@endif
						</td>
						<td class="text-center">
							{{$line['quantity']}} {{$line['units']}} 
	
							@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
							<br><small>
								{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}
							</small>
							@endif
						</td>
						<td class="text-center">{{$line['unit_price_before_discount']}}</td>
						@if(!empty($receipt_details->discounted_unit_price_label))
							<td class="text-center">
								{{$line['unit_price_inc_tax']}} 
							</td>
						@endif
						@if(!empty($receipt_details->item_discount_label))
							<td class="text-center">
								{{$line['total_line_discount'] ?? '0.00'}}
	
								@if(!empty($line['line_discount_percent']))
									 ({{$line['line_discount_percent']}}%)
								@endif
							</td>
						@endif
						<td class="text-center">{{$line['line_total']}}</td>
					</tr>
					@if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td>
									{{$modifier['name']}} {{$modifier['variation']}} 
									@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
									@if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif 
								</td>
								<td class="text-right">{{$modifier['quantity']}} {{$modifier['units']}} </td>
								<td class="text-right">{{$modifier['unit_price_inc_tax']}}</td>
								@if(!empty($receipt_details->discounted_unit_price_label))
									<td class="text-right">{{$modifier['unit_price_exc_tax']}}</td>
								@endif
								@if(!empty($receipt_details->item_discount_label))
									<td class="text-right">0.00</td>
								@endif
								<td class="text-right">{{$modifier['line_total']}}</td>
							</tr>
						@endforeach
					@endif
				@empty
					<tr>
						<td colspan="4">&nbsp;</td>
						@if(!empty($receipt_details->discounted_unit_price_label))
						<td></td>
						@endif
						@if(!empty($receipt_details->item_discount_label))
						<td></td>
						@endif
					</tr>
				@endforelse
			</tbody>
			@if(!empty($receipt_details->total_quantity_label))
						<tr>
							<td class="text-right" colspan="4" style="border:0 !important;">
								{!! $receipt_details->total_quantity_label !!}
							</td>
							<td class="text-center">
								{{$receipt_details->total_quantity}}
							</td>
						</tr>
					@endif
	
					@if(!empty($receipt_details->total_items_label))
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->total_items_label !!}
							</td>
							<td class="text-center">
								{{$receipt_details->total_items}}
							</td>
						</tr>
					@endif
					@if(!empty($receipt_details->total_due))
					<tr>
						<td class='text-right' colspan="4" style="border:0 !important;">
							الاجمالي
						</td>
						<td class="text-center">
							{{$receipt_details->subtotal}}
						</td>
					</tr>
					@endif
					@if(!empty($receipt_details->total_exempt_uf))
					<tr>
						<td class='text-right' colspan="4" style="border:0 !important;">
							@lang('lang_v1.exempt')
						</td>
						<td class="text-center">
							{{$receipt_details->total_exempt}}
						</td>
					</tr>
					@endif
					<!-- Shipping Charges -->
					@if(!empty($receipt_details->shipping_charges))
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->shipping_charges_label !!}
							</td>
							<td class="text-center">
								{{$receipt_details->shipping_charges}}
							</td>
						</tr>
					@endif
	
					@if(!empty($receipt_details->packing_charge))
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->packing_charge_label !!}
							</td>
							<td class="text-center">
								{{$receipt_details->packing_charge}}
							</td>
						</tr>
					@endif
	
					<!-- Discount -->
					@if( !empty($receipt_details->discount) )
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->discount_label !!}
							</td>
	
							<td class="text-center">
								(-) {{$receipt_details->discount}}
							</td>
						</tr>
					@endif
	
					@if( !empty($receipt_details->total_line_discount) )
						<tr>
							<td class="text-right" colspan="4" style="border:0 !important;">
								{!! $receipt_details->line_discount_label !!}
							</td>
	
							<td class="text-center">
								(-) {{$receipt_details->total_line_discount}}
							</td>
						</tr>
					@endif
	
					@if( !empty($receipt_details->additional_expenses) )
						@foreach($receipt_details->additional_expenses as $key => $val)
							<tr>
								<td class="text-right" colspan="4" style="border:0 !important;">
									{{$key}}:
								</td>
	
								<td class="text-center">
									(+) {{$val}}
								</td>
							</tr>
						@endforeach
					@endif
	
					@if( !empty($receipt_details->reward_point_label) )
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->reward_point_label !!}
							</td>
	
							<td class="text-center">
								(-) {{$receipt_details->reward_point_amount}}
							</td>
						</tr>
					@endif
	
					<!-- Tax -->
					@if( !empty($receipt_details->tax) )
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->tax_label !!}
							</td>
							<td class="text-center">
								(+) {{$receipt_details->tax}}
							</td>
						</tr>
					@endif
	
					@if( $receipt_details->round_off_amount > 0)
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								{!! $receipt_details->round_off_label !!}
							</td>
							<td class="text-center">
								{{$receipt_details->round_off}}
							</td>
						</tr>
					@endif
	
					<!-- Total -->
					@if( $receipt_details->discount)
					<tr>
						<td class='text-right' colspan="4" style="border:0 !important;">
							{!! $receipt_details->total_label !!}
						</td>
						<td class="text-center">
							{{$receipt_details->total}}
							@if(!empty($receipt_details->total_in_words))
								<br>
								<small>({{$receipt_details->total_in_words}})</small>
							@endif
						</td>
					</tr>
					@endif
	
					<!-- Total Paid-->
					@if(!empty($receipt_details->total_paid))
						<tr>
							<td class='text-right' colspan="4" style="border:0 !important;">
								الاجمالي المدفوع
							</td>
							<td class="text-center">
								{{$receipt_details->total_paid}}
							</td>
						</tr>
					@endif
	
					<!-- Total Due-->
					@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
					<tr>
						<td class='text-right' colspan="4" style="border:0 !important;">
							الاجمالي المستحق
						</td>
						<td class="text-center" style="color:red ! important;">
							{{$receipt_details->total_due}}
						</td>
					</tr>
					@endif
	
					@if(!empty($receipt_details->all_due))
					<tr>
						<td class='text-right' colspan="4" style="border:0 !important;">
							{!! $receipt_details->all_bal_label !!}
						</td>
						<td class="text-center">
							{{$receipt_details->all_due}}
						</td>
					</tr>
					@endif
		</table>
	</div>
	</div>
					
				
	{{-- <div class="col-xs-6" style="margin-top: -80px !important;">
		<table class="table table-slim">
			@if(!empty($receipt_details->payments))
				@foreach($receipt_details->payments as $payment)
					<tr>
						<td class="text-left" style="border:0px !important;">{{$payment['method']}}</td>
						<td class="text-center" style="border:0px !important;">{{$payment['amount']}}</td>
						<td class="text-left" style="border:0px !important;">{{$payment['date']}}</td>
					</tr>
				@endforeach
			@endif
		</table>
	</div> --}}
	
	<div class="border-bottom col-md-12">
		@if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
			<!-- tax -->
			@if(!empty($receipt_details->taxes))
				<table class="table table-slim table-bordered">
					<tr>
						<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
					</tr>
					@foreach($receipt_details->taxes as $key => $val)
						<tr>
							<td class="text-center"><b>{{$key}}</b></td>
							<td class="text-center">{{$val}}</td>
						</tr>
					@endforeach
				</table>
			@endif
		@endif
	</div>
	
	{{-- @if(!empty($receipt_details->additional_notes))
		<div class="col-xs-12">
			<p>{!! nl2br($receipt_details->additional_notes) !!}</p>
		</div>
	@endif --}}
	</div>



	@if(empty($receipt_details->total_due) && empty($receipt_details->total_paid))
    {{-- عرض سعر --}}

@else
    {{-- فاتورة مستحقة أو مدفوعة --}}
    @if(!empty($receipt_details->footer_text))
        <div class="@if($receipt_details->show_barcode || $receipt_details->show_qr_code) col-xs-8 @else col-xs-12 @endif" style="text-align: center; margin-top: 10px;">
            {!! $receipt_details->footer_text !!}
        </div>
    @endif
@endif

@if($receipt_details->show_barcode || $receipt_details->show_qr_code)
    <div class="@if(!empty($receipt_details->footer_text)) col-xs-4 @else col-xs-12 @endif text-center" style="text-align: center !important;">
        @if($receipt_details->show_barcode)
            {{-- Barcode --}}
            <img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2, 30, array(39, 48, 54), true)}}">
        @endif

        @if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
            <img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE', 3, 3, [39, 48, 54])}}">
        @endif
    </div>
@endif


	
</body>
</html>

  




	{{-- <!-- Title of receipt -->
		@if(!empty($receipt_details->invoice_heading))
			<h3 class="text-center">
				{!! $receipt_details->invoice_heading !!}
			</h3>
		@endif
 --}}



















{{-- <div class="col-xs-12 text-center"> --}}
	{{-- <!-- Invoice  number, Date  -->
	<p style="width: 100% !important" class="word-wrap">
		<span class="pull-left text-left word-wrap">
			<!-- customer info -->
			<b style="font-size:18px; color:#403e3e !important;"> <i class="fa fas fa-users"></i> {{ $receipt_details->customer_label }}</b> 
			<br/>
			<b>{{$receipt_details->date_label}}</b> {{$receipt_details->invoice_date}}

			@if(!empty($receipt_details->customer_info))
				<br/>
				<b>- ផ្ញើជូន (Send To) :</b>  {!! $receipt_details->customer_name !!}<br/>
				<b>- ទូរស័ព្ទ (Tel) :</b>  {!! $receipt_details->customer_mobile !!}<br/>
				<b>- ទីតាំង (Add) :</b>  
				{{-- {!! $receipt_details->address_line_1 !!} --}}
			{{-- @endif
	
			@if(!empty($receipt_details->client_id_label))
				<br/>
				<b>{{ $receipt_details->client_id_label }}</b> {{ $receipt_details->client_id }}
			@endif
			@if(!empty($receipt_details->customer_tax_label))
				<br/>
				<b>{{ $receipt_details->customer_tax_label }}</b> {{ $receipt_details->customer_tax_number }}
			@endif
			@if(!empty($receipt_details->customer_custom_fields))
				<br/>{!! $receipt_details->customer_custom_fields !!}
			@endif
			@if(!empty($receipt_details->commission_agent_label))
				<br/>
				<strong>{{ $receipt_details->commission_agent_label }}</strong> {{ $receipt_details->commission_agent }}
			@endif
			@if(!empty($receipt_details->customer_rp_label))
				<br/>
				<strong>{{ $receipt_details->customer_rp_label }}</strong> {{ $receipt_details->customer_total_rp }}
			@endif --}}
			{{-- @if(!empty($receipt_details->types_of_service))
				<span class="pull-left text-left">
					<strong>{!! $receipt_details->types_of_service_label !!}:</strong>
					{{$receipt_details->types_of_service}} --}}
					<!-- Waiter info -->
					{{-- @if(!empty($receipt_details->types_of_service_custom_fields))
						@foreach($receipt_details->types_of_service_custom_fields as $key => $value)
							<br><strong>{{$key}}: </strong> {{$value}}
						@endforeach
					@endif
				</span>
			@endif --}}
			<!-- Table information-->
			{{-- @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
				<br/>
				<span class="pull-left text-left">
					@if(!empty($receipt_details->table_label))
						<b>{!! $receipt_details->table_label !!}</b>
					@endif
					{{$receipt_details->table}}

					<!-- Waiter info -->
				</span>
			@endif
		</span> --}}
		{{-- <span class="pull-right text-left"><br>
			@if(!empty($receipt_details->invoice_no_prefix))
				<b>{!! $receipt_details->invoice_no_prefix !!}</b>
			@endif
			
			<b style="color:red !important;"> {{$receipt_details->invoice_no}} </b>
			
			@if(!empty($receipt_details->due_date_label))
				<br><b>{{$receipt_details->due_date_label}}</b> {{$receipt_details->due_date ?? ''}}
			@endif
			
	
			
			
			@if(!empty($receipt_details->sales_person_label))
				<br/>
				<b>{{ $receipt_details->sales_person_label }}</b> {{ $receipt_details->sales_person }}
			@endif
			
			@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
				<br>
				@if(!empty($receipt_details->brand_label))
					<b>{!! $receipt_details->brand_label !!}</b>
				@endif
				{{$receipt_details->repair_brand}}
			@endif


			@if(!empty($receipt_details->device_label) || !empty($receipt_details->repair_device))
				<br>
				@if(!empty($receipt_details->device_label))
					<b>{!! $receipt_details->device_label !!}</b>
				@endif
				{{$receipt_details->repair_device}}
			@endif --}}

			{{-- @if(!empty($receipt_details->model_no_label) || !empty($receipt_details->repair_model_no))
				<br>
				@if(!empty($receipt_details->model_no_label))
					<b>{!! $receipt_details->model_no_label !!}</b>
				@endif
				{{$receipt_details->repair_model_no}}
			@endif

			@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
				<br>
				@if(!empty($receipt_details->serial_no_label))
					<b>{!! $receipt_details->serial_no_label !!}</b>
				@endif
				{{$receipt_details->repair_serial_no}}<br>
			@endif --}}
			{{-- @if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				@if(!empty($receipt_details->repair_status_label))
					<b>{!! $receipt_details->repair_status_label !!}</b>
				@endif
				{{$receipt_details->repair_status}}<br>
			@endif
			
			@if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
				@if(!empty($receipt_details->repair_warranty_label))
					<b>{!! $receipt_details->repair_warranty_label !!}</b>
				@endif
				{{$receipt_details->repair_warranty}}
				<br>
			@endif --}}
			
			<!-- Waiter info -->
			{{-- @if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
				<br/>
				@if(!empty($receipt_details->service_staff_label))
					<b>{!! $receipt_details->service_staff_label !!}</b>
				@endif
				{{$receipt_details->service_staff}}
			@endif
			@if(!empty($receipt_details->shipping_custom_field_1_label))
				<br><strong>{!!$receipt_details->shipping_custom_field_1_label!!} :</strong> {!!$receipt_details->shipping_custom_field_1_value ?? ''!!}
			@endif --}}
{{-- 
			@if(!empty($receipt_details->shipping_custom_field_2_label))
				<br><strong>{!!$receipt_details->shipping_custom_field_2_label!!}:</strong> {!!$receipt_details->shipping_custom_field_2_value ?? ''!!}
			@endif

			@if(!empty($receipt_details->shipping_custom_field_3_label))
				<br><strong>{!!$receipt_details->shipping_custom_field_3_label!!}:</strong> {!!$receipt_details->shipping_custom_field_3_value ?? ''!!}
			@endif --}}
{{-- 
			@if(!empty($receipt_details->shipping_custom_field_4_label))
				<br><strong>{!!$receipt_details->shipping_custom_field_4_label!!}:</strong> {!!$receipt_details->shipping_custom_field_4_value ?? ''!!}
			@endif

			@if(!empty($receipt_details->shipping_custom_field_5_label))
				<br><strong>{!!$receipt_details->shipping_custom_field_2_label!!}:</strong> {!!$receipt_details->shipping_custom_field_5_value ?? ''!!}
			@endif --}}
			{{-- sale order --}}
			{{-- @if(!empty($receipt_details->sale_orders_invoice_no)) --}}
				{{-- <br>
				<strong>@lang('restaurant.order_no'):</strong> {!!$receipt_details->sale_orders_invoice_no ?? ''!!}
			@endif

			@if(!empty($receipt_details->sale_orders_invoice_date))
				<br>
				<strong>@lang('lang_v1.order_dates'):</strong> {!!$receipt_details->sale_orders_invoice_date ?? ''!!}
			@endif

			@if(!empty($receipt_details->sell_custom_field_1_value))
				<br>
				<strong>{{ $receipt_details->sell_custom_field_1_label }}:</strong> {!!$receipt_details->sell_custom_field_1_value ?? ''!!}
			@endif

			@if(!empty($receipt_details->sell_custom_field_2_value))
				<br>
				<strong>{{ $receipt_details->sell_custom_field_2_label }}:</strong> {!!$receipt_details->sell_custom_field_2_value ?? ''!!}
			@endif

			@if(!empty($receipt_details->sell_custom_field_3_value))
				<br>
				<strong>{{ $receipt_details->sell_custom_field_3_label }}:</strong> {!!$receipt_details->sell_custom_field_3_value ?? ''!!}
			@endif

			@if(!empty($receipt_details->sell_custom_field_4_value))
				<br>
				<strong>{{ $receipt_details->sell_custom_field_4_label }}:</strong> {!!$receipt_details->sell_custom_field_4_value ?? ''!!}
			@endif

		</span>
	</p>
</div>
</div> --}}