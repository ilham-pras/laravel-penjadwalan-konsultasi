@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left" class="py-2">
          <div class="auth-logo mb-5 mt-3">
            <a href="{{ route('home') }}">
              <img src="{{ asset('./assets/compiled/png/logo.png') }}" style="width: 200px; height: auto;" alt="Logo" />
            </a>
          </div>
          <h1 class="auth-title fs-1 mb-5">Verify Your Email Address</h1>
          <p class="auth-subtitle">Sebelum melanjutkan, harap periksa email Anda untuk link verifikasi.</p>
          <p class="auth-subtitle">Jika Anda tidak menerima email, klik tombol di bawah.</p>
          <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Resend Verification Email</button>.
          </form>
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
