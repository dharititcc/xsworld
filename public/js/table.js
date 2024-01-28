(function()
{
    XS.Table = {
        selectors: {
            tableModalBtn:     $(".table_popup_modal"),
            tableId:           $('#table_id'),
            tableModalTitle:   $('.table_model_title'),
            tableModal:        $("#tableModal"),
            tableForm:         $("#addtableform"),
            tableSubmitBtn:    $('#table_submitBtn'),
            tableStatusBtn:    $('.status'),

        },

        init: function()
        {
            this.addHandler();
        },

        addHandler: function()
        {
            var context = this;
            context.openTableModal();
            context.closeTableModal();
            context.statusUpdate();
            context.removeTables();
            context.exportQRCode();
            // context.exportPdf();
        },

        openTableModal: function()
        {
            var context = this;
            context.selectors.tableModalBtn.on("click",function(e) {
                e.preventDefault();
                var $this       = $(this),
                    tableId    = $this.data('parent_id');
                    console.log(tableId);
                if( tableId == undefined )
                {
                    // context.selectors.tableModalTitle.html('Add ');
                    context.addTableFormValidation();
                    context.selectors.tableForm.attr('action', moduleConfig.tableStore);
                }
                context.selectors.tableModal.modal('show');
            });
        },

        closeTableModal: function()
        {
            var context = this;

            context.selectors.tableModal.on('hide.bs.modal', function()
            {
                context.selectors.tableForm.validate().resetForm();
                context.selectors.tableForm.find('.error').removeClass('error');

                context.selectors.tableForm.removeAttr('action');
                context.selectors.tableForm.find('input[type="hidden"]').remove();
            });
        },


        addTableFormValidation: function()
        {
            var context = this;
            context.selectors.tableForm.validate({
                errorPlacement: function($error, $element) {
                    $error.appendTo($element.closest("div"));
                },
                rules: {
                    name: {
                        required:true,
                        maxlength: 50
                    },
                    code: {
                        required:true,
                    },
                },
                messages: {
                    code: {
                        required: "Please enter Table Code"
                    },
                    name:{
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                },

                submitHandler: function() {
                    context.submitTableForm(context.selectors.tableForm.get(0))
                }
            });
        },

        statusUpdate: function()
        {
            var context = this;
            context.selectors.tableStatusBtn.on('click', function() {
                var $this = $(this),
                    id = $this.data('id'),
                    status = $this.data('status');
                $.ajax({
                    url:moduleConfig.tableStatusUpdate,
                    type:'GET',
                    dataType: "json",
                    data: {'status':status,'id':id},
                    success: function(res) {
                        // alert('Table Status has been updated successfully');
                        XS.Common.handleSwalSuccessWithoutReload("Table Status has been updated successfully.");
                        // $this.closest('.ftr').find('.status').removeClass('green');
                        if(status == 1) {
                            $this.addClass('green');
                            $this.closest('.ftr').find('.disable').removeClass('red');
                        } else {
                            $this.addClass('red');
                            $this.closest('.ftr').find('.active').removeClass('green');
                        }
                    },
                });
            });
        },

        submitTableForm: function(form)
        {
            var context = this,
                data    = new FormData(form);

            XS.Common.btnProcessingStart(context.selectors.tableSubmitBtn);
            $(".error").remove();
            $.ajax({
                url:$(form).attr('action'),
                type:'POST',
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    // alert('Table has been submitted successfully');
                    document.getElementById('addtableform').reset();
                    // location.reload(true);
                    XS.Common.handleSwalSuccess('Table has been submitted successfully.');
                },
                error: function(xhr)
                {
                    if( xhr.status == 403 )
                    {
                        var {error} = xhr.responseJSON;
                        console.log(error.message);
                        if(error.message == "Please enter unique value") {
                            context.selectors.tableForm.find('#name').after(`<span class="error">${error.message}</span>`);
                            context.selectors.tableForm.find('#code').after(`<span class="error">${error.message}</span>`);
                        }
                        if(error.message == "Please enter unique Table name") {
                            context.selectors.tableForm.find('#name').after(`<span class="error">${error.message}</span>`);
                        }
                        if(error.message == "Please enter unique table code") {
                            context.selectors.tableForm.find('#code').after(`<span class="error">${error.message}</span>`);
                        }
                    }
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.tableSubmitBtn);
                }
            });
        },

        removeTables: function()
        {
            var context = this;

            jQuery('.remove_tables').on('click', function()
            {
                var $this   = $(this),
                    idsArr  = [];
                    // qrTables= jQuery('.qr_select:checked').map(function(){ return $(this).val() }).get();

                $('.qr_select:checked').each(function() {
                    idsArr.push($(this).val());
                });

                if(idsArr.length <= 0) {
                    // alert("Please select atleast one record to delete.");
                    XS.Common.handleSwalSuccessWithoutReload("Please select atleast one record to delete.");
                } else {
                    swal({
                        title: `Are you sure you want to delete this Records?`,
                        // text: "It will gone forevert",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var strIds = idsArr.join(",");
                            $.ajax({
                                url:moduleConfig.tableDelete,
                                type:'DELETE',
                                data: 'ids='+strIds,
                                headers: {
                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    console.log(res);
                                    // alert('Table has been removed successfully');
                                    XS.Common.handleSwalSuccessWithoutReload("Table has been removed successfully.");
                                    $this.closest('.ftr').find('.status').removeClass('green');
                                    $this.addClass('green');
                                    location.reload(true);
                                },
                            });
                        }
                        // else if (result.isDenied) {
                        //     $('.qr_select').prop('checked', false);
                        // }
                    });
                    // if(confirm("Are you sure, you want to delete the selected table?")){
                        
                    // } else {
                    //     $('.qr_select').prop('checked', false);
                    // }
                }
            });
        },

        exportQRCode: function()
        {
            var context = this;
            $('.export-info').on("click", function()
            {
                var $this           = $(this),
                    id              = $this.data('id'),
                    qr              = $this.closest('.cnt').find('.qr-code').find('img').clone(),
                    qrText          = $this.closest('.table-design').find('.head').find('h2').text(),
                    selectorQr      = $('#export_qr_code').find('.qrcode'),
                    selectorQrTxt   = $('#export_qr_code').find('.table-ids'),
                    qrCode          = qrText.split('-');

                selectorQr.children().remove();

                selectorQr.append(qr.get(0));
                selectorQrTxt.html(jQuery.trim(qrCode[1]));

                $('.export_pdf').on("click", function() {
                    $.ajax({
                        url:moduleConfig.exportpdf,
                        type:'POST',
                        data: {'id': id},
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res){
                          console.log(res);
                          var link = document.createElement("a");
                          document.body.appendChild(link);
                          link.setAttribute("type", "hidden");
                          link.href = res.pdf;
                          link.setAttribute('download', res.name);
                          link.click();
                          document.body.removeChild(link);
                        //   alert('QR code Exported successfully');
                            XS.Common.handleSwalSuccessWithoutReload("QR code Exported successfully.");
                          $('.qr-code-hide').modal('hide');
                        },
                        error: function(xhr, error)
                        {
                            console.log(xhr);
                        }
                      });
                });

                $('.print').on('click', function(e)
                {
                    e.preventDefault();
                    // console.log($(qr).attr('src')); return false;
                    w=window.open('', '');
                    // w.document.write($(qr).html());
                    w.document.write('<html><head>');
                    w.document.write('</head><body >');
                    w.document.write('<img id="print-image-element" src="'+$(qr).attr('src')+'"/>');
                    w.document.write('<script>var img = document.getElementById("print-image-element"); img.addEventListener("load",function(){ window.focus(); window.print(); window.document.close(); window.close(); }); </script>');
                    w.document.write('</body></html>');
                    w.window.print();
                    w.close();
                });
            });
        },
    }
})();