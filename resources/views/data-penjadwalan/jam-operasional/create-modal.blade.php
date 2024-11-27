<div class="modal fade" id="jamOperasionalModal" tabindex="-1" role="dialog" aria-labelledby="jamOperasionalModalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jamOperasionalModalTitle">Buat Jam Operasional</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="{{ route('jam.store') }}">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md">
              <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" placeholder="Tanggal Mulai" required>
              </div>
              <div class="form-group">
                <label for="hari_mulai">Hari Mulai</label>
                <select id="hari_mulai" name="hari_mulai" class="form-select" required>
                  <option disabled selected>-- Pilih Hari --</option>
                  @foreach ($enumDays as $hari)
                    <option value="{{ $hari }}">{{ ucfirst($hari) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" placeholder="Jam Mulai" required>
              </div>
            </div>
            <div class="col-md">
              <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" placeholder="Tanggal Selesai" required>
              </div>
              <div class="form-group">
                <label for="hari_selesai">Hari Selesai</label>
                <select id="hari_selesai" name="hari_selesai" class="form-select" required>
                  <option disabled selected>-- Pilih Hari --</option>
                  @foreach ($enumDays as $hari)
                    <option value="{{ $hari }}">{{ ucfirst($hari) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" placeholder="Jam Selesai" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
