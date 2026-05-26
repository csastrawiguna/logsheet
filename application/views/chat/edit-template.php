<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
            require 'stylesheet.php';

            function usertag($wording, $user) {
                if (strpos($wording, '{{user}}') === false) {
                    return $wording;
                } else {
                    echo str_replace("{{user}}", $user, $wording);
                }   
            }

            function nulltodate($date) {
                if(is_null($date)) {
                    return '-';
                } else {
                    return date("d-M-Y", strtotime($date));
                }
            }

            $allowedUser = ['1', '5', '9'];
         ?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-purple">
                    <span class="h6">Edit Whatsapp's Reply Templates : <b><?= $template['name'] ?></b></span>
                    <div class="card-tools">
                        <?php if(in_array($this->session->userdata('role_access'), $allowedUser)) : ?>
                        <?php endif ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form method="POST" action="" class="col-md-8">
                            <div class="modal-body">
                                <input type="hidden" class="form-control" id="editTemplateId" name="editTemplateId" value="<?= $template['id'] ?>" readonly>
                                <div class="form-group">
                                    <label for="editTemplateName" class="form-control-label">Title (judul)</label>
                                    <input class="form-control" id="editTemplateName" name="editTemplateName" value="<?= $template['name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="editTemplateWording" class="form-control-label">Detail wording (redaksi kalimat)</label>
                                    <textarea class="form-control" id="editTemplateWording" name="editTemplateWording" placeholder="Detail wording" rows="4"><?= $template['wording'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="editTemplateRemark" class="form-control-label">Remark</label>
                                    <input class="form-control" id="editTemplateRemark" name="editTemplateRemark" value="<?= $template['remark'] ?>">
                                </div>
                                <div class="row mt-3">                
                                    <div class="col-sm">
                                        <button type="submit" class="btn btn-primary" name="searchMessageSubmit" id="searchMessageSubmit"><i class="fas fa-check"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>