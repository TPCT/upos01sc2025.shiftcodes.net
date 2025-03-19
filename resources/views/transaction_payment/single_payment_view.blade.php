<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title no-print">
                @lang( 'lang_v1.view_payment' )
                @if(!empty($single_payment_line->payment_ref_no))
                    ( @lang('purchase.ref_no'): {{ $single_payment_line->payment_ref_no }} )
                @endif
            </h4>
            <h4 class="modal-title visible-print-block">
                @if(!empty($single_payment_line->payment_ref_no))
                    ( @lang('purchase.ref_no'): {{ $single_payment_line->payment_ref_no }} )
                @endif
            </h4>
        </div>
        <div class="modal-body">
                <div class="row">
                    <div class="col-xs-6">
                        @lang('purchase.supplier'):
                        <address>
                            <strong>{{ $contact->supplier_business_name }}</strong>
                            {{ $contact->name }}
                            {!! $contact->contact_address !!}
                            @if(!empty($contact->tax_number))
                                <br>@lang('contact.tax_no'): {{$contact->tax_number}}
                            @endif
                            @if(!empty($contact->mobile))
                                <br>@lang('contact.mobile'): {{$contact->mobile}}
                            @endif
                            @if(!empty($contact->email))
                                <br>@lang('business.email'): {{$contact->email}}
                            @endif
                        </address>
                    </div>
                    <div class="col-xs-6">
                        @lang('business.business'):
                        <address>
                            <strong>{{ $business->name }}</strong>

                            @if(!empty($location))
                                {{ $location->name }}
                                @if(!empty($location->landmark))
                                    <br>{{$location->landmark}}
                                @endif
                                @if(!empty($location->city) || !empty($location->state) || !empty($location->country))
                                    <br>{{implode(',', array_filter([$location->city, $location->state, $location->country]))}}
                                @endif
                            @endif

                            @if(!empty($business->tax_number_1))
                                <br>{{$business->tax_label_1}}
                                : {{$business->tax_number_1}}
                            @endif

                            @if(!empty($business->tax_number_2))
                                <br>{{$business->tax_label_2}}
                                : {{$business->tax_number_2}}
                            @endif

                            @if(!empty($location))
                                @if(!empty($location->mobile))
                                    <br>@lang('contact.mobile'): {{$location->mobile}}
                                @endif
                                @if(!empty($location->email))
                                    <br>@lang('business.email'): {{$location->email}}
                                @endif
                            @endif
                        </address>
                    </div>
                    @if (!empty($transaction))
                        <div class="col-xs-6">
                            @if(!empty($transaction->transaction_for))
                                @lang('essentials::lang.payroll_for'):
                                <address>
                                    <strong>{{ $transaction->transaction_for->user_full_name }}</strong>
                                    @if(!empty($transaction->transaction_for->address))
                                        <br>{{$transaction->transaction_for->address}}
                                    @endif
                                    @if(!empty($transaction->transaction_for->contact_number))
                                        <br>@lang('contact.mobile')
                                        : {{$transaction->transaction_for->contact_number}}
                                    @endif
                                    @if(!empty($transaction->transaction_for->email))
                                        <br>@lang('business.email'): {{$transaction->transaction_for->email}}
                                    @endif
                                </address>
                            @endif
                        </div>
                    @endif
                </div>
            <div class="row">
                <br>
                <div class="col-xs-6">
                    <strong>رصيد الحساب قبل: </strong>@format_currency($due - $single_payment_line->amount)
                    <br>

                    <strong>@lang('purchase.amount') :</strong>
                    @format_currency($single_payment_line->amount)<br>
                    <strong>رصيد الحساب بعد: </strong>@format_currency($due)<br>
                    <strong>@lang('lang_v1.payment_method') :</strong>
                    {{ $payment_types[$single_payment_line->method] ?? '' }}<br>
                    @if($single_payment_line->method == "card")
                        <strong>@lang('lang_v1.card_holder_name') :</strong>
                        {{ $single_payment_line->card_holder_name }} <br>
                        <strong>@lang('lang_v1.card_number') :</strong>
                        {{ $single_payment_line->card_number }} <br>
                        <strong>@lang('lang_v1.card_transaction_number') :</strong>
                        {{ $single_payment_line->card_transaction_number }}

                    @elseif($single_payment_line->method == "cheque")
                        <strong>@lang('lang_v1.cheque_number') :</strong>
                        {{ $single_payment_line->cheque_number }}
                    @elseif($single_payment_line->method == "bank_transfer")

                    @elseif($single_payment_line->method == "custom_pay_1")

                        <strong>@lang('lang_v1.transaction_number') :</strong>
                        {{ $single_payment_line->transaction_no }}
                    @elseif($single_payment_line->method == "custom_pay_2")

                        <strong>@lang('lang_v1.transaction_number') :</strong>
                        {{ $single_payment_line->transaction_no }}
                    @elseif($single_payment_line->method == "custom_pay_3")

                        <strong> @lang('lang_v1.transaction_number'):</strong>
                        {{ $single_payment_line->transaction_no }}
                    @endif
                    <strong>@lang('purchase.payment_note') :</strong>
                    {{ $single_payment_line->note }}
                </div>
                <div class="col-xs-6">
                    <b>@lang('purchase.ref_no'):</b>
                    @if(!empty($single_payment_line->payment_ref_no))
                        {{ $single_payment_line->payment_ref_no }}
                    @else
                        --
                    @endif
                    <br />
                    <b>@lang('lang_v1.paid_on'):</b> {{ @format_datetime($single_payment_line->paid_on) }}<br />
                    <br>
                    @if(!empty($single_payment_line->document_path))
                        <a href="{{$single_payment_line->document_path}}"
                           class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-accent no-print"
                           download="{{$single_payment_line->document_name}}"><i class="fa fa-download"
                                                                                 data-toggle="tooltip"
                                                                                 title="{{__('purchase.download_document')}}"></i> {{__('purchase.download_document')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white no-print"
                    aria-label="Print"
                    onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang( 'messages.print' )
            </button>
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white no-print"
                    data-dismiss="modal">@lang( 'messages.close' )
            </button>
        </div>
    </div>
</div>