
<div class="content-wrapper">
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            
            <?php 
                if (!$this->input->post('summaryBestAgentStartPeriod') || !$this->input->post('summaryBestAgentEndPeriod')) {
                    $startPeriod = date("Y-m-01", strtotime("-2 months"));
                    $endPeriod = date("Y-m-01");
                } else {
                    $startPeriod = $this->input->post('summaryBestAgentStartPeriod');
                    $endPeriod = $this->input->post('summaryBestAgentEndPeriod');
                }

            ?>

            <div class="card">
                <div class="card-header bg-primary">
                    <span class="card-title">Summary of Best Agent</span>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 640px;">
                            <label for="summaryBestAgentStartPeriod">Period</label>
                            <input type="date" class="custom-select" name="summaryBestAgentStartPeriod" id="summaryBestAgentStartPeriod" style="width: 160px;" value="<?= $startPeriod ?>">
                            <label for="summaryBestAgentEndPeriod">to</label>
                            <input type="date" class="custom-select" name="summaryBestAgentEndPeriod" id="summaryBestAgentEndPeriod" style="width: 160px;" value="<?= $endPeriod; ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSubmitSummaryBestAgent" name="buttonSubmitSummaryBestAgent">Go</button>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <p class="lead text-indigo"><i class="fas fa-suitcase"></i> Summary of Best Agent Period : <?= date("F Y", strtotime($startPeriod)) ?> - <?= date("F Y", strtotime($endPeriod)) ?></p>
                            <?php if(count($output) < 1) : ?>
                                <p class="lead text-danger ml-4">There were no data or result!</p>
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
                                            <th class="text-center">Total Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach($output as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td><?= $row['agent'] ?></td>
                                                <td class="text-center"><?= number_format($row['productivity'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['smilevoice'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['attendance'], 2); ?></td>
                                                <td class="text-center"><?= number_format($row['elearning'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['teamwork'], 2) ?></td>
                                                <td class="text-center"><?= number_format($row['total_score'], 5) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

