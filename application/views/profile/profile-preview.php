
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="card card-primary card-outline col-md-8">
                <div class="card-header">
                    <span class="text-primary">Gambar yang akan digunakan sebagai profile</span> 
                </div>        
                <div class="card-body">
                    <?php var_dump($_FILES) ?>
                    <img class="" src="<?= $full_path ?>">
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>