 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content pt-3">
         <div class="container-fluid">
             <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>

             <!-- <div class="row">
                 <div class="col text-center" style="margin-top: 30vh;;">
                     <h3 class="h3 text-warning">Coming soon</h3>
                 </div>
             </div> -->
             <div class="card">
                 <div class="card-header bg-primary">
                     <div class="card-title">Transition of CS Index Survey Result</div>
                 </div>
                 <div class="card-body">
                    <div class="row mb-3">
                        <div class="col form-row">
                            <label for="csindexTransitionResult" class="mr-3">Period</label>
                            <input type="date" class="col-sm-2 form-control" name="csindexTransitionStart" id="csindexTransitionStart" value="<?= date("Y-m-01", strtotime("-6 months")) ?>">
                            &nbspto&nbsp
                            <input type="date" class="col-sm-2 form-control" name="csindexTransitionEnd" id="csindexTransitionEnd" value="<?= date("Y-m-01") ?>">
                            <button class="btn btn-outline-primary ml-1" id="buttonCsindexTransition">Go</btn>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table table-hover table-responsive" id="tableCsindexTransition">
                                 <thead class="bg-light">
                                     <tr>
                                         <th class="" >User ID</th>
                                         <?php
                                            $keys = array_keys($csindexTransition[0]);
                                            for ($col = 1; $col < count($csindexTransition[0]); $col++) :
                                                $nama_kolom = date("M-y", strtotime($keys[$col]));
                                            ?>
                                             <th class=""><?= str_replace('_', ' ', $nama_kolom); ?></th>
                                         <?php endfor; ?>
                                         <!-- <th>target (times)</th> -->
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php foreach ($csindexTransition as $row) : ?>
                                         <tr>
                                         <?php
                                            // echo "<td>" . $row['agent'] . "</td>";
                                            // for ($baris = count($csindexTransition[0]) - 1; $baris > 0; $baris--) :
                                            for ($baris = 0; $baris < count($csindexTransition[0]); $baris++) :
                                                $baris_data = $keys[$baris];
                                                $row[$baris_data] == null ? $cell_value = '-' : $cell_value = $row[$baris_data];
                                                is_numeric($cell_value) ? $cell = '<td  align="center">' . round($cell_value, 3) * 100 . '%' . '</td>' : $cell = '<td>' . str_replace(',', '.', $cell_value) . '</td>';
                                                echo $cell;
                                            endfor;
                                            // echo '<td class="text-center">?</td></tr>';
                                        endforeach; ?>
                                 </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
 </div>
 <!-- /.container-fluid -->
 </section>
 <!-- /.content -->
 </div>
 <!-- /.content-wrapper -->

 <script>
     //deklarasi chartjs untuk membuat grafik 2d di id mychart 
 </script>