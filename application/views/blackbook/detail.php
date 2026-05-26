<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid py-3 px-1">
        <!-- /.row -->
        <?php 
          if(!$this->input->post()) {
            $startPeriod = date("Y-m-d", strtotime("-3 months"));
            $endPeriod = date("Y-m-d");
          } else {
            $startPeriod = $this->input->post('blackbookDetailDateStart');
            $endPeriod = $this->input->post('blackbookDetailDateEnd');
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

          function nameToStyle($name, $reff) {
            
            if ($name == $reff) {
              return 'btn-danger';
            } else {
              return 'btn-outline-danger';
            }
          }

          $allowedAccess = [1, 2, 4, 5, 6, 9];
        ?>

        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header bg-primary">
                Detail List of Agent's Black Notes period <span class="text-bold text-warning"><?= date("F Y", strtotime($startPeriod)); ?> to <?= date("F Y", strtotime($endPeriod)); ?></span>
                <div class="card-tools">
                  <a href="<?= base_url('blackbook/dailymonitoring') ?>" class="text-white mr-3" ><i class="fas fa-info-circle"></i> Cashless</a>
                  <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                    <a href="<?= base_url('blackbook/repeatquestion') ?>" class="text-white mr-3" ><i class="fas fa-undo-alt" ></i> Repeat Question</a>
                    <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#blackBookAdd" id="blackBookAddButton"><i class="fas fa-plus-circle" ></i> Add new</a>
                  <?php endif ?>
                </div>
              </div>
              <div class="card-body">                
                <form action="" class="form-row mb-3" method="post" style="width: 520px;">
                  <label for="blackbookDetailDateStart" class="col-sm-2">Period</label>
                  <div class="col-sm-4">
                    <input type="date" id="blackbookDetailDateStart" name="blackbookDetailDateStart" class="form-control" value="<?= $startPeriod?>">
                  </div>
                  <div class="col-sm-4">
                    <input type="date" id="blackbookDetailDateEnd" name="blackbookDetailDateEnd" class="form-control" value="<?= $endPeriod?>">
                  </div>
                  <div class="col-sm-2">
                    <div class="row ml-1">
                      <button type="submit" class="btn btn-outline-primary" id="buttonSelectBlackbookDetail" name="buttonSelectBlackbookDetail">Go</button>
                      <button type="button" class="btn btn-outline-success ml-1" id="buttonToExcelBlackbookDetail" name="buttonToExcelBlackbookDetail"><i class="fas fa-file-excel"></i></button>
                    </div>
                  </div>
                </form>
                <div class="row mt-3 mb-4">
                  <div class="col-10 p-2 border rounded" style="background-color: rgba(242, 242, 242, 1.0);">
                    <p class="text-bold">Count by Agent <span class="badge badge-light font-weight-normal">Score (qty)</span> :</p>
                    <?php foreach($allBlackNotesByAgent as $row) : ?>
                      <a href="<?= base_url('blackbook/byagent') ?>" class="btn btn-sm <?= nameToStyle($row['agent'], $this->session->userdata('user_id')) ?> my-1 mx-1">
                        <?= $row['agent'] ?> <span class="badge badge-light ml-2"><?= $row['scores'] ?> <span class="font-weight-normal">(<?= $row['blacknote'] ?>)</span></span>
                      </a>
                    <?php endforeach; ?>
                  </div>
                  <div class="col-auto ml-1 p-3 border rounded" style="min-width: 120px;">
                    <span class="badge badge-secondary py-1 px-2 mb-2">Scoring</span>
                      <?php foreach ($scoreLevels as $row) : ?>
                        <div>- <?= ucwords($row['level']) ?> <span class="float-right">: <?= $row['score'] ?></span></div>
                      <?php endforeach; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <table id="tableBlackbookDetail" class="table table-sm">
                      <thead>
                        <tr class="bg-light">
                          <th class="text-center">No</th>
                          <th>Date</th>
                          <th>Agent</th>
                          <th>Type of Sin</th>
                          <th>Detail</th>
                          <th>Additional info</th>
                          <th>Input on</th>
                          <th class="text-center">...</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1; ?>
                        <?php foreach($allBlackNotes as $data): ?>                     
                          <tr>
                              <td class="text-center" style="width:16px;"><?= $i++; ?></td>
                              <td class="col-sm-1"><?= date("d-M-y", strtotime($data['date'])); ?></td>
                              <td class="col-sm-1"><?= $data['agent']; ?></td>
                              <td class="col-sm-3">
                                <?= $data['type']; ?>
                                <span class="float-right badge badge-secondary font-weight-normal mr-3"><?= $data['score'] ?></span>
                              </td>
                              <td class="col-sm-3"><?= $data['detail']; ?>. <?= voiceLinkToString($data['voice_link']); ?></td>
                              <td class="col-sm-2"><?= $data['remark']; ?></td>
                              <td class="col-sm-1"><?= date("d-M-y", strtotime($data['saved_at'])); ?></td>
                              <td style="width:16px;">
                                <div class="btn-group">                              
                                  <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                  <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                                    <table  class="table table-sm table-borderless table-hover">
                                      <tbody>
                                        <tr>
                                          <td>Saved by</td>
                                          <td class="">: <?= $data['saved_by']; ?></td>
                                        </tr>
                                        <tr>
                                          <td>Saved at</td>
                                          <td class="">: <?= toStringDate($data['saved_at']); ?></td>
                                        </tr>
                                        <tr>
                                          <td>Last modified</td>
                                          <td class="">: <?= $data['last_modified_by']; ?></td>
                                        </tr>
                                        <tr>
                                          <td>Datetime</td>
                                          <td class="">: <?= toStringDate($data['last_modified_at']); ?></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                                      <table class="table table-sm table-borderless">
                                        <tbody>
                                          <tr class="border-top">
                                            <td class="py-2">                                        
                                              <a href="" class="text-primary buttonBlackbookDataEdit" title="Edit data" data-id="<?= $data['id']; ?>" data-toggle="modal" data-target="#blackBookAdd">
                                                <i class="fas fa-pen"></i></span> &nbspEdit data
                                            </a>
                                            </td>
                                          </tr>
                                          <tr class="border-top">
                                            <td class="py-2">
                                              <a class="text-danger buttonBlackbookDataDelete" href="<?= base_url()?>blackbook/delete/<?=$data['id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
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
<div class="modal fade" id="blackBookAdd" tabindex="-1" role="dialog" aria-labelledby="blackBookAddLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="blackBookAddLabel">Add Agents' Black Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
              <input type="hidden" class="form-control" name="blackbookAddId" id="blackbookAddId">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                      <label for="blackbookAddAgent" class="form-label">Agent</label>
                      <div class="">
                          <select type="" class="form-control custom-select" id="blackbookAddAgent" name="blackbookAddAgent">\
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
                      <label for="blackbookAddDate" class="form-label">Date</label>
                      <div class="">
                          <input type="date" class="form-control" id="blackbookAddDate" name="blackbookAddDate">
                      </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                  <label for="blackbookAddSinType" class="form-label">Type of Sins (Dosa apa)</label>
                  <div class="">
                    <select type="" class="js-example-basic-single custom-select" id="blackbookAddSinType" name="blackbookAddSinType">
                      <option>-- pilih dosa --</option>
                      <?php foreach ($allBlackNotesType as $row) : ?>
                        <option value="<?= $row['type'] ?>"><?= $row['bahasa'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
              </div>
              <div class="form-group">
                  <label for="blackbookAddDetail" class="form-label">Detail dosa</label>
                  <div class="">
                      <input type="" class="form-control" id="blackbookAddDetail" name="blackbookAddDetail">
                  </div>
              </div>
              <div class="form-group">
                  <label for="blackbookAddRemark" class="form-label">Remark (keterangan)</label>
                  <div class="">
                      <input type="" class="form-control" id="blackbookAddRemark" name="blackbookAddRemark">
                  </div>
              </div>
              <div class="form-group">
                  <label for="blackbookAddVoicelink" class="form-label">Voice recording (link recording kalau ada)</label>
                  <div class="">
                      <input type="" class="form-control" id="blackbookAddVoicelink" name="blackbookAddVoicelink">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
              <button type="reset" class="btn btn-warning" name="blackbookAddReset" id="blackbookAddReset"><i class="fas fa-undo"></i> Reset</button>
              <button type="submit" class="btn btn-primary" name="blackbookAddSubmit" id="blackbookAddSubmit"><i class="fas fa-save"></i> Save</button>
          </div>
      </form>
    </div>
  </div>
</div>