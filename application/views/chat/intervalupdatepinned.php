<?php 
    require 'stylesheet.php';
?>

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