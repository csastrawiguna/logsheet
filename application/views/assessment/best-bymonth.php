
<div class="content-wrapper">
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            
            <?php 

            ?>
            
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="card-title">Result of Best Agent by Month</span>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 640px;">
                            <label for="selectMonthyBestByMonth">Period</label>
                            <input type="date" class="custom-select" name="selectMonthyBestByMonth" id="selectMonthyBestByMonth" style="width: 160px;" value="<?= $selectPeriod ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryAssessment" name="buttonSelectByAgentNew">Go</button>
                        </form>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-8">
                            <p class="lead text-indigo"><i class="fas fa-layer-group"></i> Source data</p>
                            <?php if(count($sourceByMonth) < 1) : ?>
                                <p class="lead ml-4">There were no data or all items not completed yet</p>
                                <a href="#" class="ml-4" data-toggle="modal" data-target="#sourceDataCheck"><button class="btn btn-outline-primary">Check source data</button></a>
                            <?php else : ?>
                                <table class="table table-sm table-bordered ml-4">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="text-center">#</th>
                                            <th>Agent</th>
                                            <th class="text-center">Prod/hour</th>
                                            <th class="text-center">CS index</th>
                                            <th class="text-center">Attendance</th>
                                            <th class="text-center">Elearning Score</th>
                                            <th class="text-center">Teamwork by AUX</th>
                                            <?php  ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($sourceByMonth as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td><?= $row['agent'] ?></td>
                                                <td class="text-center"><?= number_format($row['prod_hour'], 1) ?></td>
                                                <td class="text-center"><?= number_format($row['csindex_ratio'] *100, 1) ?>%</td>
                                                <td class="text-center"><?= number_format($row['attendance'] * 100, 1); ?>%</td>
                                                <td class="text-center"><?= number_format($row['elearning_score'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['auxratio'] * 100, 1) ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-10">
                            <p class="lead text-indigo"><i class="fas fa-suitcase"></i> Result of Best Agent on <?= date("F Y", strtotime($selectPeriod)) ?></p>
                            <?php if(count($resultByMonth) < 1) : ?>
                                <p class="lead ml-4">There were no result to be displayed</p>
                                <a href="<?= base_url() . 'assessment/processbymonth/' . $selectPeriod ?>" class="ml-4"><button class="btn btn-outline-primary">Proccess / Calculate Result</button></a>
                            <?php else: ?>
                                <table class="table table-sm table-bordered ml-4">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="text-center align-middle">#</th>
                                            <th class="align-middle">Agent</th>
                                            <th class="text-center align-middle">Productivity<br>30%</th>
                                            <th class="text-center align-middle">Smile voice<br>20%</th>
                                            <th class="text-center align-middle">Attendance<br>20%</th>
                                            <th class="text-center align-middle">Elearning<br>15%</th>
                                            <th class="text-center align-middle">Teamwork<br>15%</th>
                                            <th class="text-center align-middle">Total score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($resultByMonth as $row) :  ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td><?= $row['agent'] ?></td>
                                                <td class="text-center">
                                                    <?= number_format($row['productivity_result'], 1) ?>
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['productivity_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['smilevoice_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['smilevoice_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['attendance_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['attendance_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['elearning_result'], 2) ?>
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['elearning_score']) ?>)</span>
                                                </td>
                                                <td class="text-center">
                                                    <?= number_format($row['teamwork_result'] * 100, 1) ?>%
                                                    &nbsp;<span class="text-primary">(<?= number_format($row['teamwork_score']) ?>)</span>
                                                </td>
                                                <td class="text-center text-primary text-bold"><?= number_format($row['total_score'], 4) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <a href="<?= base_url() . 'assessment/resetBestAgentResult/' . $selectPeriod ?>" class="ml-4 mt-4" id="linkResetResultBestAgent"><button class="btn btn-danger" id="buttonResetResultBestAgent">Reset Result</button></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="sourceDataCheck" tabindex="-1" role="dialog" aria-labelledby="sourceDataCheck" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sourceDataCheck">Check rows data : <span class="text-primary"><?= date("F Y",strtotime($selectPeriod)) ?></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Source items</th>
                            <th class="text-center">Numbers of record</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Productivity</td>
                            <td class="text-center"><?= $checkSourceData['productivity'] ?></td>
                        </tr>
                        <tr>
                            <td>Smile voice (CS index)</td>
                            <td class="text-center"><?= $checkSourceData['csindex'] ?></td>
                        </tr>
                        <tr>
                            <td>Attendance</td>
                            <td class="text-center"><?= $checkSourceData['attendance'] ?></td>
                        </tr>
                        <tr>
                            <td>Elearning test score</td>
                            <td class="text-center"><?= $checkSourceData['elearning'] ?></td>
                        </tr>
                        <tr>
                            <td>Teamwork (AUX data)</td>
                            <td class="text-center"><?= $checkSourceData['teamwork'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



