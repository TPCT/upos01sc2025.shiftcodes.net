@php use App\Contact; @endphp
@php use App\ExpenseCategory; @endphp
<div class="row">
  <div class="col-md-12">
    <hr>
    <h3>@lang('lang_v1.sales_report')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('lang_v1.total_payment')</th>
        <th>@lang('sale.total_paid')</th>
        <th>@lang('sale.total_remaining')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total = 0;
        $total_sells_paid = 0;
      @endphp
      @foreach($sells as $sell)
        @php
          if ($sell->types_of_service_id){
              if ($sell->packing_charge_type == "percent"){
                  $services[(int)$sell->types_of_service_id]['total'] += $sell->total_before_tax * $sell->packing_charge / 100;
              }else{
                  $services[(int)$sell->types_of_service_id]['total'] += $sell->packing_charge;
              }
          }
          $amount = 0;
          foreach ($sell->payment_lines as $line)
              $amount += $line->amount;
          $total_sells_paid += $amount;
          $total += $sell->final_total;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $sell->id])}}"># {{$sell->invoice_no}} </a>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$sell->final_total}}</span></td>
          <td><span class="display_currency" data-currency_symbol="true">{{$amount}}</span></td>
          <td><span class="display_currency"
                    data-currency_symbol="true">{{$sell->final_total - $amount}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total}}</span></td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total_sells_paid}}</span></td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total - $total_sells_paid}}</span>
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.purchases_report')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('purchase.supplier')</th>
        <th>@lang('lang_v1.total_payment')</th>
        <th>@lang('sale.total_paid')</th>
        <th>@lang('sale.total_remaining')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total = 0;
        $total_purchases_paid = 0;
      @endphp
      @foreach($purchases as $purchase)
        @php
          $amount = 0;
          foreach ($purchase->payment_lines as $line)
              $amount += $line->amount;
          $total_purchases_paid += $amount;
          $total += $purchase->final_total;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $purchase->id])}}"># {{$purchase->ref_no}} </a>
          </td>
          <td><span>{{$purchase->contact->name}}</span></td>
          <td><span class="display_currency" data-currency_symbol="true">{{$purchase->final_total}}</span>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$amount}}</span></td>
          <td><span class="display_currency"
                    data-currency_symbol="true">{{$purchase->final_total - $amount}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total}}</span></td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total_purchases_paid}}</span></td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total - $total_purchases_paid}}</span>
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.services_report')</h3>
    <table class="table">
      <thead>
      <tr>
        <th>@lang('lang_v1.service_name')</th>
        <th>@lang('lang_v1.service_charge')</th>
      </tr>
      </thead>
      <tbody>
      @foreach($services as $service)
        @php
          $total += $service['total']
        @endphp
        <tr>
          <td>{{$service['name']}}</td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{$service['total']}}</span>
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-condensed">
      <tr>
        <th>@lang('lang_v1.payment_method')</th>
        <th>@lang('sale.sale')</th>
        <th>@lang('lang_v1.expense')</th>
      </tr>
      <tr>
        <td>
          @lang('cash_register.cash_in_hand'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->cash_in_hand }}</span>
        </td>
        <td>--</td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.cash_payment'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_cash }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_cash_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.checque_payment'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_cheque }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_cheque_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.card_payment'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_card }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_card_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.bank_transfer'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_bank_transfer }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_bank_transfer_expense }}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('lang_v1.advance_payment'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_advance }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_advance_expense }}</span>
        </td>
      </tr>
      @if(array_key_exists('custom_pay_1', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_1']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_1 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_1_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_2', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_2']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_2 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_2_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_3', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_3']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_3 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_3_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_4', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_4']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_4 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_4_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_5', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_5']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_5 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_5_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_6', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_6']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_6 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_6_expense }}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_7', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_7']}}:
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_7 }}</span>
          </td>
          <td>
                        <span class="display_currency"
                              data-currency_symbol="true">{{ $register_details->total_custom_pay_7_expense }}</span>
          </td>
        </tr>
      @endif
      <tr>
        <td>
          @lang('cash_register.other_payments'):
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_other }}</span>
        </td>
        <td>
                    <span class="display_currency"
                          data-currency_symbol="true">{{ $register_details->total_other_expense }}</span>
        </td>
      </tr>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.bill_collection_details')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('lang_v1.total_payment')</th>
        <th>@lang('sale.invoice_no')</th>
        <th>@lang('lang_v1.payment_method')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $bill_collection_total = 0;
      @endphp
      @foreach($collected_bills as $bill)
        @php
          $transaction = $bill->transaction;
          $bill_collection_total += $bill->amount;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $transaction->id])}}"># {{$bill->payment_ref_no}} </a>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$bill->amount}}</span></td>
          <td><span>{{$bill->transaction->invoice_no}}</span></td>
          <td><span>{{__("lang_v1." . $bill->method)}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$bill_collection_total}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.customer_discount_details')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('lang_v1.total_payment')</th>
        <th>@lang('sale.invoice_no')</th>
        <th>@lang('lang_v1.contact_name')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total = 0;
      @endphp
      @foreach($discounts as $discount)
        @php
          $transaction = $discount->transaction;
          if ($transaction->discount_type == "percentage"){
              $discount_amount = $transaction->total_before_tax * $transaction->discount_amount / 100;
          }else{
              $discount_amount = $transaction->discount_amount;
          }

          $total += $transaction->total_before_tax - $discount_amount;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $transaction->id])}}"># {{$discount->payment_ref_no}} </a>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$discount_amount}}</span></td>
          <td><span>{{$transaction->invoice_no}}</span></td>
          <td><span>{{$transaction->contact->name}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.bill_collection_details_without_invoice')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('lang_v1.total_payment')</th>
        <th>@lang('lang_v1.contact_name')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total = 0;
      @endphp
      @foreach($collected_bills_without_invoices as $bill)
        @php
          $transaction = $bill->transaction;
          $bill_collection_total += $bill->amount;
          $total += $bill->amount;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue"
                 data-href=""># {{$bill->payment_ref_no}} </a>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$bill->amount}}</span></td>
          <td><span>{{Contact::find($bill->payment_for)?->name ?? '-----'}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.expenses_details')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('expense.category_name')</th>
        <th>@lang('sale.total_paid')</th>
        <th>@lang('expense.expense_status')</th>
        <th>@lang('expense.expense_for')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total_expenses_paid = 0;
      @endphp
      @foreach($expenses as $expense)
        @php
          $transaction = $expense->transaction;
          $total_expenses_paid += $expense->amount;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $transaction->id])}}"># {{$expense->payment_ref_no}} </a>
          </td>
          <td>
            <span>{{ExpenseCategory::find($transaction->expense_category_id)?->name ?? '-------'}}</span>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{$expense->amount}}</span></td>
          <td><span>@lang('lang_v1.' . $transaction->status)</span></td>
          <td>
            <span>{{$transaction->transaction_for?->first_name . " " . $transaction->transaction_for?->last_name}}</span>
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{$total_expenses_paid}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h3>@lang('lang_v1.income_details')</h3>
    <table class="table">
      <thead>
      <tr>
        <th># @lang('lang_v1.reference_no')</th>
        <th>@lang('expense.category_name')</th>
        <th>@lang('sale.total_amount')</th>
        <th>@lang('expense.expense_status')</th>
        <th>@lang('expense.expense_for')</th>
      </tr>
      </thead>
      <tbody>
      @php
        $total_income = 0;
      @endphp
      @foreach($incomes as $income)
        @php
          $transaction = $income->transaction;
          $total_income += $income->amount;
        @endphp
        <tr class="">
          <td><a class="badge bg-blue view-invoice-modal"
                 data-href="{{route('view-payment', ['payment_id' => $transaction->id])}}"># {{$income->payment_ref_no}} </a>
          </td>
          <td>
            <span>{{ExpenseCategory::find($transaction->expense_category_id)?->name ?? '-------'}}</span>
          </td>
          <td><span class="display_currency" data-currency_symbol="true">{{-1*$income->amount}}</span></td>
          <td><span>@lang('lang_v1.' . $transaction->status)</span></td>
          <td>
            <span>{{$transaction->transaction_for?->first_name . " " . $transaction->transaction_for?->last_name}}</span>
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
      <tr>
        <td>@lang('lang_v1.total_payment')</td>
        <td><span class="display_currency" data-currency_symbol="true">{{-1*$total_income}}</span></td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-12 col-md-6">
    <h3>@lang('lang_v1.inside')</h3>
    @php
      $total_cash_register_income = $details['drawer_cash'] + $details['purchase_return'] + $total_sells_paid +  $bill_collection_total + -1*$total_income;
    @endphp
    <table class="table table-condensed">
      <tbody>
      <tr>
        <td>
          @lang('lang_v1.drawer_cash'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['drawer_cash'] }}</span>
        </td>
      </tr>
      <tr class="success">
        <td>
          @lang('lang_v1.purchases_refunds')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_return'] }}</span>
        </td>
      </tr>
      <tr class="success">
        <td>
          @lang('lang_v1.sells')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $total_sells_paid }}</span>
        </td>
      </tr>
      <tr class="success">
        <td>
          @lang('lang_v1.bill_collection')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $bill_collection_total }}</span>
        </td>
      </tr>
      <tr class="success">
        <td>
          @lang('lang_v1.income')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ -1*$total_income }}</span>
        </td>
      </tr>
      <tr class="success">
        <td>
          @lang('lang_v1.total_payment')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $total_cash_register_income }}</span>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  <div class="col-12 col-md-6">
    <h3>@lang('lang_v1.outside')</h3>
    @php
      $total_cash_register_outcome = $total_purchases_paid + $details['sell_return'] + $total_expenses_paid + $details['supplier_payments'];
    @endphp
    <table class="table table-condensed">
      <tbody>
      <tr class="danger">
        <td>
          @lang('lang_v1.total_purchases_payment')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $total_purchases_paid }}</span>
        </td>
      </tr>
      <tr class="danger">
        <td>
          @lang('lang_v1.total_sell_return')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sell_return'] }}</span>
        </td>
      </tr>
      <tr class="danger">
        <td>
          @lang('lang_v1.total_expenses')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $total_expenses_paid }}</span>
        </td>
      </tr>
      <tr class="danger">
        <td>
          @lang('lang_v1.supplier_payments')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['supplier_payments'] }}</span>
        </td>
      </tr>
      <tr class="danger">
        <td>
          @lang('lang_v1.total_payment')
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $total_cash_register_outcome }}</span>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="d-flex">
  <h3 class="text-primary text-center">@lang('lang_v1.total'): <span class="display_currency" data-currency_symbol="true">{{$total_cash_register_income - $total_cash_register_outcome }}</span> </h3>
</div>

  <script>
    $(function(){
      $("a.view-invoice-modal").on('click', function (){
         $.get({
           url: $(this).data('href'),
           success: function (response){
              $(".invoice_modal").html(response).modal('show');
           }
         })
      })
    })
  </script>
