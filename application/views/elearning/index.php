<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid px-0 pt-2">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <?php 
        function pretest2tag($pre) {
          if ($pre == 1) {
            return '<span class="float-right badge badge-info" title="Has Pretest">Pre</span>';
          } else {
            return '';
          }
        }

        function status2tag($stts) {
          if ($stts == 1) {
            return '<span class="float-right mx-1 badge badge-primary" title="Has Pretest">Ongoing</span>';
          } else {
            return '<span class="float-right mx-1 badge badge-secondary font-weight-normal" title="Has Pretest">Inactive</span>';
          }
        }
       ?>
      <div class="card">
        <div class="card-header bg-primary">
          List of Elearning
          <div class="card-tools">
            <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#categoryModal" id="buttonAdd"><i class="fas fa-plus-circle"></i> Add New</a>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
          <table class="table table-hover table-sm" id="tableElearningList">
            <thead>
              <tr>
                <th style="width: 10px" class="align-top">#</th>
                <th class="text-center align-top">Period</th>
                <th class="align-top">Elearning name</th>
                <th class="text-center align-top">Start</th>
                <th class="text-center align-top">End</th>
                <th class="text-center align-top">Material</th>
                <th class="text-center align-top">...</th>                  
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($elearning_category as $el) :
              ?>
                <tr>
                  <td><?= $i++;  ?></td>
                  <td class="text-center"><?= date('M-Y', strtotime($el['period']));  ?></td>
                  <td>
                    <?= $el['name'];  ?>
                    <?= status2tag($el['status']) ?>
                    <?= pretest2tag($el['pretest']) ?>
                  </td>
                  <td class="text-center"><?= date("d-M-y", strtotime($el['startdate']));  ?></td>
                  <td class="text-center"><?= date("d-M-y", strtotime($el['enddate']));  ?></td>
                  <td class="text-center">
                    <?php if ($el['elearning_material'] == '' || $el['elearning_material'] == '-') : ?>
                      <p class="text-secondary">-</p>
                    <?php
                      else : 
                      $material = explode('.', $el['elearning_material']);
                    ?>
                      <a href="<?= base_url('material' . '/'.$el['elearning_material']); ?>"><i class="fas fa-file-<?=$material[1];?>"></i> <?= strtoupper($material[1]) ?></a>
                    <?php endif; ?>
                  </td>                    
                  <td>
                    <div class="btn-group">
                      <p class="dropdown-toggle text-center" style="cursor: pointer;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                      </p>
                      <div class="dropdown-menu" style="width: 300px;">
                        <table  class="table table-sm table-borderless">
                          <tbody>
                            <tr>
                              <td class="pl-3">Question qty</td>
                              <td class="pl-3">: <?= $el['question_qty']; ?></td>
                            </tr>
                            <tr>
                              <td class="pl-3">Test duration</td>
                              <td class="pl-3">: <?= $el['test_duration']; ?> min</td>
                            </tr>
                            <tr class="text-bold">
                              <td class="pl-3">Posttest attemp</td>
                              <td class="pl-3">: <?= $el['posttest_attemp']; ?> times</td>
                            </tr>
                            <tr>
                              <td class="pl-3">Passing score</td>
                              <td class="pl-3">: <?= $el['passing_score']; ?></td>
                            </tr>
                            <tr>
                              <td class="pl-3">Created by</td>
                              <td class="pl-3">: <?= $el['created_by']; ?></td>
                            </tr>
                            <tr>
                              <td class="pl-3">Created at</td>
                              <td class="pl-3">: <?= date("d-M-Y h:i",strtotime($el['created_on'])); ?></td>
                            </tr>
                            <tr>
                              <td class="pl-3">Last modified</td>
                              <td class="pl-3">: <?= $el['last_modified_by']; ?></td>
                            </tr>
                            <tr>
                              <td class="pl-3">Datetime</td>
                              <td class="pl-3">: <?= date("d-M-Y h:i",strtotime($el['last_modified_on'])); ?></td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- <div class="dropdown-divider"></div> -->
                        <table class="table table-sm table-borderless">
                          <tbody>
                            <tr class="border-top">
                              <td class="py-2 pl-3">
                                <a href="<?= base_url('elearning/summary') .  '/' . $el['id']; ?>" class="text-dark"><i class="fas fa-play" title="Go to Elearning summary"></i> &nbspResult</a>
                              </td>
                            </tr>
                            <tr class="border-top">
                              <td class="py-2 pl-3">
                                <a class="buttonEdit h6 text-dark" data-toggle="modal" data-target="#categoryModal" data-id="<?= $el['id']; ?>" style="cursor: pointer;" title="Edit Elearning">
                                  <span class="fas fa-edit"></span> &nbspEdit elearning
                                </a>
                              </td>
                            </tr>
                            <tr class="border-top">
                              <td class="py-2 pl-3">
                                <a class="buttonDeleteElearning text-danger" href="<?= base_url('elearning/delete_elearning') . '/' . $el['id']; ?>" title="Delete Elearning" style="cursor: pointer; text-decoration: none;">
                                  <span class="fas fa-times"></span> &nbspDelete elearning
                                </a>
                              </td>
                            </tr>  
                          </tbody>
                        </table>                          
                      </div>                       
                    </div>                        
                  </td>                    
                </tr>
              <?php
              endforeach;
              ?>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </section>
