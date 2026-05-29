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
                    AUX Daily (All Data) periode: <?= date("d F Y", strtotime($startPeriod)) ?> - <?= date("d F Y", strtotime($endPeriod)) ?>
                    <div class="card-tools">
                        <a href="#" data-toggle="modal" data-target="#modalUploadAuxDaily" class="text-white mr-3"><i class="fas fa-upload"></i> Upload File</a>
                        <a href="#" data-toggle="modal" data-target="#modalAddSingleAuxDaily" class="text-white mr-3"><i class="fas fa-plus-circle"></i> Add Single Data</a>
                    </div>
                </div>
                <div class="card-body">                
                    <form action="" class="form-row mb-5" method="post" style="width: 820px;">
                        <label for="auxDailyAllStartPeriod" class="col-sm-1">Period</label>
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="auxDailyAllStartPeriod" name="auxDailyAllStartPeriod" class="form-control" value="<?= $startPeriod ?>">
                        </div>-
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="auxDailyAllEndPeriod" name="auxDailyAllEndPeriod" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1">
                          <div class="row">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectAuxDailyAll" name="buttonSelectAuxDailyAll">Go</button>      
                          </div>
                        </div>
                    </form>
                    <?php if (count($auxDailyByPeriod) < 1 ) : ?>
                        <p class="h2 text-muted text-center"><i class="far fa-dizzy"></i></p>
                        <p class="lead text-center text-muted">No Data To Be Displayed</p>
                    <?php else : ?>
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
                                <?php foreach($auxDailyByPeriod as $row) : ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Add Single Data -->
<div class="modal fade" id="modalAddSingleAuxDaily" tabindex="-1" role="dialog" aria-labelledby="modalAddSingleAuxDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddSingleAuxDailyLabel">Add Single Data AUX Daily - Time in second</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="addSingleAuxDailyId" id="addSingleAuxDailyId">
                    <div class="row border-bottom">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="addSingleAuxDailyAgent" class="form-label">Agent</label>
                                <div class="">
                                    <select type="" class="form-control custom-select" id="addSingleAuxDailyAgent" name="addSingleAuxDailyAgent">\
                                        <option>-- select agent --</option>
                                        <?php foreach($allAgents as $agent): ?>
                                            <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="addSingleAuxDailyExtension" class="form-label">Ext.</label>
                                <div class="">
                                    <input type="" class="form-control" id="addSingleAuxDailyExtension" name="addSingleAuxDailyExtension">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="addSingleAuxDailyDate" class="form-label">Date</label>
                                <div class="">
                                    <input type="date" class="form-control" id="addSingleAuxDailyDate" name="addSingleAuxDailyDate">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="addSingleAuxDailyStaffedtime" class="col-sm-8 col-form-label">Login/staffed time (seconds)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyStaffedtime" name="addSingleAuxDailyStaffedtime">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux0" class="col-sm-8 col-form-label">AUX 0 (Hanging phone)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux0" name="addSingleAuxDailyAux0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux1" class="col-sm-8 col-form-label">AUX 1 (Pray)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux1" name="addSingleAuxDailyAux1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux2" class="col-sm-8 col-form-label">AUX 2 (Break/Pantry/Restroom)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux2" name="addSingleAuxDailyAux2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux3" class="col-sm-8 col-form-label">AUX 3 (Lunch time)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux3" name="addSingleAuxDailyAux3">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux4" class="col-sm-8 col-form-label">AUX 4 (Happy Call Survey)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux4" name="addSingleAuxDailyAux4">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux5" class="col-sm-8 col-form-label">AUX 5 (Callback)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux5" name="addSingleAuxDailyAux5">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux6" class="col-sm-8 col-form-label">AUX 6 (Input data)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux6" name="addSingleAuxDailyAux6">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux7" class="col-sm-8 col-form-label">AUX 7 (Back Office)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux7" name="addSingleAuxDailyAux7">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux8" class="col-sm-8 col-form-label">AUX 8 (Response WA)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux8" name="addSingleAuxDailyAux8">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux9" class="col-sm-8 col-form-label">AUX 9 (Response WA)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux9" name="addSingleAuxDailyAux9">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyAux1099" class="col-sm-8 col-form-label">AUX 10-99 (Others AUX)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="addSingleAuxDailyAux1099" name="addSingleAuxDailyAux1099">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addSingleAuxDailyRemark" class="col-sm-2 col-form-label">Remark</label>
                        <div class="col-sm-10">
                            <input type="" class="form-control" id="addSingleAuxDailyRemark" name="addSingleAuxDailyRemark">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="reset" class="btn btn-warning" name="blackbookAddReset" id="blackbookAddReset"><i class="fas fa-undo"></i> Reset</button>
                    <button type="submit" class="btn btn-primary" name="blackbookAddSubmit" id="blackbookAddSubmit"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>