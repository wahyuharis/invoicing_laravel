<script>
    function add_item(id_item, kode_item, nama_item, harga_jual, qty, satuan_item, disc) {
        var self = this;

        self.id_item = ko.observable(id_item);
        self.kode_item = ko.observable(kode_item);
        self.nama_item = ko.observable(nama_item);
        self.harga_jual = ko.observable(harga_jual);
        self.qty = ko.observable(qty);
        self.satuan_item = ko.observable(satuan_item);
        self.disc = ko.observable(disc);
        self.sub1 = ko.computed(function() {
            total = 0;
            total = curency_to_float(self.qty()) * curency_to_float(self.harga_jual());
            total = float_to_currency(total);
            return total;
        });

        self.sub = ko.computed(function() {
            total = 0;
            total = curency_to_float(self.sub1()) - (curency_to_float(self.sub1()) * (curency_to_float(self.disc()) / 100));

            total = float_to_currency(total);
            return total;
        });

    }


    function Module_pesan() {
        var self = this;

        self.kode_sales = ko.observable('');
        self.tanggal = ko.observable('');
        self.pajak = ko.observable('0');
        self.id_customer = ko.observable('');
        self.barang_dikirim = ko.observable('');
        self.jml_dibayar = ko.observable('0');
        self.catatan = ko.observable('');
        // self.sisa_tagihan=ko.observable();

        self.opt_customer = ko.observableArray(<?= $opt_customer ?>);
        self.item_list = ko.observableArray([]);

        self.sub = ko.computed(function() {
            // self.item_list();
            var total = 0;
            for (var i = 0; i < self.item_list().length; i++) {
                sub1 = self.item_list()[i].sub();
                total = total + curency_to_float(sub1);
            }

            total = float_to_currency(total);
            return total;
        });

        self.total = ko.computed(function() {
            // self.item_list();
            var total = 0;

            pajak = (curency_to_float(self.pajak()) / 100) * curency_to_float(self.sub());

            total = pajak + curency_to_float(self.sub());
            total = float_to_currency(total);

            return total;
        });

        self.sisa_tagihan = ko.computed(function() {
            var sisa_tagihan = 0;

            sisa_tagihan = (curency_to_float(self.total())) - curency_to_float(self.jml_dibayar());

            sisa_tagihan = float_to_currency(sisa_tagihan);

            return sisa_tagihan;
        });

        self.jml_dibayar.subscribe(function(newValue) {
            if (curency_to_float(self.total()) < curency_to_float(newValue)) {
                bootbox.alert("Maaf Jml dibayar tidak Boleh lebih dari total tagihan !");
                self.jml_dibayar(0);
            }
        });

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
                        self.item_list.push(new add_item(data.id_item, data.kode_item, data.nama_item, data.harga_jual, '0', data.satuan_item, '0'));
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
        // alert('hello');
        ko.applyBindings(new Module_pesan(), document.getElementById("model_purchase"));

        $('#id_customer').select2({
            "theme": "bootstrap4"
        });

        $('#pick_item_modal').on('shown.bs.modal', function(event) {
            $('#pick_item_table').DataTable({
                "ordering": false,
                ajax: '<?= url('admin/item/datatables') ?>',
                "drawCallback": function(settings) {

                    $('.pilih_item').click(function() {
                        val = $(this).attr('id_item');
                        // console.log(val);

                        var context = ko.contextFor(document.getElementById("model_purchase"));
                        context.$data.add_item_modal(val);
                    });
                }
            });
        })

        $('#pick_item_modal').on('hidden.bs.modal', function(event) {
            $('#pick_item_table').dataTable().fnClearTable();
            $('#pick_item_table').dataTable().fnDestroy();
        })

        $('#tanggal').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('#form_1').submit(function(e) {
            e.preventDefault();
            JsLoadingOverlay.show();
            $.ajax({
                url: '<?= url('admin/sales/submit/') ?>', // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function(data) // A function to be called if request succeeds
                {
                    if (data.success) {
                        window.location = '<?= url('admin/sales/view') ?>/' + data.data.insert_id;
                        // window.location = '<?= url('admin/sales/') ?>';
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

    $('#model_purchase').on('DOMSubtreeModified', function() {
        format();
    });
</script>