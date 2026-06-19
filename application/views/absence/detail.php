<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid pt-2 px-1">
            <?php 
                if($this->input->post('absentDetailDateStart') && $this->input->post('absentDetailDateEnd')){
                    $startPeriod = $this->input->post('absentDetailDateStart');
                    $endPeriod = $this->input->post('absentDetailDateEnd');
                } else{
                    $startPeriod = date("Y-m-d", strtotime("-3 months"));
                    $endPeriod = date("Y-m-d");
                }

                function toStringDate($date){
                    if(strtotime($date) < 0){
                      return '-';
                    } else {
                      return date("d-M-Y h:i",strtotime($date));
                    }
                  }
            ?>

    		<div class="card">
    			<div class="card-header bg-primary">
    				List of Staff Absent
                    <div class="card-tools">                                
                        <a href="#" class="text-white mr-3" id="buttonAbsentAdd" data-toggle="modal" data-target="#absentAdd"><i class="fas fa-plus-circle" ></i> Add data</a>                                
                    </div>
    			</div>
    			<div class="card-body">
                    <div class="row">
                        <div class="col-sm mb-4">
                            <form action="" class="form-row" method="post" style="width: 560px;">
                                <label for="absentDetailDateStart" class="col-sm-2">Period</label>
                                <div class="col-sm-3">
                                    <input type="date" id="absentDetailDateStart" name="absentDetailDateStart" class="form-control" value="<?= $startPeriod; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <input type="date" id="absentDetailDateEnd" name="absentDetailDateEnd" class="form-control" value="<?= $endPeriod; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-outline-primary" id="buttonSelectabsentDetail" name="buttonSelectabsentDetail">Go</button>
                                    <button type="button" class="btn btn-outline-success ml-1" id="buttonToExcelAbsentDetail" name="buttonToExcelAbsentDetail"><i class="fas fa-file-excel"></i></button>
                                </div>
                            </form>                    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">                    
            				<table id="tableAbsenceDetail" class="table table-sm">
            					<thead>
            						<tr class="text-center" style="max-width: 40px;">
            							<th>No</th>
			            				<th>Date</th>
			            				<th>Agent</th>
			            				<th>Absent</th>
			            				<th>Reason</th>
			            				<th>Remark</th>
                                        <th>Action</th>
            						</tr>
            					</thead>
            					<tbody>
                                    <?php $i = 1; ?>
            						<?php foreach($allAbsentData as $data): ?>
                                        <tr>
                                            <td class="text-center"><?= $i++; ?></td>
                                            <td><?= date("d-M-y", strtotime($data['absent_date'])); ?></td>
                                            <td><?= $data['cti_id']; ?></td>
                                            <td><?= $data['permit_type']; ?></td>
                                            <td><?= $data['permit_reason']; ?></td>
                                            <td><?= $data['permit_remark']; ?></td>
                                            <td class="text-center h6">
                                                <div class="btn-group">                              
                                                  <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                  <div class="dropdown-menu dropdown-menu-right bg-light p-2" style="min-width: 300px;">
                                                    <table  class="table table-sm table-borderless table-hover">
                                                      <tbody>
                                                        <tr>
                                                          <td>Saved by</td>
                                                          <td class="">: <?= $data['input_by']; ?></td>
                                                        </tr>
                                                        <tr>
                                                          <td>Saved at</td>
                                                          <td class="">: <?= toStringDate($data['input_at']); ?></td>
                                                        </tr>
                                                        <tr>
                                                          <td>Last modified</td>
                                                          <td class="">: <?= $data['last_modified_by']; ?></td>
                                                        </tr>
                                                        <tr>
                                                          <td>Datetime</td>
                                                          <td class="">: <?= toStringDate($data['last_modified_at']); ?></td>
                                                        </tr>
                                                      </tbody>
                                                    </table>
                                                    <table class="table table-sm table-borderless">
                                                      <tbody>
                                                        <tr class="border-top">
                                                          <td class="py-2">                                        
                                                            <a href="" class="text-dark buttonAbsentDataEdit" title="Edit data" data-id="<?= $data['absent_id']; ?>" data-toggle="modal" data-target="#absentAdd">
                                                                <i class="fas fa-pen"></i> &nbspEdit data
                                                          </a>
                                                          </td>
                                                        </tr>
                                                        <tr class="border-top">
                                                          <td class="py-2">
                                                            <a class="text-danger buttonAbsentDataDelete" href="<?= base_url()?>absence/deleteAbsenceById/<?=$data['absent_id']?>" data-id="<?= $data['absent_id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                                                <i class="fas fa-trash"></i> &nbspDelete data
                                                            </a>
                                                          </td>
                                                        </tr>  
                                                      </tbody>
                                                    </table> 
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
        </div><!-- /.container-fluid -->
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="absentAdd" tabindex="-1" role="dialog" aria-labelledby="absentAddLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="absentAddLabel">Add Absent Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="">
            <div class="modal-body">
                <input type="hidden" name="absentAddDateId" id="absentAddDateid" readonly>
                <div class="form-group">
                    <label for="absentAddDate" class="form-label">Date</label>
                    <div class="">
                        <input type="date" class="form-control" id="absentAddDate" name="absentAddDate" value="<?= date("Y-m-d") ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="absentAddAgent" class="form-label">Agent</label>
                    <div class="">
                        <select type="" class="custom-select js-example-basic-single" id="absentAddAgent" name="absentAddAgent">
                            <option>-- select agent --</option>
                            <?php foreach($allAgents as $agent): ?>
                                <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="absentAddPermitType" class="form-label">Permit type (Ijin)</label>
                    <div class="">
                        <select type="" class="custom-select js-example-basic-single" id="absentAddPermitType" name="absentAddPermitType">
                            <option>-- select permit --</option>
                            <option value="Annual leave">Annual leave - Cuti tahunan</option>
                            <option value="PKB special leave">PKB special leave - Cuti khusus PKB</option>
                            <option value="Long leave">Long leave - Cuti kelipatan</option>
                            <option value="Unpaid leave">Unpaid leave - Ijin potong gaji</option>
                            <option value="Sick">Sick - Sakit</option>
                            <option value="Covid">Covid case</option>
                            <option value="3 hours-permit">3 hours-permit - Ijin 3 jam</option>
                            <option value="Coming late">Coming late - Datang terlambat</option>
                            <option value="Biztrip">Biztrip - Dinas luar kota</option>
                            <option value="WFH">WFH</option>
                            <option value="Others">Others - Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="absentAddReason" class="form-label">Reason (alasan)</label>
                    <div class="">
                        <input type="" class="form-control" id="absentAddReason" name="absentAddReason">
                    </div>
                </div>
                <div class="form-group">
                    <label for="absentAddRemark" class="form-label">Remark (keterangan)</label>
                    <div class="">
                        <input type="" class="form-control" id="absentAddRemark" name="absentAddRemark">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary" name="absentAddSubmit" id="absentAddSubmit">Save</button>
                <button type="submit" class="btn btn-primary" name="absentAddUpdate" id="absentAddUpdate">Update</button>
            </div>
        </form>
      </div>
    </div>
</div>