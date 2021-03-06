<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/supplier/add') ?>" class="btn btn-primary">Add</a>
        <a href="<?= url('admin/supplier/') ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrows-rotate"></i>
        </a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/supplier/') ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control"  value="{{ Request::input('search') }}" name="search" placeholder="Search">
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
                <th>Nama Supplier</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Kota</th>
            </thead>
            <tbody>
                <?php foreach ($supplier as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/supplier/edit/' .  $row->id_supplier) ?>" class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= url('admin/supplier/delete/' .  $row->id_supplier) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td><?= $row->nama_suplier ?></td>
                        <td><?= $row->email ?></td>
                        <td><?= $row->phone ?></td>
                        <td><?= $row->kota ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $supplier->appends(request()->input())->links(); }}
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