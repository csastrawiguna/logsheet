<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                $allowedChangeAgent = in_array($this->session->userdata('role_access'), ['1', '5', '6', '9']);

                require 'aux-function.php';
            ?>

            <div class="card">
                <div class="card-header bg-primary">
                    AUX Daily (All Data) periode: <?= date("d F Y", strtotime($startPeriod)) ?> - <?= date("d F Y", strtotime($endPeriod)) ?>
                    <div class="card-tools">
                        <a href="#" data-toggle="modal" data-target="#modalUploadAuxDaily" class="text-white mr-3"><i class="fas fa-upload"></i> Upload File</a>
                        <a href="#" id="buttonAddSingleAuxDaily" data-toggle="modal" data-target="#modalAddSingleAuxDaily" class="text-white mr-3"><i class="fas fa-plus-circle"></i> Add Single Data</a>
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
                                <?php foreach($auxDailyByPeriod as $row) : ?>
                                    <tr>
                                        <td class="text-left"><?= $row['agent'] ?></td>
                                        <td><?= date("d M 'y", strtotime($row['date'])) ?></td>
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
                                            <a href="#" class="text-success buttonViewDetailSingleAuxDaily" data-toggle="modal" data-target="#modalViewDetailSingleRowAuxDaily" data-date="<?= $row['date'] ?>" data-agent="<?= $row['agent'] ?>"><i class="fas fa-search"></i></a><br>
                                            <?php if ($allowedChangeAgent) : ?>
                                                <a href="#" class="buttonDeleteSingleAuxDaily" data-link="<?= base_url('auxdata/deleteSingleDaily/' . $row['id']) ?>">
                                                    <i class="fas fa-times text-danger"></i>
                                                </a>
                                                <a href="#" class="buttonEditSingleAuxDaily ml-1" data-toggle="modal" data-target="#modalAddSingleAuxDaily" data-date="<?= $row['date'] ?>" data-agent="<?= $row['agent'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
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
                                    <input type="date" class="form-control" id="addSingleAuxDailyDate" name="addSingleAuxDailyDate" value="<?= date("Y-m-d", strtotime("-1 days")) ?>">
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

<!-- Modal Upload AUX Daily -->
<div class="modal fade" id="modalUploadAuxDaily" tabindex="-1" role="dialog" aria-labelledby="modalUploadAuxDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?= form_open_multipart('auxdata/uploadAuxDaily'); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadAuxDailyLabel">Upload AUX Daily</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="uploadAuxDailyFile">Data <i class="fas fa-file-excel"></i></label>
                        <input type="file" accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" id="uploadAuxDailyFile" name="uploadAuxDailyFile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="reset" class="btn btn-warning" name="uploadAuxDailyFileReset" id="uploadAuxDailyFileReset"><i class="fas fa-undo"></i> Reset</button>
                    <button type="submit" class="btn btn-primary" name="uploadAuxDailyFileSubmit" id="uploadAuxDailyFileSubmit"><i class="fas fa-upload"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal View Detail per Row Data -->
<div class="modal fade" id="modalViewDetailSingleRowAuxDaily" tabindex="-1" role="dialog" aria-labelledby="modalViewDetailSingleRowAuxDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="min-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewDetailSingleRowAuxDailyLabel">Detail AUX Daily by Agent</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="viewDetailSingleAuxDailyAgent">Agent</label>
                            <input type="" class="form-control" id="viewDetailSingleAuxDailyAgent" value="" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="viewDetailSingleAuxDailyDate">Date</label>
                            <input type="" class="form-control" id="viewDetailSingleAuxDailyDate" value="" readonly>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered mb-3" style="max-width: 320px;">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>