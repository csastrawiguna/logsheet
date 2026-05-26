<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-0 pt-2">
        <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- <p id="surveyFilling" style="display: none;"><?= $isdonesurvey;?></p>
            <p id="surveyTreshold" style="display: none;"><?= $surveyTreshold ?></p> -->
            <?php 
              $allowedUser = ['1', '4', '5', '9'];
              function toStringDate($date){
                if(strtotime($date) < 0){
                  return '-';
                } else {
                  return date("d-M-Y h:i",strtotime($date));
                }
              }

              function hourToColor($val){
                if($val > 50) {
                  return 'bg-danger text-bold';
                } else if ($val > 35 && $val < 50) {
                  return 'text-danger text-bold';
                } else {
                  return 'text-primary';
                }
              }

              function trend($plan, $actual) {
                if ($plan > $actual) {
                  return '<i class="fas fa-arrow-down"></i>';
                } else if ($plan < $actual) {
                  return '<i class="fas fa-arrow-up text-danger"></i>';
                } else {
                  return '<i class="fas fa-check text-success"></i>';
                }
              }

              function travelTag($tag, $lic) {
                if (strtolower($tag) == 'jemputan') {
                  return '<span class="float-right pr-1" title="Jemputan"><i class="fas fa-car-side text-indigo"></i></span>';
                } else if (strtolower($tag) == 'leader') {
                  return '<span class="float-right pr-1" title="Leader in charge: ' . $lic . '"> <i class="fas fa-user-secret text-indigo"></i> </span>';
                } else if (strtolower($tag) == 'ramadhan shifting') {
                  return '<span class="float-right pr-1" title="Ramadhan Shifting"><i class="fas fa-clock text-success"></i></span>';
                } else {
                  return false;
                }
              }

              function changeScheduleTag($tag) {
                if (strtolower($tag) == 'swap') {
                  return '<span class="text-danger"><i class="fas fa-random"></i> Tukar Jadwal</span>';
                } else if (strtolower($tag) == 'replace_request') {
                   return '<span class="text-danger"><i class="fas fa-handshake"></i> Minta Ganti</span>';
                } else {
                  return '-';
                }
              }

              function changeScheduleTagShort($tag) {
                if (strtolower($tag) == 'swap') {
                  return '<span class="float-right pr-1" title="Tukar Jadwal"><i class="fas fa-random text-danger"></i></span>';
                } else if (strtolower($tag) == 'replace_request') {
                   return '<span class="float-right pr-1" title="Diganti"><i class="fas fa-handshake text-danger"></i></span>';
                } else {
                  return;
                }
              }

              function scheduleToTextColor($scheduled, $actual) {
                if ($scheduled !== $actual) {
                  return 'text-danger';
                } else {
                  return;
                }
              }

              function prodRemarkToState($remark) {
                if ($remark == NULL || $remark == '') {
                  return false;
                } else {
                  return '<span class="badge badge-pill float-right pr-1 buttonOvertimeProdRemark" title="' . $remark . '" data-remark="' . $remark . '" style="position: absolute; z-index: 99; cursor: pointer"><i class="fas fa-info-circle text-danger"></i></span>';
                }
              }

              function ramadhanShifting($tag) {
                if (strtolower($tag) == 'swap') {
                  return '<span class="float-right pr-1" title="Tukar Jadwal"><i class="fas fa-random text-danger"></i></span>';
                } else if (strtolower($tag) == 'replace_request') {
                   return '<span class="float-right pr-1" title="Diganti"><i class="fas fa-handshake text-danger"></i></span>';
                } else {
                  return;
                }
              }

              function ramadhanRow($tag) {
                if (strtolower($tag) == 'ramadhan shifting') {
                  return 'font-italic';
                } else {
                  return false;
                }
              }

            ?>

            <div class="row">
              <div class="col">
                  <div class="card">
                      <div class="card-header bg-primary">
                        List of Overtime Schedule
                        <div class="card-tools">
                            <a href="<?= base_url('obidience/benefit') ?>" class="text-white mr-2">
                              <i class="fas fa-calculator"></i> Salary simulation
                            </a>
                            <?php if (in_array($this->session->userdata('role_access'), $allowedUser)) : ?>                        
                            <a href="" class="text-white mr-2" data-toggle="modal" data-target="#addSingleScheduleModal" id="buttonaddSingleSchedule"> 
                              <i class="fas fa-plus-circle"></i> Add single
                            </a>
                            <a href="" class="text-white mr-2" data-toggle="modal" data-target="#scheduleUploadExcelModal" id="buttonAddScheduleExcel">
                              <i class="fas fa-upload"></i> Upload Excel
                            </a>
                            <a href="<?= base_url() ?>files/Format_Upload_Lemburan.xlsx" class="text-white mr-2"> 
                              <i class="fas fa-file-alt"></i> Format upload
                            </a>
                            <?php endif; ?>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">                        
                        <div class="row">
                            <form action="" id="formSelectPeriodObidienceDetail" class="form-row" method="POST" style="min-width: 660px; max-width: 670px;">
                              <label for="obidienceDetailDateStart" class="col-sm-1">Date</label>
                              <div class="col-sm-3">
                                <input type="date" id="obidienceDetailDateStart" name="obidienceDetailDateStart" class="form-control" value="<?= $startPeriod?>">
                              </div>
                              <div class="col-sm-3">
                                <input type="date" id="obidienceDetailDateEnd" name="obidienceDetailDateEnd" class="form-control" value="<?= $endPeriod?>">
                              </div>
                              <button type="submit" class="btn btn-outline-primary ml-1" id="buttonObidienceDetailSubmit" name="buttonObidienceDetailSubmit">Go</button>
                              <button type="button" class="btn btn-outline-success ml-1" id="buttonScheduleToExcel" name="buttonScheduleToExcel"><i class="fas fa-file-excel"></i></button>
                              <a href="<?= base_url('obidience/viewallschedule/') . $startPeriod . '/' . $endPeriod ?>" class="btn btn-outline-success ml-1"><i class="fas fa-list"></i></a>
                              <?php if(in_array($this->session->userdata('role_access'), $allowedUser)): ?>
                                <a href="#" data-toggle="modal" data-target="#productivityFillingModal" class="col-sm-auto">
                                  <button type="button" class="btn btn-outline-info"><i class="fas fa-marker"></i> Fill Prod.</button>
                                </a>
                              <?php endif; ?>
                            </form>
                        </div>
                        <div class="row my-4">
                          <div class="col rounded pt-2" style="background-color: rgba(244, 244, 224, 1.0);">
                            <?php if (strtolower($this->session->userdata('employement')) == 'permanent') : ?>
                              <p class="lead text-center text-danger text-bold">Untuk karyawan Permanen/SEID sangat direkomendasikan jumlah Plan lembur & Aktual sama</p>
                            <?php else :  ?>
                              <ul style="font-size: 1.1rem;">
                                <li class="text-danger">Lembur malam, jam kerja <strong>mulai 08:50,</strong> bukan <span style="text-decoration: line-through;">09:00</span></li>
                                <li>Tukar/ganti, perhatikan penggantinya: <span class="text-bold text-danger">[Agent senior <i class="fas fa-arrows-alt-h"></i> Agent senior]</span> , <span class="text-bold text-indigo">[Agent junior <i class="fas fa-arrows-alt-h"></i> agent junior]</span></li>
                              </ul>
                            <?php endif; ?>
                          </div>
                        </div>
                        
                        <div class="row">
                           <div class="col">
                               <table class="table table-hover table-sm table-bordered" id="tableObidienceExchange">
                                   <thead class="bg-light">
                                       <tr class="text-center">
                                           <th rowspan="2" class="align-middle">#</th>
                                           <th rowspan="2" class="align-middle">Date</th>
                                           <th colspan="3">Schedule</th>
                                           <th colspan="4" class="bg-light">Actual</th>
                                           <th rowspan="2" class="align-middle">Reason</th>
                                           <!-- <th rowspan="2" class="align-middle">Remark</th> -->
                                           <th rowspan="2" class="align-middle"><i class="fas fa-bars"></i></th>
                                       </tr>
                                       <tr class="text-center">
                                         <th class="">Agent</th>
                                         <th class="">Start</th>
                                         <th class="">Finish</th>
                                         <th class="">Agent</th>
                                         <th class="">Start</th>
                                         <th class="">Finish</th>
                                         <th class="">Prod.</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                      <?php $i = 1; ?>
                                      <?php foreach ($allSchedule as $data) : ?>
                                        <tr class="<?= ramadhanRow($data['remark'])?>">
                                            <td class="text-center"><?= $i++; ?></td>
                                            <td class="text-center"><?= date("d-M-y", strtotime($data['date']))?></td>
                                            <td class="<?= scheduleToTextColor($data['agent_scheduled'], $data['actual_overtime']) ?>">
                                              <?= changeScheduleTagShort($data['replace_mark']); ?> 
                                              <?= $data['agent_scheduled'] . travelTag($data['remark'], $data['leader_in_charge']); ?>
                                            </td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['time_start'])) ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['time_end'])) ?></td>
                                            <td class="text-primary text-bold"><?= $data['actual_overtime'] ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['actual_start'])) ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['actual_end'])) ?></td>
                                            <td class="text-center text-indigo">
                                              <?= number_format(($data['prod_call'] + $data['prod_whatsapp'] + $data['prod_followup'] + $data['prod_others']), 0) ?>
                                              <?= prodRemarkToState($data['prod_remark']) ?>
                                            </td>
                                            <td><?= $data['reason'] ?></td>
                                            <!-- <?php if ($data['remark'] == "Jemputan") :  ?>
                                              <td class="text-indigo text-bold"><?= $data['remark'] ?></td>
                                            <?php else : ?>
                                              <td><?= $data['remark'] == 'Leader' ? 'Leader<br><small>[' . $data['leader_in_charge'] . ']</small>' : ''; ?></td>
                                            <?php endif?> -->
                                            <td>                                                  
                                              <div class="btn-group">
                                                <button class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars"></i></button>
                                                <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                                                  <table class="table table-sm table-borderless table-hover">
                                                    <tbody>
                                                      <tr>
                                                        <td>Change schedule</td>
                                                        <td class="">: <?= changeScheduleTag($data['replace_mark']); ?></td>
                                                      </tr>
                                                      <tr>
                                                        <td>Last modified by</td>
                                                        <td class="">: <?= $data['last_modified_by']; ?></td>
                                                      </tr>
                                                      <tr>
                                                        <td>Last modified at</td>
                                                        <td class="">: <?= toStringDate($data['last_modified_at']); ?></td>
                                                      </tr>
                                                      <tr class="text-bold">
                                                        <td>Leader in charge:</td>
                                                        <td class="">: <?= $data['leader_in_charge']; ?></td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                  
                                                  <div class="dropdown-divider"></div>
                                                  <div class="my-2">
                                                    <a href="" class="buttonEditScheduleReplace" data-toggle="modal" data-target="#scheduleReplaceModal" id="buttonAddScheduleReplace" data-id="<?= $data['id']; ?>"> 
                                                      <i class="fas fa-hands-helping"></i> Replace schedule (ganti)
                                                    </a>
                                                  </div>
                                                  <div class="my-2">
                                                    <a href="" class="buttonEditScheduleSwap my-1" data-toggle="modal" data-target="#scheduleSwapModal" id="buttonAddScheduleSwap" data-id="<?= $data['id']; ?>"> 
                                                      <i class="fas fa-random"></i> Swap with others (tukar jadwal)
                                                    </a>
                                                  </div> 
                                                  <div class="my-2">
                                                    <?php if ($this->session->userdata('role_access') == 9 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 1  ): ?>
                                                      <a href="" class="buttonEditScheduleUpdate" data-toggle="modal" data-target="#scheduleUpdateModal" id="" data-id="<?= $data['id']; ?>"> 
                                                        <i class="fas fa-redo"></i> Update
                                                      </a>
                                                    <?php endif; ?> 
                                                  </div>
                                                  
                                                  <div class="dropdown-divider"></div>
                                                  <div class="my-2">
                                                    <?php if ($this->session->userdata('role_access') == 9 || $this->session->userdata('role_access') == 5  ): ?>
                                                    <a href="" class="buttonDeleteScheduleUpdate text-danger"  data-id="<?= $data['id']; ?>"> 
                                                      <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                  <?php endif; ?> 
                                                  </div>
                                                </div>
                                              </div>
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
            </div>
            
            <div class="row mt-2">
              <div class="col-md-6">
                <div class="card card-outline card-primary">
                  <div class="card-header">
                    Actual/scheduled overtime duration 
                  </div>
                  <div class="card-body">
                    <table class="table table-sm table-bordered" id="tableAllAgentOvertimeDuration">
                        <thead>
                          <tr>
                            <th class="align-middle text-center">#</th>
                            <th class="align-middle">Agent</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Actual</th>
                            <th class="text-center">Vs</th>
                          </tr>                          
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>                          
                          <?php foreach ($overtimeDurationData as $data): ?>
                            <tr>
                              <td class="text-center"><?= $i++; ?></td>
                              <td><?= $data['agent']; ?></td>
                              <td class="text-center <?= hourToColor($data['duration_plan']); ?>"><?= number_format($data['duration_plan'], 1); ?></td>
                              <td class="text-center <?= hourToColor($data['duration_actual']); ?>"><?= number_format($data['duration_actual'], 1); ?></td>
                              <td class="text-center">
                                <?= trend($data['duration_plan'],$data['duration_actual']); ?>
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
</div>

