
<div class="content-wrapper">
    <section class="content pt-2">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            
            <?php 
                $allowedChangeAgent = ['1', '5', '6', '9'];
                if (!$this->input->post('selectByAgentNewStartPeriod') || !$this->input->post('selectByAgentNewEndPeriod')) {
                    $startPeriod = date("Y-m-01", strtotime("-1 month"));
                    $endPeriod = date("Y-m-01");
                    $selectedAgent = $this->session->userdata('user_id');
                } else {
                    $startPeriod = $this->input->post('selectByAgentNewStartPeriod');
                    $endPeriod = $this->input->post('selectByAgentNewEndPeriod');
                    $selectedAgent = $this->input->post('selectByAgentNewAgent');
                }

            ?>
            
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="card-title">Achievement by Agent</span>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 760px;">
                            <label for="selectByAgentNewAgent">Agent</label>
                            <select class="custom-select" name="selectByAgentNewAgent" id="selectByAgentNewAgent" style="width: 160px;">
                                <option selected><?= $selectedAgent ?></option>
                                <?php if(in_array($this->session->userdata('role_access'), $allowedChangeAgent)): ?>
                                    <?php foreach ($allAgents as $ag): ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option><?= $this->session->userdata('user_id'); ?></option>
                                <?php endif; ?>
                            </select>
                            <label for="selectByAgentNewStartPeriod" class="ml-5">Period</label>
                            <input type="date" class="custom-select" name="selectByAgentNewStartPeriod" id="selectByAgentNewStartPeriod" style="width: 160px;" value="<?= $startPeriod ?>">
                            <label for="selectByAgentNewEndPeriod">to</label>
                            <input type="date" class="custom-select" name="selectByAgentNewEndPeriod" id="selectByAgentNewEndPeriod" style="width: 160px;" value="<?= $endPeriod; ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryAssessment" name="buttonSelectByAgentNew">Go</button>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <p class="lead text-indigo"><i class="fas fa-layer-group"></i> Source data</p>
                            <?php if(count($sourceData) < 1) : ?>
                                <p class="lead">There were no data</p>
                            <?php else : ?>
                                <table class="table table-sm table-bordered ml-4">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="text-center">#</th>
                                            <th>Month</th>
                                            <th class="text-center">Prod/hour</th>
                                            <th class="text-center">CS index</th>
                                            <th class="text-center">Attendance</th>
                                            <th class="text-center">Elearning Score</th>
                                            <th class="text-center">Teamwork by AUX</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($sourceData as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td><?= date("F Y", strtotime($row['month'])) ?></td>
                                                <td class="text-center"><?= number_format($row['productivity_result'], 1) ?></td>
                                                <td class="text-center"><?= number_format($row['smilevoice_result'] *100, 1) ?>%</td>
                                                <td class="text-center"><?= number_format($row['attendance_result'] * 100, 1); ?>%</td>
                                                <td class="text-center"><?= number_format($row['elearning_score'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['teamwork_result'] * 100, 1) ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-10">
                            <p class="lead text-indigo"><i class="fas fa-suitcase"></i> Result of Calculation</p>
                            <?php if(count($sourceData) < 1) : ?>
                                <p class="lead ml-4">There were no result to be displayed</p>
                                <!-- <a href="<?= base_url() . 'assessment/processbymonth/' . $selectPeriod ?>" class="ml-4"><button class="btn btn-outline-primary">Proccess / Calculate Result</button></a> -->
                            <?php else: ?>
                                <table class="table table-sm table-bordered ml-4">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="text-center align-middle">#</th>
                                            <th class="align-middle">Month</th>
                                            <th class="text-center align-middle">Productivity<br>30%</th>
                                            <th class="text-center align-middle">Smile voice<br>20%</th>
                                            <th class="text-center align-middle">Attendance<br>20%</th>
                                            <th class="text-center align-middle">Elearning<br>15%</th>
                                            <th class="text-center align-middle">Teamwork<br>15%</th>
                                            <th class="text-center align-middle">Total score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($sourceData as $row) :  ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td><?= date("F Y", strtotime($row['month'])) ?></td>
                                                <td class="text-center">
                                                    <?= number_format($row['productivity_result'], 1) ?>
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['productivity_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['smilevoice_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['smilevoice_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['attendance_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['attendance_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['elearning_result'], 2) ?>
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['elearning_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['teamwork_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['teamwork_score']) ?>)</span>
                                                </td>
                                                <td class="text-center text-primary text-bold"><?= number_format($row['total_score'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

