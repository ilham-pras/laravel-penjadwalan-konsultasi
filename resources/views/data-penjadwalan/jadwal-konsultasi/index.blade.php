@extends('layouts.app')

@section('title', 'Jadwal Konsultasi')

@section('content')
  @include('layouts.sidebar')

  <div id="main" class="layout-horizontal">
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

    <div class="content-wrapper container">
      <div class="page-heading">
        <div class="page-title">
          <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
              <h3>Jadwal Konsultasi</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="page-content">
        <section class="section">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h5 class="card-title">Jadwal Konsultasi</h5>
              {{-- <button type="button" class="btn icon icon-left btn-primary" data-bs-toggle="modal" data-bs-target="#createJadwalModal">
                <i data-feather="plus"></i>
                Tambah Jadwal Konsultasi
              </button> --}}
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover" id="table1">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nama lengkap</th>
                      <th class="text-center">Perusahaan</th>
                      <th class="text-center">Konsultasi</th>
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">Jam</th>
                      <th class="text-center">Link Zoom</th>
                      <th class="text-center">Hubungi</th>
                      {{-- <th class="text-center" style="width: 15%">Action</th> --}}
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($events) > 0)
                      @foreach ($events as $event)
                        <tr>
                          <td class="align-middle text-center">{{ $loop->iteration }}</td>
                          <td class="align-middle">{{ $event['nama_lengkap'] }}</td>
                          <td class="align-middle">{{ $event['perusahaan'] }}</td>
                          <td class="align-middle">{{ $event['jenis_konsultasi'] }}</td>
                          <td class="align-middle">
                            {{ $event['start']? \Carbon\Carbon::parse($event['start'])->locale('id')->translatedFormat('d F Y'): '' }}
                          </td>
                          <td class="align-middle">
                            {{ $event['start']? \Carbon\Carbon::parse($event['start'])->locale('id')->format('H:i'): '' }} -
                            {{ $event['end']? \Carbon\Carbon::parse($event['end'])->locale('id')->format('H:i'): '' }}
                          </td>
                          <td class="align-middle text-center text-primary">
                            @if ($event['zoom_link'])
                              <a href="{{ $event['zoom_link'] }}" class="text-decoration-none" target="_blank">Join Zoom Meeting</a>
                            @else
                              Tidak ada
                            @endif
                          </td>
                          <td class="align-middle">
                            {{ $profile['no_telp'] }}
                          </td>
                          {{-- <td class="align-middle text-center d-flex justify-content-between">
                            <button class="btn icon icon-left btn-sm btn-primary me-1" data-bs-toggle="modal">
                              <i data-feather="edit"></i>
                            </button>
                            <button class="btn icon icon-left btn-sm btn-danger">
                              <i data-feather="trash"></i>
                            </button>
                          </td> --}}
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="8" class="text-center h5">-- Tidak Ada Jadwal --</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
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

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/id.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @if (session('success'))
        Swal.fire({
          title: 'Berhasil!',
          text: "{{ session('success') }}",
          icon: 'success',
          confirmButtonText: 'OK'
        });
      @elseif (session('error'))
        Swal.fire({
          title: 'Gagal!',
          text: "{{ session('error') }}",
          icon: 'error',
          confirmButtonText: 'OK'
        });
      @endif
    });

    function confirmDelete(id) {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Submit form untuk menghapus
          document.getElementById(`delete-form-${id}`).submit();
        }
      });
    }
  </script>
@endsection