<!-- modal exchange -->
<div class="modal fade" id="scheduleReplaceModal" tabindex="-1" role="dialog" aria-labelledby="scheduleExchangeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="scheduleExchangeModalLabel">Overtime Schedule Exchangement</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="post" action="">
        <input type="hidden" class="form-control" id="replacemark" name="replacemark" value="replace_request" readonly>
        <input type="hidden" class="form-control" id="scheduleExchangeId" name="scheduleExchangeId" readonly>
        <div class="form-group">
          <label for="scheduleExchangeDate" class="form-label">Date</label>
          <div class="">
            <input type="date" class="form-control" id="scheduleExchangeDate" name="scheduleExchangeDate" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleExchangeAgentScheduled" class="form-label">Actual Overtime</label>
          <div class="">            
            <input type="" class="form-control" id="scheduleExchangeAgentScheduled" name="scheduleExchangeAgentScheduled" readonly>
          </div>
        </div>
        <div class="form-group">
        <label for="scheduleExchangeReplacedBy" class="form-label">Replaced by (diganti oleh)</label>
          <div class="">
            <select class="form-control custom-select" id="scheduleExchangeReplacedBy" name="scheduleExchangeReplacedBy">
              <?php foreach($allAgents as $agent): ?>
                <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleExchangeReason" class="form-label">Reason (alasan)</label>
          <div class="">
            <input type="" class="form-control" id="scheduleExchangeReason" name="scheduleExchangeReason">
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleExchangeRemark" class="form-label">Remark (keterangan) - opsional</label>
          <div class="">
            <input type="" class="form-control" id="scheduleExchangeRemark" name="scheduleExchangeRemark">
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <!-- <label for="scheduleExchangeTimeStart" class="form-label">Time Start</label> -->
              <div class="">
                <input type="hidden" class="form-control" id="scheduleExchangeTimeStart" name="scheduleExchangeTimeStart" readonly>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <!-- <label for="scheduleExchangeTimeEnd" class="form-label">Time End</label> -->
              <div class="">
                <input type="hidden" class="form-control" id="scheduleExchangeTimeEnd" name="scheduleExchangeTimeEnd" readonly>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="scheduleExchangeUpdate" id="scheduleExchangeUpdate">Update</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>

