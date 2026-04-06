<div class="modal fade" id="ajaxModel" tabindex="-1" aria-labelledby="modelHeading" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modelHeading">Tambah Partisipan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body col-md-12">
        <form id="FormPartisipan" name="FormPartisipan" class="form-horizontal">
            @csrf
          <input type="hidden" name="peserta_id" id="peserta_id">

          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input class="form-control form-control-sm" id="nama" name="name" type="text" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input class="form-control form-control-sm" id="email" name="email" type="email" required>
          </div>

          <div class="mb-3">
            <label for="sertif_id" class="form-label">Parent Sertif</label>
            <select class="form-control form-control-sm searchable-select" id="sertif_id" name="sertif_id" style="width: 100%;">
              <option value="">-- Pilih Sertif --</option>
            </select>
          </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
