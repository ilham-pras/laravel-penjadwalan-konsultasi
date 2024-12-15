@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  @if (auth()->user()->role === 'admin')
    @include('layouts.sidebar')
  @endif

  <div id="main" class="layout-horizontal">
    @if (auth()->user()->role === 'admin')
      <header class="mb-4">
        <nav class="navbar navbar-expand navbar-light navbar-top">
          <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
              <i class="bi bi-justify fs-3"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto mb-lg-0"></ul>

              <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="user-menu d-flex">
                    <div class="user-name text-end me-2">
                      <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                      <p class="mb-0 text-sm text-gray-600">
                        @if (auth()->user()->role === 'admin')
                          Administrator
                        @elseif (auth()->user()->role === 'user')
                          Member
                        @endif
                      </p>
                    </div>
                    <div class="user-img d-flex align-items-center dropdown-toggle">
                      <div class="avatar avatar-md">
                        <img src="{{ asset('./assets/compiled/png/profile-picture.png') }}" alt="Profile Picture">
                      </div>
                    </div>
                  </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem">
                  <li>
                    <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                  </li>
                  <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="icon-mid bi bi-person me-2"></i>Profile</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                		  document.getElementById('logout-form').submit();">
                      <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                      Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                    </form>
                  </li>
                </ul>
              </div>
            </div>

          </div>
        </nav>
      </header>
    @elseif (auth()->user()->role === 'user')
      @include('layouts.navigation')
    @endif

    <div class="content-wrapper container">
      <div class="page-heading">
        <h3>Dashboard</h3>
      </div>

      <div class="page-content">
        <section class="row">
          <div class="col-12 col-lg-8">
            <div class="row">
              <div class="col-12">
                @if (session('status'))
                  <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                  </div>
                @endif
                <div class="card">
                  <div class="card-header">
                    <h4>Dashboard</h4>
                  </div>
                  <div class="card-body">
                    <!-- Menampilkan pesan berdasarkan peran user -->
                    @if (auth()->user()->role === 'admin')
                      <p>Halo, Selamat datang {{ Auth::user()->name }}</p>

                      <!-- Mengecek koneksi dengan Google Calendar -->
                      @php
                        $googleCalendarToken = DB::table('google_calendar_tokens')
                            ->where('user_id', auth()->user()->id)
                            ->first();
                      @endphp
                      @if (!$googleCalendarToken)
                        <div class=" py-2">
                          <p class="fw-bold">Anda belum terhubung dengan Google Calendar.</p>
                          <a href="{{ route('google.login') }}" class="btn btn-primary">Hubungkan Google Calendar</a>
                        </div>
                      @else
                        <div class="fw-bold">Anda sudah terhubung dengan Google Calendar.</div>
                      @endif

                      <!-- Mengecek koneksi dengan Zoom -->
                      @php
                        $zoomToken = DB::table('zoom_tokens')
                            ->where('user_id', auth()->user()->id)
                            ->first();
                      @endphp
                      @if (!$zoomToken)
                        <div class="py-2">
                          <p class="fw-bold">Anda belum terhubung dengan Zoom.</p>
                          <a href="{{ route('zoom.connect') }}" class="btn btn-primary">Hubungkan Zoom</a>
                        </div>
                      @else
                        <div class="fw-bold">Anda sudah terhubung dengan Zoom.</div>
                      @endif
                    @elseif (auth()->user()->role === 'user')
                      <p>Halo, Selamat datang {{ Auth::user()->name }}</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4">
            <div class="card">
              <div class="card-header pb-2 bg-primary">
                <h4 class="text-white">Jadwal Konsultasi</h4>
              </div>
              <div class="card-content pb-2" style="max-height: 600px; overflow-y: auto;">
                @if (count($events) > 0)
                  @foreach ($events as $event)
                    <div class="d-flex px-4 py-2">
                      <div class="name ms-2">
                        <h5 class="mb-1">{{ $event->jenis_konsultasi }}</h5>
                        <h6 class="text-muted mb-0">
                          {{ $event->start_date? \Carbon\Carbon::parse($event->start_date)->locale('id')->translatedFormat('l, d F Y'): '' }}
                        </h6>
                        <h6 class="text-muted mb-2">
                          {{ $event->start_date? \Carbon\Carbon::parse($event->start_date)->locale('id')->format('H:i'): '' }} -
                          {{ $event->end_date? \Carbon\Carbon::parse($event->end_date)->locale('id')->format('H:i'): '' }}
                        </h6>
                        <h6>
                          @if ($event['zoom_link'])
                            <a href="{{ $event['zoom_link'] }}" class="text-decoration-none" target="_blank">Join Zoom Meeting</a>
                          @else
                            Tidak ada link zoom
                          @endif
                        </h6>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="px-4 py-2">
                    <h5 class="mb-1 text-muted text-center">Tidak ada jadwal</h5>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <footer>
      <div class="container">
        <div class="footer clearfix mb-0 text-muted">
          <div class="float-start">
            <p>Copyright &copy; 2024 by Mazer</p>
          </div>
          <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="https://saugi.me">Saugi</a></p>
          </div>
        </div>
      </div>
    </footer>
  </div>
@endsection
