<div class="content-wrapper">
  <section class="content pt-2 px-1">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header bg-primary">
          <span class="card-title">All Active Users</span>
          <div class="card-tools mr-2">
            <a href="<?= base_url('usermanagement/index') ?>" class="text-white mr-3"><i class="fas fa-arrow-circle-left"></i> Back</a>
            <a href="<?= base_url('usermanagement/exportActiveUser') ?>" class="text-white mr-3"><i class="fas fa-download"></i> Active user to Excel</a>
            <a href="<?= base_url('usermanagement/exportWholeUser') ?>" class="text-white"><i class="fas fa-download"></i> Whole users to Excel</a>
          </div>
        </div>
        
        <div class="card-body">
          <div class="d-flex flex-wrap">
            <?php foreach ($allUsers as $row) : ?>
              <div class="mb-4">
                <img class="img img-circle float-left mx-2 my-1" width="170" src="<?= base_url() . '/assets/img/profile/' . $row['photo'] ?>">
                <br>
                <p class="text-center lead mb-0"><?= ucwords($row['user_id']) ?></p>
                <p class="text-center text-muted mb-0"><?= ucwords($row['fullname']) ?></p>
                <p class="text-center text-muted mb-0"><?= $row['npk'] ?></p>
                <p class="text-center text-muted mb-0">Born: <?= date("d-M-Y", strtotime($row['birthdate'])) ?></p>
                <p class="text-center text-muted">Join: <?= date("d-M-Y", strtotime($row['joindate'])) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>