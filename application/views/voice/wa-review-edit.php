<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
          $maxScore = (int)$scoreList['high'] * 3;
          $currentMonthScore = $waReviewCurrentMonthResult['avg_score_response'] + $waReviewCurrentMonthResult['avg_score_accuracy'] + $waReviewCurrentMonthResult['avg_score_wording'];
        ?>

        <div class="container-fluid pt-2 px-1">           
            <form method="POST" action="">
                <div class="row">
                    <!-- left segment : agent & period data -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header bg-primary">
                                Agent & WA Info
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="waReviewEditPeriod">Period (month)</label>
                                    <input type="date" class="form-control text-center" id="waReviewEditPeriod" name="waReviewEditPeriod" value="<?= $waReviewById['period']; ?>" style="padding-left: 40px;">
                                </div>
                                <div class="form-group">
                                    <label for="waReviewEditAgent">Agent</label>
                                    <select class="custom-select" name="waReviewEditAgent" id="waReviewEditAgent"  style="text-align: center; text-align-last: center;" disabled>
                                        <option value="<?= $waReviewById['agent'] ?>" selected><?= $waReviewById['agent'] ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="waReviewEditLatestScore">This month result</label>
                                    <div id="waReviewEditLatestScore">
                                        <?= wa2barstotal($currentMonthScore / 15 * 100, 0) ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="waReviewEditVoiceNumber">WA # (current)</label>
                                    <input type="" class="form-control text-center" id="waReviewEditVoiceNumber" value="<?= $waReviewCurrentMonthQty ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="waReviewEditCurrentScore">Current WA score</label>
                                    <div id="waReviewEditCurrentScore">
                                        <?= wa2barstotal(($waReviewById['score_response'] + $waReviewById['score_accuracy'] + $waReviewById['score_wording']) /15 * 100, 0) ?>
                                    </div>
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
                        <div class="card" id="waReviewEditContainer">
                            <div class="card-header bg-primary">
                                WA Review Items
                                <div class="card-tools">
                                    <div class="btn-group mr-2">
                                        <div class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                            <a class="dropdown-item" href="<?= base_url('files/Format_Count_Response_Time_WA_no_adjustment.xlsx') ?>"><i class=" fas fa-file-excel"></i> Normal Mendawai time</a>
                                            <a class="dropdown-item" href="<?= base_url('files/Format_Count_Response_Time_WA_GMT7.xlsx') ?>"><i class="far fa-file-code"></i> Adjust GMT+7 time</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" class="form-control text-center text-primary" id="waReviewEditId" name="waReviewEditId" value="<?= $waReviewById['id'] ?>" readonly>
                                <div class="form-group row">
                                    <label for="waReviewEditConversationDate" class="col-sm-2 col-form-label" style="min-width: 120px;">Conversation date</label>
                                    <div class="col-sm-3" style="max-width: 240px; min-width: 238px;">
                                        <input type="datetime-local" class="form-control" name="waReviewEditConversationDate" id="waReviewEditConversationDate" value="<?= $waReviewById['datetime'] ?>" required>
                                    </div>
                                    <label for="waReviewEditPhone" class="col-sm-2 col-form-label text-right" style="max-width: 124px; min-width: 120px;">Phone</label>
                                    <div class="col-sm-3" style="min-width: 220px; max-width: 240px;">
                                        <input type="" class="form-control" name="waReviewEditPhone" id="waReviewEditPhone" value="<?= $waReviewById['customer_phone'] ?>" placeholder="Telp konsumen" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="waReviewEditTicket" class="col-sm-2 col-form-label" style="min-width: 120px;">Ticket</label>
                                    <div class="col-sm-3" style="max-width: 240px; min-width: 238px;">
                                        <input type="" class="form-control" name="waReviewEditTicket" id="waReviewEditTicket" value="<?= $waReviewById['ticket_number'] ?>" ">
                                    </div>
                                    <label for="waReviewEditSystemCode" class="col-sm-2 col-form-label text-right" style="max-width: 124px;">System code</label>
                                    <div class="col-sm-3" style="min-width: 220px; max-width: 240px;">
                                        <select class="custom-select" name="waReviewEditSystemCode" id="waReviewEditSystemCode" >
                                            <option value="<?= $waReviewById['system_code'] ?>" selected><?= $waReviewById['system_code'] ?></option>
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
                                <div class="form-group row border-top mt-3 pt-3">
                                    <label for="waReviewEditResponseGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Response</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewEditResponseGood" name="waReviewEditResponse" <?= val2checked($waReviewById['score_response'], $scoreList['high']) ?>/>
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>OK / Good <em class="text-muted"> - Respon balasan ~ 1 menit</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewEditResponseLess" name="waReviewEditResponse" <?= val2checked($waReviewById['score_response'], $scoreList['medium']) ?>/>
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Need Improve <em class="text-muted"> - Respon balasan 2 ~ 5 menit</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewEditResponseBad" name="waReviewEditResponse" <?= val2checked($waReviewById['score_response'], $scoreList['low']) ?>/>
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
                                            <input type="" class="form-control" id="waReviewEditResponseRemark" name="waReviewEditResponseRemark" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Information Accuracy -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewEditAccuracyGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Accuracy</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewEditAccuracyGood" name="waReviewEditAccuracy" <?= val2checked($waReviewById['score_accuracy'], $scoreList['high']) ?>/>
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
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewEditAccuracyLess" name="waReviewEditAccuracy" <?= val2checked($waReviewById['score_accuracy'], $scoreList['medium']) ?>/>
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
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewEditAccuracyBad" name="waReviewEditAccuracy" />
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good <em class="text-muted">- salah informasi ke konsumen</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="" class="form-control" id="waReviewEditAccuracyRemark" name="waReviewEditAccuracyRemark" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Wording -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewEditWordingGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Wording</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="<?= $scoreList['high'] ?>" id="waReviewEditWordingGood" name="waReviewEditWording" <?= val2checked($waReviewById['score_wording'], $scoreList['high']) ?>/>
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
                                                <input type="radio" value="<?= $scoreList['medium'] ?>" id="waReviewEditWordingLess" name="waReviewEditWording" <?= val2checked($waReviewById['score_wording'], $scoreList['medium']) ?>/>
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
                                                <input type="radio" value="<?= $scoreList['low'] ?>" id="waReviewEditWordingBad" name="waReviewEditWording" <?= val2checked($waReviewById['score_wording'], $scoreList['low']) ?>/>
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good <em class="text-muted">- kalimat asal, tidak mencerminkan layanan WA perusahaan.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <input type="" class="form-control" id="waReviewEditWordingRemark" name="waReviewEditWordingRemark" placeholder="Keterangan kurang/tidak ada smile voice">
                                        </div>
                                    </div>
                                </div>
                                <!-- Remark & Voice File-->
                                <div class="form-group row">
                                    <label for="waReviewEditRemark" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Remark</label>
                                    <div class="col-sm-9">
                                        <input type="" class="form-control" id="waReviewEditRemark" name="waReviewEditRemark" value="<?= $waReviewById['remark'] ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update data</button>
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