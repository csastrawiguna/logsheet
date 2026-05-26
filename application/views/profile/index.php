<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="row">
                <div class="col-auto">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline" style="width: 400px;">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="img-fluid img-circle" style="height: 140px;" src="<?= base_url(); ?>assets/img/profile/<?= $userDetail['photo']; ?>" alt="User profile picture">
                            </div>                            

                            <h3 class="profile-username text-center mt-3"><?= $userDetail['fullname']; ?></h3>
                            <p class="text-muted text-center">
                                <?= $userDetail['jobdesk']; ?><br>
                                <?= $userDetail['department_name']; ?>
                            </p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Birht date</b> <a class="float-right"><?= date('d F Y', strtotime($userDetail['birthdate'])); ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>NPK</b> <a class="float-right"><?= $userDetail['npk']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Join date</b> <a class="float-right">
                                        <?php
                                        if (strtotime($userDetail['joindate']) < 1) {
                                            echo "-";
                                        } else {
                                            echo date('d F Y', strtotime($userDetail['joindate']));
                                        }
                                        ?>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Employement</b> <a class="float-right"><?= $userDetail['status']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right"><?= $userDetail['email_address']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email personal</b> <a class="float-right"><?= $userDetail['email_personal']; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Moodle user</b> <a class="float-right"><?= $userDetail['user_moodle']; ?></a>
                                </li>
                            </ul>

                            <a href="<?= base_url('profile/edit') ?>" class="btn btn-sm btn-outline-primary float-right ml-1" title="Edit profile" id="buttonEditProfile"><i class="fas fa-pen"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-primary float-right ml-1" title="Update password" id="buttonUpdatePassword"><i class="fas fa-lock"></i></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6 col-auto" id="formUpdatePassword" style="display: none;">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline" style="max-width: 540px;">
                        <div class="card-header bg-light">
                            <h3 class="card-title text-primary">Update Password</h3>
                        </div>
                        <!-- /.card-header -->
                        <form class="form-horizontal" id="formUpdatePassword" method="post">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="oldPassword" class="col-sm-6 col-form-label">Old password</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword">
                                        <?= form_error('oldPassword', '<small class="text-danger">', '</small>'); ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="newPassword" class="col-sm-6 col-form-label">New password</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                                        <?= form_error('newPassword', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label for="confirmNewPassword" class="col-sm-6 col-form-label">Confirm New password</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword">
                                        <?= form_error('confirmNewPassword', '<small class="text-danger">', '</small>'); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" id="buttonSubmitUpdatePassword" class="btn btn-sm btn-outline-primary float-right"> Submit </button>
                            </div>
                            <!-- /.card-footer -->
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <div class="col-md-6 col-auto" id="formUpdateProfile" style="display: none;">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-header bg-light">
                            <h3 class="card-title text-primary">Edit Profile</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h5 class="text-center text-muted">
                                <p>We are sorry</p>
                                <p>Update profile will be available soon</p>
                            </h5>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.row -->
    </section>
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->