<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/stock/adj') ?>" class="btn btn-primary">Adjustment</a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/stock/') ?>">
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
                <th>Detail</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Kategory</th>
                <th>Qty</th>
            </thead>
            <tbody>
                <?php foreach ($item_stock as $row) { ?>
                    <tr>
                        <td>
                            <a class="btn btn-primary btn-sm" href="<?= url('admin/stock/detail/' . $row->id_item) ?>">Detail</a>
                        </td>
                        <td><?= $row->kode_item ?></td>
                        <td><?= $row->nama_item ?></td>
                        <td><?= $row->nama_category ?></td>
                        <td><?= $row->qty ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $item_stock->appends(request()->input())->links(); }}
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