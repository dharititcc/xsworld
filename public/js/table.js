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
                        console.log(res);
                        alert('Table Status has been updated successfully');
                        $this.closest('.ftr').find('.status').removeClass('green');
                        $this.addClass('green');
                    },
                });
            });
        },

        submitTableForm: function(form)
        {
            var context = this,
                data    = new FormData(form);

            XS.Common.btnProcessingStart(context.selectors.tableSubmitBtn);
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
                    alert('Table has been submitted successfully');
                    document.getElementById('addtableform').reset();
                    location.reload(true);
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
                    qrTables= jQuery('.qr_select:checked').map(function(){ return $(this).val() }).get();

                console.log(qrTables);
                $.ajax({
                    url:moduleConfig.tableDelete,
                    type:'POST',
                    dataType: "json",
                    data: {'id':qrTables},
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        console.log(res);
                        alert('Table has been removed successfully');
                        $this.closest('.ftr').find('.status').removeClass('green');
                        $this.addClass('green');
                    },
                });
            });
        },
    }
})();