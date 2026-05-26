<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="container-fluid">
            <!-- /.row -->
                <?php 
                    require 'function_productivity.php';
                ?>

                <div class="card card-primary">
                    <div class="card-header">
                        <span>Add/input productivity by interval</span>
                        <div class="card-tools">
                            <a class="text-white mr-3" href="<?= base_url('files/Format_Upload_Productivity_Harian_single_date.xlsx') ?>"><i class="far fa-file-excel"></i> Format Hitung</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('productivity/byinterval') ?>" method="POST">
                            <div class="form-group row" style="max-width: 400px">
                                <label for="inputRawProductivityTime" class="col-sm-3 right">Update per</label>
                                <!-- <input type="date" class="col-sm-5 mr-1 custom-select" id="inputRawProductivityDate" name="inputRawProductivityDate" value="<?= date("Y-m-d") ?>"> -->
                                <input type="time" class="col-sm-4 custom-select" id="inputRawProductivityTime" name="inputRawProductivityTime">
                            </div>
                            <div class="form-group row mt-4">
                                <label for="inputRawProductivity" class="col-sm">Paste dari Excel ke kolom <span class="h5"><i class="fas fa-arrow-circle-down text-danger"></i></span></label>
                            </div>
                            <textarea name="inputRawProductivity" id="inputRawProductivity"></textarea>
                            <button type="submit" class="btn btn-outline-primary mt-2">Proccess data</button>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>