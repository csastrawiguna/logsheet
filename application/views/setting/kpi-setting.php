<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col">
                    <form action="" class="form-row mt-3 mb-4" method="post" style="width: 480px;">
                        <label for="kpiTargetSelectFiscal" class="col-sm-2">Fiscal</label>
                        <div class="col-sm-6">
                            <select type="" id="kpiTargetSelectFiscal" name="kpiTargetSelectFiscal" class="custom-select">
                                <option value="<?= $latestFiscal ?>" selected><?= $latestFiscal ?></option>
                                <?php foreach($allFiscals as $fiscal): ?>
                                    <option value="<?= $fiscal['fiscal'] ?>"><?= $fiscal['fiscal'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="row ml-0">
                                <button type="submit" class="btn btn-outline-primary" id="kpiTargetSubmitSelectFiscal" name="kpiTargetSubmitSelectFiscal">Go</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">                
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-primary">
                            Target setting for agent's KPI
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-outline-primary" id="buttonAddKpiTarget"><i class="fas fa-plus-circle"></i> Add new</button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">                            
                            <div class="row">
                                <div class="col-8">
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Customer Assistant</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc10') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Customer Assistant (6 - 12 months)</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc12') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Customer Assistant (<6 months)</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc11') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Product Assistant</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc20') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Spare Part Specialist</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc30') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Spare Part Specialist Plus</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc40') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Complaint Specialist</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-6">Item</td>
                                                        <td class="col-3">Weight (%)</td>
                                                        <td class="col-3">Target</td>
                                                    </tr>
                                                    <?php foreach ($allTargets as $data) : ?>
                                                        <?php if ($data['jobcode'] == 'cs-ccc-cc50') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['description'];?></td>
                                                                <td>
                                                                    <input type="" name="" value="<?= $data['weight'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="number" name="" value="<?= $data['target'];?>" class="form-control">
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-primary">
                            KPI Measurement
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-outline-primary" id="buttonAddKpiMeasurement"><i class="fas fa-plus-circle"></i> Add new</button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">                                    
                                    <!-- new -->
                                    <div class="card card-light collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title text-primary">Customer Assistant</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table class="col-12 table table-borderless table-sm">
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="col-2">Item</td>
                                                        <td class="col-4">Description</td>
                                                        <td class="col-2">Min</td>
                                                        <td class="col-2">Max</td>
                                                        <td class="col-2">Criteria (%)</td>
                                                        <td class="col-2">...</td>
                                                    </tr>
                                                    <?php foreach ($allKpiMeasurement as $data) : ?>
                                                        <?php if ($data['jobcode'] === 'cs-ccc-cc10') : ?>
                                                            <tr>
                                                                <input type="hidden" name="" value="<?= $data['id'];?>">
                                                                <td><?= $data['kpi_item'];?></td>
                                                                <td><?= $data['kpi_description'];?></td>
                                                                <td>
                                                                    <input type="" name="" class="form-control" value="<?= $data['range_min'];?>" class="text-center form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="" name="" class="form-control"  value="<?= $data['range_max'];?>" class="form-control">
                                                                </td>
                                                                <td class="text-right">
                                                                    <input type="" name="" class="form-control"  value="<?= $data['criteria'];?>" class="form-control">
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-outline-danger deleteKpiMeasurement" data-id="<?= $data['id']; ?>">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->