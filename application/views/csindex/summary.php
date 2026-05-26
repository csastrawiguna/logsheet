<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>

            <!-- <div class="row">
                <div class="col text-center" style="margin-top: 30vh;;">
                    <h3 class="h3 text-warning">Coming soon</h3>
                </div>
            </div> -->
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="">KPI for CAC (Customer Assistant Center</div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <form class="form-inline" action="" method="post">
                                <div class="form-group">
                                    <label for="csindexSummaryStartPeriod">Periode</label>
                                    <input type="date" id="csindexSummaryStartPeriod" name="csindexSummaryStartPeriod" class="form-control mx-sm-1" style="min-width: 130px; max-width: 140px;" value="<?= $startPeriod ?>" title="Start period">-
                                    <input type="date" id="csindexSummaryEndPeriod" name="csindexSummaryEndPeriod" class="form-control mx-sm-1" style="min-width: 130px; max-width: 140px;" value="<?= $endPeriod ?>" title="End period">
                                    <button type="submit" class="btn btn-outline-primary">Go</button>
                                </div>
                            </form>
                        </div>      
                    </div>
                    <div class="row p-2">
                        <div class="col-8">
                            <h6 class="text-primary">RESULT</h6>
                            <div id="myChart">
                                <canvas id="csindexSummaryChart" height="100" width="240"></canvas>
                            </div>
                        </div>
                        <div class="col-4">
                            <table class="table table-sm table-bordered ml-2">
                                <thead>
                                    <tr class="text-center">
                                        <th class="col-sm-2 align-middle">Period</th>
                                        <th class="col-sm-1 align-middle">KPI</th>
                                        <th class="col-sm-1 align-middle">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary as $sm) : ?>
                                        <tr>
                                            <td class="text-center"><?= date("M-Y", strtotime($sm['period'])); ?></td>
                                            <td class="text-center text-bold"><?= round($sm['total_result'], 3) * 100; ?>%</td>
                                            <?php if ($sm['total_result'] >= 0.82) : ?>
                                                <td class="text-center h5"><span class="text-primary lnr lnr-smile"></span></td>
                                            <?php else : ?>
                                                <td class="text-center h5"><span class="text-danger lnr lnr-sad"></span></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-8">
                            <h6 class="text-primary">Q1. The Customer Impression to CCC's Agent Service</h6>
                            <table class="table table-bordered table-sm ml-2">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle col-sm-2">Period</th>
                                        <th colspan="5" class="">Rating</th>
                                        <th rowspan="2" class="col-sm-1 align-middle">Total</th>
                                        <th rowspan="2" class="col-sm-1 align-middle">KPI</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="col-sm-1 align-middle">Very good</th>
                                        <th class="col-sm-1 align-middle">Good</th>
                                        <th class="col-sm-1 align-middle">Fairly good</th>
                                        <th class="col-sm-1 align-middle">Not very good</th>
                                        <th class="col-sm-1 align-middle">Bad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary as $sm) : ?>
                                        <tr>
                                            <td class="text-center"><?= date("M-Y", strtotime($sm['period'])); ?></td>
                                            <td class="text-center"><?= $sm['q1_3']; ?></td>
                                            <td class="text-center"><?= $sm['q1_2']; ?></td>
                                            <td class="text-center"><?= $sm['q1_1']; ?></td>
                                            <td class="text-center"><?= $sm['q1__1']; ?></td>
                                            <td class="text-center"><?= $sm['q1__2']; ?></td>
                                            <td class="text-center"><?= $sm['q1_qty']; ?></td>
                                            <td class="text-center text-bold"><?= round($sm['q1_result'], 3) * 100; ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-8">
                            <h6 class="text-primary">Q2. The Customer Impression to CCC's staff Manners and Kindness</h6>
                            <table class="table table-sm table-bordered ml-2">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle col-sm-2">Period</th>
                                        <th colspan="5" class="">Rating</th>
                                        <th rowspan="2" class="col-sm-1 align-middle">Total</th>
                                        <th rowspan="2" class="col-sm-1 align-middle">KPI</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="col-sm-1 align-middle">Very kind</th>
                                        <th class="col-sm-1 align-middle">Kind</th>
                                        <th class="col-sm-1 align-middle">Fairly kind</th>
                                        <th class="col-sm-1 align-middle">Not very kind</th>
                                        <th class="col-sm-1 align-middle">Bad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary as $sm) : ?>
                                        <tr>
                                            <td class="text-center"><?= date("M-Y", strtotime($sm['period'])); ?></td>
                                            <td class="text-center"><?= $sm['q2_3']; ?></td>
                                            <td class="text-center"><?= $sm['q2_2']; ?></td>
                                            <td class="text-center"><?= $sm['q2_1']; ?></td>
                                            <td class="text-center"><?= $sm['q2__1']; ?></td>
                                            <td class="text-center"><?= $sm['q2__2']; ?></td>
                                            <td class="text-center"><?= $sm['q2_qty']; ?></td>
                                            <td class="text-center text-bold"><?= round($sm['q2_result'], 3) * 100; ?>%</td>
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
<!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->