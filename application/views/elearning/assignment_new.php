<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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
                return date("d-M-Y H:i:s", strtotime($date));
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
      ?>

      <div class="row">
        <div class="col-sm" id="elearningAssignmentList">
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <form id="colSelectEl">
                <div class="form-row align-items-center col mb-3">
                  <div class="col-sm-8">
                    <label class="col-sm-3" for="selectElearningCategory">Period</label>
                    <select class="custom-select col-sm-6" id="selectElearningCategory" name="selectElearningCategory">
                      <?php foreach ($allElearningCategory as $ael) : ?>
                        <option value="<?= $ael['id']; ?>" data-value="<?= $ael['id']; ?>"><?= $ael['name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-primary" id="btnSelectActiveEl" name="btnSelectActiveEl">Go</button>
                  </div>
                  <!-- <div class="col-sm-1">
                  </div> -->
                  <div class="col-sm-4 text-right">
                    <button type="button" class="btn btn-outline-primary" id="buttonElearningAssignmentUserList"><span class="lnr lnr-plus-circle"></span> Add user</button>                   
                  </div>
              </form>
            </div>
            <!-- /.card-header -->
            <?php if(empty($allElearningCategory)): ?>
              <table class="table">
                <tbody>
                  <tr>
                    <td colspan="7" class="alert alert-warning text-center h5">Currently there was no active elearning</td>
                  </tr>
                </tbody>
              </table>
            <?php else: ?>
              <table class="table table-hover ">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>User ID</th>
                    <th>Department</th>
                    <th class="tableDataIsDone text-center">Pretest</th>
                    <th class="text-center tableDataExamDate">Pretest time</th>
                    <th class="text-center">Posttest</th>
                    <th class="text-center tableDataExamDate">Posttest time</th>
                    <th class="text-center">Duration</th>
                    <th>
                      <span id="buttonSelectAllUsers">
                        <div class="pretty p-svg p-curve">
                          <input type="checkbox" class="" data-id="">
                          <div class="state p-danger">
                          <!-- svg path -->
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                              <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label></label>
                          </div>
                        </div>
                      </span>
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php if(empty($allAssignedUser)): ?>
                    <tr>
                      <td colspan="7" class="alert alert-light text-center h6 text-info">-- There was no user assigned in this elearning --</td>
                    </tr>
                  <?php else: ?>
                    <?php $i = 1; ?>
                    <?php foreach ($allAssignedUser as $data) : ?>
                      <tr>
                        <td class="text-center"><?= $i++; ?></td>
                        <td><?= $data['user_id']; ?></td>
                        <td><?= $data['department']; ?></td>
                        <td class="text-center tableDataIsDone"><?= scoreToString($data['pretest_score']) ?></td>
                        <td class="text-center"><?= dateToString($data['pretest_date']) ?></td>
                        <td class="text-center tableDataExamDate"><?= scoreToString($data['posttest_score']) ?></td>
                        <td class="text-center"><?= dateToString($data['posttest_date']) ?></td>
                        <td class="text-center"><?= toDuration($data['exam_duration'])  ?></td>
                        <td>
                          <!-- pretest & post test undone-->
                          <?php if ($data['pretest_date'] == '0000-00-00 00:00:00') { ?>
                            <!-- <button class="btn btn-sm btn-outline-danger unassignedUser" data-userid="<?= idToString($data['user_id']) ?>" data-elearningid="<?= idToString($data['elearning_id']) ?>" title="Unassign User">
                              <i class="fas fa-times"></i>
                            </button> -->
                            <div class="pretty p-svg p-curve">
                              <input type="checkbox" class="" data-id="">
                              <div class="state p-danger">
                              <!-- svg path -->
                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                  <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                </svg>
                                <label></label>
                              </div>
                            </div>
                            <a href="#" class="unassignedUser text-danger" data-userid="<?= idToString($data['user_id']) ?>" data-elearningid="<?= idToString($data['elearning_id']) ?>" title="Unassign User">
                              <i class="fas fa-user-alt-slash"></i>
                            </a>
                          </td>
                          <!-- <td>
                            <div class="btn-group">                              
                              <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                              <div class="dropdown-menu dropdown-menu-right bg-light p-2" style="min-width: 200px;">
                                <a href="#" class="unassignedUser text-danger" data-userid="<?= idToString($data['user_id']) ?>" data-elearningid="<?= idToString($data['elearning_id']) ?>" title="Unassign User">
                                  <i class="fas fa-user-alt-slash"></i> Unassign user
                                </a>
                                <?php } else if ($data['posttest_date'] == '0000-00-00 00:00:00' && $data['pretest_date'] != '0000-00-00 00:00:00') { ?>
                                  <button class="btn btn-sm btn-warning resetPretest" data-userid="<?= idToString($data['user_id']) ?>" data-elearningid="<?= idToString($data['elearning_id']) ?>" title="Reset Pretest">
                                    <i class="fas fa-undo-alt"></i> Pretest
                                  </button>
                                <?php } else { ?>
                                  <button class="btn btn-sm btn-danger resetPosttest" data-userid="<?= idToString($data['user_id']) ?>" data-elearningid="<?= idToString($data['elearning_id']) ?>" title="Reset Post Test">
                                    <i class="fas fa-undo-alt"></i> Posttest
                                  </button>
                                <?php } ?>
                              </div>
                              </div>
                          </td> -->
                          <!-- pretest done & post test undone -->
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

      <div class="col" id="elearningAssignmentTableUser" style="max-width: 454px; display: none">
        <div class="card card-outline card-primary">
          <div class="box-profile card-body">
            <div class="row mb-3 mx-2">
              <label class="col-sm-6" for="selectElearningCategory">Pick user to assigned</label>
              <div class="col-sm-2">
              </div>
              <!-- <div class="col-sm-4 text-right">
                <button id="buttonElearningAssignmentCloseUSer" class="btn btn-outline-secondary">Close</button>
              </div> -->
            </div>
            <div class="row mx-auto" style="height: 70vh; overflow-y: auto; overflow-x: hidden;">
              <div class="form-group">
                <div class="col mx-auto">

                  <table class="table table-bordered table-sm px-0" style="width: 380px;">
                    <thead>
                      <tr>
                        <th>User ID</th>
                        <th>NPK</th>
                        <th>Department</th>
                        <th>Assign</th>
                      </tr>
                    </thead>                    
                    <tbody>
                      <!-- <input type="hidden" value="<?= $selectedElearningId; ?>" readonly name="elearningAssignmentSelectedElearning" id="elearningAssignmentSelectedElearning"> -->
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
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col mt-3 ml-2">
                <button id="assignUserBtn" class="btn btn-outline-primary">Assign</button>
                <button id="buttonElearningAssignmentCloseUSer" class="btn btn-outline-danger">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- <script>
  document.getElementById('selectElearningCategory').value = "<?= $_POST['selectElearningCategory']; ?> ";
  document.getElementById('asgElearningId').value = "?= $_POST['selectElearningCategory']; ?>";
</script>