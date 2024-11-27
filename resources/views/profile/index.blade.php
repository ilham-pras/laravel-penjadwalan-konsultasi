@extends('layouts.app')

@section('title', 'Profile')

@section('content')
  <div id="main" class="layout-horizontal">
    @include('layouts.navigation')

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
              <a href="{{ route('google.login') }}" class="btn btn-primary">Hubungkan Google Calendar</a>
              <a href="{{ route('zoom.connect') }}" class="btn btn-primary">Hubungkan Zoom</a>
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
            <p>2023 &copy; Mazer</p>
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
