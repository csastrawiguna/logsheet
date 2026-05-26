<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <div class="row">                
                <div class="col">
                    <div class="card">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card-header bg-primary">
                                Add General Info
                                <div class="card-tools">                                    
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <textarea id="settingAddGeneralInfoDetail" name="settingAddGeneralInfoDetail"></textarea>
                                <div class="form-group">
                                    <label for="settingAddGeneralInfoStatus" class="col-sm-8 col-form-label">Status</label>
                                    <div class="col-sm-2">
                                        <select type="number" class="form-control custom-select" id="settingAddGeneralInfoStatus" name="settingAddGeneralInfoStatus">
                                            <option value="">- select status -</option>
                                            <option value="2">Active</option>
                                            <option value="1">Sticky</option>
                                            <option value="3">Incative</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-outline-primary" id="settingAddGeneralInfoSubmit" name="settingAddGeneralInfoSubmit">Save</button>                                
                                <a href="<?= base_url('setting') ?>"><button type="button" class="btn btn-outline-secondary">Cancel</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- CKeditor 4.16.2 -->
<script src="<?= base_url('assets/ckeditor4.19/ckeditor.js') ?>"></script>
<script type="text/javascript">
    CKEDITOR.replace('settingAddGeneralInfoDetail', {       
        // Responsive Filemanager
        removePlugins : 'exportpdf',
        extraPlugins : 'filetools, ckeditorfa, dialog',
        allowedContent : true,
        disallowedContent : 'img{width,height}; img[width,height]',
        contentsCss : 'http://192.168.188.254/logsheet/assets/ckeditor4.19/plugins/ckeditorfa/css/ckeditorfa.css',
        filebrowserBrowseUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserUploadUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserImageBrowseUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
        filebrowserUploadMethod : 'form',        
    });

    CKEDITOR.on('instanceReady', function (ev) {
        ev.editor.dataProcessor.htmlFilter.addRules({
            elements: {
                img: function (el) {
                    el.addClass('img-fluid');
                }
            }
        });
    });
</script>

