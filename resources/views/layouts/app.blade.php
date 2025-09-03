<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'NISHIMOO') }} | @yield('title')</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" href="{{ asset('images/N.png') }}" type="image/png">

  <!-- FontAwesome CDN -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Vite -->
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  <!-- 既存のカスタムCSS（Sassへ移行中なら残してOK） -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
      <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
          <img src="{{ asset('images/NITCHYMOO.png') }}" alt="" class="nishimoo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left -->
          <ul class="navbar-nav ms-auto d-flex align-items-center h-100">
            @auth
              @if(!request()->is('admin/*'))
              <form action="{{ route('home') }}" method="get" class="d-flex align-items-center my-0">
                <input type="text" name="search" placeholder="search..." class="form-control form-control-sm">
              </form>
              @endif
            @endauth
          </ul>

          <!-- Right -->
          <ul class="navbar-nav mobile-center ms-auto gap-2">
            @guest
              @if (Route::has('login'))
              <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
              @endif
              @if (Route::has('register'))
              <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
              @endif
            @else
              <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link d-flex align-items-center">
                  <i class="fa-solid fa-house text-primary icon-sm"></i>&nbsp;HOME
                </a>
              </li>

              @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
              <li class="nav-item">
                <a href="{{ route('post.create') }}" class="nav-link d-flex align-items-center">
                  <i class="fa-solid fa-circle-plus text-secondary icon-sm"></i>&nbsp;ADD
                </a>
              </li>
              @endif

              <li class="nav-item">
                <a href="{{ route('faqs') }}" class="nav-link d-flex align-items-center">
                  <i class="fa-solid fa-circle-question text-secondary icon-sm"></i>&nbsp;FAQ
                </a>
              </li>

              <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link btn" href="#" role="button"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle avatar-sm">
                  @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                  @endif
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  @can('admin')
                  <a href="{{ route('admin.users') }}" class="dropdown-item">
                    <i class="fa-solid fa-user-gear"></i> Admin
                  </a>
                  <hr class="dropdown-divider">
                  @endcan

                  <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                    <i class="fa-solid fa-circle-user"></i> Profile
                  </a>

                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </div>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>

    <main class="mb-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12">
            @yield('content')
          </div>
        </div>
      </div>
    </main>
  </div>

  {{-- 独自JSは Vite に統合したので不要（@vite が読み込みます） --}}
  {{-- <script src="{{ asset('js/post-like.js') }}" defer></script>
  <script src="{{ asset('js/follow.js') }}" defer></script> --}}
  @stack('scripts')
</body>
</html>
