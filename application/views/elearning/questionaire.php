<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content pt-3">
    <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
    <?php                 
      function dateToString($date) {
        if($date == '0000-00-00 00:00:00' || $date == '0' || $date == NULL) {
            return '-';
        } else {
            return date("d-M-Y H:i", strtotime($date));
        }
      }
    ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header bg-primary">
              List of Questioner
              <div class="card-tools">
                <!-- <a href="#" class="text-white mr-3" data-toggle="modal" data-target="#questionaireModal" id="btnAddQuestionaire"><i class="fas fa-plus-circle"></i> Add new</a> -->
                <a href="<?= base_url('elearning/addquestionaire') ?>" class="text-white mr-3"><i class="fas fa-plus-circle"></i> Add new</a>
                <a href="#" class="text-white mr-3"  data-toggle="modal" data-target="#questionaireImportExcel">
                  <i class="fas fa-upload"></i></span> From Excel
                </a>
                <a href="<?= base_url() ?>files/Format_Upload_Soal_Elearning.xlsx" class="text-white">
                  <i class="fas fa-download"></i> Format Excel
                </a>
              </div>
            </div>
            <div class="card-body">
              <table id="tableElearningQuestionaire" class="table table-sm table-hover">
                <thead>
                  <tr class="text-center">
                    <th style="max-width: 20px;">#</th>
                    <th class="col-sm-1 text-center">Category</th>
                    <th class="col-sm-2">Question</th>
                    <th class="col-sm-1">A</th>
                    <th class="col-sm-1">B</th>
                    <th class="col-sm-1">C</th>
                    <th class="col-sm-1">D</th>
                    <th class="col-sm-1">E</th>
                    <th class="">...</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($allQuestionaire as $qs) :
                  ?>
                    <tr>
                      <td class="text-center"><?= $i++; ?></td>
                      <td class="text-center col-sm-1"><?= $qs['category']; ?></td>
                      <td><?= $qs['question']; ?></td>
                      <td class="<?= strtoupper($qs['correct_key']) == 'A' ? 'text-bold text-dark' : ''; ?>"><?= $qs['option_a']; ?></td>
                      <td class="<?= strtoupper($qs['correct_key']) == 'B' ? 'text-bold text-dark' : ''; ?>"><?= $qs['option_b']; ?></td>
                      <td class="<?= strtoupper($qs['correct_key']) == 'C' ? 'text-bold text-dark' : ''; ?>"><?= $qs['option_c']; ?></td>
                      <td class="<?= strtoupper($qs['correct_key']) == 'D' ? 'text-bold text-dark' : ''; ?>"><?= $qs['option_d']; ?></td>
                      <td class="<?= strtoupper($qs['correct_key']) == 'E' ? 'text-bold text-dark' : ''; ?>"><?= $qs['option_e']; ?></td>
                      <td class="text-center">
                        <div class="btn-group">                              
                          <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                          <div class="dropdown-menu dropdown-menu-right bg-light p-2" style="min-width: 280px;">
                            <table  class="table table-sm table-borderless">
                              <tbody>
                                <tr>
                                  <td>Answer key</td>
                                  <td>: <?= strtoupper($qs['correct_key']) ?></td>
                                </tr>
                                <tr>
                                  <td>Saved by</td>
                                  <td>: <?= $qs['saved_by'] ?></td>
                                </tr>
                                <tr>
                                  <td>Saved at</td>
                                  <td>: <?= dateToString($qs['saved_at']) ?></td>
                                </tr>
                                <tr>
                                  <td>Updated by</td>
                                  <td>: <?= $qs['updated_by'] ?></td>
                                </tr>
                                <tr class="border-bottom">
                                  <td>Updated at</td>
                                  <td>: <?= dateToString($qs['updated_at']) ?></td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                      <a class="btnEditQuestionaire" href="<?= base_url('elearning/editquestioner') . '/'  . $qs['qid']; ?>" style="cursor: pointer;" title="Edit questioner">
                                        <i class="fas fa-edit"></i>&nbsp; Edit questioner
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <a class="buttonDeleteElearningQuestionaire text-danger" data-qid="<?= $qs['qid']; ?>" href="<?= base_url('elearning/delete_questionaire') . '/'  . $qs['qid']; ?>" title="Delete questioner">
                                      <i class="fas fa-times"></i>&nbsp; Delete questioner
                                    </a>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php
                  endforeach;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Add Questionaire-->
    <div class="modal fade" id="questionaireModal" tabindex="-1" role="dialog" aria-labelledby="questionaireModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="questionaireModalLabel">Add new questionaire</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?= form_open_multipart('elearning/questionaire'); ?>
            <div class="row">
              <div class="col-8">
                <div class="form-group row">
                  <label for="selectQuestionaireCategory" class="col-sm-2 col-form-label">Category</label>
                  <div class="col-sm-10">
                    <select class="custom-select form-control" id="selectQuestionaireCategory" name="selectQuestionaireCategory">
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
                </div>
              </div>
              <div class="col-4">
                <div class="form-group row">
                  <label for="formQuestionPeriod" class="col-sm-2 col-form-label">Period</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" id="formQuestionPeriod" name="formQuestionPeriod">
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input class="form-control" id="formQid" name="formQid" hidden>
              </div>
            </div>
            <div class="form-group row">
              <label for="formQuestion" class="col-sm-2 col-form-label">Question</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="formQuestion" name="formQuestion"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="formOptionA" class="col-sm-2 col-form-label">Option A</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="formOptionA" name="formOptionA">
              </div>
            </div>
            <div class="form-group row">
              <label for="formOptionB" class="col-sm-2 col-form-label">Option B</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="formOptionB" name="formOptionB">
              </div>
            </div>
            <div class="form-group row">
              <label for="formOptionC" class="col-sm-2 col-form-label">Option C</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="formOptionC" name="formOptionC">
              </div>
            </div>
            <div class="form-group row">
              <label for="formOptionD" class="col-sm-2 col-form-label">Option D</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="formOptionD" name="formOptionD">
              </div>
            </div>
            <div class="form-group row">
              <label for="formOptionE" class="col-sm-2 col-form-label">Option E</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="formOptionE" name="formOptionE">
              </div>
            </div>
            <div class="form-group row">
              <label for="formCorrect_key" class="col-sm-2 col-form-label">Answer</label>
              <div class="col-sm-10">
                <select class="custom-select form-control" id="formCorrect_key" name="formCorrect_key">
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
              <label for="formQuestionairePicture" class="col-sm-2 col-form-label">Picture</label>
              <div class="col-sm-10">
                <input type="file" class="" id="formQuestionairePicture" name="formQuestionairePicture">
              </div>
            </div>
            <div class="form-group row">
              <label for="formStatus" class="col-sm-2 col-form-label">Status</label>
              <div class="col-sm-10">
                <select class="custom-select form-control" id="formStatus" name="formStatus">
                  <option value="1" selected="">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="questionerSubmit">Save</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Questionaire Import Excel -->
    <div class="modal fade" id="questionaireImportExcel" tabindex="-1" role="dialog" aria-labelledby="questionaireImportExcelLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="questionaireImportExcelLabel">Import questionaire</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <?= form_open_multipart('elearning/uploadQuestionaire'); ?>
            <div class="input-group mb-3">
              <select class="custom-select form-control" id="status" name="uploadQuestionaireSelectCategory">
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
              <div class="input-group-prepend">
                <label class="input-group-text" for="uploadQuestionaireElearningId">Category</label>
              </div>
            </div>

            <div class="input-group mb-3">
              <input type="date" class="form-control" id="uploadQuestionairePeriod" name="uploadQuestionairePeriod">
              <div class="input-group-prepend">
                <label class="input-group-text" for="uploadQuestionairePeriod">Period</label>
              </div>
            </div>

            <div class="input-group mb-3">
              <div class="custom-file">
                <label class="custom-file-label" for="uploadQuestionaireFile" id="labelUploadQuestionaireFile" aria-describedby="inputGroupFileAddon02">Choose file</label>
                <input type="file" class="custom-file-input" id="uploadQuestionaireFile" name="uploadQuestionaireFile">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="questionerImport">Upload</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

</div>


