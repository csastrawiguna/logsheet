<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php 
      // if(!$this->input->post()) {
      //   $startPeriod = date("Y-m-d", strtotime("-6 months"));
      //   $endPeriod = date("Y-m-d");
      // } else {
      //   $startPeriod = $this->input->post('selectSummaryVoiceStart');
      //   $endPeriod = $this->input->post('selectSummaryVoiceEnd');
      // }
    ?>

    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 py-0">
          <div class="card card-outline card-info">
            <div class="card-header">
              <p class="h5 text-info"><span class="h5"><i class="fas fa-info-circle"></i></span> Informasi</p>
                <a href="<?= $_SERVER['HTTP_REFERER'] ?>" style="position: absolute; right: 10px; top: 10px;" class="float-right"><button class="btn btn-sm btn-outline-info"><- Back</button></a>              
            </div>

            <div class="card-body">
              <div>
                <p class="lead">
                  4 Hal yang dinilai dalam Etika penerimaan telepon:
                </p>
              </div>
              <div class="row mt-5">
                <div class="col-sm-2">
                  <img src="<?= base_url('files/info/hello.jpg') ?>" width="100" class="text-center">
                </div>
                <div class="col-sm-8">
                  <p class="text-bold text-info text-info">Salam pembuka (greeting opening) - maks. 3 poin</p>
                  <p><i class="fas fa-check"></i> Greeting bagus [3]</p>
                  <p><i class="fas fa-skull"></i> Greeting tidak bagus [1]</p>
                </div> 
              </div>
              <div class="row mt-5">
                <div class="col-sm-2">
                  <img src="<?= base_url('files/info/voice_tone.png') ?>" width="100" class="text-center">
                </div>
                <div class="col-sm-8">
                  <p class="text-bold text-info">Smile Voice - maks. 10 poin</p>
                  <p><i class="fas fa-check"></i> Smile voice OK [10]</p>
                  <p><i class="fas fa-check"></i> Perlu diperbaiki [5]</p>
                  <p><i class="fas fa-skull"></i> Suara datar [3]</p>
                  <p><i class="fas fa-skull"></i> Nada tinggi, memotong pembicaraan, melenguh, dsb. [1]</p>
                </div> 
              </div>
              <div class="row mt-5">
                <div class="col-sm-2">
                  <img src="<?= base_url('files/info/communication.png') ?>" width="100" class="text-center">
                </div>
                <div class="col-sm-8">
                  <p class="text-bold text-info">Accuracy - maks. 10 poin</p>
                  <p><i class="fas fa-check"></i> Semua info akurat [10]</p>
                  <p><i class="fas fa-check"></i> Info kurang lengkap, jargon, tidak info cashless, nama konsumen <3x [5]</p>
                  <p><i class="fas fa-skull"></i> Salah info ke konsumen [1]</p>
                </div> 
              </div>
              <div class="row mt-5">
                <div class="col-sm-2">
                  <img src="<?= base_url('files/info/thank_you.png') ?>" width="100" class="text-center">
                </div>
                <div class="col-sm-8">
                  <p class="text-bold text-info">Closing - maks. 2 poin</p>
                  <p><i class="fas fa-check"></i> OK [2]</p>                  
                  <p><i class="fas fa-skull"></i> Tidak lengkap, tidak tawarkan bantuan kembali [1]</p>
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