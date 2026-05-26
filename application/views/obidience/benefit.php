<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid px-1 pt-2">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <?php 
              $allowedUser = ['1', '5', '9'];
              function toStringDate($date){
                if(strtotime($date) < 0){
                  return '-';
                } else {
                  return date("d-M-Y h:i",strtotime($date));
                }
              }

              function hourToColor($val){
                if($val > 50) {
                  return 'bg-danger text-bold';
                } else if ($val > 35 && $val < 50) {
                  return 'text-danger text-bold';
                } else {
                  return 'text-primary';
                }
              }

              function trend($plan, $actual) {
                if ($plan > $actual) {
                  return '<i class="fas fa-arrow-down"></i>';
                } else if ($plan < $actual) {
                  return '<i class="fas fa-arrow-up text-danger"></i>';
                } else {
                  return '<i class="fas fa-check text-success"></i>';
                }
              }
            ?>
            <div class="card">
                <div class="card-header bg-primary">
                    Count/Simulation of Overtime Salary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="" id="formSelectPeriodBenefitSimulatin" method="POST">
                                <div class="form-group row">
                                    <label for="benefitSimulationDateStart" class="col-sm-2 col-form-label">Period</label>
                                    <div class="col-sm-3">
                                        <input type="date" id="benefitSimulationDateStart" name="benefitSimulationDateStart" class="form-control" value="<?= $startPeriod?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="date" id="benefitSimulationDateEnd" name="benefitSimulationDateEnd" class="form-control" value="<?= $endPeriod?>">
                                    </div>
                                    <div class="col-sm">
                                        <button type="submit" class="btn btn-outline-primary" id="buttonBenefitSimulationLoad" name="buttonBenefitSimulationLoad">Load data</button>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group row">
                                <label for="benefitSimulationPersonalWage" class="col-sm-2 col-form-label">Basic Salary</label>
                                <div class="col-sm-6">
                                    <input type="text" id="benefitSimulationPersonalWage" name="benefitSimulationPersonalWage" class="form-control" placeholder="Masukkan gaji pokok disini">
                                </div>
                                <div class="col-sm">
                                    <button type="button" class="btn btn-outline-primary" id="buttonBenefitSimulationCalculate">Calculate</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border pt-2 pr-2" style="font-size: 14px">
                                <ol class="text-info">
                                    <li>Pilih periode cut off, klik [Load Data]</li>
                                    <li>Masukkan gaji pokok, klik [Calculate]</li>
                                    <li>Jika tidak muncul, clear cache & history</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                <div class="row mt-3">
                    <div class="col">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="align-middle">#</th>
                                    <th class="align-middle">Date</th>
                                    <th class="align-middle">Time Start</th>
                                    <th class="align-middle">Time End</th>
                                    <th class="align-middle">Duration<br>(hour)</th>
                                    <th class="align-middle">Calc.</th>
                                    <th class="align-middle">OT Salary</th>
                                    <th class="align-middle">Meal</th>
                                    <th class="align-middle">Transport</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach($actualOvertime as $row) : ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= date("d-M-Y", strtotime($row['date'])) ?></td>
                                        <td><?= date("H:i", strtotime($row['actual_start'])) ?></td>
                                        <td><?= date("H:i", strtotime($row['actual_end'])) ?></td>
                                        <td><?= number_format($row['actual_duration'], 2) ?></td>
                                        <td class="durationCalculated" id="<?= 'calculated' . ($i - 1) ?>"><?= number_format($row['calculated'], 2) ?></td>
                                        <td class="overtimeFeeContainer" id="<?= 'feeContainer' . ($i - 1) ?>"><?= number_format(0,0) ?></td>
                                        <td><?= number_format($row['meal'], 0) ?></td>
                                        <td><?= number_format($row['transport'], 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="text-bold bg-light">
                                    <td colspan="4" class="text-center">Total</td>
                                    <td><?= $subtotal['totalDuration'] ?></td>
                                    <td id="subtotalCalculated"><?= $subtotal['totalCalculated'] ?></td>
                                    <td id="subtotalOvertimeFee"></td>
                                    <td id="subtotalMeal"><?= number_format($subtotal['totalMeal'], 0) ?></td>
                                    <td id="subtotalTransport"><?= number_format($subtotal['totalTransport'], 0) ?></td>
                                </tr>
                            </tbody>
                        </table>                         
                    </div>                          
                </div>
                <div class="row mt-5">
                    <div class="col">
                        <button class="btn badge badge-pill badge-primary mb-3">Salary simulation</button>
                        <div class="form-group row">
                            <label for="benefitSimulationSummaryBasic" class="col-sm-2 col-form-label">Basic salary</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationSummaryBasic" name="benefitSimulationSummaryBasic" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationTransport" class="col-sm-2 col-form-label">Transport allow.</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationTransport" name="benefitSimulationTransport" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationMeal" class="col-sm-2 col-form-label">Meal allow.</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationMeal" name="benefitSimulationMeal" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationOvertimeFee" class="col-sm-2 col-form-label">Uang lembur</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationOvertimeFee" name="benefitSimulationOvertimeFee" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationOvertimeMeal" class="col-sm-2 col-form-label">Meal lembur</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationOvertimeMeal" name="benefitSimulationOvertimeMeal" class="form-control" value="<?= number_format($subtotal['totalMeal'], 0) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationOvertimeTransport" class="col-sm-2 col-form-label">Transport lembur</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationOvertimeTransport" name="benefitSimulationOvertimeTransport" class="form-control" value="<?= number_format($subtotal['totalTransport'], 0) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="benefitSimulationGrandtotal" class="col-sm-2 col-form-label">TOTAL</label>
                            <div class="col-sm-2">
                                <input type="" id="benefitSimulationGrandtotal" name="benefitSimulationGrandtotal" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
