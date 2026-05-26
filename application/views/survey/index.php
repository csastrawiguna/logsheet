<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="row"> 
        <div class="col-sm my-3 px-1">
          <p id="surveyFilling" style="display: none;"><?= $isdonesurvey;?></p>
          <div class="card">
            <div class="card-header bg-primary">
              Tolong isi kuesioner ini ya...
            </div>
            <div class="card-body">
              <form method="post" action="">
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <td rowspan="2">1</td>                  
                      <td class="text-bold">Bonus & kenaikan gaji 100% bergantung KPI.<br>Penting tidak mengetahui pencapaian kerja (produktivitas, CS index, smile voice, dsb,) di bulan sekarang atau sebelumnya?</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ1" value="Ya">
                          <div class="state p-primary-o">
                            <label>Ya, penting</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ1" value="Tidak">
                          <div class="state p-primary-o">
                            <label>Tidak penting</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ1" value="Abstain">
                          <div class="state p-primary-o">
                            <label>Tidak yakin (abstain)</label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr class="border-top">
                      <td rowspan="2">2</td>                  
                      <td class="text-bold">Harapannya, pencapaian produktivitas, CS index, smile voice, dsb diinformasikan setiap...</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="1minggu">
                          <div class="state p-primary-o">
                            <label>1 minggu sekali</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="2minggu">
                          <div class="state p-primary-o">
                            <label>2 minggu sekali</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="1bulan">
                          <div class="state p-primary-o">
                            <label>1 bulan sekali</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="2bulan">
                          <div class="state p-primary-o">
                            <label>2 bulan sekali</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="3bulan">
                          <div class="state p-primary-o">
                            <label>3 bulan sekali</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ2" value="6bulan">
                          <div class="state p-primary-o">
                            <label>6 bulan sekali</label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr class="border-top">
                      <td rowspan="2">3</td>                  
                      <td class="text-bold">Kalau sudah tahu produktivitas, CS index, smile voice, dkk di bulan sebelumnya kurang, apa yang akan dilakukan</td>
                    </tr>
                    <tr>
                      <td>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ3" value="diperbaiki">
                          <div class="state p-primary-o">
                            <label>Berusaha naik (diperbaiki)</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ3" value="pasrah">
                          <div class="state p-primary-o">
                            <label>Pasrah</label>
                          </div>
                        </div><br>
                        <div class="pretty p-default p-round my-2">
                          <input type="radio" name="surveyQ3" value="abstain">
                          <div class="state p-primary-o">
                            <label>Tidak yakin (abstain)</label>
                          </div>
                        </div>                      
                      </td>
                    </tr>
                    <tr class="border-top">
                      <td rowspan="2">4</td>                  
                      <td class="text-bold">Apakah aplikasi Logsheet bermanfaat (Ya / Tidak / Abstain)?<br>Berikan alasan</td>
                    </tr>
                    <tr>
                      <td>
                        <textarea name="surveyQ4" cols="100%"></textarea>
                      </td>
                    </tr>
                    <tr class="border-top">
                      <td rowspan="2">5</td>                  
                      <td class="text-bold">Fitur yang diharapkan ada di aplikasi Logsheet</td>
                    </tr>
                    <tr>
                      <td>
                        <textarea name="surveyQ5" cols="100%"></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>
                        <button type="submit" class="btn btn-primary">Sumbit</button>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
          Tolong isi beberapa pertanyaan ini
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