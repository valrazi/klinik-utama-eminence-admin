<?= $this->extend('layouts/backoffice/main') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="col">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Jadwal - <?= esc($doctor['name']) ?></h3>
                </div>

                <form method="post" action="<?= base_url("backoffice/doctors/schedule/" . $doctor['id'] . "/store") ?>">
                    <div class="card-body">
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= esc(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="schedule_date">Tanggal</label>
                            <input type="date" class="form-control" id="schedule_date" name="schedule_date" required>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Jam Mulai</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time">Jam Selesai</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="<?= base_url("backoffice/doctors/jadwal/" . $doctor['id']) ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>