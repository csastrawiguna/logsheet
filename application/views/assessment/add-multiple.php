<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- Header baru -->
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Add Multiple Staff Others KPI Item</h3>
                </div>
                <div class="card-body">                       
                    <div class="row">
                        <div class="col">
                            <form method="post" action="<?= base_url('assessment/submitMultipleKpi') ?>">
                                <table class="table table-sm table-borderless">
                                    <thead>
                                        <tr class="text-center text-sm">
                                            <th>#</th>
                                            <th>Period</th>
                                            <th>Agent</th>
                                            <th>SKAPE draft</th>
                                            <th>SKAPE solution</th>
                                            <th>Knowledge sharing</th>
                                            <th>Part callback (%)</th>
                                            <th>Fwd complaint (%)</th>
                                            <th>Compl. completion(%)</th>
                                            <th>Complaint report</th>
                                            <th>Email reply (%)</th>
                                            <th>Promo inq (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="hidden" name="inputMultipleNumrows" value="<?= $numRows; ?>"></td>
                                        </tr>
                                        <?php for ($i = 0; $i < $numRows; $i++) : ?>
                                            <tr>
                                                <td><?= $i + 1; ?></td>
                                                <td><input type="date" id="inputMultiplePeriod<?= $i ?>" name="inputMultiplePeriod<?= $i ?>" class="form-control" value="<?= $period; ?>"></td>
                                                <td>
                                                    <select class="custom-select" id="inputMultipleAgent<?= $i ?>" name="inputMultipleAgent<?= $i ?>">
                                                        <option selected></option>
                                                        <?php foreach($allAgents as $data): ?>
                                                            <option><?= $data['user_id'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" id="inputMultipleSkapeDraft<?= $i ?>" name="inputMultipleSkapeDraft<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" id="inputMultipleSkapeSolution<?= $i ?>" name="inputMultipleSkapeSolution<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" id="inputMultipleKnowledgeSharing<?= $i ?>" name="inputMultipleKnowledgeSharing<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.1" id="inputMultiplePartCallback<?= $i ?>" name="inputMultiplePartCallback<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.1" id="inputMultipleComplaintForward<?= $i ?>" name="inputMultipleComplaintForward<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.1" id="inputMultipleComplaintCompletion<?= $i ?>" name="inputMultipleComplaintCompletion<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" id="inputMultipleComplaintReport<?= $i ?>" name="inputMultipleComplaintReport<?= $i ?>" class="form-control" value="0">
                                                </td>                                                
                                                <td>
                                                    <input type="number" id="inputMultipleEmailReply<?= $i ?>" name="inputMultipleEmailReply<?= $i ?>" class="form-control" value="0">
                                                </td>
                                                <td>
                                                    <input type="number" id="inputMultiplePromoInquiry<?= $i ?>" name="inputMultiplePromoInquiry<?= $i ?>" class="form-control" value="0">
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                                <button class="btn btn-outline-primary mt-3" id="buttonSubmitAddMultipleKpi">Submit</button>
                            </form>                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
