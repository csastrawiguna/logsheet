<div class="content-wrapper">
<!-- Main content -->
   <section class="content pt-3 px-1">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>      
      <?php
         $leaderAccess = ['1', '2', '5', '6', '9'];

         function toString($data) {
            if ($data == '' || $data == NULL || $data == '0000-00-00 00:00:00') {
               return "-" ;
            } else {
               return $data;
            }
         }

      ?>
      <div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="card">
                  <div class="card-header bg-dark">
                     <span class="">Feedback NEW SKAPE yang sudah disimpan</span>
                     <div class="card-tools">
                        <!-- <a href="<?= base_url('survey/toExcelFeedback') ?>" class="mr-3 text-warning"><i class="fas fa-download"></i> Excel</a> -->
                        <a href="" class="text-warning" data-toggle="modal" data-target="#surveyFeedbackNewskape" id="btnAddFeedbackNewskape">
                           <i class="fas fa-plus-circle"> </i> Tambah Feedback
                        </a>
                     </div>
                  </div>
                  <div class="card-body">
                     <table class="table" id="tableFeedbackNewskape">
                        <?php if (in_array($this->session->userdata('role_access'), $leaderAccess)) : ?>
                           <thead>
                              <tr class="border-bottom">
                                 <th>#</th>
                                 <th>Agent</th>
                                 <th>Tanggal</th>
                                 <th>Kategori</th>
                                 <th>Detail feedback/masukan/saran</th>
                                 <th>...</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i = 1; ?>
                              <?php if (count($feedbackByAgent) < 1 ) : ?>
                                 <tr>
                                    <td colspan="5" class="text-center text-muted bg-light"><em>-- Tidak ada feedback --</em></td>
                                 </tr>
                              <?php else : ?>
                                 <?php foreach ($feedbackByAgent as $row) : ?>
                                    <tr>
                                       <td><?= $i++; ?></td>
                                       <td><?= $row['agent']; ?></td>
                                       <td><?= date("d-M", strtotime($row['saved_at'])); ?></td>
                                       <td><?= $row['category']; ?></td>
                                       <td><?= $row['detail']; ?></td>
                                       <td>                                          
                                          <div class="btn-group">                              
                                             <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                             <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                                                <table  class="table table-sm table-borderless table-hover">
                                                   <tbody>
                                                      <tr>
                                                         <td>Saved by</td>
                                                         <td class="">: <?= $row['agent']; ?></td>
                                                      </tr>
                                                      <tr>
                                                         <td>Saved at</td>
                                                         <td class="">: <?= date("d M Y h:i:s", strtotime($row['saved_at'])); ?></td>
                                                      </tr>
                                                      <tr>
                                                         <td>Updated by</td>
                                                         <td class="">: <?= toString($row['updated_by']); ?></td>
                                                      </tr>
                                                      <tr>
                                                         <td>Updated at</td>
                                                         <td class="">: <?= date("d M Y h:i:s", strtotime(toString($row['updated_at']))); ?></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                                <table class="table table-sm table-borderless">
                                                   <tbody>
                                                      <tr>
                                                         <td>
                                                            <button class="btn btn-danger btn-sm btnDeleteFeedback" data-id="<?= $row['id'] ?>" style="width: 49%;"><i class="fas fa-trash"></i> <small>Delete</small></button>
                                                            <button class="btn btn-warning btn-sm btnEditFeedback" data-id="<?= $row['id'] ?>" data-toggle="modal" data-target="#surveyFeedbackNewskape" style="width: 49%;"><i class="fas fa-pen"></i> <small>Edit</small></button>
                                                         </td>
                                                      </tr>                                                      
                                                   </tbody>
                                                </table> 
                                             </div>
                                          </div>
                                       </td>                                 
                                    </tr>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </tbody>
                        <?php else : ?>
                           <thead>
                              <tr class="border-bottom">
                                 <th>#</th>
                                 <th>Tanggal</th>
                                 <th>Kategori</th>
                                 <th>Detail feedback/masukan/saran</th>
                                 <th>...</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $i = 1; ?>
                              <?php if (count($feedbackByAgent) < 1 ) : ?>
                                 <tr>
                                    <td colspan="5" class="text-center text-muted bg-light"><em>-- Tidak ada feedback --</em></td>
                                 </tr>
                              <?php else : ?>
                                 <?php foreach ($feedbackByAgent as $row) : ?>
                                    <tr>
                                       <td><?= $i++; ?></td>
                                       <td><?= date("d-M", strtotime($row['saved_at'])); ?></td>
                                       <td><?= $row['category']; ?></td>
                                       <td><?= $row['detail']; ?></td>
                                       <td>
                                          <button class="btn btn-danger btn-sm" class="btnDeleteFeedback" data-id="<?= $row['id'] ?>"><i class="fas fa-trash"></i></button>
                                          <button class="btn btn-warning btn-sm" class="btnEditFeedback" data-id="<?= $row['id'] ?>"><i class="fas fa-pen"></i></button>
                                       </td>                                  
                                    </tr>
                                 <?php endforeach; ?>
                              <?php endif; ?>
                           </tbody>
                        <?php endif; ?>
                     </table>          
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>


<div class="modal fade" id="surveyFeedbackNewskape" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="surveyFeedbackNewskapeLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 560px;">
      <div class="modal-content">
         <form method="POST" action="">
            <div class="modal-header bg-warning">
               <h6 class="modal-title text-center" id="surveyFeedbackNewskapeLabel">Feedback New SKAPE</h6>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="feedbackNewskapeId" id="feedbackNewskapeId" readonly>
               <div class="form-group">
                  <label for="feedbackNewskapeCategory" class="form-label">Category</label>
                  <div class="">
                     <select class="custom-select" id="feedbackNewskapeCategory" name="feedbackNewskapeCategory">
                        <option value="Desain tampilan">Desain tampilan</option>
                        <option value="Kecepatan akses">Kecepatan akses</option>
                        <option value="Pencarian solusi">Pencarian solusi</option>
                        <option value="Struktur menu">Struktur menu</option>
                        <option value="Lainnya">Lainnya</option>
                        <option value="" selected>-</option>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label for="feedbackNewskapeDetail" class="form-label">Detail feedback, masukan, komentar, atau saran</label>
                  <div class="">
                     <textarea class="form-control" id="feedbackNewskapeDetail" name="feedbackNewskapeDetail"></textarea>        
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-warning" id="feedbackNewskapeSubmit" name="feedbackNewskapeSubmit">Save</button>
            </div>
         </form>             
      </div>
   </div>
</div>
