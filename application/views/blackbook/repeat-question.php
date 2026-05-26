<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid py-3 px-1">
        <!-- /.row -->
        <?php 
          if(!$this->input->post('repeatQuestionDateStart')) {
            $startPeriod = date("Y-m-d", strtotime("-3 months"));
            $endPeriod = date("Y-m-d");
          } else {
            $startPeriod = $this->input->post('repeatQuestionDateStart');
            $endPeriod = $this->input->post('repeatQuestionDateEnd');
          }

          function toStringDate($date){
            if(strtotime($date) < 0){
              return '-';
            } else {
              return date("d-M-Y h:i",strtotime($date));
            }
          }

          function voiceLinkToString($data) {
            if ($data == null) {
              return '';
            } else {
              return '<br><a href="' . $data . '" target="_blank" title="cek recording disini"><i class="fas fa-volume-up"></i> link recording</a>';
            }
          }
          $allowedAccess = [1, 2, 4, 5, 6, 9];
        ?>

        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header bg-primary">
                Agent's Repeat Quuestions : <span class="text-bold text-warning"><?= date("F Y", strtotime($startPeriod)); ?> to <?= date("F Y", strtotime($endPeriod)); ?></span>
                <div class="card-tools">
                  <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                    <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#repeatQuestionkAdd" id="repeatQuestionkAddButton"><i class="fas fa-plus-circle" ></i> Add new</a>
                  <?php endif ?>
                </div>
              </div>
              <div class="card-body">
                <form action="" class="form-row mb-5" method="post" style="width: 520px;">
                  <label for="repeatQuestionDateStart" class="col-sm-2">Period</label>
                  <div class="col-sm-4">
                    <input type="date" id="repeatQuestionDateStart" name="repeatQuestionDateStart" class="form-control" value="<?= $startPeriod?>">
                  </div>
                  <div class="col-sm-4">
                    <input type="date" id="repeatQuestionDateEnd" name="repeatQuestionDateEnd" class="form-control" value="<?= $endPeriod?>">
                  </div>
                  <div class="col-sm-2">
                    <div class="row ml-1">
                      <button type="submit" class="btn btn-outline-primary" id="buttonSelectRepeatQuestionDetail" name="buttonSelectRepeatQuestionDetail">Go</button>
                      <a href="<?= base_url('blackbook/toExcelRepeatQuestionDetail/') . $startPeriod . '/' . $endPeriod ?>" class="btn btn-outline-success ml-1" id="buttonToExcelRepeatQuestionDetail" name="buttonToExcelBlackbookDetail"><i class="fas fa-file-excel"></i></a>
                    </div>
                  </div>
                </form>
                <div class="row">
                  <div class="col">
                    <h5 class="lead text-indigo">Summary by Agent</h5>
                    <table class="table table-sm table-bordered col-sm-8">
                      <thead>
                        <tr class="bg-light">
                          <th>#</th>
                          <th>Agent</th>
                          <th class="text-center">SKAPE</th>
                          <th class="text-center">CCC Flow</th>
                          <th class="text-center">CS Flow</th>
                          <th class="text-center">Others</th>
                          <th class="text-center">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($summaryByAgents as $row) : ?>
                          <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= $row['agent'] ?></td>
                            <td class="text-center"><?= $row['skape'] ?></td>
                            <td class="text-center"><?= $row['ccc_flow'] ?></td>
                            <td class="text-center"><?= $row['cs_flow'] ?></td>
                            <td class="text-center"><?= $row['others'] ?></td>
                            <td class="text-center"><?= $row['total'] ?></td>
                          </tr>
                        <?php endforeach; ?>
                        <tr class="text-bold text-center">
                          <td colspan="2">Total</td>
                          <td><?= $summaryByAgentsSubtotal['skape'] ?></td>
                          <td><?= $summaryByAgentsSubtotal['ccc_flow'] ?></td>
                          <td><?= $summaryByAgentsSubtotal['cs_flow'] ?></td>
                          <td><?= $summaryByAgentsSubtotal['others'] ?></td>
                          <td><?= $summaryByAgentsSubtotal['total'] ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col">
                    <h5 class="lead text-indigo">Detail List</h5>
                    <table id="tableBlackbookDetail" class="table table-bordered table-sm">
                      <thead>
                        <tr class="bg-light">
                          <th class="text-center">No</th>
                          <th>Date</th>
                          <th>Agent</th>
                          <th>Category</th>
                          <th>Detail</th>
                          <th>Remark</th>
                          <th>Input on</th>
                          <th class="text-center">...</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($detailList as $row): ?>                     
                          <tr>
                              <td class="text-center" style="width:16px;"><?= $i++; ?></td>
                              <td class="col-sm-1"><?= date("d-M-y", strtotime($row['date'])); ?></td>
                              <td class="col-sm-1"><?= $row['agent']; ?></td>
                              <td class="col-sm-2"><?= $row['category']; ?></td>
                              <td class="col-sm-4"><?= $row['detail']; ?></td>
                              <td class="col-sm-2"><?= $row['remark']; ?></td>
                              <td class="col-sm-1"><?= date("d-M-y", strtotime($row['saved_at'])); ?></td>
                              <td style="width:16px;">
                                <div class="btn-group">                              
                                  <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                  <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                                    <table  class="table table-sm table-borderless table-hover">
                                      <tbody>
                                        <tr>
                                          <td>Saved by</td>
                                          <td class="">: <?= $row['saved_by']; ?></td>
                                        </tr>
                                        <tr>
                                          <td>Saved at</td>
                                          <td class="">: <?= toStringDate($row['saved_at']); ?></td>
                                        </tr>
                                        <tr>
                                          <td>Last modified</td>
                                          <td class="">: <?= $row['updated_by']; ?></td>
                                        </tr>
                                        <tr>
                                          <td>Datetime</td>
                                          <td class="">: <?= toStringDate($row['updated_at']); ?></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                                      <table class="table table-sm table-borderless">
                                        <tbody>
                                          <tr class="border-top">
                                            <td class="py-2">                                        
                                              <a href="" class="text-primary buttonRepeatQuestionEdit" title="Edit data" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#repeatQuestionkAdd">
                                                <i class="fas fa-pen"></i></span> &nbspEdit data
                                            </a>
                                            </td>
                                          </tr>
                                          <tr class="border-top">
                                            <td class="py-2">
                                              <a class="text-danger buttonRepeatQuestionDelete" href="<?= base_url()?>blackbook/deleterepeatquestion/<?=$row['id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                                <i class="fas fa-trash"></i> &nbspDelete data
                                              </a>
                                            </td>
                                          </tr>  
                                        </tbody>
                                      </table> 
                                    <?php endif; ?>
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
        </div> 
    </div><!-- /.container-fluid -->
  </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="repeatQuestionkAdd" tabindex="-1" role="dialog" aria-labelledby="repeatQuestionkAddLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="repeatQuestionkAddLabel">Add Agents' Repeat Question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
              <input type="hidden" class="form-control" name="repeatQuestionAddId" id="repeatQuestionAddId">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                      <label for="repeatQuestionAddAgent" class="form-label">Agent</label>
                      <div class="">
                          <select type="" class="custom-select" id="repeatQuestionAddAgent" name="repeatQuestionAddAgent">
                          <!-- <select type="" class="js-example-basic-single custom-select" id="repeatQuestionAddAgent" name="repeatQuestionAddAgent"> -->
                            <option>-- select agent --</option>
                            <?php foreach($allAgents as $agent): ?>
                                <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                            <?php endforeach; ?>
                          </select>
                      </div>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                      <label for="repeatQuestionAddDate" class="form-label">Date</label>
                      <div class="">
                          <input type="date" class="form-control" id="repeatQuestionAddDate" name="repeatQuestionAddDate">
                      </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                  <label for="repeatQuestionAddCategory" class="form-label">Category</label>
                  <div class="">
                      <select type="" class="custom-select" id="repeatQuestionAddCategory" name="repeatQuestionAddCategory">
                          <option value="">-- pilih kategori --</option>
                          <option value="SKAPE">SKAPE - Sudah ada di SKAPE tapi masih tanya</option>
                          <option value="CCC Flow">CCC Work Flow (Warranty, SVC area, )</option>
                          <option value="CS Flow">CS Work Flow (Alur di CS)</option>
                          <option value="Others">Others (lain-lain)</option>
                      </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="repeatQuestionAddDetail" class="form-label">Detail repeat question</label>
                  <div class="">
                      <textarea type="" class="form-control" id="repeatQuestionAddDetail" name="repeatQuestionAddDetail"></textarea>
                  </div>
              </div>
              <div class="form-group">
                  <label for="repeatQuestionAddRemark" class="form-label">Remark (keterangan)</label>
                  <div class="">
                      <input type="" class="form-control" id="repeatQuestionAddRemark" name="repeatQuestionAddRemark">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="reset" class="btn btn-warning" name="repeatQuestionAddReset" id="repeatQuestionAddReset">Reset</button>
              <button type="submit" class="btn btn-primary" name="repeatQuestionAddSubmit" id="repeatQuestionAddSubmit">Save</button>
          </div>
      </form>
    </div>
  </div>
</div>