<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- Header baru -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Others Agent's KPI Item</h3>

                    <div class="card-tools text-white">
                        <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#modalAddSingleOthersKpi" id="buttonOthersKpiDataAdd"><i class="fas fa-user" ></i> Add single</a>
                        <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#modalAddMultipleOthersKpiRows"><i class="fas fa-users" ></i> Multiple</a>
                        <a href="#" class="text-white mr-3"><i class="fas fa-file-excel" ></i> From Excel</a>                            
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pl-2">
                        <form action="" method="post" style="width: 440px;">
                            <label for="selectOthersKpiStart">Period</label>
                            <input type="date" class="custom-select" name="selectOthersKpiStart" id="selectOthersKpiStart" style="width: 140px;" value="<?= $startPeriod; ?>">
                            <label for="selectOthersKpiEnd">to</label>
                            <input type="date" class="custom-select" name="selectOthersKpiEnd" id="selectOthersKpiEnd" style="width: 140px;" value="<?= $endPeriod; ?>">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectOthersKpi" name="buttonSelectOthersKpi">Go</button>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table id="tableOthersKpiItem" class="table table-hover">
                                <thead>
                                    <tr class="small">
                                        <th>#</th>
                                        <th>Period</th>
                                        <th>Agent</th>
                                        <th>SKAPE draft</th>
                                        <!-- <th>SKAPE solution</th> -->
                                        <th>Knowledge Sharing</th>
                                        <th>Part Callback</th>
                                        <th>Forward complaint</th>
                                        <th>Complaint completion</th>
                                        <th>Complaint Report</th>
                                        <th>Email reply</th>
                                        <th>Promo inq</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($othersKpiByPeriod as $data): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= date("M-Y", strtotime($data['period'])); ?></td>
                                            <td><?= $data['agent']; ?></td>
                                            <td><?= $data['skape_draft']; ?></td>
                                            <!-- <td><?= $data['skape_solution']; ?></td> -->
                                            <td><?= $data['knowledge_sharing']; ?></td>
                                            <td><?= $data['part_callback']; ?>%</td>
                                            <td><?= $data['complaint_forward']; ?>%</td>
                                            <td><?= $data['complaint_completion']; ?>%</td>
                                            <td><?= $data['complaint_report']; ?></td>
                                            <td><?= $data['email_reply']; ?>%</td>
                                            <td><?= $data['promo_inquiry']; ?>%</td>
                                            <td>
                                                <div class="btn-group">                              
                                                  <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                  <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 200px;">
                                                    <table class="table table-sm table-borderless">
                                                      <tbody>
                                                        <tr class="border-top">
                                                          <td class="py-2">                                        
                                                            <a href="" class="text-primary buttonOthersKpiDataEdit" title="Edit data" data-id="<?= $data['id']; ?>" data-toggle="modal" data-target="#modalAddSingleOthersKpi">
                                                              <span class="fas fa-pen"></span> &nbspEdit data
                                                          </a>
                                                          </td>
                                                        </tr>
                                                        <tr class="border-top">
                                                          <td class="py-2">
                                                            <a class="text-danger buttonOthersKpiDataDelete" href="<?= base_url()?>assessment/delete/<?=$data['id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                                              <span class="fas fa-trash"></span> &nbspDelete data
                                                            </a>
                                                          </td>
                                                        </tr>  
                                                      </tbody>
                                                    </table> 
                                                  </div>
                                                </div>
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

