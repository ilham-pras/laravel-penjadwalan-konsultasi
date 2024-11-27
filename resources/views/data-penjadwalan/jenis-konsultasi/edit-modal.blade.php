@foreach ($jenisKonsultasi as $jenis)
  <div class="modal fade" id="editJenisModal{{ $jenis->id }}" tabindex="-1" role="dialog" aria-labelledby="editJenisModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editJenisModalTitle">Edit Jenis Konsultasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{ route('jenis.update', $jenis->id) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row">
              <div class="col-md">
                <div class="form-group">
                  <label for="konsultasi">Jenis Konsultasi</label>
                  <input type="text" class="form-control" id="konsultasi" name="konsultasi" value="{{ $jenis->konsultasi }}" required>
                </div>
                <div class="row">
                  <div class="col-md">
                    <div class="form-group">
                      <label for="durasi_jam">Durasi Jam</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="durasi_jam" name="durasi_jam" value="{{ floor($jenis->durasi / 60) }}" min="0"
                          required>
                        <span class="input-group-text">Jam</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md">
                    <div class="form-group">
                      <label for="durasi_menit">Durasi Menit</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="durasi_menit" name="durasi_menit" value="{{ $jenis->durasi % 60 }}" min="0"
                          max="59" required>
                        <span class="input-group-text">Menit</span>
                      </div>
                    </div>
                  </div>
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
