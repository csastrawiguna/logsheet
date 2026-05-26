<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
      if(!$this->input->post()) {
        $startPeriod = date("Y-m-d", strtotime("-3 months"));
        $endPeriod = date("Y-m-d");
      } else {
        $startPeriod = $this->input->post('selectTransitionBlackbookStart');
        $endPeriod = $this->input->post('selectTransitionBlackbookEnd');
      }
    ?>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 py-0">
          <div class="card">
            <div class="card-header bg-primary">
              Transition
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col text-center">
                  <p class="lead">OteWe</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->