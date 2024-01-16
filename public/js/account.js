(function ()
{
    XS.Account = {
        selectors: {
            waiterModalBtn:     $(".waiter_popup_modal"),
            waiterId:           $('#waiter_id'),
            waiterName:         $('#waiter_id'),
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
            context.pickupPointValidation();

            context.passwordToggle();
        },

        passwordToggle: function()
        {
            var context = this;

            jQuery('.show-password').on('click', function(e)
            {
                e.preventDefault();

                var $this = $(this),
                    type  = $this.attr('data-type');

                    console.log(type);
                if( type == 0 )
                {
                    $this.addClass('icon-eye');
                    $this.removeClass('icon-eye-off');
                    $this.attr('data-type', 1);

                    // input type text
                    $this.closest('.form-group').find('input').attr('type', 'text');
                }
                else
                {
                    $this.addClass('icon-eye-off');
                    $this.removeClass('icon-eye');
                    $this.attr('data-type', 0);

                    // input type password
                    $this.closest('.form-group').find('input').attr('type', 'password');
                }
            });
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
                    context.selectors.waiterForm.find('input[type="password"]').attr('placeholder', 'Password *');
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
                    context.selectors.waiterForm.find('input[type="password"]').attr('placeholder', 'Password');
                }

                context.selectors.waiterModal.modal('show');
            });
        },

        closeWaiterModal: function()
        {
            var context = this;

            context.selectors.waiterModal.on('hide.bs.modal', function()
            {
                context.resetWaiterModal();
            });
        },

        resetWaiterModal: function()
        {
            var context = this,
                form    = context.selectors.waiterForm;

            form.validate().resetForm();
            form.find('[name="waiter_id"]').removeAttr('disabled').val('');
            form.find('[name="first_name"]').val('');
            form.find('[name="password"]').val('');
            form.find('[name="_method"]').remove();

            context.selectors.waiterSubmitBtn.html('Add Waiter');
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
                    // alert('Waiter has been submitted successfully');
                    document.getElementById('addwaiterform').reset();
                    // location.reload(true);
                    XS.Common.handleSwalSuccess('Waiter has been submitted successfully.');
                },
                error: function(jqXHR, exception)
                {
                    console.log(jqXHR.status);
                    if( jqXHR.status === 422 )
                    {
                        const {error}   = jqXHR.responseJSON;
                        const {message} = error;

                        $.each(message, function(index, val)
                        {
                            context.selectors.waiterForm.find(`[name="${index}"]`).after(`<label class="error">${val[0]}</label>`);
                        });
                    }
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
                    context.selectors.kitchenForm.find('input[type="password"]').attr('placeholder', 'Password *');
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
                    context.selectors.kitchenForm.find('input[type="password"]').attr('placeholder', 'Password');
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
                context.selectors.kitchenId.attr('disabled', false);
                context.selectors.kitchenId.val('');
                context.selectors.kitchenSubmitBtn.html('Add Kitchen');
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
                        // alert('Kitchen has been submitted successfully');
                        document.getElementById('addkitchenform').reset();
                        // location.reload(true);
                        XS.Common.handleSwalSuccess('Kitchen has been submitted successfully.');
                    },
                    error: function(jqXHR, exception)
                    {
                        console.log(jqXHR.status);
                        if( jqXHR.status === 422 )
                        {
                            const {error}   = jqXHR.responseJSON;
                            const {message} = error;

                            $.each(message, function(index, val)
                            {
                                context.selectors.kitchenForm.find(`[name="${index}"]`).after(`<label class="error">${val[0]}</label>`);
                            });
                        }
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
                    barId       = $this.data('parent_id'),
                    pickupZones = $.parseJSON(moduleConfig.availableBarPickupZones);

                $("#pickup_points").children().remove();

                if( barId == undefined )
                {
                    context.selectors.barModalTitle.html('Add ');
                    context.addBarFormValidation();
                    context.selectors.barForm.attr('action', moduleConfig.barpickStore);
                    context.selectors.barForm.find('input[type="password"]').attr('placeholder', 'Password *');
                    context.getAvailablePickupPoints();
                }
                else
                {
                    context.selectors.barModalTitle.html('Edit ');
                    context.selectors.barSubmitBtn.html('Edit bar');
                    context.editBarFormValidation();
                    context.selectors.barForm.attr('action', moduleConfig.barpickUpdate.replace(':ID', barId));
                    context.selectors.barId.attr('disabled',true);
                    context.selectors.barForm.find('input[type="password"]').attr('placeholder', 'Password');
                    context.getAvailablePickupPoints();

                    setTimeout(function(){
                        context.getBarData(barId);
                    });
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
                context.selectors.barId.attr('disabled',false);
                $('#barpick_id').val('');
                context.selectors.barForm.find('[name="password"]').val();
                // $("#pickup_points").children().remove();
                $('#pickup_points').val('');
                context.selectors.barForm.removeAttr('action');
                context.selectors.barForm.find('input[type="hidden"]').remove();
                context.selectors.barSubmitBtn.html('Add Bar');
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
                    pickup_points: {
                        required:true,
                    },
                },
                messages: {
                    barpick_id: {
                        required: "Please enter barpick ID"
                    },
                    pickup_points: {
                        required: "please select pickup location"
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

        pickupPointValidation: function(){
            var context = this;
            context.selectors.barSubmitBtn.submit(function (e) {
                // var pickup_points = $("#pickup_points");
                if ($("#pickup_points").val() == "") {
                    e.preventDefault();
                    //If the "Please Select" option is selected display error.
                    // alert("Please select an option!");
                    XS.Common.handleSwalSuccessWithoutReload('Please select an option!');
                    return false;
                }
                return true;
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
                    // alert('Bar pick zone has been submitted successfully');
                    document.getElementById('addbarpickform').reset();
                    // location.reload(true);
                    XS.Common.handleSwalSuccess('Bar pick zone has been submitted successfully.');
                },
                error: function(jqXHR, exception)
                {
                    console.log(jqXHR.status);
                    if( jqXHR.status === 422 )
                    {
                        const {error}   = jqXHR.responseJSON;
                        const {message} = error;

                        $.each(message, function(index, val)
                        {
                            context.selectors.barForm.find(`[name="${index}"]`).after(`<label class="error">${val[0]}</label>`);
                        });
                    }
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
                    // $("#pickup_points").children().remove();
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

        getAvailablePickupPoints: function()
        {
            var context     = this,
                pickupZones = $.parseJSON(moduleConfig.availableBarPickupZones);

            if( !$.isEmptyObject(pickupZones) )
            {
                $.each( pickupZones, function(index, pickup_point)
                {
                    context.selectors.barForm.find('[name="pickup_points"]').append(`<option value='${pickup_point.id}'>${pickup_point.name}</option>`);
                });
            }
        }
        //end Bar
    }
})();