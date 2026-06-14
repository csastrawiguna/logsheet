<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
      require 'aux-function.php';
      $allowedChangeAgent = in_array($this->session->userdata('role_access'), ['1', '5', '6', '9']);
    ?>
    <div class="container-fluid py-2 px-1">
      <div class="card">
        <div class="card-header bg-primary">
          AUX data summary
          <div class="card-tools">
          </div>
        </div>
        <div class="card-body">
          <div class="row"> 
            <div class="col-sm my-3 px-1">
              <form action="" class="form-row mb-3" method="post" style="width: 820px;">
                <label for="auxSummaryDateStart" class="col-sm-1 ml-5">Period</label>
                <div class="col-sm-2" style="min-width: 150px;">
                  <input type="date" id="auxSummaryDateStart" name="auxSummaryDateStart" class="form-control" value="<?= $startPeriod?>">
                </div>-
                <div class="col-sm-2" style="min-width: 150px;">
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

          <!-- Summary ALL -->
          <div class="row mt-3">
            <div class="col-md">
              <p class="h5 text-indigo mb-3"><i class="far fa-calendar-alt"></i> Monthly Transition (Total)</p>
              <table class="table table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th class="align-middle">Period</th>
                    <th class="align-middle text-right">Staffed<br>time</th>
                    <th class="align-middle text-right">AUX<br>total</th>
                    <th class="align-middle text-right">AUX 0<br>Hang</th>
                    <th class="align-middle text-right">AUX 1<br>Pray</th>
                    <th class="align-middle text-right">AUX 2<br>Break</th>
                    <th class="align-middle text-right">AUX 3<br>Lunch</th>
                    <th class="align-middle text-right">AUX 4<br>FU</th>
                    <th class="align-middle text-right">AUX 5<br>CAB</th>
                    <th class="align-middle text-right">AUX 6<br>Input<br>data</th>
                    <th class="align-middle text-right">AUX 7<br>Back<br>Office</th>
                    <th class="align-middle text-right">AUX 8<br>WA</th>
                    <th class="align-middle text-right">AUX 9<br>WA</th>
                    <th class="align-middle text-right">AUX<br>Other</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($auxSummaryMonthlyTransition as $row) : ?>
                    <tr>
                      <td class="align-top"><?= date("M Y", strtotime($row['period'])) ?></td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['staffed_time']) ?>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_total']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_total'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_0']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_0'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_2']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_2'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_3']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_3'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_4']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_4'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_5']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_5'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_6']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_6'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_7']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_7'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_8']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_8'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_9']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_9'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1099']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1099'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Summary Weekday -->
          <div class="row mt-3">
            <div class="col-md">
              <p class="h5 text-indigo mb-3"><i class="far fa-calendar-alt"></i> Monthly Transition - Weekday</p>
              <table class="table table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th class="align-middle">Period</th>
                    <th class="align-middle text-right">Staffed<br>time</th>
                    <th class="align-middle text-right">AUX<br>total</th>
                    <th class="align-middle text-right">AUX 0<br>Hang</th>
                    <th class="align-middle text-right">AUX 1<br>Pray</th>
                    <th class="align-middle text-right">AUX 2<br>Break</th>
                    <th class="align-middle text-right">AUX 3<br>Lunch</th>
                    <th class="align-middle text-right">AUX 4<br>FU</th>
                    <th class="align-middle text-right">AUX 5<br>CAB</th>
                    <th class="align-middle text-right">AUX 6<br>Input<br>data</th>
                    <th class="align-middle text-right">AUX 7<br>Back<br>Office</th>
                    <th class="align-middle text-right">AUX 8<br>WA</th>
                    <th class="align-middle text-right">AUX 9<br>WA</th>
                    <th class="align-middle text-right">AUX<br>Other</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($auxSummaryMonthlyTransitionWeekday as $row) : ?>
                    <tr>
                      <td class="align-top"><?= date("M Y", strtotime($row['period'])) ?></td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['staffed_time']) ?>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_total']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_total'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_0']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_0'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_2']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_2'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_3']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_3'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_4']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_4'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_5']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_5'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_6']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_6'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_7']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_7'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_8']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_8'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_9']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_9'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1099']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1099'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Summary Weekday -->
          <div class="row mt-3">
            <div class="col-md">
              <p class="h5 text-indigo mb-3"><i class="far fa-calendar-alt text-danger"></i> Monthly Transition - Overtime</p>
              <table class="table table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th class="align-middle">Period</th>
                    <th class="align-middle text-right">Staffed<br>time</th>
                    <th class="align-middle text-right">AUX<br>total</th>
                    <th class="align-middle text-right">AUX 0<br>Hang</th>
                    <th class="align-middle text-right">AUX 1<br>Pray</th>
                    <th class="align-middle text-right">AUX 2<br>Break</th>
                    <th class="align-middle text-right">AUX 3<br>Lunch</th>
                    <th class="align-middle text-right">AUX 4<br>FU</th>
                    <th class="align-middle text-right">AUX 5<br>CAB</th>
                    <th class="align-middle text-right">AUX 6<br>Input<br>data</th>
                    <th class="align-middle text-right">AUX 7<br>Back<br>Office</th>
                    <th class="align-middle text-right">AUX 8<br>WA</th>
                    <th class="align-middle text-right">AUX 9<br>WA</th>
                    <th class="align-middle text-right">AUX<br>Other</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($auxSummaryMonthlyTransitionOvertime as $row) : ?>
                    <tr>
                      <td class="align-top"><?= date("M Y", strtotime($row['period'])) ?></td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['staffed_time']) ?>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_total']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_total'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_0']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_0'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_2']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_2'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_3']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_3'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_4']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_4'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_5']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_5'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_6']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_6'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_7']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_7'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_8']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_8'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_9']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_9'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1099']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1099'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Summary by Agent -->
          <div class="row mt-3">
            <div class="col-md">
              <p class="h5 text-indigo mb-3"><i class="fas fa-user-friends"></i> By Agent</p>
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th class="align-middle">Agent</th>
                    <th class="align-middle text-right">Staffed<br>time</th>
                    <th class="align-middle text-right">AUX<br>total</th>
                    <th class="align-middle text-right">AUX 0<br>Hang</th>
                    <th class="align-middle text-right">AUX 1<br>Pray</th>
                    <th class="align-middle text-right">AUX 2<br>Break</th>
                    <th class="align-middle text-right">AUX 3<br>Lunch</th>
                    <th class="align-middle text-right">AUX 4<br>FU</th>
                    <th class="align-middle text-right">AUX 5<br>CAB</th>
                    <th class="align-middle text-right">AUX 6<br>Input<br>data</th>
                    <th class="align-middle text-right">AUX 7<br>Back<br>Office</th>
                    <th class="align-middle text-right">AUX 8<br>WA</th>
                    <th class="align-middle text-right">AUX 9<br>WA</th>
                    <th class="align-middle text-right">AUX<br>Other</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($auxSummaryByAgent as $row) : ?>
                    <tr>
                      <td class="align-top"><?= $row['agent'] ?></td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['staffed_time']) ?>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_total']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_total'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_0']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_0'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_2']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_2'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_3']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_3'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_4']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_4'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_5']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_5'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_6']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_6'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_7']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_7'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_8']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_8'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_9']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_9'] / $row['staffed_time'] * 100, 1) ?>%)</span>
                      </td>
                      <td class="text-right">
                        <?= convertToHoursMins($row['aux_1099']) ?>
                        <br>
                        <span class="text-danger">(<?= number_format($row['aux_1099'] / $row['staffed_time'] * 100, 1) ?>%)</span>
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