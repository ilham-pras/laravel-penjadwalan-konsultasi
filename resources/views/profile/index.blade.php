@extends('layouts.app')

@section('title', 'Profile')

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
        <h3>Profile</h3>
      </div>

      <div class="page-content">
        <section id="basic-vertical-layouts" class="row">
          <div class="col-md-4 col-12">
            <div class="card">
              <div class="card-content">
                <div class="card-body">
                  <form class="form form-vertical">
                    <div class="form-body">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group text-center">
                            <div class="mb-3">
                              <img src="{{ asset('./assets/compiled/png/profile-picture.png') }}" alt="Profile Picture" class="rounded-circle"
                                style="width: 150px; height: 150px;">
                            </div>
                            <h4>{{ auth()->user()->name }}</h4>
                            <p>
                              @if (auth()->user()->role === 'admin')
                                Administrator
                              @elseif (auth()->user()->role === 'user')
                                Member
                              @endif
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              @if (auth()->user()->role === 'admin')
                <a href="{{ route('google.login') }}" class="btn btn-primary">Hubungkan Google Calendar</a>
                <a href="{{ route('zoom.connect') }}" class="btn btn-primary">Hubungkan Zoom</a>
              @endif
            </div>
          </div>
          <div class="col-md col-12">
            <div class="card">
              <div class="card-content">
                <div class="card-body">
                  <form class="form form-vertical" method="POST" action="{{ route('profile.update', $userProfile->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ auth()->user()->name }}" />
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly />
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="perusahaan">Perusahaan</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ $userProfile->perusahaan ?? '' }}" />
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat">{{ $userProfile->alamat ?? '' }}</textarea>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="no_telp">No Telpon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{ $userProfile->no_telp ?? '' }}" />
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" class="form-select" name="jenis_kelamin" required>
                              <option disabled {{ empty($userProfile->jenis_kelamin) ? 'selected' : '' }}>-- Pilih Jenis Kelamin --</option>
                              <option value="Laki-laki" {{ $userProfile->jenis_kelamin === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                              <option value="Perempuan" {{ $userProfile->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-12 d-flex justify-content-start">
                          <button type="submit" class="btn btn-primary me-1 mt-3">Simpan Perubahan</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
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
  </script>
@endsection
