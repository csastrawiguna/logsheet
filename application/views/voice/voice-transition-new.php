<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
		<?php 
			require 'function-voice.php';
	      	if(!$this->input->post()) {
	        	$startPeriod = date("Y-m-d", strtotime("-3 months"));
	        	$endPeriod = date("Y-m-d");
	      	} else {
	        	$startPeriod = $this->input->post('selectTransitionVoiceStart');
	        	$endPeriod = $this->input->post('selectTransitionVoiceEnd');
	      	}
	    ?>
		<div class="container-fluid pt-2 px-1">
			<div class="card">
				<div class="card-header bg-primary">
						Transition of Voice Assessment Result
					<div class="card-tools">
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col">
							<form action="" method="post" style="width: 480px;" class="">
								<label for="selectTransitionVoiceStart">Period</label>
								<input type="date" class="custom-select" name="selectTransitionVoiceStart" id="selectTransitionVoiceStart" style="width: 160px;" value="<?= $startPeriod?>">
								<label for="selectTransitionVoiceEnd">to</label>
								<input type="date" class="custom-select" name="selectTransitionVoiceEnd" id="selectTransitionVoiceEnd" style="width: 160px;" value="<?= $endPeriod?>">
								<button type="submit" class="btn btn-outline-primary" id="buttonSelectTransitionVoice" name="buttonSelectTransitionVoice">Go</button>
							</form>
						</div>
					</div>
					<div class="row mt-5" style="height: 60vh">
						<div class="col pt-5">
							<p class="text-center display-1"><i class="fas fa-people-carry text-info"></i></p>
							<p class="lead text-muted text-center">Currently unavailable. We're still working on it...</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
