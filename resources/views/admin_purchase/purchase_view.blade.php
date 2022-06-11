<style>
    @media print {
        .hide_onPrint {
            display: none;
        }
    }
</style>
<div id="print_element">
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
                    <th id="sisa_tagihan">
                        <?php if($sisa_tagihan < 0) echo '<span class="badge badge-danger">Pembayarab Lebih</span>' ?>
                        <?= number_format($sisa_tagihan, 2) ?>
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="height: 300px;">
        <div class="col-md-12">
            <button onclick="printDiv()" class="btn btn-primary hide_onPrint">Print</button>
            <a class="btn btn-secondary hide_onPrint" href="<?= url('admin/purchase') ?>">Kembali</a>
        </div>
    </div>
</div>


<script>
    function printDiv() {
        window.print();
    }
</script>