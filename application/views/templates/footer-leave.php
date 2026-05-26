<footer class="main-footer">
    <div class="text-center">JnB | AdminLTE.io</div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
<!-- Popper JS-->
<script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url(); ?>assets/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url(); ?>assets/js/adminlte.min.js"></script>
<!-- Datetime Picker -->
<script src="<?= base_url(); ?>assets/js/jquery.datetimepicker.full.min.js"></script>
<!-- Sweetalert -->
<script src="<?= base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url(); ?>assets/js/toastr.min.js"></script>
<!-- Select2 -->
<script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
<!-- DataTable   -->
<script src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/js/responsive.bootstrap4.min.js"></script>
<style>
    /* Animasi sangkan hurungna leuwih gaya */
    /* Efek kartu anu keur aktif */
    .sholat-aktif {
        /* Konéng: rgba(255, 235, 59, 0.3) */
        background-color: rgba(255, 235, 59, 0.3) !important; 
        transform: scale(1.05); /* rada ngagedéan sakedik */
        box-shadow: 0 0 20px rgba(255, 235, 59, 0.5); /* glow konéng */
        transition: all 0.5s ease-in-out;
        border: 2px solid #ffeb3b !important; /* border konéng negeskeun */
        position: relative;
        z-index: 10;
    }

    /* Sangkan téksna tetep kabaca jelas (hideung/poék) */
    .sholat-aktif .card-header, 
    .sholat-aktif .card-body span {
        color: #333 !important; 
        font-weight: 800 !important;
    }

    /* Tambahan: animasi kedap-kedip sakedik sangkan leuwih interaktif */
    .sholat-aktif {
        animation: pulse-yellow 5s infinite;
    }

    @keyframes pulse-yellow {
        0% { box-shadow: 0 0 0 0 rgba(255, 235, 59, 0.3); }
        70% { box-shadow: 0 0 0 10px rgba(255, 235, 59, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 235, 59, 0); }
    }
</style>
<!-- Nyimpen alamat URL CI3 sangkan file JS misah apaleun kudu nembak ka mana -->
<script>
    // window.myApp = {
    //     tglHariIni: "<?php echo date('Y/m/d'); ?>"
    // };
    const jsVar = {
        baseUrl : '<?= base_url() ?>',
        CSRF_NAME : '<?= $this->security->get_csrf_token_name() ?>',
        CSRF_HASH : '<?= $this->security->get_csrf_hash() ?>',
        tglHariIni: "<?= date('Y/m/d'); ?>"
    }
</script>
<!-- Calendar -->
<!-- <script src="<?= base_url(); ?>assets/fullcalendar5110/scriptLeave5.js"></script> -->
<script src="<?= base_url('assets/fullcalendar5110/scriptLeave5.js?v=' . filemtime(FCPATH . 'assets/fullcalendar5110/scriptLeave5.js')) ?>"></script>
<script type="text/javascript">
	$(".preloader").fadeOut();
</script>

</body>

</html>