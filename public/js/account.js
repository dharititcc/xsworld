(function ()
{
    XS.Account = {
        selectors: {
            waiterModalBtn:     $(".waiter_popup_modal"),
            waiterModal:        $("#waiterModal"),
            waiterForm:         $("#addwaiterform"),
            waiterSubmitBtn:    $('#waiter_submitBtn'),
            waiterModalTitle:   $('.waiter_model_title'),
            waiterId:           $('#waiter_id'),
        },

        init: function()
        {
            this.addHandler();
        },

        addHandler: function()
        {
            var context = this;

            context.openWaiterModal();
            context.closeWaiterModal();

        },

        openWaiterModal: function()
        {
            var context = this;

            context.selectors.waiterModalBtn.on("click",function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    waiterId    = $this.data('parent_id');
                console.log(waiterId);
                if( waiterId == undefined )
                {
                    context.selectors.waiterModalTitle.html('Add ');
                    context.addWaiterFormValidation();
                    context.selectors.waiterForm.attr('action', moduleConfig.waiterStore);
                }
                else
                {
                    context.selectors.waiterModalTitle.html('Edit ');
                    context.selectors.waiterSubmitBtn.html('Edit Waiter');
                    context.editWaiterFormValidation();
                    context.selectors.waiterForm.attr('action', moduleConfig.waiterUpdate.replace(':ID', waiterId));
                    context.selectors.waiterId.attr('disabled',true);
                    context.getWaiterData(waiterId);
                    context.selectors.waiterForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                }

                context.selectors.waiterModal.modal('show');
            });
        },

        closeWaiterModal: function()
        {
            var context = this;

            context.selectors.waiterModal.on('hide.bs.modal', function()
            {
                context.selectors.waiterForm.validate().resetForm();
                context.selectors.waiterForm.find('.error').removeClass('error');

                context.selectors.waiterForm.removeAttr('action');
                context.selectors.waiterForm.find('input[type="hidden"]').remove();
            });
        },

        addWaiterFormValidation: function(){
            var context = this;

            context.selectors.waiterForm.validate({
                errorPlacement: function($error, $element) {
                    $error.appendTo($element.closest("div"));
                },
                rules: {
                    waiter_id: {
                        required:true,
                    },
                    first_name: {
                        required:true,
                        maxlength: 50
                    },
                    password: {
                        required:true,
                    },
                },
                messages: {
                    waiter_id: {
                        required: "Please enter waiter ID"
                    },
                    first_name:{
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    password: {
                        required: "Please enter Password"
                    }
                },
                
                submitHandler: function() {
                    context.submitWaiterForm(context.selectors.waiterForm.get(0))
                }
            });
        },

        editWaiterFormValidation: function(){
            var context = this;

            context.selectors.waiterForm.validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 50
                    },
                    password: {
                        required: "Please enter Password"
                    }
                },
                messages: {
                    first_name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    }
                },
                submitHandler: function() {
                    context.submitWaiterForm(context.selectors.waiterForm.get(0));
                }
            });
        },

        submitWaiterForm: function(form)
        {
            var context = this,
                data    = new FormData(form);

            // XS.Common.ajaxSetup;

            XS.Common.btnProcessingStart(context.selectors.waiterSubmitBtn);

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
                    alert('Waiter has been submitted successfully');
                    document.getElementById('addwaiterform').reset();
                    location.reload(true);
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.waiterSubmitBtn);
                }
            });
        },

        getWaiterData: function(id)
        {
            context = this;
            $.ajax({
                url: moduleConfig.waiterGet.replace(':ID',id),
                type: 'GET',
                success: function(res) {
                    $('#waiter_name').val(res.first_name);
                    $('#waiter_id').val(res.username);
                    // $('#password').val(res.password);

                },
                
            });

        }

        
    }
})();