<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="container-fluid px-0 pt-3">
		<!-- /.row -->
			<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
			
			<div class="col-8">
				<div class="card">
					<div class="card-header bg-primary">
						Configure Setting for Overtime
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<form action="" class="form" id="formSetOvertimeHour" method="POST">
                            <div class="form-group row">
                                <label for="maximumOvertimeHourSeidPermanent" class="col-sm-8 col-form-label">Maximum overtime hour (SEID - Permanent)</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control text-center" id="maximumOvertimeHourSeidPermanent" name="maximumOvertimeHourSeidPermanent" value="<?= $maximumOvertimeHourSeidPermanent; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maximumOvertimeHourSeidContract" class="col-sm-8 col-form-label">Maximum overtime hour (SEID - Contract)</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control text-center" id="maximumOvertimeHourSeidContract" name="maximumOvertimeHourSeidContract" value="<?= $maximumOvertimeHourSeidContract; ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="maximumOvertimeHourOts" class="col-sm-8 col-form-label">Maximum overtime hour (OTS)</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control text-center" id="maximumOvertimeHourOts" name="maximumOvertimeHourOts" value="<?= $maximumOvertimeHourOts; ?>">
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
