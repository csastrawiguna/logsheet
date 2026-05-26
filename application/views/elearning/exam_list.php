<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">    
    <section class="content pt-3 px-0">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <?php                 
                function scoreToString($score) {
                    if($score == '0') {
                        return '-';
                    } else {
                        return $score;
                    }
                }

                function dateToString($date) {
                    if($date == '0000-00-00 00:00:00' || $date == '' || $date == NULL) {
                        return '-';
                    } else {
                        return date("d-M-Y H:i", strtotime($date));
                    }
                }

                function materialToString($material) {
                    if($material == '-' || $material == '') {
                        return '#';
                    } else {
                        return base_url('material/') . $material;
                    }
                }

                function activetostyle($active) {
                    if ($active == 1) {
                        return 'text-primary';
                    } else {
                        return 'text-dark';
                    }
                }
            ?>        
            <!-- Main row -->
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Examination list
                        </div>                    
                        <div class="card-body">
                            <?php if (count($allElearningAssigned) == 0) { ?>
                                <div class="card mx-2 my-2" style="width: 100%;">
                                    <div class="card-body bg-warning">
                                        <h3 class="h4 text-center"><span class="lnr lnr-warning"></span> LIST IS EMPTY</h3>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                    </ul>
                                    <div class="card-body text-center h5">
                                        <p>Theres was no Elearning assigned you. Please contact your administrator</p>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <table id="tableExamList" class="table table-sm">
                                <!-- <table id="table1" class="table table-bordered table-striped"> -->
                                    <thead>
                                        <tr class="">
                                            <th>#</th>
                                            <th>Period</th>
                                            <th>Pretest score</th>
                                            <th>Pretest date</th>
                                            <th>Post test score</th>
                                            <th>Post test date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($allElearningAssigned as $row) : ?>
                                            <tr class="<?= activetostyle($row['is_active']) ?>">
                                                <td><?= $i++; ?></td>
                                                <td><?= date('M Y', strtotime($row['period'])); ?></td>
                                                <td><?= scoreToString($row['pretest_score']) ?></td>
                                                <td><?= dateToString($row['pretest_date']) ?></td>
                                                <td><?= scoreToString($row['posttest_score']) ?></td>
                                                <td><?= dateToString($row['posttest_date']) ?></td>
                                                <td>
                                                    <?php if ($row['posttest_done'] == 1 || $row['is_active'] == 0 ) : ?>
                                                        <a href="<?= materialToString($row['material']) ?>"><button class="btn btn-sm btn-outline-primary">Material</i></button></a>
                                                        <a href="<?= base_url(); ?>elearning/result/<?= $row['id']; ?>" class="text-secondary"><button class="btn btn-sm btn-outline-primary">Detail</button></a>
                                                    <?php else : ?>
                                                        <?php if ($row['pretest'] == 1) : ?>
                                                            <?php if ($row['pretest_done'] == 0) : ?>
                                                                <a href="" class="text-primary gotoPretest" data-elearning_id="<?= $row['id']; ?>" data-passing_score="<?= $row['passing_score']; ?>" data-test_duration="<?= $row['test_duration']; ?>">
                                                                    <button class="btn btn-sm btn-danger">Pretest <i class="fas fa-arrow-right"></i></button>
                                                                </a>
                                                            <?php else : ?>
                                                                <a href="<?= materialToString($row['material']) ?>"><button class="btn btn-sm btn-success">Material</button></a>
                                                                <a href="#" class="text-primary gotoPosttest" data-elearning_id="<?= $row['id']; ?>" data-passing_score="<?= $row['passing_score']; ?>" data-test_duration="<?= $row['test_duration']; ?>">
                                                                    <button class="btn btn-sm btn-danger">Post Test <i class="far fa-arrow-alt-circle-right"></i></button>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <a href="<?= materialToString($row['material']) ?>"><button class="btn btn-sm btn-success">Material</button></a>
                                                            <a href="#" class="text-primary gotoPosttest" data-elearning_id="<?= $row['id']; ?>" data-passing_score="<?= $row['passing_score']; ?>" data-test_duration="<?= $row['test_duration']; ?>">
                                                                <button class="btn btn-sm btn-danger">Post Test <i class="far fa-arrow-alt-circle-right"></i></button>
                                                            </a>
                                                        <?php endif ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>  
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>