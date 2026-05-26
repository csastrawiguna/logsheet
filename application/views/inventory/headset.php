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
              CCC Inventory - Headset
              <div class="card-tools">
                <a type="button" class="text-light mr-2" id="buttonToExcelAsstesHeadset"><i class="fas fa-file-excel"></i> Excel</a>
                <a type="button" class="text-light mx-2" data-toggle="modal" data-target="#addAssetsHeadset" id="buttonAddAsstesHeadset"><i class="fas fa-plus-circle"></i> Add Headset</a>
              </div>
            </div>
            <div class="card-body">
              <table class="table col-sm" id="tableInventoryMonitor">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th>Merk (brand)</th>
                     <th>Model/type</th>
                     <th>Serial Number</th>                     
                     <th>Status</th>
                     <th>Received</th>
                     <th>...</th>
                   </tr>
                 </thead>
                 <tbody>
                    <?php $i = 1; ?>
                   <?php foreach($allHeadset as $headset): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $headset['headset_brand'] ?></td>
                      <td><?= $headset['headset_model'] ?></td>
                      <td><?= $headset['headset_sn'] ?></td>
                      <td><?= $headset['headset_status'] ?></td>
                      <td><?= nulldate($headset['headset_recdate']) ?></td>
                      <td>                        
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                            <table  class="table table-sm table-borderless table-hover">
                              <tbody>                                                               
                                <tr>
                                  <td>Remark</td>
                                  <td class="">: <?= $headset['headset_remark'] ?></td>
                                </tr>                               
                              </tbody>
                            </table>
                            <table class="table table-sm table-borderless">
                              <tbody>
                                <tr class="border-top">
                                  <td class="py-2">                                                                            
                                    <a href="" class="text-dark buttonEditHeadset" title="Edit data" data-id="<?= $headset['headset_id'] ?>" data-toggle="modal" data-target="#addAssetsHeadset">
                                      <span class="fas fa-pen"></span> &nbspEdit data
                                    </a>
                                  </td>
                                </tr>
                                <tr class="border-top">
                                  <td class="py-2">                                                                        
                                    <a href="#" class="text-danger buttonDeleteHeadset" title="Delete data" data-id="<?= $headset['headset_id'] ?>">
                                      <i class="fas fa-trash"></i> &nbspDelete data
                                    </a>
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
<div class="modal fade" id="addAssetsHeadset" tabindex="-1" role="dialog" aria-labelledby="addAssetsHeadsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="addAssetsHeadsetLabel">Add Headset</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
          <?= form_open_multipart(); ?>
            <div class="form-group row">
              <label for="addAssetsHeadsetBrand" class="col-sm-4">Headset brand</label>
              <input type="" class="col-sm-8 form-control" id="addAssetsHeadsetBrand" name="addAssetsHeadsetBrand">  
              </select>                  
            </div>           
            <div class="form-group row">
              <label for="addAssetsHeadsetModel" class="col-sm-4">Headset model</label>
              <input type="" class="col-sm-8 form-control" id="addAssetsHeadsetModel" name="addAssetsHeadsetModel">                      
            </div>
            <div class="form-group row">
              <label for="addAssetsHeadsetSn" class="col-sm-4">Serial Number</label>
              <input type="" class="col-sm-8 form-control" id="addAssetsHeadsetSn" name="addAssetsHeadsetSn">                      
            </div>
            <div class="form-group row">
              <label for="addAssetsHeadsetRemark" class="col-sm-4">Remark</label>
              <input type="" class="col-sm-8 form-control" id="addAssetsHeadsetRemark" name="addAssetsHeadsetRemark">                      
            </div>
            <div class="form-group row">
              <label for="addAssetsHeadsetRecDate" class="col-sm-4">Received date</label>
              <input type="date" class="col-sm-8 form-control" id="addAssetsHeadsetRecDate" name="addAssetsHeadsetRecDate">                      
            </div>
            <div class="form-group row">
              <label for="addAssetsHeadsetStatus" class="col-sm-4">Headset status</label>
              <select class="col-sm-8 form-control custom-select" id="addAssetsHeadsetStatus" name="addAssetsHeadsetStatus">
                <option value="Good" selected>Good</option>
                <option value="Damage">Damage</option>
                <option value="Wait WO">Wait WO</option>
                <option value="Approved WO">Approved WO</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="reset" class="btn btn-warning">Reset</button>
              <button type="submit" class="btn btn-primary" name="addAssetsHeadsetSubmit">Save</button>
            </div>
          </form>
          </div>
          </div>
        </div>
    </div>
</div>
