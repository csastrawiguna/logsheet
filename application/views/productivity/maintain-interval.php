<div class="content-wrapper">
  <!-- Main content -->
  <section class="content pt-2 px-1">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
      <div class="container-fluid">
        <?php 
            
        ?>

				<div class="card card-primary">
					<div class="card-header">
						<?php if(count($dataToEdit) < 1 ) : ?>
							<span>No data to be displayed</span>
						<?php else : ?>
							<span>Edit Productivity Invterval per : <b><?= date("d M Y H:i", strtotime($dataToEdit[0]['datetime'])) ?></b></span>
						<?php endif; ?>
						<div class="card-tools">
							<a href="<?= base_url('productivity/interval') ?>" class="text-white mr-3"><i class="fas fa-upload"></i> Upload data</a>
						</div>
					</div>
					<div class="card-body">
						<div class="row mt-3">
							<div class="col-md-10">
								<?php if(count($dataToEdit) < 1 ) : ?>
									<div style="min-height: 60vh;">
										<p class="lead text-center text-danger" style="padding-top: 120px; font-size: 20px;">There were no data to be displayed from database</p>
										<p class="text-center">
											<a href="<?= base_url('productivity/interval') ?>"><button class="btn btn-outline-primary"><i class="fas fa-edit"></i> Add/upload data</button></a>
										</p>
									</div>
								<?php else : ?>
									<form method="POST" action="">
					  				<div class="form-group row">
					        		<label for="maintainProductivityIntervalDatetime" class="col-sm-2 col-form-label" style="max-width: 100px;">Datetime</label>
						        	<div class="col-sm-3">
						          	<input type="" class="form-control" id="maintainProductivityIntervalDatetime" value="<?= date("d-M-Y H:i", strtotime($dataToEdit[0]['datetime'])) ?>">
						        	</div>
						    		</div>

						    		<table class="table table-sm" id="tableListProductivityForEdit">
						        	<thead class="bg-light">
												<tr class="">
													<th class="text-center">
														<div class="pretty p-default">
															<input type="checkbox" id="buttonSelectAllProductivityInterval" value="">
															<div class="state p-danger">
																<label></label>
															</div>
														</div>
													</th>
													<th>#</th>
													<th>Agent</th>
													<th class="pl-3">Assign</th>
													<th class="pl-4">iCall</th>
													<th class="pl-4">WA</th>
													<th class="pl-4">FU</th>
													<th class="pl-4">Total</th>
													<th>Remark</th>
													<th class="text-center">...</th>
												</tr>
						        	</thead>
						        	<tbody>
						            <?php $i = 1; ?>
						            <?php foreach($dataToEdit as $row) : ?>
													<tr>
														<td class="text-center align-middle" style="width: 20px">
															<div class="pretty p-default">
																<input type="checkbox" class="buttonSelectAgentProductivityInterval" value="<?= $row['agent'] ?>">
																<div class="state p-warning">
																	<label></label>
																</div>
															</div>
														</td>
														<td class="align-middle"><?= $i ?> </td>
														<td>
															<input type="" class="form-control-plaintext text-bold" name="<?= $i - 1; ?>[agent]" value="<?= $row['agent'] ?>" style="max-width: 80px;">
														</td>
														<td  style="width: 160px;">
															<select class="custom-select" style="max-width: 180px;" name="<?= $i - 1; ?>[assignment]">
																<option value="<?= $row['assignment'] ?>" selected><?= $row['assignment'] ?></option>
																<option value="Reguler">Reguler</option>
																<option value="Whatsapp">Whatsapp</option>
																<option value="Follow Up">Follow Up</option>
															</select>
														</td>
														<td class="">
															<input type="" class="form-control text-center prodCall" name="<?= $i - 1; ?>[icall]" value="<?= $row['icall'] ?>" style="max-width: 70px;">
														</td>
														<td class="">
															<input type="" class="form-control text-center prodWhatsapp" name="<?= $i - 1; ?>[whatsapp]" value="<?= $row['whatsapp'] ?>" style="max-width: 70px;">
														</td>
														<td class="">
															<input type="" class="form-control text-center prodFollowup" name="<?= $i - 1; ?>[follow_up]" value="<?= $row['follow_up'] ?>" style="max-width: 70px;">
														</td>
														<td class="text-center">
															<input type="" class="form-control text-center text-bold prodTotal" name="" value="<?= $row['icall'] + $row['whatsapp'] + $row['follow_up'] ?>" style="max-width: 70px;" readonly>
														</td>
														<td class="">
															<input type="" class="form-control" name="<?= $i - 1; ?>[remark]" value="<?= $row['remark'] ?>">
														</td>
														<td class="text-center align-middle">
															<button class="btn buttonDeleteProductivityInterval" data-agent="<?= $row['agent'] ?>">
																<i class="fas fa-times text-danger"></i>
															</button>
														</td>
													</tr>
													<?php $i += 1; ?>
					            	<?php endforeach; ?>
						        	</tbody>
							    	</table>
							    	<button class="btn btn-primary my-2"><i class="fas fa-check"></i> Update Data</button>
							    	<button type="button" class="btn btn-outline-danger" id="buttonDeleteSelectedProductivityInterval" style="display: none;"><i class="fas fa-times"></i> Delete Selected Rows</button>
									</form>
								<?php endif; ?>
							</div>
						</div>
					</div>
        </div>
      </div>
    </div>
  </section>
</div>