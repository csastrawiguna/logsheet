<div class="card">
    <div class="card-header bg-purple">
        <span class="card-title">Antrian <?= $queueing[0]['description'] ?></span>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body row">
        <div class="col ml-4">
            <p class="h5 border-bottom mb-3 text-purple" style="width: 200px;"><i class="text-secondary fas fa-clipboard-list"></i> Belum Ikut Antri</p>
            <?php $a = 1; ?>
            <?php foreach($queueing as $row) : ?>
                <?php if ($row['status'] == 'blank') : ?>
                    <p class="mr-4" style="width: 180px;">
                        <span class="mr-1">(<?= $a++ ?>)</span>
                        <?= $row['agent'] ?>
                        <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('user_id') == $row['agent']) :?>
                            <span class="float-right">
                                <button class="btn badge badge-info badge-pill btnQueue" data-agent="<?= $row['agent'] ?>">Antri</button>
                            </span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <p class="h5 border-bottom mb-3 text-purple" style="width: 200px;"><i class="fas fa-fast-forward"></i> Antri/On Progress MCU</p>
            <?php $b = 1; ?>
            <?php foreach($queueing as $row) : ?>
                <?php if ($row['status'] == 'queueing') : ?>
                    <p class="mr-4" style="width: 200px;">
                        <span class="mr-1">(<?= $b++ ?>)</span>
                        <?= $row['agent'] ?>
                        <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('user_id') == $row['agent']) :?>
                            <span class="float-right">
                                <button class="btn badge badge-success badge-pill btnFinish" data-agent="<?= $row['agent'] ?>">Selesai</button>
                                <button class="btn badge badge-secondary badge-pill btnReset" data-agent="<?= $row['agent'] ?>">Reset</button>
                            </span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="col">
            <p class="h5 border-bottom mb-3 text-purple" style="width: 200px;"><i class="fas fa-check-circle text-success"></i> Sudah selesai MCU</p>
            <?php $b = 1; ?>
            <?php foreach($queueing as $row) : ?>
                <?php if ($row['status'] == 'finish') : ?>
                    <p class="mr-4" style="width: 210px;">
                        <span class="mr-1">(<?= $b++ ?>)</span>
                        <span class=""><?= $row['agent'] ?></span>
                        <?php if ($this->session->userdata('role_access') == '9' || $this->session->userdata('user_id') == $row['agent']) :?>
                            <span class="float-right">
                                <button class="btn badge badge-warning badge-pill btnQueueAgain" data-agent="<?= $row['agent'] ?>">Antri lagi</button>
                                <button class="btn badge badge-secondary badge-pill btnReset" data-agent="<?= $row['agent'] ?>">Reset</button>
                            </span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<p>Jadwal 12 - 15 Maret</p>

<table class="table table-sm table-bordered" style="width: 360px;">
    <thead>
        <tr>
            <th>No</th>
            <th>WA</th>
            <th>Call</th>
            <th>FU</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Augita</td>
            <td>Abizar</td>
            <td>Yunita</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Aliahmad</td>
            <td>Malik</td>
            <td>Eva</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Rahma</td>
            <td>Franscisco</td>
            <td>Lintang</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Ibrahim</td>
            <td>Parlin</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Elsa</td>
            <td>Zardi</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Ayunda</td>
            <td>Subehan</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Aguss</td>
            <td>Puji</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Tegar</td>
            <td>Okti</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Firas</td>
            <td>Mariana</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Arti</td>
            <td>Dina</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Aliyah</td>
            <td>Agam</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>12</td>
            <td>&nbsp;</td>
            <td>Tiwi</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>13</td>
            <td>&nbsp;</td>
            <td>Fahmi</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>

<p>&nbsp;</p>

<p>Jadwal 18 - 22 Maret</p>

<table class="table table-sm table-bordered" style="width: 360px;">
    <thead>
        <tr>
            <th>No</th>
            <th>WA</th>
            <th>Call</th>
            <th>FU</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Abizar</td>
            <td>Aguss</td>
            <td>Yunita</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Agam</td>
            <td>Aliahmad</td>
            <td>Eva</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Fahmi</td>
            <td>Arti</td>
            <td>Lintang</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Malik</td>
            <td>Augita</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Okti</td>
            <td>Ayunda</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Parlin</td>
            <td>Dina</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Puji</td>
            <td>Elsa</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Subehan</td>
            <td>Firas</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Tiwi</td>
            <td>Franscisco</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Zardi</td>
            <td>Ibrahim</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Aliyah</td>
            <td>Mariana</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>12</td>
            <td>&nbsp;</td>
            <td>Rahma</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>13</td>
            <td>&nbsp;</td>
            <td>Tegar</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>

<p>&nbsp;</p>

<p>Jadwal 25 - 28 Maret</p>

<table class="table table-sm table-bordered" style="width:360px;">
    <thead>
        <tr>
            <th>No</th>
            <th>WA</th>
            <th>Call</th>
            <th>FU</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Aguss</td>
            <td>Abizar</td>
            <td>Yunita</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Aliahmad</td>
            <td>Agam</td>
            <td>Eva</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Augita</td>
            <td>Aliyah</td>
            <td>Lintang</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Ayunda</td>
            <td>Arti</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Dina</td>
            <td>Fahmi</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Elsa</td>
            <td>Franscisco</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Firas</td>
            <td>Malik</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Ibrahim</td>
            <td>Okti</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Mariana</td>
            <td>Parlin</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Rahma</td>
            <td>Puji</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Tegar</td>
            <td>Subehan</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>12</td>
            <td>&nbsp;</td>
            <td>Tiwi</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>13</td>
            <td>&nbsp;</td>
            <td>Zardi</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>