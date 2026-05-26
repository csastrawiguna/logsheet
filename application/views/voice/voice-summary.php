<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
      if(!$this->input->post()) {
        $startPeriod = date("Y-m-d", strtotime("-6 months"));
        $endPeriod = date("Y-m-d");
      } else {
        $startPeriod = $this->input->post('selectSummaryVoiceStart');
        $endPeriod = $this->input->post('selectSummaryVoiceEnd');
      }

      function achievement2color($val, $mult) {
        $ratio = $val / $mult;
        if ($ratio < 0.80) {
            return 'danger';
        } else if ($ratio >= 0.80 && $ratio <0.90) {
            return 'warning';
        } else if ($ratio >= 0.90 && $ratio <0.95) {
            return 'info';
        } else {
            return 'success';
        }
      }

      function val2Ratio($val1, $val2) {

      }
    ?>

    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 py-0">
          <div class="card">
            <div class="card-header bg-primary">
              Summary of Agent's voice assessment (tapping)
            </div>
            <div class="card-body">
              <div class="row mb-4 pl-2">
                <form action="" method="post" style="width: 440px;">
                  <label for="selectSummaryVoiceStart">Period</label>
                  <input type="date" class="custom-select" name="selectSummaryVoiceStart" id="selectSummaryVoiceStart" style="width: 140px;" value="<?= $startPeriod?>">
                  <label for="selectSummaryVoiceEnd">to</label>
                  <input type="date" class="custom-select" name="selectSummaryVoiceEnd" id="selectSummaryVoiceEnd" style="width: 140px;" value="<?= $endPeriod?>">
                  <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryVoice" name="buttonSelectSummaryVoice">Go</button>
                </form>
                <div class="" style="position: absolute; top: 56px; right: 20px;">
                  <a href="<?= base_url('voice/info') ?>" class="text-info"><i class="fas fa-info-circle"></i> Info penilaian</a>
                </div>                
              </div>
              <div class="row mb-5">
                <div class="col">
                  <p class="h5 text-indigo mb-3">Whole Summary on : <?= date("F Y", strtotime($startPeriod)) ?> to <?= date("F Y", strtotime($endPeriod)) ?></p>
                  <?php if($voiceSummaryByFindings['qty'] == 0 ) : ?>
                    <p class="lead font-italic"> <i class="fas fa-grin-beam-sweat text-danger ml-3"></i>  there were no data to be displayed</p>
                  <?php else : ?>
                    <table class="table table-sm table-bordered table-responsive">
                      <thead class="bg-light">
                        <tr>
                          <th class="px-4 py-2">Item</th>
                          <th class="px-4 py-2">Sub-assessment</th>
                          <th class="px-4 py-2">Achievement</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="px-4 py-2" rowspan="2">Greeting</td>
                          <td class="px-4 py-2">Greeting completeness</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['greeting_complete'] / $voiceSummaryByFindings['qty'] / 3 * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['greeting_complete'] / $voiceSummaryByFindings['qty'] / 3 * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Smile voice on greeting</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['greeting_smile'] / $voiceSummaryByFindings['qty'] / 2 * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['greeting_smile'] / $voiceSummaryByFindings['qty'] / 2 * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2" rowspan="5">Intonation</td>
                          <td class="px-4 py-2">Intonation straight</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['intonation_straight'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['intonation_straight'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Intonation clear</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['intonation_clear'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['intonation_clear'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Intonation not flat</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['intonation_not_flat'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['intonation_not_flat'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Intonation not weak</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['intonation_not_weak'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['intonation_not_weak'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Intonation not high</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['intonation_not_high'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['intonation_not_high'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2" rowspan="5">Handling</td>
                          <td class="px-4 py-2">No jargon</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['handling_no_jargon'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['handling_no_jargon'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Mention customer's name (min 3X)</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['handling_customer_name'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['handling_customer_name'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Communicative</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['handling_communicative'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['handling_communicative'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Information accuracy</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['handling_accuracy'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['handling_accuracy'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2">Offering help</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['handling_ask_help'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['handling_ask_help'] / $voiceSummaryByFindings['qty'] * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td class="px-4 py-2" rowspan="5">Closing</td>
                          <td class="px-4 py-2">Complete closing greeting</td>
                          <td class="px-4 py-2" style="width: 40%">
                            <span class="ml-1 float-right h6 text-success">
                              <?= number_format($voiceSummaryByFindings['closing'] / $voiceSummaryByFindings['qty'] / 5 * 100, 0) ?>%
                            </span>
                            <div class="progress-group">
                              <div class="progress" style="min-height: 18px;">
                                <div class="progress-bar bg-success" style="width:<?= number_format($voiceSummaryByFindings['closing'] / $voiceSummaryByFindings['qty'] / 5 * 100, 0) ?>%; height: 100%;"></div>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  <?php endif; ?>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col">
                  <span class="h5 text-indigo mb-3">Findings : <?= date("F Y", strtotime($startPeriod)) ?> to <?= date("F Y", strtotime($endPeriod)) ?></span>
                  <br>
                  <em class="text-muted"> 0 (zero) = clear </em>
                  <div id="voiceSummaryChartContainer">
                    <!-- <canvas id="blackbookSummaryChart" height="100" width="240"></canvas> -->
                    <table class="table table-sm table-bordered" id="tableSummaryVoiceByCategory">
                      <thead>
                        <tr>
                          <th rowspan="2" class="align-middle text-center">Period</th>
                          <th rowspan="2" class="align-middle text-center">Voice Qty</th>
                          <th colspan="2" class="align-middle text-center">Greeting</th>
                          <th colspan="5" class="align-middle text-center">Intonation</th>
                          <th colspan="5" class="align-middle text-center">Handling</th>
                          <th rowspan="2" class="align-middle text-center">Closing</th>
                        </tr>
                        <tr>
                          <th class="align-middle text-center">Incom<br>plete</th>
                          <th class="align-middle text-center">No smile</th>
                          <th class="align-middle text-center">Straight</th>
                          <th class="align-middle text-center">Unclear</th>
                          <th class="align-middle text-center">Flat</th>
                          <th class="align-middle text-center">Weak</th>
                          <th class="align-middle text-center">Over<br>tone</th>
                          <th class="align-middle text-center">Jargon</th>
                          <th class="align-middle text-center">Cust.<br>name</th>
                          <th class="align-middle text-center">Accuracy</th>
                          <th class="align-middle text-center">Commu<br>nicative</th>
                          <th class="align-middle text-center">Ask help</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($voiceSummaryByCategory as $data): ?>
                          <tr>
                            <td class="text-center"><?= date("M-y", strtotime($data['period'])); ?></td>
                            <td class="text-center"><?= $data['survey_qty']; ?></td>
                            <td class="text-center"><?= $data['greeting_complete']; ?></td>
                            <td class="text-center"><?= $data['greeting_smile']; ?></td>
                            <td class="text-center"><?= $data['intonation_straight']; ?></td>
                            <td class="text-center"><?= $data['intonation_clear']; ?></td>
                            <td class="text-center"><?= $data['intonation_not_flat']; ?></td>
                            <td class="text-center"><?= $data['intonation_not_weak']; ?></td>
                            <td class="text-center"><?= $data['intonation_not_high']; ?></td>
                            <td class="text-center"><?= $data['handling_no_jargon']; ?></td>
                            <td class="text-center"><?= $data['handling_customer_name']; ?></td>
                            <td class="text-center"><?= $data['handling_communicative']; ?></td>
                            <td class="text-center"><?= $data['handling_accuracy']; ?></td>
                            <td class="text-center"><?= $data['handling_ask_help']; ?></td>
                            <td class="text-center"><?= $data['closing']; ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col pr-5">
                  <!-- <h6 class="h5 text-indigo mb-3">Summary by Agent</h6> -->                  
                  <div id="chartVoiceUnproperSummaryContainer">
                    <canvas id="chartVoiceUnproperSummary" height="" width=""></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header bg-danger">
              By Agent
              <div class="card-tools">                         
                 <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                 </button>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-sm" id="tableSummaryVoiceByAgent">
                <thead>
                  <tr>
                    <th>Agent</th>
                    <th class="align-middle text-center">Incom<br>plete</th>
                    <th class="align-middle text-center">No smile</th>
                    <th class="align-middle text-center">Straight</th>
                    <th class="align-middle text-center">Unclear</th>
                    <th class="align-middle text-center">Flat</th>
                    <th class="align-middle text-center">Weak</th>
                    <th class="align-middle text-center">Over<br>tone</th>
                    <th class="align-middle text-center">Jargon</th>
                    <th class="align-middle text-center">Cust.<br>name</th>
                    <th class="align-middle text-center">Commu<br>nicative</th>
                    <th class="align-middle text-center">Accuracy</th>
                    <th class="align-middle text-center">Ask help</th>
                    <th class="align-middle text-center">Closing</th>
                    <th class="align-middle text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($voiceUnproperByAgent as $row) : ?>
                    <?php if ($row['total_finding'] > 0 ) : ?>
                      <tr>
                        <td><?= $row['agent'] ?></td>                          
                        <td class="text-center"><?= $row['greeting_incomplete']; ?></td>
                        <td class="text-center"><?= $row['greeting_nosmile']; ?></td>
                        <td class="text-center"><?= $row['intonation_nostraight']; ?></td>
                        <td class="text-center"><?= $row['intonation_noclear']; ?></td>
                        <td class="text-center"><?= $row['intonation_flat']; ?></td>
                        <td class="text-center"><?= $row['intonation_weak']; ?></td>
                        <td class="text-center"><?= $row['intonation_high']; ?></td>
                        <td class="text-center"><?= $row['handling_jargon']; ?></td>
                        <td class="text-center"><?= $row['handling_nocustomer_name']; ?></td>
                        <td class="text-center"><?= $row['handling_nocommunicative']; ?></td>
                        <td class="text-center"><?= $row['handling_inaccurate']; ?></td>
                        <td class="text-center"><?= $row['handling_noask_help']; ?></td>
                        <td class="text-center"><?= $row['closing_unstandard'] + $row['closing_incomplete']; ?></td>
                        <td class="text-center"><?= $row['total_finding']; ?></td>
                      </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header bg-success">
              CLEAR (no finding)
              <div class="card-tools">                         
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap">
                <?php foreach ($voiceUnproperClearAgent as $row) : ?>
                  <div class="">
                    <img class="img img-circle float-left mx-1 my-1" width="140" src="<?= base_url() . '/assets/img/profile/' . $row['photo'] ?>">
                    <br>
                    <p class="text-center mb-4"><?= ucwords($row['agent']) ?></p>
                  </div>
                <?php endforeach; ?>
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