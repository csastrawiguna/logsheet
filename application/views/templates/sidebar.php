<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 <?= $this->session->userdata('theme_text'); ?> ">
    <!-- Brand Logo -->
    <a href="<?= base_url(); ?>" class="brand-link">
        <img src="<?= base_url('assets/img/logo/logsheet2.png'); ?>" alt="" class="brand-image elevation-3" style="opacity: .8" height="30">
        <span class="font-weight-bold italic">Logsheet </span><small>v1.5</small>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Query menu -->
                <?php
                $role_access = $this->session->userdata['role_access'];
                $queryMenu = "SELECT menu_access.menu_id AS id, menu.menu_name AS menu, menu.link AS link, menu.icon AS icon
                              FROM menu_access JOIN menu
                              ON menu.menu_id = menu_access.menu_id
                              WHERE menu_access.role_access = '$role_access'
                              ORDER BY menu_access.menu_id ASC
                            ";
                $menu = $this->db->query($queryMenu)->result_array();
                ?>

                <?php
                foreach ($menu as $m) :
                    $menuUrl = $this->uri->segment(1);
                    if ($m['link'] == $menuUrl) :
                ?>
                        <li class="nav-item has-treeview menu-open">
                            <a href="<?= base_url($m['link']); ?>" class="nav-link">
                                <i class="nav-icon <?= $m['icon']; ?>"></i>
                                <p>
                                    <?= $m['menu']; ?>
                                </p>
                            </a>
                            <?php
                            $mid = $m['id'];
                            $querySubmenu = "SELECT submenu.id AS id, submenu.submenu_name AS submenu_name, submenu.menu_id AS menu_id, submenu.submenu_link AS submenu_link, submenu_access.role_access AS role_access
                                         FROM submenu JOIN submenu_access
                                         ON submenu.id = submenu_access.submenu_id
                                         WHERE submenu.menu_id = '$mid'
                                         AND submenu_access.role_access = '$role_access'
                                         ORDER BY submenu_id ASC
                                        ";
                            $submenu = $this->db->query($querySubmenu)->result_array();
                            ?>

                            <?php foreach ($submenu as $sm) :
                                $submenuUrl = $this->uri->segment(1) . '/' . $this->uri->segment(2);
                                if ($sm['submenu_link'] == $submenuUrl) :
                            ?>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= base_url($sm['submenu_link']);  ?>" class="nav-link active">
                                                <i class="far fa-circle nav-icon ml-4"></i>
                                                <p><?= $sm['submenu_name']; ?></p>
                                            </a>
                                        </li>
                                    </ul>
                                <?php else : ?>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="<?= base_url($sm['submenu_link']);  ?>" class="nav-link">
                                                <i class="far fa-circle nav-icon ml-4"></i>
                                                <p><?= $sm['submenu_name']; ?></p>
                                            </a>
                                        </li>
                                    </ul>
                            <?php
                                endif;
                            endforeach; ?>
                        </li>
                    <?php else : ?>
                        <li class="nav-item has-treeview">
                            <a href="<?= base_url($m['link']); ?>" class="nav-link">
                                <i class="nav-icon <?= $m['icon']; ?>"></i>
                                <p>
                                    <?= $m['menu']; ?>
                                </p>
                            </a>
                            <?php
                            $mid = $m['id'];
                            $querySubmenu = "SELECT submenu.id AS id, submenu.submenu_name AS submenu_name, submenu.menu_id AS menu_id, submenu.submenu_link AS submenu_link, submenu_access.role_access AS role_access
                                         FROM submenu JOIN submenu_access
                                         ON submenu.id = submenu_access.submenu_id
                                         WHERE submenu.menu_id = '$mid'
                                         AND submenu_access.role_access = '$role_access'
                                         ORDER BY submenu_id ASC
                                        ";
                            $submenu = $this->db->query($querySubmenu)->result_array();
                            ?>

                            <?php foreach ($submenu as $sm) : ?>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url($sm['submenu_link']);  ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon ml-4"></i>
                                            <p><?= $sm['submenu_name']; ?></p>
                                        </a>
                                    </li>
                                </ul>
                            <?php endforeach; ?>
                        </li>
                <?php
                    endif;
                endforeach;
                ?>
                <li class="nav-item">
                    
                    <form action="http://192.168.188.254/cccinfo/auth/loginfromlogsheet/" method="post">
                        <input type="hidden" name="logsheetUserid" value="<?= $this->session->userdata('user_id') ?>">
                        <input type="hidden" name="logsheetPassword" value="<?= $this->session->userdata('user_password') ?>">
                        
                        <button class="btn nav-link" id="">
                            <span class="nav-icon lnr lnr-link"></span>
                            <?php if($this->session->userdata('theme_text') == 'sidebar-dark-primary'): ?>
                                <p class="text-white">
                                    CCC Info
                                </p>
                            <?php else : ?>
                                <p class="text-dark">
                                    CCC Info
                                </p>
                            <?php endif; ?>
                        </button>
                    </form>
                </li>

                <hr>

                <li class="nav-item">
                    <a href="<?= base_url('auth/logout'); ?>" class="nav-link" id="buttonLogout">
                        <span class="lnr lnr-power-switch"></span>
                        <p>
                            Logout 
                        </p>
                    </a>
                </li>
            </ul>
        </nav>        
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>