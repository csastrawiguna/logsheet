<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content pt-3">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <div class="container-fluid">
      <div class="card">
        <div class="card-header bg-primary">
          <span class="h6">Add new questioner</span>
        </div>
        <form method="POST" action="">
        <!-- <form method="POST" action="<?= base_url('elearning/tesa') ?>"> -->
          <div class="card-body">
            <div class="form-group row">
              <label for="elearningAddQuestionerCategory" class="col-sm-auto" style="width: 100px;">Category</label>
              <div class="col-sm-3">
                <select class="custom-select" id="elearningAddQuestionerCategory" name="elearningAddQuestionerCategory">
                  <option>-</option>
                  <option value="AIR CONDITIONER">AIR CONDITIONER</option>
                  <option value="AIR PURIFIER & AIR COOLER">AIR PURIFIER & AIR COOLER</option>
                  <option value="AUDIO">AUDIO</option>
                  <option value="LAPTOP">LAPTOP</option>
                  <option value="REFRIGERATOR">REFRIGERATOR</option>
                  <option value="SMALL HOME APPLIANCES">SMALL HOME APPLIANCES</option>
                  <option value="SMARTPHONE">SMARTPHONE</option>
                  <option value="TELEVISION">TELEVISION</option>
                  <option value="WASHING MACHINE">WASHING MACHINE</option>
                  <option value="BED (BUSSINES PRODUCT)">BED (BUSSINES PRODUCT)</option>
                  <option value="OTHERS">OTHERS</option>
                  <option value="CCC GENERAL">CCC GENERAL</option>
                </select>
              </div>
              <label for="elearningAddQuestionerPeriod" class="col-sm-auto ml-5" style="width: 100px;">Period</label>
              <div class="col-sm-3">
                <input type="date" class="form-control row" id="elearningAddQuestionerPeriod" name="elearningAddQuestionerPeriod">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerQuestion" class="col-sm-auto" style="width: 100px;">Question</label>
              <div class="col-sm-10">
                <textarea id="elearningAddQuestionerQuestion" name="elearningAddQuestionerQuestion"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerOptionA" class="col-sm-auto" style="width: 100px;">Option A</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningAddQuestionerOptionA" name="elearningAddQuestionerOptionA">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerOptionB" class="col-sm-auto" style="width: 100px;">Option B</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningAddQuestionerOptionB" name="elearningAddQuestionerOptionB">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerOptionC" class="col-sm-auto" style="width: 100px;">Option C</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningAddQuestionerOptionC" name="elearningAddQuestionerOptionC">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerOptionD" class="col-sm-auto" style="width: 100px;">Option D</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningAddQuestionerOptionD" name="elearningAddQuestionerOptionD">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerOptionE" class="col-sm-auto" style="width: 100px;">Option E</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningAddQuestionerOptionE" name="elearningAddQuestionerOptionE">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerCorrectkey" class="col-sm-auto" style="width: 100px;">Answer</label>
              <div class="col-sm-2">
                <select class="custom-select form-control" id="elearningAddQuestionerCorrectkey" name="elearningAddQuestionerCorrectkey">
                  <option selected="">Pilih jawaban</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                  <option value="E">E</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningAddQuestionerStatus" class="col-sm-auto" style="width: 100px;">Status</label>
              <div class="col-sm-10">
                <!-- <input type="checkbox" id="elearningAddQuestionerStatus" name="elearningAddQuestionerStatus"> -->
                <div class="pretty p-switch p-fill">
                    <input type="hidden" name="elearningAddQuestionerStatus" name="elearningAddQuestionerStatus" value="0">
                    <input type="checkbox" name="elearningAddQuestionerStatus" name="elearningAddQuestionerStatus" value="1" checked>
                    <div class="state p-primary">
                      <label>Show questioner <span class="text-secondary"><em>(jika NONAKTIF kuesioner tidak akan ditampilkan)</em></span></label>
                    </div>
                </div> 
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary" name="elearningAddQuestionerSubmit">Submit</button>
            <button class="btn btn-secondary">Cancel</btn>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
<script type="text/javascript">
  CKEDITOR.replace('elearningAddQuestionerQuestion', {       
      // Responsive Filemanager
      removePlugins : 'exportpdf',
      extraPlugins : 'filetools, ckeditorfa, dialog',
      allowedContent : true,
      contentsCss : 'http://192.168.188.254/logsheet/assets/ckeditor/plugins/ckeditorfa/css/ckeditorfa.css',
      filebrowserBrowseUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',        
      filebrowserUploadUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
      filebrowserImageBrowseUrl : 'http://192.168.188.254/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
      filebrowserUploadMethod : 'form',        
  });
</script>
<!-- <script type="text/javascript">
  CKEDITOR.replace('elearningAddQuestionerQuestion', {       
      // Responsive Filemanager
      removePlugins : 'exportpdf',
      extraPlugins : 'filetools, ckeditorfa, dialog',
      allowedContent : true,
      contentsCss : 'http://localhost:8080/logsheet/assets/ckeditor/plugins/ckeditorfa/css/ckeditorfa.css',
      filebrowserBrowseUrl : 'http://localhost:8080/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',        
      filebrowserUploadUrl : 'http://localhost:8080/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
      filebrowserImageBrowseUrl : 'http://localhost:8080/logsheet/assets/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr=',
      filebrowserUploadMethod : 'form',        
  });
</script> -->

