<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                require 'aux-function.php';
            ?>

            <div class="card">
                <div class="card-header bg-primary">
                    AUX Daily by Agent
                </div>
                <div class="card-body">
                    <!-- <form action="<?= base_url('auxdata/test') ?>" class="form-row mb-5" method="post" style="width: 1080px;"> -->
                    <form action="" class="form-row mb-5" method="post" style="width: 1080px;">
                        <label for="auxByAgentSelectAgent" class="col-sm-1 text-right" style="max-width: 60px;">Agent</label>
                        <div class="col-sm-2">
                            <select id="auxByAgentSelectAgent" name="auxByAgentSelectAgent" class="custom-select">
                                <option value="<?= $agent ?>" selected><?= $agent ?></option>
                                <?php if($allowedChangeAgent): ?>
                                    <?php foreach ($allAgents as $ag): ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option><?= $this->session->userdata('user_id'); ?></option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <label for="auxDailyByAgentStartPeriod" class="col-sm-1 text-right">Period</label>
                        <div class="col-sm-2" style="min-width: 140px; max-width: 150px;">
                            <input type="date" id="auxDailyByAgentStartPeriod" name="auxDailyByAgentStartPeriod" class="form-control" value="<?= $startPeriod ?>">
                        </div>-
                        <div class="col-sm-2" style="min-width: 140px; max-width: 150px;">
                            <input type="date" id="auxDailyByAgentEndPeriod" name="auxDailyByAgentEndPeriod" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1 ml-3" style="min-width: 100px;">
                             <div class="pretty p-default p-curve">
                                <input type="checkbox" id="auxDailyByAgentIsohTrue" name="auxDailyByAgentIsoh[]" class="form-control" value="1" <?= isohToChecked($isOh, 1) ?>>
                                <div class="state p-success-o">
                                    <label>Weekday</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1" style="max-width: 60px;">
                            <div class="pretty p-default p-curve">
                                <input type="checkbox" id="auxDailyByAgentIsohFalse" name="auxDailyByAgentIsoh[]" class="form-control" value="0" <?= isohToChecked($isOh, 0) ?>>
                                <div class="state p-warning-o">
                                    <label>OT</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectAuxDailyByAgent" name="buttonSelectAuxDailyByAgent">Go</button>
                        </div>
                    </form>
                    <?php if (count($auxDailyByAgent) < 1 ) : ?>
                        <p class="h2 text-muted text-center"><i class="far fa-dizzy"></i></p>
                        <p class="lead text-center text-muted">No Data To Be Displayed</p>
                    <?php else : ?>
                        <table id="tableAuxAgent" class="table dataTableBasic">
                            <thead>
                                <tr class="border-top">
                                    <th class="align-middle">Agent</th>
                                    <th class="align-middle">Date</th>
                                    <th class="text-right align-middle">Staffed<br>Login</th>
                                    <th class="text-right align-middle">TTL AUX</th>
                                    <th class="text-right align-middle">AUX<br>1,2,3,6</th>
                                    <th class="text-right align-middle">Hanging<br><small>(AUX 0)</small></th>
                                    <th class="text-right align-middle">Pray<br><small>(AUX 1)</small></th>
                                    <th class="text-right align-middle">Break<br><small>(AUX 2)</small></th>
                                    <th class="text-right align-middle">Lunch<br><small>(AUX 3)</small></th>
                                    <th class="text-right align-middle">Input Data<br><small>(AUX 6)</small></th>
                                    <th class="text-right align-middle">Respon WA<br><small>(AUX 8)</small></th>
                                    <th class="align-middle">Remark</th>
                                    <th class="align-middle text-center">...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($auxDailyByAgent as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row['agent'] ?></td>
                                        <td>
                                            <?= date("d M 'y", strtotime($row['date'])) ?>
                                            <?= isohToBadge($row['is_oh']) ?>        
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['staffed_time']) ?></p>
                                        </td>
                                        <td class="text-right">
                                            <p>
                                                  <?= convertToHoursMins($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) ?>
                                                <br>
                                                <span class="text-muted">
                                                (<?= number_format((($row['aux_0'] + $row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_4'] + $row['aux_5'] + $row['aux_6'] + $row['aux_7'] + $row['aux_8'] + $row['aux_9'] + $row['aux_1099']) / $row['staffed_time']) *100, 1) ?>%)
                                                </span>
                                            </p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) ?><br>
                                            <span class="text-muted">
                                                (<?= number_format((($row['aux_1'] + $row['aux_2'] + $row['aux_3'] + $row['aux_6']) / $row['staffed_time']) *100, 1) ?>%)
                                            </span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_0']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_0'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_1']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_1'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_2']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_2'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_3']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_3'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_6']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_6'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td class="text-right">
                                            <p><?= convertToHoursMins($row['aux_8']) ?><br>
                                            <span class="text-muted">(<?= number_format(($row['aux_8'] / $row['staffed_time']) *100, 1) ?>%)</span></p>
                                        </td>
                                        <td>
                                            <?= $row['remark'] ?>
                                        </td>
                                        <td>
                                            <a href="#" class="text-primary buttonViewDetailSingleAuxDaily" data-toggle="modal" data-target="#modalViewDetailSingleRowAuxDaily" data-date="<?= $row['date'] ?>" data-agent="<?= $row['agent'] ?>"><i class="fas fa-search"></i></a><br>
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

<!-- Modal View Detail per Row Data -->
<div class="modal fade" id="modalViewDetailSingleRowAuxDaily" tabindex="-1" role="dialog" aria-labelledby="modalViewDetailSingleRowAuxDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 900px;">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalViewDetailSingleRowAuxDailyLabel">Detail AUX Daily by Agent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-group col-md-3" style="max-width: 160px;">
                            <label for="viewDetailSingleAuxDailyAgent">Agent</label>
                            <input type="" class="form-control" id="viewDetailSingleAuxDailyAgent" value="" readonly>
                        </div>
                        <div class="form-group col-md-2" style="max-width: 120px;">
                            <label for="viewDetailSingleAuxDailyDate">Date</label>
                            <input type="" class="form-control" id="viewDetailSingleAuxDailyDate" value="" readonly>
                        </div>
                        <div class="form-group col-md-1 ml-0">
                            <label for="viewDetailSingleAuxDailyIsoh">&nbsp;</label>
                            <span class="" id="viewDetailSingleAuxDailyIsoh"><span class="badge badge-primary badge-pill px-2 py-1">Weekday</span></span>
                        </div>
                        <div class="form-group col-md-2">
                        </div>
                        <div class="form-group col-md-4 text-right">
                            <table class="table table-bordered mb-3">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center align-top">Staffed Time</th>
                                        <th class="text-center align-top">Total AUX</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" id="tdStaffedtime"></td>
                                        <td class="text-center" id="tdAuxtotal"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Detail breakdown AUX -->
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="11" class="align-middle text-center">AUX</th>
                        </tr>
                        <tr>
                            <th class="text-center align-top">AUX 0<br>Hanging</th>
                            <th class="text-center align-top">AUX 1<br>Pray</th>
                            <th class="text-center align-top">AUX 2<br>Break</th>
                            <th class="text-center align-top">AUX 3<br>Lunch</th>
                            <th class="text-center align-top">AUX 4<br>Survey</th>
                            <th class="text-center align-top">AUX 5<br>Callback</th>
                            <th class="text-center align-top">AUX 6<br>Input<br>Data</th>
                            <th class="text-center align-top">AUX 7<br>BO</th>
                            <th class="text-center align-top">AUX 8<br>WA resp.</th>
                            <th class="text-center align-top">AUX 9<br>Login</th>
                            <th class="text-center align-top">Others</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-top" id="tdAux0"></td>
                            <td class="text-center align-top" id="tdAux1"></td>
                            <td class="text-center align-top" id="tdAux2"></td>
                            <td class="text-center align-top" id="tdAux3"></td>
                            <td class="text-center align-top" id="tdAux4"></td>
                            <td class="text-center align-top" id="tdAux5"></td>
                            <td class="text-center align-top" id="tdAux6"></td>
                            <td class="text-center align-top" id="tdAux7"></td>
                            <td class="text-center align-top" id="tdAux8"></td>
                            <td class="text-center align-top" id="tdAux9"></td>
                            <td class="text-center align-top" id="tdAux1099"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="viewDetailSingleAuxDailyRemark">Remark</label>
                            <input type="" class="form-control" id="viewDetailSingleAuxDailyRemark" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-dismiss="modal"><i class="fas fa-check"></i> Done</button>
            </div>
        </div>
    </div>
</div>