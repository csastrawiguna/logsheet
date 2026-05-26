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
          <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
              <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-assets-pc-tab" data-toggle="pill" href="#custom-tabs-assets-pc" role="tab" aria-controls="custom-tabs-assets-pc" aria-selected="true">Computer (PC)</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-assets-monitor-tab" data-toggle="pill" href="#custom-tabs-assets-monitor" role="tab" aria-controls="custom-tabs-assets-monitor" aria-selected="false">Monitor</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-assets-ipphone-tab" data-toggle="pill" href="#custom-tabs-assets-ipphone" role="tab" aria-controls="custom-tabs-assets-ipphone" aria-selected="false">IP phone</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-assets-headset-tab" data-toggle="pill" href="#custom-tabs-assets-headset" role="tab" aria-controls="custom-tabs-assets-headset" aria-selected="false">Headset</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-assets-other-tab" data-toggle="pill" href="#custom-tabs-assets-other" role="tab" aria-controls="custom-tabs-assets-other" aria-selected="false">Others</a>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-assets-pc" role="tabpanel" aria-labelledby="custom-tabs-assets-pc-tab">
                  <div class="my-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addAssetsPc" id="buttonAddAsstesPc">Add</button>                    
                  </div>    
                   <table class="table col-sm" id="tableInventoryPc">
                     <thead>
                       <tr>
                         <th>#</th>
                         <th>Merk (brand)</th>
                         <th>Model/type</th>
                         <th>Serial Number</th>
                         <th>Specification</th>
                         <th>IP address</th>
                         <th>Received date</th>                         
                         <th>Remark</th>
                         <th>Status</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; ?>
                       <?php foreach($allPc as $pc): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $pc['pc_brand'] ?></td>
                          <td><?= $pc['pc_model'] ?></td>
                          <td><?= $pc['pc_sn'] ?></td>
                          <td><?= $pc['pc_spec'] ?></td>
                          <td><?= $pc['pc_ip'] ?></td>
                          <td><?= nulldate($pc['pc_recdate']) ?></td>
                          <td><?= $pc['pc_remark'] ?></td>
                          <td><?= $pc['pc_status'] ?></td>
                          <td>
                            <a href="#" class="text-danger buttonDeletePc" title="Delete data" data-id="<?= $pc['pc_id'] ?>">
                                  <i class="lnr lnr-trash"></i>
                            </a> &nbsp
                            <a href="" class="text-dark buttonEditPC" title="Edit data" data-id="<?= $pc['pc_id'] ?>" data-toggle="modal" data-target="#addAssetsPc">
                                <span class="lnr lnr-pencil"></span>
                            </a>
                          </td>
                        </tr>
                       <?php endforeach; ?>
                     </tbody>
                   </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-assets-monitor" role="tabpanel" aria-labelledby="custom-tabs-assets-monitor-tab">
                  <div class="my-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addAssetsMonitor" id="buttonAddAsstesMonitor">Add</button>                    
                  </div> 
                   <table class="table col-sm-8" id="tableInventoryMonitor">
                     <thead>
                       <tr>
                         <th>#</th>
                         <th>Merk (brand)</th>
                         <th>Model/type</th>
                         <th>Size (inch)</th>
                         <th>Remark</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; ?>
                       <?php foreach($allMonitor as $monitor): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $monitor['monitor_brand'] ?></td>
                          <td><?= $monitor['monitor_model'] ?></td>
                          <td><?= $monitor['monitor_size'] ?></td>
                          <td><?= $monitor['monitor_remark'] ?></td>
                          <td>
                            <a href="" class="text-danger " title="Delete data" data-id="">
                                  <i class="lnr lnr-trash"></i>
                              </a> &nbsp
                              <a href="" class="text-dark " title="Edit data" data-id="">
                                  <span class="lnr lnr-pencil"></span>
                              </a>
                          </td>
                        </tr>
                       <?php endforeach; ?>
                     </tbody>
                   </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-assets-ipphone" role="tabpanel" aria-labelledby="custom-tabs-assets-ipphone-tab">
                  <div class="my-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addAssetsIpphone" id="buttonAddAsstesIpphone">Add</button>                    
                  </div> 
                   <table class="table col-sm-8" id="tableInventoryIpphone">
                     <thead>
                       <tr>
                         <th>#</th>
                         <th>Merk (brand)</th>
                         <th>Model/type</th>
                         <th>Remark</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; ?>
                       <?php foreach($allIpphone as $ip): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $ip['ipphone_brand'] ?></td>
                          <td><?= $ip['ipphone_model'] ?></td>
                          <td><?= $ip['ipphone_remark'] ?></td>
                          <td>
                            <a href="" class="text-danger " title="Delete data" data-id="">
                                  <i class="lnr lnr-trash"></i>
                              </a> &nbsp
                              <a href="" class="text-dark " title="Edit data" data-id="">
                                  <span class="lnr lnr-pencil"></span>
                              </a>
                          </td>
                        </tr>
                       <?php endforeach; ?>
                     </tbody>
                   </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-assets-headset" role="tabpanel" aria-labelledby="custom-tabs-assets-headset-tab">
                  <div class="my-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#addAssetsHeadset" id="buttonAddAsstesHeadset">Add</button>      
                  </div>    
                   <table class="table col-sm" id="tableInventoryHeadset">
                     <thead>
                       <tr>
                         <th>#</th>
                         <th>Merk (brand)</th>
                         <th>Model/type</th>
                         <th>Serial no.</th>
                         <th>Received</th>
                         <th>Remark</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                        <?php $i = 1; ?>
                       <?php foreach($allHeadset as $hs): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $hs['headset_brand'] ?></td>
                          <td><?= $hs['headset_model'] ?></td>
                          <td><?= $hs['headset_sn'] ?></td>
                          <td><?= nulldate($hs['headset_recdate']); ?></td>
                          <td><?= $hs['headset_remark'] ?></td>
                          <td>
                            <a href="" class="text-danger " title="Delete data" data-id="">
                                  <i class="lnr lnr-trash"></i>
                              </a> &nbsp
                              <a href="" class="text-dark " title="Edit data" data-id="">
                                  <span class="lnr lnr-pencil"></span>
                              </a>
                          </td>
                        </tr>
                       <?php endforeach; ?>
                     </tbody>
                   </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-assets-other" role="tabpanel" aria-labelledby="custom-tabs-assets-other-tab">
                   OTHERS 
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Add Asset PC -->
<div class="modal fade" id="addAssetsPc" tabindex="-1" role="dialog" aria-labelledby="addAssetsPcLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetsPcLabel">Add PC Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?= form_open_multipart('inventory/addassetspc'); ?>
                <div class="form-group row">                  
                  <input type="hidden" class="col-sm-7 form-control" id="addAssetsPcId" name="addAssetsPcId">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcBrand" class="col-sm-4">PC brand</label>
                  <select class="col-sm-7 form-control custom-select" id="addAssetsPcBrand" name="addAssetsPcBrand">
                    <option value="Dell">Dell</option>
                    <option value="Dynabook">Dynabook</option>
                    <option value="HP">HP</option>
                    <option value="Lenovo">Lenovo</option>
                    <option value="Apple">Apple</option>
                  </select>
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcModel" class="col-sm-4">PC model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcModel" name="addAssetsPcModel">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcSn" class="col-sm-4">Serial no.</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcSn" name="addAssetsPcSn">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcSpec" class="col-sm-4">Specification</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcSpec" name="addAssetsPcSpec">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcIp" class="col-sm-4">IP Address</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcIp" name="addAssetsPcIp">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcRemark" name="addAssetsPcRemark">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcRecdate" class="col-sm-4">Received date</label>
                  <input type="date" class="col-sm-7 form-control" id="addAssetsPcRecdate" name="addAssetsPcRecdate">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcStatus" class="col-sm-4">PC status</label>
                  <select type="" class="col-sm-7 form-control custom-select" id="addAssetsPcStatus" name="addAssetsPcStatus">
                    <option value="Good" selected>Good</option>
                    <option value="Damage">Damage</option>
                    <option value="Wait WO">Wait WO</option>
                    <option value=""></option>
                  </select>
                </div>  
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsPcSubmit" id="addAssetsPcSubmit">Save</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsPcUpdate" id="addAssetsPcUpdate">Update</button>
                </div>
              </form>
            </div>
        </div>
    </div>
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
                  <label for="addAssetsMonitorModel" class="col-sm-4">Monitor model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsMonitorModel" name="addAssetsMonitorModel">                      
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
                  <label for="addAssetsMonitorRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsMonitorRemark" name="addAssetsMonitorRemark">                      
                </div>    
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

