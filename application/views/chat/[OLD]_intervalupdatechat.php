<?php 
    require 'stylesheet.php';
?>

<?php foreach ($allchats as $row) : ?>
    <?php if($row['userid'] == $this->session->userdata('user_id')) : ?>
        <div class="direct-chat-msg right float-right mt-2" style="max-width: 80%; min-width: 45%" id="chat<?= $row['id'] ?>">
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
            <img class="direct-chat-img" src="<?= base_url('assets/img/profile/') . $row['photo'] ?>">
            <div class="direct-chat-text bg-sender">
                <?= repliedToStringSender($row['replied_to'], $row['replied_to_userid'], $row['replied_to_message']) ?>
                <?= stickyNote($row['is_sticky']) ?><?= $row['message'] ?>
            </div>
        </div>
        <div class="row" style="width: 65%">
            <div class="col-sm"><span style="display: none;" id="note<?= $row['id'] ?>"><?= $row['note_sticky'] ?></span>
            </div>
        </div>
    <?php else : ?>
        <div class="direct-chat-msg mt-3 float-left" style="max-width: 80%; min-width: 60%;" id="chat<?= $row['id'] ?>">
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
            <img class="direct-chat-img" src="<?= base_url('assets/img/profile/') . $row['photo'] ?>">
            <div class="direct-chat-text bg-receiver">
                <?= repliedToStringOthers($row['replied_to'], $row['replied_to_userid'], $row['replied_to_message']) ?>
                <?= stickyNote($row['is_sticky']) ?><?= $row['message'] ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>