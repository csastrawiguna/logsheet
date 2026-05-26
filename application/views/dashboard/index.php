<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <!-- <p id="surveyFilling" style="display: none;"><?= $isdonesurvey;?></p> -->
        <!-- <p id="surveyFilling" style="display: none;"><?= $isdonesurvey ?></p>
        <p id="surveyTreshold" style="display: none;"><?= $surveyTreshold ?></p> -->
        
        <div class="container-fluid">
            <style type="text/css">
                .itemList {
/*                    border: 1px rgba(195,205,215,0.3) solid;*/
                    height: 32px;
                    margin: 1px 8px;
                    width: 100px;
                    display: inline-flex;
                    align-items: flex-start;
                    cursor: move;
                    padding-left: 5px;
                    color: #213344;
                    border-radius: 3px;
                    background-color: rgba(195, 200, 230, 0.2);
                }

                .avatar {
                    height: 20px;
                    line-height: 20px;
                    font-size: 12px;
                    width: 20px;
                    border-radius: 50%;
                    background-color: #47f268;
                    color: white;
                    text-align: center;
                }
            </style>
            <!-- /.row -->
            <?php
                function ramadhanDaysCount(){
                    $date1 = date_create("2023-03-22");
                    $date2 = date_create(date("Y-m-d"));
                    $diff = date_diff($date2, $date1);
                    echo $diff->format("Ramadhan day #%a");
                }                

                function statusToDisplay($data){
                    if($data == 0 ) {
                        echo 'display: none';
                    } else {
                        echo '';
                    }
                }

                function quoteDisplay($data) {
                    if($data == '' ) {
                        return 'Your quote is lorem ipsum';
                    } else {
                        return '"' . $data . '"';
                    }
                }

                function accessToAction($agent) {
                    if ($this->session->userdata('role_access') == '9' || $this->session->userdata('user_id') == $agent) {

                    }
                }

                function nameToColor($name1, $name2) {
                    if ($name1 == $name2) {
                        return 'text-primary';
                    } else {
                        return;
                    }
                }

                function voteListNumToText($list) {
                    $out = [];
                    if (count($list) == 0 ) {
                        $out = [
                            'title' => 'No Vote',
                            'detail' => 'Currently there were no vote'
                        ];
                    } else if (count($list) == 1) {
                        $out = [
                            'title' => $list[0]['vote_name'],
                            'detail' => $list,
                        ];
                    } else {
                        $out = [
                            'title' => count($list) . ' votes',
                            'detail' => $list,
                        ];
                    }

                    return $out;
                }

                function nullToButton($vt) {
                    if (is_null($vt)) {
                        return ' Vote ';
                    } else {
                        return '<i class="fas fa-bars"></i>';
                    }
                }

                $allowedUser = [1, 5, 6, 9];
                $headUsers = [5, 9];
             ?>

            <div class="card mt-3 mb-3" style="<?= statusToDisplay($dashboardItem['profile_info']) ?>; background-image: url(<?= base_url('assets/img/profile-bg/') . $profilebg; ?>); background-repeat: no-repeat; background-position: bottom; background-size: cover; height: 360px;">
                <div class="col-md-12 text-center pt-3 pb-0">
                    <?php if ($this->session->userdata('role_access') == 10) : ?>
                        <img class="img-circle" src="<?= base_url().'files/kucing_kawin.jpg'; ?>" alt="" height="260">
                        <div class="row mt-3" style="background-color: rgba(255, 255, 255, 0.5);">
                            <div class="col-sm-12 text-center mt-3">
                                <p class="text-muted lead" style="font-size: 24px;">Awawawawawawaw....</p>
                            </div>
                            <div class="col text-center">
                                <div class="error lead text-danger" style="font-size: 36px;">User Account Banned</div>
                                <p class="lead text-gray mt-3">Your account has been suspensed due to unconformity reason</p>
                            </div>
                        </div>
                        <p class="font-italic text-muted">__ <?= ramadhanDaysCount(); ?> __</p>
                    <?php else :  ?>
                        <img class="img-circle" src="<?= base_url().'assets/img/profile/'.$this->session->userdata('profile_photo'); ?>" alt="" height="260px" style="border: 7px solid #fff; box-shadow: 0px 3px 10px #ffe;">
                        <div class="row mt-3" style="background-color: rgba(255, 255, 255, 0.7);">
                            <div class="col-sm-12 text-center my-2">
                                <p class="h3">Welcome, <span class="text-danger" style=""><?= $this->session->userdata('userfullname'); ?></span>
                                </p>
                                <h6 class="text-secondary font-italic">
                                    <?= quoteDisplay($userQuote); ?>
                                </h6>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
                            
            <div class="row" style="<?= statusToDisplay($dashboardItem['ramadhan_info']) ?>">
                <div class="col-md">
                    <div class="card">
                        <div class="card-body rounded py-2" style="background-color: rgba(0, 255, 40, 0.2)">
                            <?php
                                $target = new DateTime(date('Y-m-d', strtotime($ramadhanFirstdate)));
                                // $target = new DateTime(date('Y-m-d', strtotime('2025-03-01')));
                                $currDate = new DateTime(date("Y-m-d"));
                                $diff = $target->diff($currDate);
                                $gap = $diff->format('%a');

                                if ($diff->format('%R%a') < 0) {
                                    $text = $gap . ' days to Ramadhan';
                                } else if ($diff->format('%R%a') >= 0 && $diff->format('%R%a') < 29) {
                                    $text = 'Ramadhan days : ' . ($gap + 1);
                                } else {
                                    $text = 'Happy Eid Mubarak';
                                }
                            ?>
                            <div class="row">
                                <div class="col-sm-1" style="max-width: 40px;">
                                    <img class="img-fluid" src="<?= base_url('assets/responsive_filemanager/source/2025/ramadhan_ornament3.png') ?>" style="max-height: 30px;">
                                </div>
                                <div class="col-sm-5">
                                    <span class="lead" style="font-size: 20px;"><?= $text ?></span>
                                </div>
                                <div class="col-sm-6">
                                    <img class="img-fluid float-right" src="<?= base_url('assets/responsive_filemanager/source/2025/ramadhan_ornament2.png') ?>" style="max-height: 30px; width: 120px;">        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="<?= statusToDisplay($dashboardItem['elearning_alert']) ?>">
                <div class="col-md-6 col-sm-8 col-12" style="max-width: 400px;">
                    <?php if(!empty($elearningAssignment)): ?>
                        <?php foreach ($elearningAssignment as $data): ?>
                            <?php if($data['is_done'] == 0) :?>
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-info-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="h5 lead text-danger">You've assigned for Elearning</span>
                                        <span class="text-bold h6 mb-3">Due : <?= date("d F Y", strtotime($data['enddate'])); ?></span>
                                        <a href="<?= base_url().'elearning/examination'; ?>" class="text-primary">Go to elearning <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-info-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="h5 text-info">You've done Elearning</span>
                                        <span class="text-bold h6 mb-3 text-muted">Due : <?= date("d F Y", strtotime($data['enddate'])); ?></span>
                                        <a href="<?= base_url().'elearning/result'; ?>" class="text-primary">See result <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['sholattime']) ?>" id="sholatTimeContainer">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-success">
                            <h3 class="card-title">Jadwal Waktu Sholat hari ini: <?= date("d F Y") ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeImsak">
                                    <div class="card-header">
                                        Imsak
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeSubuh">
                                    <div class="card-header">
                                        Subuh
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeTerbit">
                                    <div class="card-header">
                                        Terbit
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeDhuha">
                                    <div class="card-header">
                                        Dhuha
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeZhuhur">
                                    <div class="card-header">
                                        Zhuhur
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeAshar">
                                    <div class="card-header">
                                        Ashar
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeMaghrib">
                                    <div class="card-header">
                                        Maghrib
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="card card-outline card-success text-center cardEachSholatTime" style="min-width: 100px;" id="sholatTimeIsya">
                                    <div class="card-header">
                                        Isya
                                    </div>
                                    <div class="card-body">
                                        <span class="text-bold">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['general_vote']) ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-pink">
                            <h3 class="card-title">VOTING for : <span class="text-bold"><?= ucwords(voteListNumToText($voteList)['title']) ?> <i class="fas fa-heart"></i></span></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-11">
                                    <table class="table table-sm table-borderless">
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($voteList as $row) : ?>
                                                <tr class="border-bottom">
                                                    <td style="width: 30px;";><?= $i++ ?></td>
                                                    <td class="col-sm-8">
                                                        <p class="h4 text-dark"><?= $row['vote_name'] ?></p>
                                                        <span class="text-secondary"><?= $row['vote_desc'] ?></span>
                                                    </td>
                                                    <td class="align-middle col-sm-3" style="min-width: 300px; max-width: 360px;">
                                                        Your vote : <span class="text-bold"><?= is_null($row['vote_to'])? " - " : $row['vote_to']  ?></span>
                                                        <?php if (in_array($this->session->userdata('role_access'), $allowedUser)) : ?>
                                                            <button type="button" class="ml-1 btn btn-sm btn-outline-secondary float-right buttonViewResult" data-toggle="modal" data-target="#modalResultGeneralVote" data-id="<?= $row['id'] ?>">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary float-right buttonSubmitVote" data-toggle="modal" data-target="#modalGeneralVote" data-id="<?= $row['id'] ?>">
                                                            <?= is_null($row['vote_to']) ? ' Vote ' : '<i class="fas fa-bars"></i>' ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['productivity_interval']) ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-purple">
                            <?php if (count($productivityInterval) < 1) : ?>
                                <span class="h6">Productivity by interval</span>
                            <?php else :  ?>
                                <h3 class="card-title">Produktivitas Office Hour <strong><?= date("d F Y", strtotime($productivityInterval[0]['datetime'])) ?> per interval : <?= date("H:i", strtotime($productivityInterval[0]['datetime'])) ?></strong></h3>
                            <?php endif; ?>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body row">
                            <?php if (count($productivityInterval) < 1) : ?>
                                <p class="text-center lead">No data to be displayed (not updated yet)</p>
                            <?php else : ?>
                                <table class="table-sm table table-bordered table-stripped col-sm-9">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Assignment</th>
                                            <th class="text-center">Inc. Call</th>
                                            <th class="text-center">Whatsapp</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Follow up</th>
                                            <th class="text-center">Total Prod</th>
                                            <th class="text-center">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($productivityInterval as $row) : ?>
                                            <tr class="<?= nameToColor($row['agent'], $this->session->userdata('user_id')) ?>">
                                                <td><?= $row['agent'] ?></td>
                                                <td><?= $row['assignment'] ?></td>
                                                <td class="text-center"><?= $row['icall'] ?></td>
                                                <td class="text-center"><?= $row['whatsapp'] ?></td>
                                                <td class="text-center"><?= $row['sms_email'] ?></td>
                                                <td class="text-center"><?= $row['follow_up'] ?></td>
                                                <td class="text-center"><?= $row['icall'] + $row['whatsapp'] + $row['sms_email'] + $row['follow_up'] ?></td>
                                                <td class=""><?= $row['remark'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="col-sm-2 ml-3">
                                    <p class="">
                                        <span style="font-size: 80px;"><i class="far fa-clock text text-danger"></i></span><br>
                                        <span class="lead text-danger" style="font-size: 20px;"><?= date("d-M-Y", strtotime($productivityInterval[0]['datetime'])) ?><br>Per : <?= date("H:i", strtotime($productivityInterval[0]['datetime'])) ?></span>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['lebaran_operation']) ?>">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <span class="h5">Lebaran Operation <?= date("Y") ?></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col">
                                    <?php $yrs = date("Y"); ?>
                                    <a href="<?= base_url() . 'assets/responsive_filemanager/source/' . $yrs . '/Piket_Lebaran_' . $yrs . '.xlsx' ?>" class="float-left mr-3" ><i class="fas fa-file-excel"></i> Jadwal Piket Lebaran</a>
                                    <?php if (in_array($this->session->userdata('role_access'), $allowedUser)) : ?>
                                        <a href="#" id="buttonAddLebaranOperationReport" class="float-left" data-toggle="modal" data-target="#modalUploadExcelPiketLebaran"><i class="fas fa-upload"></i> Upload Data Piket Lebaran</a>
                                    <?php endif; ?>
                                </div>
                                <div class="col">
                                    <a href="#" id="buttonAddLebaranOperationReport" class="float-right" data-toggle="modal" data-target="#modalLebaranOperationReport"><i class="fas fa-plus-circle"></i> Add Daily Achievement Report</a>
                                </div>
                            </div>
                            <?php if (count($lebaranOperationData) < 1) : ?>
                                <p class="lead text-danger mt-2">
                                    There were no data recorded on database. Please add report data.
                                </p>
                            <?php else : ?>
                                <div class="row"><div class="col">
                                <table class="table table-sm table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="2" class="align-top">#</th>
                                            <th rowspan="2" class="align-top">Date</th>
                                            <th colspan="3">Call</th>
                                            <th colspan="2">Whatsapp</th>
                                            <th rowspan="2" class="align-top">Email</th>
                                            <th rowspan="2" class="align-top">FU</th>
                                            <th colspan="3">Keluhan</th>
                                            <th rowspan="2" class="align-top">Remark</th>
                                            <th rowspan="2" class="align-top">...</th>
                                        </tr>
                                        <tr style="font-size: 8px; color: #333;">
                                            <th>Inb</th>
                                            <th>ACD</th>
                                            <th>CAR</th>
                                            <th>Res.</th>
                                            <th>Ong.</th>
                                            <th>Biasa</th>
                                            <th>Urgent</th>
                                            <th style="min-width: 200px;">Detail urgent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($lebaranOperationData as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $i++ ?></td>
                                                <td class="text-center"><?= date("d M", strtotime($row['date'])) ?></td>
                                                <td class="text-center"><?= $row['inbound'] ?></td>
                                                <td class="text-center"><?= $row['acd'] ?></td>
                                                <td class="text-center"><?= $row['car'] ?>%</td>
                                                <td class="text-center"><?= $row['wa_resolved'] ?></td>
                                                <td class="text-center"><?= $row['wa_ongoing'] ?></td>
                                                <td class="text-center"><?= $row['email_replied'] ?></td>
                                                <td class="text-center"><?= $row['followup'] ?></td>
                                                <td class="text-center"><?= $row['complaint_reguler'] ?></td>
                                                <td class="text-center"><?= $row['complaint_urgent_qty'] ?></td>
                                                <td><small><?= $row['complaint_urgent_detail'] ?></small></td>
                                                <td><?= $row['remark'] ?></td>
                                                <td>
                                                    <a href="#" class="buttonEditLebaranOperationData" data-id="<?= $row['id'] ?>" data-toggle="modal" data-target="#modalLebaranOperationReport">
                                                        <button class="btn btn-xs"><i class="fas fa-edit"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="<?= statusToDisplay($dashboardItem['birthday_info']) ?>">
                <div class="col-md-12">   
                    <?php if(count($userBirthdate) < 1) : ?>
                        <div class="card collapsed-card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title">Birhtday info</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="lead">There were no member will celebrate birthday 7 days ahead</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title">Birhtday info</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                        foreach ($userBirthdate as $us) :
                                    ?>
                                        <?php if (date("m-d", strtotime($us['birthdate'])) == date("m-d")) { ?>
                                            <div class="small-box bg-fuchsia col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>Happy Birthday</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+1 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>1 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+2 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>2 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+3 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>3 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+4 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>4 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+5 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>5 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+6 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>6 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } else if (date("m-d", strtotime($us['birthdate'])) == date("m-d", strtotime("+7 days"))) { ?>
                                            <div class="small-box bg-pink col-sm-3 mx-3">
                                                <div class="inner">
                                                    <h4>7 hari lagi</h4>
                                                    <h6><?= $us['fullname']; ?></h6>
                                                    <p><?= date("d F", strtotime($us['birthdate'])); ?></p>
                                                    <p></p>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>                                                
                                            </div>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>                                                
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['general_queue']) ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-purple">
                            <span class="card-title">Antrian <?= $queueing[0]['description'] ?></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col ml-4">
                                <p class="h5 border-bottom mb-3 text-purple" style="width: 200px;"><i class="fas fa-clipboard-list"></i> Belum Ikut Antri</p>
                                <?php $a = 1; ?>
                                <?php foreach($queueing as $row) : ?>
                                    <?php if ($row['status'] == 'blank') : ?>
                                        <p class="mr-4" style="width: 180px;">
                                            <span class="mr-1">(<?= $a++ ?>)</span>
                                            <?= $row['agent'] ?>
                                            <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('role_access') == '1' || $this->session->userdata('user_id') == $row['agent']) :?>
                                                <span class="float-right">
                                                    <button class="btn badge badge-info badge-pill btnQueue" data-agent="<?= $row['agent'] ?>">Antri</button>
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="col">
                                <p class="h5 border-bottom mb-3 text-purple" style="width: 240px;"><i class="fas fa-fast-forward"></i> Antri/On Progress MCU</p>
                                <?php $b = 1; ?>
                                <?php foreach($queueing as $row) : ?>
                                    <?php if ($row['status'] == 'queueing') : ?>
                                        <p class="mr-4" style="width: 240px;">
                                            <span class="mr-1">(<?= $b++ ?>)</span>
                                            <?= $row['agent'] ?>
                                            <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('role_access') == '1' || $this->session->userdata('user_id') == $row['agent']) :?>
                                                <span class="float-right">
                                                    <button class="btn badge badge-success badge-pill btnFinish" data-agent="<?= $row['agent'] ?>">Selesai</button>
                                                    <button class="btn badge badge-secondary badge-pill btnReset" data-agent="<?= $row['agent'] ?>">Reset</button>
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="col">
                                <p class="h5 border-bottom mb-3 text-purple" style="width: 240px;"><i class="fas fa-check-circle text-success"></i> Sudah selesai MCU</p>
                                <?php $b = 1; ?>
                                <?php foreach($queueing as $row) : ?>
                                    <?php if ($row['status'] == 'finish') : ?>
                                        <p class="mr-4" style="width: 240px;">
                                            <span class="mr-1">(<?= $b++ ?>)</span>
                                            <span class=""><?= $row['agent'] ?></span>
                                            <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('role_access') == '1' || $this->session->userdata('user_id') == $row['agent']) :?>
                                                <span class="float-right">
                                                    <button class="btn badge badge-warning badge-pill btnQueueAgain" data-agent="<?= $row['agent'] ?>">Antri lagi</button>
                                                    <button class="btn badge badge-secondary badge-pill btnReset" data-agent="<?= $row['agent'] ?>">Reset</button>
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="<?= statusToDisplay($dashboardItem['zhuhur_schedule']) ?>">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <span class="card-title">Jadwal Sholat Zhuhur</span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-sm-8">
                                <table class="table table-sm table-borderless">
                                    <?php for($x = 0; $x < count($prayScheduleTimes); $x++): ?>
                                        <tr class="">
                                            <td class="border-bottom align-middle col-sm-2"><span class="badge badge-success badge-pill py-2 px-3" style="font-size:12px"><?= $prayScheduleTimes[$x]['pray_time'] ?></span>
                                            </td>
                                            <td class="border-bottom text-center col-sm-10" style="max-width: 320px;">
                                                 <?php for($i = 0; $i < count($praySchedule); $i++): ?>
                                                    <?php if($praySchedule[$i]['pray_time'] == $prayScheduleTimes[$x]['pray_time']) : ?>
                                                        <li class="bg-light itemList" ><span class="badge badge-primary mr-1"><?= substr($praySchedule[$i]['name'], 0, 1) ?></span> <?= $praySchedule[$i]['name'] ?></li>  
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </table>
                            </div>
                            <div class="col-sm-auto ml-4">
                                <p class="lead">Catatan:</p>
                                <span class="text-dark">Monggo kalau ada yang mau gantian/tukeran
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="<?= statusToDisplay($dashboardItem['break_schedule']) ?>">
                <div class="col-md-12">
                    <?php if(count($breakSchedule) == 0) : ?>
                        <div class="card collapsed-card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title">BREAK SCHEDULE</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <em class="lead">Not available yet</em>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h3 class="card-title">BREAK SCHEDULE - <?= date("d F Y", strtotime($breakSchedule[0]['date_start'])) ?> - <?= date("d F Y", strtotime($breakSchedule[0]['date_end'])) ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2 mr-3">
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                                <tr class="">
                                                    <th colspan="2" class="border-bottom">Group I</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach($breakSchedule as $row): ?>
                                                    <?php if($row['break_group'] == 1 ): ?>
                                                        <tr>
                                                            <td class="col-sm-1"><?= $i++ . '. '; ?></td>
                                                            <td><?= $row['name']; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-2 mr-3">
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                                <tr class="">
                                                    <th colspan="2" class="border-bottom">Group II</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach($breakSchedule as $row): ?>
                                                    <?php if($row['break_group'] == 2 ): ?>
                                                        <tr>
                                                            <td class="col-sm-1"><?= $i++ . '. '; ?></td>
                                                            <td><?= $row['name']; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-2 mr-3">
                                        <table class="table table-sm table-borderless">
                                            <thead>
                                                <tr class="">
                                                    <th colspan="2" class="border-bottom">Group III</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach($breakSchedule as $row): ?>
                                                    <?php if($row['break_group'] == 3 ): ?>
                                                        <tr>
                                                            <td class="col-sm-1"><?= $i++ . '. '; ?></td>
                                                            <td><?= $row['name']; ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm mx-3" style="max-width: 350px;">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>Hari</th>
                                                    <th class="text-center">Group</th>
                                                    <th class="text-center">Jam</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($breakTime as $row) : ?>
                                                    <tr>
                                                        <td><?= $row['workday'] ?></td>
                                                        <td class="text-center"><?= $row['group'] ?></td>
                                                        <td class="text-center">
                                                            <?= date("H:i", strtotime($row['time_start'])) ?> - 
                                                            <?= date("H:i", strtotime($row['time_end'])) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row" style="<?= statusToDisplay($dashboardItem['general_info']) ?>">
                <div class="col col-flex">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Info seputar CCC</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <ol>
                                <?php foreach ($allGeneralInfo as $row) : ?>
                                    <?php if ($row['status'] != 3) : ?>
                                        <li class="">
                                            <p><?= $row['detail_info'] ?></p>
                                        </li>
                                        <hr>
                                    <?php endif; ?>                                    
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

   
<div class="modal fade" id="modalLebaranOperationReport" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('dashboard/addLebaranReport') ?>" method="POST">
                <div class="modal-header text-success">
                    <h5 class="modal-title" id="modalLebaranOperationReportLabel">Add Lebaran Operation Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="addLebaranReportId" id="addLebaranReportId" readonly>
                    <div class="form-group row">
                        <label for="addLebaranReportDate" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" name="addLebaranReportDate" id="addLebaranReportDate" value="<?= date("Y-m-d") ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportCallInbound" class="col-sm-2 col-form-label">Call</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportCallInbound" id="addLebaranReportCallInbound" placeholder="Inbound">
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportCallAcd" id="addLebaranReportCallAcd" placeholder="ACD">
                        </div>
                        <div class="col-sm-2">
                            <input type="number" step="0.1" class="form-control" name="addLebaranReportCallCar" id="addLebaranReportCallCar" placeholder="% CAR">
                        </div>
                        <div class="col-sm-">%</div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportWhatsappResolved" class="col-sm-2 col-form-label">Whatsapp</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportWhatsappResolved" id="addLebaranReportWhatsappResolved" placeholder="resolved">
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportWhatsappOngoing" id="addLebaranReportWhatsappOngoing" placeholder="ongoing">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportEmailReplied" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportEmailReplied" id="addLebaranReportEmailReplied" placeholder="Terbalas">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportFollowup" class="col-sm-2 col-form-label">Follow Up</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="addLebaranReportFollowup" id="addLebaranReportFollowup" placeholder="Follow Up berhasil">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportComplaintReguler" class="col-sm-2 col-form-label">Keluhan</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportComplaintReguler" id="addLebaranReportComplaintReguler" placeholder="Biasa">
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" name="addLebaranReportComplaintUrgentQty" id="addLebaranReportComplaintUrgentQty" placeholder="Urgent">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportComplaintUrgentDetail" class="col-sm-2 col-form-label">Keluhan urgent</label>
                        <div class="col-sm-10">
                            <div class="text-danger font-italic" style="font-size: 10px;">Disarankan pakai numbering.<br>Format penulisan: Nama konsumen - notif - cabang - model - detail keluhan - tindakan yang sudah dilakukan.</div>
                            <textarea class="form-control" name="addLebaranReportComplaintUrgentDetail" id="addLebaranReportComplaintUrgentDetail"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addLebaranReportComplaintRemark" class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="addLebaranReportComplaintRemark" id="addLebaranReportComplaintRemark"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <button type="submit" id="buttonAddNewLebaranOperationData" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInfoIsiFeedbackNewskape" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalInfoIsiFeedbackNewskapeLabel" aria-hidden="false">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body bg-dark">
        <p class="text-center h3 mt-3 p-2">
          Tolong masukan, komentar, ataupun feedback lain buat <span class="text-warning">NEW SKAPE</span>
          <br>
        </p>
        <p class="text-center h5 text-light p-2">
          Masukan-masukan sangat diperlukan biar <span class="text-warning">NEW SKAPE</span> bisa lebih baik, minimal sama dengan SKAPE lama
        </p>        
        <p class="text-center mt-5">
          <button class="btn btn-outline-light" data-dismiss="modal" aria-label="Close">OK, mengerti. Isi nanti</button>
          <a href="<?= base_url('survey/skapefeedback') ?>"><button class="btn btn-outline-warning">Isi feedback sekarang</button></a>
        </p>
      </div>             
    </div>
  </div>
