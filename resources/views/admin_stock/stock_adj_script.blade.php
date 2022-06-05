<script>
    function add_item(id_item, kode_item, nama_item, qty, qty_adj) {
        var self = this;

        self.id_item = ko.observable(id_item);
        self.kode_item = ko.observable(kode_item);
        self.nama_item = ko.observable(nama_item);
        self.qty = ko.observable(qty);
        self.qty_adj = ko.observable(qty_adj);
    }

    function Stockadj_Module() {
        var self = this;

        self.item_list = ko.observableArray([]);

        self.delete_item_list = function(row) {
            self.item_list.remove(row);
        }


        self.add_item_modal = function(id_item) {
            add = true;
            for (var i = 0; i < self.item_list().length; i++) {
                id_item2 = self.item_list()[i].id_item();
                if (id_item == id_item2) {
                    add = false;
                }
            }

            if (add) {
                JsLoadingOverlay.show();
                $.ajax({
                    url: '/admin/item/get_item/' + id_item,
                    type: 'get',
                    success: function(data) {
                        console.log(data);
                        // self.item_list.push(new add_item(data.id_item, data.kode_item, data.nama_item, data.harga_jual, '0', data.satuan_item, '0'));

                        // function add_item(id_item, kode_item, nama_item, qty, qty_adj) {
                        self.item_list.push(new add_item(data.id_item, data.kode_item, data.nama_item, data.qty_akhir, data.qty_akhir));

                        $('#pick_item_modal').modal('hide');
                        JsLoadingOverlay.hide();

                    },
                    error: function(err) {
                        alert("terjadi kesalahan");
                        JsLoadingOverlay.hide();
                    }
                });
            } else {
                toastr["error"]("Maaf Item Sudah Ada !");
            }


        }
    }

    $('#stock_adj_module').on('DOMSubtreeModified', function() {
        format();
    });

    $(document).ready(function() {
        ko.applyBindings(new Stockadj_Module(), document.getElementById("stock_adj_module"));

        $('#pick_item_modal').on('hidden.bs.modal', function(event) {
            $('#pick_item_table').dataTable().fnClearTable();
            $('#pick_item_table').dataTable().fnDestroy();
        })

        $('#pick_item_modal').on('shown.bs.modal', function(event) {
            $('#pick_item_table').DataTable({
                "ordering": false,
                ajax: '<?= url('admin/item/datatables') ?>',
                "drawCallback": function(settings) {

                    $('.pilih_item').click(function() {
                        val = $(this).attr('id_item');
                        // console.log(val);

                        var context = ko.contextFor(document.getElementById("stock_adj_module"));
                        context.$data.add_item_modal(val);
                    });
                }
            });
        });

        $('#form_1').submit(function(e) {
            e.preventDefault();
            JsLoadingOverlay.show();
            $.ajax({
                url: '<?= url('admin/stock/adj_submit/') ?>', // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function(data) // A function to be called if request succeeds
                {
                    if (data.success) {
                        // window.location = '<?= url('admin/sales/view') ?>/' + data.data.insert_id;
                        window.location = '<?= url('admin/stock/') ?>';
                        console.log(data);
                    } else {
                        toastr.error(data.message);
                    }
                    console.log(data);
                    JsLoadingOverlay.hide();
                },
                error: function(err, txt) {
                    JsLoadingOverlay.hide();
                    console.log(err);
                    // console.log('================');
                    // console.log(txt);
                    bootbox.alert({
                        size: "large",
                        title: '<span class="text-danger" >Error ' + err.status + '<span>',
                        message: '<iframe id="bootframe_err"  src="about:blank" style="width:100%;height:500px;border:none" ></iframe>',
                        onShown: function(e) {
                            var doc = document.getElementById('bootframe_err').contentWindow.document;
                            doc.open();
                            doc.write(err.responseText);
                            doc.close();
                        }
                    });
                }
            });
        });

    });
</script>