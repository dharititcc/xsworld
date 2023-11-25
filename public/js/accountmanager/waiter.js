$(document).ready(function() {
    var modal = $("#waiterModal");
    //waiterModal open pop up
    $(".waiter_modal").on("click",function(e) {
        e.preventDefault();
        var $this = $(this),
            // parent = $this.data('parent'),
            parent_id  = $this.data('parent_id'),
            type = $this.data('type');
            // $('.waiter_model_title').html('Add');
            modal.find('#addwaiterform').find('#user_id').val(parent_id);
            modal.modal('show');
    });
    $('.waiters').on('click',function(e) {
        e.preventDefault();
        $('#waiter_submitBtn').html('Add Waiter');
        $('.waiter_model_title').html('Add ');
    })

    //close modal pop up
    modal.on('hide.bs.modal',function(){
        var $this = jQuery(this);
        $this.find('#addwaiterform').find('.form-control').val('');
        $('#waiter_id').attr('disabled',false);
        var $alertas = $('#addwaiterform');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
    });

    $("#sidebarToggle1").on('click',function(e){
        e.preventDefault();
        $('body').removeClass('sb-sidenav-toggled');
    });
});

$('#waiter_submitBtn').click(function(e) {
    //getter fun validate
    var crudetype = $('#waiterModal').data('crudetype');
    if(crudetype === 1) {
        $("#addwaiterform").validate({
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
            
            submitHandler: function(form) {
                formsubmit(form)
            }
        });
    } else {
        $("#addwaiterform").validate({
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
    $('#waiter_submitBtn').html('Please Wait...');
    $('#waiter_submitBtn').attr('disabled',true);

    // XS.Common.btnProcessingStart($('#waiter_submitBtn'));
    var route = '';
    var crudetype = $('#waiterModal').data('crudetype');
    var data = new FormData(),
        user_id = $('#user_id').val();
        name = $('#waiter_name').val();
        waiter_id = $('#waiter_id').val();
        password = $('#password').val();
    
    data.append('first_name',name);
    data.append('user_id',user_id);
    data.append('waiter_id',waiter_id);
    data.append('password',password);
    if(crudetype === 1) {
        /// add waiter
        route = moduleConfig.waiterStore;
    } else {
        /// update waiter
        route = moduleConfig.waiterUpdate.replace(':ID', user_id),
        data.append('_method','PUT');
    }
    
    $.ajax({
        url:route,
        type:'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#waiter_submitBtn').html('Submit');
            $('#waiter_submitBtn').attr('disabled',false);
            // alert('Waiter has been submitted successfully');
            document.getElementById('addwaiterform').reset();
            // location.reload(true);
            XS.Common.handleSwalSuccess('Waiter has been submitted successfully.');
        }
    });
}

function getPickup(id) {
    $('#user_id').val(id);
    $('#waiterModal').data('crudetype',1);
}

function getWaiter(id)
{
    $('.waiter_model_title').html('Edit ');
    $('#waiter_submitBtn').html('Edit Waiter');
    $('#user_id').val(id);
    $('#waiter_id').attr('disabled',true);
    $.ajax({
        url: moduleConfig.waiterGet.replace(':ID',id),
        type: 'GET',
        success: function(res) {
            $('#waiter_name').val(res.first_name);
            $('#waiter_id').val(res.username);
            // $('#password').val(res.password);

            $('#waiterModal').data('crudetype',0);
            $('#waiterModal').modal('show');
        },
        error: function(data) {}
    });
}