<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3 px-1">
        <div class="container-fluid">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <!-- Summary by Category Tab -->
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-productCategory-tab" data-toggle="pill" href="#custom-tabs-one-productCategory" role="tab" aria-controls="custom-tabs-one-productCategory" aria-selected="false">Product Material</a>
                        </li>

                        <!-- Summary Result Tab -->
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-nonproductCategory-tab" data-toggle="pill" href="#custom-tabs-one-nonproductCategory" role="tab" aria-controls="custom-tabs-one-nonproductCategory" aria-selected="true">Non-Product Material</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <!-- Summary by Category Content -->
                        <div class="tab-pane fade show active" id="custom-tabs-one-productCategory" role="tabpanel" aria-labelledby="custom-tabs-one-productCategory-tab">
                            <table class="table" id="tableMaterialEducationProduct">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kategori</th>
                                        <th>Judul Materi</th>
                                        <th>Keterangan</th>
                                        <th>Link Materi</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($productMaterial as $row) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['category'] ?></td>
                                            <td><?= $row['material_title'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td>
                                                <a href="<?= base_url() . $row['material_link'] ?>" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Material
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 420px;">
                                                    <table class="table table-sm table-borderless table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Disimpan oleh</td>
                                                                <td class="">: <?= $row['saved_by']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Disimpan pada</td>
                                                                <td class="">: <?= $row['saved_at']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diperbaharui oleh</td>
                                                                <td class="">: <?= $row['updated_by']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diperbaharui pada</td>
                                                                <td class="">: <?= $row['updated_at']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Result Content -->
                        <div class="tab-pane fade" id="custom-tabs-one-nonproductCategory" role="tabpanel" aria-labelledby="custom-tabs-one-nonproductCategory-tab">
                            <table class="table" id="tableMaterialEducationNonproduct">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kategori</th>
                                        <th>Judul Materi</th>
                                        <th>Keterangan</th>
                                        <th>Link Materi</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach($softskillMaterial as $row) : ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['category'] ?></td>
                                            <td><?= $row['material_title'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td>
                                                <a href="<?= base_url() . $row['material_link'] ?>" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Material
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                <i class="fas fa-bars" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;"></i>
                                                <div class="dropdown-menu dropdown-menu-right p-2" style="min-width: 420px;">
                                                    <table class="table table-sm table-borderless table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td>Disimpan oleh</td>
                                                                <td class="">: <?= $row['saved_by']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Disimpan pada</td>
                                                                <td class="">: <?= $row['saved_at']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diperbaharui oleh</td>
                                                                <td class="">: <?= $row['updated_by']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diperbaharui pada</td>
                                                                <td class="">: <?= $row['updated_at']; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>