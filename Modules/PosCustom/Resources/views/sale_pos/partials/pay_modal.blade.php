<!-- زر فتح المودال -->
<div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="{{ route('postpay') }}" method="POST" id="pay_contact_due_form" enctype="multipart/form-data">
				@csrf

                <input type="hidden" name="contact_id" value="{{ $walk_in_customer['contact_id'] ?? '' }}">
                <input type="hidden" name="due_payment_type" value="{{ $due_payment_type }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">@lang('purchase.add_payment')</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        @if($due_payment_type == 'purchase')
                            <div class="col-md-6">
                                <div class="well">
                                    <strong>@lang('purchase.supplier'):</strong> {{ $walk_in_customer['name'] ?? '' }}<br>
                                    <strong>@lang('business.business'):</strong> {{ $walk_in_customer['supplier_business_name'] ?? 'N/A' }}<br><br>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="well">
                                    <strong>@lang('report.total_purchase'):</strong>
                                    <span class="display_currency" data-currency_symbol="true">{{ $walk_in_customer['total_purchase'] ?? 0 }}</span><br>
                                    <strong>@lang('contact.total_paid'):</strong>
                                    <span class="display_currency" data-currency_symbol="true">{{ $walk_in_customer['total_paid'] ?? 0 }}</span><br>
                                    <strong>@lang('contact.total_purchase_due'):</strong>
                                    <span class="display_currency" data-currency_symbol="true">
                                        {{ ($walk_in_customer['total_purchase'] ?? 0) - ($walk_in_customer['total_paid'] ?? 0) }}
                                    </span><br>
                                    @if(!empty($walk_in_customer['opening_balance']) || ($walk_in_customer['opening_balance'] ?? '0.00') != '0.00')
                                        <strong>@lang('lang_v1.opening_balance'):</strong>
                                        <span class="display_currency" data-currency_symbol="true">
                                            {{ $walk_in_customer['opening_balance'] ?? 0 }}
                                        </span><br>
                                        <strong>@lang('lang_v1.opening_balance_due'):</strong>
                                        <span class="display_currency" data-currency_symbol="true">
                                            {{ $ob_due ?? 0 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- باقي الكود الخاص بالمعلومات الإضافية -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="method">@lang('purchase.payment_method'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                    <select name="method" class="form-control payment_types_dropdown" required>
                                        @foreach ($payment_types as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paid_on">@lang('lang_v1.paid_on'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="paid_on" class="form-control" id="paid_on" value="{{ @format_datetime($payment_line->paid_on) }}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount">@lang('sale.amount'):</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                    <input type="text" name="amount" class="form-control input_number payment_amount" placeholder="@lang('sale.amount')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.close')</button>
                </div>
            </form>
            <!-- إغلاق النموذج -->

        </div>
    </div>
</div>