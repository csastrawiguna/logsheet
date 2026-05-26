<!--Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2">
        <div class="container-fluid p-0">
            <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
            <!-- /.row -->
            <div class="card">
                <div class="card-header bg-danger">
                    Overtime Schedule <span class="text-bold">Jika Idul Fitri jatuh pada <span style="color:yellow">21 April 2023</span></span>
                </div>
                <div class="card-body">
                    <div class="mb-5" style="display: none;">
                        <div class="alert alert-danger" role="alert">
                            <p class="lead">HARAP DIPERHATIKAN!<br>
                            Efektif Selasa, tanggal 16 Maret 2021 tolong lembur sesuai dengan jadwal <span class="text-bold text-warning">(termasuk jam lembur di Sabtu).</span> Lembur diganti jika hanya mendesak atau urgent saja.<br>
                            Untuk yang diganti atau menggantikan lembur <span class="text-bold">wajib</span> lapor dan dengan persetujuan saya.<br>
                            </p>
                            <code class="text-warning">
                                Terima kasih<br>
                                Cuparsa
                            </code>
                        </div>
                    </div>
                    <!-- <p class="lead text-secondary" id="textSlankOvertimeSchedule">Pengen lihat jadwal lembur ya... <i class="far fa-grin-tongue"></i></p>
                    <button class="btn btn-sm btn-outline-warning" id="buttonShowOvertimeSchedule">Show me up</button> -->
                    <table class="table" id="tabelOvertimeSchedule" style="">
                        <thead>
                            <tr class="border-bottom border-top">
                                <th>Tgl</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th colspan="8">Agent</th>
                            </tr>
                        </thead>
                        <tbody>                                                                                                                
                            <tr><td rowspan="2">20 Apr</td><td rowspan="2">Kamis</td><td rowspan="2">08.00 - 16.00</td><td>Lupus</td><td>Helmy</td><td>Indra</td><td>Aliahmad</td><td>Rahma</td><td>Abizar</td><td>Pras</td><td>Subehan</td></tr>
                            <tr><td>Malik</td><td>Tegar</td><td>Dinty</td><td>Agam</td><td></td><td></td><td></td><td></td></tr>
                            <tr style="background-color: rgba(165,220,105,0.2)"><td>21 Apr</td><td>Jumat</td><td>08:00 - 12:00</td><td>Stenly</td><td>Dina</td><td>Franscisco</td><td>Mariana</td><td>Parlin</td><td></td><td></td><td></td></tr>
                            <tr style="background-color: rgba(165,220,105,0.2)"><td>22 Apr</td><td>Sabtu</td><td>08:00 - 12:00</td><td>Stenly</td><td>Dina</td><td>Franscisco</td><td>Mariana</td><td>Parlin</td><td></td><td></td><td></td></tr>
                            <tr class="bg-light"><td>23 Apr</td><td>Minggu</td><td>08:00 - 15:00</td><td>Stenly</td><td>Dinda</td><td>Dina</td><td>Franscisco</td><td>Mariana</td><td>Parlin</td><td></td><td></td></tr>
                            <tr><td rowspan="2">24 Apr</td><td rowspan="2">Senin</td><td rowspan="2">08:00 - 16:00</td><td>Stenly</td><td>Dinda</td><td>Dina</td><td>Mariana</td><td>Dinar</td><td>Fahmi</td><td>Parlin</td><td>Franscisco</td></tr>
                            <tr><td>Aguss</td><td>Augita</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            <tr><td rowspan="2">25 Apr</td><td rowspan="2">Selasa</td><td rowspan="2">08.00 - 16.00</td><td>Fauzi</td><td>Dinda</td><td>Aliahmad</td><td>Zardi</td><td>Okti</td><td>Ayunda</td><td>Firas</td><td>Dinar</td></tr>
                            <tr class="border-bottom"><td>Fahmi</td><td>Aguss</td><td>Augita</td><td>Puji</td><td>Indra</td><td>Ibrahim</td><td class="text-purple">Parlin*</td><td></td></tr>
                        </tbody>
                    </table>
                    <p class="text-purple mt-3">*) <small>= standby on call (cowok panggilan)</small></p>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper