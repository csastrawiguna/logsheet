<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <style type="text/css">
            ul {
                list-style-type: none;
            }

            li {
                display: inline-block;
            }

            input[type="radio"][id^="bg"] {
                display: none;
            }

            label {
                border: 1px solid #fff;
                padding: 10px;
                display: block;
                position: relative;
                margin: 10px;
                cursor: pointer;
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            label::before {
                background-color: white;
                color: white;
                content: " ";
                display: block;
                border-radius: 50%;
                border: 1px solid grey;
                position: absolute;
                top: -5px;
                left: -5px;
                width: 25px;
                height: 25px;
                text-align: center;
                line-height: 28px;
                transition-duration: 0.4s;
                transform: scale(0);
            }

            label img {
/*                height: 300px;*/
                width: 600px;
                transition-duration: 0.2s;
                transform-origin: 50% 50%;
            }

            :checked+label {
                border-color: #17A2B8;
                border-radius: 4px;
            }

            :checked+label::before {
                content: "✓";
                background-color: #007BFF;
                transform: scale(1);
                line-height: 150%;
            }

            :checked+label img {
                transform: scale(0.9);
                box-shadow: 0 0 5px #333;
                z-index: -1;
            }

            #btnBgprofileUpdate {
                margin-top: 16px;
                /*height: 50px;
                line-height: 50px;*/
            }

        </style>

        <div class="container-fluid">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="row">
                <div class="card card-primary card-outline" style="width: 68%">
                    <form method="POST" action="">
                        <div class="card-header">
                            <span class="text-primary">Edit your personal email and quote</span>
                        </div>
                        <div class="card-body">
                                <div class="form-group">
                                    <label for="profileEditPersonalEmail" class="col-form-label" style="max-width: 160px; min-width: 150px;">Personal email</label>
                                    <div class="">
                                        <input type="email" class="form-control" id="profileEditPersonalEmail" name="profileEditPersonalEmail" value="<?= $currentData['email_personal'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="profileEditPersonalQuote" class="col-form-label" style="max-width: 160px; min-width: 150px;">Quote <small class="text-danger">(maks. 200 karakter)</small></label>
                                    <div class="">
                                        <textarea rows="1" class="form-control" id="profileEditPersonalQuote" name="profileEditPersonalQuote"><?= $currentData['quote'] ?></textarea>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary px-3">Update</button>
                            <a href="<?= base_url('profile') ?>"><span class="btn btn-outline-secondary">Cancel</span></a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="card card-primary card-outline" style="width: 68%">
                    <div class="card-header">
                        <span class="h6 text-primary">Change profile picture</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <?php if ($currentData['photo'] == 'nophoto.png') :  ?>
                                <div class="col-sm-12">
                                    <p class="rounded display-4 text-center" style="padding-top: 18px; padding-bottom: 18px; font-size: 32px; background-color: #f0f0f0; color:#AE445A">Your account has no profile picture<br><span class="lead" style="color: #040D12;">Please upload your best photo for your more expressive personal profile</span></p>
                                </div>
                            <?php else : ?>
                                <label for="" class="col-sm-2 col-form-label" style="max-width: 160px; min-width: 70px;">Current profile</label>
                                <div class="col-sm-10">
                                    <img class="img border rounded" src="<?= base_url('assets/img/profile/') . $currentData['photo'] ?>" style="width: 100%;">
                                    <div class="mt-1">
                                        <button type="button" class="btn btn-outline-danger" id="buttonProfileEditRemovePhoto"><i class="fas fa-times"></i> Remove photo</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?= form_open_multipart('profile/processuploadpicture') ?>
                            <div class="form-group row mt-4">
                                <label for="profileEditPhoto" class="col-sm-2 col-form-label" style="max-width: 160px; min-width: 70px;">Change picture</label>
                                <div class="col-sm-8">
                                    <div class="custom-file">
                                        <input accept="image/*" type="file" class="custom-file-input" name="profileEditProfilePicture" id="profileEditProfilePicture" title="Gambar harus berukuran persegi (width x height sama)">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    <small class="font-italic">Dimensi foto harus berukuran persegi (width x height sama)</small>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-outline-primary">Upload</button>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <div id="textPicturePreviewContainer" style="display: none;">
                                    <span class="btn btn-success col-sm-2" style="height: 40px;line-height: 20px; cursor: auto;"> Preview </span>
                                    <img class="img col-sm-10 round" src="" id="profileEditPhotoDirectPreview" style="width: 100%;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card card-primary card-outline col" style="width: 100%">
                    <div class="card-header">
                        <span class="h6 text-primary">Background picture (viewed on Dashboard)</span>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="bguserid" name="bguserid" value="<?= $this->session->userdata('user_id') ?>">
                        <?php foreach ($allbackground as $row) : ?>
                            <?php if ($row['bg'] == 'bg_cuparsa.jpg') : ?>
                                <?php continue; ?>
                            <?php else : ?>
                                <ul>
                                    <li>
                                        <input type="radio" id="<?= $row['bg'] ?>" name="bg_select" value="<?= $row['bg'] ?>" class="bgname">
                                        <label for="<?= $row['bg'] ?>"><img src="<?= base_url('assets/img/profile-bg/' . $row['bg'])?>"></label>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="row ml-3">
                            <div class="col-sm-auto" style="margin-top: 19px;">
                                <b class="text-primary">Background offset position :</b>
                            </div>
                            <div class="col-sm-2" style="max-width: 120px">
                                <input type="radio" name="bg_position" class="bgposition" id="bg_position_top" value="top">
                                <label for="bg_position_top" class="text-center">Top</label>
                            </div>
                            <div class="col-sm-2" style="max-width: 120px">
                                <input type="radio" name="bg_position" class="bgposition" id="bg_position_center" value="center">
                                <label for="bg_position_center" class="text-center">Center</label>
                            </div>
                            <div class="col-sm-2" style="max-width: 120px">
                                <input type="radio" name="bg_position" class="bgposition" id="bg_position_bottom" value="bottom">
                                <label for="bg_position_bottom" class="text-center">Bottom</label>
                            </div>
                            <div class="col-sm-2" style="max-width: 120px">
                                <input type="radio" name="bg_position" class="bgposition" id="bg_position_left" value="left">
                                <label for="bg_position_left" class="text-center">Left</label>
                            </div>
                            <div class="col-sm-2" style="max-width: 120px">
                                <input type="radio" name="bg_position" class="bgposition" id="bg_position_right" value="right">
                                <label for="bg_position_right" class="text-center">Right</label>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-outline-primary" id="btnBgprofileUpdate">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    profileEditProfilePicture.onchange = evt => {
        document.getElementById("textPicturePreviewContainer").style.display = 'flex';
        const [file] = profileEditProfilePicture.files;
        if (file) {
            profileEditPhotoDirectPreview.src = URL.createObjectURL(file)
        }
    }
</script>
