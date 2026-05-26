<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <div class="title">
                                <h6>Target setting for agent's KPI</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card card-light collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title text-primary">Customer Assistant</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                            <?php foreach ($allTarget as $at) : ?>
                                                <?php if ($at['jobcode'] == 'cs-ccc-cc01') : ?>
                                                    <tr class="row">
                                                        <td class="col-5"><?= $at['item']; ?></td>
                                                        <td class="col-5">
                                                            <?php if ($at['target'] < 1) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1" step="0.01" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 20) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="20" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 30) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="30" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 50) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="50" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 100) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="100" step="5" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 500) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="500" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 1000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1000" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 3000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="3000" step="10" style="width: 100%;">
                                                            <?php } else { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="5000" step="10" style="width: 100%;">
                                                            <?php }; ?>
                                                        </td>
                                                        <td class="col-2">
                                                            <?php if ($at['target'] < 1) {
                                                                echo  $at['target'] * 100 . '%';
                                                            } else {
                                                                echo  (int)$at['target'];
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-light collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title text-primary">Customer Assistant (less than 12 months)</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                            <?php foreach ($allTarget as $at) : ?>
                                                <?php if ($at['jobcode'] == 'cs-ccc-cc11') : ?>
                                                    <tr class="row">
                                                        <td class="col-5"><?= $at['item']; ?></td>
                                                        <td class="col-5">
                                                            <?php if ($at['target'] < 1) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1" step="0.01" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 20) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="20" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 30) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="30" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 50) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="50" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 100) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="100" step="5" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 500) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="500" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 1000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1000" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 3000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="3000" step="10" style="width: 100%;">
                                                            <?php } else { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="5000" step="10" style="width: 100%;">
                                                            <?php }; ?>
                                                        </td>
                                                        <td class="col-2">
                                                            <?php if ($at['target'] < 1) {
                                                                echo  $at['target'] * 100 . '%';
                                                            } else {
                                                                echo  (int)$at['target'];
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-light collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title text-primary">Product Assistant</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                            <?php foreach ($allTarget as $at) : ?>
                                                <?php if ($at['jobcode'] == 'cs-ccc-cc02') : ?>
                                                    <tr class="row">
                                                        <td class="col-5"><?= $at['item']; ?></td>
                                                        <td class="col-5">
                                                            <?php if ($at['target'] < 1) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1" step="0.01" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 20) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="20" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 30) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="30" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 50) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="50" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 100) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="100" step="5" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 500) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="500" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 1000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1000" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 3000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="3000" step="10" style="width: 100%;">
                                                            <?php } else { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="5000" step="10" style="width: 100%;">
                                                            <?php }; ?>
                                                        </td>
                                                        <td class="col-2">
                                                            <?php if ($at['target'] < 1) {
                                                                echo  $at['target'] * 100 . '%';
                                                            } else {
                                                                echo  (int)$at['target'];
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-light collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title text-primary">Spare Part Specialist</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                            <?php foreach ($allTarget as $at) : ?>
                                                <?php if ($at['jobcode'] == 'cs-ccc-cc03') : ?>
                                                    <tr class="row">
                                                        <td class="col-5"><?= $at['item']; ?></td>
                                                        <td class="col-5">
                                                            <?php if ($at['target'] < 1) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1" step="0.01" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 20) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="20" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 30) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="30" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 50) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="50" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 100) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="100" step="5" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 500) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="500" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 1000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1000" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 3000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="3000" step="10" style="width: 100%;">
                                                            <?php } else { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="5000" step="10" style="width: 100%;">
                                                            <?php }; ?>
                                                        </td>
                                                        <td class="col-2">
                                                            <?php if ($at['target'] < 1) {
                                                                echo  $at['target'] * 100 . '%';
                                                            } else {
                                                                echo  (int)$at['target'];
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-light collapsed-card">
                                <div class="card-header">
                                    <h3 class="card-title text-primary">Complaint Specialist</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                            <?php foreach ($allTarget as $at) : ?>
                                                <?php if ($at['jobcode'] == 'cs-ccc-cc05') : ?>
                                                    <tr class="row">
                                                        <td class="col-5"><?= $at['item']; ?></td>
                                                        <td class="col-5">
                                                            <?php if ($at['target'] < 1) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1" step="0.01" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 20) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="20" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 30) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="30" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 50) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="50" step="1" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 100) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="100" step="5" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 500) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="500" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 1000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="1000" step="10" style="width: 100%;">
                                                            <?php } else if ($at['target'] <= 3000) { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="3000" step="10" style="width: 100%;">
                                                            <?php } else { ?>
                                                                <input type="range" value="<?= $at['target']; ?>" min="0" max="5000" step="10" style="width: 100%;">
                                                            <?php }; ?>
                                                        </td>
                                                        <td class="col-2">
                                                            <?php if ($at['target'] < 1) {
                                                                echo  $at['target'] * 100 . '%';
                                                            } else {
                                                                echo  (int)$at['target'];
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form action="" class="form" id="formSetMaxLeavePerDay" method="POST">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <div class="title">
                                    <h6 class="h6">Daily leave quota</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="settingMaxLeaveDaily" class="col-sm-8 col-form-label">Maximum staff leave per day</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control text-center" id="settingMaxLeaveDaily" name="settingMaxLeaveDaily" value="<?= $maxLeavePerDay; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-outline-primary" id="buttonSettingMaxLeaveDaily" name="buttonSettingMaxLeaveDaily">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->