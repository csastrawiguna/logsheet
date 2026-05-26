<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style=""><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <?php 
                if(!$this->input->post()) {
                    $inputStartPeriod = date("Y-m-01", strtotime("-1 months"));
                    $inputEndPeriod = date("Y-m-d");
                };

                function toStringDate($date){
                  if(strtotime($date) < 0){
                    return '-';
                  } else {
                    return date("d-M-Y h:i",strtotime($date));
                  }
                }

                function toBoolean($data){
                	if($data == 1) {
                		echo 'checked';
                	} else {
                		return '';
                	}
                }

                function toTextClass($data){
                    if($data == 1) {
                        return '<label class="text-primary">Active</label>';
                    } else {
                        return '<label class="text-secondary">Inactive</label>';
                    }
                }

                function wordLimiter($text, $limit = 20) {
                    if (str_word_count($text, 0) > $limit) {
                        $words = str_word_count($text, 2);
                        $pos   = array_keys($words);
                        $text  = substr($text, 0, $pos[$limit]) . '...';
                    }
                    return $text;                    
                }
            ?>

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Items displayed on Dashboard
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Is Shown?</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                	<?php foreach($allItemsInfo as $data) : ?>
                                		<tr>
                                			<td><?= $data['dashboard_item']; ?></td>
                                			<td>                                				
                                				<div class="pretty p-switch p-fill">
					                              <input type="checkbox" class="buttonActivateDashboardItem" data-value="<?= $data['is_active'] ?>" data-itemid="<?= $data['id'] ?>" <?php toBoolean($data['is_active']) ?>>
					                              <div class="state p-primary" oninput="assignUnassignUser();">
					                                <label></label>
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

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-primary">
                            List of Info displayed on "Info Seputar CCC"
                            <div class="card-tools">                                
                                <a href="<?= base_url('setting/addgeneralinfo') ?>" class="text-white mr-3" ><i class="fas fa-plus-circle" ></i> Add new</a>
                            </div>
                        </div>
                        <div class="card-body">                                       
                            <table class="table table-sm" id="tableGeneralInfoList">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Info</th>
                                        <th class="text-center">Status</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($allDashboardInfo as $data) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $data['detail_info'] ?></td>
                                            <td class="text-center">
                                                <?php if ($data['status'] == 3) { ?>
                                                    <span class="badge text-danger">Inactive</span>
                                                <?php } else if ($data['status'] == 1) { ?>
                                                    <span class="badge text-success">Sticky</span>
                                                <?php } else { ?>
                                                    <span class="badge text-primary">Active</span>
                                                <?php }  ?>
                                            </td>
                                            <td>
                                                <div class="input-group-prepend mx-auto">
                                                    <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-bars text-dark"></i>
                                                    </button>
                                                    <div class="dropdown-menu p-2">
                                                        <table class="table table-borderless table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Saved by</td>
                                                                    <td>:</td>
                                                                    <td>-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Saved at</td>
                                                                    <td>:</td>
                                                                    <td><?= toStringDate($data['saved_at']) ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Last modified by</td>
                                                                    <td>:</td>
                                                                    <td>-</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Last modified at</td>
                                                                    <td>:</td>
                                                                    <td><?= toStringDate($data['updated_at']) ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="<?= base_url('setting/editgeneralinfo/') . $data['id'] ?>" data-id="<?= $data['id']; ?>"><i class="fas fa-pen text-dark"></i> &nbspEdit Info</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item buttonToggleActiveInactive" href="#" data-id="<?= $data['id']; ?>"><i class="fas fa-exchange-alt text-primary"></i> &nbspToggle Active-Inactive</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item buttonGeneralInfoContentDelete" href="<?= base_url('setting/deletegeneralinfo')?>" data-id="<?= $data['id']; ?>"><i class="fas fa-trash text-danger"></i> &nbspDelete Info</a>
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

            <div class="row">
                <div class="col-6" style="min-width: 540px;">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Survey
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('setting/setSurveyActiveness') ?>" method="post">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="col-10">
                                                Survey activeness<br>
                                                <small class="text-secondary"><em>(if new user will not able access menu except fill the survey)</em></small>
                                            </td>
                                            <td class="pl-4 ml-1 col-2">
                                                <div class="pretty p-switch p-fill">                                                
                                                  <input type="hidden" class="form-control" id="buttonActivateSurveyFake" name="buttonActivateSurvey" <?= toBoolean($surveyItem); ?>  value="0">
                                                  <input type="checkbox" class="form-control" id="buttonActivateSurvey" name="buttonActivateSurvey" <?= toBoolean($surveyItem); ?> value="<?= $surveyItem ?>">
                                                  <div class="state p-primary">
                                                    <!-- <label>Active</label> -->
                                                    <?= toTextClass($surveyItem) ?>
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-10">
                                                Minimum survey treshold<br>
                                                <small class="text-secondary"><em>(if new user will not able access menu except fill the survey)</em></small>
                                            </td>
                                            <td class="col-2">
                                                <input type="number" class="form-control" id="minQtySurvey" name="minQtySurvey" value="<?= $minQtySurvey ?>" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="submit" class="btn btn-outline-primary" id="submitActivateSurvey">Save</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>