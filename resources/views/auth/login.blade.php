@extends('layouts.app')

@section('title', 'Login')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="auth-logo">
            <a href="#"><img src="{{ asset('./assets/compiled/svg/logo.svg') }}" alt="Logo" /></a>
          </div>
          <h1 class="auth-title">Log in.</h1>
          <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
              <input type="email" id="email" name="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>
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
              <input type="password" id="password" name="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                placeholder="Password" required autocomplete="current-password">
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
              </div>
              <button type="button" class="btn btn-link position-absolute" id="togglePassword" style="right: 10px; top: 12px;">
                <i class="bi bi-eye-fill" id="eyeIcon"></i>
              </button>
            </div>
            <div class="form-check form-check-lg d-flex align-items-end">
              <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
              <label class="form-check-label text-gray-600" for="remember"> Keep me logged in </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
          </form>
          <div class="text-center mt-5 text-lg fs-4">
            <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="font-bold">Sign up</a></p>
            @if (Route::has('password.request'))
              <p><a class="font-bold" href="{{ route('password.request') }}">Forgot your password?</a></p>
            @endif
          </div>
        </div>
      </div>
      <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">
          <div class="alert alert-light-primary color-primary d-flex align-items-center px-3 py-2 mb-3">
            <div class="col">
              Admin:<br>
              email: admin.ilham@example.com<br>
              password: admin123<br>
            </div>
            <div class="col">
              User:<br>
              email: user.ilham@example.com<br>
              password: ilham123<br>
            </div>
            <div class="col">
              User:<br>
              email: user.maulana@example.com<br>
              password: maulana123<br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
      // Toggle the type attribute
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      // Toggle the eye icon
      eyeIcon.classList.toggle('bi-eye-fill');
      eyeIcon.classList.toggle('bi-eye-slash-fill');
    });
  </script>
@endsection
