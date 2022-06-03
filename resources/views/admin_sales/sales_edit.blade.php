<div class="row">
    <div class="col-md-4">
        <table class="table table-striped">
            <tr>
                <th>Kode Sales</th>
                <th>:</th>
                <th><?= $sales->kode_sales ?></th>
            </tr>
            <tr>
                <th>Customer</th>
                <th>:</th>
                <th><?= $sales->nama_customer ?></th>
            </tr>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table table-striped">
            <tr>
                <th>Tanggal</th>
                <th>:</th>
                <th><?= $sales->tanggal ?></th>
            </tr>
            <tr>
                <th>Barang Dikirim</th>
                <th>:</th>
                <th>
                    <?php
                    // $purchase->barang_diterima
                    if ($sales->barang_dikirim > 0) {
                        echo '<span class="badge badge-primary">Dikirim</span>';
                    } else {
                        echo '<span class="badge badge-secondary">Belum Dikirim</span>';
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
                    <?= $sales->catatan ?>
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
                foreach ($sales_list as $row) {
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
                <th> <?= number_format($sales->sub, 2) ?> </th>
            </tr>
            <tr>
                <th> Pajak (%) </th>
                <th> : </th>
                <th> <?= $sales->pajak ?> </th>
            </tr>
            <tr>
                <th> Total </th>
                <th> : </th>
                <th> <?= number_format($sales->total, 2) ?> </th>
            </tr>
            <tr>
                <th> Dibayarkan </th>
                <th> : </th>
                <th> <?= number_format( $cashflow->total, 2) ?> </th>
            </tr>
            <tr>
                <th> Sisa Tagihan </th>
                <th> : </th>
                <th id="sisa_tagihan">
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
                    <th>Barang Dikirim </th>
                    <th> : </th>
                    <th>
                        <select id="barang_dikirim" name="barang_dikirim" class="form-control">
                            <?php if ($sales->barang_dikirim > 0) { ?>
                                <option value="1" selected>Barang Telah Dikirim</option>
                            <?php } else { ?>
                                <option value="0">Barang Belum Dikirim</option>
                                <option value="1">Barang Telah Dikirim</option>
                            <?php } ?>

                        </select>
                    </th>
                </tr>
                <tr>
                    <th> Setor Cicilan (+Dibayarkan) </th>
                    <th> : </th>
                    <?php
                    $readonly = "";
                    if ($sisa_tagihan < 1) {
                        $readonly = " readonly ";
                    }
                    ?>
                    <th>
                        <input type="text" id="jml_dibayar" <?= $readonly ?> name="jml_dibayar" class="form-control thousand" placeholder="Jml Dibayarkan">

                    </th>
                </tr>

            </table>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-secondary" href="<?= url('admin/sales') ?>">Kembali</a>
        </div>
    </div>
</form>


<div class="row">
    <div class="col-md-12" style="height: 300px;">

    </div>
</div>

@include('admin_sales.sales_edit_script')