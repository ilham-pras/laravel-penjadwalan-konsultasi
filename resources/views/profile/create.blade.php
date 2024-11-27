@extends('layouts.app')

@section('title', 'Lengkapi Profile')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left" class="py-2">
          <div class="auth-logo mb-5 mt-3">
            <a href="#">
              <img src="{{ asset('./assets/compiled/png/logo.png') }}" style="width: 200px; height: auto;" alt="Logo" />
            </a>
          </div>
          <h1 class="auth-title">Lengkapi Biodata</h1>
          <p class="auth-subtitle mb-4">Input your data to register to our website.</p>

          <form method="POST" action="{{ route('profile.store') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-3">
              <input id="perusahaan" type="text" class="form-control form-control-xl @error('perusahaan') is-invalid @enderror" name="perusahaan"
                value="{{ old('perusahaan') }}" placeholder="Nama Perusahaan" required autocomplete="perusahaan">
              @error('perusahaan')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-building"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-3">
              <input id="alamat" type="text" class="form-control form-control-xl @error('alamat') is-invalid @enderror" name="alamat"
                value="{{ old('alamat') }}" placeholder="Alamat" required autocomplete="alamat">
              @error('alamat')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-geo-alt"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-3">
              <input id="no_telp" type="text" class="form-control form-control-xl @error('no_telp') is-invalid @enderror" name="no_telp"
                value="{{ old('no_telp') }}" placeholder="Nomor Telepon" required autocomplete="no_telp">
              @error('no_telp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-telephone"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-3">
              <select id="jenis_kelamin" class="form-select form-select-lg @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                <option disabled selected>-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
              </select>
              @error('jenis_kelamin')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Submit</button>
          </form>
          <div class="text-center mt-3 text-lg fs-4">
            <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-bold">Log in</a>.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right" class="position-relative">
          <div class="position-absolute w-100 h-100">
            <img src="{{ asset('./assets/compiled/svg/login-icon2.svg') }}" style="position: absolute; top: 60px; left: 100px; width: 480px; height: auto;"
              alt="Logo" />
            <img src="{{ asset('./assets/compiled/svg/login-icon.svg') }}" style="position: absolute; bottom: 0; right: 20px;" alt="Logo" />
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
