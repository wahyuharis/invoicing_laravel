<div class="row">
    <div class="col-md-4">
        <table class="table table-striped">
            <tr>
                <th>Kode Purchase</th>
                <th>:</th>
                <th><?= $purchase->kode_purchase ?></th>
            </tr>
            <tr>
                <th>Supplier</th>
                <th>:</th>
                <th><?= $purchase->nama_suplier ?></th>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-striped">
            <tr>
                <th>Tanggal</th>
                <th>:</th>
                <th><?= $purchase->tanggal ?></th>
            </tr>
            <tr>
                <th>Barang Diterima</th>
                <th>:</th>
                <th>
                    <?php
                    // $purchase->barang_diterima
                    if ($purchase->barang_diterima > 0) {
                        echo '<span class="badge badge-primary">Diterima</span>';
                    } else {
                        echo '<span class="badge badge-secondary">Belum Diterima</span>';
                    }
                    ?>
                </th>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-striped">
            <tr>
                <th>Catatan</th>
                <th>:</th>
                <th>
                    <?= $purchase->catatan ?>
                </th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Disc</th>
                    <th>Sub</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($purchase_list as $row) {
                ?>
                    <tr>
                        <td><?= $row->kode_item ?></td>
                        <td><?= $row->nama_item ?></td>
                        <td><?= number_format($row->harga, 2) ?></td>
                        <td><?= $row->qty ?></td>
                        <td><?= $row->satuan_item ?></td>
                        <td><?= $row->disc ?></td>
                        <td><?= number_format($row->sub, 2) ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
    </div>
    <div class="col-md-6">
        <table class="table table-striped">
            <tr>
                <th>Sub </th>
                <th> : </th>
                <th> <?= number_format($purchase->sub, 2) ?> </th>
            </tr>
            <tr>
                <th> Pajak (%) </th>
                <th> : </th>
                <th> <?= $purchase->pajak ?> </th>
            </tr>
            <tr>
                <th> Total </th>
                <th> : </th>
                <th> <?= number_format($purchase->total, 2) ?> </th>
            </tr>
            <tr>
                <th> Dibayarkan </th>
                <th> : </th>
                <th> <?= number_format(-1 * $cashflow->total, 2) ?> </th>
            </tr>
            <tr>
                <th> Sisa Tagihan </th>
                <th> : </th>
                <th>
                    <?= number_format($sisa_tagihan, 2) ?>
                </th>
            </tr>
        </table>
    </div>
</div>

<form id="form_1" method="post">
    <div class="row">
        <div class="col-md-6">
            <input type="hidden" name="id" value="<?= $id ?>">
        </div>
        <div class="col-md-6">
            <br><br>
            <table class="table table-striped">
                <tr>
                    <th>Barang Diterima </th>
                    <th> : </th>
                    <th>
                        <select id="barang_diterima" name="barang_diterima" class="form-control">
                            <?php if ($purchase->barang_diterima > 0) { ?>
                                <option value="1" selected>Barang Telah Diterima</option>
                            <?php } else { ?>
                                <option value="0">Barang Belum Diterima</option>
                                <option value="1">Barang Telah Diterima</option>
                            <?php } ?>

                        </select>
                    </th>
                </tr>
                <tr>
                    <th> Setor Cicilan (+Dibayarkan) </th>
                    <th> : </th>
                    <th>
                        <input type="text" id="jml_dibayar" name="jml_dibayar" class="form-control thousand" placeholder="Jml Dibayarkan">

                    </th>
                </tr>

            </table>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-secondary" href="<?= url('admin/purchase') ?>">Kembali</a>
        </div>
    </div>
</form>


<div class="row">
    <div class="col-md-12" style="height: 300px;">

    </div>
</div>

@include('admin_purchase.purchase_edit_script')