<div class="modal fade" id="modalAddSingleOthersKpi" tabindex="-1" role="dialog" aria-labelledby="modalAddSingleOthersKpiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAddSingleOthersKpiLabel">Add Single Agent Others KPI Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="">
            <input type="hidden" class="form-control" id="addSingleOthersKpiId" name="addSingleOthersKpiId">
            <div class="modal-body">
                <div class="form-group row">
                    <label for="addSingleOthersKpiPeriod" class="col-sm-6 col-form-label">Period</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" id="addSingleOthersKpiPeriod" name="addSingleOthersKpiPeriod">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiPeriod" class="col-sm-6 col-form-label">Agent</label>
                    <div class="col-sm-6">
                        <select type="" class="form-control custom-select" id="addSingleOthersKpiAgent" name="addSingleOthersKpiAgent">
                            <option>-- select agent --</option>
                            <?php foreach($allAgents as $agent): ?>
                                <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiSkapeDraft" class="col-sm-6 col-form-label">SKAPE draft</label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control" id="addSingleOthersKpiSkapeDraft" name="addSingleOthersKpiSkapeDraft" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiSkapeSolution" class="col-sm-6 col-form-label">SKAPE solution</label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control" id="addSingleOthersKpiSkapeSolution" name="addSingleOthersKpiSkapeSolution" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiKnowledgeSharing" class="col-sm-6 col-form-label">Knowledge sharing</label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control" id="addSingleOthersKpiKnowledgeSharing" name="addSingleOthersKpiKnowledgeSharing" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiPartCallback" class="col-sm-6 col-form-label">Callback spare part (%)</label>
                    <div class="col-sm-6">
                        <input type="number" step="0.1" class="form-control" id="addSingleOthersKpiPartCallback" name="addSingleOthersKpiPartCallback" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiComplaintForward" class="col-sm-6 col-form-label">Complaint forward ratio (%)</label>
                    <div class="col-sm-6">
                        <input type="number" step="0.1" class="form-control" id="addSingleOthersKpiComplaintForward" name="addSingleOthersKpiComplaintForward" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiComplaintCompletion" class="col-sm-6 col-form-label">Complaint completion ratio (%)</label>
                    <div class="col-sm-6">
                        <input type="number" step="0.1" class="form-control" id="addSingleOthersKpiComplaintCompletion" name="addSingleOthersKpiComplaintCompletion" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiComplaintReport" class="col-sm-6 col-form-label">Complaint Report</label>
                    <div class="col-sm-6">
                        <input type="number" step="" class="form-control" id="addSingleOthersKpiComplaintReport" name="addSingleOthersKpiComplaintReport" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiEmailReply" class="col-sm-6 col-form-label">Email reply ratio (%)</label>
                    <div class="col-sm-6">
                        <input type="number" step="0.1" class="form-control" id="addSingleOthersKpiEmailReply" name="addSingleOthersKpiEmailReply" value="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="addSingleOthersKpiPromoInquiry" class="col-sm-6 col-form-label">Promo inquiry forward ratio (%)</label>
                    <div class="col-sm-6">
                        <input type="number" step="0.1" class="form-control" id="addSingleOthersKpiPromoInquiry" name="addSingleOthersKpiPromoInquiry" value="0">
                    </div>
                </div>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary" name="absentAddSubmit" id="absentAddSubmit">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
<div class="modal fade" id="modalAddMultipleOthersKpiRows" tabindex="-1" role="dialog" aria-labelledby="modalAddMultipleOthersKpiRows" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalAddMultipleOthersKpiRowsLabel">Add Others KPI Item</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="<?= base_url('assessment/addMultipleKpi') ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label for="addMultipleOthersKpiPeriod" class="form-label">Please enter month of period</label>
                    <div class="">
                        <input type="date" class="form-control" id="addMultipleOthersKpiPeriod" name="addMultipleOthersKpiPeriod" value="2">
                    </div>
                    <label for="addMultipleOthersKpiRows" class="form-label">Please enter number of row/staff</label>
                    <div class="">
                        <input type="number" class="form-control" id="addMultipleOthersKpiRows" name="addMultipleOthersKpiRows" value="2">
                    </div>
                </div>                
            </div>
            <div class="modal-footer">            
                <button type="submit" class="btn btn-primary" name="rowsAddMultipleOthersKpiSubmit" id="rowsAddMultipleOthersKpiSubmit">Go</button>
            </div>
        </form>
      </div>
    </div>
  </div>