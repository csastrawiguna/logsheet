<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid px-1">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- Header baru -->
            <?php 
                if (!$this->input->post('selectAssessmentByAgentStartPeriod') || !$this->input->post('selectAssessmentByAgentEndPeriod')) {
                    if( (int)date("m") > 4 && (int)date("m") < 10 ) {
                        $startPeriod = date("Y-04-01");
                        $endPeriod = date("Y-09-01");
                    } else if ((int)date("m") < 4) {
                        $startPeriod = date("Y-10-01", strtotime("-1 year"));
                        $endPeriod = date("Y-03-01");
                    } else {
                         $startPeriod = date("Y-10-01");
                        $endPeriod = date("Y-03-01", strtotime("+1 year"));
                    }      
                    $agent = $this->session->userdata('user_id');
                    $jobcode = $this->session->userdata('jobcode');
                } else {
                    $startPeriod = $this->input->post('selectAssessmentByAgentStartPeriod');
                    $endPeriod = $this->input->post('selectAssessmentByAgentEndPeriod');
                    $agent = $this->input->post('selectAssessmentByAgentSelectAgent');
                    $jobcode = $this->db->get_where('user', ['user_id' => $agent])->row_array()['jobcode'];
                }
            ?>

            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Assesment of Agent Performance</h3>                    
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 640px;">
                            <label for="selectAssessmentByAgentSelectAgent">Agent</label>
                            <select type="date" class="custom-select mr-5" name="selectAssessmentByAgentSelectAgent" id="selectAssessmentByAgentSelectAgent" style="width: 140px;" value="$agent">
                                <?php if($this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 6 || $this->session->userdata('role_access') == 9 ): ?>                                         
                                        <option value="<?= $agent; ?>" selected><?= $agent; ?></option>
                                    <?php foreach($allAgent as $agents): ?>
                                        <option value="<?= $agents['user_id']; ?>"><?= $agents['user_id']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="<?= $this->session->userdata('user_id'); ?>"><?= $this->session->userdata('user_id'); ?></option>
                                <?php endif; ?>
                            </select>
                            <label for="selectAssessmentByAgentStartPeriod">Period</label>
                            <input type="date" class="custom-select" name="selectAssessmentByAgentStartPeriod" id="selectAssessmentByAgentStartPeriod" style="width: 140px;" value="<?= $startPeriod; ?>">
                            <label for="selectAssessmentByAgentEndPeriod">to</label>
                            <input type="date" class="custom-select" name="selectAssessmentByAgentEndPeriod" id="selectAssessmentByAgentEndPeriod" style="width: 140px;" value="<?= $endPeriod ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectAssessmentByAgent" name="buttonSelectAssessmentByAgent">Go</button>
                        </form>
                    </div>
                    <!-- Rengtang Penilaian KPI -->
                    <div class="row mt-3">
                        <div class="col-4">
                            <h6 class="h6 text-indigo">KPI grade</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Achievement vs target (%)</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>&GreaterEqual;66.6%</td>
                                        <td>S</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>63.6% ~ 66.59%</td>
                                        <td>A</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>60.0% ~ 63.59%</td>
                                        <td>B</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>48.0% ~ 59.99%</td>
                                        <td>C</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td><48%</td>
                                        <td>D</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Target KPI -->
                    <div class="row mt-3">
                        <div class="col-6">
                            <h6 for="tableAgentTargent" class="text-indigo h6">Target</h6>
                            <table class="table table-sm" id="tableAgentTargent" style="min-width: 480px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Activity</th>
                                        <th>Weight</th>
                                        <th>Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($target as $target): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $target['description'] ?></td>
                                            <td><?= $target['weight'] ?>%</td>
                                            <td><?= round($target['target'],0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="text-bold">
                                        <td colspan="2" class="text-center">Total</td>
                                        <td><?= $totalWeight['totalWeight'] ?>%</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Result & Compare Result vs target KPI -->
            		<?php
            			switch ($jobcode) {                            
                            case 'cs-ccc-cc10': // Customer Assistant (level 1)
                            case 'cs-ccc-cc11': // Customer Assistant (level 1 <6 months employement)
                            case 'cs-ccc-cc12': // Customer Assistant (level 1 6-12 months employement)
                    ?> 
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($result as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td><?= $data['productivity']; ?></td>
                                                    <td><?= round($data['csindex'] * 100, 1); ?>%</td>
                                                    <td><?= round($data['absence'] * 100, 1); ?>%</td>
                                                    <td><?= $data['elearning']; ?></td>                                                    
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultProductivity"><?= $data['productivity']; ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence"><?= $data['absence']; ?>%</td>
                                                    <td class="kpiResultElearning"><?= $data['elearning']; ?>%</td>                                                    
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiProductivityCA text-center"></td>
                                                <td class="averageKpiCsindexCA text-center"></td>
                                                <td class="averageKpiAbsenceCA text-center"></td>
                                                <td class="averageKpiElearningCA text-center"></td>                                                
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiProductivityCA text-center"><?= $targetDetail['productivity'] ?></td>
                                                <td class="weightKpiCsindexCA text-center"><?= $targetDetail['csindex'] ?></td>
                                                <td class="weightKpiAbsenceCA text-center"><?= $targetDetail['absence'] ?></td>
                                                <td class="weightKpiElearningCA text-center"><?= $targetDetail['elearning'] ?></td>                                                
                                            </tr>
                                            <tr class="text-bold bg-light">
                                                <td colspan="2" class="text-center">Achievement<br>(estimation)</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementCA align-middle"></td>
                                            </tr>
                                        </tbody>
                                    </table>                                                                             
                    <?php 
                        break;
        				case 'cs-ccc-cc20': // Product Assistant
            		?>	
                            <!-- Result -->
                            <div class="row mt-3">
                            	<div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                    				<table class="table table-sm" id="tableKpiItemsResult" >
		                    			<thead>
		                    				<tr class="text-center">
		                    					<th>#</th>
		                    					<th>Period</th>
		                    					<th>Productivity</th>
		                    					<th>CS index ratio</th>
		                    					<th>Attendance</th>
                                                <th>Q-A Draft</th>
                                                <th>Knowledge Sharing</th>
		                    				</tr>
		                    			</thead>
		                    			<tbody>
		                    				<?php $i = 1; ?>
		                    				<?php foreach($result as $data): ?>
		                    					<tr class="text-center">
		                    						<td><?= $i++; ?></td>
		                    						<td><?= date("M Y", strtotime($data['period'])); ?></td>
		                    						<td><?= $data['productivity']; ?></td>
		                    						<td><?= round($data['csindex'] * 100, 1); ?>%</td>
		                    						<td><?= round($data['absence'] * 100, 1); ?>%</td>
                                                    <td><?= $data['skape_draft']; ?></td>
                                                    <td><?= $data['knowledge_sharing']; ?></td>
		                    					</tr>
		                    				<?php endforeach; ?>
		                    			</tbody>
		                    		</table>
                                </div>
                            </div>
                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Q-A Draft</th>
                                                <th>Knowledge Sharing</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultProductivity"><?= $data['productivity']; ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence"><?= $data['absence']; ?>%</td>
                                                    <td class="kpiResultSkapeDraft"><?= $data['skape_draft']; ?>%</td>
                                                    <td class="kpiResultKnowledgeSharing"><?= $data['knowledge_sharing']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiProductivityPA text-center"><?= $targetDetail['productivity'] ?></td>
                                                <td class="averageKpiCsindexPA text-center"><?= $targetDetail['csindex'] ?></td>
                                                <td class="averageKpiAbsencePA text-center"><?= $targetDetail['absence'] ?></td>
                                                <td class="averageKpiSkapeDraftPA text-center"><?= $targetDetail['skape_draft'] ?></td>
                                                <td class="averageKpiKnowledgeSharingPA text-center"><?= $targetDetail['knowledge_sharing'] ?></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiProductivityPA text-center"><?= $targetDetail['productivity'] ?>%</td>
                                                <td class="weightKpiCsindexPA text-center"><?= $targetDetail['csindex'] ?>%</td>
                                                <td class="weightKpiAbsencePA text-center"><?= $targetDetail['absence'] ?>%</td>
                                                <td class="weightKpiSkapeDraftPA text-center"><?= $targetDetail['skape_draft'] ?>%</td>
                                                <td class="weightKpiKnowledgeSharingPA text-center"><?= $targetDetail['knowledge_sharing'] ?>%</td>
                                            </tr>
                                            <tr class="text-bold bg-light bordered">
                                                <td colspan="2" class="text-center">Achievement<br>(estimation):</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementPA align-middle"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
            		<?php 
        					break;
                            case 'cs-ccc-cc30':
                    ?> 
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>
                                                <th>Part Code</th>
                                                <th>Callback part</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($result as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td><?= $data['productivity']; ?></td>
                                                    <td><?= round($data['csindex'] * 100, 1); ?>%</td>
                                                    <td><?= round($data['absence'] *100, 1); ?>%</td>
                                                    <td><?= $data['elearning']; ?></td>
                                                    <td><?= $data['part_code']; ?></td>
                                                    <td><?= $data['part_callback']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>
                                                <th>Part Code</th>
                                                <th>Callback part</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultProductivity"><?= $data['productivity']; ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence"><?= $data['absence']; ?>%</td>
                                                    <td class="kpiResultElearning"><?= $data['elearning']; ?>%</td>
                                                    <td class="kpiResultPartCode"><?= $data['part_code']; ?>%</td>
                                                    <td class="kpiResultPartCallback"><?= $data['part_callback']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiProductivityPart text-center"></td>
                                                <td class="averageKpiCsindexPart text-center"></td>
                                                <td class="averageKpiAbsencePart text-center"></td>
                                                <td class="averageKpiElearningPart text-center"></td>
                                                <td class="averageKpiPartCodePart text-center"></td>
                                                <td class="averageKpiPartCallbackPart text-center"></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiProductivityPart text-center"><?= $targetDetail['productivity'] ?></td>
                                                <td class="weightKpiCsindexPart text-center"><?= $targetDetail['csindex'] ?></td>
                                                <td class="weightKpiAbsencePart text-center"><?= $targetDetail['absence'] ?></td>
                                                <td class="weightKpiElearningPart text-center"><?= $targetDetail['elearning'] ?></td>
                                                <td class="weightKpiPartCodePart text-center"><?= $targetDetail['part_code'] ?></td>
                                                <td class="weightKpiPartCallbackPart text-center"><?= $targetDetail['part_callback'] ?></td>
                                            </tr>
                                            <tr class="text-bold bg-light">
                                                <td colspan="2" class="text-center">Achievement<br>(estimation)</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementPart align-middle"></td>
                                            </tr>
                                        </tbody>
                                    </table>                                                                             
                    <?php 
                            break;
                            case 'cs-ccc-cc40':
                    ?> 
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Email reply</th>
                                                <th>Promo Inq forward</th>
                                                <!-- <th>Callback part</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($result as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td><?= $data['productivity']; ?></td>
                                                    <td><?= round($data['csindex'] * 100, 1); ?>%</td>
                                                    <td><?= round($data['absence'] * 100, 1); ?>%</td>
                                                    <td><?= round($data['email_reply'], 1); ?>%</td>
                                                    <td><?= round($data['promo_inquiry'], 1); ?>%</td>
                                                    <!-- <td><?= $data['part_callback']; ?>%</td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Email reply</th>
                                                <th>Promo Inq forward</th>
                                                <!-- <th>Callback part</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultProductivity"><?= $data['productivity']; ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence"><?= $data['absence']; ?>%</td>
                                                    <td class="kpiResultEmailReply"><?= $data['email_reply']; ?>%</td>
                                                    <td class="kpiResultPromoInquiry"><?= $data['promo_inquiry']; ?>%</td>
                                                    <!-- <td class="kpiResultPartCallback"><?= $data['part_callback']; ?>%</td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiProductivityPartPlus text-center"></td>
                                                <td class="averageKpiCsindexPartPlus text-center"></td>
                                                <td class="averageKpiAbsencePartPlus text-center"></td>
                                                <td class="averageKpiEmailReplyPartPlus text-center"></td>
                                                <td class="averageKpiPromoInquiryPartPlus text-center"></td>
                                                <!-- <td class="averageKpiPartCallbackPartPlus text-center"></td> -->
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiProductivityPartPlus text-center"><?= $targetDetail['productivity'] ?></td>
                                                <td class="weightKpiCsindexPartPlus text-center"><?= $targetDetail['csindex'] ?></td>
                                                <td class="weightKpiAbsencePartPlus text-center"><?= $targetDetail['absence'] ?></td>
                                                <td class="weightKpiEmailReplyPartPlus text-center"><?= $targetDetail['email_reply'] ?></td>
                                                <td class="weightKpiPromoInquiryPartPlus text-center"><?= $targetDetail['promo_inquiry'] ?></td>
                                                <!-- <td class="weightKpiPartCallbackPart text-center"><?= $targetDetail['part_callback'] ?></td> -->
                                            </tr>
                                            <tr class="text-bold bg-light">
                                                <td colspan="2" class="text-center">Achievement<br>(estimation)</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementPartPlus align-middle"></td>
                                            </tr>
                                        </tbody>
                                    </table>                                                                             
                    <?php 
                            break;
                            case 'cs-ccc-cc50':
                    ?> 
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Forward Complaint</th>
                                                <th>Complaint Report</th>
                                                <th>Complaint Completion</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($result as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td><?= round($data['complaint_forward'], 1); ?>%</td>
                                                    <td><?= $data['complaint_report']; ?></td>
                                                    <td><?= $data['complaint_completion']; ?></td>
                                                    <td><?= round($data['csindex'] * 100, 1); ?>%</td>
                                                    <td><?= round($data['absence'] * 100, 1); ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <pre>
                            
                            </pre>

                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Forward Complaint</th>
                                                <th>Complaint Report</th>
                                                <th>Complaint Completion</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultComplaintForwardComplaint"><?= $data['complaint_forward']; ?>%</td>
                                                    <td class="kpiResultComplaintReportComplaint"><?= $data['complaint_report'] ?>%</td>
                                                    <td class="kpiResultComplaintCompletionComplaint"><?= $data['complaint_completion'] ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence"><?= $data['absence'] ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiComplaintForwardComplaint text-center"></td>
                                                <td class="averageKpiComplaintReportComplaint text-center"></td>
                                                <td class="averageKpiComplaintCompletionComplaint text-center"></td>
                                                <td class="averageKpiCsindexComplaint text-center"></td>
                                                <td class="averageKpiAbsenceComplaint text-center"></td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiComplaintForwardComplaint text-center"><?= $targetDetail['complaint_forward'] ?></td>
                                                <td class="weightKpiComplaintReportComplaint text-center"><?= $targetDetail['complaint_report'] ?></td>
                                                <td class="weightKpiComplaintCompletionComplaint text-center"><?= $targetDetail['complaint_completion'] ?></td>
                                                <td class="weightKpiCsindexComplaint text-center"><?= $targetDetail['csindex'] ?></td>
                                                <td class="weightKpiAbsenceComplaint text-center"><?= $targetDetail['absence'] ?></td>
                                            </tr>
                                            <tr class="text-bold bg-light">
                                                <td colspan="2" class="text-center">Achievement<br>(estimation)</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementComplaint"></td>
                                            </tr>
                                        </tbody>
                                    </table>                                                                      
                    <?php 
                            break;
        				    case 'cs-ccc-cc10':
    				?>
    							<div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">Achievement</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($result as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td><?= $data['productivity']; ?></td>
                                                    <td><?= round($data['csindex'] * 100, 1); ?>%</td>
                                                    <td>0</td>
                                                    <td><?= $data['elearning']; ?></td>                                                    
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- vs Target KPI -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">vs target (KPI Measurement)</h6>
                                    <table class="table table-sm" id="tableKpiItemsResult" >
                                        <thead>
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Period</th>
                                                <th>Productivity</th>
                                                <th>CS index ratio</th>
                                                <th>Attendance</th>
                                                <th>Elearning</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach($kpiResult as $data): ?>
                                                <tr class="text-center">
                                                    <td><?= $i++; ?></td>
                                                    <td><?= date("M Y", strtotime($data['period'])); ?></td>
                                                    <td class="kpiResultProductivity"><?= $data['productivity']; ?>%</td>
                                                    <td class="kpiResultCsindex"><?= $data['csindex']; ?>%</td>
                                                    <td class="kpiResultAbsence">0</td>
                                                    <td class="kpiResultElearning"><?= $data['elearning']; ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Average</td>
                                                <td class="averageKpiProductivityCA text-center"></td>
                                                <td class="averageKpiCsindexCA text-center"></td>
                                                <td class="averageKpiAbsenceCA text-center"></td>
                                                <td class="averageKpiElearningCA text-center"></td>                                                
                                            </tr>
                                            <tr class="text-bold">
                                                <td colspan="2" class="text-center">Weight</td>
                                                <td class="weightKpiProductivityCA text-center"><?= $targetDetail['productivity'] ?>%</td>
                                                <td class="weightKpiCsindexCA text-center"><?= $targetDetail['csindex'] ?>%</td>
                                                <td class="weightKpiAbsenceCA text-center"><?= $targetDetail['absence'] ?>%</td>
                                                <td class="weightKpiElearningCA text-center"><?= $targetDetail['elearning'] ?>%</td>
                                            </tr>
                                            <tr class="text-bold bg-light">
                                                <td colspan="2" class="text-center">Achievement (estimation)</td>
                                                <td colspan="6" class="text-center text-primary kpiAchievementCA"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                                                                            
    				<?php 
    					break;
                        default:
                    ?>
                            <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">KPI Estimation calculation only for Contact Center member</h6>
                                </div>
                            </div>

                            <!-- <div class="row mt-3">
                                <div class="col">
                                    <h6 for="tableAgentAchievement" class="text-indigo h6">KPI Estimation calculation only for Contact Center member</h6>
                                </div>
                            </div> -->
                    <?php 
                        break;
                    }
            		?>
                </div>
            </div>
            <?php 
            $selectedAgent = '';
            $loggedUser = $this->db->get_where('user', ['user_id' => $this->session->userdata('user_id')])->row_array()['status'];
            ?>
            <?php if ($this->session->userdata('role_access') == 9 || $loggedUser == 'OTS') : ?>
                <?php $selectedAgent = $agent = $this->input->post('selectAssessmentByAgentSelectAgent'); ?>
                <p class="badge badge-primary mt-5 ">Penilaian Performa selama 2022 | <span class="text-warning">target rating 4.0</span></p>
                <img class="img img-fluid" src="<?= base_url() . 'assets/responsive_filemanager/source/assessment/2022/' . $selectedAgent . '.png'?>">
            <?php endif; ?>
                
        </div>
    </section>
</div>