<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
        ?>
        <div class="container-fluid pt-2 px-1">           
            <?= form_open_multipart('voice/wareviewform'); ?>
                <div class="row">
                    <!-- left segment : agent & period data -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header bg-primary">
                                Agent & WA Info
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="waReviewSurveyPeriod" class="text-muted">Period (month)</label>
                                    <input type="date" class="form-control text-center" id="waReviewSurveyPeriod" name="waReviewSurveyPeriod" value="<?= date("Y-m-01"); ?>" style="padding-left: 40px;">
                                </div>
                                <div class="form-group">
                                    <label for="waReviewSurveyAgent" class="text-muted">Agent</label>
                                    <select class="custom-select" name="waReviewSurveyAgent" id="waReviewSurveyAgent"  style="text-align: center; text-align-last: center;">
                                        <option value="">- select agent -</option>
                                        <?php foreach ($allActiveAgent as $ag) : ?>
                                            <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="waReviewSurveyLatestScore" class="text-muted">Prev result</label>
                                    <div id="waReviewSurveyLatestScore">
                                        <?= value2barstotal(0/15 * 100, 0) ?>
                                    </div>
                                    <!-- <input type="" class="form-control text-center" id="waReviewSurveyLatestScore" value="On Dev. progress" readonly> -->
                                </div>
                                <div class="form-group">
                                    <label for="waReviewSurveyVoiceNumber" class="text-muted">WA # (current)</label>
                                    <input type="" class="form-control text-center" id="waReviewSurveyVoiceNumber" value="0" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="waReviewSurveyCurrentScore" class="text-muted">Current WA score</label>
                                    <div id="waReviewSurveyCurrentScore">
                                        <?= value2barstotal(0/15 * 100, 0) ?>
                                    </div>
                                    <!-- <input type="" class="form-control text-center text-primary" id="voiceSurveyCurrentScore" value="On Dev. progress" readonly> -->
                                </div>
                            </div>
                        </div>

                        <!-- Inco Scoring -->
                        <div class="card card-info">
                            <div class="card-header">
                                <span class="h6">Scoring</span>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Good</b> <a class="float-right mr-4">: <?= $scoreList['high'] ?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Need improve</b> <a class="float-right mr-4">: <?= $scoreList['medium'] ?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Bad</b> <a class="float-right mr-4">: <?= $scoreList['low'] ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <!-- right segment : survey form -->
                    <div class="col-9">
                        <div class="card" id="waReviewSurveyContainer">
                            <div class="card-header bg-primary">
                                WA Review Items
                                <div class="card-tools">
                                    <div class="btn-group mr-2">
                                        <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-file-alt"></i> Format upload
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                            <a class="dropdown-item" href="<?= base_url('files/Format_Count_Response_Time_WA_no_adjustment.xlsx') ?>"><i class=" fas fa-file-excel"></i> Normal Mendawai time</a>
                                            <a class="dropdown-item" href="<?= base_url('files/Format_Count_Response_Time_WA_GMT7.xlsx') ?>"><i class="far fa-file-code"></i> Adjust GMT+7 time</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group row bg-light">
                                    <label for="waReviewSurveyConversationDate" class="col-sm-2 col-form-label" style="min-width: 120px;">Conversation date</label>
                                    <div class="col-sm-3" style="max-width: 240px; min-width: 238px;">
                                        <input type="datetime-local" class="form-control" name="waReviewSurveyConversationDate" id="waReviewSurveyConversationDate" value="<?= date("Y-m-d H:i") ?>" required>
                                    </div>
                                    <label for="waReviewSurveyPhone" class="col-sm-2 col-form-label text-right" style="max-width: 124px; min-width: 120px;">Phone</label>
                                    <div class="col-sm-3" style="min-width: 220px; max-width: 240px;">
                                        <input type="" class="form-control" name="waReviewSurveyPhone" id="waReviewSurveyPhone" value="" placeholder="Telp konsumen" required>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3 bg-light">
                                    <label for="waReviewSurveyTicket" class="col-sm-2 col-form-label" style="min-width: 120px;">Ticket</label>
                                    <div class="col-sm-3" style="max-width: 240px; min-width: 238px;">
                                        <input type="" class="form-control" name="waReviewSurveyTicket" id="waReviewSurveyTicket" placeholder="No ticket jika ada">
                                    </div>
                                    <label for="waReviewSurveySystemCode" class="col-sm-2 col-form-label text-right" style="max-width: 124px;">System code</label>
                                    <div class="col-sm-3" style="min-width: 220px; max-width: 240px;">
                                        <select class="custom-select" name="waReviewSurveySystemCode" id="waReviewSurveySystemCode" >
                                            <option value=""> - pilih system code - </option>
                                            <option value="1a">1a</option>
                                            <option value="1b">1b</option>
                                            <option value="1c">1c</option>
                                            <option value="1d">1d</option>
                                            <option value="1e">1e</option>
                                            <option value="2a">2a</option>
                                            <option value="2b">2b</option>
                                            <option value="3a">3a / 3m / 3q</option>
                                            <option value="3b">3b</option>
                                            <option value="3c">3c</option>
                                            <option value="3d">3d</option>
                                            <option value="3e">3e</option>
                                            <option value="3f">3f / 3h / 3i</option>
                                            <option value="3j">3j</option>
                                            <option value="3k">3k</option>
                                            <option value="4a">4a</option>
                                            <option value="4b">4b</option>
                                            <option value="5a">5a/5b/5c/5d</option>
                                            <option value="5e">5e</option>
                                            <option value="6a">6a</option>
                                            <option value="7a">7a / 7b / 7c</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Greeting -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewSurveyResponseGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Response</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewSurveyResponseGood" name="waReviewSurveyResponse" />
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>OK / Good <em class="text-muted"> - Respon balasan < 1 menit</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewSurveyResponseLess" name="waReviewSurveyResponse" />
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Need Improve <em class="text-muted"> - Respon balasan 1 ~ 5 menit</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewSurveyResponseBad" name="waReviewSurveyResponse" />
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good <em class="text-muted">- Respon balasan > 5 menit</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="" class="form-control" id="waReviewSurveyResponseRemark" name="waReviewSurveyResponseRemark" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Information Accuracy -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewSurveyAccuracyGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Accuracy</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewSurveyAccuracyGood" name="waReviewSurveyAccuracy" />
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Good <em class="text-muted">- info benar & lengkap, termasuk <strong>cashless</strong></em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewSurveyAccuracyLess" name="waReviewSurveyAccuracy" />
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Need improve <em class="text-muted">- info kurang lengkap, tidak info cashless, biaya servis dsb.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewSurveyAccuracyBad" name="waReviewSurveyAccuracy" />
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Bad <em class="text-muted">- salah informasi ke konsumen</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="" class="form-control" id="waReviewSurveyAccuracyRemark" name="waReviewSurveyAccuracyRemark" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Wording -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewSurveyWordingGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Wording</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewSurveyWordingGood" name="waReviewSurveyWording" />
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Good / OK<em class="text-muted">- sedikit typo masih ditolelir</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewSurveyWordingLess" name="waReviewSurveyWording" />
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Need improve <em class="text-muted">- kalimat ambigu</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewSurveyWordingBad" name="waReviewSurveyWording" />
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Bad <em class="text-muted">- kalimat asal, tidak mencerminkan layanan WA perusahaan.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <input type="" class="form-control" id="waReviewSurveyWordingRemark" name="waReviewSurveyWordingRemark" placeholder="Keterangan kurang/tidak ada smile voice">
                                        </div>
                                    </div>
                                </div>
                                <!-- Remark & Voice File-->
                                <div class="form-group row">
                                    <label for="waReviewSurveyRemark" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Remark</label>
                                    <div class="col-sm-9">
                                        <input type="" class="form-control" id="waReviewSurveyRemark" name="waReviewSurveyRemark" placeholder="Catatan lain (jika ada)">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewSurveyExcelFile" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Chat File</label>
                                    <div class="col-sm-9">
                                        <input type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control" id="waReviewSurveyExcelFile" name="waReviewSurveyExcelFile" placeholder="">
                                        <small class="text-danger">File Excel percakapan agent - jika diperlukan</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save data</button>
                                <button type="reset" class="btn btn-warning"><i class="fas fa-undo"></i> Reset form</button>
                                <a href="<?= base_url('voice/wareviewlist') ?>">
                                    <button type="button" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Cancel</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>