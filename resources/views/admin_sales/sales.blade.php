<?php

// use App\Models\AdminPurchaseModel;

// $purchase_model = new AdminPurchaseModel();
?>
<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/sales/add') ?>" class="btn btn-primary">Add</a>

        <a href="<?= url('admin/sales/') ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrows-rotate"></i>
        </a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/sales/') ?>">
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
                <th>Kode Sales</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Sub</th>
                <th>Pajak</th>
                <th>Total</th>
                <th>Sisa Tagihan</th>
                <th>Barang Diterima</th>
            </thead>
            <tbody>
                <?php foreach ($sales as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/sales/edit/' .  $row->id_sales) ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="<?= url('admin/sales/view/' .  $row->id_sales) ?>" class="btn btn-info btn-sm">Print</a>
                            <a href="<?= url('admin/sales/delete/' .  $row->id_sales) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td><?= $row->kode_sales ?></td>
                        <td><?= $row->nama_customer ?></td>
                        <td><?= $row->tanggal ?></td>
                        <td><?= number_format($row->sub, 2) ?></td>
                        <td><?= $row->pajak ?></td>
                        <td><?= number_format($row->total, 2) ?></td>
                        <td>
                            <?php
                            // $sisa_tagihan = $purchase_model->get_sisa_tagihan($row->id_sales);

                            // if (count($sisa_tagihan) < 1) {
                            //     echo '<span class="badge badge-secondary">Belum Dibayar</span>';
                            // } 
                            // elseif($sisa_tagihan[0]->sisa_tagihan < 1){
                            //     echo '<span class="badge badge-primary">Lunas</span>';
                            // }
                            // else {
                            //     echo number_format($sisa_tagihan[0]->sisa_tagihan, 2);
                            // }
                            // ?>

                        </td>
                        <td>
                            <?php
                            if ($row->barang_dikirim > 0) { ?>
                                <span class="badge badge-primary">Telah Dikirim</span>
                            <?php } else { ?>
                                <span class="badge badge-secondary">Belum Dikirim</span>
                            <?php }  ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $sales->appends(request()->input())->links(); }}
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