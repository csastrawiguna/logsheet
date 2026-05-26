<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content pt-3 px-1">
    <div class="container-fluid">
      <!-- /.row -->
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <?php                 
          function scoreToString($score) {
            if($score == '0') {
                return '-';
            } else {
                return $score;
            }
          }

          function dateToString($date) {
            if($date == '0000-00-00 00:00:00' || $date == '0' || $date == NULL) {
                return '-';
            } else {
                return date("d-M-Y H:i", strtotime($date));
            }
          }

          function idToString($id) {
            if(is_null($id)) {
              return 'salah';
            } else {
              return $id;
            }
          }

          function toDuration($dur) {
            $hours = floor($dur / 3600);
            $minutes = floor($dur / 60) % 60;
            $seconds = $dur % 60;

            echo toDec($hours).":".toDec($minutes).":".toDec($seconds);
          }

          function toDec($num) {
            if ($num < 10) {
              return "0".$num;
            } else {
              return $num;
            }
          }

          function remedialtag($rem) {
              if ($rem > 0) {
                  return '<span class="badge badge-pill badge-warning float-right">' . $rem . '</span>';
              } else {
                  return '';
              }
          }

          function posttestAttempToReset($rem, $att) {
            $out = [];
            if (($rem + 1) >= $att) {
              $out = ['func' => 'disabled', 'cls' => 'disabled resetPostTestDisabled'];
            } else {
              $out = ['func' => '', 'cls' => ''];
            }
            return $out;
          }
      ?>

      <div class="row">
        <div class="col-sm" id="elearningAssignmentList">
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <form id="colSelectEl">
                <div class="form-row align-items-center col mb-3">
                  <div class="col-sm-8">
                    <label class="col-sm-2 text-right" for="selectElearningCategory">Elearning </label>
                    <select class="custom-select col-sm-6" id="selectElearningCategory" name="selectElearningCategory">
                      <option value="<?= $elearning_id; ?>" selected><?= $elearning_name; ?></option>
                      <?php foreach ($allElearningCategory as $ael) : ?>
                        <option value="<?= $ael['id']; ?>" data-value="<?= $ael['id']; ?>"><?= $ael['name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" id="btnSelectActiveEl" name="btnSelectActiveEl">Go</button>
                  </div>
                  <div class="col-sm-4 text-right">
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#elearningAssignmentTableUnassignedUser" id="buttonElearningAssignmentUserList"><span class="fas fa-user-plus"></span> Add user</button>
                  </div>
                </div>
              </form>
            
              <?php if(empty($allElearningCategory)): ?>
                <table class="table">
                  <tbody>
                    <tr>
                      <td colspan="7" class="alert alert-warning text-center h5">Currently there was no active elearning</td>
                    </tr>
                  </tbody>
                </table>
              <?php else: ?>
                <table class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User ID</th>
                      <th>Department</th>
                      <th class="tableDataIsDone text-center">Pretest</th>
                      <th class="text-center tableDataExamDate">Pretest time</th>
                      <th class="text-center">Duration</th>
                      <th class="text-center">Posttest</th>
                      <th class="text-center tableDataExamDate">Posttest time</th>
                      <th class="text-center">Duration</th>
                      <th>...</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(empty($allAssignedUser)): ?>
                      <tr>
                        <td colspan="7" class="alert alert-light text-center h6 text-info">-- There was no user assigned in this elearning --</td>
                      </tr>
                    <?php else: ?>
                      <?php $i = 1; ?>
                      <?php foreach ($allAssignedUser as $row) : ?>
                        <tr>
                          <td class="text-center"><?= $i++; ?></td>
                          <td>
                            <?= $row['user_id']; ?>
                            <?= remedialtag($row['remedial']) ?>    
                          </td>
                          <td><?= $row['department']; ?></td>
                          <td class="text-center tableDataIsDone"><?= scoreToString($row['pretest_score']) ?></td>
                          <td class="text-center"><?= dateToString($row['pretest_date']) ?></td>
                          <td class="text-center"><?= toDuration($row['pretest_duration'])  ?></td>
                          <td class="text-center tableDataExamDate"><?= scoreToString($row['posttest_score']) ?></td>
                          <td class="text-center"><?= dateToString($row['posttest_date']) ?></td>
                          <td class="text-center"><?= toDuration($row['exam_duration'])  ?></td>
                          <td>
                            <!-- pretest & post test undone-->
                            <?php if ($row['pretest_done'] == 0 && $row['posttest_done'] == 0) { ?>
                              <button class="btn btn-sm btn-warning unassignedUser" data-userid="<?= idToString($row['user_id']) ?>" data-elearningid="<?= idToString($row['elearning_id']) ?>" title="Unassign User">
                                <l class="fas fa-user-alt-slash"></l> Unassign
                              </button>
                            <!-- pretest done & post test undone -->
                            <?php } else if ($row['posttest_done'] == 0 && $row['pretest_done'] == 1) { ?>
                              <button class="btn btn-sm btn-warning resetPretest" data-userid="<?= idToString($row['user_id']) ?>" data-elearningid="<?= idToString($row['elearning_id']) ?>" title="Reset Pretest">
                                <i class="fas fa-undo-alt"></i> Pretest
                              </button>
                            <?php } else { ?>
                              <button class="btn btn-sm btn-danger resetPosttest <?= posttestAttempToReset($row['remedial'], $row['posttest_attemp'])['cls'] ?>" data-userid="<?= idToString($row['user_id']) ?>" data-elearningid="<?= idToString($row['elearning_id']) ?>" data-maxattemp="<?= $row['posttest_attemp'] ?>" title="Reset Post Test" <?= posttestAttempToReset($row['remedial'], $row['posttest_attemp'])['func'] ?>>
                                <i class="fas fa-undo-alt"></i> Posttest
                              </button>
                            <?php } ?>
                          </td>                          
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="elearningAssignmentTableUnassignedUser" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="elearningAssignmentTableUnassignedUserLabel">Assign Staff for Elearning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>User ID</th>
              <th>NPK</th>
              <th>Department</th>
              <th>Assign</th>
            </tr>
          </thead>                    
          <tbody>
            <?php foreach ($unassignedUser as $uas) : ?>
              <tr>
                <td><?= $uas['user_id']; ?></td>
                <td><?= $uas['npk']; ?></td>
                <td><?= $uas['department']; ?></td>
                <td class="text-center">
                  <div class="pretty p-switch p-fill">
                    <input type="checkbox" name="userAssigned" class="assignUnassignUserCheckbox" data-userid="<?= $uas['user_id']; ?>" data-elearningid="<?= $elearning_id; ?>" data-pretest="<?= $isPretest; ?>">
                    <div class="state p-primary" oninput="assignUnassignUser();">
                      <label></label>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="modal-footer">
          <button id="assignUserBtn" class="btn btn-outline-primary">Assign</button>
          <button id="buttonElearningAssignmentCloseUSer" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>
