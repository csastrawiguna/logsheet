<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <?php 
                $rows = 3;
                if($this->input->post('kpiNewTargetAddRowQty')) {
                    $rows += $rowQty;
                }

            ?>
            <!-- /.row -->
            <!-- <div class="row">
                <div class="col">
                    <form action="" class="form-row mt-3 mb-4" method="post" style="width: 640px;">
                        <label for="kpiAddSelectJobdesk" class="col-sm-2">Select jobdesk</label>
                        <div class="col-sm-6">
                            <select type="" id="kpiAddSelectJobdesk" name="kpiAddSelectJobdesk" class="custom-select">
                                <option value="<?= $latestFiscal ?>" selected><?= $latestFiscal ?></option>
                                <?php foreach($allJobdesks as $jobdesk): ?>
                                    <option value="<?= $jobdesk['jobcode'] ?>"><?= $jobdesk['jobdesk'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>                        
                    </form>
                </div>
            </div> -->
            <div class="row">
                <div class="col">
                    
                </div>
            </div>
            <div class="row">                
                <div class="col-10">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Add KPI target for Customer Assistant
                            <div class="card-tools">
                                <div class="card-tools">                                
                                    <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#kpiNewTargetAdd"><i class="fas fa-plus-circle" ></i> Add rows</a>
                                </div>
                            </div>  
                        </div>
                        <div class="card-body"> 
                            <form method="POST" action="">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="kpiNewTargetAddFiscal">Fiscal</label>
                                        <input type="" class="" name="kpiNewTargetAddFiscal" id="kpiNewTargetAddFiscal">
                                    </div>
                                </div>
                                <table class="col-12 table table-borderless table-sm">
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="col-2">Item</td>
                                            <td class="col-5">Description</td>
                                            <td class="col-2">Weight (%)</td>
                                            <td class="col-2">Target</td>                                        
                                            <td class="col-1">...</td>
                                        </tr>                                            
                                    </tbody>
                                    
                                    <tbody>
                                        <?php for($i = 0; $i < $rows; $i++): ?>
                                            <tr>
                                                <td>
                                                    <input type="" name="kpiNewTargetAddItem<?= $i ?>" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="" name="kpiNewTargetAddDesc<?= $i ?>" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="" name="kpiNewTargetAddWeight<?= $i ?>" class="form-control">
                                                </td>                                            
                                                <td>
                                                    <input type="" name="kpiNewTargetAddTarget<?= $i ?>" class="form-control">
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>                          
                                <button type="submit" class="btn btn-outline-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>                
            </div>            
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal fade" id="kpiNewTargetAdd" tabindex="-1" role="dialog" aria-labelledby="kpiNewTargetAddLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="kpiNewTargetAddLabel">Add Agents' Black Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="">
          <div class="modal-body">                              
              <div class="form-group">
                  <label for="kpiNewTargetAddRowQty" class="form-label">Number of rows</label>
                  <div class="">
                      <input type="number" class="form-control" id="kpiNewTargetAddRowQty" name="kpiNewTargetAddRowQty">
                  </div>
              </div>              
          </div>
          <div class="modal-footer">              
              <button type="submit" class="btn btn-primary" name="submitKpiNewTargetAdd" id="submitKpiNewTargetAdd">Go</button>
          </div>
      </form>
    </div>
  </div>
</div>