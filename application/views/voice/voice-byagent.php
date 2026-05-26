<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php require 'function-voice.php'; ?>
        <div class="container-fluid pt-3">        	
        	<div class="card">
        		<div class="card-header bg-primary">
        			Detail Voice Assesment by agent
        		</div>
        		<div class="card-body">        
		            <div class="row">
		            	<div class="col-sm">
		            		<?php 
								if(!$this->input->post()){
									$startPeriod = date("Y-m-01", strtotime('-1 months'));
									$endPeriod = date("Y-m-d");
									$agent = $this->session->userdata('user_id');
								} else {
									$startPeriod = date("Y-m-01", strtotime($this->input->post('voiceByAgentDateStart')));
         						$endPeriod = $this->input->post('voiceByAgentDateEnd');
            					$agent = $this->input->post('voiceByAgentSelectAgent');
								}
							?>
		            		<form action="" class="form-row" method="post" style="width: 680px;">
		            			<label for="voiceByAgentSelectAgent" class="col-sm-1">Agent</label>
		                        <div class="col-sm-2">
		                        	<select id="voiceByAgentSelectAgent" name="voiceByAgentSelectAgent" class="custom-select">
		                        		<?php if($this->session->userdata('role_access') == 9 || $this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 ): ?>
		                        			<option value="<?= $agent; ?>"><?= $agent; ?></option>
		                        			<?php foreach ($allAgent as $ag): ?>
		                        				<option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
		                        			<?php endforeach; ?>
		                        		<?php else: ?>
		                        			<option><?= $this->session->userdata('user_id'); ?></option>
		                        		<?php endif; ?>
		                        	</select>
		                        </div>
		                        <div class="col-sm-1"></div>
		                        <label for="voiceByAgentDateStart" class="col-sm-1">Period</label>
		                        <div class="col-sm-3">
		                        	<input type="date" id="voiceByAgentDateStart" name="voiceByAgentDateStart" class="form-control" value="<?= $startPeriod ?>">
		                        </div>
		                        <div class="col-sm-3">
			                        <input type="date" id="voiceByAgentDateEnd" name="voiceByAgentDateEnd" class="form-control" value="<?= $endPeriod ?>">
			                    </div>
			                    <div class="col-sm-1">
		                        	<button type="submit" class="btn btn-outline-primary" id="buttonSubmitVoiceByAgent" name="buttonSubmitVoiceByAgent">Go</button>
		                        </div>
		                    </form>                    
		            	</div>
		            </div>
		            
		            <!-- summary by agent  -->
		            <div class="row mt-5">
		            	<div class="col-10">
		            		<p class="h5 text-indigo mb-3"><i class="far fa-file-alt"></i> Summary by period</p>
		            		<table class="table table-sm table-hover table-bordered">
		            			<thead class="bg-light">
                              <tr>
                                 <th class="text-center align-middle">#</th>
                                 <th class="text-center align-middle">Month</th>
                                 <th class="text-center align-middle"><i class="fas fa-volume-down"></i></th>
                                 <th class="text-center"><i class="fas fa-phone-alt"></i><br>Greeting</th>
                                 <th class="text-center" style="width: 160px;"><i class="far fa-smile"></i><br>Smile Voice</th>
                                 <th class="text-center"><i class="fas fa-spell-check"></i><br>Accuracy</th>
                                 <th class="text-center"><i class="fas fa-window-close"></i><br>Closing</th>
                                 <th class="text-center align-middle">Total/Result</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i = 1; ?>
                              <?php foreach($voiceSummaryByAgent as $row): ?>
                                 <tr>
                                    <td class="text-center align-middle"><?= $i++ ?></td>
                                    <td class="align-middle"><?= date("M Y", strtotime($row['month'])) ?></td>
                                    <td class="text-center align-middle"><?= $row['qty'] ?></td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['greeting'] / 3 * 100), 1), $row['greeting_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['smile'] / 10 * 100), 1), $row['smile_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['accuracy'] / 10 * 100), 1), $row['accuracy_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['closing'] / 2 * 100), 1), $row['closing_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['total'] / 25 * 100), 1), $row['qty']) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
		            			
		            		</table>
		            	</div>
		            </div>
		            
		            <!-- finding lists -->
					<div class="row mt-4">
					  <div class="col-11">
					     <p class="h5 text-indigo mb-3"><i class="far fa-thumbs-down"></i> Bad Findings List</p>
					     <?php if(count($voiceUnproperListByAgent) == 0 ) : ?>
					        <p class="lead font-italic"> <i class="far fa-smile-beam  ml-3"></i>  there were no bad findings during those period</p>
					     <?php else : ?>
					        <table class="table table-sm table-bordered table-hover">
					           <thead class="bg-light">
					              <tr>
					                 <th class="text-center">#</th>
					                 <th class="text-center">Month</th>
					                 <th class="">Cust. phone</th>
					                 <th class="text-center">Call date</th>
					                 <th class="text-center">Greeting</th>
					                 <th class="text-center">Smile Voice</th>
					                 <th class="text-center">Accuracy</th>
					                 <th class="text-center">Closing</th>
					                 <th class="text-center">Total/Result</th>
					              </tr>
					           </thead>
					           <tbody>
					              <?php $j = 1; ?>
					              <?php foreach ($voiceUnproperListByAgent as $row): ?>
					                 <tr>
					                    <td class="text-center align-middle"><?= $j++ ?></td>
					                    <td class="align-middle text-center"><?= date("M Y", strtotime($row['period'])) ?></td>
					                    <td class="text-center align-middle"><?= $row['customer_phone'] ?></td>
					                    <td class="text-center align-middle"><?= date("d-M-Y", strtotime($row['call_date'])) ?></td>
					                    <td class="">
					                       <?= value2barstotal(number_format(($row['greeting'] / 3 * 100), 0), 10) ?>
					                    </td>
					                    <td class="">
					                       <?= value2barstotal(number_format(($row['smile_voice'] / 10 * 100), 0), 10) ?>
					                    </td>
					                    <td class="">
					                       <?= value2barstotal(number_format(($row['accuracy'] / 10 * 100), 0), 10) ?>
					                    </td>
					                    <td class="">
					                       <?= value2barstotal(number_format(($row['closing'] / 2 * 100), 0), 10) ?>
					                    </td>
					                    <td class="">
					                       <?= value2barstotal(number_format((($row['greeting'] + $row['smile_voice'] + $row['accuracy'] + $row['closing']) / 25 * 100), 0), 10) ?>
					                    </td>
					                 </tr>
					              <?php endforeach; ?>
					           </tbody>
					        </table>
					     <?php endif; ?>
					  </div>
					</div>

		            <!-- detail voice assessment -->
		            <div class="row mt-4">
		            	<div class="col">
		            		<p class="h5 text-indigo"><i class="fas fa-list"></i> Detail Voice Assessment</p>
		            		<table id="tableVoiceByAgent" class="table table-sm table-responsive table-bordered">
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
									<?php foreach ($voiceDetailByAgent as $data) : ?>
										<tr class="py-2">
											<td class="text-center align-middle"><?= $i++; ?></td>
											<td class="text-center align-middle"><?= date("M-y", strtotime($data['period'])); ?></td>
											<td class="align-middle"><?= $data['agent']; ?></td>
											<td class="text-center align-middle"><?= $data['customer_phone']; ?><?= source2icon($data['survey_source']) ?></td>
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
<!-- /.content-wrapper -->