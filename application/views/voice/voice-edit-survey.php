<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid pt-3">

            <?php 
                function valueToCheckbox($val) {
                    if ($val > 0) {
                        return 'checked';
                    } else {
                        return '';
                    }
                }

                $totalScore = 
                    $voiceData['greeting_complete'] + 
                    $voiceData['greeting_smile'] + 
                    $voiceData['intonation_straight'] + 
                    $voiceData['intonation_clear'] + 
                    $voiceData['intonation_not_flat'] + 
                    $voiceData['intonation_not_weak'] + 
                    $voiceData['intonation_not_high'] + 
                    $voiceData['handling_no_jargon'] + 
                    $voiceData['handling_customer_name'] + 
                    $voiceData['handling_communicative'] + 
                    $voiceData['handling_accuracy'] + 
                    $voiceData['handling_ask_help'] + 
                    $voiceData['closing'];
            ?>

            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Agent and Voice info
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="voiceSurveyPeriodSource">Period (month)</label>
                                <input type="date" class="form-control text-center" id="voiceSurveyPeriodSource" name="voiceSurveyPeriodSource" value="<?= $voiceData['period']; ?>" style="padding-left: 40px;">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyAgentSource">Agent</label>
                                <select name="voiceSurveyAgentSource" id="voiceSurveyAgentSource" class="custom-select" style="text-align: center; text-align-last: center;">
                                    <option value="<?= $voiceData['agent'];?>"><?= $voiceData['agent'];?></option>
                                    <?php foreach ($allActiveAgent as $ag) : ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyVoiceNumberSource">Voice #</label>
                                <input type="" class="form-control text-center" id="voiceSurveyVoiceNumberSource" name="voiceSurveyVoiceNumberSource" value="<?= $voiceData['voice_number'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyLatestScore">Prev # score (average)</label>
                                <input type="" class="form-control text-center" id="voiceSurveyLatestScore" name="voiceSurveyLatestScore">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyCurrentScore">Current survey score</label>
                                <input type="" class="form-control text-center text-primary" id="voiceSurveyCurrentScore" name="voiceSurveyCurrentScore" value="<?= $totalScore ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Voice Assesment Items
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?= base_url('voice/performEditVoice') ?>">
                                <input type="hidden" name="voiceSurveyEditId" value="<?= $voiceData['id'] ?>">
                                <table class="table table-sm table-borderless" id="tableVoiceSurveyEditForm">
                                    <tbody>
                                        <tr style="display: none;">
                                            <td>Period</td>
                                            <td>
                                                <input type="date" class="form-control text-center" id="voiceSurveyEditPeriod" name="voiceSurveyEditPeriod" value="">
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td>Agent</td>
                                            <td>
                                                <input type="" class="form-control text-center" id="voiceSurveyEditAgent" name="voiceSurveyEditAgent" value="">
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td>Voice number</td>
                                            <td>
                                                <input type="" class="form-control text-center" id="voiceSurveyEditVoiceNumber" name="voiceSurveyEditVoiceNumber" value="">
                                            </td>
                                        </tr>
                                        <tr class="mb-5 border-bottom">
                                            <td class="text-bold">Call date</td>
                                            <td>
                                                <input type="date" class="form-control" name="voiceSurveyEditCallDate" id="voiceSurveyEditCallDate" style="width: 160px;" value="<?= $voiceData['call_date'] ?>">
                                            </td>
                                        </tr>
                                        <tr class="mb-5">
                                            <td rowspan="2" class="text-bold">Greeting</td>
                                            <td>
                                                <div class="pretty p-default p-round my-2">
                                                    <input type="radio" name="voiceSurveyEditGreetingComplete" id="voiceSurveyEditGreetingComplete1" value="3" required <?= ($voiceData['greeting_complete'] == 3) ? 'checked' : ''; ?>>
                                                    <div class="state p-success-o">
                                                        <label>Greeting Complete</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-4">
                                                    <input type="radio" name="voiceSurveyEditGreetingComplete" id="voiceSurveyEditGreetingComplete2" value="1" required <?= ($voiceData['greeting_complete'] == 1) ? 'checked' : ''; ?>>
                                                    <div class="state p-danger-o">
                                                        <label>Greeting Incomplete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom" style="margin-bottom: 10px;">
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="2" name="voiceSurveyEditGreetingSmile" id="voiceSurveyEditGreetingSmile" <?= valueToCheckbox($voiceData['greeting_smile']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Smile Voice</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td rowspan="5" class="text-bold">Intonasi</td>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditIntonasiLugas" name="voiceSurveyEditIntonasiLugas" <?= valueToCheckbox($voiceData['intonation_straight']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Lugas</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditIntonasiJelas" name="voiceSurveyEditIntonasiJelas" <?= valueToCheckbox($voiceData['intonation_clear']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Suara jelas</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditIntonasiTidakDatar" name="voiceSurveyEditIntonasiTidakDatar" <?= valueToCheckbox($voiceData['intonation_not_flat']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Intonasi tidak datar</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditIntonasiTidakLemas" name="voiceSurveyEditIntonasiTidakLemas" <?= valueToCheckbox($voiceData['intonation_not_weak']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Intonasi tidak lemas</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom">
                                            <td class="border-bottom">
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditIntonasiTidakTinggi" name="voiceSurveyEditIntonasiTidakTinggi" <?= valueToCheckbox($voiceData['intonation_not_high']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Intonasi tidak tinggi</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td rowspan="5" class="text-bold border-bottom">Handling</td>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditHandlingTidakJargon" name="voiceSurveyEditHandlingTidakJargon" <?= valueToCheckbox($voiceData['handling_no_jargon']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Tidak ada jargon</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditHandlingSebutNamaKonsumen" name="voiceSurveyEditHandlingSebutNamaKonsumen" <?= valueToCheckbox($voiceData['handling_customer_name']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Sebut nama konsumen 3X</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditHandlingKomunikatif" name="voiceSurveyEditHandlingKomunikatif" <?= valueToCheckbox($voiceData['handling_communicative']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Komunikatif / smile</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditHandlingAkurasiInformasi" name="voiceSurveyEditHandlingAkurasiInformasi" <?= valueToCheckbox($voiceData['handling_accuracy']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Informasi ke konsumen akurat</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-bottom">
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="1" id="voiceSurveyEditHandlingBantuanKembali" name="voiceSurveyEditHandlingBantuanKembali" <?= valueToCheckbox($voiceData['handling_ask_help']) ?> />
                                                    <div class="state p-success">
                                                        <!-- svg path -->
                                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                            <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                        </svg>
                                                        <label>Menanyakan bantuan kembali</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="">
                                            <td class="text-bold">Closing</td>
                                            <td class="">
                                                <div class="pretty p-default p-round my-2">
                                                    <input type="radio" id="voiceSurveyEditClosing1" name="voiceSurveyEditClosing" value="5" required <?= ($voiceData['closing'] == 5) ? 'checked' : ''; ?>>
                                                    <div class="state p-success-o">
                                                        <label>Closing Lengkap</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-2">
                                                    <input type="radio" id="voiceSurveyEditClosing3" name="voiceSurveyEditClosing" value="2" required <?= ($voiceData['closing'] ==2) ? 'checked' : ''; ?>>
                                                    <div class="state p-warning-o">
                                                        <label>Closing tidak standar</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-2">
                                                    <input type="radio" id="voiceSurveyEditClosing2" name="voiceSurveyEditClosing" value="1" required <?= ($voiceData['closing'] == 1) ? 'checked' : ''; ?>>
                                                    <div class="state p-danger-o">
                                                        <label>Closing tidak lengkap</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Remark</td>
                                            <td>
                                                <input type="" name="voiceSurveyEditRemark" id="voiceSurveyEditRemark" class="form-control" autocomplete="off" value="<?= $voiceData['voice_remark'] ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Voice link</td>
                                            <td>
                                                <input type="" name="voiceSurveyEditVoiceLink" id="voiceSurveyEditVoiceLink" class="form-control" autocomplete="off" value="<?= $voiceData['voice_link'] ?>">
                                            </td>
                                        </tr>
                                        <tr class="row mt-3">
                                            <td>
                                                <button type="submit" class="btn btn-outline-primary ml-2" id="buttonVoiceSurveyEditSubmit">Submit</button>
                                                <button type="button" class="btn btn-outline-primary ml-2" id="buttonVoiceSurveyEditEdit" style="display: none;">Update</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $("#tableVoiceSurveyForm input[name=voiceSurveyGreetingComplete]").prop("checked", false);
</script>