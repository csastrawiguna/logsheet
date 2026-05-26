 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content pt-3">
         <div class="container-fluid">
             <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>

             <!-- <div class="row">
                 <div class="col text-center" style="margin-top: 30vh;;">
                     <h3 class="h3 text-warning">Coming soon</h3>
                 </div>
             </div> -->
             <div class="card">
                 <div class="card-header bg-primary">
                     <div class="card-title">Manage Survey Data of CS Index</div>
                 </div>
                 <div class="card-body">
                     <div class="row">
                         <div class="col mt-3">
                             <h5 class="h5 text-primary">CS Index Survey Data Management</h5>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-6">
                             <table class="mt-3 table table-sm" id="tableManageCSindexData">
                                 <thead>
                                     <tr class="text-center">
                                         <th>No</th>
                                         <th>Period</th>
                                         <th>Success Survey Qty</th>
                                         <th>Action</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php $i = 1; ?>
                                     <?php foreach ($allData as $ad) : ?>
                                         <tr class="text-center">
                                             <td><?= $i++; ?></td>
                                             <td><?= date("F Y", strtotime($ad['period'])); ?></td>
                                             <td><?= $ad['q1']; ?></td>
                                             <td>
                                                 <button class="btn btn-xs btn-outline-danger btnDeleteSurveyData" data-period="<?=$ad['period'];?>">Delete</button>
                                             </td>
                                         </tr>
                                     <?php endforeach; ?>
                                 </tbody>
                             </table>
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