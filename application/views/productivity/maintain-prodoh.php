<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="container-fluid">
            <!-- /.row -->
                <?php 
                    function remarkToString($rmk) {
                        return $rmk == 1 ? '' : strtoupper($rmk);
                    }
                ?>

                <div class="card card-primary">
                    <div class="card-header">
                        <span>Maintain Productivity OH Data</span>
                        <div class="card-tools">
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form action="" method="post" style="width: 820px;" class="row">
                                    <label for="productivityDailyEditAgent" class="mr-2 col-sm-auto">Agent</label>
                                    <select class="custom-select col-sm-auto" name="productivityDailyEditAgent" id="productivityDailyEditAgent" style="width: 160px;">
                                        <option value="<?= $agent ?>" selected><?= $agent ?></option>
                                        <option value="">- select agent -</option>
                                        <?php foreach ($allAgents as $row) : ?>
                                            <option value="<?= $row['user_id'] ?>"><?= $row['user_id'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="productivityDailyEditStartdate" class="ml-5 mr-2 col-sm-auto">Period</label>
                                    <input type="date" class="col-sm-auto form-control" name="productivityDailyEditStartdate" id="productivityDailyEditStartdate" style="width: 160px;" value="<?= $startDate; ?>">
                                    <label for="productivityDailyEditEnddate" class="mx-2 col-sm-auto">to</label>
                                    <input type="date" class="form-control" name="productivityDailyEditEnddate" id="productivityDailyEditEnddate" style="width: 160px;" value="<?= $endDate; ?>">
                                    <button type="submit" class="btn btn-outline-primary ml-1" id="productivityDailyEditSubmit" name="productivityDailyEditSubmit">Go</button>
                                    <?php if (count($dataforedit) >= 1) : ?>
                                        <a href="<?= base_url('productivity/detailDailyToExcel/') . $startDate . '/' . $endDate ?>" class="ml-1">
                                            <span class="btn btn-outline-success"><i class="fas fa-file-excel"></i></span>
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <p class="h5 mb-3 text-indigo">Edit Productivity OH</p>
                                <table class="table table-sm tableBasicDataTable" id="tableListProductivityForEdit">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Agent</th>
                                            <th>Assign</th>
                                            <th class="text-center">Target</th>
                                            <th class="text-center">iCall</th>
                                            <th class="text-center">WA</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">FU</th>
                                            <th class="text-center">Total</th>
                                            <th>Remark</th>
                                            <th>
                                                <div class="pretty p-default">
                                                    <input type="checkbox" id="buttonSelectAllProductivityDaily" value="">
                                                    <div class="state p-danger">
                                                        <label></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                ...
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($dataforedit as $row) : ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= date("d-M-y", strtotime($row['date'])) ?></td>
                                                <td><?= $row['agent'] ?></td>
                                                <td><?= ucwords($row['assignment']) ?></td>
                                                <td class="text-center"><?= $row['target'] ?></td>
                                                <td class="text-center"><?= $row['icall'] ?></td>
                                                <td class="text-center"><?= $row['whatsapp_reply'] ?></td>
                                                <td class="text-center"><?= $row['sms_email'] ?></td>
                                                <td class="text-center"><?= $row['followup'] ?></td>
                                                <td class="text-center"><?= $row['icall'] + $row['whatsapp_reply'] + $row['sms_email'] + $row['followup'] ?></td>
                                                <td><?= remarkToString($row['remark']) ?></td>
                                                <td>
                                                    <div class="pretty p-default">
                                                        <input type="checkbox" class="buttonSelectAgentProductivityDaily" value="<?= $row['id'] ?>">
                                                        <div class="state p-warning">
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('productivity/deleteDailySingle/') . $row['id'] ?>" class="mx-1 buttonDeleteSingleProductivityDaily"><i class="fas fa-times text-danger"></i></a>
                                                    <a href="#" class="mx-1 buttonEditSingleProductivityDaily" data-id=<?= $row['id'] ?> data-toggle="modal" data-target="#editDataProductivityDaily"><i class="fas fa-edit text-secondary"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-danger my-2" id="buttonDeleteSelectedProductivityDaily" style="display: none;">Delete Selected Rows</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="editDataProductivityDaily" tabindex="-1" role="dialog" aria-labelledby="editDataProductivityDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('productivity/editDailySingle') ?>" method="POST">
                <div class="modal-header">
                    <span class="modal-title h5 text-primary" id="editDataProductivityDailyLabel">Edit Productivity Daily Data</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="editProductivityDailyId" id="editProductivityDailyId" value="" readonly>
                    <div class="form-group row">
                        <label for="editProductivityDailyDate" class="col-sm-4 col-form-label">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="editProductivityDailyDate" id="editProductivityDailyDate" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyAgent" class="col-sm-4 col-form-label">Agent</label>
                        <div class="col-sm-8">
                            <input type="" class="form-control" name="editProductivityDailyAgent" id="editProductivityDailyAgent" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyAssignment" class="col-sm-4 col-form-label">Assignment</label>
                        <div class="col-sm-8">
                            <div class="pretty p-default p-curve">
                                <input type="radio" id="editProductivityDailyAssignmentReguler" name="editProductivityDailyAssignment" value="reguler" >
                                <div class="state p-primary-o">
                                    <label>Reguler</label>
                                </div>
                            </div>
                            <div class="pretty p-default p-curve">
                                <input type="radio" id="editProductivityDailyAssignmentFollowup" name="editProductivityDailyAssignment" value="follow up">
                                <div class="state p-primary-o">
                                    <label>Follow Up</label>
                                </div>
                            </div>
                            <div class="pretty p-default p-curve">
                                <input type="radio" id="editProductivityDailyAssignmentWhatsapp" name="editProductivityDailyAssignment" value="whatsapp">
                                <div class="state p-primary-o">
                                    <label>Whatsapp</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyTarget" class="col-sm-4 col-form-label">Target</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="editProductivityDailyTarget" id="editProductivityDailyTarget" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyIcall" class="col-sm-4 col-form-label">Incoming Call</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="editProductivityDailyIcall" id="editProductivityDailyIcall" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyWhatsapp" class="col-sm-4 col-form-label">Whatsapp reply</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="editProductivityDailyWhatsapp" id="editProductivityDailyWhatsapp" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyFollowup" class="col-sm-4 col-form-label">Follow Up Call</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="editProductivityDailyFollowup" id="editProductivityDailyFollowup" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="editProductivityDailyRemark" class="col-sm-4 col-form-label">Remark</label>
                        <div class="col-sm-8">
                            <input type="" class="form-control" name="editProductivityDailyRemark" id="editProductivityDailyRemark" value="">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" name="productivityDailyEditSubmit"><i class="fas fa-save"></i> Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>