@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left" class="py-2">
          <div class="auth-logo mb-5 mt-3">
            <img src="{{ asset('./assets/compiled/png/logo.png') }}" style="width: 200px; height: auto;" alt="Logo" />
          </div>
          <h1 class="auth-title">New Password</h1>
          <p class="auth-subtitle mb-4">Input new password.</p>

          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group position-relative has-icon-left mb-3">
              <input id="email" type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" name="email"
                value="{{ $email ?? old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
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
                <div class="input-group-text">
                  <button type="button" class="btn btn-link" id="togglePassword1">
                    <i class="bi bi-eye-fill" id="eyeIcon1"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-group has-icon-left mb-3">
              <div class="input-group">
                <div class="position-relative col">
                  <input id="password-confirm" type="password" class="form-control form-control-xl" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Confirm Password">
                  <div class="form-control-icon">
                    <i class="bi bi-shield-lock"></i>
                  </div>
                </div>
                <div class="input-group-text">
                  <button type="button" class="btn btn-link" id="togglePassword2">
                    <i class="bi bi-eye-fill" id="eyeIcon2"></i>
                  </button>
                </div>
              </div>
            </div>
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Reset Password</button>
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

  <script>
    // Toggle for password field
    document.getElementById('togglePassword1').addEventListener('click', function() {
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon1');
      togglePasswordVisibility(passwordField, eyeIcon);
    });

    // Toggle for confirm password field
    document.getElementById('togglePassword2').addEventListener('click', function() {
      const confirmPasswordField = document.getElementById('password-confirm');
      const eyeIcon = document.getElementById('eyeIcon2');
      togglePasswordVisibility(confirmPasswordField, eyeIcon);
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
