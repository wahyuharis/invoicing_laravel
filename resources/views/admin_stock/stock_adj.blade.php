<div id="stock_adj_module">

    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <span class="btn btn-primary" data-toggle="modal" data-target="#pick_item_modal">Pilih Item</span>


            <table class="table table-striped">
                <thead>
                    <th>#</th>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>qty</th>
                    <th style="width: 150px;">qty adj</th>
                    <!-- <th>#</th> -->
                </thead>
                <tbody data-bind="foreach:item_list">
                    <tr>
                        <td> <span data-bind="click: $root.delete_item_list" class="btn btn-danger btn-sm">delete</span> </td>
                        <td> <span data-bind="text:kode_item"></span> </td>
                        <td> <span data-bind="text:nama_item"></span> </td>
                        <td> <span data-bind="text:qty"></span> </td>
                        <td> <input type="text" data-bind="value:qty_adj" class="form-control form-control-sm number"> </td>
                        <!-- <td>  </td> -->
                    </tr>
                </tbody>
            </table>
            <br><br>
            <textarea name="ko_output" class="form-control" data-bind="value:ko.toJSON($root)"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="min-height: 300px;">

            <button type="submit" class="btn btn-primary">Save</button>
            <a class="btn btn-secondary" href="<?= url('admin/sales') ?>">Kembali</a>
        </div>
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
@include('admin_stock.stock_adj_script')