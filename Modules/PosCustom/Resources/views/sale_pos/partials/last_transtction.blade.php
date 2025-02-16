@php
  $transactions = DB::table('transactions')
    ->orderBy('transaction_date', 'desc')
    ->take(10)
    ->get(['transaction_date', 'invoice_no', 'final_total']);
// dd($transactions);

@endphp
لإضافة مسافة على الحواف حول الجدول، يمكن استخدام CSS لتطبيق تباعد خارجي أو داخلي على الجدول أو الخلايا. يمكنك تحقيق ذلك باستخدام class أو كتابة الأنماط مباشرة.
المثال مع تطبيق التباعد:

<style>
    .custom-table-container {
        padding: 10px; /* تباعد داخلي حول الجدول */
    }

    .custom-table-container table {
        margin: 10px auto; /* تباعد خارجي حول الجدول */
        width: 95%; /* عرض الجدول مع مساحة على الحواف */
    }

    .custom-table-container th, .custom-table-container td {
        padding: 10px; /* تباعد داخل الخلايا */
        text-align: center; /* محاذاة النص إلى الوسط */
    }
</style>
<!-- زر فتح المودال -->
<div class="modal fade last_transtction" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">


                <input type="hidden" name="contact_id" value="{{ $walk_in_customer['contact_id'] ?? '' }}">
                <input type="hidden" name="due_payment_type" value="{{ $due_payment_type }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">@lang('اخر فواتير للعميل')</h4>
                </div>

                <div class="custom-table-container">
                    <table class="table table-condensed table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>التاريخ</th>
                                <th>رقم الفاتورة</th>
                                <th>سعر الوحدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp <!-- تعريف العداد -->
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $counter }}</td> <!-- عرض الرقم التسلسلي -->
                                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}</td>
                                    <td>{{ $transaction->invoice_no }}</td>
                                    <td>{{ number_format($transaction->final_total, 2) }}</td>
                                </tr>
                                @php $counter++; @endphp <!-- زيادة العداد -->
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                </div>
            </form>
            <!-- إغلاق النموذج -->

        </div>
    </div>
</div>