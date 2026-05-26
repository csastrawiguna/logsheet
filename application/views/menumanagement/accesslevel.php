<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm my-3 py-0">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <span class="text-primary">List of Access Level</span>
              <div class="card-tools">
                <a type="button" class="text-primary mr-2" id="buttonAddAccessLevel" name="buttonAddAccessLevel" data-toggle="modal" data-target="#modalAddAccessLevel"><span class="lnr lnr-plus-circle"></span> Add Access Level</a>
              </div>
            </div>
            <div class="card-body">              
              <div class="row">
                <div class="col-6 ml-3">
                  <table class="table table-borderless">
                    <thead>
                      <tr class="border-bottom">
                        <th>Id</th>
                        <th>Menu</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($allAccessLevel as $accesslevel) : ?>
                        <tr class="border-bottom">
                          <td><?= $accesslevel['role_access'] ?></td>
                          <td><?= $accesslevel['role_name'] ?></td>
                          <td>
                            <button class="btn badge btn-danger" data-roleaccess = "<?= $accesslevel['role_access'] ?>">Delete</button>
                            <button class="btn badge btn-warning" data-toggle="modal" data-target="#modalAddAccessLevel" data-roleaccess = "<?= $accesslevel['role_access'] ?>">Edit</button>
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

<div class="modal fade" id="modalAddAccessLevel" tabindex="-1" role="dialog" aria-labelledby="modalAddAccessLevel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAddAccessLevel">Add Access Level</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="formAddAccessLevelRoleId" id="formAddAccessLevelRoleId">
          <div class="form-group">
            <label for="exampleFormControlInput1">Role</label>
            <input type="" class="form-control" id="formAddAccessLevelRole" name="formAddAccessLevelRole" placeholder="example: user_department">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Role Name</label>
            <input type="" class="form-control" id="formAddAccessLevelRoleName" name="formAddAccessLevelRoleName" placeholder="example: User Department">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="buttonAddAccessLevel" id="buttonAddAccessLevel">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>