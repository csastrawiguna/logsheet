<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
        ?>
        <div class="container-fluid pt-2 px-1">           

            <!-- <form method="post" action="<?= base_url('voice/uploadSurveyVoice') ?>"> -->
            <?= form_open_multipart('voice/performEditVoice'); ?>
                <input type="hidden" name="voiceSurveyEditId" value="<?= $voiceData['id'] ?>" readonly>
                <input type="hidden" name="voiceSurveyEditVoiceLink" value="<?= $voiceData['voice_link'] ?>" readonly>
                <div class="row">
                    <!-- left segment : agent & period data -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header bg-primary">
                                Agent and Voice info
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="voiceSurveyEditPeriod" class="text-muted">Period (month)</label>
                                    <input type="date" class="form-control text-center" id="voiceSurveyEditPeriod" name="voiceSurveyEditPeriod" value="<?= $voiceData['period'] ?>" style="padding-left: 40px;">
                                </div>
                                <div class="form-group">
                                    <label for="voiceSurveyEditAgent" class="text-muted">Agent</label>
                                    <select name="voiceSurveyEditAgent" id="voiceSurveyEditAgent" class="custom-select" style="text-align: center; text-align-last: center;">
                                        <option value="<?= $voiceData['agent'] ?>" selected><?= $voiceData['agent'] ?></option>
                                        <option value="">- select agent -</option>
                                        <?php foreach ($allActiveAgent as $ag) : ?>
                                            <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="voiceSurveyEditLatestScore" class="text-muted">Prev result</label>
                                    <div id="voiceSurveyEditLatestScore">
                                        <?= value2barstotal(0/25 * 100, 0) ?>
                                    </div>
                                    <!-- <input type="" class="form-control text-center" id="voiceSurveyEditLatestScore" value="On Dev. progress" readonly> -->
                                </div>
                                <div class="form-group">
                                    <label for="voiceSurveyEditVoiceNumber" class="text-muted">Voice # (current)</label>
                                    <input type="" class="form-control text-center" id="voiceSurveyEditVoiceNumber" value="" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="voiceSurveyEditCurrentScore" class="text-muted">Current survey score</label>
                                    <div id="voiceSurveyEditCurrentScore">
                                        <?= value2barstotal(0/25 * 100, 0) ?>
                                    </div>
                                    <!-- <input type="" class="form-control text-center text-primary" id="voiceSurveyCurrentScore" value="On Dev. progress" readonly> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- right segment : survey form -->
                    <div class="col-9">
                        <div class="card" id="voiceSurveyEditContainer">
                            <div class="card-header bg-primary">
                                Voice Assesment Items
                            </div>
                            <div class="card-body">
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditCallDate" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Call date</label>
                                    <div class="col-sm-3" style="max-width: 200px;">
                                        <input type="date" class="form-control" name="voiceSurveyEditCallDate" id="voiceSurveyEditCallDate" value="<?= $voiceData['call_date'] ?>" required>
                                    </div>
                                    <label for="voiceSurveyEditPhone" class="col-sm-2 col-form-label text-right" style="max-width: 80px;">Phone</label>
                                    <div class="col-sm-3" style="max-width: 180px;">
                                        <input type="" class="form-control" name="voiceSurveyEditPhone" id="voiceSurveyEditPhone" value="<?= $voiceData['customer_phone'] ?>" placeholder="Telp konsumen" required>
                                    </div>
                                    <div class="col-sm-2 mr-2" style="max-width: 80px;">
                                        <div class="pretty p-default p-curve">
                                            <input type="radio" name="voiceSurveyTagSource" id="voiceSurveyTagSourceIncoming" value="incoming" <?= checkedTag($voiceData['survey_source'], 'incoming') ?> required />
                                            <div class="state p-success-o">
                                                <label>Incoming</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2" style="max-width: 80px;">
                                        <div class="pretty p-default p-curve">
                                            <input type="radio" name="voiceSurveyTagSource" id="voiceSurveyTagSourceFollowup" value="follow up" <?= checkedTag($voiceData['survey_source'], 'follow up') ?> required />
                                            <div class="state p-info-o">
                                                <label>Follow Up</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Greeting -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditGreetingGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Greeting</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="3" id="voiceSurveyEditGreetingGood" name="voiceSurveyEditGreeting" <?= checkedTag($voiceData['greeting'], 3) ?>/>
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>OK / Bagus</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="1" id="voiceSurveyEditGreetingBad" name="voiceSurveyEditGreeting" <?= checkedTag($voiceData['greeting'], 1) ?> />
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good <em class="text-muted">- kurang smile voice saat greeting, tidak lengkap, dsb.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="" class="form-control" id="voiceSurveyEditGreetingRemark" name="voiceSurveyEditGreetingRemark" value="<?= $voiceData['greeting_remark'] ?>" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Smile Voice -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditSmileGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Smile Voice</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="10" id="voiceSurveyEditSmileGood" name="voiceSurveyEditSmile" <?= checkedTag($voiceData['smile_voice'], 10) ?> />
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Good / OK</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="5" id="voiceSurveyEditSmileLess" name="voiceSurveyEditSmile" <?= checkedTag($voiceData['smile_voice'], 5) ?>/>
                                                <div class="state p-primary">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Need improve <em class="text-muted">- ada smile voice tapi masih kurang</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="3" id="voiceSurveyEditSmileFlat" name="voiceSurveyEditSmile" <?= checkedTag($voiceData['smile_voice'], 3) ?>/>
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Flat <em class="text-muted">- tidak ada smile</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="1" id="voiceSurveyEditSmileBad" name="voiceSurveyEditSmile" <?= checkedTag($voiceData['smile_voice'], 1) ?>/>
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Bad <em class="text-muted">- nada tinggi, memotong pembicaraan konsumen, ekspresi melenguh, dsb.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <input type="" class="form-control" id="voiceSurveyEditSmileRemark" name="voiceSurveyEditSmileRemark" value="<?= $voiceData['smile_voice_remark'] ?>" placeholder="Keterangan kurang/tidak ada smile voice">
                                        </div>
                                    </div>
                                </div>
                                <!-- Information Accuracy -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditAccuracyGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Accuracy</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="10" id="voiceSurveyEditAccuracyGood" name="voiceSurveyEditAccuracy" <?= checkedTag($voiceData['accuracy'], 10) ?>/>
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
                                                <input type="radio" value="5" id="voiceSurveyEditAccuracyLess" name="voiceSurveyEditAccuracy" <?= checkedTag($voiceData['accuracy'], 5) ?>/>
                                                <div class="state p-warning">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good enouch <em class="text-muted">- info kurang lengkap, tidak info cashless, biaya servis dsb.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="1" id="voiceSurveyEditAccuracyBad" name="voiceSurveyEditAccuracy" <?= checkedTag($voiceData['accuracy'], 1) ?>/>
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
                                            <input type="" class="form-control" id="voiceSurveyEditAccuracyRemark" name="voiceSurveyEditAccuracyRemark" value="<?= $voiceData['accuracy_remark'] ?>" placeholder="Keterangan info tidak/kurang akurat">
                                        </div>
                                    </div>
                                </div>
                                <!-- Closing -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditClosingGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Closing</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="2" id="voiceSurveyEditClosingGood" name="voiceSurveyEditClosing" <?= checkedTag($voiceData['closing'], 2) ?>/>
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>OK / Bagus</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="1" id="voiceSurveyEditClosingBad" name="voiceSurveyEditClosing" <?= checkedTag($voiceData['closing'], 1) ?>/>
                                                <div class="state p-danger">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Not good <em class="text-muted">- tidak tawarkan bantuan kembali, tidak lengkap, terburu-buru, dsb.</em></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="" class="form-control" id="voiceSurveyEditClosingRemark" name="voiceSurveyEditClosingRemark" value="<?= $voiceData['closing_remark'] ?>" placeholder="Keterangan closing tidak bagus">
                                        </div>
                                    </div>
                                </div>
                                <!-- Remark & Voice File-->
                                <div class="form-group row">
                                    <label for="voiceSurveyEditRemark" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Remark</label>
                                    <div class="col-sm-9">
                                        <input type="" class="form-control" id="voiceSurveyEditRemark" name="voiceSurveyEditRemark" value="<?= $voiceData['voice_remark'] ?>" placeholder="Catatan lain (jika ada)">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3">
                                    <label for="voiceSurveyEditVoiceFile" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Voice File</label>
                                    <div class="col-sm-9">
                                        <input type="file" accept="audio/*" class="form-control" id="voiceSurveyEditVoiceFile" name="voiceSurveyEditVoiceFile" vale="<?= $voiceData['voice_link'] ?>" placeholder="Catatan lain (jika ada)">
                                        <span style="font-size: 13px;" class="text-danger">Tidak perlu upload lagi jika file recording masih sama dengan sebelumnya</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save data</button>
                                <button type="reset" class="btn btn-warning"><i class="fas fa-undo"></i> Reset form</button>
                                <a href="<?= base_url('voice/index') ?>">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>