<!-- modal swap schedule -->
<div class="modal fade" id="scheduleSwapModal" tabindex="-1" role="dialog" aria-labelledby="scheduleSwapLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">      
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleSwapModalLabel">Swap/switch Overtime Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="<?= base_url('obidience/swapSchedule') ?>">
        <div class="modal-body">
          <input type="hidden" class="form-control" id="swapmark" name="swapmark" value="swap" readonly>
          <input type="hidden" class="form-control" id="scheduleSwapIdFrom" name="scheduleSwapIdFrom" readonly>
          <h6 class="text-primary text-bold p-2">Schedule (jadwal)</h6>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapDateFrom" class="form-label">Date (tanggal)</label>
                <div class="">            
                  <input type="date" class="form-control" id="scheduleSwapDateFrom" name="scheduleSwapDateFrom" readonly placeholder="date">
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapAgentFrom" class="form-label">Agent</label>
                <div class="">            
                  <input type="" class="form-control" id="scheduleSwapAgentFrom" name="scheduleSwapAgentFrom" readonly>
                </div>
              </div>
            </div>
          </div>                      
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapTimeStartFrom" class="form-label">Time Start</label>
                <div class="">
                  <input type="time" class="form-control" id="scheduleSwapTimeStartFrom" name="scheduleSwapTimeStartFrom" readonly>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapTimeEndFrom" class="form-label">Time End</label>
                <div class="">
                  <input type="time" class="form-control" id="scheduleSwapTimeEndFrom" name="scheduleSwapTimeEndFrom" readonly>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" id="scheduleSwapDurationFrom" name="scheduleSwapDurationFrom" readonly>
          <div class="form-group">
            <label for="scheduleSwapReasonFrom" class="form-label">Reason (alasan)</label>
            <div class="">
              <input type="" class="form-control" id="scheduleSwapReasonFrom" name="scheduleSwapReasonFrom"From>
            </div>
          </div>
          <div class="form-group mt-4">
            <h6 class="text-primary text-bold p-2">Swap with (tukar dengan)</h6>
          </div>
          <input type="hidden" class="form-control" id="scheduleSwapIdTo" name="scheduleSwapIdTo" readonly>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapDateTo" class="form-label">Date (tanggal) and</label>
                <div class="">            
                  <input type="date" class="form-control" id="scheduleSwapDateTo" name="scheduleSwapDateTo">
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapAgentTo" class="form-label">Agent</label>
                <select class="form-control custom-select" id="scheduleSwapAgentTo" name="scheduleSwapAgentTo">
                  <?php foreach($allAgents as $agent): ?>
                    <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
                  <?php endforeach; ?>
                </select>                
              </div>
            </div>
          </div>                      
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapTimeStartTo" class="form-label">Time Start</label>
                <div class="">
                  <input type="time" class="form-control" id="scheduleSwapTimeStartTo" name="scheduleSwapTimeStartTo" readonly>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="scheduleSwapTimeEndTo" class="form-label">Time End</label>
                <div class="">
                  <input type="time" class="form-control" id="scheduleSwapTimeEndTo" name="scheduleSwapTimeEndTo" readonly>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" id="scheduleSwapDurationTo" name="scheduleSwapDurationTo" readonly>
        </div>    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="scheduleSwapUpdate" id="scheduleSwapUpdate">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- modal UPDATE SCHEDULE -->
