<?php // app/Views/appointments/index.php

// Extend the AdminLTE layout (replace with your actual layout path if different)
echo $this->extend('layouts/backoffice/main');

echo $this->section('content');
?>
<section class="content">
    <div class="col">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header">
                    <!-- <h3 class="card-title">Quick Example</h3> -->
                </div>
                <form id="generalForm" method="post">
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="namaLengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" placeholder="Masukkan Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label for="jenisKelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenisKelamin" name="jenisKelamin">
                                <option value="" selected disabled>-- Jenis Kelamin --</option>
                                <option value="male">Laki - Laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="" selected disabled>-- Role --</option>
                                <option value="doctor">Dokter</option>
                                <option value="therapist">Terapis</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nomorWhatsapp">Nomor Whatsapp</label>
                            <input type="text" class="form-control" id="nomorWhatsapp" name="nomorWhatsapp" placeholder="ex: 6285141231918">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts') ?>

<script src="<?= base_url("template/plugins/jquery-validation/jquery.validate.min.js") ?>"></script>
<script src="<?= base_url("template/plugins/jquery-validation/additional-methods.min.js") ?>"></script>
<script>
    $(function() {
        // $.validator.setDefaults({
        //     submitHandler: function() {
        //         alert("Form successful submitted!");
        //     }
        // });
        $('#generalForm').validate({
            rules: {
                namaLengkap: {
                    required: true,
                },
                jenisKelamin: {
                    required: true,
                },
                role: {
                    required: true
                },
                nomorWhatsapp: {
                    required: true,
                    number: true,
                    minlength: 10,
                },
            },
            messages: {
                namaLengkap: {
                    required: 'Mohon Masukkan Nama Lengkap',
                },
                jenisKelamin: {
                    required: 'Mohon Masukkan Jenis Kelamin',
                },
                role: {
                    required: 'Mohon Masukkan Role',
                },
                nomorWhatsapp: {
                    required: 'Mohon Masukkan Nomor Whatsapp',
                    number: 'Mohon Masukkan Nomor Whatsapp yang Valid',
                    minlength: 'Nomor Whatsapp Minimal 10 Karakter',
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
<?php echo $this->endSection(); ?>