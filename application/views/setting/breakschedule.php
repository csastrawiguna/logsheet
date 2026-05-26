<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <?php 
                if (!$this->input->post('breakScheduleSearchDateInput')) {
                    $curdate = date("Y-m-d");
                } else {
                    $curdate = $this->input->post('breakScheduleSearchDateInput');
                }

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

                function nullToString($data){
                    if($data == 'NULL' || $data == NULL ) {
                        echo '-';
                    } else {
                        echo $data;
                    }
                }
            ?>

            <style type="text/css">
                .itemList {
                    border: 1px rgba(105,205,215,0.7) dashed;
                    height: 32px;
                    margin: 8px 8px;
                    width: 128px;
                    display: inline-flex;
                    align-items: center;
                    cursor: move;
                    padding-left: 5px;
                    color: #213344;
                    border-radius: 3px;
                    background-color: rgba(195, 230, 230, 0.2);
                }

                .avatar {
                    height: 20px;
                    line-height: 20px;
                    font-size: 12px;
                    width: 20px;
                    border-radius: 50%;
                    background-color: #17A2B8;
                    color: white;
                    text-align: center;
                }

                .groupContainer {
                    margin: 10px;
                    min-height: 40px;
                    border: 1px rgba(105, 200, 190, 0.3) solid;
                    border-radius: 4px;
                    background-color: rgba(195, 200, 190, 0.1);
                }

                #allunallocated{
                    min-height: 40px;
                }
            </style>
            
            <div class="row">
                <div class="col">
                    <div class="card">
                        <form method="POST" action="">
                            <div class="card-header bg-primary">
                                Break schedule allocation : <?= date("d F Y", strtotime($breakSchedule[0]['date_start'])) ?> - <?= date("d F Y", strtotime($breakSchedule[0]['date_end'])) ?>
                                <div class="card-tools">
                                    <i class="fas fa-info-circle mr-3"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm">
                                        <p class="lead text-indigo mb-3">Break date list (latest 5)</p>
                                        <table class="table table-sm table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Date period</th>
                                                    <th>Remark</th>
                                                    <th class="pl-3">...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($breakDate as $row) : ?>
                                                    <tr>
                                                        <form action="" method="POST">
                                                            <input type="hidden" name="breakScheduleSearchDateInput" value="<?= $row['date_start'] ?>">
                                                            <td><?= $i++; ?></td>
                                                            <td><?= date("d M 'y", strtotime($row['date_start'])) . ' - ' . date("d M 'y", strtotime($row['date_end'])) ?></td>
                                                            <td><?= $row['remark'] ?></td>
                                                            <td class="pl-3">
                                                                <button type="submit" class="btn btn-sm btn-outline-primary" data-id="<?= $row['date_start'] ?>" data-dateend="<?= $row['date_end'] ?>"><i class="fas fa-search"></i> View</button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary buttonCopyToNewSchedule" data-toggle="modal" data-target="#modalNewCopyBreakSchedule" data-id="<?= $row['id'] ?>"><i class="fas fa-copy"></i> Copy to new</button>
                                                                <button type="button" class="btn btn-sm btn-outline-primary buttonEditBreakdate" data-toggle="modal" data-target="#modalEditBreakdate" data-id="<?= $row['id'] ?>"><i class="fas fa-edit"></i> Edit</button>
                                                                <a href="<?= base_url('setting/deleteBreakScheduleGroup')  ?>" class="text-danger buttonDeleteScheduleGroup" data-id="<?= $row['id'] ?>"><button type="buttton" class="btn btn-sm btn-outline-danger"> <i class="fas fa-times"></i> Delete</a>
                                                            </td>
                                                        </form>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>                                            
                                        </table>
                                    </div>
                                </div>
                                <p class="lead text-indigo mt-2">Search by date</p>
                                <div class="row">
                                    <form action="" class="form-row mb-5" method="post" style="width: 500px;">
                                        <label for="breakScheduleSearchDateInput" class="col-sm-1 ml-2 mr-3">Date</label>
                                        <div class="col-sm-auto">
                                            <input type="date" id="breakScheduleSearchDateInput" name="breakScheduleSearchDateInput" class="form-control" value="<?= $curdate ?>" style="max-width: 200px;min-width: 200px">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-outline-primary" id="buttonBreakScheduleSearchDateInput" name="buttonBreakScheduleSearchDateInput"><i class="fas fa-search"></i> View</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <p class="lead text-indigo mb-3 mt-4">Detail Break Schedule</p>
                                <div class="row">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-sm-3" style="max-width: 230px;">
                                                <div class="card card-outline card-primary">
                                                    <div class="card-header bg-light">
                                                        Unallocated
                                                    </div>
                                                    <div class="card-body" id="unallocatedGroup">
                                                        <?php foreach($unAllocatedBreak as $row) : ?>
                                                            <li class="bg-iight itemList" draggable="true" data-name="<?= $row['name'] ?>"  data-breakgroup="0"><span class="avatar mr-2"><?= $row['initial'] ?></span> <?= $row['name'] ?></li>    
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="card card-outline card-info">
                                                    <div class="card-header">Break allocation : <span class="text-danger"><?= date("d M'y", strtotime($breakSchedule[0]['date_start'])) ?> - <?= date("d M'y", strtotime($breakSchedule[0]['date_end'])) ?></span> <span id="breakDetailGroupId" style="display: none;"><?= $breakSchedule[0]['break_date_id'] ?></span></div>
                                                    <div class="card-body">
                                                        <span class="text-primary">Grup #1</span>
                                                        <div class="row mb-4">
                                                            <div class="col groupContainer" id="firstGroup">
                                                                <?php foreach($breakSchedule as $row) : ?>
                                                                    <?php if ($row['break_group'] == 1 ) : ?>
                                                                        <li class="bg-iight itemList" draggable="true" data-name="<?= $row['name'] ?>" data-breakgroup="<?= $row['break_group'] ?>"><span class="avatar mr-2"><?= $row['initial'] ?></span> <?= $row['name'] ?></li>
                                                                    <?php endif ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <span class="text-primary">Grup #2</span>
                                                        <div class="row mb-4">
                                                            <div class="col groupContainer" id="secondGroup">
                                                                <?php foreach($breakSchedule as $row) : ?>
                                                                    <?php if ($row['break_group'] == 2 ) : ?>
                                                                        <li class="bg-iight itemList" draggable="true" data-name="<?= $row['name'] ?>" data-breakgroup="<?= $row['break_group'] ?>"><span class="avatar mr-2"><?= $row['initial'] ?></span> <?= $row['name'] ?></li>
                                                                    <?php endif ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                        <span class="text-primary">Grup #3</span>
                                                        <div class="row">
                                                            <div class="col groupContainer" id="thirdGroup">
                                                                <?php foreach($breakSchedule as $row) : ?>
                                                                    <?php if ($row['break_group'] == 3 ) : ?>
                                                                        <li class="bg-iight itemList" draggable="true" data-name="<?= $row['name'] ?>" data-breakgroup="<?= $row['break_group'] ?>"><span class="avatar mr-2"><?= $row['initial'] ?></span> <?= $row['name'] ?></li>
                                                                    <?php endif ?>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <form action="<?= base_url('setting/updateBreakScheduleGroup') ?>" method="post" id="formSubmitUpdateBreakSchedule">
                                                            <input type="hidden" name="dataCollectBreakScheduleUpdate"  id="dataCollectBreakScheduleUpdate" value="">
                                                            <button type="button" class="btn btn-outline-info" id="buttonUpdateBreakSchedule">Update/save</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    let firstBox = document.getElementById("firstGroup");
    let secondBox = document.getElementById("secondGroup");
    let thirdBox = document.getElementById("thirdGroup");
    let unallocatedBox = document.getElementById("unallocatedGroup");
    let lists = document.getElementsByClassName("itemList");
    
    for (list of lists) {
        list.addEventListener("dragstart", function(e) {
            let selected = e.target;

            firstBox.addEventListener("dragover", function(e) {
                e.preventDefault();
            });
            firstBox.addEventListener("drop", function(e) {
                firstBox.appendChild(selected);
                selected.dataset.breakgroup = 1;
                selected = null;
            });

            secondBox.addEventListener("dragover", function(e) {
                e.preventDefault();
            });
            secondBox.addEventListener("drop", function(e) {
                secondBox.appendChild(selected);
                selected.dataset.breakgroup = 2;
                selected = null;
            });

            thirdBox.addEventListener("dragover", function(e) {
                e.preventDefault();
            });
            thirdBox.addEventListener("drop", function(e) {
                thirdBox.appendChild(selected);
                selected.dataset.breakgroup = 3;
                selected = null;
            });

            unallocatedBox.addEventListener("dragover", function(e) {
                e.preventDefault();
            });
            unallocatedBox.addEventListener("drop", function(e) {
                unallocatedBox.appendChild(selected);
                selected.dataset.breakgroup = 0;
                selected = null;
            });
        });
    }
