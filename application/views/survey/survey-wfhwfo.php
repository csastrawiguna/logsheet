<div class="content-wrapper">
  <!-- Main content -->
  <section class="content pt-3 px-1">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col">
          <p id="surveyFilling" style="display: none;"><?= $isdonesurvey;?></p>
          <form method="post" action="">
            <div class="card">
              <div class="card-header bg-purple">
                <span class="h6">Pilih WFH atau WFO ?</span>
              </div>
              <div class="card-body">
                <p class="text-purple">Kalau ada jadwal WFH, akan tetap ikut WFH sesuai jadwal atau lebih memilih tetap masuk kantor (WFO) jika diperbolehkan?<br>Kasih alasannya ya...</p>                
                
                <fieldset class="form-group row mt-5">
                  <legend class="col-form-label col-sm-2 float-sm-left pt-0">WFH atau WFO</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="surveyWfhQ1" id="surveyWfhQ1Wfh" value="WFH">
                      <label class="form-check-label" for="surveyWfhQ1Wfh">
                        Ikut WFH
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="surveyWfhQ1" id="surveyWfhQ1Wfo" value="WFO">
                      <label class="form-check-label" for="surveyWfhQ1Wfo">
                        Lebih pilih WFO (masuk kantor)
                      </label>
                    </div>                    
                  </div>
                </fieldset>
                <div class="form-group row">
                  <legend for="surveyWfhQ2" class="col-sm-2 col-form-label">Alasan</legend>
                  <div class="col-sm-10">
                    <textarea type="" class="form-control" id="surveyWfhQ2" name="surveyWfhQ2"></textarea>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button class="btn btn-outline-primary">Submit</button>
              </div>
            </div>
          </form>
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
      <div class="modal-body bg-purple">
        <p class="text-center h3 mt-3">
          Tolong isi beberapa pertanyaan ini, yang sudah isi tolong isi lagi ya. <span class="text-warning">Tadi kehapus</span>
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
