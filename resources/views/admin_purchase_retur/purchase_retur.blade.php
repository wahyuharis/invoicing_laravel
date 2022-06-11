<?php

// use App\Models\AdminPurchaseModel;

// $purchase_model = new AdminPurchaseModel();
?>
<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/purchase_retur/add') ?>" class="btn btn-primary">Add</a>

        <a href="<?= url('admin/purchase_retur/') ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrows-rotate"></i>
        </a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/purchase_retur/') ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ Request::input('search') }}" placeholder="Search">
                <div class="input-group-append">
                    <button class="btn btn-dark" type="submit" id="button-addon2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <th>Action</th>
                <th>Kode Purchase retur</th>
                <th>Kode Purchase</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Sub</th>
                <th>Pajak</th>
                <th>Total</th>
            </thead>
            <tbody>
                <?php foreach ($purchase_retur as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/purchase_retur/edit/' .  $row->id_purchase_retur) ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="<?= url('admin/purchase_retur/view/' .  $row->id_purchase_retur) ?>" class="btn btn-info btn-sm">Print</a>
                            <a href="<?= url('admin/purchase_retur/delete/' .  $row->id_purchase_retur) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td><?= $row->kode_retur ?></td>
                        <td><?= $row->kode_purchase ?></td>
                        <td><?= $row->nama_suplier ?></td>
                        <td><?= $row->tanggal ?></td>
                        <td><?= number_format($row->sub, 2) ?></td>
                        <td><?= $row->pajak ?></td>
                        <td><?= number_format($row->total, 2) ?></td>
                       
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $purchase_retur->appends(request()->input())->links(); }}
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            var urldelete = $(this).attr('href');
            // console.log(urldelete);

            bootbox.confirm({
                message: "Yakin Menghapus Data ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-secondary'
                    }
                },
                callback: function(result) {
                    if (result) {
                        window.location.href = urldelete;
                    }
                }
            });

        });
    });
</script>