</div>

<div class="modal fade" id="modalGeneralVote" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalGeneralVoteLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="width: 460px;">
            <div class="modal-header">
                <span class="h5">Voting untuk : <span class="text-bold text-pink" id="modalGeneralVoteTitle"></span></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>    
            <form class="" action="<?= base_url('dashboard/submitVote') ?>" method="POST">
                <div class="modal-body" >
                    <p class="h6 text-primary">
                        Pilih nama/item yang akan di-vote:
                    </p>
                    <input type="hidden" id="submitVoteId" name="submitVoteId" >
                    <div id="dataListContainer" class="pl-4" style="min-height: 240px; max-height: 380px; display: blok; overflow-y: auto; overflow-x: hidden;">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col text-center">
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="submit" class="btn btn-outline-primary">Submit</button>
                    </div>
                </div>             
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalResultGeneralVote" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalGeneralVoteLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-static modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="h5">Voting untuk : <span class="text-bold text-pink" id="modalResultVoteTitle"></span></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col">
                        <div class="card collapsed-card">
                            <div class="card-header bg-pink">
                                <span class="card-title">Summary hasil voting</span>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="containerVoteSummary"></div>
                            </div>    
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <div class="card card collapsed-card">
                            <div class="card-header bg-secondary">
                                <span class="card-title">Detail hasil voting</span>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="containerVoteDetailResult"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <small class="col text-muted">
                        Disclaimer:<br>
                        <em>Hasil vote murni hasil pemungutan suara</em>
                    </small>
                </div>
            </div>     
        </div>
    </div>
</div>