<div class="modal fade" id="scheduleUpdateModal" tabindex="-1" role="dialog" aria-labelledby="scheduleUpdateLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md" role="document" style="max-width: 560px;">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title text-primary" id="scheduleUpdateModalLabel">Update Overtime Schedule</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="post" action="<?= base_url('obidience/update'); ?>">
        <input type="hidden" class="form-control" id="scheduleUpdateId" name="scheduleUpdateId" >
        <div class="form-group row">
          <label for="scheduleUpdateDate" class="col-sm-2 col-form-label">Date</label>
          <div class="col-sm-6">
              <input type="date" class="form-control" id="scheduleUpdateDate" name="scheduleUpdateDate" value="<?= date("Y-m-01"); ?>">
          </div>
        </div>
        <hr>
        <div class="form-group row" style="margin-bottom: 1px;">
          <label for="scheduleUpdateAgentScheduled" class="col-sm-2 col-form-label">Plan</label>
          <div class="col-sm-6">
            <div class="form-group">
              <select class="form-control custom-select" id="scheduleUpdateAgentScheduled" name="scheduleUpdateAgentScheduled">
                <?php foreach($allAgents as $agent): ?>
                  <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
                <?php endforeach; ?>
              </select> 
            </div>
          </div>
        </div>
        <div class="form-group row border-bottom">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <div class="row mt-0">
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateTimeStart" class="form-label badge">Time Start</label>
                  <div class="">
                    <input type="time" class="form-control" id="scheduleUpdateTimeStart" name="scheduleUpdateTimeStart" >
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateTimeEnd" class="form-label badge">Time End</label>
                  <div class="">
                    <input type="time" class="form-control" id="scheduleUpdateTimeEnd" name="scheduleUpdateTimeEnd" >
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateDuration" class="form-label badge">Duration</label>
                  <div class="">
                    <input type="" class="form-control" id="scheduleUpdateDuration" name="scheduleUpdateDuration">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="form-group row" style="margin-bottom: 1px;">
          <label for="scheduleUpdateDate" class="col-sm-2 col-form-label">Actual</label>
          <div class="col-sm-6">
            <div class="form-group">
              <select class="form-control custom-select" id="scheduleUpdateActualOvertime" name="scheduleUpdateActualOvertime">
                <?php foreach($allAgents as $agent): ?>
                  <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
                <?php endforeach; ?>
              </select> 
            </div>
          </div>
        </div>
        <div class="form-group row border-bottom">
          <label for="" class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10">
            <div class="row mt-0">
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateActualStart" class="form-label badge">Actual Start</label>
                  <div class="">
                    <input type="time" class="form-control" id="scheduleUpdateActualStart" name="scheduleUpdateActualStart" >
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateActualEnd" class="form-label badge">Actual End</label>
                  <div class="">
                    <input type="time" class="form-control" id="scheduleUpdateActualEnd" name="scheduleUpdateActualEnd" >
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="scheduleUpdateActualDuration" class="form-label badge">Duration</label>
                  <div class="">
                    <input type="" class="form-control" id="scheduleUpdateActualDuration" name="scheduleUpdateActualDuration">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="form-group row">
          <label for="scheduleUpdateReason" class="col-sm-2 col-form-label">Reason</label>
          <div class="col-sm-10">
              <input type="" class="form-control" id="scheduleUpdateReason" name="scheduleUpdateReason" value="" placeholder="Alasan tidak lembur">
          </div>
        </div>
        <div class="form-group row">
          <label for="scheduleUpdateRemark" class="col-sm-2 col-form-label">Remark</label>
          <div class="col-sm-10">
              <input type="" class="form-control" id="scheduleUpdateRemark" name="scheduleUpdateRemark" value="" placeholder="Keterangan tambahan">
          </div>
        </div>
      </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="scheduleUpdateUpdate" id="scheduleUpdateUpdate">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- modal ADD SINGLE SCHEDULE (1 STAFF ON CERTAIN DAY) -->
