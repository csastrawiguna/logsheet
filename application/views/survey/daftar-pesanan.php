<div class="content-wrapper">
  <!-- Main content -->
  <section class="content pt-3 px-1">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col">
          <div class="card">
            <div class="card-header bg-success">
              Daftar Pesanan
            </div>
            <div class="card-body">
              <a href="<?= base_url('form'); ?>" class="float-right btn btn-outline-success">ke Form Pendataan Pesanan</a>
              <table class="table table-sm" id="tableAllOrderFamilyDay">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>NPK</th>
                    <th>Barang 1</th>
                    <th>Voucher 1</th>
                    <th>Barang 2</th>
                    <th>Voucher 2</th>
                    <th>Barang 3</th>
                    <th>Voucher 3</th>
                    <th>Barang 4</th>
                    <th>Voucher 4</th>
                    <th>Barang 5</th>
                    <th>Voucher 5</th>
                    <th>Ambil/ Kirim</th>
                    <th>Alamat kirim</th>
                    <th class="text-center">...</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1;  ?>
                  <?php foreach($allOrder as $data): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $data['nama_lengkap'] ?></td>
                      <td><?= $data['npk'] ?></td>
                      <td><?= $data['produk1'] ?></td>
                      <td><?= $data['kode_voucher_1'] ?></td>
                      <td><?= $data['produk2'] ?></td>
                      <td><?= $data['kode_voucher_2'] ?></td>
                      <td><?= $data['produk3'] ?></td>
                      <td><?= $data['kode_voucher_3'] ?></td>
                      <td><?= $data['produk4'] ?></td>
                      <td><?= $data['kode_voucher_4'] ?></td>
                      <td><?= $data['produk5'] ?></td>
                      <td><?= $data['kode_voucher_5'] ?></td>
                      <td><?= $data['ambil_kirim'] ?></td>
                      <td><?= $data['alamat_kirim'] ?></td>
                      <td class="text-center">
                        <a href="#" class="text-danger deleteOrderList" data-id="<?= $data['id']; ?>"><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
