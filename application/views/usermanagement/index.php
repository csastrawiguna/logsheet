 <div class="content-wrapper">
     <div class="content-header">
         <div class="container-fluid">
             <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
             <div class="row">
                 <div class="col-7">
                     <!-- Header baru -->
                     <div class="card">
                        <div class="card-header bg-primary">
                         <h3 class="card-title">User list</h3>

                         <div class="card-tools">
                             <div class="input-group input-group-sm">
                                 <!-- <input type="text" name="table_search" class="form-control float-right mr-1" placeholder="Search"> -->
                                 <a href="<?= base_url('usermanagement/viewactive') ?>">
                                     <button type="button" class="btn btn-sm btn-light mr-1"><span class="fas fa-search"></span> All</button>
                                 </a>
                                 <button type="button" class="btn btn-sm btn-light mr-1" id="buttonShowUserByBirthdate"><span class="fas fa-calendar-alt"></span> Show birthdate</button>
                                 <button type="button" class="btn btn-sm btn-light mr-1" id="buttonShowUserByJoindate"><span class="fas fa-search"></span> Show joindate</button>
                                 <a href="<?= base_url('usermanagement/add') ?>" class="ml-1">
                                    <button type="button" class="btn btn-sm btn-light"><i class="fas fa-plus-circle"></i> Add</button>
                                </a>
                             </div>
                         </div>
                        </div>
                        <!-- <table class="table table-borderless" id="tableUserLists">
                            <thead style="display: none;">
                                <tr><th>#</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alluser as $all) : ?>
                                    <tr style="padding-y: 0px; margin-y: 0px">
                                        <td style="padding-y: 0px; margin-y: 0px">
                                            <div class="card card-light collapsed-card">
                                                <div class="card-header">
                                                    <?php if ($all['is_active'] == 1) : ?>
                                                        <h3 class="card-title text-primary"><?= $all['user_id']; ?></h3>
                                                    <?php else : ?>
                                                        <h3 class="card-title text-danger"><?= $all['user_id']; ?></h3>
                                                    <?php endif; ?>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool buttonDeleteUser" data-userid="<?= $all['user_id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <a href="<?= base_url('usermanagement/edit/') . $all['user_id'] ?>" class="btn btn-tool">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table cellpadding="5">
                                                        <tbody>
                                                            <tr>
                                                                <td class="pr-3">Fullname</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1">
                                                                    <?= $all['fullname']; ?>
                                                                    <?php
                                                                        if ($all['is_active'] == 1) {
                                                                            echo '<label class="badge badge-primary badge-pill">Active</label>';
                                                                        } else {
                                                                            echo '<label class="badge badge-danger badge-pill">Inactive</label>';
                                                                        }
                                                                    ?>
                                                                    <span class="badge badge-pill badge-primary"><?= $all['role_name']; ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">NPK</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1">
                                                                    <?= $all['npk']; ?>
                                                                    <span class="badge badge-pill badge-info"><?= $all['status']; ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Birth date</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= date('d-M-Y', strtotime($all['birthdate'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Join date</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= date('d-M-Y', strtotime($all['joindate'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Retire date</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1">
                                                                    <?php if ($all['retiredate'] == 0) {
                                                                            echo "-";
                                                                        } else {
                                                                            echo date('d-M-Y', strtotime($all['retiredate']));
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Email</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['email_address']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Email personal</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['email_personal']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Department</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['department_name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Section</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['section']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Jobdesk</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['jobdesk']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Moodle username</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['user_moodle']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">Replacement for</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['replacement_for']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-3">MPR approval</td>
                                                                <td class="px-3">:</td>
                                                                <td class="pl-1"><?= $all['mpr_approval']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                <img class="img img-fluid rounded" src="<?= base_url('assets/img/profile/') . $all['photo'] ?>" style="width: 200px;">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>    
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table> -->

                        <div class="card-body" style="height: 648px; overflow-y: scroll;">
                             <?php foreach ($alluser as $all) : ?>
                                 <div class="card card-light collapsed-card">
                                     <div class="card-header">
                                         <?php if ($all['is_active'] == 1) { ?>
                                             <h3 class="card-title text-primary"><?= $all['user_id']; ?></h3>
                                         <?php } else { ?>
                                             <h3 class="card-title text-danger"><?= $all['user_id']; ?></h3>
                                         <?php } ?>
                                         <div class="card-tools">
                                             <button type="button" class="btn btn-tool buttonDeleteUser" data-userid="<?= $all['user_id']; ?>"><i class="fas fa-trash"></i>
                                             </button>
                                             <a href="<?= base_url('usermanagement/edit/') . $all['user_id'] ?>" class="btn btn-tool">
                                                <i class="fas fa-edit"></i>
                                             </a>
                                             <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                             </button>
                                         </div>
                                     </div>
                                     <div class="card-body">
                                         <table cellpadding="5">
                                            <tbody>
                                                 <tr>
                                                     <td class="pr-3">Fullname</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1">
                                                        <?= $all['fullname']; ?>
                                                        <?php
                                                            if ($all['is_active'] == 1) {
                                                                echo '<label class="badge badge-primary badge-pill">Active</label>';
                                                            } else {
                                                                echo '<label class="badge badge-danger badge-pill">Inactive</label>';
                                                            }
                                                        ?>
                                                        <span class="badge badge-pill badge-primary"><?= $all['role_name']; ?></span>
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">NPK</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1">
                                                         <?= $all['npk']; ?>
                                                         <span class="badge badge-pill badge-info"><?= $all['status']; ?></span>
                                                     </td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Birth date</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= date('d-M-Y', strtotime($all['birthdate'])); ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Join date</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= date('d-M-Y', strtotime($all['joindate'])); ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Retire date</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1">
                                                         <?php if ($all['retiredate'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                echo date('d-M-Y', strtotime($all['retiredate']));
                                                            }
                                                            ?>
                                                     </td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Email</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['email_address']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Email personal</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['email_personal']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Department</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['department_name']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Section</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['section']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Jobdesk</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['jobdesk']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Moodle username</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['user_moodle']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Replacement for</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['replacement_for']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">MPR approval</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['mpr_approval']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td class="pr-3">Remark</td>
                                                     <td class="px-3">:</td>
                                                     <td class="pl-1"><?= $all['remark']; ?></td>
                                                 </tr>
                                                 <tr>
                                                     <td colspan="3">
                                                         <img class="img img-fluid rounded" src="<?= base_url('assets/img/profile/') . $all['photo'] ?>" style="width: 200px;">
                                                     </td>
                                                 </tr>
                                             </tbody>
                                         </table>
                                     </div>
                                 </div>
                             <?php endforeach; ?>
                        </div>
                     </div>
                 </div>
                 <div class="col-5" id="listAllUserByJoinDate" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Based on join date (newer to older)</h3>
                        </div>
                        <div class="card-body" style="height: 648px; overflow-y: scroll;">
                            <ul class="list-group list-group-unbordered mb-3">
                                <?php foreach($alluserDesc as $data): ?>
                                    <li class="list-group-item">
                                        <b><?= substr($data['fullname'], 0, 24) ?></b> <a class="float-right"><?= date('d M Y', strtotime($data['joindate'])); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>                            
                        </div>                        
                    </div>
                 </div>
                 
                 <div class="col-5" id="listAllUserByBirthdate" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Based on birth date (newer to older)</h3>
                        </div>
                        <div class="card-body" style="height: 648px; overflow-y: scroll;">
                            <ul class="list-group list-group-unbordered mb-3">
                                <?php foreach($alluserBirthdate as $data): ?>
                                    <li class="list-group-item">
                                        <b><?= substr($data['fullname'], 0, 24) ?></b> <a class="float-right"><?= date('d M Y', strtotime($data['birthdate'])); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>                            
                        </div>                        
                    </div>
                 </div>
             </div>
         </div>
     </div><!-- /.container-fluid -->
 </div>
 <!-- /.content-header -->
 </div>
 <!-- /.content-wrapper -->