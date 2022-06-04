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
        })

    });
</script>