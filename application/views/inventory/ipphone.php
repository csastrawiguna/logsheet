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
              CCC Inventory - IP phone
              <div class="card-tools">
                <a type="button" class="text-light mr-2" id="buttonToExcelAssetsIpphone"><i class="fas fa-file-excel"></i> Excel</a>
                <a type="button" class="text-light mx-2" data-toggle="modal" data-target="#addAssetsIpphone" id="buttonAddAssetsIpphone"><i class="fas fa-plus-circle"></i> Add IP phone</a>
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
                   <?php foreach($allIpphone as $ipphone): ?>
                    <!-- <tr <?= toTextColor($ipphone['pc_status']) ?>> -->
                    <tr>
                      <td>                        
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                            <table  class="table table-sm table-borderless table-hover">
                              <tbody>
                                <tr>
                                  <td>Owner</td>
                                  <td class="">: </td>
                                </tr>
                                <tr>
                                  <td>Received</td>
                                  <td class="">: </td>
                                </tr>
                                <tr>
                                  <td>Remark</td>
                                  <td class="">: </td>
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
<div class="modal fade" id="addAssetsIpphone" tabindex="-1" role="dialog" aria-labelledby="addAssetsIpphoneLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetsIpphoneLabel">Add IP phone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <?= form_open_multipart(); ?>
                <div class="form-group row">                  
                  <input type="hidden" class="col-sm-7 form-control" id="addAssetsIpphoneId" name="addAssetsIpphoneId">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneDeptown" class="col-sm-4">PC owner</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneDeptown" name="addAssetsIpphoneDeptown">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneBrand" class="col-sm-4">PC brand</label>
                  <select class="col-sm-7 form-control custom-select" id="addAssetsIpphoneBrand" name="addAssetsIpphoneBrand">
                    <option value="Dell">Dell</option>
                    <option value="Dynabook">Dynabook</option>
                    <option value="HP">HP</option>
                    <option value="Lenovo">Lenovo</option>
                    <option value="Apple">Apple</option>
                  </select>
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneModel" class="col-sm-4">PC model</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneModel" name="addAssetsIpphoneModel">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneSn" class="col-sm-4">Serial no.</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneSn" name="addAssetsIpphoneSn">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneSpec" class="col-sm-4">Specification</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneSpec" name="addAssetsIpphoneSpec">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneIp" class="col-sm-4">IP Address</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneIp" name="addAssetsIpphoneIp">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneRemark" class="col-sm-4">Remark</label>
                  <input type="" class="col-sm-7 form-control" id="addAssetsIpphoneRemark" name="addAssetsIpphoneRemark">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneRecdate" class="col-sm-4">Received date</label>
                  <input type="date" class="col-sm-7 form-control" id="addAssetsIpphoneRecdate" name="addAssetsIpphoneRecdate">                      
                </div>
                <div class="form-group row">
                  <label for="addAssetsIpphoneStatus" class="col-sm-4">PC status</label>
                  <select type="" class="col-sm-7 form-control custom-select" id="addAssetsIpphoneStatus" name="addAssetsIpphoneStatus">
                    <option value="Good" selected>Good</option>
                    <option value="Fairly good">Fairly good</option>
                    <option value="Damage">Damage</option>
                    <option value=""></option>
                  </select>
                </div>  
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsIpphoneSubmit" id="addAssetsIpphoneSubmit">Save</button>
                  <button type="submit" class="btn btn-primary" name="addAssetsIpphoneUpdate" id="addAssetsIpphoneUpdate" style="display: none;">Update</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
