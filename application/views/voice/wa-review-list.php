<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
        ?>
        <div class="container-fluid pt-2 px-1">
            <div class="card">
                <div class="card-header bg-primary">
                    WA Reivew (All Data) periode: <?= date("d F Y", strtotime($startPeriod)) ?> - <?= date("d F Y", strtotime($endPeriod)) ?>
                    <div class="card-tools">
                        <a href="<?= base_url('voice/wareviewform') ?>" class="text-white mr-3"><i class="fas fa-plus-circle"></i> New WA Review</a>
                    </div>
                </div>
                <div class="card-body">                
                    <form action="" class="form-row mb-3" method="post" style="width: 820px;">
                        <label for="waReviewAllStartPeriod" class="col-sm-1">Period</label>
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="waReviewAllStartPeriod" name="waReviewAllStartPeriod" class="form-control" value="<?= $startPeriod ?>">
                        </div>-
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="waReviewAllEndPeriod" name="waReviewAllEndPeriod" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1">
                          <div class="row">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectWaReviewAll" name="buttonSelectWaReviewAll">Go</button>      
                          </div>
                        </div>
                    </form>
                    <div class="border mb-3"></div>
                    <table class="table table-bordered dataTableBasic">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">#</th>
                                <th rowspan="2" class="align-middle">Period</th>
                                <th rowspan="2" class="align-middle">Agent</th>
                                <th rowspan="2" class="align-middle">Datetime</th>
                                <th rowspan="2" class="align-middle">Cust. Phone</th>
                                <th colspan="4" class="align-middle">Review</th>
                                <th rowspan="2" class="align-middle">Remark</th>
                                <th rowspan="2" class="align-middle">...</th>
                            </tr>
                            <tr>
                                <th>Response</th>
                                <th>Accuracy</th>
                                <th>Wording</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($waReviewList as $row) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= date("M Y", strtotime($row['period'])); ?></td>
                                    <td><?= $row['agent'] ?></td>
                                    <td>
                                        <?= date("d M Y", strtotime($row['datetime'])) ?>
                                        <br>        
                                    </td>
                                    <td><?= $row['customer_phone'] ?></td>
                                    <td>
                                        <?= $row['score_response'] ?>
                                    </td>
                                    <td>
                                        <?= $row['score_accuracy'] ?>
                                    </td>
                                    <td>
                                        <?= $row['score_wording'] ?>
                                    </td>
                                    <td>
                                        <?= number_format(($row['score_response'] + $row['score_accuracy'] + $row['score_wording']) / 15 * 100, 1) ?>
                                    </td>
                                    <td>
                                        <?= $row['remark'] ?>
                                    </td>
                                    <td>
                                        ...
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>