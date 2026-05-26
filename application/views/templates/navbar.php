    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
                <h4 class="h4 ml-2"><?= $title; ?></h4>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <?= $this->session->userdata['userfullname']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                    <a href="<?= base_url('profile'); ?>" class="dropdown-item">
                        <i class="lnr lnr-user mr-1"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item" id="navButtonLogout">
                        <i class="lnr lnr-power-switch mr-1"></i> Logout
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <span class="lnr lnr-highlight"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" id="navbarSelectTheme">
                    <span class="dropdown-item dropdown-header">View theme</span>
                    <div class="dropdown-divider"></div>
                    <?php
                    $theme_list = $this->db->get('view_theme')->result_array();
                    foreach ($theme_list as $th) :
                    ?>
                        <span href="" class="dropdown-item" data-theme="<?= $th['id']; ?>" data-themename="<?= $th['theme_name']; ?>" data-themetext="<?= $th['theme_text']; ?>" data-userid="<?= $this->session->userdata('user_id'); ?>" data-controller="<?= $this->uri->segment(1); ?>" data-method="<?= $this->uri->segment(2); ?>" style="cursor: pointer;">
                            <span class=""><?= $th['theme_name']; ?></span>
                        </span>
                    <?php endforeach; ?>
            </li>
            <!-- Notifications Dropdown Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                    <i class="fas fa-th-large"></i>
                </a>
            </li> -->
        </ul>
    </nav>
    <!-- /.navbar -->