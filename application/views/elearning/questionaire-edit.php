<div class="content-wrapper">
  <section class="content pt-3">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>

    <?php 
      function booleanToChecked($data) {
        if ($data == 1) {
          return 'checked';
        } else {
          return false;
        }
      }
    ?>

    <div class="container-fluid">
      <div class="card">
        <div class="card-header bg-primary">
          <span class="h6">Edit or update selected questioner</span>
        </div>

        <form method="POST" action="">
          <div class="card-body">
            <input type="hidden" class="form-control row" id="elearningEditQuestionerId" name="elearningEditQuestionerId" value="<?= $questionerData['id'] ?>">
            <div class="form-group row">
              <label for="elearningEditQuestionerCategory" class="col-sm-auto" style="width: 100px;">Category</label>
              <div class="col-sm-3">
                <select class="custom-select" id="elearningEditQuestionerCategory" name="elearningEditQuestionerCategory">
                  <option value="<?= $questionerData['category'] ?>" selected><?= $questionerData['category'] ?></option>
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
              <label for="elearningEditQuestionerPeriod" class="col-sm-auto ml-5" style="width: 100px;">Period</label>
              <div class="col-sm-3">
                <input type="date" class="form-control row" id="elearningEditQuestionerPeriod" name="elearningEditQuestionerPeriod" value="<?= $questionerData['period'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerQuestion" class="col-sm-auto" style="width: 100px;">Question</label>
              <div class="col-sm-10">
                <textarea id="elearningEditQuestionerQuestion" name="elearningEditQuestionerQuestion"><?= $questionerData['question'] ?></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerOptionA" class="col-sm-auto" style="width: 100px;">Option A</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningEditQuestionerOptionA" name="elearningEditQuestionerOptionA" value="<?= $questionerData['option_a'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerOptionB" class="col-sm-auto" style="width: 100px;">Option B</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningEditQuestionerOptionB" name="elearningEditQuestionerOptionB" value="<?= $questionerData['option_b'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerOptionC" class="col-sm-auto" style="width: 100px;">Option C</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningEditQuestionerOptionC" name="elearningEditQuestionerOptionC" value="<?= $questionerData['option_c'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerOptionD" class="col-sm-auto" style="width: 100px;">Option D</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningEditQuestionerOptionD" name="elearningEditQuestionerOptionD" value="<?= $questionerData['option_d'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerOptionE" class="col-sm-auto" style="width: 100px;">Option E</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="elearningEditQuestionerOptionE" name="elearningEditQuestionerOptionE" value="<?= $questionerData['option_e'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerCorrectkey" class="col-sm-auto" style="width: 100px;">Answer</label>
              <div class="col-sm-2">
                <select class="custom-select form-control" id="elearningEditQuestionerCorrectkey" name="elearningEditQuestionerCorrectkey">
                  <option value="<?= $questionerData['correct_key'] ?>" selected><?= $questionerData['correct_key'] ?></option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="C">C</option>
                  <option value="D">D</option>
                  <option value="E">E</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="elearningEditQuestionerStatus" class="col-sm-auto" style="width: 100px;">Status</label>
              <div class="col-sm-10">
                <!-- <input type="checkbox" id="elearningEditQuestionerStatus" name="elearningEditQuestionerStatus"> -->
                <div class="pretty p-switch p-fill">
                    <input type="hidden" name="elearningEditQuestionerStatus" name="elearningEditQuestionerStatus" value="0">
                    <input type="checkbox" name="elearningEditQuestionerStatus" name="elearningEditQuestionerStatus" value="1" <?= booleanToChecked($questionerData['status']) ?>>
                    <div class="state p-primary">
                      <label>Show questioner <span class="text-secondary"><em>(jika NONAKTIF kuesioner tidak akan ditampilkan)</em></span></label>
                    </div>
                </div> 
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary" name="elearningEditQuestionerSubmit">Submit</button>
            <button class="btn btn-secondary">Cancel</btn>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script src="<?= base_url('assets/ckeditor4.19/ckeditor.js') ?>"></script>
<script type="text/javascript">
  CKEDITOR.replace('elearningEditQuestionerQuestion', {       
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