<div class="modal fade" id="addSingleScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addSingleScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSingleScheduleModalLabel">Add single schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="<?= base_url('obidience/addSingleSchedule'); ?>">
        <div class="modal-body">
          <div class="form-group row">
            <label for="addSingleScheduleDate" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-6">
                <input type="date" class="form-control" id="addSingleScheduleDate" name="addSingleScheduleDate" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="addSingleScheduleType" class="col-sm-2 col-form-label">OT type</label>
            <div class="col-sm-6">
                <select class="custom-select" id="addSingleScheduleType" name="addSingleScheduleType">
                  <option value="">- select -</option>
                  <option value="Day Off">Day Off</option>
                  <option value="Short">Short</option>
                </select>
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <label for="addSingleScheduleAgentScheduled" class="col-sm-2 col-form-label">Agent</label>
            <div class="col-sm-6">
              <div class="form-group">
                <select class="form-control custom-select" id="addSingleScheduleAgentScheduled" name="addSingleScheduleAgentScheduled">
                  <option value="">- select agent -</option>
                  <?php foreach($allAgents as $agent): ?>
                    <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
                  <?php endforeach; ?>
                </select> 
              </div>
            </div>
          </div>
          <div class="form-group row border-bottom">
            <label for="" class="col-sm-2 col-form-label">Time</label>
            <div class="col-sm-10">
              <div class="row mt-0">
                <div class="col">
                  <div class="form-group">
                    <label for="addSingleScheduleTimeStart" class="form-label badge">Start</label>
                    <div class="">
                      <input type="time" class="form-control" id="addSingleScheduleTimeStart" name="addSingleScheduleTimeStart" >
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="addSingleScheduleTimeEnd" class="form-label badge">End</label>
                    <div class="">
                      <input type="time" class="form-control" id="addSingleScheduleTimeEnd" name="addSingleScheduleTimeEnd" >
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="addSingleScheduleDuration" class="form-label badge">Duration</label>
                    <div class="">
                      <input type="" class="form-control" id="addSingleScheduleDuration" name="addSingleScheduleDuration">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="addSingleScheduleReason" class="col-sm-2 col-form-label">Reason</label>
            <div class="col-sm-10">
                <input type="" class="form-control" id="addSingleScheduleReason" name="addSingleScheduleReason" value="">
            </div>
          </div>
          <div class="form-group row">
            <label for="addSingleScheduleRemark" class="col-sm-2 col-form-label">Remark</label>
            <div class="col-sm-10">
                <input type="" class="form-control" id="addSingleScheduleRemark" name="addSingleScheduleRemark" value="">
            </div>
          </div>        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="addSingleScheduleUpdate" id="addSingleScheduleUpdate">Save</button>
        </div>
    </form>
      </div>
  </div>
