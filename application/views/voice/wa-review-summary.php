<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
          require 'function-voice.php';
        ?>
        <div class="container-fluid pt-2 px-1">
            <div class="card card-primary">
                <div class="card-header">
                    <span class="h6">Summary of Agent's WA Reply Review</span>
                </div>
            </div>
        </div>
    </section>
</div>