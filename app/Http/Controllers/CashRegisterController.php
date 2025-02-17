<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\CashRegister;
use App\CashRegisterTransaction;
use App\Transaction;
use App\TransactionPayment;
use App\TypesOfService;
use App\Utils\CashRegisterUtil;
use App\Utils\ModuleUtil;
use Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Utils\TransactionUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Log;


class CashRegisterController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $cashRegisterUtil;

    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param CashRegisterUtil $cashRegisterUtil
     * @return void
     */
    public function __construct(CashRegisterUtil $cashRegisterUtil, ModuleUtil $moduleUtil)
    {
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('cash_register.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //like:repair
        $sub_type = request()->get('sub_type');

        try {
            $initial_amount = 0;
            if (!empty($request->input('amount'))) {
                $initial_amount = $this->cashRegisterUtil->num_uf($request->input('amount'));
            }
            $user_id = $request->session()->get('user.id');
            $business_id = $request->session()->get('user.business_id');

            $register = CashRegister::create([
                'business_id' => $business_id,
                'user_id' => $user_id,
                'status' => 'open',
                'location_id' => $request->input('location_id'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:00'),
            ]);
            if (!empty($initial_amount)) {
                $register->cash_register_transactions()->create([
                    'amount' => $initial_amount,
                    'pay_method' => 'cash',
                    'type' => 'credit',
                    'transaction_type' => 'initial',
                ]);
            }
        } catch (Exception $e) {
            Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
        }

        return redirect()->action([SellPosController::class, 'create'], ['sub_type' => $sub_type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse
     */
    public function create()
    {
        //like:repair
        $sub_type = request()->get('sub_type');

        //Check if there is a open register, if yes then redirect to POS screen.
        if ($this->cashRegisterUtil->countOpenedRegister() != 0) {
            return redirect()->action([SellPosController::class, 'create'], ['sub_type' => $sub_type]);
        }
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);

        return view('cash_register.create')->with(compact('business_locations', 'sub_type'));
    }

    /**
     * Display the specified resource.
     *
     * @param CashRegister $cashRegister
     * @return Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);
        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = !empty($register_details['closed_at']) ? $register_details['closed_at'] : Carbon::now()->toDateTimeString();
        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time);

        $payment_types = $this->cashRegisterUtil->payment_types(null, false, $business_id);

        return view('cash_register.register_details')
            ->with(compact('register_details', 'details', 'payment_types', 'close_time'));
    }

    /**
     * Shows register details modal.
     *
     * @param void
     * @return Application|Factory|View|\Illuminate\View\View
     */
    public function getRegisterDetails()
    {
        if (!auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $register_details = $this->cashRegisterUtil->getRegisterDetails();

        $user_id = auth()->user()->id;
        $open_time = $register_details['open_time'];
        $close_time = Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        $sells = Transaction::where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->whereBetween('transaction_date', [$open_time, $close_time]);
        })->get();

        $purchases = Transaction::where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'purchase');
            $query->where('business_id', $business_id);
            $query->whereBetween('transaction_date', [$open_time, $close_time]);
        })->get();

        $collected_bills = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $discounts = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
            $query->where('discount_amount', '>', 0);
            $query->whereNotNull('contact_id');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $expenses = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'expense');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $incomes = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'expense_refund');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $collected_bills_without_invoices = TransactionPayment::where(function($query) use ($business_id, $open_time, $close_time) {
            $query->whereNull('transaction_id');
            $query->whereNotNull('payment_for');
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })
        ->get();

        $services = TypesOfService::all()->pluck('name', 'id')->map(function ($item) {
            return [
                'name' => $item,
                'total' => 0
            ];
        })->toArray();

        $details['drawer_cash'] = CashRegisterTransaction::whereHas('cash_register', function ($query) use ($business_id, $user_id){
            $query->where('business_id', $business_id);
            $query->where('user_id', $user_id);
            $query->where('status', 'open');
        })->where(function ($query) use ($business_id, $user_id){
            $query->where('transaction_type', 'initial');
        })->first()?->amount ?? 0;

        $details['purchase_return'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'purchase_return');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $user_id, $open_time, $close_time){
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->sum('amount');

        $details['sell_return'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'sell_return');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $user_id, $open_time, $close_time){
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->sum('amount');

        $details['supplier_payments'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'purchase');
            $query->where('business_id', $business_id);
            $query->where('payment_status', 'partial');
        })
            ->where(function ($query) use ($business_id, $open_time, $close_time) {
                $query->where('business_id', $business_id);
                $query->whereBetween('paid_on', [$open_time, $close_time]);
            })->sum('amount');

        $pos_settings = !empty(request()->session()->get('business.pos_settings')) ? json_decode(request()->session()->get('business.pos_settings'), true) : [];

        return view('cash_register.register_details')
            ->with(compact(
                'register_details', 'details', 'payment_types',
                'pos_settings', 'sells', 'purchases', 'collected_bills', 'services',
                'discounts', 'expenses', 'incomes', 'collected_bills_without_invoices',
                'close_time'
            ));
    }

    /**
     * Shows close register form.
     *
     * @param void
     * @return Response
     */
    public function getCloseRegister($id = null)
    {
        if (!auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);

        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        $sells = Transaction::where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->whereBetween('transaction_date', [$open_time, $close_time]);
        })->get();

        $purchases = Transaction::where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'purchase');
            $query->where('business_id', $business_id);
            $query->whereBetween('transaction_date', [$open_time, $close_time]);
        })->get();

        $collected_bills = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $discounts = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'sell');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
            $query->where('discount_amount', '>', 0);
            $query->whereNotNull('contact_id');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $expenses = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'expense');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $incomes = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('type', 'expense_refund');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->get();

        $collected_bills_without_invoices = TransactionPayment::where(function($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
            $query->whereNull('transaction_id');
            $query->whereNotNull('payment_for');
        })->get();

        $services = TypesOfService::all()->pluck('name', 'id')->map(function ($item) {
            return [
                'name' => $item,
                'total' => 0
            ];
        })->toArray();

        $details['drawer_cash'] = CashRegisterTransaction::whereHas('cash_register', function ($query) use ($business_id, $user_id){
            $query->where('business_id', $business_id);
            $query->where('user_id', $user_id);
            $query->where('status', 'open');
        })->where(function ($query) use ($business_id, $user_id){
            $query->where('transaction_type', 'initial');
        })->first()?->amount ?? 0;

        $details['purchase_return'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'purchase_return');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $user_id, $open_time, $close_time){
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->sum('amount');

        $details['sell_return'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'sell_return');
            $query->where('business_id', $business_id);
            $query->where('status', 'final');
            $query->where('payment_status', 'paid');
        })->where(function ($query) use ($business_id, $user_id, $open_time, $close_time){
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->sum('amount');

        $details['supplier_payments'] = TransactionPayment::whereHas('transaction', function ($query) use ($business_id, $user_id){
            $query->where('type', 'purchase');
            $query->where('business_id', $business_id);
            $query->where('payment_status', 'partial');
        })
        ->where(function ($query) use ($business_id, $open_time, $close_time) {
            $query->where('business_id', $business_id);
            $query->whereBetween('paid_on', [$open_time, $close_time]);
        })->sum('amount');

        $pos_settings = !empty(request()->session()->get('business.pos_settings')) ? json_decode(request()->session()->get('business.pos_settings'), true) : [];

        return view('cash_register.close_register_modal')
            ->with(compact(
                'register_details', 'details', 'payment_types',
                'pos_settings', 'sells', 'purchases', 'collected_bills', 'services',
                'discounts', 'expenses', 'incomes', 'collected_bills_without_invoices'
            ));
    }

    /**
     * Closes currently opened register.
     *
     * @param Request $request
     * @return Response
     */
    public function postCloseRegister(Request $request)
    {
        if (!auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //Disable in demo
            if (config('app.env') == 'demo') {
                $output = ['success' => 0,
                    'msg' => 'Feature disabled in demo!!',
                ];

                return redirect()->action([HomeController::class, 'index'])->with('status', $output);
            }

            $input = $request->only(['closing_amount', 'total_card_slips', 'total_cheques', 'closing_note']);
            $input['closing_amount'] = $this->cashRegisterUtil->num_uf($input['closing_amount']);
            $user_id = $request->input('user_id');
            $input['closed_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $input['status'] = 'close';
            $input['denominations'] = !empty(request()->input('denominations')) ? json_encode(request()->input('denominations')) : null;

            CashRegister::where('user_id', $user_id)
                ->where('status', 'open')
                ->update($input);
            $output = ['success' => 1,
                'msg' => __('cash_register.close_success'),
            ];
        } catch (Exception $e) {
            Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->back()->with('status', $output);
    }
}
