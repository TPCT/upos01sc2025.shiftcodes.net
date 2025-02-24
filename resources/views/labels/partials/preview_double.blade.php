<table align="center" style="{{$barcode_details->col_distance * 1}}in {{$barcode_details->row_distance * 1}}in; overflow: hidden !important;">
	@foreach($page_products as $page_product)
		<tr>
			<td align="center" valign="center">
				<div style="overflow: hidden !important;display: flex; flex-wrap: wrap;align-content: center;width: {{$paper_width * 1}}in; height: {{$barcode_details->height * 1}}in">
					<div style="width: {{$paper_width}}in; height: {{$barcode_details->height * 0.5}}in !important;">
						<b style="display: block !important; font-size: {{$barcode_details->business_name_size}}px;text-align: right;direction: rtl;padding-right:10px">
							{{$print['business_name']}}
							<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
								@if($print['price_type'] == 'inclusive')
									{{@num_format($page_product->sell_price_inc_tax)}}
								@else
									{{@num_format($page_product->default_sell_price)}}
								@endif
							</span>
						</b>

						@if(!empty($print['name']))
							<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
								{{$page_product->product_actual_name}}
								@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
									({{$page_product->lot_number}})
								@endif
							</span>
						@endif

						@if(!empty($print['exp_date']) && !empty($page_product->exp_date))
							<span style="font-size: {{$barcode_details->exp_date_size}}px">
								<b>@lang('product.exp_date'):</b>
								{{$page_product->exp_date}}
							</span>
						@endif

						<img style="width:90% !important;height: {{$barcode_details->height*0.24}}in !important;"
							 src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
						<br>
					</div>
					<div style="width: {{$paper_width}}in; height: {{$barcode_details->height * 0.5}}in; !important;">
						<b style="display: block !important; font-size: {{$barcode_details->business_name_size}}px;text-align: right;direction: rtl;padding-right:10px">
							{{$print['business_name']}}
							<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
								@if($print['price_type'] == 'inclusive')
									{{@num_format($page_product->sell_price_inc_tax)}}
								@else
									{{@num_format($page_product->default_sell_price)}}
								@endif
							</span>
						</b>

						@if(!empty($print['name']))
							<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
								{{$page_product->product_actual_name}}
								@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
									({{$page_product->lot_number}})
								@endif
							</span>
						@endif

						@if(!empty($print['exp_date']) && !empty($page_product->exp_date))
							<span style="font-size: {{$barcode_details->exp_date_size}}px">
								<b>@lang('product.exp_date'):</b>
								{{$page_product->exp_date}}
							</span>
						@endif

						<img style="width:90% !important;height: {{$barcode_details->height*0.24}}in !important;"
							 src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
					</div>
				</div>

			</td>
		</tr>
	@endforeach
</table>
<style type="text/css">

    .price_span {
        float: left;
        font-size: 11px;
        color: #fff;
        background-color: #000000d1;
        font-family: Arial;
        font-weight: 900;
        padding: 1;
        z-index: 1111;
        margin-left: 5px;

    }

    .sym-curr {
        position: absolute;
        color: black;
        top: -4px;
        right: 30%;
        font-family: cursive;

    }

    @media print {

        table {
            page-break-after: always;
        }

        @page {
            size: {{$paper_width}}in {{$paper_height}}in;

            margin-top: 0in !important;
            margin-bottom: 0in !important;
            margin-left: 0in !important;
            margin-right: 0in !important;
        }
    }
</style>