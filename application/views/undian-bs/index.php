<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-0 pt-3">
        <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                if(!$this->input->post()) {
                    $inputStartPeriod = date("Y-m-01", strtotime("-1 months"));
                    $inputEndPeriod = date("Y-m-d");
                } else {
                    $inputStartPeriod = $this->input->post('obidienceSummaryDateStart');
                    $inputEndPeriod = $this->input->post('obidienceSummaryDateEnd');
                }
            ?>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-primary">
                        Form Regist
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="h5 my-5">Summary of Overtime Incompliance period <span class="text-primary"><?= date("F Y", strtotime($inputStartPeriod)) ?></span> to <span class="text-primary"><?= date("F Y", strtotime($inputEndPeriod)) ?></span></h5>
                                <div class="row">
                                    <div class="col-3">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Agent</th>
                                                    <th class="text-center">TTL Incompliance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($summaryObidienceByPeriod as $data) : ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= $data['agent']; ?></td>
                                                        <td class="text-center"><?= $data['incompliance']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 ml-5">
                                        <div id="obidienceSummaryChartDiv">
                                            <canvas id="obidienceSummaryChart" height="100" width="240"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
