<?php

use App\Models\AdminPurchaseModel;

$purchase_model = new AdminPurchaseModel();
?>
<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/purchase/add') ?>" class="btn btn-primary">Add</a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/purchase/') ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" placeholder="Search">
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
                <th>Kode Purchase</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Sub</th>
                <th>Pajak</th>
                <th>Total</th>
                <th>Sisa Tagihan</th>
                <th>Barang Diterima</th>
            </thead>
            <tbody>
                <?php foreach ($purchase as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/purchase/edit/' .  $row->id_purchase) ?>" class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= url('admin/purchase/delete/' .  $row->id_purchase) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td><?= $row->kode_purchase ?></td>
                        <td><?= $row->nama_suplier ?></td>
                        <td><?= $row->tanggal ?></td>
                        <td><?= number_format($row->sub, 2) ?></td>
                        <td><?= $row->pajak ?></td>
                        <td><?= number_format($row->total, 2) ?></td>
                        <td>
                            <?php
                            $sisa_tagihan = $purchase_model->get_sisa_tagihan($row->id_purchase);

                            if (count($sisa_tagihan) < 1) {
                                echo '<span class="badge badge-secondary">Belum Dibayar</span>';
                            } else {
                                echo number_format($sisa_tagihan[0]->sisa_tagihan, 2);
                            }
                            ?>

                        </td>
                        <td>
                            <?php
                            if ($row->barang_diterima > 0) { ?>
                                <span class="badge badge-primary">Diterima</span>
                            <?php } else { ?>
                                <span class="badge badge-secondary">Belum Diterima</span>
                            <?php }  ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $purchase->appends(request()->input())->links(); }}
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