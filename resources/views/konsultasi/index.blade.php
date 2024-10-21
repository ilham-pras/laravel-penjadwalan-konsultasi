@extends('layouts.app')

@section('title', 'Konsultasi')

@section('content')
  <div id="main" class="layout-horizontal">
    @include('layouts.navigation')

    <div class="content-wrapper container">
      <div class="page-heading">
        <h3>Konsultasi</h3>
      </div>
      <div class="page-content">
        <section class="row">
          <div class="col">

            <div class="card">
              <div class="card-header">
                <h4>Jadwal Konsultasi</h4>
              </div>
              <div class="card-body">
                <div>
                  <table class="table table-bordered table-hover">
                    <thead class="table-secondary">
                      <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nama lengkap</th>
                        <th scope="col" class="text-center">Perusahaan</th>
                        <th scope="col" class="text-center">Tanggal</th>
                        <th scope="col" class="text-center">Jam</th>
                        <th scope="col" class="text-center">Link Zoom</th>
                        <th scope="col" class="text-center">Hubungi</th>
                        <th scope="col" class="text-center" style="width: 10%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if (count($events) > 0)
                        @foreach ($events as $event)
                          <tr>
                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $event['nama_lengkap'] }}</td>
                            <td class="align-middle">{{ $event['perusahaan'] }}</td>
                            <td class="align-middle">
                              {{ $event['start']? \Carbon\Carbon::parse($event['start'])->locale('id')->translatedFormat('d F Y'): '' }}
                            </td>
                            <td class="align-middle">
                              {{ $event['start']? \Carbon\Carbon::parse($event['start'])->locale('id')->format('H:i'): '' }} -
                              {{ $event['end']? \Carbon\Carbon::parse($event['end'])->locale('id')->format('H:i'): '' }}
                            </td>
                            <td class="align-middle text-center text-primary">
                              @if ($event['zoom_link'])
                                <a href="{{ $event['zoom_link'] }}" class="text-decoration-none" target="_blank">Join Zoom Meeting</a>
                              @else
                                Tidak ada
                              @endif
                            </td>
                            <td class="align-middle">
                              {{ $event['no_telp'] }}
                            </td>
                            <td class="align-middle text-center">
                              <a href="{{ route('konsultasi.edit', $event['id']) }}">
                                <div class="btn btn-sm btn-primary">Edit</div>
                              </a>
                              <a href="{{ route('konsultasi.destroy', $event['id']) }}">
                                <div class="btn btn-sm btn-danger">Hapus</div>
                              </a>
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="7" class="text-center h2 py-4">-- Tidak Ada Event --</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
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
          <div class="float-start_date">
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
