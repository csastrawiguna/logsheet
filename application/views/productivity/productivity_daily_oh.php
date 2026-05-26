<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <?php 
            require 'function_productivity.php';
            ?>

            <!-- <?php var_dump($transitionProductivityDaily) ?> -->

            <div class="row">
                <div class="col-sm-auto">
                    <?php if (in_array($this->session->userdata('role_access'), $allowedChangeAgent)) : ?>
                        <div class="card collapsed-card card-primary" style="max-width: 400px; min-width: 400px;">
                            <div class="card-header">
                                Target produktivitas
                                <div class="card-tools">
                                    <a href="<?= base_url('setting/dailyproductivity') ?>" class="text-white mr-2">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Job desk</th>
                                            <th>Target</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allProductivityDailyTarget as $row) : ?>
                                            <tr>
                                                <td><?= $row['icon'] . ' &nbsp; ' . $row['jobdesk'] ?></td>
                                                <td><?= $row['target'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-auto mr-3">
                    <div class="card" style="max-width: 400px; min-width: 400px;">
                        <div class="card-header bg-primary">
                            FU Full History : <span class="text-bold"> <?= date("d M Y", strtotime($startDate)) ?> - <?= date("d M Y", strtotime($endDate)) ?> </span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Agent</th>
                                        <th class="text-center">Times</th>
                                        <th class="text-center">Total FU</th>
                                        <th class="text-center">Avg FU/day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($fuschedule as $row) : ?>
                                        <tr>
                                            <td class="text-center"><?= $i++ ?></td>
                                            <td><?= $row['agent'] ?></td>
                                            <td class="text-center"><?= $row['times'] ?></td>
                                            <td class="text-center"><?= $row['totalfu'] ?></td>
                                            <td class="text-center"><?= number_format($row['averagefu'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="card" style="max-width: 400px; min-width: 400px;">
                        <div class="card-header bg-primary">
                            WA Full History : <span class="text-bold"> <?= date("d M Y", strtotime($startDate)) ?> - <?= date("d M Y", strtotime($endDate)) ?> </span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Agent</th>
                                        <th class="text-center">Times</th>
                                        <th class="text-center">Total WA</th>
                                        <th class="text-center">Avg WA/day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($waschedule as $row) : ?>
                                        <tr>
                                            <td class="text-center"><?= $i++ ?></td>
                                            <td><?= $row['agent'] ?></td>
                                            <td class="text-center"><?= $row['times'] ?></td>
                                            <td class="text-center"><?= $row['totalwa'] ?></td>
                                            <td class="text-center"><?= number_format($row['averagewa'], 0) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-primary">
                    Produktivitas Harian (tidak termasuk saat lembur)
                    <div class="card-tools">
                        <?php if (in_array($this->session->userdata('role_access'), $allowedChangeAgent)) : ?>
                            <!-- <a href="<?= base_url('productivity/addproductivitydaily') ?>" class="mr-3 text-white"><i class="fas fa-plus-circle"></i> Add single date</a> -->
                            <!-- <a type="button" class="text-white mr-3" data-toggle="modal" data-target="#modalProductivityDailyEditSingle"><i class="fas fa-edit"></i> Maintain data</a> -->
                            <div class="btn-group mr-3">
                                <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-edit"></i> Maintain data</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalProductivityDailyEditSingle" ><i class="fas fa-file"></i> Productivity Daily</a>
                                    <a class="dropdown-item" href="<?= base_url('productivity/maintaininterval') ?>" data-toggle="" data-target="" ><i class="fas fa-clock"></i> Productivity Interval</a>
                                </div>
                            </div>
                            <div class="btn-group mr-3">
                                <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-upload"></i> Upload data</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalProductivityDailyAddSingle" ><i class="fas fa-plus-circle"></i> Upload single date</a>
                                    <a class="dropdown-item" href="#" data-toggle="" data-target="#modalProductivityDailyAddMultiple" ><i class="fas fa-layer-group"></i> Upload multiple date</a>
                                    <a class="dropdown-item" href="<?= base_url('productivity/interval') ?>" ><i class="fas fa-clock"></i> Produktivitas per interval</a>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-file-alt"></i> Format upload</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?= base_url('files/Format_Upload_Productivity_Harian_single_date.xlsx') ?>"><i class="far fa-file"></i> Single Date</a>
                                    <a class="dropdown-item" href="#" onclick="Swal.fire('Unavailable', 'Upload format still unavailable', 'error')"><i class="far fa-file-alt"></i> Multiple Date</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <form action="" method="post" style="width: 880px;">
                                <label for="productivityDailySelectDateStart">Period</label>
                                <input type="date" class="custom-select" name="productivityDailySelectDateStart" id="productivityDailySelectDateStart" style="width: 160px;" value="<?= $startDate; ?>">
                                <label for="productivityDailySelectDateEnd">to</label>
                                <input type="date" class="custom-select" name="productivityDailySelectDateEnd" id="productivityDailySelectDateEnd" style="width: 160px;" value="<?= $endDate; ?>">
                                <label for="productivityDailySelectAgent" class="ml-5">Agent</label>                
                                <select class="col-sm-2 custom-select" name="productivityDailySelectAgent" id="productivityDailySelectAgent" style="width: 240px;">
                                    <option selected><?= $agent ?></option>
                                    <?php if (in_array($this->session->userdata('role_access'), $allowedChangeAgent)) : ?>
                                        <?php foreach ($allAgents as $row) : ?>
                                            <option value="<?= $row['user_id']; ?>"><?= $row['user_id']; ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="<?= $this->session->userdata('user_id'); ?>" selected><?= $this->session->userdata('user_id'); ?></option>
                                    <?php endif; ?>
                                </select>
                                <button type="submit" class="btn btn-outline-primary" id="buttonProductivityDailySubmit" name="buttonProductivityDailySubmit">Go</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-10" style="max-width: 720px;">                                    
                            <?php if(is_null($allProductivityDailyData)) :  ?>
                                <p class="lead text-muted pl-5">No data to be displayed</p>
                            <?php else : ?>
                                <table class="table table-hover table-sm">
                                    <thead class="border-bottom">
                                        <tr class="">
                                            <th>Date</th>
                                            <th>Job</th>
                                            <th class="text-center">Target</th>
                                            <th class="text-center">Call</th>
                                            <th class="text-center">Whatsapp</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Follow up</th>
                                            <th class="text-center">Total</th>
                                            <th class="">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                              
                                        <?php foreach ($allProductivityDailyData as $row): ?>
                                            <tr class="<?= isundertarget($row['total'], $row['followup'], $row['assignment'], $row['target']); ?>">
                                                <td><?= date("d M Y", strtotime($row['date'])) ?></td>
                                                <td><?= assignment2icon($row['assignment']) ?></td>
                                                <td class="text-center"><?= $row['target'] ?></td>
                                                <td class="text-center"><?= $row['icall'] ?></td>
                                                <td class="text-center"><?= $row['whatsapp_reply'] ?></td>
                                                <td class="text-center"><?= $row['sms_email'] ?></td>
                                                <td class="text-center"><?= $row['followup'] ?></td>
                                                <td class="text-center"><?= $row['total'] ?></td>
                                                <td><?= $row['remark'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="text-bold bg-light">
                                            <td colspan="3" class="text-center">Average</td>
                                            <td class="text-center"><?= number_format($totalProductivityDailyData['ave_icall'], 0) ?></td>
                                            <td class="text-center"><?= number_format($totalProductivityDailyData['ave_whatsapp_reply'], 0) ?></td>
                                            <td class="text-center"><?= number_format($totalProductivityDailyData['ave_sms_email'], 0) ?></td>
                                            <td class="text-center"><?= number_format($totalProductivityDailyData['ave_followup'], 0) ?></td>
                                            <td class="text-center"><?= number_format($totalProductivityDailyData['total'], 0) ?></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col">
                            <canvas id="chartProductivityDailyByAgent" height="80" width="340"></canvas>
                        </div>
                    </div>
                </div>                            
            </div>
            
            <!-- Summary by agent for Analytics-->
            <div class="card" style="">
                <div class="card-header bg-primary">
                Summary of Daily Productivity: <span style="color: #ff0"><?= date("d F Y", strtotime($startDate)) ?> - <?= date("d F Y", strtotime($endDate)) ?></span> <span class="badge badge-pill badge-danger">Analytics</span>
                    <div class="card-tools">
                        <a href="#" id="buttonProductvitiyDailySummaryToExcel" class="text-light mr-2 h5"><i class="fas fa-file-excel"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm" style="max-width: 720px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th class="text-center">Icall</th>
                                <th class="text-center">Whatsapp</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Follow Up</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Target</th>
                                <th colspan="2">Achievement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($summbyProductivityDailyByAgentNonzero as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $row['agent'] ?></td>
                                    <td class="text-center"><?= number_format($row['icall'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['whatsapp_reply'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['sms_email'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['followup'], 0) ?></td>
                                    <td class="text-center bg-light"><?= number_format($row['total'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['target_daily'], 0) ?></td>
                                    <td style="width: 100px;">
                                        <div class="progress-group">
                                            <div class="progress">
                                                <div class="progress-bar bg-<?= achievement2color($row['ratio_daily']) ?>" style="width: <?= number_format($row['ratio_daily'], 3) * 100 ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-<?= achievement2color($row['ratio_daily']) ?>"><?= number_format($row['ratio_daily'], 3) * 100 ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary by agent for Management -->
            <div class="card collapsed-card" style="">
                <div class="card-header bg-primary">
                Summary of Daily Productivity: <span style="color: #ff0"><?= date("d F Y", strtotime($startDate)) ?> - <?= date("d F Y", strtotime($endDate)) ?></span> <span class="badge badge-pill badge-light">Management Report</span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm" style="max-width: 720px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Agent</th>
                                <th class="text-center">Icall</th>
                                <th class="text-center">Whatsapp</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Follow Up</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Target</th>
                                <th colspan="2">Achievement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($summbyProductivityDailyByAgentNonzeroMgt as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $row['agent'] ?></td>
                                    <td class="text-center"><?= number_format($row['icall'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['whatsapp_reply'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['sms_email'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['followup'], 0) ?></td>
                                    <td class="text-center bg-light"><?= number_format($row['total'], 0) ?></td>
                                    <td class="text-center"><?= number_format($row['target_general'], 0) ?></td>
                                    <td style="width: 100px;">
                                        <div class="progress-group">
                                            <div class="progress">
                                                <div class="progress-bar bg-<?= achievement2color($row['ratio_general']) ?>" style="width: <?= number_format($row['ratio_general'], 3) * 100 ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-<?= achievement2color($row['ratio_general']) ?>"><?= number_format($row['ratio_general'], 3) * 100 ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
                
            <?php if (in_array($this->session->userdata('role_access'), $allowedChangeAgent)) : ?>
                <div class="card" style="">
                    <div class="card-header bg-primary">
                    Transisi produktivitas harian: <span style="color: #ff0"><?= date("d F Y", strtotime($startDate)) ?> - <?= date("d F Y", strtotime($endDate)) ?></span>
                    </div>
                    <div class="card-body">
                        <?php if(is_null($transitionProductivityDaily)) :  ?>
                            <p class="lead text-muted pl-5">No data to be displayed</p>
                        <?php else : ?>
                            <table class="table table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="" >User ID</th>
                                        <?php
                                        $keys = array_keys($transitionProductivityDaily[0]);
                                        for ($col = 1; $col < count($transitionProductivityDaily[0]); $col++) :
                                        $nama_kolom = date("d-M", strtotime($keys[$col]));
                                        ?>
                                        <th class="text-center text-sm"><?= str_replace('_', ' ', $nama_kolom); ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transitionProductivityDaily as $row) : ?>
                                    <tr>
                                        <?php
                                        for ($baris = 0; $baris < count($transitionProductivityDaily[0]); $baris++) :
                                        $baris_data = $keys[$baris];
                                        $row[$baris_data] == null ? $cell_value = '-' : $cell_value = $row[$baris_data];

                                        is_numeric($cell_value) ? $cell = '<td  class="text-center">' . $cell_value . '</td>' : $cell = '<td>' . str_replace(',', '.', $cell_value) . '</td>';
                                        echo $cell;
                                        endfor;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<div class="modal fade" id="modalProductivityDailyAddSingle" tabindex="-1" role="dialog" aria-labelledby="modalProductivityDailyAddSingleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductivityDailyAddSingleLabel">Add Productivity Daily from Excel (Single Date)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('productivity/addproductivitydailysingledate'); ?> 
                <div class="form-group">
                    <label for="productivityDailyAddSingleDate">Date</label><br>
                    <input type="date" class="form-control" id="productivityDailyAddSingleDate" name="productivityDailyAddSingleDate" value="<?= date('Y-m-d', strtotime('-1 days')) ?>">
                </div>                                
                <div class="form-group">
                    <label for="productivityDailyAddSingleFile">File</label><br>
                    <input type="file" class="form-control" id="productivityDailyAddSingleFile" name="productivityDailyAddSingleFile">
                </div>                
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-warning"><i class="fas fa-undo"></i> Reset</button>
                <button type="submit" class="btn btn-primary" name="productivityDailyAddSubmit"><i class="fas fa-upload"></i> Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProductivityDailyEditSingle" tabindex="-1" role="dialog" aria-labelledby="modalProductivityDailyAddSingleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="modalProductivityDailyAddSingleLabel">Edit/Delete Productivity Daily Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('productivity/maintain') ?>" method="POST">
                    <div class="row">
                        <div class="col">
                            <p class="mb-3 text-bold">Select Agent</p>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <select type="" class="col-sm-11 form-control" id="productivityDailyEditAgent" name="productivityDailyEditAgent" placeholder="Agent">
                            <option value="">- all agent -</option>
                            <?php foreach($allAgents as $row) : ?>
                                <option value="<?= $row['user_id'] ?>"><?= $row['user_id'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p class="mb-3 text-bold">Select date period (start and end date)</p>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <input type="date" class="col-sm-5 form-control" id="productivityDailyEditStartdate" name="productivityDailyEditStartdate" placeholder="Date start" value="">
                        <div class="col-sm-1">to</div>
                        <input type="date" class="col-sm-5 form-control" id="productivityDailyEditEnddate" name="productivityDailyEditEnddate" placeholder="Date end" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="productivityDailyEditSubmit"><i class="fas fa-truck-loading"></i> Load Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProductivityDailyEditInterval" tabindex="-1" role="dialog" aria-labelledby="modalProductivityDailyAddSingleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="modalProductivityDailyAddSingleLabel">Edit/Delete Productivity Daily Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('productivity/maintaininterval') ?>" method="POST">
                    <div class="row">
                        <div class="col">
                            <p class="mb-3">Select date period (start and end date)</p>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <input type="date" class="col-sm-5 form-control" id="productivityIntervalEditStartdate" name="productivityIntervalEditStartdate" placeholder="Date start" value="">
                        <div class="col-sm-1">to</div>
                        <input type="date" class="col-sm-5 form-control" id="productivityIntervalEditEnddate" name="productivityIntervalEditEnddate" placeholder="Date end" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="productivityIntervalEditSubmit">Load Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProductivityDailyAdd" tabindex="-1" role="dialog" aria-labelledby="modalProductivityDailyAddLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductivityDailyAddLabel">Add Productivity Daily from Excel (Multiple Date)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('elearning/addproductivitydailymultiple'); ?>                                
                <div class="form-group">
                    <label for="productivityDailyAddFile">File</label><br>
                    <input type="file" class="" id="productivityDailyAddFile" name="productivityDailyAddFile">
                </div>                
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary" name="productivityDailyAddSubmit">Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>