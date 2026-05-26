<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-0 pt-3">
        <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- <p id="surveyFilling" style="display: none;"><?= $isdonesurvey;?></p>
            <p id="surveyTreshold" style="display: none;"><?= $surveyTreshold ?></p> -->
            <?php 
              $allowedUser = ['1', '5', '9'];
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
                   return '<span class="float-right pr-1" title="Leader in charge: ' . $lic . '"> <i class="fas fa-user-secret text-danger"></i> </span>';
                } else {
                  return false;
                }
              }

              function changeScheduleTag($tag) {
                if (strtolower($tag) == 'swap') {
                  return '<span class="text-indigo"><i class="fas fa-random"></i> Tukar Jadwal</span>';
                } else if (strtolower($tag) == 'replace_request') {
                   return '<span class="text-danger"><i class="fas fa-handshake"></i> Minta Ganti</span>';
                } else {
                  return '-';
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
                          <div class="col">
                            <form action="" id="formSelectPeriodObidienceDetail" class="form-row mb-4" method="post" style="width: 520px;">
                              <div class="row">
                                <label for="obidienceDetailDateStart" class="col-sm-2">Period</label>
                                <div class="col-sm-4">
                                  <input type="date" id="obidienceDetailDateStart" name="obidienceDetailDateStart" class="form-control" value="<?= $startPeriod?>">
                                </div>
                                <div class="col-sm-4">
                                  <input type="date" id="obidienceDetailDateEnd" name="obidienceDetailDateEnd" class="form-control" value="<?= $endPeriod?>">
                                </div>
                                <div class="col-sm-1">
                                  <button type="submit" class="btn btn-outline-primary" id="buttonObidienceDetailSubmit" name="buttonObidienceDetailSubmit">Go</button>                                      
                                </div>
                                <div class="col-sm-1">
                                  <button type="button" class="btn btn-outline-success ml-1" id="buttonScheduleToExcel" name="buttonScheduleToExcel"><!-- <i class="fas fa-file-excel"></i> --> Excel</button>
                                </div>
                              </div>
                            </form>
                          </div>
                          <!-- <div style="position: absolute; right: 30px;" class="text-danger">Jika Idul Fitri 21 April, <a href="<?= base_url('obidience/schedule') ?>">jadwalnya disini</a></div> -->
                        </div>
                        <div class="row mb-4" style="display: none">
                          <div class="col bg-danger m-3">
                            <h4 class="my-3 text-center">Jadwal masih tentatif</h4>
                          </div>
                        </div>
                        <?php if (strtolower($this->session->userdata('employement')) == 'permanent') : ?>
                          <div class="row">
                            <div class="col">
                              <p class="lead text-center pt-1 pb-2 bg-warning" ><span class="">Untuk karyawan Permanen/SEID sangat direkomendasikan jumlah Plan lembur & Aktual sama</span></p>
                            </div>
                          </div>
                        <?php else :  ?>
                          <div class="row">
                            <div class="col">
                              <p class="lead text-center pt-1 pb-2 bg-light"><span class="text-danger"><i class="fas fa-info-circle"></i> Yang lembur malam, jam kerja <strong>mulai 08:50</strong>, bukan <span style="text-decoration: line-through;">09:00</span></span></p>
                            </div>
                          </div>
                        <?php endif; ?>
                        
                        <div class="row">
                           <div class="col">
                               <table class="table table-hover table-sm table-bordered" id="tableObidienceExchange">
                                   <thead class="bg-light">
                                       <tr class="text-center">
                                           <th rowspan="2" class="align-middle">#</th>
                                           <th rowspan="2" class="align-middle">Date</th>
                                           <th colspan="3">Schedule</th>
                                           <th colspan="3">Actual</th>
                                           <th rowspan="2" class="align-middle">Reason</th>
                                           <!-- <th rowspan="2" class="align-middle">Remark</th> -->
                                           <th rowspan="2" class="align-middle"><i class="fas fa-bars"></i></th>
                                       </tr>
                                       <tr class="text-center">
                                         <th>Agent</th>
                                         <th>Start</th>
                                         <th>Finish</th>
                                         <th>Agent</th>
                                         <th>Start</th>
                                         <th>Finish</th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                      <?php $i = 1; ?>
                                      <?php foreach ($allSchedule as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $i++; ?></td>
                                            <td class="text-center"><?= date("d-M-y", strtotime($data['date']))?></td>
                                            <td><?= $data['agent_scheduled'] . travelTag($data['remark'], $data['leader_in_charge']) ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['time_start'])) ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['time_end'])) ?></td>
                                            <td><?= $data['actual_overtime'] ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['actual_start'])) ?></td>
                                            <td class="text-center"><?= date("H:i", strtotime($data['actual_end'])) ?></td>
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
                                                      <tr>
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
                            <th class="align-middle">#</th>
                            <th class="align-middle">Agent</th>
                            <th class="text-center">Plan</th>
                            <th class="text-center">Actual</th>
                            <th class="text-center">Vs</th>
                          </tr>                          
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>                          
                          <?php foreach($overtimeDurationData as $data): ?>
                            <tr>
                              <td><?= $i++; ?></td>
                              <td><?= $data['agent']; ?></td>
                              <td class="text-center <?= hourToColor($data['plan']); ?>"><?= number_format($data['plan'], 1); ?></td>
                              <td class="text-center <?= hourToColor($data['actual']); ?>"><?= number_format($data['actual'], 1); ?></td>
                              <td class="text-center">
                                <?= trend($data['plan'],$data['actual']); ?>
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
          <label for="scheduleExchangeAgentScheduled" class="form-label">Agent scheduled (jadwal)</label>
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
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="scheduleUpdateModalLabel">Update Overtime Schedule</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="post" action="<?= base_url('obidience/update'); ?>">
        <input type="hidden" class="form-control" id="scheduleUpdateId" name="scheduleUpdateId" >
        <div class="form-group">
          <label for="scheduleUpdateDate" class="form-label">Date</label>
          <div class="">
            <input type="date" class="form-control" id="scheduleUpdateDate" name="scheduleUpdateDate" >
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleUpdateAgentScheduled" class="form-label">Agent scheduled (jadwal)</label>          
          <select class="form-control custom-select" id="scheduleUpdateAgentScheduled" name="scheduleUpdateAgentScheduled">
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div>
        <div class="form-group">
          <label for="scheduleUpdateReplacedBy" class="form-label">Replaced by (diganti oleh)</label>          
          <select class="form-control custom-select" id="scheduleUpdateReplacedBy" name="scheduleUpdateReplacedBy">
          	<option></option>
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div>
        <div class="form-group">
          <label for="scheduleUpdateReason" class="form-label">Alasan</label>
          <div class="">
            <input type="" class="form-control" id="scheduleUpdateReason" name="scheduleUpdateReason">
          </div>
        </div>    
        <div class="form-group">
          <label for="scheduleUpdateRemark" class="form-label">Remark (keterangan) - opsional</label>
          <div class="">
            <input type="" class="form-control" id="scheduleUpdateRemark" name="scheduleUpdateRemark">
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleUpdateActualOvertime" class="form-label">Actual Overtime</label>          
          <select class="form-control custom-select" id="scheduleUpdateActualOvertime" name="scheduleUpdateActualOvertime">
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div> 
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="scheduleUpdateTimeStart" class="form-label">Time Start</label>
              <div class="">
                <input type="time" class="form-control" id="scheduleUpdateTimeStart" name="scheduleUpdateTimeStart" >
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="scheduleUpdateTimeEnd" class="form-label">Time End</label>
              <div class="">
                <input type="time" class="form-control" id="scheduleUpdateTimeEnd" name="scheduleUpdateTimeEnd" >
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleUpdateDuration" class="form-label">OT duration</label>
          <div class="">
            <input type="" class="form-control" id="scheduleUpdateDuration" name="scheduleUpdateDuration">
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
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="addSingleScheduleModalLabel">Add single schedule</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="post" action="<?= base_url('obidience/addSingleSchedule'); ?>">
        <div class="form-group">
          <label for="addSingleScheduleDate" class="form-label">Date</label>
          <div class="">
            <input type="date" class="form-control" id="addSingleScheduleDate" name="addSingleScheduleDate" >
          </div>
        </div>
        <div class="form-group">
          <label for="scheduleUpdateAgentScheduled" class="form-label">Agent scheduled (jadwal)</label>          
          <select class="form-control custom-select" id="addSingleScheduleAgentScheduled" name="addSingleScheduleAgentScheduled">
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div>
        <!-- <div class="form-group">
          <label for="addSingleScheduleReplacedBy" class="form-label">Replaced by (diganti oleh)</label>          
          <select class="form-control custom-select" id="addSingleScheduleReplacedBy" name="addSingleScheduleReplacedBy">
            <option></option>
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div> -->
        <!-- <div class="form-group">
          <label for="addSingleScheduleReason" class="form-label">Alasan</label>
          <div class="">
            <input type="" class="form-control" id="addSingleScheduleReason" name="addSingleScheduleReason">
          </div>
        </div> -->    
        <div class="form-group">
          <label for="addSingleScheduleRemark" class="form-label">Remark (keterangan) - opsional</label>
          <div class="">
            <input type="" class="form-control" id="addSingleScheduleRemark" name="addSingleScheduleRemark">
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="addSingleScheduleTimeStart" class="form-label">Time Start</label>
              <div class="">
                <input type="time" class="form-control" id="addSingleScheduleTimeStart" name="addSingleScheduleTimeStart" >
              </div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="addSingleScheduleTimeEnd" class="form-label">Time End</label>
              <div class="">
                <input type="time" class="form-control" id="addSingleScheduleTimeEnd" name="addSingleScheduleTimeEnd" >
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="addSingleScheduleDuration" class="form-label">OT duration</label>
          <div class="">
            <input type="" class="form-control" id="addSingleScheduleDuration" name="addSingleScheduleDuration">
          </div>
        </div>
      </div>
