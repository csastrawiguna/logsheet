<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content pt-3 px-1">
        <div class="container-fluid">            
            <!-- /.row -->
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                $allowed = ['1', '5', '9'];
            ?>
            <div class="row">
                <div class="col" id="questionaireAssignList">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form id="formQuestionaireAssignmentSelectElearningCategory" method="POST">
                                <div class="form-row align-items-center col mb-3">
                                    <div class="col-sm-8 float-left">
                                        <label class="col-sm-3" for="questionaireAssignmentSelectElearningCategory">Period/Category</label>
                                        <select class="custom-select col-sm-6" id="questionaireAssignmentSelectElearningCategory" name="questionaireAssignmentSelectElearningCategory">
                                            <option value="<?= $elearningId ?>" selected><?= date("M Y", strtotime($elearningPeriod)) . ' - ' . $elearningName ?></option>
                                            <?php foreach ($allElearningCategory as $ael) : ?>
                                                <option value="<?= $ael['id']; ?>"><?= date("M Y", strtotime($ael['period'])) . ' - ' . $ael['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary" id="buttonQuestionaireAssignmentSelectElearningCategory" name="buttonQuestionaireAssignmentSelectElearningCategory">Go</button>
                                        <?php if(in_array($this->session->userdata('role_access'), $allowed)) : ?>
                                            <button type="button" class="btn btn-outline-success" id="buttonQuestionsToExcel">
                                                <i class="fas fa-file-excel"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-2 float-right">
                                        <button type="button" class="btn btn-outline-primary float-right" id="buttonElearningAssignmentUserList" data-toggle="modal" data-target="#assignQuestionaire"><i class="fas fa-plus"></i> Add questioner</button>
                                    </div>
                                </div>
                            </form>                        

                            <?php if(empty($allQuestionaireAssigned)) : ?>
                                <table class="table table-hover">
                                    <tbody>                                
                                        <tr>
                                            <td colspan="7" class="alert alert-warning text-center h5">Currently there was no questioner assigned to this elearning</td>
                                        </tr>                                    
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <table class="table table-hover table-sm" id="tableElearningAssignedQuestionaire">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Questioner</th>
                                            <th class="text-center">...</th>
                                            <th>
                                                <div class="pretty p-default">
                                                    <input type="checkbox" id="buttonSelectAllUnassignQuestionaire" value="">
                                                    <div class="state p-warning">
                                                        <label></label>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($allQuestionaireAssigned as $row): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $row['questionaire_category'] ?></td>
                                                <td><?= $row['questionaire'] ?></td>
                                                <td class="text-center">
                                                    <button class="btn buttonUnassignQuestionaire" data-assignid="<?= $row['id'] ?>">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="pretty p-default">
                                                        <input type="checkbox" class="buttonMarkUnassignQuestionaire" value="<?= $row['id'] ?>">
                                                        <div class="state p-warning">
                                                            <label></label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>                                
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-danger my-2" id="buttonUnassignSelectedSelected" style="display: none;">Delete Selected Rows</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </section>
<!-- /.content -->
</div>

<div class="modal fade" id="assignQuestionaire" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignQuestionaireLabel">Assign questioner for Elearning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm " id="tableElearningUnassignedQuestionaire">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Period</th>
                            <th>Questioner</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allQuestionaireUnassigned as $data) : ?>
                            <tr>
                                <td><?= $data['category'] ?></td>
                                <td><?= date("M-y", strtotime($data['period'])) ?></td>
                                <td><?= $data['question'] ?></td>
                                <td class="text-center">
                                    <div class="pretty p-switch p-fill">
                                        <input type="checkbox" name="userAssigned" class="assignUnassignedQuestionaire" data-questionaireid="<?= $data['qid'] ?>">
                                        <div class="state p-primary">
                                        <label></label>
                                        </div>
                                    </div> 
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" id="submitAssignQuestionaire">Save</button>
            </div>
        </div>
    </div>
</div>