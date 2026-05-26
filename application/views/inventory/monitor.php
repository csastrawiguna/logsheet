<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <?php 
        function nulldate($datestring){
          if($datestring == '0000-00-00' || $datestring == null){
            return '-';
          } else {
            return date("d M Y", strtotime($datestring));
          }
        }
      ?>
      <div class="row"> 
        <div class="col-sm my-3 px-1">
          <div class="card">
            <div class="card-header bg-primary">
              CCC Inventory - Monitor
              <div class="card-tools">
                <a type="button" class="text-light mr-2" id="buttonToExcelAsstesPc"><i class="fas fa-file-excel"></i> Excel</a>
                <a type="button" class="text-light mx-2" data-toggle="modal" data-target="#addAssetsMonitor" id="buttonAddAsstesMonitor"><i class="fas fa-plus-circle"></i> Add PC</a>
              </div>
            </div>
            <div class="card-body">
              <table class="table col-sm" id="tableInventoryMonitor">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th>Merk (brand)</th>
                     <th>Size (inch)</th>
                     <th>Model/type</th>
                     <th>Serial Number</th>
                     <th>Received date</th>                         
                     <th>Remark</th>
                     <th>Status</th>
                     <th>Action</th>
                   </tr>
                 </thead>
                 <tbody>
                    <?php $i = 1; ?>
                   <?php foreach($allMonitor as $monitor): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $monitor['monitor_brand'] ?></td>
                      <td><?= $monitor['monitor_size'] ?></td>
                      <td><?= $monitor['monitor_model'] ?></td>
                      <td><?= $monitor['monitor_sn'] ?></td>
                      <td><?= nulldate($monitor['monitor_recdate']) ?></td>
                      <td><?= $monitor['monitor_remark'] ?></td>
                      <td><?= $monitor['monitor_status'] ?></td>
                      <td>
                        <a href="#" class="text-danger buttonDeleteMonitor" title="Delete data" data-id="<?= $monitor['monitor_id'] ?>">
                              <i class="lnr lnr-trash"></i>
                        </a> &nbsp
                        <a href="" class="text-dark buttonEditMonitor" title="Edit data" data-id="<?= $monitor['monitor_id'] ?>" data-toggle="modal" data-target="#addAssetsPc">
                            <span class="lnr lnr-pencil"></span>
                        </a>
                      </td>
                    </tr>
                   <?php endforeach; ?>
                 </tbody>
               </table>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<!-- Modal Add Asset Monitor -->
<div class="modal fade" id="addAssetsMonitor" tabindex="-1" role="dialog" aria-labelledby="addAssetsMonitorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetsMonitorLabel">Add Monitor Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?= form_open_multipart(); ?>
                <div class="form-group row">
                  <label for="addAssetsMonitorBrand" class="col-sm-4">Monitor brand</label>
                  <select class="col-sm-7 form-control custom-select" id="addAssetsMonitorBrand" name="addAssetsMonitorBrand">
                    <option value="Acer">Acer</option>
                    <option value="Benq">Benq</option>
                    <option value="Dell">Dell</option>
                    <option value="LG">LG</option>
                    <option value="Samsung">Samsung</option>
                    <option value="Sharp">Sharp</option>
                  </select>
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorSize" class="col-sm-4">Size (inch)</label>
                  <select class="col-sm-7 form-control custom-select" id="addAssetsMonitorSize" name="addAssetsMonitorSize">
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="22">22</option>
                    <option value="24">24</option>
                    <option value="32">32</option>
                  </select>
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorModel" class="col-sm-4">Monitor model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsMonitorModel" name="addAssetsMonitorModel">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorSn" class="col-sm-4">Serial Number</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsMonitorSn" name="addAssetsMonitorSn">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsMonitorRemark" name="addAssetsMonitorRemark">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorRecDate" class="col-sm-4">Received date</label>
                  <input type="date" class="col-sm-7 form-control" id="addAssetsMonitorRecDate" name="addAssetsMonitorRecDate">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsMonitorStatus" class="col-sm-4">Monitor status</label>
                  <select class="col-sm-7 form-control custom-select" id="addAssetsMonitorStatus" name="addAssetsMonitorStatus">
                    <option value="Good" selected>Good</option>
                    <option value="Damage">Damage</option>
                    <option value="Wait WO">Wait WO</option>
                    <option value=""></option>
                  </select>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsMonitorSubmit">Save</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
