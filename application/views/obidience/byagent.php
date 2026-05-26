<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-0 pt-3">
        <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                if(!$this->input->post()) {
                    $startPeriod = date("Y-m-01", strtotime("-1 months"));
                    $endPeriod = date("Y-m-d");
                     $agent = $this->session->userdata('user_id');
                } else {
                    $startPeriod = $this->input->post('obidienceByAgentDateStart');
                    $endPeriod = $this->input->post('obidienceByAgentDateEnd');
                    $agent = $this->input->post('obidienceByAgentSelectAgent');
                }

                function markSwap($rem) {
                    if (strtolower($rem) == 'swap') {
                        return '<span class="badge badge-pill badge-warning">Tukar</span>';
                    } else {
                        return '<span class="badge badge-pill badge-danger">Diganti</span>';
                    }
                }
            ?>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-primary">
                        Incompliance by Agent
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm">                               
                                <form action="" class="form-row" method="post" style="width: 820px;">
                                    <label for="obidienceByAgentSelectAgent" class="col-sm-1">Agent</label>
                                    <div class="col-sm-2">
                                        <select id="obidienceByAgentSelectAgent" name="obidienceByAgentSelectAgent" class="custom-select">
                                            <option selected><?= $agent ?></option>
                                            <?php if($this->session->userdata('role_access') == 9 || $this->session->userdata('role_access') == 1 || $this->session->userdata('role_access') == 5 || $this->session->userdata('role_access') == 7): ?>
                                                <?php foreach ($allAgents as $ag): ?>
                                                    <option value="<?= $ag['user_id']; ?>"><?= $ag['user_id']; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option><?= $this->session->userdata('user_id'); ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <label for="obidienceByAgentDateStart" class="col-sm-1 ml-5">Period</label>
                                    <div class="col-sm-2">
                                        <input type="date" id="obidienceByAgentDateStart" name="obidienceByAgentDateStart" class="form-control" value="<?= $startPeriod?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="date" id="obidienceByAgentDateEnd" name="obidienceByAgentDateEnd" class="form-control" value="<?= $endPeriod?>">
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="row ml-1">
                                            <button type="submit" class="btn btn-outline-primary" id="buttonSelectObidienceByAgent" name="buttonSelectObidienceByAgent">Go</button>      
                                        </div>
                                    </div>
                                </form>                  
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <table class="table table-hover table-sm" id="tableObidienceByAgent">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">#</th>
                                            <th class="align-middle">Date</th>
                                            <th class="align-middle">Schedule</th>
                                            <th class="align-middle">Status</th>
                                            <th class="align-middle">Actual OT</th>
                                            <th class="align-middle">Reason</th>
                                            <th class="align-middle">Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($obidienceByAgent as $data): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= date("d-M-y", strtotime($data['date'])); ?></td>
                                                <td><?= $data['agent_scheduled']; ?></td>
                                                <td class=""><?= markSwap($data['replace_mark']); ?></td>
                                                <td><?= $data['replaced_by']; ?></td>
                                                <td><?= $data['reason']; ?></td>
                                                <td><?= $data['remark']; ?></td>                                                
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
    </section>
</div>
