<?= $this->extend('layouts/backoffice/main') ?>

<?= $this->section('content') ?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Reschedule Reservation - <?= esc($reservation['patient_name']) ?></h3>
            </div>
            <form method="post" action="<?= base_url('backoffice/reservations/'.$reservation['id'].'/update-reschedule') ?>">
                <div class="card-body">
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="date">Tanggal Baru</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Pilih Waktu</label>
                        <select id="time" name="time" class="form-control" required>
                            <option value="">-- Pilih Tanggal Dulu --</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-info" type="submit">Reschedule</button>
                    <a href="<?= base_url('backoffice/reservations') ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('date').addEventListener('change', async function() {
        let date = this.value;
        let select = document.getElementById('time');
        select.innerHTML = '<option>Loading...</option>';

        let res = await fetch("<?= base_url('backoffice/reservations/'.$reservation['id'].'/get-schedules') ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'date=' + encodeURIComponent(date)
        });

        let data = await res.json();
        select.innerHTML = '';

        if (data.success && data.schedules.length > 0) {
            data.schedules.forEach(s => {
                let opt = document.createElement('option');
                opt.value = `${s.start_time}-${s.end_time}`;
                opt.textContent = `${s.start_time} - ${s.end_time}`;
                select.appendChild(opt);
            });
        } else {
            select.innerHTML = '<option value="">Tidak ada jadwal tersedia</option>';
        }
    });
</script>
<?= $this->endSection() ?>
