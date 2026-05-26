<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-2 px-1">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php                
            $allowSticky = ['1', '2', '4', '5', '6', '9'];
            $allowSetting = ['1', '5', '9'];

        ?>
       
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-purple">
                    Search chat message : <span class="text-bold"><?= $messageClue ?></span> <em>(1 tahun ke belakang)</em>
                    <div class="card-tools">
                        <a href="#" data-toggle="modal" data-target="#modalSearchMessage" class="text-white mr-3"><i class="fas fa-search"></i> Cari lagi</a>
                        <a href="<?= base_url('chat') ?>" class="mr-3 text-white"><i class="fas fa-arrow-left"></i> Back to chat</a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-purple">Message found : <?= count($grabbedMessage) ?></p>
                    <table class="table table-hover" id="tableChatSearchResult">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Datetime</th>
                                <th>Sender</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($grabbedMessage as $row) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= date("d-M-Y H:i", strtotime($row['datetime'])) ?></td>
                                    <td><?= $row['userid'] ?></td>
                                    <td><?= $row['message'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalSearchMessage" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSearchMessageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSearchMessageLabel">Search Chat Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="searchMessageClue" class="form-control-label">Message clue:</label>
                        <input class="form-control" id="searchMessageClue" name="searchMessageClue" placeholder="Masukkan clue atau kata kunci">
                    </div>
                    <div class="row mt-3">                
                        <div class="col-sm">
                            <button type="submit" class="btn btn-primary float-right" name="searchMessageSubmit" id="searchMessageSubmit">Search message</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>