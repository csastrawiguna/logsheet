<div class="bg-light" style="min-height: 100vh;height: 100%; width: 100%; background:url(<?= base_url('assets/img/bg/r_domenico-loia-310197-unsplash.jpg');?>) no-repeat center center; background-size: cover; position: fixed;z-index: -1; overflow: hidden;">
</div>
<span style="position: fixed; bottom: 10px; right: 20px; color: #fff; z-index: 10">image: <a href="https://unsplash.com/photos/hGV2TfOh0ns" target="_blank" style="color: #fff">www.unsplash.com</a></span>
<div class="" style="min-height: 100vh;height: 100%; width: 100%; background-color: rgba(210,210,210,0.6); background-size: cover; position: relative; z-index: 9; overflow: hidden;" >
    <div class="row">
        <div class="col-4 mx-auto" style="margin-top: 26vh; max-width: 360px; min-width: 350px;">
            <div class="card">
                <div class="card-header text-center bg-light" style="font-size: 40px; height: 80px;">
                    <!-- <span class="lnr lnr-layers"></span><span style="font-size: 32px;"> LOGSHEET</span> -->
                    <img src="<?= base_url('assets/img/logo/logsheet.png') ?>" class="img" height="32">
                </div>
                <div class="card-body login-card-body">
                    <form action="" class="text-center mt-4 mb-3" method="post">
                        <div class="input-group mb-3">
                            <input type="" class="form-control" name="username" id="username" placeholder="Username or CTI ID" autofocus="" value="<?= set_value('username') ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="lnr lnr-user"></span>
                                </div>
                            </div>
                        </div>
                        <di class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="lnr lnr-lock"></span>
                                </div>
                            </div>
                        </di px-3v>
                        <div class="row">
                            <!-- /.col -->
                            <!-- <div class="col-8"></div> -->
                            <div class="col text-right">
                                <button type="submit" name="submit" class="mb-2 btn btn-primary btn-block">Login</button>
                                <a href="<?= base_url('auth/formResetPassword'); ?>" type="button" name="submit" class="text-secondary text-right">Forgot password</a>
                            </div>

                            <!-- /.col -->
                        </div>
                    </form>
                    <div class="text-center" style="margin: auto;">
                        <?= $this->session->flashdata('message'); ?>
                    </div>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>