<div class="content-wrapper">
    <!-- Main content -->
  <section class="content pt-2">
    <div class="container-fluid">
      <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <?php 
        $allowedChangeAgent = ['1', '5', '6', '9'];
        if(!$this->input->post()) {
          $startPeriod = date("Y-m-d", strtotime("-6 months"));
          $endPeriod = date("Y-m-d");
          $agent = $this->session->userdata('user_id');
        } else {
          $startPeriod = $this->input->post('blackbookByAgentDateStart');
          $endPeriod = $this->input->post('blackbookByAgentDateEnd');
          $agent = $this->input->post('blackbookByAgentSelectAgent');
        }

        function voiceLinkToString($data) {
          if ($data == null) {
            return '';
          } else {
            return '<br><a href="' . $data . '" target="_blank" title="cek recording disini"><i class="fas fa-volume-up"></i> link recording</a>';
          }
        }

        function toStringDate($date){
          if(strtotime($date) < 0){
            return '-';
          } else {
            return date("d-M-Y h:i",strtotime($date));
          }
        }
      ?>
      <div class="card">
        <div class="card-header bg-primary">
          Black Note by Agent <span class="text-bold text-warning"><?= date("F Y", strtotime($startPeriod)); ?> to <?= date("F Y", strtotime($endPeriod)); ?></span>
        </div>
        <div class="card-body">                
          <form action="" class="form-row mb-5" method="post" style="width: 820px;">
            <label for="blackbookByAgentSelectAgent" class="col-sm-1">Agent</label>
            <div class="col-sm-2">
              <select id="blackbookByAgentSelectAgent" name="blackbookByAgentSelectAgent" class="custom-select">
                <option selected><?= $agent ?></option>
                <?php if(in_array($this->session->userdata('role_access'), $allowedChangeAgent)): ?>
                  <?php foreach ($allAgents as $ag): ?>
                    <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option><?= $this->session->userdata('user_id'); ?></option>
                <?php endif; ?>
              </select>
            </div>
            <label for="blackbookByAgentDateStart" class="col-sm-1 ml-5">Period</label>
            <div class="col-sm-2">
              <input type="date" id="blackbookByAgentDateStart" name="blackbookByAgentDateStart" class="form-control" value="<?= $startPeriod?>">
            </div>
            <div class="col-sm-2">
              <input type="date" id="blackbookByAgentDateEnd" name="blackbookByAgentDateEnd" class="form-control" value="<?= $endPeriod?>">
            </div>
            <div class="col-sm-1">
              <div class="row ml-1">
                <button type="submit" class="btn btn-outline-primary" id="buttonSelectBlackbookByAgent" name="buttonSelectBlackbookByAgent">Go</button>      
              </div>
            </div>
          </form>
          <table id="tableBlackbookByAgent" class="table table-sm">
          	<?php if($this->session->userdata('role_access') == 9 || $this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 6 ): ?>
	            <thead>
	              <tr style="max-width: 40px;">
	                <th class="text-center">No</th>
	                <th>Date</th>
	                <th>Type of Sin</th>
	                <th>Detail</th>
	                <th>Remark</th>
	                <th class="text-center">Action</th>
	              </tr>
	            </thead>
	            <tbody>
	              <?php $i = 1; ?>
	              <?php foreach($allBlackNotesByAgent as $data): ?>                     
	                <tr>
	                    <td class="text-center"><?= $i++; ?></td>
	                    <td><?= date("d-M-Y", strtotime($data['date'])); ?></td>
	                    <td>
                        <?= $data['type']; ?>
                        <span class="float-right badge badge-secondary font-weight-normal mr-3"><?= $data['score'] ?></span>
                      </td>
	                    <td><?= $data['detail']; ?>. <?= voiceLinkToString($data['voice_link']); ?></td>
	                    <td><?= $data['remark']; ?></td>
	                    <td class="text-center h6">
	                    	<div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                            <table  class="table table-sm table-borderless table-hover">
                              <tbody>
                                <tr>
                                  <td>Saved by</td>
                                  <td class="">: <?= $data['saved_by']; ?></td>
                                </tr>
                                <tr>
                                  <td>Saved at</td>
                                  <td class="">: <?= toStringDate($data['saved_at']); ?></td>
                                </tr>
                                <tr>
                                  <td>Last modified</td>
                                  <td class="">: <?= $data['last_modified_by']; ?></td>
                                </tr>
                                <tr>
                                  <td>Saved at</td>
                                  <td class="">: <?= toStringDate($data['last_modified_at']); ?></td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="table table-sm table-borderless">
                              <tbody>
                                <tr class="border-top">
                                  <td class="py-2">
                                  	<a href="" class="text-dark buttonBlackbookDataEdit" title="Edit data" data-id="<?= $data['id']; ?>">
                                      <i class="fas fa-pen"></i></span> &nbspEdit data
                                  </a>
                                  </td>
                                </tr>
                                <tr class="border-top">
                                  <td class="py-2">
                                  	<a href="<?= base_url()?>blackbook/delete/<?=$data['id']?>" class="text-danger buttonBlackbookDataDelete" title="Delete data" data-id="<?= $data['id']; ?>" style="cursor: pointer; text-decoration: none;">
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
            <?php else: ?>
	            <thead>
	              <tr style="max-width: 40px;">
	                <th class="text-center">No</th>
	                <th>Date</th>
	                <th>Type of Sin</th>
	                <th>Detail</th>
	                <th>Remark</th>
	              </tr>
	            </thead>
	            <tbody>
	              <?php $i = 1; ?>
	              <?php foreach($allBlackNotesByAgent as $data): ?>                     
	                <tr>
	                    <td class="text-center"><?= $i++; ?></td>
	                    <td><?= date("d-M-Y", strtotime($data['date'])); ?></td>
	                    <td><?= $data['type']; ?></td>
	                    <td><?= $data['detail']; ?></td>
	                    <td><?= $data['remark']; ?></td>                    
	                </tr>
	              <?php endforeach; ?>
	            </tbody>
	        <?php endif; ?>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
