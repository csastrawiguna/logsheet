<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- /.row -->
            <div class="card">
                <div class="card-header bg-primary">
                    Detail Productivity by Agent by period
                    <div class="card-tools">
                        <a href="" class="mx-2 text-white" data-toggle="modal" data-target="#editDataProductivity" id="buttonAddDataProductivity" ><i class="fas fa-plus-circle"></i> Add single data</a>
                        <a href="" class="mx-2 text-white" data-toggle="modal" data-target="#addDataProductivityExcel" id="buttonAddDataProductivityExcel" ><i class="fas fa-upload"></i> From Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 280px;">
                            <label for="selectProductivityByPeriod">Period</label>
                            <input type="date" class="custom-select" name="selectProductivityByPeriod" id="selectProductivityByPeriod" style="width: 160px;" value="<?= date("Y-m-01", strtotime('-1 months')); ?>">
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectProductivityByPeriod" name="buttonSelectProductivityByPeriod">Go</button>
                        </form>
                        <form action="" method="post" style="width: 380px;">
                            <label for="selectOrderProductivityByPeriod">Order by </label>
                            <select type="" class="custom-select" name="selectOrderProductivityByPeriod" id="selectOrderProductivityByPeriod" style="width: 120px;">
                                <option value="agent" selected>Agent</option>
                                <option value="icall">Inc.Call</option>
                                <option value="callback">Callback</option>
                                <option value="follow_up">Follow up</option>
                                <option value="sms">SMS</option>
                                <option value="whatsapp">Whatsapp</option>
                                <option value="sharp_id">Sharp ID</option>
                                <option value="email">Email</option>
                                <option value="notif_sap">Notif SAP</option>
                                <option value="part_code">Parts code</option>
                                <option value="total">Total</option>
                                <option value="work_hour">Work hour</option>
                                <option value="prod_hour">Prod/hour</option>
                            </select>
                            <select type="" class="custom-select" name="selectOrderTypeProductivityByPeriod" id="selectOrderTypeProductivityByPeriod" style="width: 80px;">
                                <option value="ASC" selected>ASC</option>
                                <option value="DESC">DESC</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectOrderProductivityByPeriod" name="buttonSelectOrderProductivityByPeriod">Sort</button>
                        </form>                        
                    </div>
                    <div class="row table-responsive">
                        <h5 class="pl-1 h5 text-primary text-center mt-2 mb-3" id="productivityByPeriodDataTitle">Data of agent productivity on <?= date("F Y", strtotime('-1 months')); ?></h5>

                        <table class="table table-bordered table-sm" id="tableProductivityAll">
                            <thead>
                                <tr class="text-center">
                                    <th class="align-middle">Agent</th>
                                    <th class="align-middle">Inc call</th>
                                    <th class="align-middle">Callback</th>
                                    <th class="align-middle">FU call</th>
                                    <th class="align-middle">SMS</th>
                                    <th class="align-middle">Whatsapp</th>
                                    <th class="align-middle">Sharp ID</th>
                                    <th class="align-middle">Email</th>
                                    <th class="align-middle">Notif SAP</th>
                                    <th class="align-middle">Complaint</th>
                                    <th class="align-middle">Part code</th>
                                    <th class="align-middle">Others</th>
                                    <th class="align-middle">Total</th>
                                    <th class="align-middle">Work hour</th>
                                    <th class="align-middle">Prod/ hour</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lastMonthProductivity)) : ?>
                                    <tr class="text-center">
                                        <td colspan="17" class="bg-light text-center">
                                            <h5 class="text-danger">Data unavailable yet</h5>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($lastMonthProductivity as $pr) : ?>
                                        <tr>
                                            <td><?= $pr['agent']; ?></td>
                                            <td class="text-center"><?= round($pr['icall'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['callback'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['follow_up'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['sms'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['whatsapp'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['sharp_id'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['email'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['notif_sap'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['complaint'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['part_code'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['others'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['total'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['work_hour'], 0); ?></td>
                                            <td class="text-center"><?= round($pr['prod_hour'], 1); ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-sm text-primary buttonEditProductivityAgent" data-id="<?= $pr['id']; ?>"><span class="lnr lnr-pencil"></span></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>                   
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal upload from excel -->
<div class="modal fade" id="addDataProductivityExcel" tabindex="-1" role="dialog" aria-labelledby="addDataProductivityExcelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDataProductivityExcelLabel">Import productivity from Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('productivity/uploadProductivityFromExcel'); ?>
                <div class="form-group row">
                    <label for="productivityAddExcel" class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="productivityAddExcel" name="productivityAddExcel">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary" name="categoryModalSubmit">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Productivity -->
<div class="modal fade" id="editDataProductivity" tabindex="-1" role="dialog" aria-labelledby="editDataProductivityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDataProductivityLabel">Add Productivity Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-x: hidden;">
                <form action="" method="post">
                    <div class="form-group row">
                        <label for="addProductivityPeriod" class="col-sm-6 col-form-label">Period</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" id="addProductivityPeriod" name="addProductivityPeriod" value="<?= date("Y-m-01"); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityAgent" class="col-sm-6 col-form-label">Agent </label>
                        <div class="col-sm-6">
                            <select name="addProductivityAgent" id="addProductivityAgent" class="custom-select">
                                <option>-select agent-</option>
                                <?php foreach ($allAgent as $ag) : ?>
                                    <option value="<?= $ag['user_id'] ?>"><?= $ag['user_id'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityIcall" class="col-sm-6 col-form-label">Incoming call</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityIcall" name="addProductivityIcall">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityCallback" class="col-sm-6 col-form-label">Callback</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityCallback" name="addProductivityCallback">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityFollowup" class="col-sm-6 col-form-label">Follow up call</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityFollowup" name="addProductivityFollowup">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivitySms" class="col-sm-6 col-form-label">SMS reply</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivitySms" name="addProductivitySms">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label for="addProductivityWebchat" class="col-sm-6 col-form-label">Webchat</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityWebchat" name="addProductivityWebchat">
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label for="addProductivityWhatsapp" class="col-sm-6 col-form-label">Whatsapp</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityWhatsapp" name="addProductivityWhatsapp">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivitySharpid" class="col-sm-6 col-form-label">Sharp ID</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivitySharpid" name="addProductivitySharpid">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityEmail" class="col-sm-6 col-form-label">Email </label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityEmail" name="addProductivityEmail">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityNotifSap" class="col-sm-6 col-form-label">SAP notif.</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityNotifSap" name="addProductivityNotifSap">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityComplaint" class="col-sm-6 col-form-label">Complaint</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityComplaint" name="addProductivityComplaint">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityPartcode" class="col-sm-6 col-form-label">Spare part code entry</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityPartcode" name="addProductivityPartcode">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityOthers" class="col-sm-6 col-form-label">Others</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityOthers" name="addProductivityOthers">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addProductivityWorkHour" class="col-sm-6 col-form-label">Working hour</label>
                        <div class="col-sm-6">
                            <input type="" class="form-control" id="addProductivityWorkHour" name="addProductivityWorkHour">
                        </div>
                    </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-warning" id="productivityAddSingleDataReset">Reset</button>
                <button type="button" class="btn btn-danger" style="display: none;" id="productivityAddSingleDataDelete" name="productivityAddSingleDataDelete">Delete data</button>
                <button type="submit" class="btn btn-primary" id="productivityAddSingleDataSubmit" name="productivityAddSingleDataSubmit">Save</button>   
                <button type="button" class="btn btn-primary" id="productivityUpdateDataSubmit" name="productivityUpdateDataSubmit">Update</button>          
            </div>
            </form>
        </div>
    </div>
</div>
</div>