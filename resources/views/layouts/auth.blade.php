<!DOCTYPE html>
@php
  $logo=\App\Models\Utility::get_file('uploads/logo/');
  $company_logo=Utility::getValByName('company_logo_dark');
  $company_logos=Utility::getValByName('company_logo_light');
  $company_favicon=Utility::getValByName('company_favicon');
  $setting = \App\Models\Utility::settings();
  $color = (!empty($setting['color'])) ? $setting['color'] : 'theme-3';
  if(isset($setting['color_flag']) && $setting['color_flag'] == 'true')
    {
        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }
  $company_logo = \App\Models\Utility::GetLogo();
  $SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
  $lang = \App::getLocale('lang');
        if($lang == 'ar' || $lang == 'he'){
               $setting['SITE_RTL']= 'on';
        }
        elseif($setting['SITE_RTL'] == 'on')
        {
            $setting['SITE_RTL']= 'on';
        }
        else {
            $setting['SITE_RTL']= 'off';
        }

  $getseo= App\Models\Utility::getSeoSetting();
  $metatitle =  isset($getseo['meta_title']) ? $getseo['meta_title'] :'';
  $metsdesc= isset($getseo['meta_desc'])?$getseo['meta_desc']:'';
  $meta_image = \App\Models\Utility::get_file('uploads/meta/');
  $meta_logo = isset($getseo['meta_image'])?$getseo['meta_image']:'';

