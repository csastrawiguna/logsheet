<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <?= $this->session->flashdata('message');  ?>
            <!-- /.row -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        CS index result by Agent
                    </h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body" style="height: 76vh;">
                    <form action="">
                        <div class="row">
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="resultAgent">Agent</label>
                                    </div>
                                    <div class="col-6">
                                        <select name="resultAgent" id="resultAgent" class="custom-select">
                                            <?php if ($this->session->userdata['role_access'] == 1 || $this->session->userdata['role_access'] == 5 || $this->session->userdata['role_access'] == 9) : ?>
                                                <?php foreach ($agents as $ag) : ?>
                                                    <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                                <?php endforeach;?>
                                            <?php else :?>
                                                <option value="<?= $this->session->userdata['user_id']; ?>" selected><?= $this->session->userdata['user_id']; ?>
                                                </option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-2 text-right">
                                        <label for="resultPeriodStart" class="">Period</label>
                                    </div>
                                    <div class="col-4">
                                        <select name="resultPeriodStart" id="resultPeriodStart" class="custom-select">
                                            <option selected>-start month-</option>
                                            <?php foreach ($period as $pr) : ?>
                                                <option value="<?= $pr['period']; ?>"><?= date("M-Y", strtotime($pr['period'])); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    -
                                    <div class="col-4">
                                        <select name="resultPeriodEnd" id="resultPeriodEnd" class="custom-select">
                                            <option selected>-end month-</option>
                                            <?php foreach ($period as $pr) : ?>
                                                <option value="<?= $pr['period']; ?>"><?= date("M-Y", strtotime($pr['period'])); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-outline-primary" type="button" name="buttonSelectCsindexResultByAgent" id="buttonSelectCsindexResultByAgent">Go</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-4">
                        <table class="table table-stripped table-hover table-bordered table-sm col-8" id="tableCsindexResultByAgent">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Period</th>
                                    <th class="text-center">Qty survey</th>
                                    <th class="text-center">Q1 point</th>
                                    <th class="text-center">Q2 point</th>
                                    <th class="text-center">Total point</th>
                                    <th class="text-center">% CS ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->