<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
        ?>
        <div class="container-fluid pt-2 px-1">           
            <?= form_open_multipart('voice/survey'); ?>
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
                                        <?= value2barstotal(0/25 * 100, 0) ?>
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
                                        <?= value2barstotal(0/25 * 100, 0) ?>
                                    </div>
                                    <!-- <input type="" class="form-control text-center text-primary" id="voiceSurveyCurrentScore" value="On Dev. progress" readonly> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- right segment : survey form -->
                    <div class="col-9">
                        <div class="card" id="waReviewSurveyContainer">
                            <div class="card-header bg-primary">
                                WA Review Items
                            </div>
                            <div class="card-body">
                                <div class="form-group row bg-light">
                                    <label for="waReviewSurveyConversationDate" class="col-sm-2 col-form-label">Conversation date</label>
                                    <div class="col-sm-3" style="max-width: 240px;">
                                        <input type="datetime-local" class="form-control" name="waReviewSurveyConversationDate" id="waReviewSurveyConversationDate" value="<?= date("Y-m-d H:i") ?>" required>
                                    </div>
                                    <label for="waReviewSurveyPhone" class="col-sm-2 col-form-label text-right" style="max-width: 80px;">Phone</label>
                                    <div class="col-sm-3" style="max-width: 180px;">
                                        <input type="" class="form-control" name="waReviewSurveyPhone" id="waReviewSurveyPhone" value="" placeholder="Telp konsumen" required>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-3 bg-light">
                                    <label for="waReviewSurveyTicket" class="col-sm-2 col-form-label">Ticket #</label>
                                    <div class="col-sm-3" style="max-width: 240px;">
                                        <input type="" class="form-control" name="waReviewSurveyTicket" id="waReviewSurveyTicket" placeholder="No ticket jika ada">
                                    </div>
                                </div>

                                <!-- Greeting -->
                                <div class="form-group row border-bottom pb-3">
                                    <label for="waReviewSurveyResponseGood" class="col-sm-2 col-form-label" style="max-width: 110px; min-width: 100px">Response</label>
                                    <div class="col-sm-9">
                                        <div class="mb-2">
                                            <div class="pretty p-svg p-curve">
                                                <input type="radio" value="3" id="waReviewSurveyResponseGood" name="waReviewSurveyResponse" />
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
                                                <input type="radio" value="3" id="waReviewSurveyResponseLess" name="waReviewSurveyResponse" />
                                                <div class="state p-success">
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
                                                <input type="radio" value="1" id="waReviewSurveyResponseBad" name="waReviewSurveyResponse" />
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
                                                <input type="radio" value="10" id="waReviewSurveyAccuracyGood" name="waReviewSurveyAccuracy" />
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
                                                <input type="radio" value="5" id="waReviewSurveyAccuracyLess" name="waReviewSurveyAccuracy" />
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
                                                <input type="radio" value="1" id="waReviewSurveyAccuracyBad" name="waReviewSurveyAccuracy" />
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
                                                <input type="radio" value="10" id="waReviewSurveyWordingGood" name="waReviewSurveyWording" />
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
                                                <input type="radio" value="5" id="waReviewSurveyWordingLess" name="waReviewSurveyWording" />
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
                                                <input type="radio" value="1" id="waReviewSurveyWordingBad" name="waReviewSurveyWording" />
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
                                <a href="<?= base_url('voice/index') ?>">
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