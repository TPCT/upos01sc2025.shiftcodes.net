<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>فاتورة مبيعات</title>
	<style>

		body.invoice-slim {
			font-family: 'Tahoma', sans-serif;
			direction: rtl;
			text-align: right;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-size: 7px;
			word-break: break-all;
		}

		.logo img{
			margin: auto;
			height: 80px;
			object-fit: contain;
		}
		.container {
			margin: 20px auto;
			border: 2px solid #000;
			padding: 5px;
		}

		.header {
			text-align: center;
			margin-bottom: 20px;
			line-height: 1.8;
			border-bottom: 4px solid #000;
		}

		.header h2 {
			margin: 0;
			font-size: 12px;
		}

		.header p {
			margin: 5px 0;
		}

		.details p {
			margin: 5px 0;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		th,
		td {
			border: 1px solid #000;
			padding: 10px;
			text-align: center;
		}

		.summary {
			margin-top: 20px;
		}

		.summary-table {
			margin-right: auto;
		}

		.footer {
			text-align: center;
			margin-top: 30px;
			font-weight: bold;
		}

		.summary-quantity {
			display: flex;
			justify-content: space-between;
			padding-bottom: 10px;

		}

		.summary-quantity span {
			padding: 3px 22px;
		}

		.summary-head {
			display: flex;
			justify-content: space-around;

		}

		.summary-head p {
			display: flex;
			align-items: center;
			gap: 30px;
			padding: 10px;
		}

		.info-details {
			display: flex;
			align-items: center;
			gap: 50px;
			margin-bottom: 1rem;
			justify-content: space-between;
		}
	</style>
</head>

<body class="invoice-slim">
<div class="container ">
	<div class="header">
		@if(!empty($receipt_details->logo))
			<div class="logo">
				<img src="{{$receipt_details->logo}}" alt="شعار الشركة">
			</div>
		@elseif (!empty(session('business.logo')))
			<div class="logo">
				<img src="{{url('uploads/business_logos/' . session('business.logo')) }}" alt="شعار الشركة">
			</div>
		@endif

		<h2>{{$receipt_details->business_name}}</h2>
		<p>{!! $receipt_details->contact !!}</p>
		<p>العنوان: {{$receipt_details->address}}</p>
		<p>{{$receipt_details->location_name}}</p>
	</div>

	<div style="display: flex; flex-direction: column; margin-right: 20px; margin-left: 80px;">
		<p>التاريخ: {{$receipt_details->invoice_date}}</p>
		@if(!empty($receipt_details->total_due))
			<p>فاتورة مبيعات - أجل</p>
		@elseif(!empty($receipt_details->total_paid))
			<p>فاتورة مبيعات - نقدي</p>
		@else
			<p>فاتورة مبيعات - عرض سعر</p>
		@endif
	</div>

	<div class="details">
		<div class="info-details">

			<p><strong>التاريخ:</strong> {{$receipt_details->transaction_date_object->format("d/m/Y")}} - الساعة: {{$receipt_details->transaction_date_object->format("h a")}}</p>
			<p><strong>رقم الفاتورة:</strong> {{$receipt_details->invoice_no}}</p>
		</div>
		<div class="info-details">
			<p><strong>اسم العميل:</strong> {{$receipt_details->customer_name}}</p>
			<p><strong>الهاتف:</strong> {{$receipt_details->customer_mobile}}</p>

		</div>
		@if ($receipt_details->customer_city)
			<p><strong>مدينة:</strong> {{$receipt_details->customer_city}}</p>
		@endif
	</div>

	<table>
		<thead>
		<tr>
			<th>الصنف</th>
			<th>السعر</th>
			<th>الكمية</th>
			<th>الخصم %</th>
			<th>الإجمالي</th>
		</tr>
		</thead>
		<tbody>
			@php
				$total_discount = 0.0;
				$total_quantity = 0.0;
				$total_sum = 0.0;
                $total_product = 0.0;
                $total_products = 0;
			@endphp
			@foreach($receipt_details->lines as $line)
				@php
					$total_products += 1;
					$discount = $line['total_line_discount'];
					$total = $line['line_total'];
					$total_sum += $total;
					$total_quantity += (float) $line['quantity'];
					$total_discount += $discount;
				@endphp
				<tr>
					<td>{{$line['name']}}</td>
					<td>{{$line['unit_price_inc_tax'] . " " . $receipt_details->currency['symbol']}}</td>
					<td>{{(float)$line['quantity']}}</td>
					<td>{{number_format($discount, 2) . " " . $receipt_details->currency['symbol']}}</td>
					<td>{{number_format($total, 2) . " " . $receipt_details->currency['symbol']}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="summary">
		<div class="summary-head" style="display: flex; flex-direction: column">
			<p><strong>المجموع:</strong> <span>{{number_format($total_sum, 2) . " " . $receipt_details->currency['symbol']}}</span> </p>
			<p><strong>الخصم:</strong> <span>{{number_format($total_discount, 2) . " " . $receipt_details->currency['symbol']}}</span> </p>
			<p><strong>الإجمالي:</strong> <span>{{number_format($total_sum - $total_discount, 2) . " " . $receipt_details->currency['symbol']}}</span> </p>
		</div>
		<div class="summary-quantity" style="display: flex; flex-direction: column">
			<p><strong>عدد الأصناف:</strong> <span>{{$total_products}}</span> </p>
			<p><strong>إجمالى الكمية:</strong> <span>{{$total_quantity}}</span> </p>
		</div>

		<div style="height: 1px; border: 1px solid black; margin-top: 10px"></div>

		<table class="summary-table">
			<tr>
				<td><strong>الإجمالي:</strong></td>
				<td>{{number_format($total_sum - $total_discount, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
			<tr>
				<td><strong>الرصيد السابق:</strong></td>
				<td>{{number_format($receipt_details->customer_balance, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
			<tr>
				<td><strong>إجمالي الحساب:</strong></td>
				<td>{{number_format($receipt_details->customer_balance - $receipt_details->total_unformatted + $receipt_details->total_paid_unformatted, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
		</table>
	</div>

	<div class="footer">
		{!! $receipt_details->footer_text !!}
	</div>
</div>
</body>

</html>