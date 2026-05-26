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
		<div class="container-fluid pt-3">
			<div class="card">
				<div class="card-header bg-primary">
					Transition
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
					<div class="row mt-5">
						<div class="col">
							<table class="table table-sm table-hover table-responsive" id="voiceTableTransitionByPeriod">
                                <thead class="bg-light">
                                 <tr>
                                     <th class="" >User ID</th>
                                     <?php
                                        $keys = array_keys($transitionVoiceByPeriod[0]);
                                        for ($col = 1; $col < count($transitionVoiceByPeriod[0]) -1; $col++) :
                                            $nama_kolom = date("M-y", strtotime($keys[$col]));
                                        ?>
                                         <th class="text-center"><?= str_replace('_', ' ', $nama_kolom); ?></th>
                                     <?php endfor; ?>
                                      <th class="text-center">Total</th>
                                     <!-- <th>target (times)</th> -->
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php foreach ($transitionVoiceByPeriod as $row) : ?>
                                 	<?php if ($row['is_active'] == 1 ) : ?>
                                     <tr>
                                     <?php
                                        // echo "<td>" . $row['agent'] . "</td>";
                                        // for ($baris = count($transitionVoiceByPeriod[0]) - 1; $baris > 0; $baris--) :
                                        $totalvalue = 0;
                                        for ($baris = 0; $baris < count($transitionVoiceByPeriod[0]) - 1 ; $baris++) :
                                            $baris_data = $keys[$baris];
                                            $row[$baris_data] == null ? $cell_value = '-' : $cell_value = $row[$baris_data];
                                            $totalvalue += (int)$cell_value;

                                            if (is_numeric($cell_value)) {
                                            	$cell = '<td  class="text-center">' . $cell_value . '</td>';	
                                            } else {
                                            	$cell = '<td>' . str_replace(',', '.', $cell_value) . '</td>';
                                            }
                                            echo $cell;
                                        endfor; ?>
                                        <td class="text-center"><?= $totalvalue ?></td>
                                    <?php endif; ?>
                                 <?php endforeach; ?>
                             </tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
