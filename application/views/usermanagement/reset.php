 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content pt-3">
     <div class="container-fluid">
       <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
       <div class="card card-primary card-tabs">
         <div class="card-header p-0 pt-1">
           <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
             <!-- Summary Result Tab -->
             <li class="nav-item">
               <a class="nav-link active" id="custom-tabs-one-requestReset-tab" data-toggle="pill" href="#custom-tabs-one-requestReset" role="tab" aria-controls="custom-tabs-one-requestReset" aria-selected="true">Reset Password/User</a>
             </li>

             <!-- Summary by Category Tab -->
             <li class="nav-item">
               <a class="nav-link" id="custom-tabs-one-summaryReset-tab" data-toggle="pill" href="#custom-tabs-one-summaryReset" role="tab" aria-controls="custom-tabs-one-summaryReset" aria-selected="false">Summary</a>
             </li>
           </ul>
         </div>
         <div class="card-body">
           <div class="tab-content" id="custom-tabs-one-tabContent">
             <!-- Summary Result Content -->
             <div class="tab-pane fade show active" id="custom-tabs-one-requestReset" role="tabpanel" aria-labelledby="custom-tabs-one-requestReset-tab">
               <div class="row">
                 <div class="col-8" style="height: 70vh; overflow-y:auto;">
                   <table class="table table-bordered table-hover" id="tableListRequestResetPassword">
                     <thead>
                       <tr class="text-center">
                         <th>#</th>
                         <th>User ID</th>
                         <th>IP address</th>
                         <th>Request on</th>
                         <th>Reason</th>
                         <th>Is locked</th>
                         <th>Status</th>
                       </tr>
                     </thead>
                     <tbody>
                       <?php
                        $i = 1;
                        foreach ($allLockedUser as $al) :
                        ?>
                         <tr data-userid="<?= $al['user_id']; ?>" data-id="<?= $al['id']; ?>">
                           <td><?= $i++; ?></td>
                           <td><?= $al['user_id']; ?></td>
                           <td class="text-center"><?= $al['ip_address']; ?></td>
                           <td class="text-center"><?= date("d-M-Y H:i:s", strtotime($al['datetime'])); ?></td>
                           <td class="text-center"><?= $al['reason']; ?></td>
                           <td class="text-center">
                             <?php if ($al['is_locked'] == 1) {
                                echo "Yes";
                              } else {
                                echo "No";
                              }
                              ?>
                           </td>
                           <td class="text-center">
                             <?php if ($al['status'] == 0) : ?>
                               <label class="badge badge-sm badge-danger">Pending</label>
                             <?php else : ?>
                               <label class="badge badge-sm badge-primary">Done</label>
                             <?php endif; ?>
                           </td>
                         </tr>
                       <?php endforeach; ?>
                     </tbody>
                   </table>
                 </div>
                 <div class="col-3 ml-3" id="colActionResetPassword" style="display: none;">
                   <div class="card card-primary card-outline">
                     <div class="card-header bg-light">
                       <span class="text-primary">Action</span>
                       <span class="card-tools text-secondary" id="buttonCloseActionResetPassword" style="cursor: pointer;">Close</span>
                     </div>
                     <div class="card-body">
                       <div class="form-group">
                         <label for="colActionResetId">For user ID</label>
                         <input type="hidden" class="form-control" id="colActionResetId" name="colActionResetId" readonly>
                         <input type="" class="form-control" id="colActionResetUserId" readonly>
                       </div>
                       <div class="form-group">
                         <label for="buttonResetPassword">Click here to reset password</label>
                         <button class="btn btn-block btn-outline-primary" id="buttonResetPassword">Reset password</button>
                       </div>
                       <div class="form-group">
                         <label for="buttonUnlockUser">Click this button to unlock user</label>
                         <button class="btn btn-block btn-outline-primary" id="buttonUnlockUser">Reset/unlock user</button>
                       </div>
                       <div class="form-group">
                         <label for="buttonDismissResetRequest">Dismiss request</label>
                         <button class="btn btn-block btn-outline-danger" id="buttonDismissResetRequest">Dismiss request</button>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>

             <div class="tab-pane fade show active" id="custom-tabs-one-summaryReset" role="tabpanel" aria-labelledby="custom-tabs-one-summaryReset-tab">
             </div>
           </div>
         </div>
       </div>

     </div><!-- /.container-fluid -->
   </section>
 </div>
 <!-- /.container-fluid -->
 </section>
 <!-- /.content -->
 </div>
 <!-- /.content-wrapper -->