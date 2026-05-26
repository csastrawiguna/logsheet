<div class="content-wrapper">
    <section class="content pt-3 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
                function toDuration($dur) {
                    $hours = floor($dur / 3600);
                    $minutes = floor($dur / 60) % 60;
                    $seconds = $dur % 60;
                    echo toDec($hours).":".toDec($minutes).":".toDec($seconds);
                }

                function toDec($num) {
                    if ($num < 10) {
                        return "0".$num;
                    } else {
                        return $num;
                    }
                }

                function remedialtag($rem) {
                    if ($rem > 0) {
                        return '<span class="badge badge-pill badge-warning float-right">' . $rem . '</span>';
                    } else {
                        return '';
                    }
                }

                if (!($this->input->post('selectCategorySummary'))) {
                    $elearning_id = $this->elearning->getAllElearningCategory()[0]['id'];
                } else {
                    $elearning_id = $this->input->post('selectCategorySummary');
                }

            ?>
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <!-- Summary by Category Tab -->
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-byCategory-tab" data-toggle="pill" href="#custom-tabs-one-byCategory" role="tab" aria-controls="custom-tabs-one-byCategory" aria-selected="false">By Category</a>
                        </li>

                        <!-- Summary Result Tab -->
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-sumary-tab" data-toggle="pill" href="#custom-tabs-one-summary" role="tab" aria-controls="custom-tabs-one-summary" aria-selected="true">Score Transition</a>
                        </li>

                        <!-- Summary by Agent Tab -->
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-byAgent-tab" data-toggle="pill" href="#custom-tabs-one-byAgent" role="tab" aria-controls="custom-tabs-one-byAgent" aria-selected="false">By Agent</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <!-- Summary by Category Content -->
                        <div class="tab-pane fade show active" id="custom-tabs-one-byCategory" role="tabpanel" aria-labelledby="custom-tabs-one-byCategory-tab">
                            <div class="row mb-2">
                                <form class="form-inline" method="post" id="formGetSummaryByCategory">
                                    <div class="form-group mx-sm-2 mb-2">
                                        <label for="selectCategorySummary" class="mr-3">Elearing period</label>
                                        <select type="password" class="form-control custom-select" id="selectCategorySummary" name="selectCategorySummary">
                                            <option value="<?= $elearning_id; ?>"><?= '[' . date('M-Y', strtotime($elearning_period)) . '] ' . $elearning_name; ?></option>
                                            <?php foreach ($elearningList as $el) : ?>
                                                <option value="<?= $el['id']; ?>"><?= '[' . date('M-Y', strtotime($el['period'])) . '] ' . $el['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" id="btnSelectSummaryByCategory" class="btn btn-outline-primary mb-2">View Result</button>
                                </form>
                                <button type="button" id="buttonExportToExcel" class="btn btn-outline-success mb-2 mx-1" title="Export to Excel">
                                    <i class="fas fa-download" style="font-size: 16px;"></i> to Excel
                                </button>
                                <button type="button" id="buttonResultImportFromExcel" class="btn btn-outline-success mb-2" title="Import from Excel" data-toggle="modal" data-target="#modalElearningUploadResultFromExcel">
                                    <i class="fas fa-upload" style="font-size: 16px;"></i> from Excel
                                </button>
                            </div>

                            <div id="jsGrid1" class="row">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover table-sm" id="tableElearningSummaryByPeriod">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Fullname</th>
                                                <th>NPK</th>
                                                <th>Department</th>
                                                <th class="text-center">Pretest</th>
                                                <th class="text-center">Posttest</th>
                                                <th class="text-center">Pass?</th>
                                                <th class="">Posttest date</th>
                                                <th class="text-center">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($summaryByCategory as $sc) : ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td class="pr-3">
                                                        <?= $sc['fullname']; ?>
                                                        <?= remedialtag($sc['remedial']) ?>        
                                                    </td>
                                                    <td><?= $sc['npk']; ?></td>
                                                    <td><?= $sc['department']; ?></td>
                                                    <td class="text-center">
                                                        <?php if ($sc['pretest_score'] == 0) : ?>
                                                            -
                                                        <?php else : echo $sc['pretest_score']; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($sc['posttest_score'] == 0) : ?>
                                                            -
                                                        <?php else : echo $sc['posttest_score']; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center h5">
                                                        <?php if ($sc['is_pass'] == 0) : ?>
                                                            <i class="far fa-times-circle text-danger"></i>
                                                        <?php else : ?>
                                                            <i class="fas fa-check-circle text-primary"></i>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="">
                                                        <?php if ($sc['posttest_score'] == 0) {
                                                            echo "-";
                                                        } else {
                                                            echo date('d-M-Y H:i:s', strtotime($sc['posttest_date']));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="text-center"><?= toDuration($sc['exam_duration']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TRANSITION -->
                        <div class="tab-pane fade" id="custom-tabs-one-summary" role="tabpanel" aria-labelledby="custom-tabs-one-summary-tab">
                            <div class="row mb-2">
                                <form class="form-inline" method="post" id="formGetSummaryResult">
                                    <div class="form-group mx-sm-2 mb-2">
                                        <label for="selectElearningSummaryStart" class="mr-3">Elearing period</label>
                                        <input type="date" class="form-control" name="selectElearningSummaryStart" id="selectElearningSummaryStart" value="<?= date("Y-m-01", strtotime("-5 months")) ?>">
                                        <label for="selectElearningSummaryEnd" class="mx-3">to</label>
                                        <input type="date" class="form-control" name="selectElearningSummaryEnd" id="selectElearningSummaryEnd" value="<?= $elearningList[0]['period'] ?>">
                                    </div>
                                    <button type="button" id="btnSelectSummaryResult" class="btn btn-outline-primary mb-2">Go</button>
                                </form>
                            </div>

                            <div class="mt-4 col-8" id="elearningSummaryTableSummary">
                                <table class="table table-sm table-hover" id="tableElearningSummaryTableTransition">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>User</th>
                                            <?php
                                            $keys = array_keys($summaryResultTransition[0]);
                                            for ($col = 1; $col < count($summaryResultTransition[0]); $col++) :
                                                $nama_kolom = date("M-y", strtotime($keys[$col]));
                                            ?>
                                                <th class="px-3"><?= str_replace('_', ' ', $nama_kolom); ?></th>
                                            <?php endfor; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($summaryResultTransition as $row) : ?>
                                            <tr>
                                            <?php
                                            for ($baris = 0; $baris < count($summaryResultTransition[0]); $baris++) :
                                                $baris_data = $keys[$baris];
                                                $row[$baris_data] == null ? $cell_value = '-' : $cell_value = $row[$baris_data];
                                                is_numeric($cell_value) ? $cell = '<td  align="center" class="px-3">' . round($cell_value, 1) . '</td>' : $cell = '<td>' . str_replace(',', '.', $cell_value) . '</td>';
                                                echo $cell;
                                            endfor;
                                            echo '</tr>';
                                        endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- BY AGENT -->
                        <div class="tab-pane fade" id="custom-tabs-one-byAgent" role="tabpanel" aria-labelledby="custom-tabs-one-byAgent-tab">
                            Summary by Agent not available yet
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div><!-- /.container-fluid -->
    </section>


    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<div class="modal fade" id="modalElearningUploadResultFromExcel" tabindex="-1" role="dialog" aria-labelledby="modalElearningUploadResultFromExcelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalElearningUploadResultFromExcelLabel">Upload Elearning Result from Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= form_open_multipart('elearning/uploadElearningResultFromExcelNew'); ?>

                <div class="form-group">
                    <label for="uploadElearningResultFromExcelPeriod">Period / month of Elearning</label>
                    <input type="date" class="form-control" id="uploadElearningResultFromExcelPeriod" name="uploadElearningResultFromExcelPeriod">
                </div>
                <div class="form-group">
                    <label for="uploadElearningResultFromExcelCategory">Category (refer to product or material)</label>
                    <input type="" class="form-control" id="uploadElearningResultFromExcelCategory" name="uploadElearningResultFromExcelCategory">
                </div>
                <div class="form-group">
                    <label for="uploadElearningResultFromExcelPassingScore">Passing score</label>
                    <input type="number" class="form-control" id="uploadElearningResultFromExcelPassingScore" name="uploadElearningResultFromExcelPassingScore">
                </div>
                <div class="form-group">
                    <label for="uploadElearningResultFromExcelPassingScore">Elearning date</label>
                    <div class="row">
                        <div class="col-4">
                            <input type="date" class="form-control" id="uploadElearningResultFromExcelStartdate" name="uploadElearningResultFromExcelStartdate">
                        </div>
                        <div class="col-sm-auto" style="max-width: 5px;"> - </div>
                        <div class="col-4">
                            <input type="date" class="form-control" id="uploadElearningResultFromExcelEnddate" name="uploadElearningResultFromExcelSEndate">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="uploadElearningResultFromExcelFile">File</label><br>
                    <input type="file" class="" id="uploadElearningResultFromExcelFile" name="uploadElearningResultFromExcelFile">
                </div>
                <!-- <div class="custom-file mb-3">
                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                    <input type="file" class="custom-file-input" id="uploadElearningResultFromExcelFile" name="uploadElearningResultFromExcelFile">
                </div> -->
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary" name="uploadElearningResultFromExcelSubmit">Upload</button>
            </div>
            </form>
        </div>
    </div>
</div>