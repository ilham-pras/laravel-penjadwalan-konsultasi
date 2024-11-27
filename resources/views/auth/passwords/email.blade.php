@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left" class="py-2">
          <div class="auth-logo mb-5 mt-3">
            <img src="{{ asset('./assets/compiled/png/logo.png') }}" style="width: 200px; height: auto;" alt="Logo" />
          </div>
          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif
          <h1 class="auth-title">Forgot Password</h1>
          <p class="auth-subtitle mb-4">Input your email and we will send you reset password link.</p>

          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-3">
              <input id="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-envelope"></i>
              </div>
            </div>
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Send</button>
          </form>
          <div class="text-center mt-3 text-lg fs-4">
            <p class="text-gray-600">Remember your account? <a href="{{ route('login') }}" class="font-bold">Log in</a>.</p>
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
