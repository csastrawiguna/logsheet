<div class="content-wrapper">
    <section class="content pt-2 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                function idToLink($id) {
                    $baselink = 'https://cs-ms.sharp-indonesia.com/callcenter/SkapeSolution.aspx?Code=';
                    $link = '';
                    if (strlen(trim($id)) <= 6) {
                        $link = $baselink . trim($id);
                    } else {
                        $link = trim($id);
                    }
                    return $link;
                }

                function statusToState($stts) {
                    if ($stts == 0) {
                        return '<i class="fas fa-hourglass-half text-danger"></i>';
                    } else {
                        return '<i class="fas fa-check-circle text-success"></i>';
                    }
                }

                function remarkToState($remark, $by, $at) {
                    if (strlen($remark) == 0 || is_null($remark)) {
                        return '';
                    } else {
                        return '<br><span class="badge badge-secondary font-weight-normal">' . $by. '</span><br><span class="text-success">' . $remark . '</span>';
                    }
                }

                $allowedAccess = [1, 2, 4, 5, 6, 9];
            ?>
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="h6">All Feedback for SKAPE Submitted on : <?= date("d M Y", strtotime($startPeriod)) ?> to <?= date("d M Y", strtotime($endPeriod)) ?></span>
                    <div class="card-tools">
                        <a href="#" class="mr-3 text-white" data-toggle="modal" data-target="#modalSkapeFeedback"><i class="fas fa-plus-circle"></i> Add Feedback</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover tableBasicDataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>By</th>
                                <th>Skape Title/Link</th>
                                <th>Feedback</th>
                                <th>Solved?</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($allSkapeFeedback as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= date("d M y", strtotime($row['saved_at'])) ?></td>
                                    <td><?= ucwords($row['saved_by']) ?></td>
                                    <td><?= $row['solution_title'] ?><br><a href="<?= idToLink($row['solution_id']) ?>" target="_blank">Solution ID: <?= $row['solution_id'] ?></a></td>
                                    <td><?= $row['feedback'] ?></td>
                                    <td><?= statusToState($row['status']) ?> <?= remarkToState($row['remark'], $row['updated_by'], $row['updated_at']) ?></td>
                                    <td style="width:16px;">
                                        <div class="btn-group">                              
                                            <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 270px;">
                                                <p>Updated by : <span class="text-bold"><?= $row['updated_by'] ?></span></p>
                                                <p>Updated at : <span class="text-bold"><?= date("d-M-Y H:i", strtotime($row['updated_at'])) ?></span></p>
                                                <?php if(in_array($this->session->userdata('role_access'), $allowedAccess)) : ?>
                                                    <p>
                                                    <a href="#" class="buttonElearningSkapefeedbackEdit" title="Edit data" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#modalSkapeFeedbackResponse"><i class="fas fa-pen"></i> Give response
                                                    </a>
                                                    </p>
                                                    <p>
                                                    <a class="text-danger buttonElearningSkapefeedbackDelete" href="<?= base_url()?>elearning/deletefeedback/<?=$row['id']?>" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                                        <i class="fas fa-times"></i> &nbsp;Delete data
                                                    </a></p>
                                                <?php endif; ?>
                                                    
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
    </section>
</div>

<div class="modal fade" id="modalSkapeFeedback" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSkapeFeedbackLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSkapeFeedbackLabel">Feedback for SKAPE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url('elearning/skapefeedback') ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="skapefeedbackCategory" class="form-control-label">Category</label>
                        <select class="custom-select" id="skapefeedbackCategory" name="skapefeedbackCategory">
                            <option value="">- pilih -</option>
                            <option value="Air Conditioner">Air Conditioner</option>
                            <option value="Air Purifier & Air Cooler">Air Purifier & Air Cooler</option>
                            <option value="Audio">Audio</option>
                            <option value="LCD TV">LCD TV</option>
                            <option value="Notebook">Notebook</option>
                            <option value="Refrigerator">Refrigerator</option>
                            <option value="SHA">SHA</option>
                            <option value="Washing Machine">Washing Machine</option>
                            <option value="Others">Others</option>
                            <option value="General">General</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="skapefeedbackTitle" class="form-control-label">Solution title</label>
                        <textarea class="form-control" rows="2" id="skapefeedbackTitle" name="skapefeedbackTitle" placeholder="Judul solusi di SKAPE"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="skapefeedbackLink" class="form-control-label">Solution ID/link</label>
                        <input class="form-control" id="skapefeedbackLink" name="skapefeedbackLink" placeholder="Masukkan id solusi atau link solusi">
                    </div>
                    <div class="form-group">
                        <label for="skapefeedbackComment" class="form-control-label">Feedback</label>
                        <textarea class="form-control" rows="3" id="skapefeedbackComment" name="skapefeedbackComment" placeholder="Masukkan detail komentar atau feedback untuk SKAPE"></textarea>
                    </div>
                    <div class="row mt-3">                
                        <div class="col-sm">
                            <button type="submit" class="btn btn-primary float-right" name="skapefeedbackSubmit" id="skapefeedbackSubmit">Submit Feedback</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSkapeFeedbackResponse" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSkapeFeedbackResponseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSkapeFeedbackLabel">Response for SKAPE Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= base_url('elearning/responsefeedback') ?>">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="feedbackResponseId" name="feedbackResponseId" value="" readonly>
                    <div class="form-group row">
                        <label for="feedbackResponseCategory" class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-4">
                            <input type="" class="form-control" id="feedbackResponseCategory" name="feedbackResponseCategory" value="" readonly>
                        </div>
                        <label for="feedbackResponseLink" class="col-sm-2 col-form-label text-right">Id</label>
                        <div class="col-sm-4">
                            <input type="" class="form-control" id="feedbackResponseLink" name="feedbackResponseLink" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="feedbackResponseTitle" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="feedbackResponseTitle" name="feedbackResponseTitle" readonly></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="feedbackResponseComment" class="col-sm-2 col-form-label">Feedback</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="feedbackResponseComment" name="feedbackResponseComment" readonly></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="feedbackResponseStatus" class="col-sm-2 col-form-label">Solved?</label>
                        <div class="col-sm-4">
                            <div class="pretty p-svg p-curve">
                                <input type="hidden" id="feedbackResponseStatusFake" name="feedbackResponseStatus" value="0">
                                <input type="checkbox" class="form-control" id="feedbackResponseStatus" name="feedbackResponseStatus" value="1">
                                <div class="state p-success">
                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                    </svg>
                                    <label style="color: rgba(0, 0, 0, 0);"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="feedbackResponseRemark" class="col-sm-2 col-form-label">Response</label>
                        <div class="col-sm-10">
                            <input type="" class="form-control" id="feedbackResponseRemark" name="feedbackResponseRemark" value="">
                        </div>
                    </div>

                    <div class="row mt-3">                
                        <div class="col-sm">
                            <button type="submit" class="btn btn-primary float-right" name="skapefeedbackSubmit" id="skapefeedbackSubmit">Submit Response</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>