</div>

<!-- modal upload schedule dari Excel -->
<div class="modal fade" id="scheduleUploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="scheduleUploadExcelLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="scheduleUploadExcelModalLabel">Upload Overtime Schedule</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <?= form_open_multipart('obidience/uploadScheduleExcel'); ?>
        <div class="form-group row">
          <label for="scheduleUploadExcel" class="col-sm-2">Data</label>
          <input type="file" class="col-sm-10" id="scheduleUploadExcel" name="scheduleUploadExcel">
        </div>
      </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="scheduleUploadExcelSubmit">Upload</button>
        </div>
      </form>      
    </div>
    </div>
  </div>
</div>

<!-- modal CHOOSE DATA OT PRODUCTIVITY FILLING -->
<div class="modal fade" id="productivityFillingModal" tabindex="-1" role="dialog" aria-labelledby="productivityFillingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 560px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="productivityFillingModalLabel">Select OT date to fill Productivity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?= base_url('obidience/productivityfilling/')?>">
          <div class="form-group">
            <div class="row">
              <div class="col-sm-6">
                <label for="productivityFillingStartdate" class="col-form-label">Start from</label>
                <input type="date" class="form-control" id="productivityFillingStartdate" name="productivityFillingStartdate" value="<?= $startPeriod; ?>">
              </div>
              <div class="col-sm-6">
                <label for="productivityFillingEnddate" class="col-form-label">Until</label>
                <input type="date" class="form-control" id="productivityFillingEnddate" name="productivityFillingEnddate" value="<?= $endPeriod; ?>">
              </div>
            </div>
          </div>
          <hr>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-primary" name="productivityFillingSubmit" id="productivityFillingSubmit"><i class="fas fa-file-alt"></i> Get data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>