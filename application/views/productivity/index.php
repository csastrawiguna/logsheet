<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- /.row -->
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-titles">
                        Summary of Agent productivity
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 540px;">
                            <label for="selectSummaryProductivityStart">Period</label>
                            <input type="date" class="custom-select" name="selectSummaryProductivityStart" id="selectSummaryProductivityStart" style="width: 140px;" value="<?= date("Y-m-01", strtotime('-1 months')); ?>">
                            <label for="selectSummaryProductivityEnd">to</label>
                            <input type="date" class="custom-select" name="selectSummaryProductivityEnd" id="selectSummaryProductivityEnd" style="width: 140px;" value="<?= date("Y-m-01", strtotime('-1 months')); ?>">
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectSummaryProductivity" name="buttonSelectSummaryProductivity">Go</button>
                            <button type="button" class="btn btn-outline-success ml-1" id="buttonProductvitiyDetailToExcel" name="buttonProductvitiyDetailToExcel"> <i class="fas fa-file-excel"></i> Detail Excel</button>
                        </form>
                        <form action="" method="post" style="width: 380px;">
                            <label for="selectOrderSummaryProductivity">Order by </label>
                            <select type="" class="custom-select" name="selectOrderSummaryProductivity" id="selectOrderSummaryProductivity" style="width: 120px;">
                                <option value="agent">Agent</option>
                                <option value="icall">Inc.Call</option>
                                <option value="callback">Callback</option>
                                <option value="follow_up">Follow up</option>
                                <option value="sms">SMS</option>
                                <option value="webchat">Webchat</option>
                                <option value="whatsapp">Whatsapp</option>
                                <option value="sharp_id">Sharp ID</option>
                                <option value="email">Email</option>
                                <option value="notif_sap">Notif SAP</option>
                                <option value="part_code">Parts code</option>
                                <option value="total" selected>Total</option>
                                <option value="work_hour">Work hour</option>
                                <option value="prod_hour">Prod/hour</option>
                            </select>
                            <select type="" class="custom-select" name="selectOrderTypeSummaryProductivity" id="selectOrderTypeSummaryProductivity" style="width: 80px;">
                                <option value="ASC">ASC</option>
                                <option value="DESC" selected>DESC</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectOrderSummaryProductivity" name="buttonSelectOrderSummaryProductivity">Sort</button>
                        </form>
                    </div>
                    
                    <div class="row table-responsive">
                        <h5 class="pl-1 h5 text-primary text-center mt-2 mb-3" id="productivityDataTitle">Data of agent productivity on <?= date("F Y", strtotime('-1 months')); ?></h5>

                        <h6 class="h6 text-indigo pl-1">Customer Assistant</h6>
                        <table class="table table-bordered table-sm" id="tableProductivityCa">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lastMonthSummaryProductivity)) : ?>
                                    <tr class="text-center">
                                        <td colspan="16" class="bg-light text-center">
                                            <h5 class="text-danger">Data unavailable yet</h5>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php $jobcodeCa = ['cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12']; ?>
                                    <?php foreach ($lastMonthSummaryProductivity as $ps) : ?>
                                        <?php if (in_array($ps['jobcode'], $jobcodeCa)) : ?>
                                            <tr>
                                                <td><?= $ps['agent']; ?></td>
                                                <td class="text-center"><?= round($ps['icall'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['callback'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['follow_up'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sms'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['whatsapp'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sharp_id'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['email'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['notif_sap'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['complaint'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['part_code'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['others'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['total'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['work_hour'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['prod_hour'], 1); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <h6 class="h6 text-indigo pl-1 mt-4">Product Assistant</h6>
                        <table class="table table-bordered table-sm" id="tableProductivityPa">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lastMonthSummaryProductivity)) : ?>
                                    <tr class="text-center">
                                        <td colspan="16" class="bg-light text-center">
                                            <h5 class="text-danger">Data unavailable yet</h5>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($lastMonthSummaryProductivity as $ps) : ?>
                                        <?php if ($ps['jobcode'] == 'cs-ccc-cc20') : ?>
                                            <tr>
                                                <td><?= $ps['agent']; ?></td>
                                                <td class="text-center"><?= round($ps['icall'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['callback'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['follow_up'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sms'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['whatsapp'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sharp_id'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['email'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['notif_sap'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['complaint'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['part_code'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['others'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['total'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['work_hour'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['prod_hour'], 1); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <h6 class="h6 text-indigo pl-1 mt-4">Spare Part Specialist</h6>
                        <table class="table table-bordered table-sm" id="tableProductivityPart">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lastMonthSummaryProductivity)) : ?>
                                    <tr class="text-center">
                                        <td colspan="16" class="bg-light text-center">
                                            <h5 class="text-danger">Data unavailable yet</h5>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($lastMonthSummaryProductivity as $ps) : ?>
                                        <?php if ($ps['jobcode'] == 'cs-ccc-cc30') : ?>
                                            <tr>
                                                <td><?= $ps['agent']; ?></td>
                                                <td class="text-center"><?= round($ps['icall'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['callback'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['follow_up'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sms'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['whatsapp'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sharp_id'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['email'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['notif_sap'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['complaint'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['part_code'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['others'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['total'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['work_hour'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['prod_hour'], 1); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <h6 class="h6 text-indigo pl-1 mt-4">Back Office</h6>
                        <table class="table table-bordered table-sm" id="tableProductivityBo">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lastMonthSummaryProductivity)) : ?>
                                    <tr class="text-center">
                                        <td colspan="16" class="bg-light text-center">
                                            <h5 class="text-danger">Data unavailable yet</h5>
                                        </td>
                                    </tr>
                                <?php else : ?>
                                    <?php $jobcodeAgent = ['cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc20', 'cs-ccc-cc30'] ?>
                                    <?php foreach ($lastMonthSummaryProductivity as $ps) : ?>
                                        <?php if (in_array($ps['jobcode'], $jobcodeAgent) == false) : ?>
                                            <tr>
                                                <td><?= $ps['agent']; ?></td>
                                                <td class="text-center"><?= round($ps['icall'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['callback'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['follow_up'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sms'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['whatsapp'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['sharp_id'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['email'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['notif_sap'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['complaint'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['part_code'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['others'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['total'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['work_hour'], 0); ?></td>
                                                <td class="text-center"><?= round($ps['prod_hour'], 1); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>