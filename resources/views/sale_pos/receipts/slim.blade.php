<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>فاتورة مبيعات</title>
	<style>

		.invoice-slim {
			font-family: 'Tahoma', sans-serif;
			direction: rtl;
			text-align: right;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-size: 7px;
			word-break: break-all;
		}

		.invoice-slim .logo img{
			margin: auto;
			height: 80px;
			object-fit: contain;
		}
		.invoice-slim .container {
			margin: 20px auto;
			border: 2px solid #000;
			padding: 5px;
		}

		.invoice-slim .header {
			text-align: center;
			margin-bottom: 20px;
			line-height: 1.8;
			border-bottom: 4px solid #000;
		}

		.invoice-slim .header h2 {
			margin: 0;
			font-size: 12px;
		}

		.invoice-slim .header p {
			margin: 5px 0;
		}

		.invoice-slim .details p {
			margin: 5px 0;
		}

		.invoice-slim table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		.invoice-slim th,
		.invoice-slim td {
			border: 1px solid #000;
			padding: 10px;
			text-align: center;
		}

		.invoice-slim .summary {
			margin-top: 20px;
		}

		.invoice-slim .summary-table {
			margin-right: auto;
		}

		.invoice-slim .footer {
			text-align: center;
			margin-top: 30px;
			font-weight: bold;
		}

		.invoice-slim .summary-quantity {
			display: flex;
			justify-content: space-between;
			padding-bottom: 10px;

		}

		.invoice-slim .summary-quantity span {
			padding: 3px 22px;
		}

		.invoice-slim .summary-head {
			display: flex;
			justify-content: space-around;

		}

		.invoice-slim .summary-head p {
			display: flex;
			align-items: center;
			gap: 30px;
			padding: 10px;
		}

		.invoice-slim .info-details {
			display: flex;
			align-items: center;
			gap: 50px;
			margin-bottom: 1rem;
			justify-content: space-between;
		}
	</style>
</head>

<body>
<div class="container invoice-slim">
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
		<div style="display: flex; justify-content: center">
			@if(!empty($receipt_details->total_due))
				<p>فاتورة مبيعات - أجل</p>
			@elseif(!empty($receipt_details->total_paid))
				<p>فاتورة مبيعات - نقدي</p>
			@else
				<p>فاتورة مبيعات - عرض سعر</p>
			@endif
		</div>
	</div>

	<div class="details">
		<p><strong>التاريخ:</strong> {{$receipt_details->transaction_date_object->format("d/m/Y")}} - الساعة: {{$receipt_details->transaction_date_object->format("h a")}}</p>
		<p><strong>رقم الفاتورة:</strong> {{$receipt_details->invoice_no}}</p>
		<p><strong>اسم العميل:</strong> {{$receipt_details->customer_name}}</p>
		<p><strong>الهاتف:</strong> {{$receipt_details->customer_mobile}}</p>
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
			<th>الخصم</th>
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
                $receipt_total = $receipt_details->customer_balance - $receipt_details->total_unformatted + $receipt_details->total_paid_unformatted;
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
					<td>{{$line['unit_price_inc_tax']}}</td>
					<td>{{(float)$line['quantity']}}</td>
					<td>{{number_format($discount, 2)}}</td>
					<td>{{number_format($total, 2)}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="summary">

		<div class="summary-quantity" style="display: flex; flex-direction: column">
			<p><strong style="width: 40px">عدد الأصناف:</strong> <span>{{$total_products}}</span> </p>
			<p><strong style="width: 40px">إجمالى الكمية:</strong> <span>{{$total_quantity}}</span> </p>
		</div>

		<div style="height: 1px; border: 1px solid black; margin-top: 10px"></div>

		<table class="summary-table">
			<tr>
				<td><strong>المجموع:</strong></td> <td>{{number_format($total_sum, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
			<tr>
				<td><strong>الخصم:</strong></td> <td>{{number_format($total_discount, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
			<tr>
				<td><strong>الإجمالي:</strong></td>
				<td>{{number_format($total_sum - $total_discount, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
			@if ($receipt_details->payment_type == "sell")
				@if ($receipt_details->payment_status == "due")
					<tr>
						<td><strong>الرصيد السابق:</strong></td>
						<td>{{number_format($receipt_details->customer_balance, 2) . " " . $receipt_details->currency['symbol']}}</td>
					</tr>
					<tr>
						<td><strong>إجمالي الحساب:</strong></td>
						<td>{{number_format($receipt_total > 0 ? 0 : abs($receipt_total), 2) . " " . $receipt_details->currency['symbol']}}</td>
					</tr>
				@else
					<tr>
						<td><strong>إجمالي الحساب:</strong></td>
						<td>{{number_format($receipt_details->total_unformatted, 2) . " " . $receipt_details->currency['symbol']}}</td>
					</tr>
				@endif
			@endif


		</table>
	</div>

	<div class="footer">
		{!! $receipt_details->footer_text !!}
	</div>
</div>
</body>

</html>