<?php // app/Views/appointments/index.php

// Extend the AdminLTE layout (replace with your actual layout path if different)
echo $this->extend('layouts/backoffice/main');

echo $this->section('content');
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Dokter</h3>
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

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Nomor Whatsapp</th>
                                    <th>Status</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($listDoctor)): ?>
                                    <?php foreach ($listDoctor as $i => $therapist): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($therapist['name']) ?></td>
                                            <td>
                                                <?= $therapist['gender'] === 'male' ? 'Laki - Laki' : 'Perempuan' ?>
                                            </td>
                                            <td><?= esc($therapist['whatsapp_number']) ?></td>
                                            <td>
                                                <?= $therapist['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' ?>
                                            </td>
                                            <td>

                                                <a href="/backoffice/doctors/jadwal/<?= $therapist['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-calendar"></i></a>
                                                <a href="/backoffice/doctors/edit/<?= $therapist['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                <?php if ($therapist['is_active']) { ?>
                                                    <a href="javascript:void(0)"
                                                        onclick="deactivateDoctor(<?= $therapist['id'] ?>)"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="javascript:void(0)"
                                                        onclick="activateDoctor(<?= $therapist['id'] ?>)"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php } ?>


                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada dokter</td>
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
<?php echo $this->endSection(); ?>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function deactivateDoctor(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Dokter akan dinonaktifkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, nonaktifkan'
        }).then((result) => {
            if (result.isConfirmed) {
                // submit hidden form via POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= base_url('backoffice/doctors/deactivate') ?>/${id}`;

                // CSRF token if enabled
                <?php if (csrf_token()): ?>
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '<?= csrf_token() ?>';
                    csrf.value = '<?= csrf_hash() ?>';
                    form.appendChild(csrf);
                <?php endif; ?>

                document.body.appendChild(form);
                form.submit();
            }
        })
    }

    function activateDoctor(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Dokter akan diaktifkan!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0ead11',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, aktifkan'
        }).then((result) => {
            if (result.isConfirmed) {
                // submit hidden form via POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= base_url('backoffice/doctors/activate') ?>/${id}`;

                // CSRF token if enabled
                <?php if (csrf_token()): ?>
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '<?= csrf_token() ?>';
                    csrf.value = '<?= csrf_hash() ?>';
                    form.appendChild(csrf);
                <?php endif; ?>

                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>

<?php echo $this->endSection(); ?>