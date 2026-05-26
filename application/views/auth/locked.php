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
                <div class="container-fluid">

                    <!-- 404 Error Text -->
                    <div class="text-center" style="margin-top: 30vh;">
                        <div class="h1 mx-auto text-dark mt-5 mb-3" data-text="">USER LOCKED!</div>
                        <p class="h4 text-gray-800">We're not sure, but it may caused by too many attempt wrong password!</p>
                        <p class="mute text-gray mb-5">Lamun password poho ulah dipaksa login atuh! Inget-inget matakna password teh</p>
                        <p class="text-gray-500 mb-3">Please contact leader/administrator to recover your account</p>
                        <form action="<?= base_url('auth/resetAccount'); ?>" method="post">
                            <input type="hidden" name="locked_userid" value="<?= $user_id; ?>">
                            <input type="hidden" name="locked_ipaddress" value="<?= $this->input->ip_address(); ?>">
                            <button type="submit" class="btn btn-outline-danger">Send request</button>
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