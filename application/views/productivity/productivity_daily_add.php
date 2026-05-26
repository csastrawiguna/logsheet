<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <?php 
                $allowedChangeSetting = ['1', '5', '9'];
            ?>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <form method="post" action="">
                            <div class="card-header bg-primary">
                                Add single date productivity daily
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-outline-primary" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