<!-- Modal Add Asset IP Phone -->
<div class="modal fade" id="addAssetsIpphone" tabindex="-1" role="dialog" aria-labelledby="addAssetsIpphoneLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetsIpphoneLabel">Add IP Phone Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?= form_open_multipart(); ?>
                <div class="form-group row">
                  <label for="addAssetsIpphoneBrand" class="col-sm-4">Brand</label>
                  <input class="col-sm-7 form-control" id="addAssetsIpphoneBrand" name="addAssetsIpphoneBrand" value="Avaya">                    
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneModel" class="col-sm-4">IP phone model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneModel" name="addAssetsIpphoneModel">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneRemark" name="addAssetsIpphoneRemark">                      
                </div>    
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsIpphoneSubmit">Save</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Asset Headset -->
<div class="modal fade" id="addAssetsHeadset" tabindex="-1" role="dialog" aria-labelledby="addAssetsHeadsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetsHeadsetLabel">Add Headset Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?= form_open_multipart(); ?>
                <div class="form-group row">
                  <label for="addAssetsHeadsetBrand" class="col-sm-4">Brand</label>
                  <input class="col-sm-7 form-control" id="addAssetsHeadsetBrand" name="addAssetsHeadsetBrand" value="Avaya">                    
                </div>
                <div class="form-group row">
                  <label for="addAssetsHeadsetModel" class="col-sm-4">Headset model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsHeadsetModel" name="addAssetsHeadsetModel">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsHeadsetRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsHeadsetRemark" name="addAssetsHeadsetRemark">                      
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