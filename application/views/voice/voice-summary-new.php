<div class="content-wrapper">
   <section class="content">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <?php 
      require 'function-voice.php';
      ?>

      <div class="container-fluid pt-2 px-0">
         <div class="card card-primary">
            <div class="card-header">
               <span>Summary of Agent Voice Assessment</span>
            </div>
            <div class="card-body">
               <!-- form date input -->
               <div class="row mb-4 pl-2">
                  <form action="" method="post" class="" style="width: 480px;">
                     <label for="selectSummaryVoiceStart">Period</label>
                     <input type="date" class="custom-select" name="selectSummaryVoiceStart" id="selectSummaryVoiceStart" style="width: 160px;" value="<?= $startPeriod?>">
                     <label for="selectSummaryVoiceEnd">to</label>
                     <input type="date" class="custom-select" name="selectSummaryVoiceEnd" id="selectSummaryVoiceEnd" style="width: 160px;" value="<?= $endPeriod?>">
                     <button type="submit" class="btn btn-outline-primary" id="buttonSelectSummaryVoice" name="buttonSelectSummaryVoice">Go</button>
                  <button type="button" class="btn btn-outline-success" id="buttonToExcelSummaryVoice" name="buttonToExcelSummaryVoice"><i class="fas fa-file-excel"></i></button>
                  </form>
                  <div class="" style="position: absolute; top: 56px; right: 20px;">
                     <a href="<?= base_url('voice/info') ?>" class="text-info"><i class="fas fa-info-circle"></i> Info penilaian</a>
                  </div>                
               </div>
               
               <!-- table summary result -->
               <div class="row mb-4">
                  <div class="col-11">
                     <p class="h5 text-indigo mb-3"><i class="fas fa-file-alt"></i> Whole Summary on : <?= date("F Y", strtotime($startPeriod)) ?> to <?= date("F Y", strtotime($endPeriod)) ?></p>
                     <?php if($voiceSummary['qty'] == 0 ) : ?>
                       <p class="lead font-italic"> <i class="fas fa-grin-beam-sweat text-danger ml-3"></i>  there were no data to be displayed</p>
                     <?php else : ?>
                        <table class="table table-sm table-bordered">
                           <thead class="bg-light">
                              <tr class="">
                                 <th class="px-4 py-2 col-2 text-center">Item</th>
                                 <th class="px-4 py-2 col-2">Assessment</th>
                                 <th class="px-4 py-2 col-8">Achievement/Result</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td class="px-4 py-2 text-center align-middle">
                                    <span class="h3"><i class="fas fa-phone-alt"></i><br></span>
                                    Greeting
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <li>Completeness</li>
                                    <li>First impression</li>
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <div class="row">
                                       <?= value2bars('Good', number_format(($voiceSummary['greeting_good'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['greeting_good'], 'success') ?>
                                       <?= value2bars('Bad', number_format(($voiceSummary['greeting_bad'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['greeting_bad'], 'danger') ?>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="px-4 py-2 text-center align-middle">
                                    <span class="h3"><i class="far fa-smile"></i><br></span>
                                    Smile Voice
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <li>Intonation</li>
                                    <li>Consistency</li>
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <div class="row">
                                       <?= value2bars('Good', number_format(($voiceSummary['smile_good'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['smile_good'], 'success') ?>
                                       <?= value2bars('Need improve', number_format(($voiceSummary['smile_less'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['smile_less'], 'info') ?>
                                       <?= value2bars('Flat', number_format(($voiceSummary['smile_flat'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['smile_flat'], 'warning') ?>
                                       <?= value2bars('Bad', number_format(($voiceSummary['smile_bad'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['smile_bad'], 'danger') ?>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="px-4 py-2 text-center align-middle">
                                    <span class="h3"><i class="fas fa-spell-check"></i><br></span>
                                    Info Accuracy
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <li>Completeness</li>
                                    <li>Accuracy</li>
                                    <li>Cashless info</li>
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <div class="row">
                                       <?= value2bars('Good', number_format(($voiceSummary['accuracy_good'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['accuracy_good'], 'success') ?>
                                       <?= value2bars('Less accuracy', number_format(($voiceSummary['accuracy_less'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['accuracy_less'], 'warning') ?>
                                       <?= value2bars('Bad', number_format(($voiceSummary['accuracy_bad'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['accuracy_bad'], 'danger') ?>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="px-4 py-2 text-center align-middle">
                                    <span class="h3"><i class="fas fa-window-close"></i><br></span>
                                    Closing
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <li>Completeness</li>
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <div class="row">
                                       <?= value2bars('Good', number_format(($voiceSummary['closing_good'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['closing_good'], 'success') ?>
                                       <?= value2bars('Bad', number_format(($voiceSummary['closing_bad'] / $voiceSummary['qty']) * 100, 1) . '%', $voiceSummary['closing_bad'], 'danger') ?>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="px-4 py-2 text-center align-middle" colspan="2">
                                    <span class="h3"><i class="fas fa-suitcase"></i><br></span>
                                    Overall
                                 </td>
                                 <td class="px-4 py-2 align-middle">
                                    <div class="row">
                                       <?= value2barslite($voiceSummary['overall_ratio'] * 100, $voiceSummary['overall_score'], $voiceSummary['overall_divider']) ?>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     <?php endif; ?>
                  </div>
              </div>
               
              <!-- table summary by agent -->
               <div class="row mb-4">
                  <div class="col-11">
                     <p class="h5 text-indigo mb-3"><i class="fas fa-users"></i> Summary By Agent - <small class="">max. score : 25 (100%)</small></p>
                     <?php if(count($voiceSummaryByAgent) == 0 ) : ?>
                        <p class="lead font-italic"> <i class="fas fa-grin-beam-sweat text-danger ml-3"></i>  there were no data to be displayed</p>
                     <?php else : ?>
                        <table class="table table-sm table-bordered table-hover">
                           <thead class="bg-light">
                              <tr>
                                 <th class="text-center">#</th>
                                 <th>Agent</th>
                                 <th class="text-center"><i class="fas fa-volume-down"></i></th>
                                 <th class="text-center">Greeting</th>
                                 <th class="text-center" style="width: 160px;">Smile Voice</th>
                                 <th class="text-center">Accuracy</th>
                                 <th class="text-center">Closing</th>
                                 <th class="text-center">Total/Result</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i = 1; ?>
                              <?php foreach($voiceSummaryByAgent as $row): ?>
                                 <tr>
                                    <td class="text-center align-middle"><?= $i++ ?></td>
                                    <td class="align-middle"><?= $row['agent'] ?></td>
                                    <td class="text-center align-middle"><?= $row['qty'] ?></td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['greeting'] / 3 * 100), 0), $row['greeting_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['smile'] / 10 * 100), 0), $row['smile_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['accuracy'] / 10 * 100), 0), $row['accuracy_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barslite(number_format(($row['closing'] / 2 * 100), 0), $row['closing_good'], $row['qty']) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['total'] / 25 * 100), 0), $row['qty']) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     <?php endif; ?>
                  </div>
              </div>
              
              <!-- finding lists -->
               <div class="row mb-4">
                  <div class="col-11">
                     <p class="h5 text-indigo mb-3"><i class="far fa-thumbs-down"></i> Bad Findings List on : <?= date("F Y", strtotime($startPeriod)) ?> to <?= date("F Y", strtotime($endPeriod)) ?></p>
                     <?php if(count($voiceUnproperSummary) == 0 ) : ?>
                        <p class="lead font-italic"> <i class="fas fa-grin-beam-sweat text-danger ml-3"></i>  there were no data to be displayed</p>
                     <?php else : ?>
                        <table class="table table-sm table-bordered table-hover" id="tableSummaryBadFindingsList">
                           <thead class="bg-light">
                              <tr>
                                 <th class="text-center">#</th>
                                 <th>Agent</th>
                                 <th class="">Cust. phone</th>
                                 <th class="text-center">Call date</th>
                                 <th class="text-center">Greeting</th>
                                 <th class="text-center">Smile Voice</th>
                                 <th class="text-center">Accuracy</th>
                                 <th class="text-center">Closing</th>
                                 <th class="text-center">Total/Result</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $j = 1; ?>
                              <?php foreach ($voiceUnproperSummary as $row): ?>
                                 <tr>
                                    <td class="text-center align-middle"><?= $j++ ?></td>
                                    <td class="align-middle"><?= $row['agent'] ?></td>
                                    <td class="text-center align-middle"><?= $row['customer_phone'] ?></td>
                                    <td class="text-center align-middle"><?= date("d-M-Y", strtotime($row['call_date'])) ?></td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['greeting'] / 3 * 100), 0), 10) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['smile_voice'] / 10 * 100), 0), 10) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['accuracy'] / 10 * 100), 0), 10) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format(($row['closing'] / 2 * 100), 0), 10) ?>
                                    </td>
                                    <td class="">
                                       <?= value2barstotal(number_format((($row['greeting'] + $row['smile_voice'] + $row['accuracy'] + $row['closing']) / 25 * 100), 0), 10) ?>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
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