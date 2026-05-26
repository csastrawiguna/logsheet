<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="container-fluid px-0 pt-3">
		<!-- /.row -->
			<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
			<?php 
				if(!$this->input->post()) {
					$startPeriod = date("Y-m-01", strtotime("-1 months"));
					$endPeriod = date("Y-m-d", strtotime("+2 days"));
				} else {
					$startPeriod = $this->input->post('obidienceDetailDateStart');
					$endPeriod = $this->input->post('obidienceDetailDateEnd');
				}
			?>

			<div class="col-6">
				<div class="card">
					<div class="card-header bg-primary">
						Configure Setting for Overtime
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<form action="" class="form" id="formSetOvertimeHour" method="POST">
                            <div class="form-group row">
                                <label for="maximumOvertimeHour" class="col-sm-8 col-form-label">Maximum overtime hour</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control text-center" id="maximumOvertimeHour" name="maximumOvertimeHour" value="<?= $maximumOvertimeHour; ?>">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-outline-primary" id="buttonSettingMaxLeaveDaily" name="buttonSettingMaxLeaveDaily">Save</button>	                            
	                    </form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
