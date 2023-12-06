$(document).ready(function() {
    var modal = $("#pickupModal");
    //pickupModal open pop up
    $(".pickup_point_modal").on("click",function(e) {
        e.preventDefault();
        var $this = $(this),
            // parent = $this.data('parent'),
            parent_id  = $this.data('parent_id'),
            type = $this.data('type');
            var pickupType = $(this).data('pickuptype');
            $('#types').val(pickupType);
            modal.find('.pickup_model_title').html(`${type}`);
            modal.find('#pickupForm').find('#pickup_id').val(parent_id);
            modal.modal('show');
    });

    $('#get_type_drink').on('click',function() {
        var pickupType = $(this).data('pickuptype');
        $('.pickup_model_title').html('Add Drink');
        $('#types').val(pickupType);
    });

    $('#get_type_food').on('click',function() {
        $('.pickup_model_title').html('Add Food');
        var pickupType = $(this).data('pickuptype');
        $('#types').val(pickupType);
    });

    //close modal pop up
    modal.on('hide.bs.modal',function(){
        var $this = jQuery(this);
        $this.find('#pickupForm').find('.form-control').val('');
        $this.find('#pickupForm').find('.pip').remove();
        $this.find('#pickupForm').find('#pickup_id').val('');
        var $alertas = $('#pickupForm');
        $alertas.validate().resetForm();
        $this.find('#pickupForm').find('.error').remove();
    });

    //image preview
    if (window.File && window.FileList && window.FileReader) {
        $(".files").on("change", function(e) {
            var clickedButton = this,
                files = e.target.files,
                filesLength = files.length;
            for (var i = 0; i < filesLength; i++) {
                var f = files[i],
                    fileReader = new FileReader();
                fileReader.onload = (function(e) {
                    var file = e.target,
                        thumbnail = `
                            <div class="pip">
                                <img class="imageThumb" src="${e.target.result}" title="${file.name}" />
                                <i class="icon-trash remove"></i>
                            </div>
                        `;
                    $(thumbnail).insertAfter(clickedButton);
                    $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                    });
                });
                fileReader.readAsDataURL(f);
            }
        });
    } else {
        // alert("Your browser doesn't support to File API")
        XS.Common.handleSwalSuccessWithoutReload("Your browser doesn't support to File API.");
    }

    $("#sidebarToggle1").on('click',function(e){
        e.preventDefault();
        $('body').removeClass('sb-sidenav-toggled');
    });
});

$('#pickup_submitBtn').click(function(e) {
    //getter fun validate
    var crudetype = $('#pickupModal').data('crudetype');
    if(crudetype === 1) {
        $("#pickupForm").validate({
            errorPlacement: function($error, $element) {
                $error.appendTo($element.closest("div"));
            },
            rules: {
                pickup_name: {
                    required:true,
                    maxlength: 50
                },
                files: {
                    required:true,
                },
            },
            messages: {
                pickup_name:{
                    required: "Please enter name",
                    maxlength: "Your name maxlength should be 50 characters long."
                },
                files: {
                    required: "Please upload Images"
                }
            },

            submitHandler: function(form) {
                formsubmit(form)
            }
        });
    } else {
        $("#pickupForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
            },
            messages: {
                name: {
                    required: "Please enter name",
                    maxlength: "Your name maxlength should be 50 characters long."
                }
            },
            submitHandler: function(form) {
                formsubmit(form);
            }
        });
    }
});
function formsubmit(form) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#pickup_submitBtn').html('Please Wait...');
    $('#pickup_submitBtn').attr('disabled',true);
    var route = '';
    var crudetype = $('#pickupModal').data('crudetype');
    var data = new FormData(),
        name = $('#pickup_name').val();
        pickup_id = $('#pickup_id').val();
        photo = $('#upload').prop('files')[0];
        types = $('#types').val();

    data.append('name',name);
    data.append('photo',photo);
    data.append('pickup_id',pickup_id);
    data.append('types',types);
    if(crudetype === 1) {
        route = routeStore;
    } else {
        route = routeUpdate.replace(':ID', pickup_id),
        data.append('_method','PUT');
    }

    jQuery(form).find('.error').remove();

    $.ajax({
        url:route,
        type:'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            // alert('Pickup point has been submitted successfully');
            document.getElementById('pickupForm').reset();
            // location.reload(true);
            XS.Common.handleSwalSuccess('Pickup point has been submitted successfully.');
        },
        error: function(xhr)
        {
            if( xhr.status === 422 )
            {
                var {error} = xhr.responseJSON,
                    fields  = jQuery(form).find('input[type="file"]'),
                    messages= error.message;

                $.each(messages, function(eIndex, eMessage)
                {
                    fields.each(function(index, elem)
                    {
                        if( jQuery(elem).attr('type') == 'file' )
                        {
                            jQuery(elem).closest('.image_box').after(`<span class="error mb-2 d-block">${eMessage[0]}</span>`);
                        }
                        else
                        {
                            jQuery(elem).after(`<span class="error">${eMessage[0]}</span>`);
                        }
                    });
                });
            }

            if( xhr.status === 403 )
            {
                var {error} = xhr.responseJSON;
                jQuery(form).find('input[type="text"]').after(`<span class="error">${error.message}</span>`)
            }
        },
        complete: function()
        {
            $('#pickup_submitBtn').html('Save');
            $('#pickup_submitBtn').attr('disabled',false);
        }
    });
}
function getPickup(id) {
    $('#pickup_id').val(id);
    $('#pickupModal').data('crudetype',1);
}


function updatePickup(id) {
    $('.pickup_model_title').html('Edit');
    $('#pickup_id').val(id);
    $.ajax({
        url: routeGet.replace(':ID',id),
        type: 'GET',
        success: function(res) {
            $('#pickup_name').val(res.data.name);
            var image = `<div class="pip">
            <img class="imageThumb" src="${ res.data.image!="" ?res.data.image :'#'}" title="" />
            <i class="icon-trash remove"></i>
            </div>`;
            $('.image_box').children('.pip').remove();
            $('#upload').after(image);
            $('.remove').click(function() {
                $(this).parent('.pip').remove();
            });

            $('#pickupModal').data('crudetype',0);
            $('#pickupModal').modal('show');
        },
        error: function(data) {}
    });
}

// function deleteConform(id) {
//     if(!confirm("Are You Sure You want to delete this?")) {
//         event.preventDefault();
//     } else {
//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             url: "pickup/" +id,
//             type: "DELETE",
//             success: function(res) {
//                 alert('Pickup is deleted successfully');
//                 location.reload(true);
//             }
//         });
//     }
// }