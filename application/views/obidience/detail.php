<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="container-fluid px-0 pt-3">
		<!-- /.row -->
			<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
			<?php 
				if(!$this->input->post()) {
					$startPeriod = date("Y-m-01", strtotime("-1 months"));
					$endPeriod = date("Y-m-d", strtotime("+2 days"));
				} else {
					$startPeriod = $this->input->post('obidienceDetailDateStart');
					$endPeriod = $this->input->post('obidienceDetailDateEnd');
				}

				function markSwap($rem) {
					if (strtolower($rem) == 'swap') {
						return '<span class="badge badge-pill badge-warning">Tukar</span>';
					} else {
						return '<span class="badge badge-pill badge-danger">Diganti</span>';
					}
				}
			?>

			<div class="col">
				<div class="card">
					<div class="card-header bg-primary">
						Overtime Incompliance by Agent
						<!-- <div class="card-tools">
							<a href="" class="text-white buttonAdd float-right" data-toggle="modal" data-target="#addIncomplianceModal" id="buttonAdd"><span class="fas fa-plus-circle"></span> Add data</a>
						</div> -->
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<div class="row">
			            	<div class="col-sm mb-3">
			            		<form action="" class="form-row" method="post" style="width: 540px;">
			                        <label for="obidienceDetailDateStart" class="col-sm-2">Period</label>
			                        <div class="col-sm-3">
			                        	<input type="date" id="obidienceDetailDateStart" name="obidienceDetailDateStart" class="form-control" value="<?= $startPeriod ?>">
			                        </div>
			                        <div class="col-sm-3">
				                        <input type="date" id="obidienceDetailDateEnd" name="obidienceDetailDateEnd" class="form-control" value="<?= $endPeriod ?>">
				                    </div>
				                    <div class="col-sm-3">
			                        	<button type="submit" class="btn btn-outline-primary" id="obidienceGetDetail" name="obidienceGetDetail">Go</button>
			                            <button type="button" class="btn btn-outline-success ml-0" id="obidienceDetailToExcel" name="obidienceDetailToExcel"><i class="fas fa-file-excel"></i></button>
			                        </div>
			                    </form>                    
			            	</div>
			            </div>

			            <div class="row mt-3">
			            	<div class="col">
				            	<table class="table table-hover table-sm" id="tableObidienceDetail">
				            		<thead>
				            			<tr>
				            				<th class="align-middle">#</th>
				            				<th class="align-middle">Date</th>
				            				<th class="align-middle">Schedule</th>
				            				<th class="align-middle ">Status</th>
				            				<th class="align-middle">Actual OT</th>
				            				<th class="align-middle">Reason</th>
				            				<th class="align-middle">Remark</th>
				            			</tr>
				            		</thead>
				            		<tbody>
				            			<?php $i = 1; ?>
				            			<?php foreach ($obidienceData as $data): ?>
				            				<tr>
				            					<td><?= $i++; ?></td>
				            					<td><?= date("d-M-y", strtotime($data['date'])); ?></td>
				            					<td><?= $data['agent_scheduled']; ?></td>
				            					<td class=""><?= markSwap($data['replace_mark']); ?></td>
				            					<td><?= $data['replaced_by']; ?></td>
				            					<td><?= $data['reason']; ?></td>
				            					<td><?= $data['remark']; ?></td>
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
	</section>
</div>

<div class="modal fade" id="addIncomplianceModal" tabindex="-1" role="dialog" aria-labelledby="addIncomplianceLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="addIncomplianceModalLabel">Add Overtime Incompliance</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="">
					<input type="hidden" class="form-control" id="addIncomplianceId" name="addIncomplianceId" readonly>
					<div class="form-group">
	                    <label for="addIncomplianceDate" class="form-label">Date</label>
	                    <div class="">
	                        <input type="date" class="form-control" id="addIncomplianceDate" name="addIncomplianceDate">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="addIncomplianceAgentScheduled" class="form-label">Agent scheduled (jadwal)</label>
	                    <div class="">
	                    	<select class="form-control custom-select" id="addIncomplianceAgentScheduled" name="addIncomplianceAgentScheduled">
	                    		<?php foreach($allAgents as $agent): ?>
	                    			<option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
	                    		<?php endforeach; ?>
	                    	</select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="addIncomplianceReplacedBy" class="form-label">Replaced by (diganti oleh)</label>
	                    <div class="">
	                    	<select class="form-control custom-select" id="addIncomplianceReplacedBy" name="addIncomplianceReplacedBy">
	                    		<?php foreach($allAgents as $agent): ?>
	                    			<option value="<?= $agent['user_id']; ?>"><?= $agent['user_id']; ?></option>
	                    		<?php endforeach; ?>
	                    	</select>
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="addIncomplianceReason" class="form-label">Reason (alasan)</label>
	                    <div class="">
	                        <input type="" class="form-control" id="addIncomplianceReason" name="addIncomplianceReason">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label for="addIncomplianceRemark" class="form-label">Remark (keterangan)</label>
	                    <div class="">
	                        <input type="" class="form-control" id="addIncomplianceRemark" name="addIncomplianceRemark">
	                    </div>
	                </div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" name="addIncomplianceSubmit" id="addIncomplianceSubmit">Save</button>
						<button type="submit" class="btn btn-primary" name="addIncomplianceUpdate" id="addIncomplianceUpdate">Update</button>
						<button type="submit" class="btn btn-danger" name="addIncomplianceDelete" id="addIncomplianceDelete">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
