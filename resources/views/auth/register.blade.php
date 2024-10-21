@extends('layouts.app')

@section('title', 'Register')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="auth-logo">
            <a href="#"><img src="{{ asset('./assets/compiled/svg/logo.svg') }}" alt="Logo" /></a>
          </div>
          <h1 class="auth-title">Sign Up</h1>
          <p class="auth-subtitle mb-5">Input your data to register to our website.</p>

          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="name" type="text" class="form-control form-control-xl @error('name') is-invalid @enderror" name="name"
                value="{{ old('name') }}" placeholder="Nama Lengkap" required autocomplete="name">
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-person"></i>
              </div>
            </div>

            <div class="form-group position-relative has-icon-left mb-4">
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
            <div class="form-group position-relative has-icon-left mb-4">
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
            <div class="form-group position-relative has-icon-left mb-4">
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

            <div class="form-group position-relative has-icon-left mb-4">
              <input id="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-envelope"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="password" type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" name="password"
                placeholder="Password" required autocomplete="new-password">
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
              <input id="password-confirm" type="password" class="form-control form-control-xl" name="password_confirmation" required autocomplete="new-password"
                placeholder="Confirm Password">
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
          </form>
          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-bold">Log in</a>.</p>
          </div>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
      </div>
    </div>
  </div>
@endsection
