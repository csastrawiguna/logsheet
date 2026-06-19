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
            <div class="card card-primary">
                <div class="card-header">
                    <span class="h6">Summary of Agent's WA Reply Review</span>
                </div>
                <div class="card-body">
                    <form action="" class="form-row mb-3" method="post" style="width: 680px;">
                        <label for="wareviewSummaryDateStart" class="col-sm-1">Period</label>
                        <div class="col-sm-3">
                            <input type="date" id="wareviewSummaryDateStart" name="wareviewSummaryDateStart" class="form-control" value="<?= $startPeriod ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="date" id="wareviewSummaryDateEnd" name="wareviewSummaryDateEnd" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSubmitwareviewSummary" name="buttonSubmitwareviewSummary">Go</button>
                        </div>
                    </form>

                    <!-- Summary All -->
                    <div class="row mt-5">
                        <div class="col-md-10">
                            <p class="h5 text-indigo mb-3"><i class="far fa-file-alt"></i> Summary All</p>
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="row">
                                        <th class="text-center col-sm-2">Item</th>
                                        <th class="col-sm-3">Description</th>
                                        <th class="col-sm-5">Achievement/Result</th>
                                        <th class="col-sm-2">Average</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="row">
                                        <td class="text-center align-middle col-sm-2">
                                            <span class="h3"><i class="far fa-clock"></i></span>
                                            <br>
                                            Response
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            Average response time between customer chat and agent's reply
                                        </td>
                                        <td class="align-middle col-sm-6">
                                            <div class="row">
                                                <?= wa2bars('Good', number_format(($wareviewSummaryAllTotal['score_response_5'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_response_5'], 'success') ?>
                                                <?= wa2bars('Flat', number_format(($wareviewSummaryAllTotal['score_response_3'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_response_3'], 'warning') ?>
                                                <?= wa2bars('Bad', number_format(($wareviewSummaryAllTotal['score_response_1'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_response_1'], 'danger') ?>
                                            </div>
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_response'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_response'], 1), 5) ?>
                                        </td>
                                    </tr>
                                    <tr class="row">
                                        <td class="text-center align-middle col-sm-2">
                                            <span class="h3"><i class="fas fa-check"></i></span>
                                            <br>
                                            Accuracy
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            Accuracy of information delivered to customer
                                        </td>
                                        <td class="align-middle col-sm-6">
                                            <div class="row">
                                                <?= wa2bars('Good', number_format(($wareviewSummaryAllTotal['score_accuracy_5'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_accuracy_5'], 'success') ?>
                                                <?= wa2bars('Flat', number_format(($wareviewSummaryAllTotal['score_accuracy_3'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_accuracy_3'], 'warning') ?>
                                                <?= wa2bars('Bad', number_format(($wareviewSummaryAllTotal['score_accuracy_1'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_accuracy_1'], 'danger') ?>
                                            </div>
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_accuracy'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_accuracy'], 1), 5) ?>
                                        </td>
                                    </tr>
                                    <tr class="row">
                                        <td class="text-center align-middle col-sm-2">
                                            <span class="h3"><i class="fas fa-spell-check"></i></span>
                                            <br>
                                            Wording
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            Clear sentences and unambiguous
                                        </td>
                                        <td class="align-middle col-sm-6">
                                            <div class="row">
                                                <?= wa2bars('Good', number_format(($wareviewSummaryAllTotal['score_wording_5'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_wording_5'], 'success') ?>
                                                <?= wa2bars('Flat', number_format(($wareviewSummaryAllTotal['score_wording_3'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_wording_3'], 'warning') ?>
                                                <?= wa2bars('Bad', number_format(($wareviewSummaryAllTotal['score_wording_1'] / $wareviewSummaryAllTotal['qty']) * 100, 1) . '%', $wareviewSummaryAllTotal['score_wording_1'], 'danger') ?>
                                            </div>
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_wording'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_wording'], 1), 5) ?>
                                        </td>
                                    </tr>
                                    <tr class="row">
                                        <td class="align-middle col-sm-10 text-center">
                                            <p class="align-middle">Total</p>
                                        </td>
                                        <td class="align-middle col-sm-2">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_total'] * 100) / 15, 0), number_format($wareviewSummaryAllTotal['avg_total'], 1), 15) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Monthly Transition -->
                    <div class="row mt-4">
                        <div class="col-sm-10">
                            <p class="h5 text-indigo mb-3"><i class="far fa-calendar-alt"></i> Monthly Transition</p>
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="align-middle">#</th>
                                        <th class="align-middle"><i class="far fa-calendar-alt"></i><br>Month</th>
                                        <th class="align-middle"><i class="far fa-file-alt"></i><br>Qty</th>
                                        <th class="align-middle"><i class="far fa-clock"></i><br>Response</th>
                                        <th class="align-middle"><i class="fas fa-check"></i><br>Accuracy</th>
                                        <th class="align-middle"><i class="fas fa-spell-check"></i><br>Wording</th>
                                        <th class="align-middle"><i class="far fa-clipboard"></i><br>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($wareviewSummaryAllTransition as $row) : ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $i++ ?></td>
                                            <td class="align-middle"><?= date("M Y", strtotime($row['period'])) ?></td>
                                            <td class="text-center align-middle"><?= $row['qty'] ?></td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_response'] * 100) / 5, 0), number_format($row['avg_score_response'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_accuracy'] * 100) / 5, 0), number_format($row['avg_score_accuracy'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_wording'] * 100) / 5, 0), number_format($row['avg_score_wording'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_total'] * 100) / 15, 0), number_format($row['avg_total'], 1), 15) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="text-bold text-center">
                                        <td class="align-middle" colspan="2">Total</td>
                                        <td class="align-middle">
                                            
                                        </td>
                                        <td class="align-middle">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_response'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_response'], 1), 5) ?>
                                        </td>
                                        <td class="align-middle">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_accuracy'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_accuracy'], 1), 5) ?>
                                        </td>
                                        <td class="align-middle">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_score_wording'] * 100) / 5, 0), number_format($wareviewSummaryAllTotal['avg_score_wording'], 1), 5) ?>
                                        </td>
                                        <td class="align-middle">
                                            <?= wa2barslite(number_format(($wareviewSummaryAllTotal['avg_total'] * 100) / 15, 0), number_format($wareviewSummaryAllTotal['avg_total'], 1), 15) ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     

                    <!-- Summary by Agent -->
                    <div class="row mt-4">
                        <div class="col-md-10">
                            <p class="h5 text-indigo mb-3"><i class="fas fa-user-friends"></i> Summary By Agent</p>
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="align-middle">#</th>
                                        <th class="align-middle"><i class="far fa-user"></i><br>Agent</th>
                                        <th class="align-middle"><i class="far fa-file-alt"></i><br>Qty</th>
                                        <th class="align-middle"><i class="far fa-clock"></i><br>Response</th>
                                        <th class="align-middle"><i class="fas fa-check"></i><br>Accuracy</th>
                                        <th class="align-middle"><i class="fas fa-spell-check"></i><br>Wording</th>
                                        <th class="align-middle"><i class="far fa-clipboard"></i><br>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($wareviewSummaryAllByPeriod as $row) : ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $i++ ?></td>
                                            <td class="align-middle"><?= $row['agent'] ?></td>
                                            <td class="text-center align-middle"><?= $row['qty'] ?></td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_response'] * 100) / 5, 0), number_format($row['avg_score_response'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_accuracy'] * 100) / 5, 0), number_format($row['avg_score_accuracy'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_score_wording'] * 100) / 5, 0), number_format($row['avg_score_wording'], 1), $row['qty']) ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?= wa2barslite(number_format(($row['avg_total'] * 100) / 15, 0), number_format($row['avg_total'], 1), 15) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Not Good Findings List -->
                    <div class="row mt-4">
                        <div class="col">
                            <p class="h5 text-indigo mb-3"><i class="far fa-thumbs-down"></i> Not Good Findings List</p>
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
                                    <?php foreach($wareviewUnproperAll as $row) : ?>
                                        <tr>
                                            <td class="align-middle"><?= $i++; ?></td>
                                            <td class="align-middle"><?= date("M Y", strtotime($row['period'])); ?></td>
                                            <td class="align-middle text-bold"><?= $row['agent'] ?></td>
                                            <td class="align-middle">
                                                <?= date("d M Y", strtotime($row['datetime'])) ?>
                                                <br>
                                                <?= date("H:i", strtotime($row['datetime'])) ?>
                                            </td>
                                            <td class="">
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>