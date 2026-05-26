<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm my-3 py-0">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <span class="text-primary">Manage Submenu access by user type/level</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form action="" class="form-row mb-5" method="post" style="width: 520px;">
                                        <label for="menumanagementSelectAccessSubmenu" class="col-sm-4">Access type/level</label>
                                        <div class="col-sm-6">
                                            <select class="custom-select" name="menumanagementSelectAccessSubmenu" id="menumanagementSelectAccessSubmenu">
                                                <option value="<?= $accessLevel; ?>" selected><?= $role; ?></option>
                                                <?php foreach ($allAccessLevel as $data) : ?>
                                                    <option value="<?= $data['role_access']; ?>"><?= $data['role_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="row ml-1">
                                                <button type="submit" class="btn btn-outline-primary" id="buttonSelectSubmenuAccess" name="buttonSelectSubmenuAccess">Go</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <?php foreach ($allMenus as $menu) : ?>
                                        <div class="card collapsed-card">
                                            <div class="card-header bg-primary">
                                                <h3 class="card-title"><span class="<?= $menu['icon'] ?>"></span> &nbsp <?= $menu['menu_name'] ?></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <?php
                                                $submenus = $this->db->get_where('submenu', ['menu_id' => $menu['menu_id']])->result_array();
                                                ?>
                                                <?php foreach ($submenus as $sm) : ?>
                                                    <table class="table table-sm table-borderless col-8">
                                                        <tbody>
                                                            <tr>
                                                                <td class="col"><?= $sm['submenu_name'] ?></td>
                                                                <td>
                                                                    <div class="pretty p-switch p-fill">
                                                                        <input type="checkbox" name="userAssigned" class="submenuAccessCheckbox" <?= check_submenu_access($sm['id'], $accessLevel); ?> data-submenuid = "<?= $sm['id']; ?>" data-roleaccess = "<?= $accessLevel ?>">
                                                                        <div class="state p-success">
                                                                            <label></label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- <script type="text/javascript">
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script> -->