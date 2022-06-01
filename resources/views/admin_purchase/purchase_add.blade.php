<div id="model_purchase">
    <form id="form_1" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Kode Purchase :</label>
                    <input type="text" id="kode_purchase" data-bind="value:kode_purchase" class="form-control" placeholder="Kode Purchase">
                </div>
                <div class="form-group">
                    <label for="">Supplier :</label>
                    <select id="id_supplier" class="form-control" data-bind="options: opt_supplier,
                       optionsText: 'nama_suplier',
                       optionsValue: 'id_supplier',
                       value: id_supplier,
                       optionsCaption: 'Pilih supplier..'"></select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Tanggal :</label>
                    <input type="text" id="tanggal" data-bind="value:tanggal" class="form-control" placeholder="Tanggal">
                </div>
                <div class="form-group">
                    <label for="">Barang Diterima :</label>
                    <select id="barang_diterima" class="form-control" data-bind="value:barang_diterima">
                        <option value="0">Barang Belum Diterima</option>
                        <option value="1">Barang Telah Diterima</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Catatan :</label>
                    <textarea data-bind="value:catatan" class="form-control" name="catatan" id="catatan"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <textarea name="ko_output" class="form-control d-none" data-bind="value:ko.toJSON($root)"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <br>
                <span class="btn btn-primary" data-toggle="modal" data-target="#pick_item_modal">Pilih Item</span>
                <br>
                <br>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Item</th>
                            <th>Nama Item</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th>Disc(%)</th>
                            <th>Sub</th>
                        </tr>
                    </thead>
                    <tbody data-bind="foreach:item_list">
                        <td> <span class="d-none" data-bind="text:id_item"></span>
                            <span data-bind="click: $root.delete_item_list" class="btn btn-danger btn-sm">delete</span>
                        </td>
                        <td> <span data-bind="text:kode_item"></span> </td>
                        <td> <span data-bind="text:nama_item"></span> </td>
                        <td> <input data-bind="value:harga_beli" type="text" class="form-control form-control-sm thousand"> </td>
                        <td> <input data-bind="value:qty" type="text" class="form-control form-control-sm number"> </td>
                        <td> <span data-bind="text:satuan_item"></span> </td>
                        <td> <input data-bind="value:disc" type="text" class="form-control form-control-sm number"></td>
                        <td> <span data-bind="text:sub"></span> </td>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <table class="table table-striped table-sm">
                    <tr>
                        <th>Sub</th>
                        <th>:</th>
                        <th> <b data-bind="text:sub"></b> </th>
                    </tr>
                    <tr>
                        <th>Pajak</th>
                        <th>:</th>
                        <th>
                            <input type="text" id="pajak" data-bind="value:pajak" class="form-control number" placeholder="Pajak">
                        </th>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>:</th>
                        <th>
                            <b data-bind="text:total"></b>
                        </th>
                    </tr>
                    <tr>
                        <th>Dibayarkan</th>
                        <th>:</th>
                        <th>
                            <input type="text" id="jml_dibayar" data-bind="value:jml_dibayar" class="form-control thousand" placeholder="Jml Dibayarkan">
                        </th>
                    </tr>
                    <tr>
                        <th>Sisa Tagihan</th>
                        <th>:</th>
                        <th>
                            <b data-bind="text:sisa_tagihan"></b>
                        </th>
                    </tr>
                </table>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12" style="">
                <br><br>
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-secondary" href="<?= url('admin/purchase') ?>">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="height: 200px;">

            </div>
        </div>


        <!-- The Modal -->
        <div class="modal fade" id="pick_item_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Pilih Item</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <table id="pick_item_table" class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>kode item</th>
                                    <th>Nama Item</th>
                                    <th>category</th>
                                    <th>satuan</th>
                                    <th>harga beli</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@include('admin_purchase.purchase_add_script')