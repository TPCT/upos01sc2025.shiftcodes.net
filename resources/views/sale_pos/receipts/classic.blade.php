<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            padding: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header .logo img {
            height: 80px;
        }

        .header .invoice-number {
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }

        .header .details {
            text-align: left;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .gray-bg {
            background-color: #d3d3d3;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="details" style="display: flex; align-items: center; flex-direction: column; font-weight: bold;">
        <p style="margin: 0;">الشركة: {{$receipt_details->business_name}}</p>
        <p style="margin: 0;">{!! $receipt_details->contact !!}</p>
        <p style="margin: 0;">{{\Carbon\Carbon::now()->format("a h")}}</p>
    </div>

    <div class="invoice-number">
        <p>فاتورة: {{$receipt_details->invoice_no}}</p>
    </div>
    <div>
        @if(!empty($receipt_details->logo))
            <div class="logo">
                <img src="{{$receipt_details->logo}}" alt="شعار الشركة">
            </div>
        @elseif (!empty(session('business.logo')))
            <div class="logo">
                <img src="{{url('uploads/business_logos/' . session('business.logo')) }}" alt="شعار الشركة">
            </div>
        @else
            <div class="logo" style="width: 100px; height: 80px">
            </div>
        @endif

    </div>
</div>
    <div class="invoice-info" style="border: 1px solid black; padding: 10px 20px; margin-bottom: 10px; font-weight: bold; display: flex; justify-content: space-between">
        <div style="display: flex; margin-right: 20px; margin-left: 80px; flex-direction: column">
            <p>كود العميل: {{$receipt_details->client_id}}</p>
            <p>اسم العميل: {{$receipt_details->customer_name}}</p>
        </div>
        <div style="display: flex; flex-direction: column; margin-right: 20px; margin-left: 80px;">
                <p>التاريخ: {{$receipt_details->invoice_date}}</p>
                @if(!empty($receipt_details->total_due))
                    <p>نوع الفاتورة: أجل</p>
                @elseif(!empty($receipt_details->total_paid))
                    <p>نوع الفاتورة: نقدية</p>
                @else
                    <p>نوع الفاتورة: عرض سعر</p>
                @endif
        </div>
    </div>
    <table>
        <thead>
			<tr>
				<th>#</th>
				<th>كود الصنف</th>
				<th>اسم الصنف</th>
				<th>الكمية</th>
				<th>السعر</th>
				<th>الخصم</th>
				<th>الإجمالي</th>
			</tr>
        </thead>
        <tbody>
        @php
            $total_discount = 0.0;
            $total_quantity = 0.0;
            $total_sum = 0.0;
        @endphp
        @foreach($receipt_details->lines as $line)
            @php
                $discount = $line['total_line_discount'];
                $total = $line['line_total'];
                $total_sum += $total;
                $total_quantity += (float) $line['quantity'];
                $total_discount += $discount;
            @endphp
			<tr>
				<td>{{$loop->iteration}}</td>
				<td>{{$line['id']}}</td>
				<td>{{$line['name']}}</td>
				<td>{{$line['quantity']}}</td>
				<td>{{$line['unit_price_inc_tax'] . " " . $receipt_details->currency['symbol']}}</td>
				<td>{{$discount . " " . $receipt_details->currency['symbol']}}</td>
				<td>{{$total . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
        @endforeach
        </tbody>

		<tfoot>
			<tr style="font-weight:bold;">
				<td colspan="2">-</td>
				<td>اجمالي القطع</td>
				<td class="gray-bg">{{$total_quantity}}</td>
				<td>-</td>
				<td class="gray-bg">الاجمالي</td>
				<td>{{number_format($total_sum, 2) . " " . $receipt_details->currency['symbol']}}</td>
			</tr>
		</tfoot>
    </table>
    <div class="footer" style="display: flex; justify-content: space-between;">
        <div>
            <div style="font-weight: bold; display: flex; margin-bottom: 5px;">
                <div style="border: 1px solid black; margin-left: 10px; display: flex; background: #d3d3d3; align-items: center; justify-content: center; padding: 10px">
                    صافي الفاتورة
                </div>
                <div style="border: 1px solid black; display: flex; background: #d3d3d3; align-items: center; justify-content: center; padding: 10px">
                    {{number_format($total_sum, 2) . " " . $receipt_details->currency['symbol']}}
                </div>
            </div>
            <div style="font-weight: bold; display: flex; margin-bottom: 5px;">
                <div style="border: 1px solid black; margin-left: 10px; display: flex; align-items: center; justify-content: center; padding: 10px">
                    الرصيد السابق
                </div>
                <div style="border: 1px solid black; display: flex; align-items: center; justify-content: center; padding: 10px">
                    {{number_format($receipt_details->customer_balance, 2) . " " . $receipt_details->currency['symbol']}}
                </div>
            </div>
            <div style="font-weight: bold; display: flex; margin-bottom: 5px;">
                <div style="border: 1px solid black; margin-left: 10px; display: flex; align-items: center; justify-content: center; padding: 10px">
                    الرصيد الحالي
                </div>
                <div style="border: 1px solid black; display: flex; align-items: center; justify-content: center; padding: 10px">
                    {{number_format($receipt_details->customer_balance - $receipt_details->total_unformatted + $receipt_details->total_paid_unformatted, 2) . " " . $receipt_details->currency['symbol']}}
                </div>
            </div>
        </div>
        <div>
            <p style="text-align: right; padding: 5px;">قيمة البضاعة: {{number_format($total_sum, 2) . " " . $receipt_details->currency['symbol']}}</p>
            <p style="text-align: right; padding: 5px;">القيمة المقدمة: {{$receipt_details->total_paid}}</p>
            <p style="text-align: right; padding: 5px;">خصم نقدي: {{number_format($total_discount, 2) . " " . $receipt_details->currency['symbol']}}</p>
            <p style="background-color: #d3d3d3; text-align: right; padding: 5px;">بعد الخصم: {{number_format($total_sum - $total_discount, 2) . " " . $receipt_details->currency['symbol'] }}</p>
        </div>
    </div>
</body>
</html>
