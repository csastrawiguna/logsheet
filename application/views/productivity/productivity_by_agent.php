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
                        Detail Productivity by Agent by period
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 680px;">
                            <label for="selectProductivityStart">Period</label>
                            <input type="date" class="custom-select" name="selectProductivityStart" id="selectProductivityStart" style="width: 140px;" value="<?= date("Y-m-01", strtotime('-12 months')); ?>">
                            <label for="selectProductivityEnd">to</label>
                            <input type="date" class="custom-select" name="selectProductivityEnd" id="selectProductivityEnd" style="width: 140px;" value="<?= date("Y-m-01"); ?>">
                            <label for="selectProductivityAgentod" class="ml-5">Agent</label>                
                            <select class="col-2 custom-select" name="selectProductivityAgent" id="selectProductivityAgent">
                                <?php if ($this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9 ) : ?>
                                    <?php foreach ($allAgent as $ag) : ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="<?= $this->session->userdata('user_id'); ?>" selected><?= $this->session->userdata('user_id'); ?></option>
                                <?php endif; ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectProductivityPeriod" name="buttonSelectProductivityPeriod">Go</button>
                        </form>
                    </div>
                    <div class="row table-responsive">
                        <h5 class="pl-1 h5 text-primary text-center mt-3 mb-3" id="productivityDataTitle">Data of productivity on last 12 months</h5>
                        <table class="table table-bordered" id="tableProductivityByPeriodByAgent">
                            <thead>
                                <tr class="text-center">
                                    <th class="align-middle">Period</th>
                                    <th class="align-middle">Inc call</th>
                                    <th class="align-middle">Callback</th>
                                    <th class="align-middle">Follow up call</th>
                                    <!-- <th class="align-middle">SMS</th> -->
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
                                <?php foreach ($productivityByPeriodByAgent as $pr) : ?>
                                    <tr class="text-center">
                                        <td><?= date("M-y", strtotime($pr['period'])); ?></td>
                                        <td><?= $pr['icall']; ?></td>
                                        <td><?= $pr['callback']; ?></td>
                                        <td><?= $pr['follow_up']; ?></td>
                                        <!-- <td><?= $pr['sms']; ?></td> -->
                                        <td><?= $pr['whatsapp']; ?></td>
                                        <td><?= $pr['sharp_id']; ?></td>
                                        <td><?= $pr['email']; ?></td>
                                        <td><?= $pr['notif_sap']; ?></td>
                                        <td><?= $pr['complaint']; ?></td>
                                        <td><?= $pr['part_code']; ?></td>
                                        <td><?= $pr['others']; ?></td>
                                        <td><?= $pr['total']; ?></td>
                                        <td><?= round($pr['work_hour'], 0); ?></td>
                                        <td><?= round($pr['prod_hour'], 1); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr id="rowProductivityAverage" class="text-center bg-light text-bold">
                                    <td>Average</td>
                                    <td><?= round($average['avg_icall'], 0); ?></td>
                                    <td><?= round($average['avg_callback'], 0); ?></td>
                                    <td><?= round($average['avg_follow_up'], 0); ?></td>
                                    <!-- <td><?= round($average['avg_sms'], 0); ?></td> -->
                                    <td><?= round($average['avg_whatsapp'], 0); ?></td>
                                    <td><?= round($average['avg_sharp_id'], 0); ?></td>
                                    <td><?= round($average['avg_email'], 0); ?></td>
                                    <td><?= round($average['avg_notif_sap'], 0); ?></td>
                                    <td><?= round($average['avg_complaint'], 0); ?></td>
                                    <td><?= round($average['avg_part_code'], 0); ?></td>
                                    <td><?= round($average['avg_others'], 0); ?></td>
                                    <td><?= round($average['avg_total'], 0); ?></td>
                                    <td><?= round($average['avg_work_hour'], 0); ?></td>
                                    <td><?= round($average['avg_prod_hour'], 1); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>