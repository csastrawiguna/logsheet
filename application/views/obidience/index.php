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
                        Summary of Overtime Incompliance                        
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form action="" class="form-row" method="post" style="width: 820px;">
                                    <label for="obidienceSummaryDateStart" class="col-sm-1">Period</label>
                                    <div class="col-sm-2">
                                        <input type="date" id="obidienceSummaryDateStart" name="obidienceSummaryDateStart" class="form-control" value="<?= $inputStartPeriod?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="date" id="obidienceSummaryDateEnd" name="obidienceSummaryDateEnd" class="form-control" value="<?= $inputEndPeriod?>">
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="row ml-1">
                                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectObidienceSummary" name="buttonSelectObidienceSummary">Go</button>      
                                        </div>
                                    </div>
                                </form>    
                            </div>
                        </div>                 
                        <div class="row">
                            <div class="col">
                                <h5 class="h5 my-5">Summary of Overtime Incompliance period <span class="text-primary"><?= date("F Y", strtotime($inputStartPeriod)) ?></span> to <span class="text-primary"><?= date("F Y", strtotime($inputEndPeriod)) ?></span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div id="obidienceSummaryChartDiv">
                                    <canvas id="obidienceSummaryChart" height="100" width="240"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-11">
                                <table class="table table-sm table-hover mx-3" id="tableObidienceSummaryByAgent">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">#</th>
                                            <th class="align-middle">Agent</th>
                                            <th class="text-center align-middle">TTL Jadwal</th>
                                            <th class="text-center align-middle">Tdk sesuai</th>
                                            <th class="text-center align-middle">Tukar jadwal</th>
                                            <th class="text-center align-middle">Req. Ganti</th>
                                            <th class="text-center align-middle">Ganti+ tukar</th>
                                            <th class="text-center align-middle">Menggan<br>tikan</th>
                                            <th class="text-center align-middle">Obidience ratio</th>
                                            <th class="text-center align-middle">Obidience index</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($summaryObidienceByPeriod as $data) : ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $data['agent']; ?></td>
                                                <td class="text-center"><?= $data['total_schedule']; ?></td>
                                                <td class="text-center"><?= $data['incompliance']; ?></td>
                                                <td class="text-center"><?= $data['swap']; ?></td>
                                                <td class="text-center"><?= $data['replace_request']; ?></td>
                                                <td class="text-center"><?= $data['replace_request'] + $data['swap']; ?></td>
                                                <td class="text-center"><?= $data['replaced_to']; ?></td>
                                                <td class="text-center"><?= number_format(($data['total_schedule'] - $data['incompliance']) / $data['total_schedule'] * 100, 1) ; ?>%</td>
                                                <td class="text-center"><?= $data['obidience_index']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-auto">
                                <div class="callout callout-info">
                                  <dl>
                                      <dt class="h5 text-primary">Obidience Index</dt>
                                      <dd>Normal condition = lembur tidak diganti/tukar, obidience index = 0</dd>
                                      <dd>Obidience index kecil atau 0 = Jadwal Lembur jarang/tidak diganti/tukar</dd>
                                  </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
