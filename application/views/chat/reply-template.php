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
                    <span class="h6">Whatsapp's Reply Templates</span>
                    <div class="card-tools">
                        <a href="<?= base_url('chat') ?>" class="mr-3 text-white"><i class="fas fa-arrow-left"></i> Back to chat</a>
                        <?php if(in_array($this->session->userdata('role_access'), $allowedUser)) : ?>
                            <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#modalAddTemplate"><i class="fas fa-plus-circle"></i> Add Template</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered tableBasicDataTable">
                        <thead>
                            <tr class="">
                                <th class="col-sm-auto">#</th>
                                <th class="col-sm-2">Title</th>
                                <th class="col-sm-7">Wording</th>
                                <th class="col-sm-auto" style="width: 40px;">Remark</th>
                                <th class="col-sm-2">Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($templates as $row) : ?>
                                <tr class="">
                                    <td class="col-sm-auto"><?= $i++ ?></td>
                                    <td class="col-sm-2"><?= $row['name'] ?></td>
                                    <td class="col-sm-7">
                                        <span><?= usertag($row['wording'], $this->session->userdata('user_id')) ?></span><!-- 
                                        <br><button class="float-right btn badge badge-primary font-weight-normal pt-0 px-2 buttonCopyTemplate">Copy</button> -->        
                                    </td>
                                    <td class="col-sm-auto"><?= $row['remark'] ?></td>
                                    <td class="col-sm-2">
                                        <small class="text-bold"><?= $row['saved_by'] ?></small><br>
                                        <small><?= date("d-M-Y",  strtotime($row['saved_at'])) ?></small>
                                        <?php if(in_array($this->session->userdata('role_access'), $allowedUser)) : ?>
                                            <span class="float-right" style="position: relative;">
                                                <button type="button" class="btn btn-danger btn-xs btnDeleteReplyTemplate" data-id="<?= $row['id'] ?>"><i class="fas fa-times"></i></button>
                                                <a href="<?= base_url('chat/edittemplate/') . $row['id'] ?>" class="btn btn-outline-warning btn-xs"><i class="fas fa-edit"></i></a>
                                            </span>
                                        <?php endif; ?>
                                        <hr>
                                        <small><em>Last update:</em></small><br>
                                        <span class="badge badge-light font-weight-normal"><?= $row['updated_by'] ?></span>
                                        <small><?= nulltodate($row['updated_at']) ?></small><br>
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

<div class="modal fade" id="modalAddTemplate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalAddTemplateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddTemplateLabel">Add New Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addTemplateName" class="form-control-label">Title (judul)</label>
                        <input class="form-control" id="addTemplateName" name="addTemplateName" placeholder="Judul template">
                    </div>
                    <div class="form-group">
                        <label for="addTemplateWording" class="form-control-label">Detail wording (redaksi kalimat)</label>
                        <textarea class="form-control" id="addTemplateWording" name="addTemplateWording" placeholder="Detail wording" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="addTemplateRemark" class="form-control-label">Title (judul)</label>
                        <input class="form-control" id="addTemplateRemark" name="addTemplateRemark" placeholder="Additional notes">
                    </div>
                    <div class="row mt-3">                
                        <div class="col-sm">
                            <button type="submit" class="btn btn-primary" name="searchMessageSubmit" id="searchMessageSubmit"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>