</script>

<!-- Modal -->
<div class="modal fade" id="modalNewCopyBreakSchedule" tabindex="-1" role="dialog" aria-labelledby="modalNewCopyBreakScheduleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNewCopyBreakScheduleLabel">Copy Break Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url('setting/modalNewCopyBreakSchedule') ?>">
                <div class="modal-body"> 
                    <input type="hidden" name="copyBreakScheduleSourceid" id="copyBreakScheduleSourceid" value="<?= $breakSchedule[0]['id'] ?>">                             
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="copyBreakScheduleStartdate" class="form-label">Start date</label>
                                <div class="">
                                    <input type="date" class="form-control" id="copyBreakScheduleStartdate" name="copyBreakScheduleStartdate" >
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="copyBreakScheduleEnddate" class="form-label">Until</label>
                                <div class="">
                                    <input type="date" class="form-control" id="copyBreakScheduleEnddate" name="copyBreakScheduleEnddate" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="copyBreakScheduleRemark" class="form-label">Remark</label>
                        <div class="">
                            <input type="text" class="form-control" id="copyBreakScheduleRemark" name="copyBreakScheduleRemark" value="">
                        </div>
                    </div>    
                </div>
                <div class="modal-footer">              
                    <button type="submit" class="btn btn-primary" name="submitCopyBreakSchedule" id="submitCopyBreakSchedule">Copy/Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditBreakdate" tabindex="-1" role="dialog" aria-labelledby="modalEditBreakdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditBreakdateLabel">Edit Break Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url('setting/modalEditBreakdate') ?>">
                <div class="modal-body"> 
                    <input type="hidden" class="form-control" name="editBreakdateId" id="editBreakdateId" value="" readonly>  
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="editBreakdateStartdate" class="form-label">Start date</label>
                                <div class="">
                                    <input type="date" class="form-control" id="editBreakdateStartdate" name="editBreakdateStartdate" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="editBreakdateEnddate" class="form-label">Until</label>
                                <div class="">
                                    <input type="date" class="form-control" id="editBreakdateEnddate" name="editBreakdateEnddate" value="" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editBreakdateRemark" class="form-label">Remark</label>
                        <div class="">
                            <input type="text" class="form-control" id="editBreakdateRemark" name="editBreakdateRemark" value="">
                        </div>
                    </div>    
                </div>
                <div class="modal-footer">              
                    <button type="submit" class="btn btn-primary" name="submiteditBreakdate" id="submiteditBreakdate">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>