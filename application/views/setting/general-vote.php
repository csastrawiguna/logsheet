<div class="content-wrapper">
    <section class="content pt-3 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
            function statusToCheckbox($stts) {
                if ($stts == 1) {
                    return 'checked';
                } else {
                    return;
                }
            }
        ?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary">
                    General Vote
                    <div class="card-tools">
                        <div class="card-tools">                                
                            <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#modalAddGeneralVote" id="buttonAddNewGeneralVote"><i class="fas fa-plus-circle" ></i> Add Vote</a>
                        </div>
                    </div>  
                </div>
                <div class="card-body">                                                        
                    <table class="table tableBasicDataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vote name</th>
                                <th>Description</th>
                                <th>Data List</th>
                                <th>Active?</th>
                                <th>...</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($allVotes as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $row['vote_name'] ?></td>
                                    <td><?= $row['vote_desc'] ?></td>
                                    <td><?= $row['data_list'] ?></td>
                                    <td>
                                        <div class="pretty p-switch p-fill">
                                            <input type="checkbox" class="buttonToggleVoteStatus" <?= statusToCheckbox($row['is_active']) ?> value="<?= $row['id']; ?>">
                                            <div class="state p-info">
                                                <label></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">                              
                                            <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                            <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 300px;">
                                                <table  class="table table-sm table-borderless table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td>Saved by</td>
                                                            <td class="">: <?= $row['saved_by']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Saved at</td>
                                                            <td class="">: <?= $row['saved_at']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Last modified</td>
                                                            <td class="">: <?= $row['updated_by']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Datetime</td>
                                                            <td class="">: <?= $row['updated_at']; ?></td>
                                                        </tr>
                                                        <tr class="border-top">
                                                            <td colspan="2">
                                                                <a href="" class="text-primary buttonGeneralVoteEdit" title="Edit data" data-id="<?= $row['id']; ?>" data-toggle="modal" data-target="#modalAddGeneralVote">
                                                                    <i class="fas fa-pen"></i></span> &nbspEdit data
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr class="border-top">
                                                            <td colspan="2">
                                                                <a href="#" class="text-danger buttonGeneralVoteDelete" title="Delete data" style="cursor: pointer; text-decoration: none;">
                                                                    <i class="fas fa-times"></i> &nbspDelete data
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
<div class="modal fade" id="modalAddGeneralVote" tabindex="-1" role="dialog" aria-labelledby="modalAddGeneralVoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalAddGeneralVoteLabel">Add New General Vote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">                              
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="addGneralVoteId" name="addGneralVoteId" value="">
                        <label for="addGneralVoteName" class="form-label">Vote Name</label>
                        <div class="">
                            <input type="" class="form-control" id="addGneralVoteName" name="addGneralVoteName" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addGneralVoteDesc" class="form-label">Description</label>
                        <div class="">
                            <textarea class="form-control" id="addGneralVoteDesc" name="addGneralVoteDesc" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addGneralVoteDatalist" class="form-label">Data List <span class="font-weight-normal">(separated with comma. ig: Car, Train, Plane)</span></label>
                        <div class="">
                            <textarea class="form-control" id="addGneralVoteDatalist" name="addGneralVoteDatalist" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="addGneralVoteDateStart" class="form-label">Start From</label>
                                <div class="">
                                    <input type="date" class="form-control" id="addGneralVoteDateStart" name="addGneralVoteDateStart" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="addGneralVoteDateEnd" class="form-label">Until date</label>
                                <div class="">
                                    <input type="date" class="form-control" id="addGneralVoteDateEnd" name="addGneralVoteDateEnd" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-outline-secondary px-5" >Reset</button>
                    <button type="submit" class="btn btn-primary px-5" name="submitKpiNewTargetAdd" id="submitKpiNewTargetAdd">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>