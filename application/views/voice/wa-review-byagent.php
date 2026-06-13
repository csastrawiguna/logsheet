<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
            require 'function-voice.php'; 
            $maxScore = (int)$scoreList['high'] * 3;
        ?>
        <div class="container-fluid pt-3">
            <div class="card">
                <div class="card-header bg-primary">
                    WA Reply Review by agent
                </div>
                <div class="card-body">        
                    <div class="row">
                        <div class="col-sm">
                            <form action="" class="form-row" method="post" style="width: 680px;">
                                <label for="wareviewByAgentSelectAgent" class="col-sm-1">Agent</label>
                                <div class="col-sm-2">
                                    <select id="wareviewByAgentSelectAgent" name="wareviewByAgentSelectAgent" class="custom-select">
                                        <?php if($allowedAccess ): ?>
                                            <option value="<?= $agent; ?>"><?= $agent; ?></option>
                                            <?php foreach ($allAgent as $ag): ?>
                                                <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option><?= $this->session->userdata('user_id'); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-sm-1"></div>
                                <label for="wareviewByAgentDateStart" class="col-sm-1">Period</label>
                                <div class="col-sm-3">
                                    <input type="date" id="wareviewByAgentDateStart" name="wareviewByAgentDateStart" class="form-control" value="<?= $startPeriod ?>">
                                </div>
                                <div class="col-sm-3">
                                    <input type="date" id="wareviewByAgentDateEnd" name="wareviewByAgentDateEnd" class="form-control" value="<?= $endPeriod ?>">
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-outline-primary" id="buttonSubmitwareviewByAgent" name="buttonSubmitwareviewByAgent">Go</button>
                                </div>
                            </form>                    
                        </div>
                    </div>
                    <!-- summary by agent  -->
                    <div class="row mt-5">
                        <div class="col-10">
                            <p class="h5 text-indigo mb-3"><i class="far fa-file-alt"></i> Summary by period</p>
                            <table class="table table-sm table-hover table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center align-middle">#</th>
                                        <th class="text-center align-middle">Month</th>
                                        <th class="text-center align-middle"><i class="fas fa-file-alt"></i></th>
                                        <th class="text-center"><i class=" fas fa-clock"></i><br>Response</th>
                                        <th class="text-center"><i class="fas fa-check"></i><br>Accuracy</th>
                                        <th class="text-center"><i class="fas fa-spell-check"></i><br>Wording</th>
                                        <th class="text-center align-middle">Total/Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($wareviewSummaryByAgent as $row): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $i++ ?></td>
                                            <td class="align-middle"><?= date("M Y", strtotime($row['period'])) ?></td>
                                            <td class="text-center align-middle"><?= $row['qty'] ?></td>
                                            <td class="align-middle">
                                               <?= wa2barslite(number_format(($row['avg_score_response'] / 5 * 100), 1), number_format($row['avg_score_response'], 1), 5) ?>
                                            </td>
                                            <td class="">
                                               <?= wa2barslite(number_format(($row['avg_score_accuracy'] / 5 * 100), 1), number_format($row['avg_score_accuracy'], 1), 5) ?>
                                            </td>
                                            <td class="">
                                                <?= wa2barslite(number_format(($row['avg_score_wording'] / 5 * 100), 1), number_format($row['avg_score_wording'], 1), 5) ?>
                                            </td>
                                            <td class="">
                                                <?= wa2barstotal(number_format(($row['avg_total'] / 15 * 100), 1), $row['qty']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Bad finding lists -->
                    <div class="row mt-4">
                      <div class="col-11">
                         <p class="h5 text-indigo mb-3"><i class="far fa-thumbs-down"></i> Bad Findings List</p>
                         <?php if(count($wareviewUnproperByAgent) == 0 ) : ?>
                            <p class="lead font-italic"> <i class="far fa-smile-beam  ml-3"></i>  there were no bad findings during those period</p>
                         <?php else : ?>
                            <table class="table table-bordered dataTableBasic">
                                <thead class="thead-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">#</th>
                                        <th rowspan="2" class="align-middle">Period</th>
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
                                    <?php foreach($wareviewUnproperByAgent as $row) : ?>
                                        <tr>
                                            <td class="align-middle"><?= $i++; ?></td>
                                            <td class="align-middle"><?= date("M Y", strtotime($row['period'])); ?></td>
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
                                            <td class="align-middle">
                                                <a href="#" data-id="<?= $row['id'] ?>" data-agent="<?= $row['agent'] ?>" data-datetime="<?= $row['datetime'] ?>" data-customerphone="<?= $row['customer_phone'] ?>" class="buttonWaReviewViewDetail" data-toggle="modal" data-target="#modalWaReviewDetaiChatModal">  <i class="fas fa-search text-info"></i>
                                                </a> 
                                                <?= surveyorTag($row['saved_by'], $row['saved_at']) ?>   
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
                         <?php endif; ?>
                      </div>
                    </div>

                    <!-- detail WA Review List -->
                    <div class="row mt-4">
                        <div class="col">
                            <p class="h5 text-indigo"><i class="fas fa-list"></i> Detail WA Reply Review</p>
                            <table class="table table-bordered dataTableBasic">
                                <thead class="thead-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">#</th>
                                        <th rowspan="2" class="align-middle">Period</th>
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
                                    <?php foreach($wareviewListByAgent as $row) : ?>
                                        <tr>
                                            <td class="align-middle"><?= $i++; ?></td>
                                            <td class="align-middle"><?= date("M Y", strtotime($row['period'])); ?></td>
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
                                            <td class="align-middle">
                                                <a href="#" data-id="<?= $row['id'] ?>" data-agent="<?= $row['agent'] ?>" data-datetime="<?= $row['datetime'] ?>" data-customerphone="<?= $row['customer_phone'] ?>" class="buttonWaReviewViewDetail" data-toggle="modal" data-target="#modalWaReviewDetaiChatModal">  <i class="fas fa-search text-info"></i>
                                                </a> 
                                                 | <?= surveyorTag($row['saved_by'], $row['saved_at']) ?>   
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
            </div>
        </div><!-- /.container-fluid -->
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