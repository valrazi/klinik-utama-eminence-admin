<?= $this->extend('layouts/backoffice/main') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="container-fluid">
        <div class="card">

            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Jadwal Dokter: <?= esc($doctor['name']) ?></h3>
                <a href="<?= base_url("backoffice/doctors/schedule/" . $doctor['id'] . "/create") ?>" class="btn btn-primary btn-sm">
                    Tambah Jadwal Dokter
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <table id="scheduleTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Ketersediaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($schedules)): ?>
                            <?php foreach ($schedules as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= esc($s['schedule_date']) ?></td>
                                    <td><?= esc($s['start_time']) ?></td>
                                    <td><?= esc($s['end_time']) ?></td>
                                    <td>
                                        <?= $s['is_available'] ? '<span class="badge badge-success">Tersedia</span>' : '<span class="badge badge-danger">Tidak Tersedia</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada jadwal</td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url("template/plugins/jquery-validation/jquery.validate.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/jquery-validation/additional-methods.min.js") ?>"></script>

<!-- DataTables  & Plugins -->
<script src="<?= base_url("template/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-responsive/js/dataTables.responsive.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-buttons/js/dataTables.buttons.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/jszip/jszip.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/pdfmake/pdfmake.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/pdfmake/vfs_fonts.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-buttons/js/buttons.html5.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-buttons/js/buttons.print.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/datatables-buttons/js/buttons.colVis.min.js") ?>"></script>

<script>
    $(function() {
        $("#scheduleTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#scheduleTable_wrapper .col-md-6:eq(0)');
    });
</script>
<?= $this->endSection() ?>