<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="flashmessage" style="display: none;"><?= $this->session->flashdata('message'); ?></div>
        <?php 
            require 'stylesheet.php';
         ?>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-info">
                    Chat: <?= date("d F Y") ?> sampai <?= $this->db->get_where('chat_setting', ['item' => 'day_chat_existing'])->row_array()['value'] ?> hari terakhir <em class="text-warning">(kalau masih ada disini)</em>
                    <div class="card-tools">
                        <?php if (in_array($this->session->userdata('role_access'), $allowSetting)) : ?>
                            <a href="<?= base_url('chat/setting') ?>" class="text-white mr-3"><i class="fas fa-cog"></i> Config</a>
                        <?php endif; ?>
                        <a href="<?= base_url('chat/template') ?>"class="text-white mr-3"><i class="fas fa-layer-group"></i> Templates</a>
                        <a href="#" data-toggle="modal" data-target="#modalSearchMessage" class="text-white mr-3"><i class="fas fa-search"></i> Cari chat</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-9 rounded">
                            <div class="row">
                                <div class="col">
                                    <div class="px-3 direct-chat-messages" id="container-table-chat" style="min-height: 80%">
                                        <?php foreach ($allChat as $row) : ?>
                                            <?php 
                                                $is_admin = in_array($this->session->userdata('role_access'), $allowSticky); 
                                                $is_me = ($row['userid'] == $this->session->userdata('user_id')); 
                                                $current_user = $this->session->userdata('user_id'); // Asumsi session nami aya
                                            ?>

                                            <?php if($is_me) : ?>
                                                <div class="direct-chat-msg right float-right mt-2" style="max-width: 80%; min-width: 51%" id="chat<?= $row['id'] ?>">
                                                    <div class="direct-chat-infos clearfix">
                                                        <span class="direct-chat-timestamp float-left"><?= timeStringer($row['datetime']) ?></span>
                                                        <a href="#" data-target="#modalEditMessage" data-toggle="modal" class="btnEditChatMessage text-secondary ml-2" data-id="<?= $row['id'] ?>" data-issticky="<?= $row['is_sticky'] ?>"><i class="fas fa-edit"></i></a>
                                                        <?= tag2Style($row['tagged_by'], $this->session->userdata('user_id'), $row['id']) ?>
                                                        <div class="pretty p-icon p-toggle p-plain repliedtoButton"  style="opacity: 0.5; <?= tag2ReplyButton($row['tagged_by'], $row['userid'], $this->session->userdata('user_id')) ?>">
                                                            <input type="radio" name="repliedtoId" id="" value="<?= $row['id'] ?>" class="disabled">
                                                            <div class="state p-off">
                                                                <i class="icon fas fa-reply "></i>
                                                                <label></label>
                                                            </div>
                                                            <div class="state p-on p-info-o">
                                                                <i class="icon fas fa-reply"></i>
                                                                <label></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <img class="direct-chat-img" src="<?= base_url('assets/img/profile/') . $row['photo'] ?>"> -->
                                                    <div class="circle direct-chat-img text-center" style="background-color: <?= useridToInitial($row['userid'])['bgColor'] ?>; color: <?= useridToInitial($row['userid'])['textColor'] ?>; font-size: 1.1rem;line-height: 220%;"><?= $row['initial'] ?></div>
                                                    <div class="direct-chat-text bg-sender">
                                                        <?= repliedToStringSender($row['replied_to'], $row['replied_to_userid'], $row['replied_to_message']) ?>
                                                        <?= stickyNote($row['is_sticky']) ?>
                                                        <?= checkAdmin($row['message'], $is_admin, $row['id']) ?>
                                                        
                                                        <?php if($row['quota_limit'] > 0) : ?>
                                                            <div class="border-top mt-2 pt-1">
                                                                <small class="font-weight-bold">Monitoring Antrian (Limit: <?= $row['quota_limit'] ?>)</small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else : ?>
                                                <div class="direct-chat-msg mt-3 float-left" style="max-width: 80%; min-width: 55%;" id="chat<?= $row['id'] ?>">
                                                    <div class="direct-chat-infos clearfix">
                                                        <span class="direct-chat-name float-left"><?= $row['userid'] ?></span>
                                                        <span class="direct-chat-timestamp float-right">
                                                        <?= tag2Style($row['tagged_by'], $this->session->userdata('user_id'), $row['id']) ?>
                                                        <div class="pretty p-icon p-toggle p-plain mr-0 repliedtoButton" style="opacity: 0.5; <?= tag2ReplyButton($row['tagged_by'], $row['userid'], $this->session->userdata('user_id')) ?>">
                                                            <input type="radio" name="repliedtoId" id="" value="<?= $row['id'] ?>" class="disabled">
                                                                <div class="state p-off">
                                                                    <i class="icon fas fa-reply "></i>
                                                                    <label></label>
                                                                </div>
                                                                <div class="state p-on p-info-o">
                                                                    <i class="icon fas fa-reply"></i>
                                                                    <label></label>
                                                                </div>
                                                            </div>
                                                            <?= timeStringer($row['datetime']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="circle direct-chat-img text-center" style="background-color: <?= useridToInitial($row['userid'])['bgColor'] ?>; color: <?= useridToInitial($row['userid'])['textColor'] ?>; font-size: 1.1rem;line-height: 220%;"><?= $row['initial'] ?></div>
                                                    <div class="direct-chat-text bg-receiver">
                                                        <?= repliedToStringOthers($row['replied_to'], $row['replied_to_userid'], $row['replied_to_message']) ?>
                                                        <?= stickyNote($row['is_sticky']) ?><?= $row['message'] ?>
                                                        <?php if($row['quota_limit'] > 0) : ?>
                                                            <hr class="my-2" style="border-top: 1px solid rgba(0,0,0,.1)">
                                                            <?php 
                                                                $count_li = substr_count($row['message'], '<li>');
                                                                $sisa = $row['quota_limit'] - $count_li;
                                                                $sudah_daftar = strpos($row['message'], "<li>$current_user</li>") !== false;
                                                            ?>
                                                            <small>Sisa kuota: <?= $sisa ?> lagi</small>
                                                            <button class="btn btn-sm btn-block 
                                                                <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['background'] ?> 
                                                                <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['class'] ?> 
                                                                <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['disabled'] ?>" 
                                                                data-id="<?= $row['id'] ?>" 
                                                                data-remain="<?= $sisa ?>">
                                                                <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['caption'] ?>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="">
                                <input type="hidden" id="postMessageTaggedUserId" value="">
                                <div class="row mt-1">
                                    <div class="col-sm">
                                        <textarea class="form-control" name="formChatMesage" id="formChatMesage"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-3 d-flex align-items-center justify-content-between">
                                    
                                    <?php if (in_array($this->session->userdata('role_access'), $allowSticky)) : ?>
                                        <div class="col-sm-1">
                                            <div class="pretty p-svg p-curve">
                                                <input type="checkbox" class="" id="postMessageIssticky" name="postMessageIssticky" value="1">
                                                <div class="state p-primary">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Pinned?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 float-left">
                                            <input class="form-control" name="postMessageStickyNote" id="postMessageStickyNote" placeholder="Judul Sticky Message (makx. 24 chars)">
                                        </div>
                                        <!-- <div class="col-sm-1 "></div> -->
                                    <?php endif; ?>
                                    <div class="col-sm-1 " style="display: none;">
                                        <input type="hidden" class="form-control" id="postMessageRepliedto" name="postMessageRepliedto" value="NULL" style="max-width: 60px;">
                                    </div>
                                    <?php if (in_array($this->session->userdata('role_access'), $allowSticky)) : ?>
                                        <div class="col-sm-1 float-right ">
                                            <div class="pretty p-svg p-curve">
                                                <input type="checkbox" class="" id="postMessageIsTagged" name="postMessageIsTagged" value="TRUE">
                                                <div class="state p-primary">
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label>Tag?</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (in_array($this->session->userdata('role_access'), $allowSetting)) : ?>
                                            <div class="col-sm-3 form-inline float-right">
                                                <input type="number" class="form-control mb-2 mr-sm-2" id="postMessageQuotaLimit" name="postMessageQuotaLimit" style="max-width: 80px;" value="0">
                                                <label for="postMessageQuotaLimit" class="font-weight-normal mr-2">List</label>
                                            </div>
                                        <?php endif ?>
                                    <?php endif; ?>
                                    <div class="col-sm-2 float-right">
                                        <button type="button" name="buttonChatSubmit" id="buttonChatSubmit" class="btn btn-block btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <p class="h6 badge badge-pill badge-primary" style="font-weight: normal; font-size: 14px; line-height: 16px;">Pinned Message</p>
                            <br>
                            <div id="pinnedList" class="px-2 py-1">
                                <?php foreach($allSticky as $row) : ?>
                                    <p class="border-top">
                                        <b class="text-secondary"><?= $row['userid'] ?></b> <small class="">(<?= timeStringer($row['datetime']) ?>)</small>
                                        <a href="#chat<?= $row['id'] ?>" class="float-right"><i class="fas fa-search"></i></a>
                                        <?php if ($this->session->userdata('user_id') == $row['userid']) : ?>
                                            <a href="#" data-id="<?= $row['id'] ?>" class="float-right mr-1 text-danger dismissPinMessage"><i class="fas fa-times"></i></a>
                                        <?php endif; ?>
                                        <br>
                                        <?php if (strlen(trim($row['note_sticky'])) < 2) : ?>
                                            <?= substr(trim($row['message']), 0, 20) ?>...
                                        <?php else : ?>
                                            <?= trim($row['note_sticky']) ?>
                                        <?php endif; ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalEditMessage" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalEditMessageLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="modalEditMessageLabel">Edit Chat Message</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <form method="POST" action="<?= base_url('chat/update') ?>">
        <div class="modal-body">
            <input type="hidden" class="form-control" name="editMessageId" id="editMessageId" readonly>
            <div class="form-group">
                <div class="">
                    <textarea class="form-control" id="editMessageDetail" name="editMessageDetail"></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-2">
                    <?php if (in_array($this->session->userdata('role_access'), $allowSticky)) : ?>
                        <div class="pretty p-svg p-curve">
                            <input type="checkbox" class="" id="editMessageIssticky" name="editMessageIssticky" value="1">
                            <div class="state p-primary">
                                <!-- svg path -->
                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                </svg>
                                <label>Pinned?</label>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <div class="col-sm-6">
                    <?php if(in_array($this->session->userdata('role_access'), $allowSticky)): ?>
                        <input class="form-control" name="editMessageStickyNote" id="editMessageStickyNote" placeholder="Judul Sticky Message (makx. 24 chars)">
                    <?php endif; ?>
                </div>
                
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary float-right" name="editMessageSubmit" id="editMessageSubmit">Update message</button>
                    <button type="button" class="btn btn-secondary float-right mr-1" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
      </form>
    </div>
  </div>
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
            <form method="POST" action="<?= base_url('chat/searchmessage') ?>">
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

<script type="text/javascript">
    function chatAutoLoad() {
        const url1 = 'chat/allchat';
        const xhr1 = new XMLHttpRequest();
        xhr1.onload = function() {
            document.getElementById("container-table-chat").innerHTML = this.responseText;
        }
        xhr1.open("GET", url1);
        xhr1.send();


        const url2 = 'chat/allpinned';
        const xhr2 = new XMLHttpRequest();
        xhr2.onload = function() {
            document.getElementById("pinnedList").innerHTML = this.responseText;
        }
        xhr2.open("GET", url2);
        xhr2.send();
    }

    setInterval(function() {
        chatAutoLoad();
        // pinAutoLoad();
    }, 10000);

    const ChatConfig = {
        baseUrl: '<?= base_url() ?>',
        userId: '<?= $this->session->userdata('user_id') ?>',
        userName: '<?= $this->session->userdata('user_id') ?>',
        lastDatetime: '<?= $allChat[0]['datetime'] ?? 0 ?>',
        lastId: '<?= $allChat[0]['id'] ?? 0 ?>'
    }
</script>
