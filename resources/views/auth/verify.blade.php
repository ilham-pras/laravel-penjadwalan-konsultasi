@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
  <div id="main" class="layout-horizontal navbar-fixed">
    @include('layouts.navigation')

    <div class="content-wrapper container">
      <div class="page-heading">
        <h3>Verify Email</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col-12">
            <div class="row">
              <div class="col-12">
                @if (session('resent'))
                  <div class="alert alert-light-success color-success" role="alert">
                    <i class="bi bi-check-circle"></i>
                    {{ __(' A new verification link has been sent to your email address.') }}
                  </div>
                @endif
                <div class="card">
                  <div class="card-header">
                    <h4>Verify Your Email Address</h4>
                  </div>
                  <div class="card-body">

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email, click the button below.') }}
                    <div>
                      <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-2 align-baseline">{{ __('Resend Verification Email') }}</button>.
                      </form>
                    </div>
                  </div>
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
@endsection
