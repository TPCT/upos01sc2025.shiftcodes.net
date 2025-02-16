<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title>

    @include('layouts.partials.css')

    @include('layouts.partials.extracss_auth')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <style>
        /* إعدادات الخلفية */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('{{ asset('images/land.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #fff; /* يمكن ضبط اللون حسب الخلفية */
        }

        .container-fluid {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

.right-col {
    background: rgba(0, 0, 0, 0.5); /* خلفية سوداء شفافة بنسبة 50% */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

        .whatsapp-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
        }

    </style>
</head>

<body class="pace-done">
    @inject('request', 'Illuminate\Http\Request')
    @if (session('status') && session('status.success'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
            data-msg="{{ session('status.msg') }}">
    @endif
    <div class="container-fluid">
        <div class="row eq-height-row">
            <div class="col-md-12 col-sm-12 col-xs-12 right-col">
                <div class="row">
<div class="tw-w-full tw-h-[100px] tw-overflow-hidden">
    <img src="{{ asset('img/soon (1).png') }}" alt="lock" class="tw-w-full tw-h-[100px] tw-object-cover" />
</div>


                    <div class="tw-absolute tw-top-5 md:tw-top-8 tw-right-5 md:tw-right-10 tw-flex tw-items-center tw-gap-4"
                        style="text-align: left">
                        @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                            @if (config('constants.allow_registration'))
                            @endif
                        @endif
                        @if ($request->segment(1) != 'login')
                            <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                                href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login']) }}@if(!empty(request()->lang)){{ '?lang='.request()->lang }}@endif">
                                {{ __('business.sign_in') }}
                            </a>
                        @endif
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    @include('layouts.partials.javascripts')

    <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>

    @yield('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2_register').select2();
        });
    </script>
</body>

</html>
