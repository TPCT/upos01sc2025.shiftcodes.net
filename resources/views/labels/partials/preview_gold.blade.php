<table align="center" style="overflow: hidden !important;">
	@foreach($page_products as $page_product)
		<tr style="margin-top: {{$barcode_details->top_margin}}; margin-left: {{$barcode_details->left_margin}}; ">
			<div class="right" style="position: absolute; right: 0; border: 1px solid black; border-radius: 5px; height: var(--conatiner-height); width: var(--conatiner-width);">
				<div style="height: 50%;transform: scaleX(-1) scaleY(-1);display: flex;flex-direction: column;font-weight: bolder;align-items: center;width: 100%; justify-content: center">
					<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
						@if($print['price_type'] == 'inclusive')
							{{@num_format($page_product->sell_price_inc_tax)}}
						@else
							{{@num_format($page_product->default_sell_price)}}
						@endif
					</span>
					@if(!empty($print['variations']) && $page_product->is_dummy != 1)
						<span style="font-size: {{$print['variations_size']}}px; text-align: right;">
                                {{$page_product->product_variation_name}}: <b>{{$page_product->variation_name}}</b>
						</span>
					@endif
					@if(!empty($print['name']))
						<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
								{{$page_product->product_actual_name}}
							@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
								({{$page_product->lot_number}})
							@endif
						</span>
					@endif
				</div>
				<div style="position: absolute;left: 0;border: 1px dashed black;height: 0;width: 100%;"></div>
				<div style="display: flex; flex-direction:column; justify-content: center;font-weight: bolder;align-items: center;position: absolute;top: 50%;width: 100%;height: 1.25cm;">
					<img style="width:{{$barcode_details->width}}cm !important;height: {{$barcode_details->height}}cm !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
					<span style="position: absolute;top: 0.275cm;background: white;">{{$page_product->sub_sku}}</span>
				</div>
			</div>
			<div class="left" style="position: absolute; top: 45px; left: 0; border: 1px solid black; border-radius: 5px; height: var(--conatiner-height); width: var(--conatiner-width);">
				<div style="transform: scaleX(-1) scaleY(-1); display: flex; flex-direction: column; justify-content: center;font-weight: bolder;align-items: center;position: absolute;top: 50%;width: 100%;height: 1.25cm;">
					<img style="width:{{$barcode_details->width}}cm !important;height: {{$barcode_details->height}}cm !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
					<span style="position: absolute;bottom: 0.275cm;background: white;">{{$page_product->sub_sku}}</span>
				</div>
				<div style="position: absolute;left: 0;border: 1px dashed black;height: 0;width: 100%; top: 50%"></div>
				<div style="height: 50%;display: flex;flex-direction: column;font-weight: bolder;align-items: center;width: 100%; justify-content: center">
					<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
						@if($print['price_type'] == 'inclusive')
							{{@num_format($page_product->sell_price_inc_tax)}}
						@else
							{{@num_format($page_product->default_sell_price)}}
						@endif
					</span>

					@if(!empty($print['variations']) && $page_product->is_dummy != 1)
						<span style="font-size: {{$print['variations_size']}}px; text-align: right;">
                                {{$page_product->product_variation_name}}: <b>{{$page_product->variation_name}}</b>
                            </span>
					@endif

					@if(!empty($print['name']))
						<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
								{{$page_product->product_actual_name}}
							@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
								({{$page_product->lot_number}})
							@endif
						</span>
					@endif
				</div>
			</div>
		</tr>
	@endforeach
</table>
<style type="text/css">

	html, body{
		width: 8.2cm;
		height: 3.7cm;
		margin: 0;
		padding: 0;
		position: relative;
		font-size: 7px;
	}

	:root{
		--conatiner-width: 2cm;
		--conatiner-height: 2.5cm;
	}

	.right::before{
		content: "";
		width: 6.2cm;
		height: 0.2cm;
		border: 1px solid black;
		display: block;
		left: calc(-6.2cm - 2px);
		position: absolute;
		top: 0.425cm;
		border-right: 1px solid white;
	}

	.left::after{
		content: "";
		width: 6.2cm;
		height: 0.2cm;
		border: 1px solid black;
		display: block;
		right: calc(-6.2cm - 2px);
		position: absolute;
		bottom: 0.425cm;
		border-left: 1px solid white;
	}

    @media print {

        table {
            page-break-after: always;
        }

        @page {
            size: {{$paper_width}}cm {{$paper_height}}cm;

            margin-top: 0cm !important;
            margin-bottom: 0cm !important;
            margin-left: 0cm !important;
            margin-right: 0cm !important;
        }
    }
</style>