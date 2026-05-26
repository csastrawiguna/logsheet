<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
        $allowedChangeAgent = ['1', '5', '6', '9'];
        if(!$this->input->post()) {
          $startPeriod = date("Y-m-01", strtotime("-6 months"));
          $endPeriod = date("Y-m-01");
        } else {
          $startPeriod = $this->input->post('auxSummaryDateStart');
          $endPeriod = $this->input->post('auxSummaryDateEnd');
        }

        function convertToHoursMins($time, $format = '%02d:%02d:%02d') {
          if ($time < 1) {
              return 0;
          }
          $hours = floor($time / 3600);
          $minutes = floor($time % 3600) / 60;
          $second = ($time % 60);
          return sprintf($format, $hours, $minutes, $second);
        }
      ?>
    <div class="container-fluid py-2 px-1">
      <div class="card">
        <div class="card-header bg-primary">
          AUX data summary
          <div class="card-tools">
            <a href="<?= base_url() ?>files/Format_Upload_AUXmonthly.xlsx" class="mr-2">
                <span class="text-white"><i class="fas fa-file-excel"></i> Format upload</span>
            </a>
            <a href="#modalUploadAuxSummary" class="text-white mr-2" data-toggle="modal" data-target="#modalUploadAuxSummary"><i class="fas fa-upload"></i> Upload data</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row"> 
            <div class="col-sm my-3 px-1">
              <form action="" class="form-row mb-3" method="post" style="width: 820px;">
                <label for="auxSummaryDateStart" class="col-sm-1 ml-5">Period</label>
                <div class="col-sm-2">
                  <input type="date" id="auxSummaryDateStart" name="auxSummaryDateStart" class="form-control" value="<?= $startPeriod?>">
                </div>
                <div class="col-sm-2">
                  <input type="date" id="auxSummaryDateEnd" name="auxSummaryDateEnd" class="form-control" value="<?= $endPeriod?>">
                </div>
                <div class="col-sm-1">
                  <div class="row ml-1">
                    <button type="submit" class="btn btn-outline-primary" id="buttonSelectAuxAgent" name="buttonSelectAuxAgent">Go</button>      
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <p class="text-center h5">Summary AUX by agent periode : <?= date("F Y", strtotime($startPeriod)) ?> - <?= date("F Y", strtotime($endPeriod)) ?></p>
              <table class="table table-sm" id="tableSummaryAuxMonthly">
                <thead>
                  <tr class="border-top">
                    <th class="align-middle">Agent</th>
                    <th class="text-right align-middle">Staffed<br>Login</th>
                    <th class="text-right align-middle">TTL AUX</th>
                    <th class="text-right align-middle">AUX<br>1,2,3,6</th>
                    <th class="text-right align-middle">Hanging<br><small>(AUX 0)</small></th>
                    <th class="text-right align-middle">Pray<br><small>(AUX 1)</small></th>
                    <th class="text-right align-middle">Break<br><small>(AUX 2)</small></th>
                    <th class="text-right align-middle">Lunch<br><small>(AUX 3)</small></th>
                    <th class="text-right align-middle">Follow Up<br><small>(AUX 4)</small></th>
                    <th class="text-right align-middle">Callback<br><small>(AUX 5)</small></th>
                    <th class="text-right align-middle">Input Data<br><small>(AUX 6)</small></th>
                    <th class="text-right align-middle">Respon WA<br><small>(AUX 8)</small></th>
                </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach($auxSummaryMonthly as $row) : ?>
                    <tr>
                        <td><?= $row['agent'] ?></td>
                        <td class="text-right">
                            <p><?= convertToHoursMins($row['staffed_time']) ?></p>
                        </td>
                        <td class="text-right">
                            <p>
                                <?= number_format((($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) / $row['staffed_time']) *100, 1) ?>%
                                <br>
                                <span class="text-muted">
                                  (<?= convertToHoursMins($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) ?>)
                                </span>
                            </p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format((($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p> <?= number_format(($row['aux_0'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_0']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_1'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_1']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_2'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_2']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_3'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_3']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_4'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_4']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_5'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_5']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_6'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_6']) ?>)</span></p>
                        </td>
                        <td class="text-right">
                            <p><?= number_format(($row['aux_8'] / $row['staffed_time']) *100, 1) ?>%<br>
                            <span class="text-muted">(<?= convertToHoursMins($row['aux_8']) ?>)</span></p>
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

<!-- Modal upload summary AUX -->
<div class="modal fade" id="modalUploadAuxSummary" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalUploadAuxSummaryLabel">Upload Summary AUX by month</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <?= form_open_multipart('auxdata/uploadAuxSummary'); ?>
        <div class="form-group row">
          <label for="uploadAuxSummaryMonth" class="col-sm-2">Month</label>
          <input type="date" class="col-sm-10" id="uploadAuxSummaryMonth" name="uploadAuxSummaryMonth">
        </div>
        <div class="form-group row">
          <label for="uploadAuxSummaryFile" class="col-sm-2">Data</label>
          <input type="file" class="col-sm-10" id="uploadAuxSummaryFile" name="uploadAuxSummaryFile">
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="uploadAuxSummarySubmit">Upload</button>
        </div>
      </form>      
    </div>
    </div>
  </div>
</div>