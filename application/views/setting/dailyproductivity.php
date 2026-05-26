<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-6" style="max-width: 480px">
                    <form id="formProductivityDailyTargetSetting" method="post" action="">
                        <div class="card">
                            <div class="card-header bg-primary">
                                Target Produktivitas Harian
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <thead class="">
                                        <tr>
                                            <th class="col-sm-8">Jobdesk</th>
                                            <th class="col-sm-4 text-center">Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allProductivityDailyTarget as $row): ?>
                                            <tr>
                                                <td><?= $row['jobdesk'] ?></td>
                                                <td class="">
                                                    <input type="number" name="<?= 'target_' . $row['jobcode'] ?>" class="form-control text-center" value="<?= $row['target'] ?>">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-save"></i>&nbspSave&nbsp</button>
                                <a href="<?= base_url('productivity/daily'); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

