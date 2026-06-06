<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
          $maxScore = (int)$scoreList['high'] * 3;
          $allowedAccess = in_array($this->session->userdata('role_access'), ['1', '5', '7', '9'])
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
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" class="align-middle">#</th>
                                <th rowspan="2" class="align-middle">Period</th>
                                <th rowspan="2" class="align-middle">Agent</th>
                                <th rowspan="2" class="align-middle">Datetime</th>
                                <th rowspan="2" class="align-middle">Cust. Phone</th>
                                <th colspan="4" class="align-middle text-center">Review</th>
                                <th rowspan="2" class="align-middle">Remark</th>
                                <th rowspan="2" class="align-middle">...</th>
                            </tr>
                            <tr>
                                <th>Response</th>
                                <th>Accuracy</th>
                                <th>Wording</th>
                                <th class="text-center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($waReviewList as $row) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= date("M Y", strtotime($row['period'])); ?></td>
                                    <td class="bg-light text-bold"><?= $row['agent'] ?></td>
                                    <td>
                                        <?= date("d M Y", strtotime($row['datetime'])) ?>
                                        <br>
                                        <?= date("H:i", strtotime($row['datetime'])) ?>
                                    </td>
                                    <td class="">
                                        <?= $row['customer_phone'] ?>
                                        <br>
                                        <span class="badge badge-secondary font-weight-normal"><?= $row['system_code'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_response']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['response_remark'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_accuracy']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['accuracy_remark'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_wording']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['wording_remark'] ?></span>
                                    </td>
                                    <td class="text-center bg-light">
                                        <span class="text-bold text-primary"><?= number_format(($row['score_response'] + $row['score_accuracy'] + $row['score_wording']) / $maxScore * 100, 1) ?></span>
                                    </td>
                                    <td>
                                        <?= $row['remark'] ?>
                                    </td>
                                    <td>
                                        <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewDetail"><i class="fas fa-search text-info"></i></a> 
                                        <?php if ($allowedAccess) : ?>
                                            <br>
                                            <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewEdit"><i class="fas fa-edit text-secondary"></i></a>  &nbsp; 
                                            <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewDelete"><i class="fas fa-times text-danger"></i></a> 
                                        <?php endif; ?>
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