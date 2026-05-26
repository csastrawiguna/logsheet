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

        function toTextColor($data){
          if($data == 'Damage') {
            echo 'class="text-danger"';
          } else {
            echo '-';
          }
        }
      ?>
      <div class="row"> 
        <div class="col-sm my-2 px-1">
          <div class="card">
            <div class="card-header bg-primary">
              CCC Inventory - PC
              <div class="card-tools">
                <a type="button" class="text-light mr-2" id="buttonToExcelAsstesPc"><i class="fas fa-file-excel"></i> Excel</a>
                <a type="button" class="text-light mx-2" data-toggle="modal" data-target="#addAssetsPc" id="buttonAddAsstesPc"><i class="fas fa-plus-circle"></i> Add PC</a>
              </div>
            </div>
            <div class="card-body"> 
              <table class="table col-sm" id="tableInventoryPc">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th>Brand</th>
                     <th>Model</th>
                     <th>Serial no.</th>
                     <th>Spec.</th>
                     <th>IP address</th>
                     <th>Status</th>
                     <th>...</th>
                   </tr>
                 </thead>
                 <tbody>
                    <?php $i = 1; ?>
                   <?php foreach($allPc as $pc): ?>
                    <tr <?= toTextColor($pc['pc_status']) ?>>
                      <td><?= $i++; ?></td>
                      <td><?= $pc['pc_brand'] ?></td>
                      <td><?= $pc['pc_model'] ?></td>
                      <td><?= $pc['pc_sn'] ?></td>
                      <td><?= $pc['pc_spec'] ?></td>
                      <td><?= $pc['pc_ip'] ?></td>                      
                      <td><?= $pc['pc_status'] ?></td>
                      <td>                        
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                            <table  class="table table-sm table-borderless table-hover">
                              <tbody>
                                <tr>
                                  <td>Owner</td>
                                  <td class="">: <?= $pc['pc_deptown'] ?></td>
                                </tr>
                                <tr>
                                  <td>Received</td>
                                  <td class="">: <?= nulldate($pc['pc_recdate']) ?></td>
                                </tr>
                                <tr>
                                  <td>Remark</td>
                                  <td class="">: <?= $pc['pc_remark'] ?></td>
                                </tr>                               
                              </tbody>
                            </table>
                            <table class="table table-sm table-borderless">
                              <tbody>
                                <tr class="border-top">
                                  <td class="py-2">                                        
                                    <a href="" class="text-dark buttonEditPC" title="Edit data" data-id="<?= $pc['pc_id'] ?>" data-toggle="modal" data-target="#addAssetsPc">
                                        <span class="fas fa-pen"></span> &nbspEdit data
                                    </a>
                                  </td>
                                </tr>
                                <tr class="border-top">
                                  <td class="py-2">                                    
                                    <a href="#" class="text-danger buttonDeletePc" title="Delete data" data-id="<?= $pc['pc_id'] ?>">
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
              <?= form_open_multipart(); ?>
                <div class="form-group row">                  
                  <input type="hidden" class="col-sm-7 form-control" id="addAssetsPcId" name="addAssetsPcId">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsPcDeptown" class="col-sm-4">PC owner</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsPcDeptown" name="addAssetsPcDeptown">                      
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
                    <option value="Fairly good">Fairly good</option>
                    <option value="Damage">Damage</option>
                    <option value=""></option>
                  </select>
                </div>  
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsPcSubmit" id="addAssetsPcSubmit">Save</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsPcUpdate" id="addAssetsPcUpdate" style="display: none;">Update</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
