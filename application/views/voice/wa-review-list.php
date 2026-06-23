<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
          $maxScore = (int)$scoreList['high'] * 3;
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
                    <table class="table table-bordered table-sm dataTableBasic">
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
                                    <td class="align-middle"><?= $i++; ?></td>
                                    <td class="align-middle"><?= date("M Y", strtotime($row['period'])); ?></td>
                                    <td class="align-middle text-bold"><?= $row['agent'] ?></td>
                                    <td class="align-middle">
                                        <?= date("d M Y", strtotime($row['datetime'])) ?>
                                        <br>
                                        <?= date("H:i", strtotime($row['datetime'])) ?>
                                    </td>
                                    <td class="align-middle">
                                        <?= $row['customer_phone'] ?>
                                        <br>
                                        <span class="badge badge-secondary font-weight-normal"><?= $row['system_code'] ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <?= itemScoreToColor($row['score_response']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['response_remark'] ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <?= itemScoreToColor($row['score_accuracy']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['accuracy_remark'] ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <?= itemScoreToColor($row['score_wording']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['wording_remark'] ?></span>
                                    </td>
                                    <td class="text-center bg-light align-middle">
                                        <span class="text-bold text-primary"><?= number_format(($row['score_response'] + $row['score_accuracy'] + $row['score_wording']) / $maxScore * 100, 1) ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <?= $row['remark'] ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?= surveyorTag($row['saved_by'], $row['saved_at']) ?>
                                        <div class="btn-group mt-2" style="border: 1px #efefef solid; border-radius: 3px;">
                                            <button type="button" class="btn btn-sm btn-light">
                                                <a href="#" data-id="<?= $row['id'] ?>" data-agent="<?= $row['agent'] ?>" data-datetime="<?= $row['datetime'] ?>" data-customerphone="<?= $row['customer_phone'] ?>" class="buttonWaReviewViewDetail" data-toggle="modal" data-target="#modalWaReviewDetaiChatModal">  <i class="fas fa-search text-primary"></i>
                                                </a>
                                            </button>
                                            <?php if ($allowedAccess) : ?>
                                                <button type="button" class="btn btn-sm btn-light">
                                                    <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewEdit"><i class="fas fa-edit text-dark"></i></a>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light">
                                                    <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewDelete"><i class="fas fa-times text-danger"></i></a> 
                                                </button>
                                            <?php endif; ?>
                                        </div>
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

<!-- Modal View Detail per Row Data -->
<div class="modal fade" id="modalWaReviewDetaiChatModal" tabindex="-1" role="dialog" aria-labelledby="modalWaReviewDetaiChatModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document" style="min-width: 900px;">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalWaReviewDetaiChatModalLabel">Agent's Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Desain Dashboard Agent (Bootstrap 4) -->
                <div class="d-flex flex-column bg-light rounded shadow-sm overflow-hidden" style="height: 75vh; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                    
                    <!-- Header Dashboard -->
                    <div class="p-3 text-white flex-shrink-0" style="background: linear-gradient(135deg, #dc2626, #ef4444); border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 id="detailWaChatCustomerName" class="m-0 font-weight-bold" style="letter-spacing: 0.5px;">Agent Chat View</h6>
                            <span id="detailWaChatDatetime" class="badge badge-dark font-weight-normal px-2 py-1" style="font-size: 14px; background-color: rgba(0,0,0,0.25);">---</span>
                        </div>
                    </div>

                    <!-- Chat History Area -->
                    <div id="detailWaChatConversation" class="flex-grow-1 p-3 overflow-auto d-flex flex-column" style="background-color: #f0f2f5; gap: 14px;">
                    </div>

                    <!-- Footer Status -->
                    <div class="p-2 bg-light border-top flex-shrink-0">
                        <div class="w-100 text-center py-2 text-secondary font-weight-bold bg-white rounded border" style="font-size: 11px; border-color: #dee2e6 !important;">
                            🔒 History Chat
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-dismiss="modal"><i class="fas fa-check"></i> Done</button>
            </div>
        </div>
    </div>
</div>