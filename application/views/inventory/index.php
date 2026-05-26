<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 px-1">
          <div class="card">
            <div class="card-header bg-primary">
              CCC inventory data
              <div class="card-tools">
                <!-- <a type="button" class="text-light mr-2" id="buttonToExcelAssetsAllocation"><i class="fas fa-file-excel"></i> Excel</a> -->
                <a type="button" class="text-light mx-2" data-toggle="modal" data-target="#addAssetsAllocation" id="buttonAddAssetsAllocation"><i class="fas fa-plus-circle"></i> Add data</a>
              </div>
            </div>
            <div class="card-body">
              <!-- <?php var_dump($allInventory) ?> -->
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>PC</th>
                    <th>Monitor 1</th>
                    <th>Monitor 2</th>
                    <th>IP phone</th>
                    <th>Headset</th>
                    <th>...</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($allInventory as $data): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td><?= $data['user_id'] ?></td>
                      <td>
                        <span class="text-secondary"><?= $data['pc_model'] ?></span><br>
                        <small><?= $data['pc_ip'] ?></small><br>
                        <small><?= $data['pc_spec'] ?></small><br>
                        <small>Received: <span class="text-primary"><?= date("M Y", strtotime($data['pc_recdate'])) ?></span></small>
                      </td>
                      <td>
                        <span class="text-secondary"><?= $data['monitor1_brand'] ?> <small><?= $data['monitor1_size'] ?> inch</small></span><br>
                        <small><?= $data['monitor1_model'] ?></small><br>
                        <small>s/n: <?= $data['monitor1_sn'] ?></small><br>
                        <small>Received: <span class="text-primary"> <?= date("M Y", strtotime($data['monitor1_recdate'])) ?></span></small>
                      </td>
                      <td>
                        <span class="text-secondary"><?= $data['monitor2_brand'] ?> <small><?= $data['monitor2_size'] ?> inch</small></span><br>
                        <small><?= $data['monitor2_model'] ?></small><br>
                        <small>s/n: <?= $data['monitor2_sn'] ?></small><br>
                        <small>Received: <span class="text-primary"> <?= date("M Y", strtotime($data['monitor2_recdate'])) ?></span></small>
                      </td>
                      <td>
                        <span class="text-secondary"><?= $data['ipphone_brand'] ?> <small><?= $data['ipphone_model'] ?></small></span><br>
                        <small>s/n: <?= $data['ipphone_sn'] ?></small><br>
                        <br>
                        <small>Received: <span class="text-primary"> <?= date("M Y", strtotime($data['ipphone_recdate'])) ?></span></small>
                      </td>
                      <td>
                        <span class="text-secondary"><?= $data['headset_brand'] ?> </span><br>
                        <small><?= $data['headset_model'] ?></small><br>
                        <small>s/n: <?= $data['headset_sn'] ?></small><br>
                        <small>Received: <span class="text-primary"> <?= date("M Y", strtotime($data['headset_recdate'])) ?></span></small>
                      </td>
                      <td>
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                            <table  class="table table-sm table-borderless table-hover">
                              <tbody>
                                <tr>
                                  <td>Saved by</td>
                                  <td class="">: Cuparsa</td>
                                </tr>
                                <tr>
                                  <td>Saved at</td>
                                  <td class="">: 30 April 2020</td>
                                </tr>
                                <tr>
                                  <td>Updated by</td>
                                  <td class="">: </td>
                                </tr>
                                <tr>
                                  <td>Updated at</td>
                                  <td class="">: </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="table table-sm table-borderless">
                              <tbody>
                                <tr class="border-top">
                                  <td class="py-2">                                        
                                    <a href="" class="text-dark buttonEditAllocation" title="Edit data" data-id="<?= $data['id'] ?>" data-toggle="modal" data-target="#addAssetsAllocation">
                                        <span class="fas fa-pen"></span> &nbspEdit data
                                    </a>
                                  </td>
                                </tr>
                                <tr class="border-top">
                                  <td class="py-2">                                    
                                    <a href="#" class="text-danger buttonDeleteAllocation" title="Delete data" data-id="<?= $data['id'] ?>">
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
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Add/Edit Allocation -->
<div class="modal fade" id="addAssetsAllocation" tabindex="-1" role="dialog" aria-labelledby="addAssetsAllocationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="addAssetsAllocationLabel">Add Allocation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
            <form action="" method="POST">
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