@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">
<head>
    <script>
        (function () {
            const blockedHost = 'envato.workdo.io';
            const matchesBlocked = (target) => typeof target === 'string' && target.includes(blockedHost);
            const removeBlockedScripts = (root = document) => {
                root.querySelectorAll('script[src*="' + blockedHost + '"]').forEach((node) => {
                    if (node && node.parentNode) {
                        node.parentNode.removeChild(node);
                    }
                });
            };
            const observer = new MutationObserver((mutations) => {
                mutations.forEach(({ addedNodes }) => {
                    addedNodes.forEach((node) => {
                        if (!node || node.tagName !== 'SCRIPT') {
                            return;
                        }
                        const src = node.src || node.getAttribute('src');
                        if (matchesBlocked(src) && node.parentNode) {
                            node.parentNode.removeChild(node);
                        }
                    });
                });
            });
            observer.observe(document.documentElement || document, { childList: true, subtree: true });
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => removeBlockedScripts());
            } else {
                removeBlockedScripts();
            }
            if (window.fetch) {
                const originalFetch = window.fetch.bind(window);
                window.fetch = function (input, init) {
                    const url = typeof input === 'string' ? input : input && input.url;
                    if (matchesBlocked(url)) {
                        const body = '{"success":true}';
                        if (typeof Response === 'function') {
                            return Promise.resolve(new Response(body, {
                                status: 200,
                                headers: { 'Content-Type': 'application/json' },
                            }));
                        }
                        return Promise.resolve({
                            ok: true,
                            status: 200,
                            json: () => Promise.resolve({}),
                            text: () => Promise.resolve(body),
                        });
                    }
                    return originalFetch(input, init);
                };
            }
            const originalXhrOpen = XMLHttpRequest.prototype.open;
            const originalXhrSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.open = function (method, url) {
                this._blockedEnvato = matchesBlocked(url);
                return originalXhrOpen.apply(this, arguments);
            };
            XMLHttpRequest.prototype.send = function (body) {
                if (this._blockedEnvato) {
                    this.readyState = 4;
                    this.status = 200;
                    this.responseText = '{}';
                    if (typeof this.onreadystatechange === 'function') {
                        this.onreadystatechange();
                    }
                    if (typeof this.onload === 'function') {
                        this.onload();
                    }
                    return;
                }
                return originalXhrSend.apply(this, arguments);
            };
        })();
    </script>
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'ERPGO')}} - @yield('page-title')</title>

    <meta name="title" content="{{$metatitle}}">
    <meta name="description" content="{{$metsdesc}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{$metatitle}}">
    <meta property="og:description" content="{{$metsdesc}}">
    <meta property="og:image" content="{{$meta_image.$meta_logo}}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{$metatitle}}">
    <meta property="twitter:description" content="{{$metsdesc}}">
    <meta property="twitter:image" content="{{$meta_image.$meta_logo}}">

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="description" content="Dashboard Template Description"/>
    <meta name="keywords" content="Dashboard Template"/>
    <meta name="author" content="WorkDo"/>

    <!-- Favicon icon -->
    <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')  . '?' . time() }}" type="image/x-icon"/>

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    @if ( $setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css')}}" id="main-style-link">
    @endif
    @if($setting['cust_darklayout']=='on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css')}}">
    @endif
    @if($setting['SITE_RTL'] != 'on' && $setting['cust_darklayout']!='on' )
        <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}" id="main-style-link">
    @endif


    @if (isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth-rtl.css') }}?v={{ time() }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth.css') }}?v={{ time() }}" id="main-style-link">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth-dark.css') }}?v={{ time() }}" id="main-style-link">
    @endif


    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }

        /* Critical CSS for immediate layout */
        .split-screen-login {
            display: flex !important;
            min-height: 100vh !important;
            width: 100% !important;
        }

        .login-left-panel {
            flex: 1 !important;
            background: #f3661e !important;
            padding: 60px !important;
            position: relative !important;
        }

        .login-right-panel {
            flex: 1 !important;
            background: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 40px !important;
        }

        /* Mobile Logo - Hidden on Desktop */
        .mobile-logo-top {
            display: none !important;
            text-align: center !important;
            margin-bottom: 30px !important;
        }

        .mobile-logo-top img {
            max-width: 180px !important;
            height: auto !important;
        }

        /* Tablet and Mobile */
        @media (max-width: 991px) {
            .split-screen-login {
                flex-direction: column !important;
            }

            .login-left-panel {
                min-height: 250px !important;
                padding: 30px 20px !important;
            }

            .login-right-panel {
                padding: 30px 20px !important;
            }

            /* Show mobile logo on tablets/mobile */
            .mobile-logo-top {
                display: block !important;
            }

            .language-selector-top {
                top: 15px !important;
                right: 15px !important;
            }
        }

        /* Mobile Only */
        @media (max-width: 575px) {
            .login-left-panel {
                min-height: 200px !important;
                padding: 25px 15px !important;
            }

            .login-right-panel {
                padding: 25px 15px !important;
            }

            .login-form-container {
                max-width: 100% !important;
            }

            .login-header h2 {
                font-size: 24px !important;
            }

            .mobile-logo-top img {
                max-width: 140px !important;
            }
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
</head>

<body class="{{ $themeColor }}">
    <div class="split-screen-login">
        <!-- Left Side - Construction Branding -->
        <div class="login-left-panel">
            <div class="left-panel-overlay"></div>
            <div class="left-panel-content">
                <div class="brand-logo">
                    <img class="logo"
                        src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'ERPGo') }}" loading="lazy"/>
                </div>
                <div class="welcome-text">
                    <h1>{{ __('Welcome Back') }}</h1>
                    <p>{{ __('Staff Admin Dashboard') }}</p>
                </div>
                <div class="construction-features">
                    <div class="feature-item">
                        <i class="ti ti-clipboard-check"></i>
                        <span>All departmental Support</span>
                    </div>
                    <!-- <div class="feature-item">
                        <i class="ti ti-users"></i>
                        <span>{{ __('Team Collaboration') }}</span>
                    </div>
                    <div class="feature-item">
                        <i class="ti ti-chart-line"></i>
                        <span>{{ __('Real-time Analytics') }}</span>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right-panel">
            <div class="login-form-container">
                <div class="language-selector-top">
                    @yield('language-bar')
                </div>
                <div class="login-form-wrapper">
                    <!-- Mobile Logo (Dark on White) -->
                    <div class="mobile-logo-top">
                        @if ($setting['cust_darklayout'] == 'on')
                            <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                                alt="{{ config('app.name', 'ERPGo') }}" loading="lazy"/>
                        @else
                            <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                                alt="{{ config('app.name', 'ERPGo') }}" loading="lazy"/>
                        @endif
                    </div>
                    <div class="login-header">
                        <h2>{{ __('Sign In') }}</h2>
                        <p class="text-muted">{{ __('Enter your credentials to access your account') }}</p>
                    </div>
                    @yield('content')
                    <div class="login-footer-text">
                        <p>&copy; {{ date('Y') }} {{ App\Models\Utility::getValByName('footer_text') ? App\Models\Utility::getValByName('footer_text') : config('app.name', 'ERPGo') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- [ auth-signup ] end -->

<!-- Required Js -->
<script src="{{ asset('assets/js/vendor-all.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<script>
    feather.replace();
</script>

@if (\App\Models\Utility::getValByName('cust_darklayout') == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif


<script>
    feather.replace();
    var pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener("click", function () {
            if (
                !document.querySelector(".pct-customizer").classList.contains("active")
            ) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];

        c.addEventListener("click", function (event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }



    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
</script>
@stack('custom-scripts')
@if($setting['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif
</body>
</html>
