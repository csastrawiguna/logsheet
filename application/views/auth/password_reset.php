<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->
    <link rel="icon" href="<?= base_url(); ?>assets/img/log.ico">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/fontawesome-free/css/all.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Pretty checkbox -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/pretty-checkbox.min.css">
    <!-- Pretty checkbox -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/sweetalert2.min.css">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>


                <!-- Begin Page Content -->
                <div class="container">
                    <!-- 404 Error Text -->
                    <div class="text-center" style="margin: 30vh 25vw;">
                        <div class="h1 mx-auto text-indigo mt-5 mb-3" data-text="">Password Reset Request</div>
                        <p class="mute text-gray mb-5">Heuuuu.... Inget-inget matakna password teh</p>
                        <form action="<?= base_url('auth/resetPassword'); ?>" method="post" class="row">
                            <input type="hidden" name="reset_ipaddress" value="<?= $this->input->ip_address(); ?>">
                            <div class="col-3 text-right">
                                <label for="reset_userid">User ID</label>
                            </div>
                            <input type="" name="reset_userid" id="reset_userid" value="" class="col-5">
                            <div class="col-4 text-left">
                                <button type="submit" class="btn btn-outline-primary">Send request</button>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?= base_url(); ?>assets/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
    <!-- Sweetalert -->
    <script src="<?= base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
    <!-- Script sendiri -->
    <script src="<?= base_url(); ?>assets/js/myscript.js"></script>

</body>

</html>