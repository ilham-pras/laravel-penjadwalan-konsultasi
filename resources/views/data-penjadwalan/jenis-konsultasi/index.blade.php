@extends('layouts.app')

@section('title', 'Jenis Konsultasi')

@section('content')
  @include('layouts.sidebar')

  <div id="main" class="layout-horizontal">
    <header class="mb-4">
      <div class="header-top">
        <div class="container justify-content-end">
          <div class="row justify-content-start align-items-center"></div>

          <div class="header-top-right">
            <div class="dropdown">
              <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
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
                </div>
              </a>

              <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown" style="min-width: 11rem">
                <li>
                  <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}!</h6>
                </li>
                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="icon-mid bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>Settings</a></li>
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

            <!-- Burger button responsive -->
            <a href="#" class="burger-btn d-block d-xl-none">
              <i class="bi bi-justify fs-3"></i>
            </a>
          </div>
        </div>
      </div>
    </header>

    <div class="content-wrapper container">
      <div class="page-heading">
        <div class="page-title">
          <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
              <h3>Jenis Konsultasi</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="page-content">
        <section class="section">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <h5 class="card-title">Jenis Konsultasi</h5>
              <button type="button" class="btn icon icon-left btn-primary" data-bs-toggle="modal" data-bs-target="#createJenisModal">
                <i data-feather="plus"></i>
                Tambah Jenis Konsultasi
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover" id="table1">
                  <thead class="table-light">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Jenis Konsultasi</th>
                      <th class="text-center">Durasi</th>
                      <th class="text-center" style="width: 15%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (count($jenisKonsultasi) > 0)
                      @foreach ($jenisKonsultasi as $jenis)
                        <tr>
                          <td class="align-middle text-center">{{ $loop->iteration }}</td>
                          <td class="align-middle text-center">{{ $jenis->konsultasi }}</td>
                          <td class="align-middle text-center">{{ $jenis->formatted_durasi }}</td>
                          <td class="align-middle text-center justify-content-between">
                            <button class="btn btn-sm btn-primary icon icon-left" data-bs-toggle="modal" data-bs-target="#editJenisModal{{ $jenis->id }}">
                              <i data-feather="edit"></i>
                              Edit
                            </button>
                            <button class="btn btn-sm btn-danger icon icon-left" onclick="confirmDelete({{ $jenis->id }})">
                              <i data-feather="trash"></i>
                              Delete
                            </button>
                            <!-- Form untuk Hapus -->
                            <form id="delete-form-{{ $jenis->id }}" method="POST" action="{{ route('jenis.destroy', $jenis->id) }}" style="display: none;">
                              @csrf
                              @method('DELETE')
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4" class="text-center h5">-- Tidak Ada Jenis Konsultasi --</td>
                      </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </div>

      @include('data-penjadwalan.jenis-konsultasi.create-modal')
      @include('data-penjadwalan.jenis-konsultasi.edit-modal')
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

@section('script')
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
  </script>
@endsection
