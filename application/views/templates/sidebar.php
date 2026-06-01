<aside class="main-sidebar elevation-4 <?= $this->session->userdata('theme_text'); ?>">
    <a href="<?= base_url(); ?>" class="brand-link">
        <img src="<?= base_url('assets/img/logo/logsheet2.png'); ?>" alt="" class="brand-image elevation-3" style="opacity: .8" height="30">
        <span class="font-weight-bold italic">Logsheet </span><small>v1.5</small>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <?php
                $menuUrl    = $this->uri->segment(1);
                $submenuUrl = $this->uri->segment(1) . '/' . $this->uri->segment(2);
                ?>

                <?php foreach ($sidebar_menu as $m) : 
                    $isMenuOpen = ($m['link'] == $menuUrl);
                ?>
                    <li class="nav-item has-treeview <?= $isMenuOpen ? 'menu-open' : ''; ?>">
                        <a href="<?= base_url($m['link']); ?>" class="nav-link">
                            <i class="nav-icon <?= $m['icon']; ?>"></i>
                            <p>
                                <?= $m['menu']; ?>
                                <?php if (!empty($m['submenu'])) : ?>
                                    <i class="right fas fa-angle-left"></i>
                                <?php endif; ?>
                            </p>
                        </a>

                        <?php if (!empty($m['submenu'])) : ?>
                            <ul class="nav nav-treeview">
                                <?php foreach ($m['submenu'] as $sm) : 
                                    $isActive = ($sm['submenu_link'] == $submenuUrl) ? 'active' : '';
                                ?>
                                    <li class="nav-item">
                                        <a href="<?= base_url($sm['submenu_link']); ?>" class="nav-link <?= $isActive; ?>">
                                            <i class="far fa-circle nav-icon ml-4"></i>
                                            <p><?= $sm['submenu_name']; ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

                <li class="nav-item">
                    <form action="http://192.168.188.254/cccinfo/auth/loginfromlogsheet/" method="post">
                        <input type="hidden" name="logsheetUserid" value="<?= $this->session->userdata('user_id') ?>">
                        <input type="hidden" name="logsheetPassword" value="<?= $this->session->userdata('user_password') ?>">
                        
                        <button class="btn nav-link text-left w-100" type="submit">
                            <span class="nav-icon lnr lnr-link"></span>
                            <?php $themeClass = ($this->session->userdata('theme_text') == 'sidebar-dark-primary') ? 'text-white' : 'text-dark'; ?>
                            <p class="<?= $themeClass; ?> d-inline ml-1">CCC Info</p>
                        </button>
                    </form>
                </li>

                <hr class="my-2" style="border-top: 1px solid rgba(255,255,255,.1);">

                <li class="nav-item">
                    <a href="<?= base_url('auth/logout'); ?>" class="nav-link" id="buttonLogout">
                        <span class="nav-icon lnr lnr-power-switch"></span>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>        
        </div>
    </aside>