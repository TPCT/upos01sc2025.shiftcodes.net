<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('lang_v1.client_invoices')</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('lang_v1.date')</th>
                            <th>@lang('lang_v1.invoice_no')</th>
                            <th>@lang('sale.total_amount')</th>
                            <th>@lang('lang_v1.discounts')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($client_transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->created_at}}</td>
                                    <td>
                                        <a class="badge bg-blue view-invoice-modal" data-href="{{route('view-payment', ['payment_id' => $transaction->id])}}"># {{$transaction->invoice_no}} </a>
                                    </td>
                                    <td>{{$transaction->final_total}}</td>
                                    @if ($transaction->discount_type == "percentage")
                                        <td><span>{{$transaction->discount_amount}} %</span></td>
                                    @else
                                        <td><span class="display_currency" data-currency_symbol="true">{{$transaction->discount_amount}}</span></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white"
                    data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


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