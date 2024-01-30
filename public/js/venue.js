(function ()
{
    XS.Venue = {
        selectors: {
            venueModalBtn:      $('.venue_popup_modal'),
            venueLabelTime:     $('.times'),
            venueStartTime:     $('.start_time'),
            venueCloseTime:     $('.close_time'),
            venueSubmitBtn:     $('#venue_submitBtn'),
            venueForm:          $('#addtimerform'),
            venueResImage:      $('.venue_res_image'),
            venueImageUpload:   $('#img-upload'),
            venueImageForm:     $('#addimageform'),
            venueImgSubmitBtn:  $('#venueImg_submitBtn'),
            restaurantEditBtn:  $('.edit_venue_data'),
            restaurantForm:     $("#addvenueform"),
            restaurantSubmitBtn:$("#venue_data_submitBtn"),
        },

        init: function()
        {
            this.addHandler();
        },

        addHandler: function()
        {
            var context = this;

            context.editVenueModal();
            context.editVenueImage();
            context.editVenueData();
        },

        editVenueModal: function()
        {
            var context = this;
            context.selectors.venueModalBtn.on("click", function() {
                context.selectors.venueLabelTime.hide();
                context.selectors.venueStartTime.removeAttr("style");
                context.selectors.venueCloseTime.removeAttr("style");
                context.selectors.venueSubmitBtn.removeAttr("style");

            });

            context.selectors.restaurantEditBtn.on("click", function() {
                context.selectors.restaurantSubmitBtn.removeAttr("style");
                $('#res_name').removeAttr('disabled');
                $('#street1').removeAttr('disabled');
                $('#street2').removeAttr('disabled');
                $('#city').removeAttr('disabled');
                $('#state').removeAttr('disabled');
                $('#postcode').removeAttr('disabled');
                $('#specialisation').removeAttr('disabled');
            });

            context.selectors.venueSubmitBtn.on("click", function(e) {
                e.preventDefault();
                var $this       = $(this),
                    data        = new FormData(context.selectors.venueForm.get(0)),
                    timings     = jQuery('.opening_timing_table');

                timings.find('span').remove();

                timings.find('tr').each(function(index, val)
                {
                    var $this   = $(this),
                        start   = $this.find('.start_time'),
                        end     = $this.find('.close_time');

                    if( start.val() && !end.val() )
                    {
                        $(`<span class="text-danger">Please add end time.<span/>`).insertAfter($this);
                    }

                    if( !start.val() && end.val() )
                    {
                        $(`<span class="text-danger">Please add start time.<span/>`).insertAfter($this);
                    }
                });

                if( timings.find('span').length > 0 )
                {
                    return false;
                }

                $.ajax({
                    url:moduleConfig.venueStore,
                    type:'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // alert('opening Timming Updated successfully');
                        context.selectors.venueSubmitBtn.attr("style","display: none");
                        context.selectors.venueStartTime.attr("style","display: none");
                        context.selectors.venueCloseTime.attr("style","display: none");
                        context.selectors.venueLabelTime.show();
                        // location.reload();
                        XS.Common.handleSwalSuccess('Opening Timming Updated successfully.');
                    },
                    error:function(request, status, error) {
                        console.log('Error');
                    },
                });
            })
        },

        editVenueImage: function()
        {
            var context = this;
            context.selectors.venueResImage.on("click", function() {
                context.selectors.venueImageUpload.removeAttr("style");
                context.selectors.venueImgSubmitBtn.removeAttr("style");
            });
            context.selectors.venueImgSubmitBtn.on("click", function() {
                var $this   = $(this),
                    data = new FormData(context.selectors.venueImageForm.get(0));
                $.ajax({
                    url:moduleConfig.resImageUpload,
                    type:'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        // alert('Image Updated successfully');
                        context.selectors.venueImageUpload.attr("style","display: none");
                        context.selectors.venueImgSubmitBtn.attr("style","display: none");
                        // location.reload();
                        XS.Common.handleSwalSuccess('Image Updated successfully.');
                    },
                    error:function(request, status, error) {
                        console.log('Error');
                    },
                });
            });
        },

        editVenueData: function()
        {
            var context = this;
            context.selectors.restaurantSubmitBtn.on('click', function() {

                var $this   = $(this),
                    data    = new FormData(context.selectors.restaurantForm.get(0));

                $.ajax({
                    url: moduleConfig.venueEdit,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('#res_name').attr('disabled');
                        $('#street1').attr('disabled');
                        $('#street2').attr('disabled');
                        $('#city').attr('disabled');
                        $('#state').attr('disabled');
                        $('#postcode').attr('disabled');
                        $('#specialisation').attr('disabled');
                        context.selectors.restaurantSubmitBtn.attr("style","display: none");
                        // location.reload();
                        XS.Common.handleSwalSuccess('Venue Updated successfully.');
                    },
                    error:function(jqXHR) {
                        if( jqXHR.status === 403 )
                        {
                            const {error}   = jqXHR.responseJSON;
                            const {message} = error;

                            XS.Common.handleSwalError(message, true);
                        }
                    },
                })
            });
        },
    }
})();