<?= $this->extend('layouts/backoffice/main') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= esc($title) ?></h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-warning">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="generalForm" method="post" action="<?= base_url('backoffice/doctors/update/' . $doctor['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= old('name', $doctor['name']) ?>">
                    </div>
                    <div class="form-group">
                        <label>No Whatsapp</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="<?= old('whatsapp_number', $doctor['whatsapp_number']) ?>">
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= base_url('backoffice/doctors') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
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
                name: {
                    required: true,
                },
                whatsapp_number: {
                    required: true,
                    number: true,
                    minlength: 10,
                },
            },
            messages: {
                name: {
                    required: 'Mohon Masukkan Nama Lengkap',
                },
                whatsapp_number: {
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
