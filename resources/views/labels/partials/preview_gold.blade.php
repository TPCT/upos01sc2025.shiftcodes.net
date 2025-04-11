<table align="center" style="overflow: hidden !important;">
	@foreach($page_products as $page_product)
		<tr style="margin-top: {{$barcode_details->top_margin}}; margin-left: {{$barcode_details->left_margin}}; ">
			<div class="right" style="padding-top: 0.1cm; position: absolute; right: 0; border: 1px solid black; border-radius: 5px; height: var(--conatiner-height); width: var(--conatiner-width);">

				<div style="display: flex; height: 50%">
					<div style="display: flex; flex-direction: column">
						<img style="padding-left: 0.1cm; width:{{0.80}}cm !important;height: {{$barcode_details->height}}cm !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
						<span style="transform: scaleX(-1) scaleY(-1); display: flex; flex-direction:column; justify-content: center;font-weight: bolder;align-items: center;">{{$page_product->sub_sku}}</span>
					</div>
					<div style="transform: scaleX(-1) scaleY(-1);display: flex;flex-direction: column;font-weight: bolder;align-items: center;width: 100%; justify-content: center">
					@if(!empty($print['name']))
						<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
							{{$page_product->product_actual_name}}
							@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
								({{$page_product->lot_number}})
							@endif
						</span>
					@endif
					@if ($page_product->weight)
						<span style="font-size: {{6}}px; text-align: right;">
							{{$page_product->weight}} G
						</span>
					@endif
					<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
						@if($print['price_type'] == 'inclusive')
							{{@num_format($page_product->sell_price_inc_tax)}}
						@else
							{{@num_format($page_product->default_sell_price)}}
						@endif
					</span>
					</div>
				</div>

				<div style="display: flex; height: 50%; justify-content: center; align-items: center; transform: scaleY(-1) scaleX(-1)">
					<img src="{{asset('/uploads/business_logos/' . $print['business_logo'])}}" height="30px" width="30px"/>
				</div>
			</div>
			<div class="left" style="padding-top: 0.1cm; position: absolute; top: 45px; left: 0; border: 1px solid black; border-radius: 5px; height: var(--conatiner-height); width: var(--conatiner-width);">
				<div style="display: flex; height: 50%">
					<div style="display: flex; flex-direction: column">
						<img style="padding-left: 0.1cm; width:{{0.80}}cm !important;height: {{$barcode_details->height}}cm !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($page_product->sub_sku, $page_product->barcode_type, 1,30, array(0, 0, 0), false)}}">
						<span style="display: flex; flex-direction:column; justify-content: center;font-weight: bolder;align-items: center;">{{$page_product->sub_sku}}</span>
					</div>
					<div style="display: flex;flex-direction: column;font-weight: bolder;align-items: center;width: 100%; justify-content: center">
						@if(!empty($print['name']))
							<span style="display: block !important; font-size: {{$barcode_details->name_size}}px">
								{{$page_product->product_actual_name}}
								@if(!empty($print['lot_number']) && !empty($page_product->lot_number))
									({{$page_product->lot_number}})
								@endif
							</span>
						@endif
						@if ($page_product->weight)
							<span style="font-size: {{6}}px; text-align: right;">
								{{$page_product->weight}} G
							</span>
						@endif
						<span style="font-size: {{$barcode_details->price_size}}px" class="price_span">
							@if($print['price_type'] == 'inclusive')
									{{@num_format($page_product->sell_price_inc_tax)}}
								@else
									{{@num_format($page_product->default_sell_price)}}
								@endif
						</span>
					</div>
				</div>
				<div style="display: flex; height: 50%; justify-content: center; align-items: center">
					<img src="{{asset('/uploads/business_logos/' . $print['business_logo'])}}" height="30px" width="30px"/>
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