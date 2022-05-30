<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">

        <form id="form_1" method="post">
            <input type="hidden" name="id" value="<?= $form->id ?>">
            <div class="form-group">
                <label for="">Category :</label>
                <input type="text" name="nama_category" value="<?= $form->nama_category ?>" class="form-control" placeholder="Category" id="nama_category">
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= url('admin/category') ?>" class="btn btn-dark">Kembali</a>
        </form>

    </div>
    <div class="col-md-3"></div>
</div>

@include('admin_category.category_edit_script')