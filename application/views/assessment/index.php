<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- Header baru -->
            <?php 
                if (!$this->input->post('selectSummaryAssessmentStartPeriod') || !$this->input->post('selectSummaryAssessmentEndPeriod')) {
                    if( (int)date("m") > 4 && (int)date("m") < 10 ) {
                        $startPeriod = date("Y-04-01");
                        $endPeriod = date("Y-09-01");
                    } else {
                        $startPeriod = date("Y-10-01", strtotime("-1 year"));
                        $endPeriod = date("Y-03-01");
                    }                
                } else {
                    $startPeriod = $this->input->post('selectSummaryAssessmentStartPeriod');
                    $endPeriod = $this->input->post('selectSummaryAssessmentEndPeriod');
                }

            ?>
            
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Summary of Contact Center's KPI Result - <?= $fiscal ?></h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 80px;">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 440px;">
                            <label for="selectSummaryAssessmentStartPeriod">Period</label>
                            <input type="date" class="custom-select" name="selectSummaryAssessmentStartPeriod" id="selectSummaryAssessmentStartPeriod" style="width: 140px;" value="<?= $startPeriod ?>">
                            <label for="selectSummaryAssessmentEndPeriod">to</label>
                            <input type="date" class="custom-select" name="selectSummaryAssessmentEndPeriod" id="selectSummaryAssessmentEndPeriod" style="width: 140px;" value="<?= $endPeriod; ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryAssessment" name="buttonSelectSummaryAssessment">Go</button>
                        </form>
                    </div>

                     <!-- <?php echo "<pre>"; print_r($forKpiMeasurement) ?>   -->

                    <div class="row">
                        <div class="col-10">
                            <h6 class="text-bold text-primary">SEID (Permanent & Contract)</h6>
                            <table class="table table-sm" id="tableKpiResultSummarySeid">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <!-- <th>CTI ID</th> -->
                                        <th>NPK</th>
                                        <th>Fullname</th>                                        
                                        <th>Result (%)</th>
                                        <th>KPI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php $jobcodeListSeid = ['cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc20', 'cs-ccc-cc30', 'cs-ccc-cc50']; ?>
                                    <?php foreach ($kpiResultSummary as $row): ?>
                                        <?php if ($row['status'] == 'Permanent' || $row['status'] == 'Contract') : ?>
                                            <?php if (in_array($row['jobcode'], $jobcodeListSeid)) : ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <!-- <td><?= $row['agent'] ?></td> -->
                                                    <td><?= $row['npk'] ?></td>
                                                    <td><?= $row['fullname'] ?></td>
                                                    <td><?= round($row['kpi'], 2) ?></td>
                                                    <td><?= $row['kpi_result'] ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-10">
                            <h6 class="text-bold text-primary">OTS</h6>
                            <table class="table table-sm" id="tableKpiResultSummaryOts">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <!-- <th>CTI ID</th> -->
                                        <th>NPK</th>
                                        <th>Fullname</th>                                        
                                        <th>Result (%)</th>
                                        <th>KPI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php $jobcodeListOts = ['cs-ccc-cc10', 'cs-ccc-cc11', 'cs-ccc-cc12', 'cs-ccc-cc20', 'cs-ccc-cc30', 'cs-ccc-cc50']; ?>
                                    <?php foreach ($kpiResultSummary as $row): ?>
                                        <?php if ($row['status'] == 'OTS') : ?>
                                            <?php if (in_array($row['jobcode'], $jobcodeListOts)) : ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <!-- <td><?= $row['agent'] ?></td> -->
                                                    <td><?= $row['npk'] ?></td>
                                                    <td><?= $row['fullname'] ?></td>
                                                    <td><?= round($row['kpi'], 2) ?></td>
                                                    <td><?= $row['kpi_result'] ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
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

