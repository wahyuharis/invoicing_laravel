<script>
    $(document).ready(function() {
        format();

        $('#jml_dibayar').change(function() {
            var sisa_tagihan = $("#sisa_tagihan").html();
            sisa_tagihan = curency_to_float(sisa_tagihan);
            jml_dibayar = curency_to_float($(this).val());

            if (jml_dibayar > sisa_tagihan) {
                // toastr.error("Maaf Nilai Setoran/Cicilan Tidak Boleh lebih Dari Sisa Tagihan");

                bootbox.alert({
                    message: "Maaf Nilai Setoran/Cicilan Tidak Boleh lebih Dari Sisa Tagihan!",
                    size: 'small'
                });

                $(this).val('0');
            }
        });

        $('#form_1').submit(function(e) {
            e.preventDefault();
            JsLoadingOverlay.show();
            $.ajax({
                url: '<?= url('admin/sales/edit_submit/') ?>', // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function(data) // A function to be called if request succeeds
                {
                    if (data.success) {
                        // window.location = '<?= url('admin/sales/view') ?>/' + data.data.insert_id;
                        window.location = '<?= url('admin/sales/') ?>';
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