<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/item/add') ?>" class="btn btn-primary">Add</a>
        <a href="<?= url('admin/item/') ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrows-rotate"></i>
        </a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/item/') ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control" value="{{ Request::input('search') }}" name="search" placeholder="Search">
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
                <th>Foto</th>
                <th>Kode/Barcode</th>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
            </thead>
            <tbody>
                <?php foreach ($master_item as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/item/edit/' .  $row->id_item) ?>" class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= url('admin/item/delete/' .  $row->id_item) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td>
                            <img width="100px" height="100px" src="<?= url('upload/' . $row->foto1) ?>">
                        </td>
                        <td><?= $row->kode_item ?></td>
                        <td><?= $row->nama_item ?></td>
                        <td><?= $row->nama_category ?></td>
                        <td><?= $row->satuan_item ?></td>
                        <td><?= number_format($row->harga_beli, 2) ?></td>
                        <td><?= number_format($row->harga_jual, 2) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $master_item->appends(request()->input())->links(); }}
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