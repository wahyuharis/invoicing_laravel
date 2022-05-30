<div class="row">
    <div class="col-md-4">
        <a href="<?= url('admin/category/add') ?>" class="btn btn-primary">Add</a>
    </div>
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <form method="get" action="<?= url('admin/category/') ?>">
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
                <th>Cateogory</th>
            </thead>
            <tbody>
                <?php foreach ($category as $row) { ?>
                    <tr>
                        <td>
                            <a href="<?= url('admin/category/edit/' .  $row->id_category) ?>" class="btn btn-primary btn-sm">Edit</a>

                            <a href="<?= url('admin/category/delete/' .  $row->id_category) ?>" class="delete-btn btn btn-danger btn-sm">Delete</a>
                        </td>
                        <td><?= $row->nama_category ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $category->appends(request()->input())->links(); }}
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