<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                $allowedChangeAgent = ['1', '5', '6', '9'];
                // if(!$this->input->post()) {
                //   $startPeriod = date("Y-m-01", strtotime("-6 months"));
                //   $endPeriod = date("Y-m-01");
                //   $agent = $this->session->userdata('user_id');
                // } else {
                //   $startPeriod = $this->input->post('auxByAgentDateStart');
                //   $endPeriod = $this->input->post('auxByAgentDateEnd');
                //   $agent = $this->input->post('auxByAgentSelectAgent');
                // }

                require 'aux-function.php';
            ?>

            <div class="card">
                <div class="card-header bg-primary">
                    AUX Daily 
                </div>
                <div class="card-body">                
                    <form action="" class="form-row mb-5" method="post" style="width: 820px;">
                        <label for="auxByAgentSelectAgent" class="col-sm-1">Agent</label>
                        <div class="col-sm-2">
                            <select id="auxByAgentSelectAgent" name="auxByAgentSelectAgent" class="custom-select">
                                <option selected><?= $agent ?></option>
                                <?php if(in_array($this->session->userdata('role_access'), $allowedChangeAgent)): ?>
                                    <?php foreach ($allAgents as $ag): ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option><?= $this->session->userdata('user_id'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <label for="auxByAgentDateStart" class="col-sm-1 ml-5">Period</label>
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="auxByAgentDateStart" name="auxByAgentDateStart" class="form-control" value="">
                        </div>-
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="auxByAgentDateEnd" name="auxByAgentDateEnd" class="form-control" value="">
                        </div>
                        <div class="col-sm-1">
                          <div class="row ml-1">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectAuxAgent" name="buttonSelectAuxAgent">Go</button>      
                          </div>
                        </div>
                    </form>
                    <table id="tableAuxAgent" class="table ">
                    <thead>
                        <tr class="border-top">
                            <th class="align-middle">Month</th>
                            <th class="text-right align-middle">Staffed<br>Login</th>
                            <th class="text-right align-middle">TTL AUX</th>
                            <th class="text-right align-middle">AUX<br>1,2,3,6</th>
                            <th class="text-right align-middle">Hanging<br><small>(AUX 0)</small></th>
                            <th class="text-right align-middle">Pray<br><small>(AUX 1)</small></th>
                            <th class="text-right align-middle">Break<br><small>(AUX 2)</small></th>
                            <th class="text-right align-middle">Lunch<br><small>(AUX 3)</small></th>
                            <th class="text-right align-middle">Follow Up<br><small>(AUX 4)</small></th>
                            <th class="text-right align-middle">Callback<br><small>(AUX 5)</small></th>
                            <th class="text-right align-middle">Input Data<br><small>(AUX 6)</small></th>
                            <th class="text-right align-middle">Respon WA<br><small>(AUX 8)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($auxByAgentMonthly as $row) : ?>
                            <tr>
                                <td><?= date("M Y", strtotime($row['month'])) ?></td>
                                <td class="text-right">
                                    <p><?= convertToHoursMins($row['staffed_time']) ?></p>
                                </td>
                                <td class="text-right">
                                    <p>
                                        <?= number_format((($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) / $row['staffed_time']) *100, 1) ?>%
                                        <br>
                                        <span class="text-muted">
                                          (<?= convertToHoursMins($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) ?>)
                                        </span>
                                    </p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format((($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p> <?= number_format(($row['aux_0'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_0']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_1'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_1']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_2'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_2']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_3'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_3']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_4'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_4']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_5'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_5']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_6'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_6']) ?>)</span></p>
                                </td>
                                <td class="text-right">
                                    <p><?= number_format(($row['aux_8'] / $row['staffed_time']) *100, 1) ?>%<br>
                                    <span class="text-muted">(<?= convertToHoursMins($row['aux_8']) ?>)</span></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
