{{-- Modal Tabel Jadwal --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="scheduleModalLabel">Jadwal Konsultasi</h5>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-bordered">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Booked</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Jam</th>
            </tr>
          </thead>
          <tbody id="allEvent">
            {{-- Menggunakan JQuery di dateClick --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Jadwalku</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-bordered">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nama Lengkap</th>
              <th class="text-center">Perusahaan</th>
              <th class="text-center">Jenis Konsultasi</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Jam</th>
              <th class="text-center">Link Zoom</th>
            </tr>
          </thead>
          <tbody id="myEvent">
            {{-- Menggunakan JQuery di dateClick --}}
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="openEventModal">Buat Jadwal</button>
      </div>
    </div>
  </div>
</div>

{{-- Input Modal --}}
<div class="modal fade text-left" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  {{-- Modal Jam Operasional --}}
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">Jam Operasional</h5>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              @foreach ($jamOperasional as $jam)
                <tr>
                  <th class="align-middle">
                    {{ $jam->tanggal_mulai? \Carbon\Carbon::parse($jam->tanggal_mulai)->locale('id')->translatedFormat('d F Y'): '' }} s/d
                    {{ $jam->tanggal_selesai? \Carbon\Carbon::parse($jam->tanggal_selesai)->locale('id')->translatedFormat('d F Y'): '' }}
                  </th>
                  <th class="align-middle">
                    {{ $jam->hari_mulai }} - {{ $jam->hari_selesai }} <br>
                    Jam : {{ $jam->jam_mulai? \Carbon\Carbon::parse($jam->jam_mulai)->locale('id')->format('H:i'): '' }} -
                    {{ $jam->jam_selesai? \Carbon\Carbon::parse($jam->jam_selesai)->locale('id')->format('H:i'): '' }}
                  </th>
                </tr>
              @endforeach
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  {{-- Modal Buat Jadwal --}}
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="eventModalLabel">Buat Jadwal Konsultasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md">
            <div class="form-group">
              <input type="hidden" id="booked" value="Booked">
            </div>
            <div class="form-group">
              <label for="nama_lengkap">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div class="form-group">
              <label for="perusahaan">Nama Perusahaan</label>
              <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ $profile->perusahaan ?? 'Perusahaan belum diisi' }}"
                readonly>
            </div>
            <div class="form-group">
              <label for="jenis_konsultasi">Jenis Konsultasi</label>
              <select class="form-select" id="jenis_konsultasi" name="jenis_konsultasi" required>
                <option disabled value selected>-- Pilih Jenis Konsultasi --</option>
                @foreach ($durasiKonsultasi as $jenis)
                  <option value="{{ $jenis->konsultasi }}" data-duration="{{ $jenis->durasi }}">
                    {{ $jenis->konsultasi }} - {{ $jenis->formatted_durasi }}
                  </option>
                @endforeach
              </select>
              <input type="hidden" id="durasi_konsultasi" name="durasi_konsultasi">
            </div>
            <div class="form-group row justify-content-between align-items-center">
              <label class="form-label">Tanggal dan Waktu</label>
              <div class="col">
                <label for="tanggalWaktu_mulai">Mulai</label>
                <input type="datetime-local" class="form-control" id="tanggalWaktu_mulai" name="tanggalWaktu_mulai" value="" required>
              </div>
              <div class="col">
                <label for="tanggalWaktu_selesai">Selesai</label>
                <input type="datetime-local" class="form-control" id="tanggalWaktu_selesai" name="tanggalWaktu_selesai" value="" readonly disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="deskripsi">Deskripsi</label>
              <textarea placeholder="Tambahkan Deskripsi" class="form-control" id="deskripsi" name="deskripsi"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-auto" style="display: none" id="deleteEventBtn">Hapus Booking</button>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" id="backToScheduleModal">Kembali</button>
        <button type="button" class="btn btn-primary" id="saveEventBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>
