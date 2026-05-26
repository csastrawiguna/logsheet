<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <p class="h5 ml-3">List of Elearning Questionaire</p>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-10">
                        <div class="input-group mb-3">
                          <input type="text" class="form-control" placeholder="Seach">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon1">Search</span>
                          </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryModal">Add new</button>
                    </div>
                </div>
               <table class="table table-striped table-hover mt-2">
                   <thead>
                       <tr class="text-center">
                           <th>#</th>
                           <th>Period</th>
                           <th>Elearning name</th>
                           <th>Start</th>
                           <th>End</th>
                           <th>Status</th>
                           <th>Action</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php 
                       $i = 1;
                       foreach($elearning_category as $el) :
                        ?>
                        <tr>
                            <td><?= $i++;  ?></td>
                            <td class="text-center"><?= $el['period'];  ?></td>
                            <td><?= $el['name'];  ?></td>
                            <td class="text-center"><?= $el['startdate'];  ?></td>
                            <td class="text-center"><?= $el['enddate'];  ?></td>
                            <td class="text-center">
                                <?php if($el['status'] == 0 ):
                                ?>
                                <a href=""><button class="btn btn-dark">OFF</button></a>
                                <?php
                            
                            else:
                                ?>
                                <a href=""><button class="btn btn-primary">ON</button></a>
                                <?php
                                endif;
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('elearning/edit_elearning'); ?>"><l class="fas fa-edit text-primary"></l></a> | 
                                <a href="<?= base_url('elearning/edit_elearning'); ?>"><l class="fas fa-trash-alt text-danger"></l></a> 
                            </td>
                        </tr>
                        <?php 
                        endforeach;
                        ?>
                   </tbody>
               </table>
           </div>
            <!-- Main row -->
            <div class="row">
            </div>
            <!-- /.row (main row) -->
			</div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

