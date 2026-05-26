<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <?php 
                
            ?>
            <div class="card">
                <form method="POST" action="">
                    <div class="card-header bg-primary">
                        Working Calendar
                        <div class="card-tools">
                            <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#addNewWorkingCalendarMultiple"><i class="fas fa-layer-group"></i> Add multiple</a>
                            <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#addNewWorkingCalendar" id="buttonAddSingleWorkingmonth"><i class="fas fa-plus-circle"></i> Add single data</a>
                        </div>
                    </div>
                    <div class="card-body row">
                        <table id="settingWorkingCalendarTable" class="table table-sm col-sm-8" style="min-width: 480px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Month - Year</th>
                                    <th class="text-center">Working days</th>
                                    <th class="text-center">... </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($allWorkingCalendar as $row) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td ><?= date("F Y", strtotime($row['working_month'])) ?></td>
                                        <td class="text-center"><?= $row['working_day'] ?></td>
                                        <td class="text-center">
                                            <a href="#" class="buttonEditWorkingCalendar mr-1 text-primary" data-id="<?= $row['id']?>" title="Edit data" data-toggle="modal" data-target="#addNewWorkingCalendar"><i class="far fa-edit"></i></a>
                                            <a href="#" class="buttonDeleteWorkingCalendar text-danger" data-id="<?= $row['id']?>" title="Delete data"><i class="fas fa-times"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>                    
                </form>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal fade" id="addNewWorkingCalendar" tabindex="-1" role="dialog" aria-labelledby="addNewWorkingCalendar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewWorkingCalendarTitle">Add new working calendar data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">   
                    <input type="hidden" class="form-control" id="addNewWorkingCalendarId" name="addNewWorkingCalendarId">        
                    <div class="form-group">
                        <label for="addNewWorkingCalendarMonth" class="form-label" for="">Month - year</label>
                        <input type="date" class="form-control" id="addNewWorkingCalendarMonth" name="addNewWorkingCalendarMonth">
                    </div>
                    <div class="form-group">
                        <label for="addNewWorkingCalendarDays" class="form-label">Total working days qty</label>
                        <input type="number" min="10" max="30" step="1" class="form-control" id="addNewWorkingCalendarDays" name="addNewWorkingCalendarDays">
                    </div>    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary px-3" id="addNewWorkingCalendarSubmit">Save</button>
                </div>
            </form>              
        </div>
    </div>
</div>

<div class="modal fade" id="addNewWorkingCalendarMultiple" tabindex="-1" role="dialog" aria-labelledby="addNewWorkingCalendarMultiple" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('setting/addMultipleWorkingcalendar') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewWorkingCalendarMultipleTitle">Add multiple working calendar data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                              
                    <div class="form-group">
                        <label for="addNewWorkingCalendarMultipleQty" class="form-label" for="">How many months (berapa banyak bulan/data)</label>
                        <input type="number" min="2" max="12" step="1" class="form-control" id="addNewWorkingCalendarMultipleQty" name="addNewWorkingCalendarMultipleQty" value="2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary px-3">Submit</button>
                </div>
            </form>              
        </div>
    </div>
</div>