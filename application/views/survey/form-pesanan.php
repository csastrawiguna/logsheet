<div class="content-wrapper">
  <!-- Main content -->
  <section class="content pt-3 px-1">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-10">
          <div class="card">
            <div class="card-header bg-success">
              Isi form dibawah ini untuk kolektif pemesanan produk (penukaran voucher produk Family Day 2021)
            </div>
            <div class="card-body">
              <a href="<?= base_url('form/orderlist'); ?>" class="float-right btn btn-outline-success">Lihat daftar pesanan</a>
              <p class="text-danger text-bold h5">Voucher 1 atau lebih hanya bisa ditukar dengan 1 barang (1 NPK 1 barang) kecuali masker</p>
              <form method="POST" action="<?= base_url('form/submitForm') ?>" class="mt-4">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="formPesananNpwp" class="form-label">NPWP (tanpa . atau -)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananNpwp" name="formPesananNpwp">
                        </div>
                      </div>
                    </div>                    
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananModelUnit1" class="form-label">Model unit #1 (produk) lihat di price list</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananModelUnit1" name="formPesananModelUnit1">
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananKodeVoucher1" class="form-label">Kode voucher #1 <span class="text-info">(pisahkan voucher 1 dan lainnya dg koma)</span></label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananKodeVoucher1" name="formPesananKodeVoucher1">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananModelUnit2" class="form-label">Model unit #2 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananModelUnit2" name="formPesananModelUnit2">
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananKodeVoucher2" class="form-label">Kode voucher #2 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananKodeVoucher2" name="formPesananKodeVoucher2">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananModelUnit3" class="form-label">Model unit #3 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananModelUnit3" name="formPesananModelUnit3">
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananKodeVoucher3" class="form-label">Kode voucher #3 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananKodeVoucher3" name="formPesananKodeVoucher3">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananModelUnit4" class="form-label">Model unit #4 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananModelUnit4" name="formPesananModelUnit4">
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananKodeVoucher4" class="form-label">Kode voucher #4 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananKodeVoucher4" name="formPesananKodeVoucher4">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananModelUnit5" class="form-label">Model unit #5 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananModelUnit5" name="formPesananModelUnit5">
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label for="formPesananKodeVoucher5" class="form-label">Kode voucher #5 (masker)</label>
                        <div class="">
                            <input type="" class="form-control" id="formPesananKodeVoucher5" name="formPesananKodeVoucher5">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="formPesananPilihAmbilAtauEkspedisi" class="form-label">Ambil sendiri atau kirim via ekspedisi</label>
                        <div class="">
                            <select type="" class="form-control custom-select" id="formPesananPilihAmbilAtauEkspedisi" name="formPesananPilihAmbilAtauEkspedisi">\
                                <option>-- select --</option>
                                <option value="Ambil sendiri">Ambil Sendiri</option>
                                <option value="Ekspedisi">Kirim via Ekspedisi</option>
                            </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                      <label for="formPesananAlamatKirim" class="form-label">Alamat kirim (jika akan dikirim via Ekspedisi)</label>
                      <div class="">
                          <textarea type="" class="form-control" id="formPesananAlamatKirim" name="formPesananAlamatKirim"></textarea>
                      </div>
                  </div>                  
                </div>
                <div class="modal-footer">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="formPesananSubmit" id="formPesananSubmit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
