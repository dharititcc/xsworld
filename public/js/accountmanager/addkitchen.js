$(document).ready(function() {
    var modal = $("#addkitchen");
    //addkitchen open pop up
    $(".kitchen_popup_modal").on("click",function(e) {
        e.preventDefault();
        var $this = $(this),
            // parent = $this.data('parent'),
            parent_id  = $this.data('parent_id'),
            type = $this.data('type');
            modal.find('#addkitchenform').find('#user_id').val(parent_id);
            modal.modal('show');
    });
    $('.kitchen').on('click',function(e) {
        e.preventDefault();
        $('#kitchen_submitBtn').html('Add Kitchen');
        $('.kitchen_model_title').html('Add ');
    })

    //close modal pop up
    modal.on('hide.bs.modal',function(){
        var $this = jQuery(this);
        $this.find('#addkitchenform').find('.form-control').val('');
        $('#kitchen_id').attr('disabled',false);
        var $alertas = $('#addkitchenform');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
    });

    $("#sidebarToggle1").on('click',function(e){
        e.preventDefault();
        $('body').removeClass('sb-sidenav-toggled');
    });
});

$('#kitchen_submitBtn').click(function(e) {
    //getter fun validate
    var crudetype = $('#addkitchen').data('crudetype');
    if(crudetype === 1) {
        $("#addkitchenform").validate({
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

                pickup_points: {
                    required: "please select pickup points"
                },
                
                password: {
                    required: "Please enter Password"
                }
            },
            
            submitHandler: function(form) {
                formsubmit(form)
            }
        });
    } else {
        $("#addkitchenform").validate({
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
    $('#kitchen_submitBtn').html('Please Wait...');
    $('#kitchen_submitBtn').attr('disabled',true);
    var route = '';
    var crudetype = $('#addkitchen').data('crudetype');
    var kitchen_point = [];
    var data = new FormData(),
        kitchen_id = $('#kitchen_id').val();
        // kitchen_point = $("#kitchen_point option:selected").val();
        password = $('#password').val();
        user_id = $('#user_id').val();
    
        $.each($("#kitchen_point option:selected"), function(i) {
            kitchen_point[i] = $(this).val();
        });

    data.append('kitchen_id',kitchen_id);
    data.append('password',password);
    data.append('kitchen_point',kitchen_point);
    data.append('user_id',user_id);
    if(crudetype === 1) {
        /// add kitchen
        route = moduleConfig.kitchenStore;
    } else {
        /// update kitchen
        route = moduleConfig.kitchenUpdate.replace(':ID', user_id),
        data.append('_method','PUT');
    }
    $.ajax({
        url:route,
        type:'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#kitchen_submitBtn').html('Submit');
            $('#kitchen_submitBtn').attr('disabled',false);
            // alert('Kitchen has been submitted successfully');
            document.getElementById('addkitchenform').reset();
            // location.reload(true);
            XS.Common.handleSwalSuccess('Kitchen has been submitted successfully.');
        }
    });
}

function getkitchen(id)
{
    $('kitchen_model_title').html('Edit');
    $('#kitchen_submitBtn').html('Edit Kitchen');
    $('#user_id').val(id);
    $('#kitchen_id').attr('disabled',true);
    $.ajax({
        url: moduleConfig.kitchenGet.replace(':ID',id),
        type: 'GET',
        success: function(res) {

            $.each(res.pickup_point_name, function (key, val) {
                $('select option[value="'+val.id+'"]').attr("selected",true);
            });

            $('#kitchen_id').val(res.username);
            $('#addkitchen').data('crudetype',0);
            $('#addkitchen').modal('show');
        },
        error: function(data) {}
    });
}