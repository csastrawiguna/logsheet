<?php 
  function createRandom() {   
    $new = [];
    for ($i = 0; $i < 5; $i ++) {
    $val = rand(1, 5);
    if (in_array($val, $new)) {
    $val = rand(1, 5);
    } 
    $new[] = $val;
    }
    print_r($new);
  }

  function workBreaker($words) {

  }

?>

<style type="text/css">
  .base-timer {
    position: fixed;
    width: 30px;
    height: 30px;
    right: 30px;
    z-index: 999;
  }

  .base-timer__svg {
    transform: scaleX(-1);
  }

  .base-timer__circle {
    fill: none;
    stroke: none;
  }

  .base-timer__path-elapsed {
    stroke-width: 7px;
    stroke: transparent;
  }

  .base-timer__path-remaining {
    stroke-width: 7px;
    stroke-linecap: round;
    transform: rotate(90deg);
    transform-origin: center;
    transition: 1s linear all;
    fill-rule: nonzero;
    stroke: currentColor;
  }

  .base-timer__path-remaining.green {
    color: rgb(65, 184, 131);
    color: transparent;
  }

  .base-timer__path-remaining.orange {
    color: orange;
  }

  .base-timer__path-remaining.red {
    color: red;
  }

  .base-timer__label {
    position: absolute;
    width: 30px;
    height: 30px;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: red;
    font-family: arial;
  }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
  
  <!-- Main content -->
  <section class="content pt-2">
    <div class="container-fluid">
      <div id="app"></div>
      <div class="card text-wrap">
        <form method="post" action="" id="formElearningExam">
          <div class="card-header bg-primary">
            <table class="">
              <tr>
                <th class="col-1 text-center" style="width: 10px;">No</th>
                <th class="col-11"><span class="text-center">Pertanyaan</span></th>
              </tr>
            </table>
          </div>

          <div class="card-body">
            <input type="hidden" name="exam_user_id" value="<?= $this->session->userdata('user_id'); ?>">
            <input type="hidden" name="exam_posttest_start" value="<?= date("Y-m-d H-i:s"); ?>">
            <input type="hidden" id="posttest_duration" value="<?= $getQuestionaire[0]['test_duration']; ?>">
            <input type="hidden" name="exam_elearning_id" value="<?= $getQuestionaire[0]['category']; ?>">
            <table class="table table-borderless">
              <tbody>
                <?php $i = 1; ?>
                <?php foreach ($getQuestionaire as $q) : ?>
                  <?php
                  if ($q['picture_link'] == '' || $q['picture_link'] == '-') :
                  ?>
                    <tr>
                      <td rowspan="2" class="text-center text-bold" style="width: 10px;">
                        <?= $i++; ?>
                      </td>
                      <td class="text-bold text-wrap" style="max-width: 720px;">
                        <input type="hidden" name="examPrePost" value="posttest" readonly="">
                        <input type="hidden" name="qid[<?= $i - 2; ?>]" value="<?= $q['qid'];  ?>" readonly="">
                        <span class="text-wrap w-90"><?= $q['question']; ?></span>
                      </td>
                    </tr>
                  <?php else : ?>
                    <tr>
                      <td rowspan="3" class="text-center text-bold" style="width: 10px;">
                        <?= $i++; ?>
                      </td>
                      <td class="text-bold text-wrap" style="max-width: 720px;">
                        <input type="hidden" name="qid[<?= $i - 2; ?>]" value="<?= $q['qid'];  ?>" readonly="">
                        <span class="text-wrap w-90"><?= $q['question']; ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">
                        <img src="<?= base_url() . 'question_picture/' . $q['picture_link']; ?>" alt="Figure for question" height="200">
                      </td>
                    </tr>
                  <?php endif; ?>

                  <tr class="border-bottom">
                    <td class="align-middle">
                      <?php 
                        $options = [
                          [
                            'name' => 'answer[' . ($i - 2) . ']',
                            'value' => 'A',
                            'label' => $q['option_a']
                          ],
                          [
                            'name' => 'answer[' . ($i - 2) . ']',
                            'value' => 'B',
                            'label' => $q['option_b']
                          ],
                          [
                            'name' => 'answer[' . ($i - 2) . ']',
                            'value' => 'C',
                            'label' => $q['option_c']
                          ],
                          [
                            'name' => 'answer[' . ($i - 2) . ']',
                            'value' => 'D',
                            'label' => $q['option_d']
                          ],
                          [
                            'name' => 'answer[' . ($i - 2) . ']',
                            'value' => 'E',
                            'label' => $q['option_e']
                          ]
                        ];
                        shuffle($options);
                      ?>
                      <?php foreach ($options as $row) : ?>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="<?= $row['name'] ?>" value="<?= $row['value'] ?>">
                          <div class="state p-primary-o">
                            <label class="text-wrap"><?= $row['label']; ?></label>
                          </div>
                        </div>
                        <br>
                      <?php endforeach; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <td></td>
                  <td>
                    <button class="btn btn-primary mt-3 btnSubmit" type="submit" id="buttonElearningSubmitExam">Submit exam</button>
                  </td>
                </tr>
              </tbody>
              </tbody>
          </div>
          </table>
        </form>
      </div>
      <!-- /.row (main row) -->

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
   const FULL_DASH_ARRAY = 283;
   const WARNING_THRESHOLD = 10;
   const ALERT_THRESHOLD = 5;

  const COLOR_CODES = {
    info: {
      color: "green"
    },
    warning: {
      color: "orange",
      threshold: WARNING_THRESHOLD
    },
    alert: {
      color: "red",
      threshold: ALERT_THRESHOLD
    }
  };

  const timeCase = document.getElementById("posttest_duration");
  const TIME_LIMIT = parseInt(timeCase.value) * 60;
  let timePassed = 0;
  let timeLeft = TIME_LIMIT;
  let timerInterval = null;
  let remainingPathColor = COLOR_CODES.info.color;

  document.getElementById("app").innerHTML = `
    <div class="base-timer">
      <svg class="base-timer__svg" viewBox="0 00 100 100">
        <g class="base-timer__circle">
          <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
          <path
            id="base-timer-path-remaining"
            stroke-dasharray="283"
            class="base-timer__path-remaining ${remainingPathColor}"
            d="
              M 50, 50
              m -45, 0
              a 45,45 0 1,0 90,0
              a 45,45 0 1,0 -90,0
            "
          ></path>
        </g>
      </svg>
      <span id="base-timer-label" class="base-timer__label">${formatTime(
        timeLeft
      )}</span>
    </div>
    `;

  startTimer();

  function onTimesUp() {
    clearInterval(timerInterval);
  }

  function startTimer() {
    timerInterval = setInterval(() => {
      timePassed = timePassed += 1;
      timeLeft = TIME_LIMIT - timePassed;
      document.getElementById("base-timer-label").innerHTML = formatTime(
        timeLeft
      );
      setCircleDasharray();
      setRemainingPathColor(timeLeft);

      if (timeLeft === 0) {
        onTimesUp();
      }
    }, 1000);
  }

  function formatTime(time) {
    const minutes = Math.floor(time / 60);
    let seconds = time % 60;

    if (seconds < 10) {
      seconds = `0${seconds}`;
    }

    return `${minutes}:${seconds}`;
  }

  function setRemainingPathColor(timeLeft) {
    const {
      alert,
      warning,
      info
    } = COLOR_CODES;
    if (timeLeft <= alert.threshold) {
      document
        .getElementById("base-timer-path-remaining")
        .classList.remove(warning.color);
      document
        .getElementById("base-timer-path-remaining")
        .classList.add(alert.color);
    } else if (timeLeft <= warning.threshold) {
      document
        .getElementById("base-timer-path-remaining")
        .classList.remove(info.color);
      document
        .getElementById("base-timer-path-remaining")
        .classList.add(warning.color);
    }
  }

  function calculateTimeFraction() {
    const rawTimeFraction = timeLeft / TIME_LIMIT;
    return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
  }

  function setCircleDasharray() {
    const circleDasharray = `${(
        calculateTimeFraction() * FULL_DASH_ARRAY
      ).toFixed(0)} 283`;
    document
      .getElementById("base-timer-path-remaining")
      .setAttribute("stroke-dasharray", circleDasharray);
  }

  // function autosubmit form
  function autoSubmitExam(timeMinutes){
    if(timeMinutes == 0 ) {
      return
    } else {
      var formData = document.getElementById("formElearningExam");
      var timer = parseInt(timeMinutes) * 1000;
      setTimeout(function(){
        formData.submit();
      }, timer)
    }
  }

  // run autosubmit form
  autoSubmitExam(TIME_LIMIT);
</script>