<div class="content-wrapper">
    <!-- Main content -->
  <section class="content pt-2">
    <div class="container-fluid">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <div class="card">
        <div class="card-header bg-primary">
          Long Holiday Monitoring
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-8" id="calendarLongHoliday"></div>
            <div class="col-4 p-3">
              <div class="card mt-2">
                <div class="card-header">
                  Description
                </div>
                <div class="card-body">
                  <table class="table table-sm table-borderless">
                    <tbody>
                      <tr>
                        <td><button class="btn btn-success"></button></td>
                        <td>Stay at home & travel short distance</td>
                      </tr>
                      <tr>
                        <td><button class="btn btn-primary"></button></td>
                        <td>Out of town trip (ke luar kota)</td>
                      </tr>
                      <tr>
                        <td><button class="btn btn-danger"></button></td>
                        <td>Overseas travel (ke luar negeri)</td>
                      </tr>
                      <tr>
                        <td><button class="btn" style="background-color: #6610F2"></button></td>
                        <td>Nambah pundi-pundi dompet</td>
                      </tr>
                    </tbody>
                  </table>
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
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addEventModalLabel">Add Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="form" method="POST" action="<?= base_url('leave/addNewLongHoliday');?>" id="formAddLeave">
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-sm-12">
                  <input type="hidden" class="form-control" id="addLongHolidayId" name="addLongHolidayId" readonly>
                </div>                
              </div>              
              <div class="form-group row">
                <label for="addLongHolidayStartDate" class="col-sm-4 col-form-label">Tanggal mulai</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="addLongHolidayStartDate" name="addLongHolidayStartDate" value="<?= date('Y-m-d');?>">
                </div>
              </div> 
              <div class="form-group row">
                <label for="addLongHolidayEndDate" class="col-sm-4 col-form-label">Sampai tanggal</label>
                <div class="col-sm-8">
                  <input type="date" class="form-control" id="addLongHolidayEndDate" name="addLongHolidayEndDate" value="<?= date('Y-m-d');?>">
                </div>
              </div>
              <div class="form-group row formLeaveStatus">
                <label for="addLongHolidayReason" class="col-sm-4 col-form-label">Rencana</label>
                <div class="col-sm-8">
                  <select type="" class="form-control custom-select" id="addLongHolidayReason" name="addLongHolidayReason">
                    <option value="Stay home & short travel">Stay home & short travel</option>                 
                    <option value="Out of town trip">Out of town trip (trip ke luar kota)</option>
                    <option value="Overseas travel">Overseas travel (travelling ke luar negeri)</option>
                    <option value="Lembur">Nambah pundi-pundi dompet</option>
                  </select>
                </div>
              </div>               
              <div class="form-group row">
                <label for="addLeaveDescription" class="col-sm-4 col-form-label">Description</label>
                <div class="col-sm-8">
                  <textarea class="form-control" id="addLeaveDescription" name="addLeaveDescription"></textarea>
                </div>
              </div>              
              <div class="form-group row">
                <label for="addLongHolidayDatetime" class="col-sm-4 col-form-label">Proposed at</label>
                <div class="col-sm-8">
                  <input type="" class="form-control" id="addLongHolidayDatetime" name="addLongHolidayDatetime" value="<?= date("Y-m-d h:i:s");?>">
                </div>
              </div>
            </div>
            <div class="modal-footer">              
              <button type="button" class="btn btn-secondary" id="buttonAddEventLongHolidayClose" data-dismiss="modal" aria-label="Close">Close</button>
              <button type="submit" class="btn btn-primary" id="buttonAddEventLongHolidaySubmit" name="buttonAddEventLongHolidaySubmit">Propose</button>
              <button type="button" class="btn btn-danger" id="buttonAddEventLongHolidayDelete" name="buttonAddEventLongHolidayDelete" style="display: none;">Delete</button>
              <button type="button" class="btn btn-success" id="buttonAddEventLongHolidayUpdate" name="buttonAddEventLongHolidayUpdate" style="display: none;">Update</button>              
            </div>
          </form> 
        </div>
      </div>
    </div>