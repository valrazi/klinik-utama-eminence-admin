<?= $this->extend('layouts/backoffice/main') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Reservasi</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="#example1_wrapper">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped mt-3" id="example1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Status Patient</th>
                                    <th>Whatsapp</th>
                                    <th>Layanan- Staff</th>
                                    <th>Schedule Date</th>
                                    <th>Start - End</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reservations)): ?>
                                    <?php foreach ($reservations as $res): ?>
                                        <tr>
                                            <td><?= esc($res['id']) ?></td>
                                            <td><?= esc($res['patient_name']) ?></td>
                                            <td><?= $res['patient_existing'] ? 'Pasien Lama' : 'Pasien Baru' ?></td>
                                            <td><?= esc($res['patient_whatsapp_number']) ?></td>
                                            <td><?= esc(strtoupper($res['service'])) . " - " .  esc($res['staff_name']) ?></td>
                                            <td><?= esc($res['schedule_date']) ?></td>
                                            <td><?= esc($res['start_time']) ?> - <?= esc($res['end_time']) ?></td>
                                            <td>
                                                <?php
                                                $statusClass = match ($res['status']) {
                                                    'confirmed'   => 'success',
                                                    'booked'      => 'secondary',
                                                    'rescheduled' => 'info',
                                                    'cancelled'   => 'warning',
                                                    default       => 'secondary'
                                                };
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>">
                                                    <?= esc(ucfirst($res['status'])) ?>
                                                </span>
                                            </td>

                                            <td><?= esc($res['created_at']) ?></td>
                                            <td>
                                                <?php
                                                if($res['status'] == 'booked') { ?>
                                                    <a href="<?= base_url('backoffice/reservations/' . $res['id'] . '/reschedule') ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-calendar"></i> Reschedule
                                                </a>
                                                <?php }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada reservasi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?php echo $this->section('scripts') ?>

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
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>



<?php echo $this->endSection(); ?>