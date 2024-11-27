@extends('layouts.app')

@section('title', 'Login')

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
          <h1 class="auth-title">Log in.</h1>
          <p class="auth-subtitle mb-4">Log in with your data that you entered during registration.</p>

          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group position-relative has-icon-left mb-3">
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

            <div class="form-group has-icon-left mb-3">
              <div class="input-group">
                <div class="position-relative col">
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
                </div>
                <div class="input-group-text">
                  <button type="button" class="btn btn-link" id="togglePassword">
                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-check form-check-lg d-flex align-items-end">
              <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
              <label class="form-check-label text-gray-600" for="remember"> Keep me logged in </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
          </form>
          <div class="text-center mt-3 text-lg fs-4">
            <p class="text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="font-bold">Sign up</a></p>
            @if (Route::has('password.request'))
              <p><a class="font-bold" href="{{ route('password.request') }}">Forgot your password?</a></p>
            @endif
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

  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');
      togglePasswordVisibility(passwordField, eyeIcon);
    });

    // Function to toggle password visibility
    function togglePasswordVisibility(field, icon) {
      const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
      field.setAttribute('type', type);

      // Toggle the eye icon
      icon.classList.toggle('bi-eye-fill');
      icon.classList.toggle('bi-eye-slash-fill');
    }
  </script>
@endsection
