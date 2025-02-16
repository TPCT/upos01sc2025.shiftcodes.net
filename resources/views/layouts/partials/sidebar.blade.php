<aside class="side-bar tw-relative tw-hidden tw-h-full tw-bg-white tw-w-64 xl:tw-w-64 lg:tw-flex lg:tw-flex-col tw-shrink-0">

    <!-- Logo and Sidebar Heading -->
    <a href="{{ route('home') }}"
        class="tw-flex tw-items-center tw-justify-center tw-w-full tw-border-r tw-h-20 tw-bg-@if(!empty(session('business.theme_color'))){{ session('business.theme_color') }}@else{{ 'primary' }}@endif-800 tw-shrink-0 tw-border-primary-500/30">
        
        <!-- صورة النشاط واسم النشاط -->
        <div class="tw-flex tw-flex-col tw-items-center tw-my-4">
            <!-- صورة تلقائية الحجم مع مسافة من الأعلى -->
            <img src="{{ url('uploads/business_logos/' . session('business.logo')) }}" 
                 alt="Logo" 
                 class="tw-mt-4" 
                 style="max-width: 100%; height: auto;">

            <!-- اسم النشاط -->
            <p class="tw-mt-2 tw-text-lg tw-font-medium tw-text-white side-bar-heading tw-text-center">
                {{ Session::get('business.name') }}
                <span class="tw-inline-block tw-w-3 tw-h-3 tw-bg-green-400 tw-rounded-full" title="Online"></span>
            </p>
        </div>
    </a>

    <!-- Sidebar Menu -->
    {!! Menu::render('admin-sidebar-menu', 'adminltecustom') !!}

</aside>
