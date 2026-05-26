<div class="content-wrapper">
    <!-- Main content -->
  <section class="content pt-3 px-1">
    <div class="container-fluid">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <div class="card">
        <div class="card-header bg-primary">
          Leave arrangement
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-8" id="calendar" style=""></div>            
            <div class="col-4 p-3">
              <div class="card card-outline card-primary mt-2">
                <div class="card-header">
                  Description
                </div>
                <div class="card-body">
                  <table class="table table-sm table-borderless">
                    <tbody>
                      <tr>
                        <td><button class="btn btn-primary"></button></td>
                        <td>New</td>
                        <td><button class="btn btn-success"></button></td>
                        <td>Approved</td>
                      </tr>
                      <tr>
                        <td><button class="btn btn-danger"></button></td>
                        <td>Rejected</td>
                        <td><button class="btn btn-secondary"></button></td>
                        <td>Cancelled</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>              
              <div class="card card-outline card-primary mt-2" id="cardLeaveByDay">
                <div class="card-header">
                  Date : <span class="text-danger"><?= date("d F Y"); ?></span>
                </div>
                <div class="card-body">
                  <table class="table table-sm table-borderless" id="tableCardLeaveByDay">
                    <thead class="border-bottom">
                      <tr>
                        <th>No</th>
                        <th>Agent</th>
                        <th>Proposed at</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                      </tr>
                    </tbody>
                  </table>
                  <p class="mt-3">
                    <b class="text-danger"><i class="fas fa-info-circle"></i> CATATAN:</b>
                    <ul>
                      <li class="mb-3">Jika ada 2 orang atau lebih yang mengajukan cuti di hari yang sama, silakan rembukkan sendiri siapa yang mau cuti. Kalau tidak ada kata sepakat, yang di-approved yang lebih dulu mengajukan.</li>
                      <li class="mb-3">Kalau batal cuti, dihapus dari list ya. Supaya yang lain bisa isi slotnya.</li>
                      <li class="mb-3">Maksimal nge-plot/tag tanggal cuti <b class="text-danger">sesuai dengan jatah cuti tahunan/cuti kelipatan</b>.<br>Kalau melebihi jatah cuti, akan di-drop/dialokasikan ke agent yang antri berikutnya.</li>
                    </ul>
                    <!-- <i class="fas fa-info-circle"></i> <b><u>CATATAN</u></b> <br>Jika ada 2 orang atau lebih yang mengajukan cuti di hari yang sama, silakan rembukkan sendiri siapa yang mau cuti. Kalau tidak ada kata sepakat, yang di-approved yang lebih dulu mengajukan.
                  </p>
                  <p class="text-dark mt-3">
                    Kalau batal cuti, dihapus dari list ya. Supaya yang lain bisa isi slotnya.
                  </p>
                  <p class="text-dark mt-3">
                    Maksimal nge-plot/tag tanggal cuti <b class="text-danger">sesuai dengan jatah cuti tahunan/cuti kelipatan</b>.<br>Kalau melebihi jatah cuti, akan di-drop/dialokasikan ke agent yang antri berikutnya.
                  </p> -->
                </div>
              </div>
              <div class="card mt-2" id="">  
                <div class="card-body">
                  <div>
                    <a href="<?= base_url('assets/responsive_filemanager/source/2025/2026_SEID_Working_Calendar.pdf');?>" target="_blank" class="h6 text-primary"><span class="fas fa-file-pdf"></span> Working calendar 2026</a>
                  </div>
                  <div>
                    <a href="<?= base_url('assets/responsive_filemanager/source/2024/2025_SEID_Working_Calendar.pdf');?>" target="_blank" class="h6 text-primary"><span class="fas fa-file-pdf"></span> Working calendar 2025</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Tambah Data Survey-->
    <!-- Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEventModalLabel">Add Leave Proposal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form" method="POST" action="<?= base_url('leave/addNewLeave');?>" id="formAddLeave">
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-sm-12">
              <input type="hidden" class="form-control" id="addLeaveId" name="addLeaveId" readonly>
            </div>
            <label for="addLeaveType" class="col-sm-4 col-form-label">Leave type</label>
            <div class="col-sm-8">
              <select type="" class="form-control custom-select" id="addLeaveType" name="addLeaveType">
                <option value="" selected>- pilih cuti / ijin -</option>
                <option value="Annual leave">Annual leave (cuti tahunan)</option>                 
                <option value="PKB leave">Special leave (cuti khusus PKB)</option>
                <option value="Unpaid leave">Unpaid leave (cuti potong gaji)</option>
                <option value="Others">Others (lainnya)</option>
              </select>
            </div>
          </div>              
          <div class="form-group row">
            <label for="addLeaveStartDate" class="col-sm-4 col-form-label">Start date</label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="addLeaveStartDate" name="addLeaveStartDate" value="<?= date('Y-m-d');?>">
            </div>
          </div> 
          <div class="form-group row">
            <label for="addLeaveEndDate" class="col-sm-4 col-form-label">End date</label>
            <div class="col-sm-8">
              <input type="date" class="form-control" id="addLeaveEndDate" name="addLeaveEndDate" value="<?= date('Y-m-d');?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="addLeaveReason" class="col-sm-4 col-form-label">Reason</label>
            <div class="col-sm-8">
              <input type="" class="form-control" id="addLeaveReason" name="addLeaveReason" placeholder="harus diisi alasan cuti">
            </div>
          </div>
          <div class="form-group row">
            <label for="addLeaveDescription" class="col-sm-4 col-form-label">Description</label>
            <div class="col-sm-8">
              <textarea class="form-control" id="addLeaveDescription" name="addLeaveDescription" placeholder="harus diisi, ketearangan tambahan"></textarea>
            </div>
          </div>
          <?php if($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9 ): ?>
            <div class="form-group row formLeaveStatus">
              <label for="addLeaveStatus" class="col-sm-4 col-form-label">Status</label>
              <div class="col-sm-8">
                <select type="" class="form-control custom-select" id="addLeaveStatus" name="addLeaveStatus">
                  <option value="new">New</option>                 
                  <option value="approved">Approved</option>
                  <option value="rejected">Rejected</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
            </div> 
            <div class="form-group row">
              <label for="addLeaveDatetime" class="col-sm-4 col-form-label">Proposed at</label>
              <div class="col-sm-8">
                <input type="" class="form-control" id="addLeaveDatetime" name="addLeaveDatetime">
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="modal-footer">              
          <!-- <button type="button" class="btn btn-secondary" id="buttonAddEventClose" data-dismiss="modal" aria-label="Close">Close</button> -->
          <?php if($this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 9 ): ?>
            <button type="button" class="btn btn-danger" id="buttonAddEventPurge" name="buttonAddEventPurge" style="display: none;"><i class="fas fa-trash"></i> Purge</button>
          <?php endif; ?>
          <button type="submit" class="btn btn-primary" id="buttonAddEventSubmit" name="buttonAddEventSubmit"><i class="fas fa-upload"></i> Propose</button>
          <button type="button" class="btn btn-warning" id="buttonAddEventDelete" name="buttonAddEventDelete" style="display: none;"><i class="fas fa-times"></i> Drop/delete</button>
          <button type="button" class="btn btn-success" id="buttonAddEventUpdate" name="buttonAddEventUpdate" style="display: none;"><i class="fas fa-check"></i> Update</button>              
        </div>
      </form> 
    </div>
  </div>
</div>
