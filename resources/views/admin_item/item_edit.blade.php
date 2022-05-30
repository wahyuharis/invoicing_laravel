<form id="form_1" method="post">

    <div class="row">
        <div class="col-md-4">

            <input type="hidden" name="id" value="<?= $form->id ?>">
            <div class="form-group">
                <label for="">Nama Item :</label>
                <input type="text" name="nama_item" value="<?= $form->nama_item ?>" class="form-control" placeholder="Nama Item" id="nama_item">
            </div>
            <!-- echo Form::select('size', ['L' => 'Large', 'S' => 'Small'], 'S'); -->
            <div class="form-group">
                <label for="">Kode/Barcode :</label>
                <input type="text" name="kode_item" value="<?= $form->kode_item ?>" class="form-control" placeholder="Kode Item" id="kode_item">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Category :</label>
                <?= Form::select('id_category', $opt_category, $form->id_category, ['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <label for="">Satuan :</label>
                <input type="text" name="satuan_item" value="<?= $form->satuan_item ?>" class="form-control" placeholder="Satuan" id="satuan_item">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Harga Beli :</label>
                <input type="text" name="harga_beli" value="<?= $form->harga_beli ?>" class="form-control" placeholder="Harga Beli" id="harga_beli">
            </div>

            <div class="form-group">
                <label for="">Harga Jual :</label>
                <input type="text" name="harga_jual" value="<?= $form->harga_jual ?>" class="form-control" placeholder="Harga Jual" id="harga_jual">
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <br>
            <button type="submit" class="btn btn-primary">Save</button>

            <a href="<?= url('admin/item') ?>" class="btn btn-dark">Kembali</a>
            <br><br>
            <br><br>
            <br><br>
        </div>
    </div>
</form>


@include('admin_item.item_edit_script')