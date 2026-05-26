<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php                
            $allowSetting = ['1', '5', '9'];

            function messageorderToText($val) {
                if ($val == 0) {
                    return 'By Chat';
                } else {
                    return 'Sticky first';
                }
            }
        ?>
       
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-purple">
                    Chat Message Setting
                    <div class="card-tools">
                        <a href="<?= base_url('chat') ?>" class="mr-3 text-white"><i class="fas fa-arrow-left"></i> Back to chat</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="" class="row">
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="chatSettingDayValue" class="col-sm-8 col-form-label">
                                    Duration chat displayed (days)<br>
                                    <em class="text-secondary font-weight-normal">Durasi pesan chat tampil (hari)</em>
                                </label>
                                <div class="col-sm-4">
                                    <input type="number" class="text-left form-control" id="chatSettingDayValue" name="chatSettingDayValue" value="<?= $existingSetting['day_chat_existing'] ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="chatSettingPinnedValue" class="col-sm-8 col-form-label">
                                    Duration of pinned/sticky displayed (days)<br>
                                    <em class="text-secondary font-weight-normal">Durasi pinned message tampil (hari)</em>
                                </label>
                                <div class="col-sm-4">
                                    <input type="number" class="text-left form-control" id="" name="chatSettingPinnedValue" value="<?= $existingSetting['day_pinned_stucked'] ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="chatSettingOrderby" class="col-sm-8 col-form-label">
                                    Message order list by<br>
                                    <em class="text-secondary font-weight-normal">Urutan chat berdasarkan</em>
                                </label>
                                <div class="col-sm-4">
                                    <select class="custom-select" id="chatSettingOrderby" name="chatSettingOrderby">
                                        <option value="<?= $existingSetting['message_order'] ?>"><?= messageorderToText($existingSetting['message_order']) ?></option>
                                        <option value="">- pilih -</option>
                                        <option value="0">By Chat</option>
                                        <option value="1">Pinned first</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border-top"></div>
                            <div class="row mt-3">                
                                <div class="col-sm">
                                    <button type="submit" class="btn btn-outline-primary" name="chatSettingSubmit" id="chatSettingSubmit">Save setting</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

