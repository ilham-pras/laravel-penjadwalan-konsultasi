@extends('layouts.app')

@section('title', 'Jam Operasional')

@section('css')
  {{-- Flatpicker --}}
  <link href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css" rel="stylesheet" type="text/css">
@endsection

@section('content')
  @include('layouts.sidebar')

  <div id="main" class="layout-horizontal">
    <header class="mb-4">
      <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
          <a href="#" class="burger-btn d-block">
            <i class="bi bi-justify fs-3"></i>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-lg-0"></ul>

            <div class="dropdown">
              <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-menu d-flex">
                  <div class="user-name text-end me-2">
                    <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                    <p class="mb-0 text-sm text-gray-600">
                      @if (auth()->user()->role === 'admin')
                        Administrator
                      @elseif (auth()->user()->role === 'user')
                        Member
                      @endif
                    </p>
                  </div>
                  <div class="user-img d-flex align-items-center dropdown-toggle">
                    <div class="avatar avatar-md">
                      <img src="{{ asset('./assets/compiled/png/profile-picture.png') }}" alt="Profile Picture">
                    </div>
                  </div>
                </div>
              </a>

              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem">
                <li>
                  <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                </li>
                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="icon-mid bi bi-person me-2"></i>Profile</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="icon-mid bi bi-box-arrow-left me-2"></i>
                    Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                </li>
              </ul>
            </div>
          </div>

        </div>
      </nav>
    </header>

    <div class="content-wrapper container">
      <div class="page-heading">
        <div class="page-title">
          <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
              <h3>Jam Operasional</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="page-content">
        <section class="section">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h5 class="card-title">Jam Operasional</h5>
              <button type="button" class="btn icon icon-left btn-primary" data-bs-toggle="modal" data-bs-target="#jamOperasionalModal">
                <i data-feather="plus"></i>
                Tambah Jam Operasional
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover" id="table1">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Tanggal</th>
                      <th class="text-center">Hari</th>
                      <th class="text-center" style="width: 20%">Jam</th>
                      <th class="text-center" style="width: 15%">Opsi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($jamOperasional) > 0)
                      @foreach ($jamOperasional as $jam)
                        <tr>
                          <td class="align-middle text-center">{{ $loop->iteration }}</td>
                          <td class="align-middle text-center">
                            {{ $jam->tanggal_mulai? \Carbon\Carbon::parse($jam->tanggal_mulai)->locale('id')->translatedFormat('d F Y'): '' }} s/d
                            {{ $jam->tanggal_selesai? \Carbon\Carbon::parse($jam->tanggal_selesai)->locale('id')->translatedFormat('d F Y'): '' }}
                          </td>
                          <td class="align-middle text-center">
                            {{ $jam->hari_mulai }} - {{ $jam->hari_selesai }}
                          </td>
                          <td class="align-middle text-center">
                            {{ $jam->jam_mulai? \Carbon\Carbon::parse($jam->jam_mulai)->locale('id')->format('H:i'): '' }} -
                            {{ $jam->jam_selesai? \Carbon\Carbon::parse($jam->jam_selesai)->locale('id')->format('H:i'): '' }}
                          </td>
                          <td class="align-middle text-center justify-content-between">
                            <button class="btn icon icon-left btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editJamModal{{ $jam->id }}">
                              <i data-feather="edit"></i>
                              Edit
                            </button>
                            <button class="btn icon icon-left btn-sm btn-danger" onclick="confirmDelete({{ $jam->id }})">
                              <i data-feather="trash"></i>
                              Delete
                            </button>
                            <!-- Form untuk Hapus -->
                            <form id="delete-form-{{ $jam->id }}" method="POST" action="{{ route('jam.destroy', $jam->id) }}" style="display: none;">
                              @csrf
                              @method('DELETE')
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="5" class="text-center h5">-- Tidak Ada Jam Operasional --</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </div>

      @include('data-penjadwalan.jam-operasional.create-modal')
      @include('data-penjadwalan.jam-operasional.edit-modal')
    </div>

    <footer>
      <div class="container">
        <div class="footer clearfix mb-0 text-muted">
          <div class="float-start">
            <p>Copyright &copy; 2024 by Mazer</p>
          </div>
          <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="https://saugi.me">Saugi</a></p>
          </div>
        </div>
      </div>
    </footer>
  </div>
@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/locale/id.js"></script>

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

    function confirmDelete(id) {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Submit form untuk menghapus
          document.getElementById(`delete-form-${id}`).submit();
        }
      });
    }

    const startDatePicker = flatpickr('#tanggal_mulai', {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "j F Y",
      locale: "id",
    });
    const endDatePicker = flatpickr('#tanggal_selesai', {
      dateFormat: "Y-m-d",
      altInput: true,
      altFormat: "j F Y",
      locale: "id",
    });

    const startTimePicker = flatpickr('#jam_mulai', {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      locale: "id",
    });
    const endTimePicker = flatpickr('#jam_selesai', {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      locale: "id",
    });
  </script>
@endsection
