<div class="card">
  <div class="card-header">
    <h3>Jam Operasional</h3>
  </div>
  <div class="card-body">
    {{-- @if (auth()->user()->role === 'admin')
      <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#jamOperasionalModal">Tambah</button>
    @endif --}}
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          @foreach ($jamOperasional as $jam)
            <tr>
              <th class="align-middle" style="width: 35%">
                {{ $jam->tanggal_mulai? \Carbon\Carbon::parse($jam->tanggal_mulai)->locale('id')->translatedFormat('d F Y'): '' }} s/d
                {{ $jam->tanggal_selesai? \Carbon\Carbon::parse($jam->tanggal_selesai)->locale('id')->translatedFormat('d F Y'): '' }}
              </th>
              <th class="align-middle" style="width: 20%">
                {{ $jam->hari_mulai }} - {{ $jam->hari_selesai }} <br>
                Jam : {{ $jam->jam_mulai? \Carbon\Carbon::parse($jam->jam_mulai)->locale('id')->format('H:i'): '' }} -
                {{ $jam->jam_selesai? \Carbon\Carbon::parse($jam->jam_selesai)->locale('id')->format('H:i'): '' }}
              </th>
              <th class="align-middle"></th>
              <th class="align-middle text-end"></th>
            </tr>
          @endforeach
        </thead>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="jamOperasionalModal" tabindex="-1" role="dialog" aria-labelledby="jamOperasionalModalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jamOperasionalModalTitle">Buat Jam Operasional</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <form action="#">
        <div class="modal-body">
          <div class="row">
            <div class="col-md">
              <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" placeholder="Tanggal Mulai">
              </div>
              <div class="form-group">
                <label for="jam_mulai">Jam</label>
                <input type="time" class="form-control" id="jam_mulai" placeholder="Jam Mulai">
              </div>
            </div>
            <div class="col-md">
              <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tanggal_selesai" placeholder="Tanggal Selesai">
              </div>
              <div class="form-group">
                <label for="jam_selesai">Jam</label>
                <input type="time" class="form-control" id="jam_selesai" placeholder="Tanggal Selesai">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Tutup</span>
          </button>
          <button type="button" class="btn btn-primary ms-1" data-bs-dismiss="modal">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Simpan</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
