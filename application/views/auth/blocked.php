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



        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- 404 Error Text -->
          <div class="text-center">
            <div class="error mx-auto mt-5 lead" data-text="403">403</div>
            <p class="lead text-gray-800 mb-5">Access forbidden</p>
            <p class="text-gray-500 mb-0">You are not allowed or not have permission to access</p>
            <a href="<?= base_url('dashboard');  ?>">&larr; Back to Dashboard</a>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

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