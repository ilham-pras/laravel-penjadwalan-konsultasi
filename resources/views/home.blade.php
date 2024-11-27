@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div id="main" class="layout-horizontal">
    @include('layouts.navigation')

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
                      @if (auth()->user()->google_id == null)
                        <div class=" py-2">
                          <p>Anda belum terhubung dengan Google Calendar.</p>
                          <a href="{{ route('google.login') }}" class="btn btn-primary">Hubungkan Google Calendar</a>
                        </div>
                      @else
                        Anda sudah terhubung dengan Google Calendar.
                      @endif

                      @php
                        $zoomToken = DB::table('zoom_tokens')
                            ->where('user_id', auth()->user()->id)
                            ->first();
                      @endphp
                      @if (!$zoomToken)
                        <div class="py-2">
                          <p>Anda belum terhubung dengan Zoom.</p>
                          <a href="{{ route('zoom.connect') }}" class="btn btn-primary">Hubungkan Zoom</a>
                        </div>
                      @else
                        <p>Anda sudah terhubung dengan Zoom.</p>
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
            <p>2023 &copy; Mazer</p>
          </div>
          <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="https://saugi.me">Saugi</a></p>
          </div>
        </div>
      </div>
    </footer>
  </div>
@endsection
