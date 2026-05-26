<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content pt-3">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <?php
        if (is_null($resultByCategoryByAgent)) {
          $posttest_date = '-';
          $posttest_score = '-';
        } else {
          $posttest_date = $resultByCategoryByAgent['posttest_date'];
          $posttest_score = $resultByCategoryByAgent['posttest_score'];
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
        <!-- left column -->
        <div class="col-md-3">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Result Summary</h3>
            </div>
            <!-- /.card-header -->

            <!-- form start -->
            <div class="card-body">
              <div class="form-group">
                <label for="selectResultUserId">Agent</label>
                <select class="custom-select form-control" id="selectResultUserId">
                  <?php if ($this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9) : ?>
                    <option value="">Pilih agent</option>
                    <?php foreach ($userList as $user) : ?>
                      <option value="<?= $user['user_id']; ?>"><?= $user['user_id']; ?></option>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <option selected><?= $this->session->userdata('user_id'); ?></option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="selectResultId">Elearning period</label>
                <select class="custom-select form-control" id="selectResultId">
                  <?php foreach ($elearningList as $ael) : ?>
                    <option value="<?= $ael['id']; ?>" data-id="<?= $ael['id']; ?>"><?= date('M-Y', strtotime($ael['period'])); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="selectResultPrepost">Pretest/Posttest</label>
                <select class="custom-select form-control" id="selectResultPrepost">
                  <option value="posttest">Post test</option>
                  <option value="pretest">Pretest</option>
                </select>
              </div>
              <div class="form-group">
                <label for="examScore">Score</label>
                <textarea rows="1" cols="1" class="text-primary form-control h1" id="examScore" style="font-size: 39px; text-align: center;" readonly=""><?= $posttest_score; ?></textarea>
              </div>
              <div class="form-group">
                <label for="">Exam date/time</label>
                <input type="" class="form-control text-center" id="examDate" value="<?= date('d-M-Y H:i', strtotime($posttest_date)); ?>" readonly="">
              </div>
              <div class="form-group">
                <label for="">Exam duration</label>
                <input type="" class="form-control text-center" id="examDuration" value="" readonly="">
              </div>
            </div>
            <!-- /.card-body -->

          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->

        <!-- right column -->
        <div class="col-md-9">
          <!-- general form elements disabled -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Detail Exam Result</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr class="text-center">
                    <th class="col-sm-1">No</th>
                    <th class="col-sm-9">Questionaire</th>
                    <!-- <th class="col-sm-1">Answer</th> -->
                    <th class="col-sm-1">Correct</th>
                  </tr>
                </thead>
                <tbody class="table-sm">
                  <?php $i = 1; ?>
                  <?php foreach ($examQuestionaireById as $exid) : ?>
                    <tr>
                      <td class="text-center"><?= $i++; ?></td>
                      <td><?= $exid['question']; ?></td>
                      <!-- <td class="text-center"><?= $exid['answer']; ?></td> -->
                      <td class="text-center h5">
                        <?php if ($exid['is_correct'] == 1) : ?>
                          <i class="far fa-check-circle text-primary"></i>
                        <?php else : ?>
                          <i class="far fa-times-circle text-danger"></i>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->