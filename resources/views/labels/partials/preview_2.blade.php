<table align="center" style="
    border-spacing: {{$barcode_details->col_distance * 1}}in {{$barcode_details->row_distance * 1}}in; 
    margin-top: {{$margin_top}}in;
    margin-bottom: {{$margin_top}}in;
    margin-left: {{$margin_left}}in;
    margin-right: {{$margin_left}}in;
    overflow: hidden !important;">
    
    @foreach($page_products as $page_product)

        @if($loop->index % $barcode_details->stickers_in_one_row == 0)
            <tr>
        @endif

        <td align="center" valign="center" style="border: 0.5px solid lightgray; padding: 5px;">
            <div style="width: {{$barcode_details->width * 1}}in; 
                        height: {{$barcode_details->height * 1}}in; 
                        display: flex; 
                        flex-direction: column; 
                        justify-content: center; 
                        align-items: center; 
                        text-align: center; 
                        overflow: hidden !important;">
                
                {{-- اسم المتجر --}}
                @if(!empty($print['business_name']))
                    <b style="font-size: {{$print['business_name_size']}}px; display: block; margin-bottom: 5px;">
                        {{$business_name}}
                    </b>
                @endif

                {{-- اسم المنتج --}}
                @if(!empty($print['name']))
                    <span style="font-size: {{$print['name_size']}}px; display: block; font-weight: bold; color: #000000; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); margin-top: 5px;">
                        {{$page_product->product_actual_name}}
                    </span>
                @endif

                {{-- الباركود --}}
                <img style="width: 100%; height: auto; max-height: {{$barcode_details->height * 0.24}}in; display: block;" 
                     src="https://barcode.tec-it.com/barcode.ashx?data={{ urlencode($page_product->sub_sku) }}&code=Code128&dpi=96">

                {{-- كود المنتج --}}
                <span style="font-size: 12px; font-weight: bold;">
                    {{$page_product->sub_sku}}
                </span>

                {{-- الحجم أو النوع والسعر في سطر واحد --}}
                @if((!empty($print['variations']) && $page_product->is_dummy != 1) || !empty($print['price']))
                    <div style="display: flex; justify-content: space-between; width: 100%; margin-top: 5px;">
                        {{-- الحجم أو النوع --}}
                        @if(!empty($print['variations']) && $page_product->is_dummy != 1)
                            <span style="font-size: {{$print['variations_size']}}px; text-align: right;">
                                {{$page_product->product_variation_name}}: <b>{{$page_product->variation_name}}</b>
                            </span>
                        @endif

                        {{-- السعر --}}
                        @if(!empty($print['price']))
                            <span style="font-size: {{$print['price_size']}}px; font-weight: bold; text-align: left; color: #000;">
                                <b>السعر: </b>
                                @if(isset($print['price_type']) && $print['price_type'] == 'inclusive')
                                    {{ number_format($page_product->sell_price_inc_tax, 2) }} {{ $print['currency_symbol'] ?? 'جنيه' }}
                                @else
                                    {{ number_format($page_product->default_sell_price, 2) }} {{ $print['currency_symbol'] ?? 'جنيه' }}
                                @endif
                            </span>
                        @endif
                    </div>
                @endif

            </div>
        </td>

        @if($loop->iteration % $barcode_details->stickers_in_one_row == 0)
            </tr>
        @endif

    @endforeach
</table>

<style>
    td {
        border: 1px solid lightgray;
        padding: 5px;
    }

    @media print {
        table {
            page-break-after: always;
            width: 100%;
        }
    }
</style>
