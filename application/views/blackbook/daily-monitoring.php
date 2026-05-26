<div class="content-wrapper">
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid py-2 px-0">
      <?php 
        $allowedAccess = [1, 2, 4, 5, 6, 9];

        function booleToTag($data) {
          if ($data == 1 || $data == true) {
            return '<span class="h5"><i class="fas fa-check-circle text-success"></i></span>';
          } else {
            return '<i class="fas fa-times text-danger"></i>';
          }
        }

        function avoidZeroDivider($num, $div) {
          if ($div == 0) {
            return '-';
          } else {
            $out = number_format($num / $div, 3) * 100 . '% <small class="text-secondary">[of ' . $div . ']</small>';
            return $out;
          }
        }

        function sourceToTag($src) {
          if (strtolower($src) == 'call') {
            return '<i class="fas fa-phone text-primary"></i>';
          } else if (strtolower($src) == 'whatsapp') {
            return '<span class="h5"><i class="fab fa-whatsapp text-success"></i></span>';
          } else {
             return '<i class="fas fa-bars"></i>';
          }
        }
      ?>
      
      <div class="card">
        <div class="card-header bg-purple">
          CASHLESS PAYMENT INFO TO CUSTOMER
          <div class="card-tools">
            <?php if (in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
              <a href="#" data-toggle="modal" data-target="#cashlessInfoMonitoringSetting" class="text-white mr-3"><i class="fas fa-cog"></i> Setting</a>
              <a href="#" data-toggle="modal" data-target="#cashlessInfoMonitoringAdd" class="text-white mr-3" id="buttonCashlessInfoMonitoringAdd"><i class="fas fa-plus-circle"></i> Add data</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <form action="" class="row form-row mb-4" method="post" style="width: 670px;">
            <label for="agentMonitoringDateStart" class="col-sm-1">Period</label>
            <div class="col-sm-3">
              <input type="date" id="agentMonitoringDateStart" name="agentMonitoringDateStart" class="form-control" value="<?= $startPeriod ?>">
            </div>
            <div class="col-sm-3">
              <input type="date" id="agentMonitoringDateEnd" name="agentMonitoringDateEnd" class="form-control" value="<?= $endPeriod ?>">
            </div>
            <div class="col-sm-4">
              <div class="row">
                <button type="submit" class="btn btn-outline-primary" id="agentMonitoringButtonSelect" name="agentMonitoringButtonSelect">Go</button>
                <?php if (count($detailMonitoring) >= 1) : ?>
                  <button type="button" class="btn btn-outline-success ml-1 classFunctionNotAvailableYet" id="agentMonitoringExcelSummary" name="agentMonitoringExcelSummary"><i class="fas fa-file-excel"></i> Summary</button>
                  <button type="button" class="btn btn-outline-success ml-1 classFunctionNotAvailableYet" id="agentMonitoringExcelDetail" name="agentMonitoringExcelDetail"><i class="fas fa-file-excel"></i> Detail</button>
                <?php endif; ?>
              </div>
            </div>
          </form>
          <div class="row mb-4">
            <div class="col">
              <p class="badge badge-pill badge-primary py-1 px-2">Summary</p>
              <table class="table table-sm" style="width: 560px;">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="bg-light">Agent</th>
                    <th class="text-center">% on Call</th>
                    <th class="text-center">% on WA</th>
                    <th class="text-center">% Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $x = 1; ?>
                  <?php foreach($summaryMonitoring as $row) : ?>
                    <tr>
                      <td class="text-center"><?= $x++; ?></td>
                      <td class="bg-light"><strong class="text-indigo"><?= $row['agent'] ?></strong></td>
                      <td class="text-center">
                        <?= avoidZeroDivider($row['call_done'], $row['call_qty']) ?>
                      </td>
                      <td class="text-center">
                        <?= avoidZeroDivider($row['whatsapp_done'], $row['whatsapp_qty']) ?>
                      </td>
                      <td class="text-center">
                        <!-- <?= number_format($row['done_ratio'] * 100) ?>% -->
                        <?= avoidZeroDivider(($row['call_done'] + $row['whatsapp_done']), ($row['call_qty'] + $row['whatsapp_qty'])) ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <tr class="bg-light text-bold text-center">
                    <td colspan="2" class="">Total</td>
                    <td>
                      <?= avoidZeroDivider($summaryMonitoringTotal['call_done'], $summaryMonitoringTotal['call_qty']) ?>
                    </td>
                    <td>
                      <?= avoidZeroDivider($summaryMonitoringTotal['whatsapp_done'], $summaryMonitoringTotal['whatsapp_qty']) ?>
                    </td>
                    <td>
                      <?= avoidZeroDivider(($summaryMonitoringTotal['call_done'] + $summaryMonitoringTotal['whatsapp_done']), ($summaryMonitoringTotal['call_qty'] + $summaryMonitoringTotal['whatsapp_qty'])) ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <p class="badge badge-pill badge-primary py-1 px-2">Detail</p>
              <table class="table table-sm tableBasicDataTable">
                <thead>
                  <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">Info</th>
                    <th class="align-middle">Line</th>
                    <th class="align-middle">Date</th>
                    <th class="bg-light align-middle">Agent</th>
                    <th class="align-middle">Customer data</th>
                    <th class="text-center bg-light align-middle">Info</th>
                    <th class="align-middle">Saved by/at</th>
                    <th class="align-middle">...</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach($detailMonitoring as $row) : ?>
                    <tr>
                      <td class="text-center align-middle"><?= $i++ ?></td>
                      <td class="align-middle"><?= ucwords($row['info_type']) ?></td>
                      <td class="align-middle"><?= sourceToTag($row['source']) ?></td>
                      <td class="align-middle"><?= date("d M Y", strtotime($row['date'])) ?></td>
                      <td class="bg-light align-middle"><strong class="text-indigo"><?= $row['agent'] ?></strong></td>
                      <td class="align-middle"><?= $row['customer_data'] ?></td>
                      <td class="text-center bg-light align-middle"><?= booleToTag($row['done_by_agent']) ?></td>
                      <td class="align-middle">
                        <span class="badge badge-secondary font-weight-normal">
                          <?= $row['saved_by'] ?>
                        </span>
                        <small>
                          <?= date("d-M-Y H:i", strtotime($row['saved_at'])) ?>
                        </small>
                      </td>
                      <td class="align-middle">
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 260px;">
                            <table class="table table-sm table-borderless">
                              <tbody>
                                <tr>
                                  <td>
                                    <i class="fas fa-skull"></i> Beware of something
                                  </td>
                                </tr>
                                <tr class="border-top">
                                  <td>
                                    Updated: 
                                    <span class="badge badge-info font-weight-normal">
                                      <?= $row['updated_by'] ?>
                                    </span>
                                    <small class="text-info">
                                      <?= date("d-M-y H:i", strtotime($row['updated_at'])) ?>
                                    </small>
                                  </td>
                                </tr>
                                <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                                  <tr class="border-top">
                                    <td class="py-2">                                        
                                      <a href="<?= base_url('blackbook/editDailyMonitoring/') . $row['id'] ?>" class="text-primary buttoncashlessInfoMonitoringEdit" title="Edit data" data-id="<?= $row['id']; ?>">
                                        <i class="fas fa-pen"></i></span> &nbspEdit data
                                    </a>
                                    </td>
                                  </tr>
                                  <tr class="border-top">
                                    <td class="py-2">
                                      <a class="text-danger buttoncashlessInfoMonitoringDelete" href="#" data-id="<?=$row['id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                        <i class="fas fa-times text-danger"></i> &nbspDelete data
                                      </a>
                                    </td>
                                  </tr>  
                                <?php endif; ?>
                              </tbody>
                            </table> 
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<div class="modal fade" id="cashlessInfoMonitoringAdd" tabindex="-1" role="dialog" aria-labelledby="cashlessInfoMonitoringAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cashlessInfoMonitoringAddLabel">Add Cashless Info Monitoring</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="<?= base_url('blackbook/addDailyMonitoring') ?>">
        <div class="modal-body">
          <input type="hidden" class="form-control" name="cashlessInfoMonitoringId" id="cashlessInfoMonitoringId">
          <div class="form-group row">
            <label for="cashlessInfoMonitoringDate" class="col-sm-4 col-form-label">Date</label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="cashlessInfoMonitoringDate" name="cashlessInfoMonitoringDate" value="<?= date("Y-m-d") ?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="cashlessInfoMonitoringAgent" class="col-sm-4 col-form-label">Agent</label>
            <div class="col-sm-8">
              <select type="" class="js-example-basic-single custom-select" id="cashlessInfoMonitoringAgent" name="cashlessInfoMonitoringAgent">
                <option>-- select agent --</option>
                <?php foreach($agents as $agent): ?>
                    <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="cashlessInfoMonitoringSource" class="col-sm-4 col-form-label">Source</label>
            <div class="col-sm-8">
              <div class="pretty p-default p-curve">
                <input type="radio" name="cashlessInfoMonitoringSource" value="call" />
                <div class="state p-primary-o">
                  <label>Call</label>
                </div>
              </div>
              <div class="pretty p-default p-curve">
                <input type="radio" name="cashlessInfoMonitoringSource" value="whatsapp" />
                <div class="state p-primary-o">
                  <label>Whatsapp</label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="cashlessInfoMonitoringCustomerData" class="col-sm-4 col-form-label">Customer data</label>
            <div class="col-sm-8">
              <input type="" class="form-control" id="cashlessInfoMonitoringCustomerData" name="cashlessInfoMonitoringCustomerData" placeholder="Nomor telepon konsumen">
            </div>
          </div>

          <div class="form-group row">
            <label for="cashlessInfoMonitoringAgentDone" class="col-sm-4 col-form-label">Informed Cashless?<br><small class="text-muted font-italic">Click to change</small></label>
            <div class="col-sm-8">
              <div class="pretty p-svg p-curve p-toggle" title="Click to change">
                <input type="hidden" name="cashlessInfoMonitoringAgentDone" value="0">
                <input type="checkbox" name="cashlessInfoMonitoringAgentDone" id="cashlessInfoMonitoringAgentDone" value="1">
                <div class="state p-success p-on">
                  <svg class="svg svg-icon" viewBox="0 0 20 20">
                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                  </svg>
                  <label>Informed to customer</label>
                </div>
                <div class="state p-danger p-off">
                  <svg class="svg svg-icon" viewBox="0 0 20 20">
                    <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z" style="stroke: white;fill:white;"></path>
                  </svg>
                  <label>Not informed</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
          <button type="submit" class="btn btn-primary" name="cashlessInfoMonitoringSubmit" id="cashlessInfoMonitoringSubmit"><i class="fas fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="cashlessInfoMonitoringSetting" tabindex="-1" role="dialog" aria-labelledby="cashlessInfoMonitoringSettingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cashlessInfoMonitoringSettingLabel">Add Cashless Info Monitoring</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="<?= base_url('blackbook/settingDailyMonitoring') ?>">
        <div class="modal-body">
          <input type="hidden" class="form-control" name="cashlessInfoMonitoringSettingId" id="cashlessInfoMonitoringSettingId" value="<?= $id; ?>">
          <div class="form-group row">
            <label for="cashlessInfoMonitoringSettingValue" class="col-sm-8 col-form-label">Days before to be displayed</label>
            <div class="col-sm-4">
              <input type="number" class="form-control text-center" id="cashlessInfoMonitoringSettingValue" name="cashlessInfoMonitoringSettingValue" value="<?= $daysDuration; ?>">
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
          <button type="submit" class="btn btn-primary" name="cashlessInfoMonitoringSettingSubmit" id="cashlessInfoMonitoringSettingSubmit"><i class="fas fa-save"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>