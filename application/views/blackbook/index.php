<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
      if(!$this->input->post()) {
        $startPeriod = date("Y-m-d", strtotime("-3 months"));
        $endPeriod = date("Y-m-d");
      } else {
        $startPeriod = $this->input->post('selectSummaryBlackbookStart');
        $endPeriod = $this->input->post('selectSummaryBlackbookEnd');
      }

      function toStringDate($date){
        if(strtotime($date) < 0){
          return '-';
        } else {
          return date("d-M-Y h:i",strtotime($date));
        }
      }
    ?>
    
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 py-0">
          <div class="card">
            <div class="card-header bg-primary">
              Summary of Agent's black notes
            </div>
            <div class="card-body">
              <div class="row mb-4 pl-2">
                <form action="" method="post" style="width: 440px;">
                  <label for="selectSummaryBlackbookStart">Period</label>
                  <input type="date" class="custom-select" name="selectSummaryBlackbookStart" id="selectSummaryBlackbookStart" style="width: 140px;" value="<?= $startPeriod?>">
                  <label for="selectSummaryBlackbookEnd">to</label>
                  <input type="date" class="custom-select" name="selectSummaryBlackbookEnd" id="selectSummaryBlackbookEnd" style="width: 140px;" value="<?= $endPeriod?>">
                  <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryBlackbook" name="buttonSelectSummaryBlackbook">Go</button>
                </form>
              </div>
              <div class="row">
                <div class="col-8">
                  <h6 class="h5 text-indigo mb-3">Summary by Category</h6>
                  <div id="blackbookSummaryChartContainer">
                    <canvas id="blackbookSummaryChart" height="160" width="240"></canvas>
                  </div>
                </div>
              </div>
              <div class="row mt-5">
                <div class="col">
                  <h6 class="h5 text-indigo mb-3">Summary by Agent</h6>
                  <table class="table table-sm" id="tableSummaryBlackbook">
                    <thead>
                      <tr class="">
                        <th class="align-middle">#</th>
                        <th class="align-middle">Agent</th>
                        <th class="align-middle text-center">Salah SVC area</th>
                        <th class="align-middle text-center">Salah notif type</th>
                        <th class="align-middle text-center">Balasan tdk tepat</th>
                        <th class="align-middle text-center">Tidak ada alamat</th>
                        <th class="align-middle text-center">Alamat tdk dirapikan</th>
                        <th class="align-middle text-center">Salah info</th>
                        <th class="align-middle text-center">Salah system code</th>
                        <th class="align-middle text-center">Lainnya</th>
                        <th class="align-middle text-center">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 1; ?>
                      <?php foreach($summaryBlackbookByPeriod as $data): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $data['agent'] ?></td>
                          <td class="text-center"><?= $data['wrong_service_area'] ?></td>
                          <td class="text-center"><?= $data['unproper_notif'] ?></td>
                          <td class="text-center"><?= $data['unproper_reply'] ?></td>
                          <td class="text-center"><?= $data['no_address'] ?></td>
                          <td class="text-center"><?= $data['messy_address'] ?></td>
                          <td class="text-center"><?= $data['wrong_info'] ?></td>
                          <td class="text-center"><?= $data['wrong_system_code'] ?></td>
                          <td class="text-center"><?= $data['others'] ?></td>
                          <td class="text-center"><?= $data['total'] ?></td>
                        </tr>
                      <?php endforeach; ?>                      
                    </tbody>
                  </table>                  
                </div>
              </div>
              <div class="row mt-5">
                <div class="col">
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->