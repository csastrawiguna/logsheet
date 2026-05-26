<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style type="text/css">
        html{
            font-size: 14px;
        }
        .otchange{
            color: #6610F2;
            cursor: pointer;
        }
        .preloader {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 9999;
          background-color: #fff;
        }
        .preloader .loading {
          position: absolute;
          left: 50%;
          top: 50%;
          transform: translate(-50%,-50%);
          font: 14px arial;
        }
    </style>
    <title><?= $title; ?></title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->    
    <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/favicon/favicon-16x16.png" sizes="16x16" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/fontawesome-free/css/all.css">
    <!-- Linear icon -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/linearicons-free/linearicons-free.css">    
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Pretty checkbox -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/pretty-checkbox.min.css">
    <!-- Datetime Picker -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.datetimepicker.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/responsive.bootstrap4.min.css">
    <!-- Chart.js -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/Chart.min.css">
    <!-- Croppie JS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/croppie.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/summernote.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/toastr.min.css">
    <!-- Select2JS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2-bootstrap4.min.css">
    <!-- FullCalendar -->
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/css/main.min.css"> -->

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="preloader">
            <div class="loading">
                <img src="<?= base_url('assets/img/preloader/3ball.gif') ?>" width="">
                <p class="text-center text-danger mt-3">Please wait...</p>
            </div>
        </div>