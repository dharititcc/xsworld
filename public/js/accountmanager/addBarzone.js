$(document).ready(function() {
    var modal = $("#addBarzone");
    //addBarzone open pop up
    $(".barpickzone_modal").on("click",function(e) {
        e.preventDefault();
        var $this = $(this),
            // parent = $this.data('parent'),
            parent_id  = $this.data('parent_id'),
            type = $this.data('type');
            modal.find('#addbarpickform').find('#user_id').val(parent_id);
            modal.modal('show');
    });
    $('.barzone').on('click',function(e) {
        e.preventDefault();
        $('#barpickzone_submitBtn').html('Add Bar');
        $('.barzone_model_title').html('Add Bar');
    })

    //close modal pop up
    modal.on('hide.bs.modal',function(){
        var $this = jQuery(this);
        $this.find('#addbarpickform').find('.form-control').val('');
        $('#barpick_id').attr('disabled',false);
        var $alertas = $('#addbarpickform');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
    });

    $("#sidebarToggle1").on('click',function(e){
        e.preventDefault();
        $('body').removeClass('sb-sidenav-toggled');
    });
});

$('#barpickzone_submitBtn').click(function(e) {
    //getter fun validate
    var crudetype = $('#addBarzone').data('crudetype');
    if(crudetype === 1) {
        $("#addbarpickform").validate({
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
            
            submitHandler: function(form) {
                formsubmit(form)
            }
        });
    } else {
        $("#addbarpickform").validate({
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
    $('#barpickzone_submitBtn').html('Please Wait...');
    $('#barpickzone_submitBtn').attr('disabled',true);
    var route = '';
    var crudetype = $('#addBarzone').data('crudetype');
    var data = new FormData(),
        barpick_id = $('#barpick_id').val();
        pickup_points = $("#pickup_points option:selected").val();
        console.log(pickup_points);
        password = $('#password').val();
        user_id = $('#user_id').val();
    
    data.append('barpick_id',barpick_id);
    data.append('password',password);
    data.append('pickup_points',pickup_points);
    data.append('user_id',user_id);
    if(crudetype === 1) {
        /// add bar
        route = moduleConfig.barpickStore;
    } else {
        /// update bar
        route = moduleConfig.barpickUpdate.replace(':ID', user_id),
        data.append('_method','PUT');
    }
    $.ajax({
        url:route,
        type:'POST',
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#barpickzone_submitBtn').html('Submit');
            $('#barpickzone_submitBtn').attr('disabled',false);
            // alert('Bar pick zone has been submitted successfully');
            document.getElementById('addbarpickform').reset();
            // location.reload(true);
            XS.Common.handleSwalSuccess('Bar pick zone has been submitted successfully.');
        }
    });
}

function getBarpickzone(id)
{
    $('barzone_model_title').html('Edit');
    $('#barpickzone_submitBtn').html('Edit Bar');
    $('#user_id').val(id);
    $('#barpick_id').attr('disabled',true);
    $.ajax({
        url: moduleConfig.barpickGet.replace(':ID',id),
        type: 'GET',
        success: function(res) {
            // console.log(res);

            var pickupPoint = res.pickup_point,
                options     = `
                    <option value="${pickupPoint.id}">${pickupPoint.name}</option>
                `;

            console.log($("#pickup_points").append(options));
            $("#pickup_points").append(options);
            $('#barpick_id').val(res.username);
            $('#pickup_points').val(pickupPoint.id);

            $("#pickup_points option").each(function() {
                $(this).siblings('[value="'+ this.value +'"]').remove();
              });

            $('#addBarzone').data('crudetype',0);
            $('#addBarzone').modal('show');
        },
        error: function(data) {}
    });
}