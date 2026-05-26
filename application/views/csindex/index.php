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
                        CS Index Surveyquestioner
                    </h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <!-- <input type="text" name="table_search" class="form-control float-right mr-1" placeholder="Search"> -->
                            <button type="button" class="btn btn-light btn-sm buttonAdd float-right" data-toggle="modal" data-target="#addDataSurvey" id="buttonAddDataSurey"><span class="lnr lnr-plus-circle"></span> Add data</button>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <div class="row mt-3 mb-4 pl-4">
                        <form action="" method="post" style="width: 280px;">
                            <label for="selectSurveyPeriod">Select period</label>
                            <select class="col-6 custom-select" name="selectSurveyPeriod" id="selectSurveyPeriod">
                                <?php foreach ($period as $period) : ?>
                                    <option value="<?= $period['period']; ?>"><?= date("M-Y", strtotime($period['period'])); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="buttonSelectSurveyPeriod" name="buttonSelectSurveyPeriod">Go</button>
                        </form>
                        <button type="button" id="buttonToExcelSelectedSurveyPeriod" class="btn btn-outline-success mb-2 mx-1">
                            <a href="" style="text-decoration: none; color: inherit;"><span class="lnr lnr-cloud-download" style="font-size: 16px;"></span> Excel</a>
                        </button>
                    </div>
                    <div class="table-responsive py-1">
                        <table class="table table-sm table-hover table-bordered" id="tableSurveyData">
                            <thead>
                                <tr class="text-center">
                                    <th class="align-middle">No</th>
                                    <th class="align-middle">Agent</th>
                                    <th class="align-middle">Cust.Name</th>
                                    <th class="align-middle">Cust.Phone</th>
                                    <th class="align-middle">Cust.City</th>
                                    <th class="align-middle">Q1</th>
                                    <th class="align-middle">Q2</th>
                                    <th class="align-middle">Time</th>
                                    <th class="align-middle">By</th>
                                    <th class="align-middle">...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($surveyData as $ls) : ?>
                                    <?php if ($ls['is_done'] == 0) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $ls['agent']; ?></td>
                                            <td><?= $ls['customer_name']; ?></td>
                                            <td><?= $ls['customer_phone']; ?></td>
                                            <td><?= $ls['customer_city']; ?></td>
                                            <td class="text-center"><?= $ls['questioner_1']; ?></td>
                                            <td class="text-center"><?= $ls['questioner_2']; ?></td>
                                            <td class="text-center">-</td>
                                            <td class="text-center"><?= $ls['survey_by']; ?></td>
                                            <td class="text-center">
                                                <button class="btn buttonActionSurvey" data-toggle="modal" data-target="#actionSurvey" data-id="<?= $ls['id']; ?>"><span class="lnr lnr-pencil"></span></></button>
                                                <a class="buttonActionSurveyDelete" href="<?= base_url('csindex/deletesurveybyid') . '/' . $ls['id']; ?>" title="Delete data">
                                                    <span class="lnr lnr-trash text-danger"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <tr class="bg-light">
                                            <td class="text-primary"><?= $i++; ?></td>
                                            <td class="text-primary"><?= $ls['agent']; ?></td>
                                            <td class="text-primary"><?= $ls['customer_name']; ?></td>
                                            <td class="text-primary"><?= $ls['customer_phone']; ?></td>
                                            <td class="text-primary"><?= $ls['customer_city']; ?></td>
                                            <td class="text-center text-primary"><?= strtoupper($ls['questioner_1']); ?></td>
                                            <td class="text-center text-primary"><?= strtoupper($ls['questioner_2']); ?></td>
                                            <td class="text-center text-primary"><?= date("d-M-y h:i:s", strtotime($ls['survey_datetime'])); ?></td>
                                            <td class="text-center text-primary"><?= $ls['survey_by']; ?></td>
                                            <td class="text-center"><button class="btn buttonViewSurvey text-primary" data-toggle="modal" data-target="#actionSurvey" data-id="<?= $ls['id']; ?>"><span class="lnr lnr-chevron-right-circle"></span></button></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Tambah Data Survey-->
