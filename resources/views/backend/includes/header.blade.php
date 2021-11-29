@push('after-scripts')
    <script>
        $(document).ready(function (){
            function getData() {
                $.get("{!! route('show-queue-log') !!}?disableSource", function(result) {
                    console.log(result);
                    $("#progressBar").css('width', result['progress']+"%");
                    $("#progressBar").html(result['eta'] + " " + result['fileSize'] + " " + result['progress']+"%");
                });
            }
            $(".img-avatar").click(function () {
                console.log('img-avatar clicked');
                getData();
            })
        })
    </script>
@endpush
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="m-2 ml-3 mr-3" href="#">
        @if(config('joydata.settings.logo'))
            @php
                $logoUri = url(\Illuminate\Support\Facades\Storage::url(config('joydata.settings.logo')))
            @endphp
        @else
            @php
                $logoUri = env('APP_LOGO_ADDRESS' , '/img/backend/brand/logo.png' );
                if ($logoUri == null || $logoUri == '/img/backend/brand/logo.png')
                    $logoUri = url('/img/backend/brand/logo.png');
                else
                    $logoUri = url(\Illuminate\Support\Facades\Storage::url($logoUri));
            @endphp
        @endif
        <img class="navbar-brand-full" style="max-width:220px; max-height: 40px;"  src="{!! $logoUri !!}" alt="{{app_name()}}">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav d-md-down-none">
{{--        <li class="nav-item px-3">--}}
{{--            <a class="nav-link" href="{{ route('frontend.index') }}"><i class="fas fa-home"></i></a>--}}
{{--        </li>--}}

        {{--<li class="nav-item px-3">--}}
            {{--<a class="nav-link" href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>--}}
        {{--</li>--}}

{{--        @if(config('locale.status') && count(config('locale.languages')) > 1)--}}
{{--            <li class="nav-item px-3 dropdown">--}}
{{--                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">--}}
{{--                    <span class="d-md-down-none">@lang('menus.language-picker.language') ({{ strtoupper(app()->getLocale()) }})</span>--}}
{{--                </a>--}}

{{--                @include('includes.partials.lang')--}}
{{--            </li>--}}
{{--        @endif--}}
    </ul>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item d-md-down-none">
{{--            @stack('setting-head-notification')--}}
        </li>
{{--        <li class="nav-item dropdown mr-4 pr-1">--}}
{{--            <a class="nav-link" href="#">--}}
{{--                <i class="fas fa-bell"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item d-md-down-none">--}}
{{--            <a class="nav-link" href="#">--}}
{{--                <i class="fas fa-list"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item d-md-down-none">--}}
{{--            <a class="nav-link" href="#">--}}
{{--                <i class="fas fa-map-marker-alt"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item dropdown mr-3">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
{{--            <img src="{{ $logged_in_user->picture }}" class="img-avatar" alt="{{ $logged_in_user->email }}">--}}
              <i class="fas fa-user-circle fa-3x img-avatar"></i>
              <span class="d-md-down-none">
                {{ $logged_in_user->last_name }}@if(!preg_match("/\p{Han}$/u", $logged_in_user->last_name)) @endif{{ $logged_in_user->first_name }}
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            {{--<div class="dropdown-header text-center">--}}
              {{--<strong>Account</strong>--}}
            {{--</div>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-bell"></i> Updates--}}
              {{--<span class="badge badge-info">42</span>--}}
            {{--</a>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-envelope"></i> Messages--}}
              {{--<span class="badge badge-success">42</span>--}}
            {{--</a>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-tasks"></i> Tasks--}}
              {{--<span class="badge badge-danger">42</span>--}}
            {{--</a>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-comments"></i> Comments--}}
              {{--<span class="badge badge-warning">42</span>--}}
            {{--</a>--}}
{{--            <div class="dropdown-header text-center">--}}
{{--              <strong>Settings</strong>--}}
{{--            </div>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-user"></i> Profile--}}
            {{--</a>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-wrench"></i> Settings--}}
            {{--</a>--}}
            {{--<a class="dropdown-item" href="#">--}}
              {{--<i class="fa fa-file"></i> Projects--}}
              {{--<span class="badge badge-primary">42</span>--}}
            {{--</a>--}}
            <div class="divider"></div>
            <div class="dropdown-header text-center">
              <strong>Account</strong>
            </div>
              <div class="p-2" >
                  <div class="progress">
                    <div class="progress-bar bg-warning text-dark lead" id="progressBar" role="progressbar" style="width: 25%;" aria-valuemin="0" aria-valuemax="100">25%</div>
                  </div>
              </div>
              <a class="dropdown-item" href="{{ route('show-queue-log') }}">
                  <i class="fas fa-lock"></i> show-queue-log
              </a>
              @php
              exec("df -h |grep '/$'", $output);
              @endphp
              <span class="dropdown-item">
                  {!! $output[0] !!}
              </span>

              <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">
                <i class="fas fa-lock"></i> @lang('navs.general.logout')
            </a>
          </div>
        </li>
    </ul>

    {{--<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">--}}
        {{--<span class="navbar-toggler-icon"></span>--}}
    {{--</button>--}}
    {{--<button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">--}}
        {{--<span class="navbar-toggler-icon"></span>--}}
    {{--</button>--}}
</header>
