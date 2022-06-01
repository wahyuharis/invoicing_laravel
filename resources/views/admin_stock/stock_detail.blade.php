<div class="row">
    <div class="col-md-12">
        <a class="btn btn-secondary" href="<?=url('admin/stock')?>" >Kembali</a>
        <br><br>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Tanggal</th>
                    <th>Qty Awal</th>
                    <th>Qty Masuk</th>
                    <th>Qty Keluar</th>
                    <th>Qty Perbaikan</th>
                    <th>Qty Akhir</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($item_stock_detail as $row) { ?>
                    <tr>
                        <td><?=$row->kode_item ?></td>
                        <td><?=$row->nama_item ?></td>
                        <td><?=$row->hari_jam ?></td>
                        <td><?=$row->qty_awal ?></td>
                        <td><?=$row->qty_in ?></td>
                        <td><?=$row->qty_out ?></td>
                        <td><?=$row->qty_adj ?></td>
                        <td><?=$row->qty_akhir ?></td>
                        <td><?=$row->keterangan ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        {{ $item_stock_detail->appends(request()->input())->links(); }}

    </div>
</div>