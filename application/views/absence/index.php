<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
            if (!$this->input->post('absenceSummaryDateStart') || !$this->input->post('absenceSummaryDateEnd')) {
                $startPeriod = date("Y-m-01", strtotime("-6 months"));
                $endPeriod = date("Y-m-d");
            } else {
                $startPeriod = $this->input->post('absenceSummaryDateStart');
                $endPeriod = $this->input->post('absenceSummaryDateEnd');
            }
        ?>
        <div class="container-fluid py-2 px-1">
            <!-- /.row --> 
            <div class="card">
            	<div class="card-header bg-primary">
            		<span class="card-title">Summary of Working Attendance (Absence) : <span class="font-weight-bold"><?= date("F Y", strtotime($startPeriod)) ?> - <?= date("F Y", strtotime($endPeriod)) ?></span></span>
            	</div>
            	<div class="card-body">
            		<div class="row">
		            	<div class="col-sm mb-3">
		            		<form action="" class="form-row" method="post" style="width: 480px;">
		                        <label for="absenceSummaryDateStart" class="col-sm-2">Period</label>
		                        <div class="col-sm-4">
		                        	<input type="date" id="absenceSummaryDateStart" name="absenceSummaryDateStart" class="form-control" value="<?= $startPeriod ?>">
		                        </div>
		                        <div class="col-sm-4">
			                        <input type="date" id="absenceSummaryDateEnd" name="absenceSummaryDateEnd" class="form-control" value="<?= $endPeriod ?>">
			                    </div>
			                    <div class="col-sm-2">
		                        	<button type="submit" class="btn btn-outline-primary" id="buttonSelectabsenceSummary" name="buttonSelectabsenceSummary">Go</button>
		                        </div>
		                    </form>                    
		            	</div>
		            </div>
		            <div class="row">
		            	<div class="col">
		            		<table class="table table-sm" style="max-width: 980px; min-width: 960px;">
		            			<thead>
		            				<tr>
		            					<th class="text-center">#</th>
		            					<th>Agent Name</th>
		            					<th>NPK</th>
		            					<th>Fullname</th>
		            					<th class="text-center">Working days</th>
		            					<th class="text-center">Sick</th>
		            					<th class="text-center">Unpaid leave</th>
		            					<th class="text-center">3-hour permit</th>
		            					<th class="text-center">Ttl absent</th>
		            					<th class="text-center">% Attendance</th>
		            				</tr>
		            			</thead>
		            			<tbody>
		            				<?php $i = 1; ?>
		            				<?php foreach($absenceSummaryByPeriod as $row) : ?>
		            					<tr>
		            						<td  class="text-center"><?= $i++ ?></td>
		            						<td><?= $row['agent'] ?></td>
		            						<td><?= $row['npk'] ?></td>
		            						<td><?= $row['fullname'] ?></td>
		            						<td class="text-center"><?= $working_day ?></td>
		            						<td class="text-center"><?= $row['permit_sick'] ?></td>
		            						<td class="text-center"><?= $row['permit_unpaid_leave'] ?></td>
		            						<td class="text-center"><?= $row['permit_3hour'] ?></td>
		            						<td class="text-center"><?= $row['permit_total'] ?></td>
		            						<td class="text-center">
		            							<?= number_format(($working_day - ($row['permit_sick'] + $row['permit_unpaid_leave'])) / $working_day, 3) * 100 ?>%
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