<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
		<!-- function -->
		<?php 
			require 'function-voice.php';
		 ?>
		
		<div class="container-fluid pt-3">
			<div class="card">
				<div class="card-header bg-primary">
					Detail of Voice Assesment Result
					<div class="card-tools">
						<a href="<?= base_url('voice/survey'); ?>" class="text-white mr-3"><i class="fas fa-plus-circle"></i> Add Survey</a>						
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-sm mb-5">
							<?php 
								if(!$this->input->post()){
									$startPeriod = date("Y-m-01", strtotime('-1 months'));
									$endPeriod = date("Y-m-d");
								} else {
									$startPeriod = date("Y-m-01", strtotime($this->input->post('voiceSummaryDateStart')));
            						$endPeriod = $this->input->post('voiceSummaryDateEnd');
								}
							?>
							<form action="" class="form-row" method="post" style="width: 680px;" id="formVoiceDetailSelectPeriod">
								<label for="voiceSummaryDateStart" class="col-sm-1">Period</label>
								<div class="col-sm-3">
									<input type="date" id="voiceSummaryDateStart" name="voiceSummaryDateStart" class="form-control" value="<?= $startPeriod ?>">
								</div>
								<div class="col-sm-3">
									<input type="date" id="voiceSummaryDateEnd" name="voiceSummaryDateEnd" class="form-control" value="<?= $endPeriod ?>">
								</div>
								<div class="col-sm-2">
									<button type="submit" class="btn btn-outline-primary" id="buttonSelectVoiceSummary" name="buttonSelectabsenceSummary">Go</button>
									<button type="button" class="btn btn-outline-success" id="buttonExcelVoiceDetail" name="buttonExcelVoiceDetail"><i class="fas fa-file-excel"></i></button>
								</div>								
							</form>
						</div>
					</div>					
					<div class="row">
						<div class="col">
							<table id="tableVoiceDetail" class="table table-sm table-responsive table-hover table-bordered">
								<thead>
									<tr>
										<th rowspan="2" class="align-middle text-center">#</th>
										<th rowspan="2" class="align-middle text-center">Period</th>
										<th rowspan="2" class="align-middle">Agent</th>
										<th rowspan="2" class="align-middle">Cust. phone</th>
										<th rowspan="2" class="align-middle text-center">Call date</th>
										<th colspan="2" class="align-middle text-center">Greeting</th>
										<th colspan="2" class="text-center">Smile Voice</th>
										<th colspan="2" class="text-center">Accuracy</th>
										<th colspan="2" class="text-center">Closing</th>
										<th rowspan="2" class="align-middle text-center">Score</th>
										<th rowspan="2" class="align-middle">Remark</th>
										<th rowspan="2" class="align-middle">...</th>
									</tr>
									<tr class="text-center text-sm">
										<td class="text-center">Result</td>
										<td>Reason</td>
										<td class="text-center">Result</td>
										<td>Reason</td>
										<td class="text-center">Accurate?</td>
										<td>Reason</td>
										<td class="text-center">OK?</td>
										<td>Reason</td>
									</tr>
								</thead>
								<tbody>
									<?php $i = 1; ?>
									<?php foreach ($voiceAssesmentByPeriod as $data) : ?>
										<tr class="py-2">
											<td class="text-center align-middle"><?= $i++; ?></td>
											<td class="text-center align-middle"><?= date("M-y", strtotime($data['period'])); ?></td>
											<td class="align-middle"><?= $data['agent']; ?></td>
											<td class="align-middle"><?= $data['customer_phone']; ?><?= source2icon($data['survey_source']) ?></td>
											<td class="text-center text-sm align-middle">
												<?php if($data['call_date'] == '0000-00-00'){
													echo "-";
												} else{
													echo date("d-M", strtotime($data['call_date']));
												}
												?>
											</td>
											<td class="text-center align-middle"><?= greeting2score($data['greeting']); ?></td>
											<td class=""><?= $data['greeting_remark']; ?></td>
											<td class="text-center align-middle"><?= smile2score($data['smile_voice']); ?></td>
											<td class=""><?= $data['smile_voice_remark']; ?></td>
											<td class="text-center align-middle"><?= accuracy2score($data['accuracy']); ?></td>
											<td class=""><?= $data['accuracy_remark']; ?></td>
											<td class="text-center align-middle"><?= closing2score($data['closing']); ?></td>
											<td class=""><?= $data['closing_remark']; ?></td>
											<td class="text-center align-middle"><span class="text-bold"><?= $data['greeting'] + $data['smile_voice'] + $data['accuracy'] + $data['closing'] ?></span></td>
											<td class="align-middle">
												<?= surveyorTag($data['survey_by'], $data['survey_at']) ?>
												<?= $data['voice_remark']; ?>
												<?= link2text($data['voice_remark'], $data['voice_link']) ?>
											</td>
											<td class="text-left align-middle">
												<div class="input-group-prepend mx-auto">
													<button type="button" class="btn btn-outline-light btn-sm dropdown-toggle" data-toggle="dropdown">
														<i class="fas fa-bars text-dark"></i>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item buttonVoiceDetailEdit" href="#" data-id="<?= $data['id']; ?>" data-agent="<?= $data['agent']; ?>" data-phone="<?= $data['customer_phone']; ?>"><i class="fas fa-pen"></i> &nbspEdit data</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item buttonVoiceDetailDelete" href="#" data-id="<?= $data['id']; ?>"><i class="fas fa-times text-danger"></i> &nbspDelete</a>
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
	<!-- /.content -->
</div>
<!-- /.content-wrapper