<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                function statusToPositive($status) {
                    if ($status == 1) {
                        return 'p-on';
                    } else {
                        return 'p-off';
                    }
                }

                function statusToNegative($status) {
                    if ($status == 1) {
                        return 'p-off';
                    } else {
                        return 'p-on';
                    }
                }

                function invert($st) {
                    if ($st == 1) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
            ?>

            <!-- Header baru -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Edit User Data</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="" method="POST" class="form-horizontal col-sm-10">
                            <div class="form-group row">
                                <label for="user_id" class="col-sm-2 col-form-label text-right">User ID</label>
                                <div class="col-sm-3">
                                    <input type="" class="form-control" id="user_id" name="user_id" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fullname" class="col-sm-2 col-form-label text-right">Fullname</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" id="fullname" name="fullname" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="birthdate" class="col-sm-2 col-form-label text-right">Birth date</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="npk" class="col-sm-2 col-form-label text-right">NPK</label>
                                <div class="col-sm-3">
                                    <input type="" class="form-control" id="npk" name="npk" value="">
                                </div>
                                <label for="status" class="col-sm-2 col-form-label text-right">Status</label>
                                <div class="col-sm-3">
                                    <select type="" class="custom-select form-control" id="status" name="status">
                                        <option>- select status -</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Contract">Contract</option>
                                        <option value="OTS">OTS</option>
                                        <option value="Apprentice">Apprentice</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="joindate" class="col-sm-2 col-form-label text-right">Join date</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="joindate" name="joindate" value="">
                                </div>
                                <label for="retiredate" class="col-sm-2 col-form-label text-right">Retire date</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="retiredate" name="retiredate" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emailAddress" class="col-sm-2 col-form-label text-right">Email</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" id="emailAddress" name="emailAddress" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emailPersonal" class="col-sm-2 col-form-label text-right">Email personal</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" id="emailPersonal" name="emailPersonal" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="userMoodle" class="col-sm-2 col-form-label text-right">User Moodle</label>
                                <div class="col-sm-3">
                                    <input type="" class="form-control" id="userMoodle" name="userMoodle" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jobdesk" class="col-sm-2 col-form-label text-right">Jobdesk</label>
                                <div class="col-sm-5" style="min-width: 374px; max-width: 380px;">
                                    <select type="" class="js-example-basic-single custom-select" id="deptjobdesk" name="deptjobdesk">
                                        <option>- select department & jobdesk -</option>
                                        <?php foreach ($allDepartment as $dept) : ?>
                                            <option value="<?= $dept['jobcode']; ?>"><?= $dept['jobdesk']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role_access" class="col-sm-2 col-form-label text-right">Access</label>
                                <div class="col-sm-5" style="min-width: 374px; max-width: 380px;">
                                    <select type="" class="js-example-basic-single custom-select" id="role_access" name="role_access">
                                        <option>- select access level -</option>
                                        <?php foreach ($allAccess as $acc) : ?>
                                            <option value="<?= $acc['role_access']; ?>"><?= $acc['role_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <div class="pretty p-svg p-curve mt-2">
                                        <div class="pretty p-svg p-curve p-toggle">
                                            <input type="checkbox" name="is_active" id="is_active" value="">
                                            <div class="state p-primary p-primary-o p-on">
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <label>Active</label>
                                            </div>
                                            <div class="state p-danger p-danger-o p-off">
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <i class="icon mdi mdi-close"></i>
                                                <label>Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="is_active" class="col-sm-2 col-form-label text-right">Active?</label>
                                <div class="col-sm-3">
                                    <select type="" class="custom-select form-control" id="is_active" name="is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="replacement_for" class="col-sm-2 col-form-label text-right">Replacement for</label>
                                <div class="col-sm-5" style="min-width: 374px; max-width: 380px;">
                                    <select type="" class="js-example-basic-single custom-select" id="replacement_for" name="replacement_for">
                                        <option selected value="">- staf baru pengganti untuk -</option>
                                        <?php foreach($allUserid as $row) : ?>
                                            <option value="<?= $row['fullname'] ?>"><?= $row['npk'] . ' - ' . $row['fullname'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mpr_approval" class="col-sm-2 col-form-label text-right">MPR approval</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" id="mpr_approval" name="mpr_approval" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="user_add_remark" class="col-sm-2 col-form-label text-right">Remark</label>
                                <div class="col-sm-8">
                                    <input type="" class="form-control" id="user_add_remark" name="user_add_remark" value="" placeholder="Please fill remark if any">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-outline-primary mr-1">Save</button>
                                    <button type="reset" class="btn btn-outline-warning mr-1">Reset</button>
                                    <a href="<?= base_url('usermanagement/index') ?>"><button type="button" class="btn btn-outline-secondary">Cansel</button></a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                         
            </div>
        </div>
    </div>
</div> 