<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">

        <form id="form_1" method="post">
            <input type="hidden" name="id" value="<?= $form->id ?>">
            <div class="form-group">
                <label for="">Nama Customer :</label>
                <input type="text" name="nama_customer" value="<?= $form->nama_customer ?>" class="form-control" placeholder="Category" id="nama_customer">
            </div>

            <div class="form-group">
                <label for="">Email :</label>
                <input type="text" name="email" value="<?= $form->email ?>" class="form-control" placeholder="Email" id="email">
            </div>

            <div class="form-group">
                <label for="">Phone :</label>
                <input type="text" name="phone" value="<?= $form->phone ?>" class="form-control" placeholder="Phone" id="phone">
            </div>

            <div class="form-group">
                <label for="">Kota :</label>
                <?= Form::select('id_kota', $form->opt_kota, $form->id_kota,['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <label for="">Alamat :</label>
                <textarea name="alamat"  id="alamat" class="form-control" ><?= $form->alamat ?></textarea>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="<?= url('admin/customer') ?>" class="btn btn-dark">Kembali</a>
        </form>
        <br><br><Br>

    </div>
    <div class="col-md-3"></div>
</div>

@include('admin_customer.customer_edit_script')