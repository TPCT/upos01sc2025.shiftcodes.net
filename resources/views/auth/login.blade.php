@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    @php
        $username = old('username');
        $password = null;
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }
    @endphp

    <div class="row justify-content-center">
        <div class="col-md-10">
            @if (config('app.env') == 'demo')
                @component('components.widget', [
                    'class' => 'box-primary',
                    'header' =>
                        '<h4 class="text-center">Demo Shops <small><i> <br/>Demos are for example purpose only, this application <u>can be used in many other similar businesses.</u></i> <br/><b>Click button to login that business</b></small></h4>',
                ])
                    <!-- Demo buttons (No changes here) -->
                @endcomponent
            @endif
        </div>

        <div class="col-md-12">
            <div class="tw-p-6 tw-mb-4 tw-rounded-2xl tw-bg-transparent tw-shadow-sm tw-ring-1 tw-ring-gray-200 tw-w-full">
                <div class="tw-flex tw-flex-col tw-gap-4 tw-dw-rounded-box tw-dw-p-6 tw-dw-max-w-md">
                    <div class="tw-flex tw-items-center tw-flex-col">
                        <h1 class="tw-text-lg md:tw-text-xl tw-font-bold tw-text-white">
                            <a href="https://shiftcodes.net" target="_blank" class="tw-text-white tw-underline hover:tw-text-blue-300">
                                @lang('برجاء زيارة موقع الشركة للمزيد من البرامج')
                            </a>
                        </h1>

                        <h2 class="tw-text-sm tw-font-medium tw-text-gray-500">
                            @lang('lang_v1.login_to_your') {{ config('app.name', 'ultimatePOS') }}
                        </h2>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        {{ csrf_field() }}

<div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
    <label class="tw-dw-form-control">
        <div class="tw-dw-label">
            <span class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-white">@lang('اسم المستخدم')</span>
        </div>
        <input class="form-control"
               name="username" required autofocus placeholder="@lang('lang_v1.username')"
               value="{{ $username }}" />
        @if ($errors->has('username'))
            <span class="help-block">
                <strong>{{ $errors->first('username') }}</strong>
            </span>
        @endif
    </label>
</div>

<div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
    <label class="tw-dw-form-control">
        <div class="tw-dw-label">
            <span class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-white">@lang('كلمة المرور')</span>
            @if (config('app.env') != 'demo')
                <a href="{{ route('password.request') }}"
                   class="forgot-password-link">
                    @lang('lang_v1.forgot_your_password')
                </a>
            @endif
        </div>
        <input class="form-control"
               id="password" type="password" name="password" required
               placeholder="@lang('lang_v1.password')">
        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </label>
</div>


<div class="form-group text-center">
    <button type="submit" class="btn btn-primary btn-header"
            style="background-color: rgba(0, 0, 0, 0.8); color: #00d8ff; border-color: rgba(0, 0, 0, 0.5);">
        @lang('lang_v1.login')
    </button>
</div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* توسيط الزر داخل الحاوية */
        .form-group.text-center {
            text-align: center; /* توسيط الزر داخل الحاوية */
        }

        /* تخصيص الزر */
        .btn-header {
            background-color: #3490dc;
            color: white;
            padding: 14px 20px; /* تعديل حجم الزر */
            border-radius: 8px;
            width: 100%; /* جعل الزر يأخذ العرض بالكامل */
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-header:hover {
            background-color: #2779bd;
        }

        /* تخصيص الحقول */
        .form-group input {
            background-color: transparent;
            color: #333;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #D1D5DA;
            transition: border-color 0.3s ease;
            height: 50px;
        }

        .form-group input:focus {
            border-color: #3490dc;
            outline: none;
        }

        /* رابط "نسيت كلمة المرور" */
        .forgot-password-link {
            display: inline-block;
            margin-top: 8px;
            font-size: 14px;
            color: #3490dc;
            text-align: right;
        }

        /* تحسين التوافق مع الأجهزة الصغيرة */
        @media (max-width: 768px) {
            .col-md-4 {
                width: 90%;
                margin: auto;
            }

            .form-group input {
                font-size: 14px;
                height: 45px;
            }

            .btn-primary {
                padding: 12px;
            }
        }
    </style>
@endsection
