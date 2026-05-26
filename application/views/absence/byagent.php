<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid pt-2 px-1">
        	<?php
        	?>

        	<div class="card">
        		<div class="card-header bg-primary">
        			Detail absent by agent
        		</div>
        		<div class="card-body">
		            <div class="row">
		            	<div class="col-sm mb-5">
		            		<form action="" class="form-row" method="post" style="width: 680px;">
		            			<label for="absenceByAgentSelectAgent" class="col-sm-1">Agent</label>
		                        <div class="col-sm-2">
		                        	<select id="absenceByAgentSelectAgent" name="absenceByAgentSelectAgent" class="custom-select">
		                        		<?php if($this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 6 || $this->session->userdata('role_access') == 9 ): ?>		                        			
		                        			<option value="<?= $agent; ?>" selected><?= $agent; ?></option>
		                        			<?php foreach($allAgent as $agents): ?>
		                        				<option value="<?= $agents['user_id']; ?>"><?= $agents['user_id']; ?></option>
		                        			<?php endforeach; ?>
		                        		<?php else: ?>
		                        			<option value="<?= $this->session->userdata('user_id'); ?>"><?= $this->session->userdata('user_id'); ?></option>
		                        		<?php endif; ?>
		                        	</select>
		                        </div>
		                        <div class="col-sm-1"></div>
		                        <label for="absenceByAgentDateStart" class="col-sm-1">Period</label>
		                        <div class="col-sm-3">
		                        	<input type="date" id="absenceByAgentDateStart" name="absenceByAgentDateStart" class="form-control" value="<?= $startPeriod?>">
		                        </div>
		                        <div class="col-sm-3">
			                        <input type="date" id="absenceByAgentDateEnd" name="absenceByAgentDateEnd" class="form-control" value="<?= $endPeriod?>">
			                    </div>
			                    <div class="col-sm-1">
		                        	<button type="submit" class="btn btn-outline-primary" id="buttonSelectabsenceByAgent" name="buttonSelectabsenceByAgent">Go</button>
		                        </div>
		                    </form>                    
		            	</div>
		            </div> 
		            <div class="row mb-5" style="display: none;">
		            	<div class="col">
		            		<div class="col-lg-6">
				            	<div class="card">
					              <div class="card-header border-0">
					                <div class="d-flex justify-content-between">
					                  <h3 class="card-title">Absent by Category (Sick - Unpaid leave)</h3>
					                </div>
					              </div>
					              <div class="card-body">
					                <div class="d-flex">
					                  <p class="d-flex flex-column">
					                    <span class="text-bold text-lg"></span>
					                    <span></span>
					                  </p>
					                  <p class="ml-auto d-flex flex-column text-right">
					                    <span class="text-success">
					                    </span>
					                    <span class="text-muted"></span>
					                  </p>
					                </div>
					                <!-- /.d-flex -->

					                <div class="position-relative mb-4">
					                  <canvas id="sales-chart" height="160"></canvas>
					                </div>

					                <div class="d-flex flex-row justify-content-end">
					                  <span class="mr-2">
					                    <i class="fas fa-square text-danger"></i> Sick
					                  </span>

					                  <span>
					                    <i class="fas fa-square text-gray"></i> Unpaid leave
					                  </span>
					                </div>
					              </div>
					            </div>
            				</div>
		            	</div>
		            </div>
		            
		            <div class="row mb-3">
		            	<div class="col">
			            	<table class="table table-sm" id="tableAbsenceByAgent">
			            		<thead>
			            			<tr class="text-center">
			            				<th class="align-middle">No</th>
			            				<th class="align-middle">Month</th>
			            				<th class="align-middle">Work<br>day</th>
			            				<th class="align-middle">Sick</th>
			            				<th class="align-middle">Unpaid<br>leave</th>
			            				<th class="text-muted align-middle">3 hour<br>permit</th>
			            				<th class="align-middle">Total<br><small>(Sick & Unpaid)</small></th>
			            				<th class="align-middle">%</th>
			            				<th class="align-middle">Covid</th>
			            				<th class="align-middle">Total<br><small>(inc. Covid)</small></th>
			            				<th class="align-middle">%<br><small>(inc. Covid)</small></th>
			            			</tr>
			            		</thead>
			            		<tbody>
			            			<?php $i = 1; ?>
			            			<?php foreach($absentByAgentByPeriod as $data) : ?>
			            				<tr>
			            					<td class="text-center"><?= $i++; ?></td>
			            					<td class="text-center"><?= date("M Y", strtotime($data['working_month'])); ?></td>
			            					<td class="text-center"><?= $data['working_day']; ?></td>
			            					<td class="text-center"><?= $data['permit_sick']; ?></td>
			            					<td class="text-center"><?= $data['permit_unpaid_leave']; ?></td>
			            					<td class="text-center text-muted"><?= $data['permit_3hour']; ?></td>
			            					<td class="text-center"><?= $data['permit_total']; ?></td>
			            					<td class="text-center"><?= number_format(($data['working_day'] - $data['permit_total']) / $data['working_day'],3) * 100; ?>%</td>
			            					<td class="text-center"><?= $data['permit_covid']; ?></td>
			            					<td class="text-center"><?= $data['permit_total'] + $data['permit_covid']; ?></td>
			            					<td class="text-center"><?= number_format(($data['working_day'] - ($data['permit_total'] + $data['permit_covid'])) / $data['working_day'],3) * 100; ?>%</td>
			            				</tr>
			            			<?php endforeach; ?>	
		            				<tr class="text-bold border-bottom">
		            					<td colspan="2" class="text-center">Total</td>
		            					<td class="text-center"><?= $absentByAgentTotal['working_days'] ?></td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_sick'] ?></td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_unpaid_leave'] ?></td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_3hour'] ?></td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_total'] ?></td>
		            					<td class="text-center">
		            						<?= number_format(($absentByAgentTotal['working_days'] - $absentByAgentTotal['permit_total']) / $absentByAgentTotal['working_days'],3) * 100; ?>%
		            					</td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_covid'] ?></td>
		            					<td class="text-center"><?= $absentByAgentTotal['permit_total'] + $absentByAgentTotal['permit_covid']; ?></td>
		            					<td class="text-center">
		            						<?= number_format(($absentByAgentTotal['working_days'] - ($absentByAgentTotal['permit_total'] + $absentByAgentTotal['permit_covid'])) / $absentByAgentTotal['working_days'],3) * 100; ?>%
		            					</td>
		            				</tr>
			            		</tbody>
			            	</table>
		            	</div>
		            </div>

		            <div class="row mt-5">
		            	<div class="col">
		            		<p class="h6 text-primary">Detail absent by agent</p>
		            		<table class="table table-sm" id="tableAbsenceByAgentDetail">
		            			<thead>
		            				<tr>
		            					<th>#</th>
		            					<th>Tanggal</th>
		            					<th>Absent</th>
		            					<th>Reason</th>
		            					<th>Remark</th>
		            				</tr>
		            			</thead>
		            			<tbody>
		            				<?php $n = 1; ?>
		            				<?php foreach($absentByAgentByPeriodDetail as $row) : ?>
		            					<tr>
		            						<td><?= $n++; ?></td>
		            						<td><?= date("d-M-y", strtotime($row['absent_date'])); ?></td>
		            						<td><?= $row['permit_type'] ?></td>
		            						<td><?= $row['permit_reason'] ?></td>
		            						<td><?= $row['permit_remark'] ?></td>
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
