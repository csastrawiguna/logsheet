<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="container-fluid px-0 pt-3">
		<!-- /.row -->
			<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
			
			<div class="col-6">
				<div class="card">
					<div class="card-header bg-primary">
						Configure Setting for Leave
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<form action="" class="form" id="formSetMaxLeavePerDay" method="POST">
                            <div class="form-group row">
                                <label for="settingMaxLeaveDaily" class="col-sm-8 col-form-label">Maximum staff leave per day</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control text-center" id="settingMaxLeaveDaily" name="settingMaxLeaveDaily" value="<?= $maxLeavePerDay; ?>">
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
