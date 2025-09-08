<div class="modal fade" id="ajaxModel" tabindex="-1" aria-labelledby="tambah" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambah">Tambah Sertif</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body col-md-12">
        <form id="Formsertif" name="Formsertif" class="form-horizontal">
            @csrf
          <input type="hidden" name="sertif_id" id="sertif_id">
          <input id="file_edit" name="file_edit" type="hidden">
          <div class="mb-3" id="file_upload">
            <label for="formFileSm" name="file" class="form-label">Upload</label>
            <input class="form-control form-control-sm" id="formFileSm" name="file" type="file">
            <div class="alert-message alert-warning" id="fileError"></div>
          </div>
          <!-- Excel Upload Section -->
          <div class="mb-3" id="excel_upload">
            <label for="excelFile" class="form-label">Upload Data Excel</label>
            <input class="form-control form-control-sm" id="excelFile" name="excel_file" type="file" accept=".xlsx,.xls,.csv">
            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
            <div class="alert-message alert-warning" id="excelError"></div>
          </div>
          {{-- end upload code --}}
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input page_2" type="checkbox" name="page_2" value="1" id="page_2">
              <label class="form-check-label " id="page_2" for="flexSwitchCheckDefault">Page 2</label>
            </div>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input rata_kiri" type="checkbox" name="rata_huruf" value="left" id="rata_huruf">
              <label class="form-check-label " id="rata_huruf" for="flexSwitchCheckDefault">Rata kiri</label>
            </div>
          </div>
          <label class="form-label">Sesuaikan Nama (mm)</label>
          <div class="row">
              <div class="col">
                <label for="margin_top" name="margin_top" class="form-label">TOP</label>
                <input class="form-control form-control-sm" id="margin_top" name="margin_top" type="number">
              </div>
              <div class="col">
                <label for="margin_right" name="margin_right" class="form-label">RIGHT</label>
                <input class="form-control form-control-sm" id="margin_right" name="margin_right" type="number">
              </div>
              <div class="col">
                <label for="margin_left" name="margin_left" class="form-label col-md-4">LEFT</label>
                <input class="form-control form-control-sm" id="margin_left" name="margin_left" type="number">
              </div>
          </div>
          <br>
          <label class="form-label">Sesuaikan Partisipan (mm)</label>
          <div class="row">
              <div class="col">
                <label for="peserta_top" name="peserta_top" class="form-label">TOP</label>
                <input class="form-control form-control-sm" id="peserta_top" name="peserta_top" type="number">
              </div>
              <div class="col">
                <label for="peserta_right" name="peserta_right" class="form-label">RIGHT</label>
                <input class="form-control form-control-sm" id="peserta_right" name="peserta_right" type="number">
              </div>
              <div class="col">
                <label for="peserta_left" name="peserta_left" class="form-label col-md-4">LEFT</label>
                <input class="form-control form-control-sm" id="peserta_left" name="peserta_left" type="number">
              </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

