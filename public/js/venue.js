(function () 
{
    XS.Venue = {
        selectors: {
            venueModalBtn:  $('.venue_popup_modal'),
            venueLabelTime: $('.times'),
            venueStartTime: $('.start_time'),
            venueCloseTime: $('.close_time'),
            venueSubmitBtn: $('#venue_submitBtn'),
            venueForm:      $('#addtimerform'),
            venueResImage:  $('.venue_res_image'),
            venueImageUpload:   $('#img-upload'),
            venueImageForm: $('#addimageform'),
            venueImgSubmitBtn:  $('#venueImg_submitBtn'),
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

            context.selectors.venueSubmitBtn.on("click", function(e) {
                e.preventDefault();
                var $this   = $(this),
                    data = new FormData(context.selectors.venueForm.get(0));
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
                        alert('opening Timming Updated successfully');
                        context.selectors.venueSubmitBtn.attr("style","display: none");
                        context.selectors.venueStartTime.attr("style","display: none");
                        context.selectors.venueCloseTime.attr("style","display: none");
                        context.selectors.venueLabelTime.show();
                        location.reload();
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
                        alert('Image Updated successfully');
                        context.selectors.venueImageUpload.attr("style","display: none");
                        context.selectors.venueImgSubmitBtn.attr("style","display: none");
                        location.reload();
                    },
                    error:function(request, status, error) {
                        console.log('Error');
                    },
                });
            });
        },
    }
})();