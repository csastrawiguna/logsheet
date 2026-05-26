<div class="content-wrapper">
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid py-2 px-0">
      <?php 
        $allowedAccess = [1, 2, 4, 5, 6, 9];
      ?>
      
      <div class="row">
        <div class="col-md-6">
          <form method="POST" action="">
            <div class="card">
              <div class="card-header bg-warning">
                EDIT DATA OF CASHLESS PAYMENT INFO TO CUSTOMER
                <div class="card-tools">
                </div>
              </div>
              <div class="card-body bg-white" style="min-height: 50vh;">
                <div class="form-group row">
                  <input type="hidden" class="form-control" name="editCashlessInfoMonitoringId" id="editCashlessInfoMonitoringId" value="<?= $singleData['id'] ?>" readonly>
                </div>
                <div class="form-group row my-4">
                  <label for="editCashlessInfoMonitoringDate" class="col-sm-4 col-form-label">Date</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="editCashlessInfoMonitoringDate" name="editCashlessInfoMonitoringDate" value="<?= $singleData['date'] ?>">
                  </div>
                </div>
                <div class="form-group row my-4">
                  <label for="editCashlessInfoMonitoringAgent" class="col-sm-4 col-form-label">Agent</label>
                  <div class="col-sm-8">
                    <select type="" class="js-example-basic-single custom-select" id="editCashlessInfoMonitoringAgent" name="editCashlessInfoMonitoringAgent">
                      <option>-- select agent --</option>
                      <option value="<?= $singleData['agent'] ?>" selected><?= $singleData['agent'] ?></option>
                      <?php foreach($agents as $agent): ?>
                          <option value="<?= $agent['user_id'];?>"><?= $agent['user_id'];?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row my-4">
                  <label for="editCashlessInfoMonitoringSource" class="col-sm-4 col-form-label">Source</label>
                  <div class="col-sm-8">
                    <div class="pretty p-default p-curve">
                      <input type="radio" name="editCashlessInfoMonitoringSource" value="call" <?= $singleData['source'] == 'call' ? 'checked' : ''  ?>>
                      <div class="state p-primary-o">
                        <label>Call</label>
                      </div>
                    </div>
                    <div class="pretty p-default p-curve">
                      <input type="radio" name="editCashlessInfoMonitoringSource" value="whatsapp" <?= $singleData['source'] == 'whatsapp' ? 'checked' : ''  ?>>
                      <div class="state p-primary-o">
                        <label>Whatsapp</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row my-4">
                  <label for="editCashlessInfoMonitoringCustomerData" class="col-sm-4 col-form-label">Customer data</label>
                  <div class="col-sm-8">
                    <input type="" class="form-control" id="editCashlessInfoMonitoringCustomerData" name="editCashlessInfoMonitoringCustomerData" value="<?= $singleData['customer_data'] ?>">
                  </div>
                </div>

                <div class="form-group row my-4">
                  <label for="editCashlessInfoMonitoringAgentDone" class="col-sm-4 col-form-label">Informed Cashless?<br><small class="text-muted font-italic">Click to change</small></label>
                  <div class="col-sm-8">
                    <div class="pretty p-svg p-curve p-toggle" title="Click to change">
                      <input type="hidden" name="editCashlessInfoMonitoringAgentDone" value="0" <?= $singleData['done_by_agent'] == 0 ? 'checked' : ''  ?> >
                      <input type="checkbox" name="editCashlessInfoMonitoringAgentDone" id="editCashlessInfoMonitoringAgentDone" value="1" <?= $singleData['done_by_agent'] == 1 ? 'checked' : ''  ?> >
                      <div class="state p-success p-on">
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                          <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label>Informed to customer</label>
                      </div>
                      <div class="state p-danger p-off">
                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                          <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z" style="stroke: white;fill:white;"></path>
                        </svg>
                        <label>Not informed</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-light">
                <a href="<?= base_url('blackbook/dailymonitoring') ?>"> <button type="button" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Cancel</button></a>
                <button type="submit" class="btn btn-primary" name="editCashlessInfoMonitoringSubmit" id="editCashlessInfoMonitoringSubmit"><i class="fas fa-check"></i> Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>