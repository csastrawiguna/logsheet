<div class="content-wrapper">
    <section class="content">
        <?php 
            function selfBold($name, $loginid) {
                if ($name == $loginid) {
                    return 'text-primary';
                }
            }

            function changeorno($scheduled, $actual) {
                if ($scheduled == $actual) {
                    return $scheduled;
                } else {
                    return '<span class="text-danger">' . $scheduled . '/' . $actual . '</span>';
                }
            }

            $loginid = $this->session->userdata('user_id');
        ?>
        
        <div class="container-fluid px-0 pt-2">        
            <div class="card">
                <div class="card-header bg-primary">
                    Overtime Schedule : <?= date("d M Y", strtotime($this->uri->segment(3))) ?> - <?= date("d M Y", strtotime($this->uri->segment(4))) ?>
                    <div class="card-tools">
                        <a href="<?= base_url('obidience/viewonpdf/') . $this->uri->segment(3) . '/' . $this->uri->segment(4) ?>" class="text-white mr-3"><i class="fas fa-file-pdf"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <?php foreach ($dates as $row) : ?>
                        <div class="row border-bottom py-2">
                            <div class="col-sm-1 text-bold" style="min-width: 100px;">
                                <?= date("d M Y", strtotime($row['date'])) ?>
                            </div>
                            <div class="col-sm-10">
                                <?php foreach ($schedules as $col): ?>
                                    <?php if ($row['date'] == $col['date']) : ?>
                                        <button class="btn btn-light border mb-1">
                                            <span class=" <?= selfBold($col['agent_scheduled'], $loginid) ?>"><?= changeorno($col['agent_scheduled'], $col['actual_overtime']) ?></span>
                                            <br>
                                            <small style="font-size: 8px;"><?= date("H:i", strtotime($col['time_start'])) ?> - <?= date("H:i", strtotime($col['time_end'])) ?></small>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>
