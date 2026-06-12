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
                <div class="card-body">
                    <form action="" class="form-row mb-3" method="post" style="width: 680px;">
                        <label for="wareviewSummaryDateStart" class="col-sm-1">Period</label>
                        <div class="col-sm-3">
                            <input type="date" id="wareviewSummaryDateStart" name="wareviewSummaryDateStart" class="form-control" value="<?= $startPeriod ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="date" id="wareviewSummaryDateEnd" name="wareviewSummaryDateEnd" class="form-control" value="<?= $endPeriod ?>">
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-outline-primary" id="buttonSubmitwareviewSummary" name="buttonSubmitwareviewSummary">Go</button>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col">
                            <p>
                                <?php var_dump($wareviewSummaryAllByPeriod) ?>
                            </p>
                            <p>
                                <?php var_dump($wareviewSummaryAllTotal) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>