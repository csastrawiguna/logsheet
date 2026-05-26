<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">      
      <div class="row"> 
        <div class="col-sm-10 my-3 px-1">
          <p id="surveyFilling" style="display: none;"><?= $isfilledemail;?></p>          
          <div class="card card-outline card-danger">
            <div class="card-header">
              Tolong isi kuesioner ini ya...
            </div>
            <div class="card-body">
              <form method="post" action="">
                <div class="form-group">
                  <label for="exampleInputEmail1">Masukkan email personal (boleh email kantor)</label>
                  <input type="email" class="form-control" id="collectEmailPersonal" name="collectEmailPersonal" aria-describedby="emailHelp">
                  <small id="emailHelp" class="form-text text-muted">Email akan digunakan untuk register akun Elearning baru. Jika nanti lupa password, reset password menggunakan email</small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div class="modal fade" id="categorySurvey" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="categorySurveyLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title text-center" id="categorySurveyLabel">Intermezzo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body bg-primary">
        <p class="text-center h3 mt-3">
          Tolong isi alamat email pribadi yang aktif ya... ditunggu segera.
          <br>
        </p>
        <p class="text-center h5 text-light">
          Menu-menu dapat diakses setelah semua pertanyaan diisi. Mohon dibantu ya...
        </p>        
        <p class="text-center mt-5">
          <button class="btn btn-outline-light" data-dismiss="modal" aria-label="Close">OK</button>
        </p>
      </div>             
    </div>
  </div>
</div>