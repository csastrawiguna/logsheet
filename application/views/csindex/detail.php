<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- /.row -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">
                        <span class="">Result of CS Index Survey</span>
                    </h3>
                    <div class="card-tools">                        
                            <!-- <a href="<?= base_url() ?>files/Format_Upload_CS_Index_Result.xlsx">
                                <button type="button" class="btn btn-light btn-sm float-right mr-1"><i class="fas fa-file-excel"></i> Format upload</button>
                            </a>
                            <button type="button" class="btn btn-light btn-sm float-right" data-toggle="modal" data-target="#ModalAddSurveyResult" id="buttonAddSureyResult"><span class="fas fa-plus-circle"></span> Add result</button> -->
                            <a href="<?= base_url() ?>files/Format_Upload_CS_Index_Result.xlsx">
                                <span class="text-white"><i class="fas fa-file-excel"></i> Format upload</span>
                            </a>
                            <a href="" class="text-white float-right ml-3" data-toggle="modal" data-target="#ModalAddSurveyResult" id="buttonAddSureyResult"><span class="fas fa-plus-circle"></span> Add result</a>
                    </div>
                </div>
                
                <div class="card-body ">
                    <div class="row">
                        <div class="col-4 mt-1 mb-3">
                            <form action="" method="post">
                                <label for="selectDetailResultPeriod">Select period</label>
                                <select class="col-5 custom-select" name="selectDetailResultPeriod" id="selectDetailResultPeriod">
                                    <?php foreach ($period as $period) : ?>
                                        <option value="<?= $period['period']; ?>"><?= date("M-Y", strtotime($period['period'])); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-outline-primary" name="buttonSelectDetailResultPeriod" id="buttonSelectDetailResultPeriod">Go</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mt-4">
                            <h4 class="h5 text-primary"><span class="badge badge-pill badge-primary">Summary result</span></h4>
                            <table class="table table-sm table-bordered table-hover" id="tableCsindexCsareaResult">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="col-sm-4 align-middle">Point</th>
                                        <th colspan="2" class="col-sm-4">Q1 - General Impression</th>
                                        <th colspan="2" class="col-sm-4 align-middle">Q2 - Manners</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="col-sm-2">Qty</th>
                                        <th class="col-sm-2">%</th>
                                        <th class="col-sm-2">Qty</th>
                                        <th class="col-sm-2">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Very good</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1_3']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1_3'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2_3']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2_3'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>Good</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1_2']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1_2'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2_2']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2_2'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>Fairly good</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1_1']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1_1'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2_1']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2_1'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>Not very good</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1__1']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1__1'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2__1']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2__1'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr>
                                        <td>Bad</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1__2']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1__2'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2__2']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2__2'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr class="bg-light text-bold">
                                        <td>Total</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1_qty']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2_qty']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr class="bg-light text-bold">
                                        <td>CS Area</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q1_csarea']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q1_csarea'] * 100, 1); ?>%</td>
                                        <td class="text-center"><?= $csareaResultByPeriod['q2_csarea']; ?></td>
                                        <td class="text-center"><?= round($csareaResultByPeriod['p_q2_csarea'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr class="bg-light text-bold">
                                        <td rowspan="2" class="align-middle">CS Index ratio</td>
                                        <td class="text-center" colspan="2"><?= round($csareaResultByPeriod['q1_result'] * 100, 1); ?>%</td>
                                        <td class="text-center" colspan="2"><?= round($csareaResultByPeriod['q2_result'] * 100, 1); ?>%</td>
                                    </tr>
                                    <tr class="bg-light text-bold">
                                        <td class="text-center" colspan="4"><?= round($csareaResultByPeriod['total_result'] * 100, 1); ?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="row mb-3"><span class="badge badge-pill badge-primary">Detail result</span></div>
                        <div class="row">
                            <div class="col-5">
                                <h6 class="h6">Q1. General Impression to CCC agent's service</h6>
                                <table class="table table-sm table-bordered table-hover" id="csindexDetailResultQ1">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="col-sm-2 align-middle">Nama</th>
                                            <th class="col-sm-1 align-middle">Very good</th>
                                            <th class="col-sm-1 align-middle">Good</th>
                                            <th class="col-sm-1 align-middle">Fairly good</th>
                                            <th class="col-sm-1 align-middle">Not good enough</th>
                                            <th class="col-sm-1 align-middle">Bad</th>
                                            <th class="col-sm-1 align-middle">Index/ score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latestPeriodSurveyResult as $result) : ?>
                                            <tr>
                                                <td><?= $result['agent']; ?></td>
                                                <td class="text-center"><?= $result['q1_3']; ?></td>
                                                <td class="text-center"><?= $result['q1_2']; ?></td>
                                                <td class="text-center"><?= $result['q1_1']; ?></td>
                                                <td class="text-center"><?= $result['q1__1']; ?></td>
                                                <td class="text-center"><?= $result['q1__2']; ?></td>
                                                <td class="text-center"><?= $result['q1_point']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-5">
                                <h6 class="h6">Q2. Manners of CCC agents</h6>
                                <table class="table table-sm table-bordered table-hover" id="csindexDetailResultQ2">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="col-sm-2 align-middle">Nama</th>
                                            <th class="col-sm-1 align-middle">Very good</th>
                                            <th class="col-sm-1 align-middle">Good</th>
                                            <th class="col-sm-1 align-middle">Fairly good</th>
                                            <th class="col-sm-1 align-middle">Not good enough</th>
                                            <th class="col-sm-1 align-middle">Bad</th>
                                            <th class="col-sm-1 align-middle">Index/ score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latestPeriodSurveyResult as $result) : ?>
                                            <tr>
                                                <td><?= $result['agent']; ?></td>
                                                <td class="text-center"><?= $result['q2_3']; ?></td>
                                                <td class="text-center"><?= $result['q2_2']; ?></td>
                                                <td class="text-center"><?= $result['q2_1']; ?></td>
                                                <td class="text-center"><?= $result['q2__1']; ?></td>
                                                <td class="text-center"><?= $result['q2__2']; ?></td>
                                                <td class="text-center"><?= $result['q2_point']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-1">
                                <h6 class="h6">&nbsp</h6>
                                <table class="table table-sm table-bordered table-hover bg-light" id="csindexDetailResultAll">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="col-sm align-middle">Survey Qty</th>
                                            <th class="col-sm align-middle">CSindex score</th>
                                            <th class="col-sm align-middle">CSindex ratio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($latestPeriodSurveyResult as $result) : ?>
                                            <tr>
                                                <td class="text-center"><?= $result['qty_agent']; ?></td>
                                                <td class="text-center text-bold"><?= $result['total_point']; ?></td>
                                                <td class="text-center text-bold"><?= round ($result['cs_ratio'] * 100, 1).'%'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Tambah Data Survey-->
<div class="modal fade" id="ModalAddSurveyResult" tabindex="-1" role="dialog" aria-labelledby="ModalAddSurveyResultLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddSurveyResultLabel">Upload Survey Result from Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('csindex/uploadCsIndexSurveyResult'); ?>
                <div class="form-group row">
                    <label for="csindexAddResultPeriod" class="col-sm-3">Period</label>
                    <input type="date" class="col-sm-9 custom-select" id="csindexAddResultPeriod" name="csindexAddResultPeriod">
                </div>
                <div class="form-group row">
                    <label for="csindexAddSurveyResult" class="col-sm-3">Data</label>
                    <input type="file" class="col-sm-9" id="csindexAddSurveyResult" name="csindexAddSurveyResult">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary" name="addDataSurveySubmit">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>