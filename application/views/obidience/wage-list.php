<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-1 pt-2">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="card">
                <div class="card-header bg-primary">
                    Wages
                </div>
                <div class="card-body">
                    <p class="h6 text-primary mb-3">Wages list <?= date("Y") ?></p>
                    <table class="table table-sm table-bordered col-6">
                        <thead>
                            <tr class="bg-light">
                                <th>#</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th class="text-center">Update at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($wages as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $row['user_id'] ?></td>
                                    <td class=""><?= number_format($row['wage']) ?></td>
                                    <td class="text-center text-secondary"><?= date("d-M-Y H:i", strtotime($row['updated_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
