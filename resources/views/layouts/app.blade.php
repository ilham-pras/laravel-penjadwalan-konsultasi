<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <link rel="shortcut icon" href="{{ asset('./assets/compiled/png/favicon.png') }}" type="image/png" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/app.css') }}" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/app-dark.css') }}" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/auth.css') }}" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/iconly.css') }}" />

  <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}" />

  <link rel="stylesheet" href="{{ asset('assets/extensions/simple-datatables/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/table-datatable.css') }}" />
  {{-- <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('./assets/compiled/css/table-datatable-jquery.css') }}" /> --}}

  <style>
    .content-wrapper {
      flex: 1;
    }

    footer {
      padding: 10px 0;
    }
  </style>
  @yield('css')
</head>

<body>
  <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

  <div id="app">
    <main>
      @yield('content')
    </main>
  </div>

  <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
  <script src="{{ asset('assets/static/js/pages/horizontal-layout.js') }}"></script>
  <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

  <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/flatpickr/l10n/id.js') }}"></script>

  <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

  <script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/static/js/pages/simple-datatables.js') }}"></script>
  {{-- <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script> --}}

  {{-- <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script> --}}
  <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script>
  <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  </script>
  @yield('script')
</body>

</html>
