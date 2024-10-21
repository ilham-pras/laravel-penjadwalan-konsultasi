{{-- Modal Tabel Jadwal --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">Jadwal Konsultasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-head-fixed">
          <thead class="table-secondary">
            <tr>
              <th scope="col" class="text-center">#</th>
              <th scope="col" class="text-center">Booked</th>
              <th scope="col" class="text-center">Tanggal</th>
              <th scope="col" class="text-center">Jam</th>
            </tr>
          </thead>
          <tbody id="allEvent">
            {{-- Menggunakan JQuery di dateClick --}}
          </tbody>
        </table>

        <div class="py-2"></div>

        <h5 class="modal-title pb-2">Jadwalku</h5>
        <table class="table table-bordered table-head-fixed">
          <thead class="table-secondary">
            <tr>
              <th scope="col" class="text-center">#</th>
              <th scope="col" class="text-center">Nama Lengkap</th>
              <th scope="col" class="text-center">Perusahaan</th>
              <th scope="col" class="text-center">Jenis Konsultasi</th>
              <th scope="col" class="text-center">Tanggal</th>
              <th scope="col" class="text-center">Jam</th>
              <th scope="col" class="text-center">Link Zoom</th>
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
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Jadwal Konsultasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div>
          <input type="hidden" id="booked" value="Booked">
        </div>
        <div>
          <label for="nama_lengkap" class="form-label pt-2">Nama Lengkap</label>
          <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ auth()->user()->name }}" readonly>
        </div>
        <div>
          <label for="perusahaan" class="form-label pt-2">Nama Perusahaan</label>
          <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ auth()->user()->perusahaan }}" readonly>
        </div>
        <div>
          <label for="jenis_konsultasi" class="form-label pt-2">Jenis Konsultasi</label>
          <select class="form-control" id="jenis_konsultasi" name="jenis_konsultasi" required>
            <option disabled value selected>-- Pilih Jenis Konsultasi --</option>
            <option value="Pilihan 1" data-duration="60">Pilihan 1 - 1 Jam</option>
            <option value="Pilihan 2" data-duration="90">Pilihan 2 - 1 Jam 30 Menit</option>
            <option value="Pilihan 3" data-duration="120">Pilihan 3 - 2 Jam</option>
            <option value="Pilihan 4" data-duration="30">Pilihan 4 - 30 Menit</option>
            <option value="Pilihan 5" data-duration="45">Pilihan 5 - 45 Menit</option>
          </select>
        </div>
        <div class="row justify-content-between align-items-center">
          <label class="form-label pt-2">Tanggal dan Waktu</label>
          <div class="col">
            <label for="tanggalWaktu_mulai" class="pt-1">Mulai</label>
            <input type="datetime-local" class="form-control" id="tanggalWaktu_mulai" name="tanggalWaktu_mulai" value="" required>
          </div>
          <div class="col">
            <label for="tanggalWaktu_selesai" class="pt-1">Selesai</label>
            <input type="datetime-local" class="form-control" id="tanggalWaktu_selesai" name="tanggalWaktu_selesai" value="" readonly disabled>
          </div>
        </div>
        <div>
          <label for="deskripsi" class="form-label pt-2">Deskripsi</label>
          <textarea placeholder="Tambahkan Deskripsi" class="form-control" id="deskripsi" name="deskripsi"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-auto" style="display: none" id="deleteEventBtn">Hapus Acara</button>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" id="backToScheduleModal">Kembali</button>
        <button type="button" class="btn btn-primary" id="saveEventBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>
