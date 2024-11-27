@foreach ($jamOperasional as $jam)
  <div class="modal fade" id="editJamModal{{ $jam->id }}" tabindex="-1" aria-labelledby="editJamModalTitle" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editJamModalTitle">Edit Jam Operasional</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{ route('jam.update', $jam->id) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row">
              <div class="col-md">
                <div class="form-group">
                  <label for="tanggal_mulai">Tanggal Mulai</label>
                  <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $jam->tanggal_mulai }}" required>
                </div>
                <div class="form-group">
                  <label for="hari_mulai">Hari Mulai</label>
                  <select id="hari_mulai" name="hari_mulai" class="form-select" required>
                    <option disabled selected>-- Pilih Hari --</option>
                    @foreach ($enumDays as $hari)
                      <option value="{{ $hari }}" @if ($jam->hari_mulai == $hari) selected @endif>
                        {{ ucfirst($hari) }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="jam_mulai">Jam Mulai</label>
                  <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ $jam->jam_mulai }}" required>
                </div>
              </div>
              <div class="col-md">
                <div class="form-group">
                  <label for="tanggal_selesai">Tanggal Selesai</label>
                  <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ $jam->tanggal_selesai }}" required>
                </div>
                <div class="form-group">
                  <label for="hari_selesai">Hari Selesai</label>
                  <select id="hari_selesai" name="hari_selesai" class="form-select" required>
                    <option disabled selected>-- Pilih Hari --</option>
                    @foreach ($enumDays as $hari)
                      <option value="{{ $hari }}" @if ($jam->hari_selesai == $hari) selected @endif>
                        {{ ucfirst($hari) }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label for="jam_selesai">Jam Selesai</label>
                  <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ $jam->jam_selesai }}" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach
