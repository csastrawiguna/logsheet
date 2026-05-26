<?php 
    require 'stylesheet.php';
?>

<?php foreach ($allchats as $row) : ?>
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
                    <button class="btn btn-sm btn-block <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['background'] ?> <?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['disabled'] ?>  btnSubmitVoulenteer" data-id="<?= $row['id'] ?>" data-remain="<?= $sisa ?>"><?= checkUserInVoulenteerList($this->session->userdata('user_id'), $row['message'], $sisa)['caption'] ?></button>
                <?php endif; ?>
            </div>

        </div>
    <?php endif; ?>
<?php endforeach; ?>
