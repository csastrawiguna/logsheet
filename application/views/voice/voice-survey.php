<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid pt-3">           

            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Agent and Voice info
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="voiceSurveyPeriodSource">Period (month)</label>
                                <input type="date" class="form-control text-center" id="voiceSurveyPeriodSource" name="voiceSurveyPeriodSource" value="<?= date("Y-m-01"); ?>" style="padding-left: 40px;">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyAgentSource">Agent</label>
                                <select name="voiceSurveyAgentSource" id="voiceSurveyAgentSource" class="custom-select" style="text-align: center; text-align-last: center;">
                                    <!-- <option value=""><?= $voiceData['agent']    ;?></option> -->
                                    <?php foreach ($allActiveAgent as $ag) : ?>
                                        <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyVoiceNumberSource">Voice # (current)</label>
                                <input type="" class="form-control text-center" id="voiceSurveyVoiceNumberSource" name="voiceSurveyVoiceNumberSource">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyLatestScore">Prev # score (average)</label>
                                <input type="" class="form-control text-center" id="voiceSurveyLatestScore" name="voiceSurveyLatestScore">
                            </div>
                            <div class="form-group">
                                <label for="voiceSurveyCurrentScore">Current survey score</label>
                                <input type="" class="form-control text-center text-primary" id="voiceSurveyCurrentScore" name="voiceSurveyCurrentScore">
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
                            <form method="post" action="">
                                <table class="table table-sm table-borderless" id="tableVoiceSurveyForm">
                                    <tbody>
                                        <tr style="display: none;">
                                            <td>Period</td>
                                            <td>
                                                <input type="date" class="form-control text-center" id="voiceSurveyPeriod" name="voiceSurveyPeriod" value="">
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td>Agent</td>
                                            <td>
                                                <input type="" class="form-control text-center" id="voiceSurveyAgent" name="voiceSurveyAgent" value="">
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <td>Voice number</td>
                                            <td>
                                                <input type="" class="form-control text-center" id="voiceSurveyVoiceNumber" name="voiceSurveyVoiceNumber" value="">
                                            </td>
                                        </tr>
                                        <tr class="mb-5 border-bottom">
                                            <td class="text-bold">Call date</td>
                                            <td>
                                                <input type="date" class="form-control" name="voiceSurveyCallDate" id="voiceSurveyCallDate" style="width: 160px;" value="">
                                            </td>
                                        </tr>
                                        <tr class="mb-5">
                                            <td rowspan="2" class="text-bold">Greeting</td>
                                            <td>
                                                <div class="pretty p-default p-round my-2">
                                                    <input type="radio" name="voiceSurveyGreetingComplete" id="voiceSurveyGreetingComplete1" value="3" required>
                                                    <div class="state p-success-o">
                                                        <label>Greeting Complete</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-4">
                                                    <input type="radio" name="voiceSurveyGreetingComplete" id="voiceSurveyGreetingComplete2" value="1" required>
                                                    <div class="state p-danger-o">
                                                        <label>Greeting Incomplete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="border-bottom" style="margin-bottom: 10px;">
                                            <td>
                                                <div class="pretty p-svg p-curve">
                                                    <input type="checkbox" value="2" name="voiceSurveyGreetingSmile" id="voiceSurveyGreetingSmile" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyIntonasiLugas" name="voiceSurveyIntonasiLugas" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyIntonasiJelas" name="voiceSurveyIntonasiJelas" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyIntonasiTidakDatar" name="voiceSurveyIntonasiTidakDatar" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyIntonasiTidakLemas" name="voiceSurveyIntonasiTidakLemas" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyIntonasiTidakTinggi" name="voiceSurveyIntonasiTidakTinggi" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyHandlingTidakJargon" name="voiceSurveyHandlingTidakJargon" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyHandlingSebutNamaKonsumen" name="voiceSurveyHandlingSebutNamaKonsumen" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyHandlingKomunikatif" name="voiceSurveyHandlingKomunikatif" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyHandlingAkurasiInformasi" name="voiceSurveyHandlingAkurasiInformasi" />
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
                                                    <input type="checkbox" value="1" id="voiceSurveyHandlingBantuanKembali" name="voiceSurveyHandlingBantuanKembali" />
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
                                                    <input type="radio" id="voiceSurveyClosing1" name="voiceSurveyClosing" value="5" required>
                                                    <div class="state p-success-o">
                                                        <label>Closing Lengkap</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-2">
                                                    <input type="radio" id="voiceSurveyClosing3" name="voiceSurveyClosing" value="2" required>
                                                    <div class="state p-warning-o">
                                                        <label>Closing tidak standar</label>
                                                    </div>
                                                </div>
                                                <div class="pretty p-default p-round my-2 ml-2">
                                                    <input type="radio" id="voiceSurveyClosing2" name="voiceSurveyClosing" value="1" required>
                                                    <div class="state p-danger-o">
                                                        <label>Closing tidak lengkap</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Remark</td>
                                            <td>
                                                <input type="" name="voiceSurveyRemark" id="voiceSurveyRemark" class="form-control" autocomplete="off" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold">Voice link</td>
                                            <td>
                                                <input type="" name="voiceSurveyVoiceLink" id="voiceSurveyVoiceLink" class="form-control" autocomplete="off" value="">
                                            </td>
                                        </tr>
                                        <tr class="row mt-3">
                                            <td>
                                                <button type="submit" class="btn btn-outline-primary ml-2" id="buttonVoiceSurveySubmit">Submit</button>
                                                <button type="button" class="btn btn-outline-primary ml-2" id="buttonVoiceSurveyEdit" style="display: none;">Update</button>
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