<div class="modal fade" id="addDataSurvey" tabindex="-1" role="dialog" aria-labelledby="addDataSurveyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDataSurveyLabel">Add Survey Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('csindex/uploadCsIndexData'); ?>
                <div class="form-group row">
                    <label for="addSurveyPeriod" class="col-sm-3">Period</label>
                    <input type="date" class="col-sm-9 custom-select" id="addSurveyPeriod" name="addSurveyPeriod">
                </div>
                <div class="form-group row">
                    <label for="addSurveyData" class="col-sm-3">Data</label>
                    <input type="file" class="col-sm-9" id="addSurveyData" name="addSurveyData">
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

<!-- Modal Action Survey-->
<div class="modal fade" id="actionSurvey" tabindex="-1" role="dialog" aria-labelledby="actionSurveyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionSurveyLabel">CS Index Survey</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" class="col-sm-9 custom-select" id="surveyId" name="surveyId" value="" readonly>

                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label for="doSurveyCustomerName" class=""></label>
                            </div>
                            <div class="form-group row">
                                <label for="doSurveyAgent" class="col-sm-5">Agent</label>
                                <input type="" class="col-sm-7 form-control" id="doSurveyAgent" name="doSurveyAgent" readonly>
                            </div>
                            <div class="form-group row">
                                <label for="doSurveyModel" class="col-sm-5">Model</label>
                                <input type="" class="col-sm-7 form-control" id="doSurveyModel" name="doSurveyModel" readonly>
                            </div>
                            <div class="form-group row">
                                <label for="doSurveyCallDateTime" class="col-sm-5">Call datetime</label>
                                <input type="" class="col-sm-7 form-control" id="doSurveyCallDateTime" name="doSurveyCallDateTime" readonly>
                            </div>
                        </div>
                        <div class="col-2"></div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="doSurveyCustomerName" class="">Customer</label>
                                <input type="" class="form-control" id="doSurveyCustomerName" name="doSurveyCustomerName" readonly>
                            </div>
                            <div class="form-group">
                                <input type="" class="form-control" id="doSurveyCustomerPhone" name="doSurveyCustomerPhone" readonly>
                            </div>
                            <div class="form-group">
                                <input type="" class="form-control" id="doSurveyCustomerCity" name="doSurveyCustomerCity" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="doSurveyIDetail" class="">Pertanyaan Customer</label>
                                <textarea class="form-control" name="doSurveyIDetail" id="doSurveyIDetail" rows="1"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="doSurveyActionDetail" class="">Penjelasan Agent</label>
                                <textarea class="form-control" name="doSurveyActionDetail" id="doSurveyActionDetail" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="" class="mr-5">Q1 - Impression</label>
                                    </div>
                                    <div class="col-8">
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ1" value="3" id="doSurveyQ1">
                                            <div class="state p-primary-o">
                                                <label>Very good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ1" value="2" id="doSurveyQ1">
                                            <div class="state p-primary-o">
                                                <label>Good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ1" value="1" id="doSurveyQ1">
                                            <div class="state p-primary-o">
                                                <label>Fairly good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ1" value="-1">
                                            <div class="state p-primary-o">
                                                <label>Not very good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ1" value="-2">
                                            <div class="state p-primary-o">
                                                <label>Bad</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-3">
                                        <label for="" class="mr-5">Q2 - Kindness</label>
                                    </div>
                                    <div class="col-8">
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ2" value="3" id="doSurveyQ2">
                                            <div class="state p-primary-o">
                                                <label>Very good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ2" value="2">
                                            <div class="state p-primary-o">
                                                <label>Good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ2" value="1">
                                            <div class="state p-primary-o">
                                                <label>Fairly good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ2" value="-1">
                                            <div class="state p-primary-o">
                                                <label>Not very good</label>
                                            </div>
                                        </div>
                                        <div class="pretty p-default p-round my-2">
                                            <input type="radio" name="doSurveyQ2" value="-2">
                                            <div class="state p-primary-o">
                                                <label>Bad</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" name="actionSurveySubmitDelete" id="actionSurveySubmitDelete" data-id="<?= $ls['id']; ?>">Delete</button>
                        <button type="submit" class="btn btn-primary" name="actionSurveySubmit" id="actionSurveySubmit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // const x = document.getElementById("selectSurveyPeriod");
    // x.value = json_encode(date("M-Y", strtotime($_POST['selectSurveyPeriod'])));
</script>