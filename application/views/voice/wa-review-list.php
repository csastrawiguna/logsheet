<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
          $maxScore = (int)$scoreList['high'] * 3;
          $allowedAccess = in_array($this->session->userdata('role_access'), ['1', '5', '7', '9'])
        ?>
        <div class="container-fluid pt-2 px-1">
            <div class="card">
                <div class="card-header bg-primary">
                    WA Reivew (All Data) periode: <?= date("d F Y", strtotime($startPeriod)) ?> - <?= date("d F Y", strtotime($endPeriod)) ?>
                    <div class="card-tools">
                        <a href="<?= base_url('voice/wareviewform') ?>" class="text-white mr-3"><i class="fas fa-plus-circle"></i> New WA Review</a>
                    </div>
                </div>
                <div class="card-body">                
                    <form action="" class="form-row mb-3" method="post" style="width: 820px;">
                        <label for="waReviewAllStartPeriod" class="col-sm-1">Period</label>
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="waReviewAllStartPeriod" name="waReviewAllStartPeriod" class="form-control" value="<?= $startPeriod ?>">
                        </div>-
                        <div class="col-sm-2" style="min-width: 160px;">
                            <input type="date" id="waReviewAllEndPeriod" name="waReviewAllEndPeriod" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1">
                          <div class="row">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectWaReviewAll" name="buttonSelectWaReviewAll">Go</button>      
                          </div>
                        </div>
                    </form>
                    <div class="border mb-3"></div>
                    <table class="table table-bordered dataTableBasic">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" class="align-middle">#</th>
                                <th rowspan="2" class="align-middle">Period</th>
                                <th rowspan="2" class="align-middle">Agent</th>
                                <th rowspan="2" class="align-middle">Datetime</th>
                                <th rowspan="2" class="align-middle">Cust. Phone</th>
                                <th colspan="4" class="align-middle text-center">Review</th>
                                <th rowspan="2" class="align-middle">Remark</th>
                                <th rowspan="2" class="align-middle">...</th>
                            </tr>
                            <tr>
                                <th>Response</th>
                                <th>Accuracy</th>
                                <th>Wording</th>
                                <th class="text-center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($waReviewList as $row) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= date("M Y", strtotime($row['period'])); ?></td>
                                    <td class="bg-light text-bold"><?= $row['agent'] ?></td>
                                    <td>
                                        <?= date("d M Y", strtotime($row['datetime'])) ?>
                                        <br>
                                        <?= date("H:i", strtotime($row['datetime'])) ?>
                                    </td>
                                    <td class="">
                                        <?= $row['customer_phone'] ?>
                                        <br>
                                        <span class="badge badge-secondary font-weight-normal"><?= $row['system_code'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_response']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['response_remark'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_accuracy']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['accuracy_remark'] ?></span>
                                    </td>
                                    <td>
                                        <?= itemScoreToColor($row['score_wording']) ?>
                                        <br>
                                        <span class="text-small"><?= $row['wording_remark'] ?></span>
                                    </td>
                                    <td class="text-center bg-light">
                                        <span class="text-bold text-primary"><?= number_format(($row['score_response'] + $row['score_accuracy'] + $row['score_wording']) / $maxScore * 100, 1) ?></span>
                                    </td>
                                    <td>
                                        <?= $row['remark'] ?>
                                    </td>
                                    <td>
                                        <a href="#" data-id="<?= $row['id'] ?>" data-agent="<?= $row['agent'] ?>" data-datetime="<?= $row['datetime'] ?>" class="buttonWaReviewViewDetail" data-toggle="modal" data-target="#modalWaReviewDetaiChatModal"><i class="fas fa-search text-info"></i></a> 
                                        <?php if ($allowedAccess) : ?>
                                            <br>
                                            <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewEdit"><i class="fas fa-edit text-secondary"></i></a>  &nbsp; 
                                            <a href="#" data-id="<?= $row['id'] ?>" class="buttonWaReviewViewDelete"><i class="fas fa-times text-danger"></i></a> 
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal View Detail per Row Data -->
<div class="modal fade" id="modalWaReviewDetaiChatModal" tabindex="-1" role="dialog" aria-labelledby="modalWaReviewDetaiChatModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document" style="min-width: 900px;">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalWaReviewDetaiChatModal">Agent's Chat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Desain Dashboard Agent (Bootstrap 4) -->
                <div class="d-flex flex-column bg-light rounded shadow-sm overflow-hidden" style="height: 75vh; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                    
                    <!-- Header Dashboard -->
                    <div class="p-3 text-white flex-shrink-0" style="background: linear-gradient(135deg, #dc2626, #ef4444); border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold" style="letter-spacing: 0.5px;">Agent Chat View</h6>
                            <span class="badge badge-dark font-weight-normal px-2 py-1" style="font-size: 11px; background-color: rgba(0,0,0,0.25);">Review ID: 7</span>
                        </div>
                        <div class="small text-white-50 d-flex align-items-center mt-1">
                            <span class="d-inline-block bg-success rounded-circle mr-2" style="width: 8px; height: 8px;"></span>
                            Chatting with: Bapak Darmady
                        </div>
                    </div>

                    <!-- Chat History Area -->
                    <div class="flex-grow-1 p-3 overflow-auto d-flex flex-column" style="background-color: #f0f2f5; gap: 14px;">
                        
                        <!-- Customer: Awal Chat -->
                        <div class="d-flex flex-column align-items-start w-100">
                            <small class="text-secondary font-weight-bold ml-1 mb-1" style="font-size: 11px;">👤 Customer</small>
                            <div class="p-2 text-dark shadow-sm border" style="background-color: #d9fdd3; border-radius: 0px 8px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #c0edb9 !important;">
                                Ini kulkas saya ngga dingin ya
                            </div>
                            <small class="text-muted ml-1 mt-1" style="font-size: 10px;">11:02:01</small>
                        </div>

                        <!-- Bot Response (Pihak Agent/Sistem - Katuhu) -->
                        <div class="d-flex flex-column align-items-end w-100">
                            <small class="text-secondary font-weight-bold mr-1 mb-1" style="font-size: 11px;">🤖 System Bot</small>
                            <div class="p-2 text-dark bg-white shadow-sm border" style="border-radius: 8px 0px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #e9edef !important;">
                                Selamat pagi Bapak. Silakan pilih menu layanan di bawah ini...
                            </div>
                            <small class="text-muted mr-1 mt-1" style="font-size: 10px;">11:02:01</small>
                        </div>

                        <!-- System Alert -->
                        <div class="d-flex justify-content-center my-1 w-100">
                            <div class="alert alert-primary py-2 px-3 text-center shadow-sm m-0" style="max-w: 90%; font-size: 12px; border-radius: 8px; border: none; background-color: #007bff; color: white;">
                                ⚙️ <b>Sistem:</b> Halo, Anda sudah terhubung dengan Agent Livechat SHARP.
                            </div>
                        </div>

                        <!-- Customer: Mengulang Keluhan -->
                        <div class="d-flex flex-column align-items-start w-100">
                            <div class="p-2 text-dark shadow-sm border" style="background-color: #d9fdd3; border-radius: 0px 8px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #c0edb9 !important;">
                                Ini kulkas saya tidak dingin ya
                            </div>
                            <small class="text-muted ml-1 mt-1" style="font-size: 10px;">11:03:49</small>
                        </div>

                        <!-- Response Time Label -->
                        <div class="d-flex justify-content-center my-1 w-100">
                            <span class="badge badge-warning text-dark border px-3 py-1 font-weight-normal shadow-sm" style="font-size: 11px; border-radius: 20px; background-color: #fffbeb; border-color: #fde68a !important;">
                                ⏱️ Anjeun merespon dina 41 menit
                            </span>
                        </div>

                        <!-- Agent (Anjeun/Tantri - Katuhu) -->
                        <div class="d-flex flex-column align-items-end w-100">
                            <small class="text-danger font-weight-bold mr-1 mb-1" style="font-size: 11px;">👩‍💼 Anjeun (Tantri)</small>
                            <div class="p-2 text-dark shadow-sm border" style="background-color: #e2f0fd; border-radius: 8px 0px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #cfe2fe !important;">
                                Terima kasih telah menggunakan layanan WhatsApp SHARP. Selamat siang, saya Tantri...
                            </div>
                            <small class="text-muted mr-1 mt-1" style="font-size: 10px;">11:45:16</small>
                        </div>

                        <!-- Agent (Anjeun/Tantri - Katuhu) -->
                        <div class="d-flex flex-column align-items-end w-100">
                            <div class="p-2 text-dark shadow-sm border" style="background-color: #e2f0fd; border-radius: 8px 0px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #cfe2fe !important;">
                                Baik Bapak Darmady untuk memastikan kendalanya, mangga turutan léngkah ieu...
                            </div>
                            <small class="text-muted mr-1 mt-1" style="font-size: 10px;">11:45:31</small>
                        </div>

                        <!-- Customer: Satuju -->
                        <div class="d-flex flex-column align-items-start w-100">
                            <div class="p-2 text-dark shadow-sm border" style="background-color: #d9fdd3; border-radius: 0px 8px 8px 8px; max-w: 80%; font-size: 13.5px; border-color: #c0edb9 !important;">
                                Ok bu nanti saya coba laksanakan saran dari ibu dulu
                            </div>
                            <small class="text-muted ml-1 mt-1" style="font-size: 10px;">11:46:56</small>
                        </div>

                    </div>

                    <!-- Footer Status -->
                    <div class="p-2 bg-light border-top flex-shrink-0">
                        <div class="w-100 text-center py-2 text-secondary font-weight-bold bg-white rounded border" style="font-size: 11px; border-color: #dee2e6 !important;">
                            🔒 History Chat Review ID #7 Closed
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-dismiss="modal"><i class="fas fa-check"></i> Done</button>
            </div>
        </div>
    </div>
</div>

<!-- buat chat -->
<!-- <?php 
// Conto logika kondisional dina jero loop PHP/Blade
if ($row->sender == 'customer') {
    $alignment = 'align-items-start'; // Rata kenca
    $bgColor = '#d9fdd3';             // Warna hejo WA
    $senderName = '👤 Customer';
    $borderRadius = 'border-radius: 0px 8px 8px 8px;';
} else {
    $alignment = 'align-items-end';   // Rata katuhu (Agent / Bot)
    $senderName = ($row->sender == 'agent') ? '👩‍💼 Anjeun (Tantri)' : '🤖 System Bot';
    $bgColor = ($row->sender == 'agent') ? '#e2f0fd' : '#ffffff'; // Biru keur agent, bodas keur bot
    $borderRadius = 'border-radius: 8px 0px 8px 8px;';
} ?> -->