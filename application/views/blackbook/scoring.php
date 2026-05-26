<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid py-3 px-1">
        <!-- /.row -->
        <?php 
          $allowedAccess = [1, 2, 4, 5, 6, 9];

          function role2Disabled($role, $allowedAccess) {
            if (in_array($role, $allowedAccess)) {
              return;
            } else {
              return 'disabled';
            }
          }
        ?>

        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-header bg-primary">
                Blackbook Scoring List
                <?php if (in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                  <div class="card-tools">
                    <a href="#" class="text-white mr-3" id="buttonUpdateBlackbookScoreLevel" data-toggle="modal" data-target="#updateBlackbookScoreLevelModal"><i class="fas fa-list-alt"></i> Update Score Level</a>
                    <a href="#" class="text-white mr-3" id="buttonAddBlackbookScoringItem" data-toggle="modal" data-target="#editBlackbookScoringScoreModal"><i class="fas fa-plus-circle"></i> Add Blackbook Item Score</a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-body">                
                <div class="row">
                  <div class="col-10">
                    <form method="POST" action="<?= base_url('blackbook/updatescoring') ?>">
                      <table class="table table-sm table-borderless">
                        <thead>
                          <tr class="border-bottom">
                            <th>#</th>
                            <th>Item</th>
                            <th class="text-center">Low</th>
                            <th class="text-center">Medium</th>
                            <th class="text-center">Hard</th>
                            <th class="text-center">Active?</th>
                            <th class="text-center">...</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 0; ?>
                          <?php foreach($allBlackbookScoring as $row) : ?>
                            <tr class="border-bottom">
                              <td class="align-middle"><?= $i += 1; ?></td>
                              <td class="align-middle">
                                <?= $row['type'] ?>
                                <br>
                                <small class="text-secondary font-italic"><?= $row['bahasa'] ?></small>
                              </td>
                              <td class="align-middle text-center" style="width: 70px;" >
                                <div class="pretty p-default p-curve" >
                                  <input type="hidden" name="<?= $i ?>_id" value="<?= $row['id'] ?>" >
                                  <input type="radio" name="<?= $i ?>_val" value="low" <?= $row['level'] == 'low' ? 'checked' : '' ?> <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>>
                                  <div class="state p-success-o">
                                    <label></label>
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle text-center" style="width: 70px;">
                                <div class="pretty p-default p-curve">
                                  <input type="radio" name="<?= $i ?>_val" value="medium" <?= $row['level'] == 'medium' ? 'checked' : '' ?> <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>>
                                  <div class="state p-warning-o">
                                    <label></label>
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle text-center" style="width: 70px;">
                                <div class="pretty p-default p-curve">
                                  <input type="radio" name="<?= $i ?>_val" value="high" <?= $row['level'] == 'high' ? 'checked' : '' ?> <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>>
                                  <div class="state p-danger-o">
                                    <label></label>
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle text-center" style="width: 70px;">
                                <div class="pretty p-default p-curve">
                                  <input type="hidden" name="<?= $i ?>_status" value="0" checked>
                                  <input type="checkbox" name="<?= $i ?>_status" value="1" <?= $row['is_active'] == 1 ? 'checked' : '' ?> <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>>
                                  <div class="state p-info-o">
                                    <label></label>
                                  </div>
                                </div>
                              </td>
                              <td class="align-middle text-center" style="width: 40px;">
                                <button type="button" class="btn btn-sm text-secondary buttonEditBlackbookScoringSingle" data-toggle="modal" data-target="#editBlackbookScoringScoreModal" data-id="<?= $row['id'] ?>" <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>><i class="fas fa-edit"></i></button>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                      <button type="submit" class="btn btn-outline-primary mt-3 px-3" <?= role2Disabled($this->session->userdata('role_access'), $allowedAccess) ?>><i class="fas fa-save"></i> Save Update</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> 
    </div><!-- /.container-fluid -->
  </section>
</div>
<!-- modal add/edit blackbook score -->
<div class="modal fade" id="editBlackbookScoringScoreModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="blackBookAddLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="blackbookScoringAddLabel">Add Blackbook Item Scoring</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">
            <input type="hidden" class="form-control" id="blackbookScoringAddId" name="blackbookScoringAddId" value="" readonly>
            <div class="form-group">
              <label for="blackbookScoringAddType" class="form-label">Type (english)</label>
              <div class="">
                  <input type="" class="form-control" id="blackbookScoringAddType" name="blackbookScoringAddType" placeholder="example: Notif wrong service area">
              </div>
            </div>
            <div class="form-group">
              <label for="blackbookScoringAddBahasa" class="form-label">Type in Bahasa</label>
              <div class="">
                  <input type="" class="form-control" id="blackbookScoringAddBahasa" name="blackbookScoringAddBahasa" placeholder="example: Notif - Salah service area">
              </div>
            </div>
            <div class="form-group">
              <label for="blackbookScoringAddLevel" class="form-label">Blackbook score level</label>
              <div class="row">
                <div class="col-sm-3">
                  <div class="pretty p-default p-curve">
                    <input type="radio" name="blackbookScoringAddLevel" id="blackbookScoringAddLevelLow" value="low">
                      <div class="state p-success-o">
                        <label>Low</label>
                      </div>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="pretty p-default p-curve">
                    <input type="radio" name="blackbookScoringAddLevel" id="blackbookScoringAddLevelMedium" value="medium">
                    <div class="state p-warning-o">
                      <label>Medium</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="pretty p-default p-curve">
                    <input type="radio" name="blackbookScoringAddLevel" id="blackbookScoringAddLevelHigh" value="high">
                    <div class="state p-danger-o">
                      <label>High</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group border-top" id="blackbookScoringAddBahasaIsactiveDiv" style="display: none;">
              <label for="blackbookScoringAddBahasaIsactive" class="form-label">Is active?</label>
              <div class="">
                <div class="pretty p-default p-curve">
                  <input type="hidden" name="blackbookScoringAddBahasaIsactive" id="blackbookScoringAddBahasaIsactiveFalse" value="0">
                  <input type="checkbox" name="blackbookScoringAddBahasaIsactive" id="blackbookScoringAddBahasaIsactiveTrue" value="1">
                  <div class="state p-info-o">
                    <label>Active</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" name="blackbookScoringAddDelete" id="blackbookScoringAddDelete" style="display: none;"><i class="fas fa-trash"></i> Delete</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
              <button type="submit" class="btn btn-primary" name="blackbookScoringAddSubmit" id="blackbookScoringAddSubmit"><i class="fas fa-save"></i> Save</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- modal add/edit blackbook score -->
<div class="modal fade" id="updateBlackbookScoreLevelModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="updateBlackbookScoreLevelLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="blackbookScoringAddLabel">Update Blackbook Score Level</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="<?= base_url('blackbook/updatescoringlevel') ?>">
          <div class="modal-body">
            <div class="form-group row">
              <label for="updateBlackbookScoreLevelHigh" class="col-sm-6 col-form-label">High level</label>
              <div class="col-sm-6">
                <input type="number" min="0" class="form-control text-center" id="updateBlackbookScoreLevelHigh" name="updateBlackbookScoreLevelHigh" value="<?= $allBlackbookScoringLevel['high'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="updateBlackbookScoreLevelMedium" class="col-sm-6 col-form-label">Medium level</label>
              <div class="col-sm-6">
                <input type="number" min="0" class="form-control text-center" id="updateBlackbookScoreLevelMedium" name="updateBlackbookScoreLevelMedium" value="<?= $allBlackbookScoringLevel['medium'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="updateBlackbookScoreLevelLow" class="col-sm-6 col-form-label">Low level</label>
              <div class="col-sm-6">
                <input type="number" min="0" class="form-control text-center" id="updateBlackbookScoreLevelLow" name="updateBlackbookScoreLevelLow" value="<?= $allBlackbookScoringLevel['low'] ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-primary" name="blackbookScoringAddSubmit" id="blackbookScoringAddSubmit"><i class="fas fa-save"></i> Update</button>
          </div>
      </form>
    </div>
  </div>
</div>