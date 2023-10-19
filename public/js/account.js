(function ()
{
    XS.Account = {
        selectors: {
            waiterModalBtn:     $(".waiter_popup_modal"),
            waiterId:           $('#waiter_id'),
            waiterModalTitle:   $('.waiter_model_title'),
            waiterModal:        $("#waiterModal"),
            waiterForm:         $("#addwaiterform"),
            waiterSubmitBtn:    $('#waiter_submitBtn'),

            kitchenModalBtn:    $('.kitchen_popup_modal'),
            kitchenId:          $('#kitchen_id'),
            kitchenModalTitle:  $('.kitchen_model_title'),
            kitchenModal:       $('#kitchenModal'),
            kitchenForm:        $('#addkitchenform'),
            kitchenSubmitBtn:   $('#kitchen_submitBtn'),

            barModalBtn:        $('.barpickzone_popup_modal'),
            barId:              $('#barpick_id'),
            barModalTitle:      $('.barzone_model_title'),
            barModal:           $('#addBarModal'),
            barForm:            $('#addbarpickform'),
            barSubmitBtn:       $('#barpickzone_submitBtn'),

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

            context.openKitchenModal();
            context.closeKitchenModal();

            context.openBarModal();
            context.closeBarModal();


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

        },

        //kitchen start
        openKitchenModal: function()
        {
            var context = this;
            context.selectors.kitchenModalBtn.on("click", function(e) {
                e.preventDefault();

                var $this     = $(this),
                    kitchenId = $this.data('parent_id');

                if(kitchenId == undefined)
                {
                    context.selectors.kitchenModalTitle.html('Add ');
                    context.addKitchenFormValidation();
                    context.selectors.kitchenForm.attr('action', moduleConfig.kitchenStore);
                }
                else
                {
                    context.selectors.kitchenModalTitle.html('Edit ');
                    context.selectors.kitchenSubmitBtn.html('Edit Kitchen');
                    context.editKitchenFormValidation();
                    context.selectors.kitchenForm.attr('action', moduleConfig.kitchenUpdate.replace(':ID', kitchenId));
                    context.selectors.kitchenId.attr('disabled',true);
                    context.getKitchenData(kitchenId);
                    context.selectors.kitchenForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                }
                context.selectors.kitchenModal.modal('show');
            });
        },

        closeKitchenModal: function()
        {
            var context = this;

            context.selectors.kitchenModal.on('hide.bs.modal', function()
            {
                context.selectors.kitchenForm.validate().resetForm();
                context.selectors.kitchenForm.find('.error').removeClass('error');

                context.selectors.kitchenForm.removeAttr('action');
                context.selectors.kitchenForm.find('input[type="hidden"]').remove();
            });
        },


        addKitchenFormValidation: function()
        {
            var context = this;
            context.selectors.kitchenForm.validate({
                errorPlacement: function($error, $element) {
                    $error.appendTo($element.closest("div"));
                },
                rules: {
                    kitchen_id: {
                        required:true,
                    },
                    
                    password: {
                        required:true,
                    },
                },
                messages: {
                    kitchen_id: {
                        required: "Please enter kitchen ID"
                    },
    
                    // pickup_points: {
                        // required: "please select pickup points"
                    // },
                    
                    password: {
                        required: "Please enter Password"
                    }
                },
                
                submitHandler: function() {
                    context.submitKitchenForm(context.selectors.kitchenForm.get(0));
                }
            });
        },

        editKitchenFormValidation: function()
        {
            var context = this;
            context.selectors.kitchenForm.validate({
                rules: {
                    password: {
                        required: "Please enter Password"
                    }
                },
                messages: {
                    // pickup_points: {
                        // required: "please select pickup points",
                        
                    // }
                },
                submitHandler: function() {
                    context.submitKitchenForm(context.selectors.kitchenForm.get(0));
                }
            });
        },

        submitKitchenForm: function(form)
        {
            var context = this;
                data    = new FormData(form);

                XS.Common.btnProcessingStart(context.selectors.kitchenSubmitBtn);
                // $.each($("#kitchen_point option:selected"), function(i) {
                //     kitchen_point[i] = $(this).val();
                // });
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
                        alert('Kitchen has been submitted successfully');
                        document.getElementById('addkitchenform').reset();
                        location.reload(true);
                    },
                    complete: function()
                    {
                        XS.Common.btnProcessingStop(context.selectors.kitchenSubmitBtn);
                    }
                });
        },

        getKitchenData: function(id)
        {
            context = this;
            $.ajax({
                url: moduleConfig.kitchenGet.replace(':ID',id),
                type: 'GET',
                success: function(res) {
        
                    // $.each(res.pickup_point_name, function (key, val) {
                    //     $('select option[value="'+val.id+'"]').attr("selected",true);
                    // });
        
                    $('#kitchen_id').val(res.username);
                },
            });
        },
        //kichen end

        //Bar start
        openBarModal: function()
        {
            var context = this;

            context.selectors.barModalBtn.on("click",function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    barId    = $this.data('parent_id');
                console.log(barId);
                if( barId == undefined )
                {
                    context.selectors.barModalTitle.html('Add ');
                    context.addBarFormValidation();
                    context.selectors.barForm.attr('action', moduleConfig.barpickStore);
                }
                else
                {
                    context.selectors.barModalTitle.html('Edit ');
                    context.selectors.barSubmitBtn.html('Edit bar');
                    context.editBarFormValidation();
                    context.selectors.barForm.attr('action', moduleConfig.barpickUpdate.replace(':ID', barId));
                    context.selectors.barId.attr('disabled',true);
                    context.getBarData(barId);
                    context.selectors.barForm.append(`<input type="hidden" name="_method" value="PUT" />`);
                }

                context.selectors.barModal.modal('show');
            });
        },

        closeBarModal: function()
        {
            var context = this;

            context.selectors.barModal.on('hide.bs.modal', function()
            {
                context.selectors.barForm.validate().resetForm();
                context.selectors.barForm.find('.error').removeClass('error');

                context.selectors.barForm.removeAttr('action');
                context.selectors.barForm.find('input[type="hidden"]').remove();
            });
        },

        addBarFormValidation: function(){
            var context = this;

            context.selectors.barForm.validate({
                errorPlacement: function($error, $element) {
                    $error.appendTo($element.closest("div"));
                },
                rules: {
                    barpick_id: {
                        required:true,
                    },
                    
                    password: {
                        required:true,
                    },
                },
                messages: {
                    barpick_id: {
                        required: "Please enter barpick ID"
                    },
    
                    pickup_points: {
                        required: "please select pickup points"
                    },
                    
                    password: {
                        required: "Please enter Password"
                    }
                },
                
                submitHandler: function() {
                    context.submitBarForm(context.selectors.barForm.get(0))
                }
            });
        },


        editBarFormValidation: function(){
            var context = this;

            context.selectors.barForm.validate({
                rules: {
                    password: {
                        required: "Please enter Password"
                    }
                },
                messages: {
                    pickup_points: {
                        required: "please select pickup points",
                        
                    }
                },
                submitHandler: function() {
                    context.submitBarForm(context.selectors.barForm.get(0));
                }
            });
        },


        submitBarForm: function(form)
        {
            var context = this,
                data    = new FormData(form);

            // XS.Common.ajaxSetup;

            XS.Common.btnProcessingStart(context.selectors.barSubmitBtn);

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
                    alert('Bar pick zone has been submitted successfully');
                    document.getElementById('addbarpickform').reset();
                    location.reload(true);
                },
                complete: function()
                {
                    XS.Common.btnProcessingStop(context.selectors.barSubmitBtn);
                }
            });
        },

        getBarData: function(id)
        {
            context = this;
            $.ajax({
                url: moduleConfig.barpickGet.replace(':ID',id),
                type: 'GET',
                success: function(res) {
                    var pickupPoint = res.pickup_point,
                        options     = `
                            <option value="${pickupPoint.id}">${pickupPoint.name}</option>
                        `;
        
                    $("#pickup_points").append(options);
                    $('#barpick_id').val(res.username);
                    $('#pickup_points').val(pickupPoint.id);
        
                    $("#pickup_points option").each(function() {
                        $(this).siblings('[value="'+ this.value +'"]').remove();
                      });
                },
            });
        },
        //end Bar
    }
})();