<!--         <div class="form-group">
          <label for="addSingleScheduleActualOvertime" class="form-label">Actual Overtime</label>          
          <select class="form-control custom-select" id="addSingleScheduleActualOvertime" name="addSingleScheduleActualOvertime">
            <?php foreach($allAgents as $agent): ?>
              <option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
            <?php endforeach; ?>
          </select> 
        </div>  -->        

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

<div class="modal fade" id="modalInfoIsiFeedbackNewskape" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalInfoIsiFeedbackNewskapeLabel" aria-hidden="false">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body bg-purple">
        <p class="text-center h3 mt-3 p-2">
          Tolong masukan, komentar, ataupun feedback lain buat <span class="text-warning">NEW SKAPE</span>
          <br>
        </p>
        <p class="text-center h5 text-light p-2">
          Masukan-masukan sangat diperlukan biar <span class="text-warning">NEW SKAPE</span> bisa lebih baik, minimal sama dengan SKAPE lama
        </p>        
        <p class="text-center mt-5">
          <button class="btn btn-light" data-dismiss="modal" aria-label="Close">OK, mengerti. Isi nanti</button>
          <a href="<?= base_url('survey/skapefeedback') ?>"><button class="btn btn-warning">Isi feedback sekarang</button></a>
        </p>
      </div>             
    </div>
  </div>
</div>