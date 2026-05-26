<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm my-3 py-0">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <span class="text-primary">Manage Access by User Access</span>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <form action="" class="form-row mb-5" method="post" style="width: 640px;">
                    <label for="menumanagementSelectAccess" class="col-sm-4">Access type/level</label>
                    <div class="col-sm-4">
                      <select class="custom-select" name="menumanagementSelectAccess" id="menumanagementSelectAccess">
                        <option value="<?= $accessLevel; ?>" selected><?= $role; ?></option>
                        <?php foreach ($allAccessLevel as $data) : ?>
                          <option value="<?= $data['role_access']; ?>"><?= $data['role_name']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-sm-1">
                      <button type="submit" class="btn btn-outline-primary" id="buttonSelectRoleMenuAccess" name="buttonSelectRoleMenuAccess">Go</button>
                    </div>
                    <div class="col-sm">
                      <button type="button" class="btn btn-outline-primary" id="buttonAddMenuAccess" name="buttonAddMenuAccess" data-toggle="modal" data-target="#modalMenuAccessAdd">Add Menu Access</button>
                    </div>
                  </form>
                </div>
              </div>
              
              <div class="row">
                <div class="col-4 ml-3">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Menu</th>
                        <th>...</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($allMenus as $menu) : ?>
                        <tr>
                          <td class="col"><span class="<?= $menu['icon'] ?>"></span> <?= $menu['menu_name'] ?></td>
                          <td>
                            <button class="btn badge btn-danger buttonDismissMenuAccess" data-menuid="<?= $menu['menu_id'] ?>" data-roleaccess="<?= $accessLevel ?>">Dismiss</button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
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

<div class="modal fade" id="modalMenuAccessAdd" tabindex="-1" role="dialog" aria-labelledby="modalMenuAccessAdd" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalMenuAccessAdd">Add Menu Access</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="tableUnassignedMenu" class="table table-sm">
          <thead>
            <tr>
              <th>Menu</th>
              <th>...</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($unassignedMenu as $umenu) : ?>
              <tr>
                <td><span class="<?= $umenu['menu_icon'] ?>"></span> <?= $umenu['menu_name'] ?></td>
                <td>
                  <div class="pretty p-switch p-fill">
                    <input type="checkbox" class="unassignedMenuAcces" data-menuid="<?= $umenu['menu_id'] ?>">
                    <div class="state p-success">
                      <label></label>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" name="menuAccessAdd" id="menuAccessAdd">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- <script type="text/javascript">
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script> -->