</div>
<!-- /.row (main row) -->
<!-- Button trigger modal -->
<!-- Modal Tambah -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categoryModalLabel">Add new Elearning Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open_multipart('elearning/index'); ?>

        <input type="hidden" class="form-control" id="categoryId" name="categoryId" readonly>

        <div class="form-group row">
          <label for="categoryPeriod" class="col-sm-5 col-form-label">Period</label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="categoryPeriod" name="categoryPeriod">
          </div>
        </div>
        <div class="form-group row">
          <label for="categoryName" class="col-sm-5 col-form-label">Elearning name</label>
          <div class="col-sm-7">
            <input type="" class="form-control" id="categoryName" name="categoryName">
          </div>
        </div>
        <div class="form-group row">
          <label for="startdate" class="col-sm-5 col-form-label">Start date</label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="startdate" name="startdate">
          </div>
        </div>
        <div class="form-group row">
          <label for="enddate" class="col-sm-5 col-form-label">End date</label>
          <div class="col-sm-7">
            <input type="date" class="form-control" id="enddate" name="enddate">
          </div>
        </div>
        <div class="form-group row">
          <label for="questionQty" class="col-sm-5 col-form-label">Number of question</label>
          <div class="col-sm-7">
            <input type="number" class="form-control" id="questionQty" name="questionQty">
          </div>
        </div>
        <div class="form-group row">
          <label for="testDuration" class="col-sm-5 col-form-label">Test duration (minutes)</label>
          <div class="col-sm-7">
            <input type="number" class="form-control" id="testDuration" name="testDuration">
          </div>
        </div>
        <div class="form-group row">
          <label for="posttestAttemp" class="col-sm-5 col-form-label">Post Test Attemp</label>
          <div class="col-sm-7">
            <input type="number" class="form-control" id="posttestAttemp" name="posttestAttemp" value="1">
          </div>
        </div>
        <div class="form-group row">
          <label for="passingScore" class="col-sm-5 col-form-label">Minimum score to pass</label>
          <div class="col-sm-7">
            <input type="number" class="form-control" id="passingScore" name="passingScore">
          </div>
        </div>
        <div class="form-group row">
          <label for="questionPretest" class="col-sm-5 col-form-label">Pretest</label>
          <div class="col-sm-7">
            <select class="custom-select" id="questionPretest" name="questionPretest">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="material" class="col-sm-5 col-form-label">Material</label>
          <div class="col-sm-7">
            <input type="file" class="" id="material" name="material" value="">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="reset" class="btn btn-warning">Reset</button>
          <button type="submit" class="btn btn-primary" name="categoryModalSubmit">Save</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>