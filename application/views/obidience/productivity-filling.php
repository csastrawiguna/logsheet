<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="container-fluid">
                <?php 
                ?>

                <div class="card card-primary">
                    <div class="card-header">
                        <span>Filling Overtime Productivity : <span class="text-bold"><?= date("d M Y", strtotime($this->input->post('productivityFillingStartdate'))) ?> - <?= date("d M Y", strtotime($this->input->post('productivityFillingEnddate'))) ?></span></span>
                        <div class="card-tools">
                            <a href="#" class="mr-3" id="buttonProductivityFillingToggleDateFilter"><i class="fas fa-calendar"></i> Date filter</a>
                            <a href="<?= base_url('obidience/exchange') ?>" class="mr-3" ><i class="fas fa-arrow-circle-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="rowProductivityFillingFilter" class="row mb-3 bg-light pt-3 pb-2 rounded" style="display: none;">
                            <form method="post" action="" class="ml-3">
                                <div class="form-group row">
                                    <div class="row">
                                        <label for="productivityFillingStartdate" class="col-sm-2 col-form-label">Period</label>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" id="productivityFillingStartdate" name="productivityFillingStartdate" value="<?= $this->input->post('productivityFillingStartdate'); ?>">
                                        </div>
                                        <label for="productivityFillingEnddate" class="col-sm-auto col-form-label">to</label>
                                        <div class="col-sm-4">
                                            <input type="date" class="form-control" id="productivityFillingEnddate" name="productivityFillingEnddate" value="<?= $this->input->post('productivityFillingEnddate'); ?>">
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="submit" class="btn btn-outline-primary" name="productivityFillingFilterSubmit" id="productivityFillingFilterSubmit">Go</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <form action="<?= base_url('obidience/updateOvertimeProductivity') ?>" method="POST">
                            <input type="hidden" name="prodRows" value="<?= count($overtimeData); ?>">
                            <input type="hidden" name="prodStartPeriod" value="<?= $this->input->post('productivityFillingStartdate'); ?>">
                            <input type="hidden" name="prodEndPeriod" value="<?= $this->input->post('productivityFillingEnddate'); ?> ?>">
                            <table class="table table-bordered table-striped" id="tableProductivityFilling">
                                <thead>
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle">#</th>
                                        <th rowspan="2" class="align-middle">Date</th>
                                        <th rowspan="2" class="align-middle">Actual OT</th>
                                        <th colspan="3">Overtime Hours</th>
                                        <th colspan="6">Productivity</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="py-1 px-0">Start</th>
                                        <th class="py-1 px-0">End</th>
                                        <th class="py-1 px-0">Hours</th>
                                        <th class="py-1 px-0">Call</th>
                                        <th class="py-1 px-0">WA</th>
                                        <th class="py-1 px-0">FU</th>
                                        <th class="py-1 px-0">Others</th>
                                        <th class="py-1 px-0">Total</th>
                                        <th class="py-1 px-0">Remark if not achieved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach($overtimeData as $row) : ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <?= $i + 1 ; ?>
                                                <input type="hidden" id="id-<?= $row['id'] ?>" class="id" name="id-<?= $i ?>" value="<?= $row['id'] ?>" readonly>
                                            </td>
                                            <td class="text-center align-middle px-1 mx-1"><?= date("d M 'y", strtotime($row['date'])); ?></td>
                                            <td class="align-middle text-bold">
                                                <?= $row['actual_overtime']; ?>
                                            </td>
                                            <td class="text-center align-middle"><?= date("H:i", strtotime($row['actual_start'])); ?></td>
                                            <td class="text-center align-middle"><?= date("H:i", strtotime($row['actual_end'])); ?></td>
                                            <td class="text-center align-middle"><?= number_format($row['actual_duration'], 2); ?></td>
                                            <td class="text-center p-1">
                                                <input type="" id="prodCall-<?= $row['id'] ?>" class="form-control text-center prodCallCol" name="prodCall-<?= $i ?>" style="max-width: 60px;" value="<?= $row['prod_call'] ?>">
                                            </td>
                                            <td class="text-center p-1">
                                                <input type="" id="prodWhatsapp-<?= $row['id'] ?>" class="form-control text-center prodWhatsappCol" name="prodWhatsapp-<?= $i ?>" style="max-width: 60px;" value="<?= $row['prod_whatsapp'] ?>">
                                            </td>
                                            <td class="text-center p-1">
                                                <input type="" id="prodFollowup-<?= $row['id'] ?>" class="form-control text-center prodFollowupCol" name="prodFollowup-<?= $i ?>" style="max-width: 60px;" value="<?= $row['prod_followup'] ?>">
                                            </td>
                                            <td class="text-center p-1">
                                                <input type="" id="prodOthers-<?= $row['id'] ?>" class="form-control text-center prodOthersCol" name="prodOthers-<?= $i ?>" style="max-width: 60px;" value="<?= $row['prod_others'] ?>">
                                            </td>
                                            <td class="text-center p-1">
                                                <input type="" class="form-control text-center text-bold prodTotalCol" name="" style="max-width: 60px;" value="<?= $row['prod_call'] + $row['prod_whatsapp'] + $row['prod_followup'] + $row['prod_others'] ?>" readonly>
                                            </td>
                                            <td class="text-center p-1">
                                                <input type="text" id="prodRemark-<?= $row['id'] ?>" class="form-control prodRemarkCol" name="prodRemark-<?= $i ?>" value="<?= $row['prod_remark'] ?>">
                                            </td>
                                        </tr>
                                        <?php $i += 1; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if(count($overtimeData) >= 1) : ?>
                                <button type="submit" class="btn btn-primary mt-3" id="buttonProductivityFillingSubmit">
                                    <i class="fas fa-check"></i> Submit Data
                                </button>
                            <?php endif ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="editDataProductivityDaily" tabindex="-1" role="dialog" aria-labelledby="editDataProductivityDailyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="width: 460px;">
            <div class="modal-header">
                <h5 class="modal-title" id="editDataProductivityDailyLabel">Edit Productivity Daily Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-x: hidden;">
                <form action="" method="post">
                    <input type="hidden" class="form-control" id="editProductivityDailyId" name="editProductivityDailyId" readonly>
                    <div class="form-group row">
                        <label for="editProductivityDailyDate" class="col-sm-4 col-form-label text-right">Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="editProductivityDailyDate" name="editProductivityDailyDate" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editProductivityDailySubmit" name="editProductivityDailySubmit">Update</button>          
                </div>
            </form>
        </div>
    </div>
</div>