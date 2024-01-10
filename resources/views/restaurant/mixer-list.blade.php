@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('restaurant.partials.mixertopbar')
@endsection
@section('content')
    <div class="outrbox">
        <div class="sort-by d-flex mb-4">
            <h2 class="yellow">Sort By</h2>
            <div class="searchbox"><input type="text" name="search" id="search" class="searchbar"
                    placeholder="Find a Mixer"></div>
        </div>
        <div class="mb-4 table-en-ds">
            <button class="bor-btn" id="disable">Disable Mixer</button>
            <button class="bor-btn ms-3" id="enable">Enable Mixer</button>
        </div>
        <div class="data-table drinks scroll-y h-600">
            <table width="100%" class="drink_datatable">
                <thead>
                    <tr valign="middle">
                        <th class="dt-left"><label class="cst-check"><input type="checkbox" id="allcheck" value=""><span class="checkmark"></span></label></th>
                        <th>Name</th>
                        <th class="price">Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Global popup -->
    <div class="modal fade" id="exampleModal" tabindex="-1" data-crudetype="1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="model_title">Add</span> Mixer</h2>
                </div>
                <div class="modal-body">
                    <form name="addmixer" id="mixerpopup" method="post" action="javascript:void(0)">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input id="mixer_id" type="hidden" class="mixer_id" name="mixer_id" />
                                <input type="text" name="name" class="form-control vari2" placeholder="Mixer Name">
                            </div>
                            <div class="form-group mb-4">
                                <input type="text" name="price" id="price" class="form-control vari2" placeholder="Mixer Price">
                            </div>
                            <div class="list-catg">
                                {{-- @foreach ($food_categories as $child)
                                    <label>
                                        <input type="checkbox" name="category[]" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach --}}
                                @foreach ($drink_categories as $child)
                                    <label>
                                        <input type="checkbox" name="category[]" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26 mt-4" id="submitBtn" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Global popup -->
@endsection
@section('pagescript')
<script src="{{asset('js/enableSweetalert.js')}}"></script>
<script src="{{asset('js/disableSweetalert.js')}}"></script>
    @parent
    <script type="text/javascript">
        var moduleConfig = {
            'getMixerlist': "{!! route('restaurants.mixers.index') !!}",
            'addMixer': "{!! route('restaurants.mixers.store') !!}",
            'getMixer': "{!! route('restaurants.mixers.show', ':ID') !!}",
            'updateMixer': "{!! route('restaurants.mixers.update', ':ID') !!}",
            currency: "{!! $restaurant->country->symbol !!}"
        };
        var modal = $("#exampleModal");
            // modal open pop up
            $('body').on('click', '.mixer_model', function(e)
            {
                e.preventDefault();
                var $this       = $(this),
                    type        = $this.data('type'),
                    mixer_id    = $this.data('mixer_id');
                modal.find('.model_title').html(`${type}`);

                if( type == 'Add' )
                {
                    $('.model_title').html('Add');
                    modal.find('form').find('input[name="_method"]').remove();
                    modal.find('form').attr('action', moduleConfig.addMixer);
                }
                else
                {
                    // ajax get data
                    $('.model_title').html('Edit');
                    // $('#cat_id').val(id);
                    $.ajax({
                        url: moduleConfig.getMixer.replace(':ID', mixer_id),
                        type: "GET",
                        success: function(response) {
                            modal.find('form').attr('action', moduleConfig.updateMixer.replace(':ID', mixer_id));
                            modal.find('form').append(`<input type="hidden" name="_method" value="PUT" />`);
                            $("input[name=name]").val(response.data.name);
                            $("input[name=price]").val(response.data.price);
                            $('input[name="category[]"]').val(response.data.categories);
                            var image = `
                                            <div class="pip">
                                                <img class="imageThumb" src="${ response.data.image != "" ? response.data.image : ''}" title="" />
                                                <i class="icon-trash remove"></i>
                                            </div>
                                        `;

                            if( response.data.image != "" )
                            {
                                $(".image_box").children('.pip').remove();
                                $("#upload").after(image);
                            }

                            $(".remove").click(function() {
                                $(this).parent(".pip").remove();
                            });

                            $('#exampleModal').data('crudetype', 0);
                            $('#exampleModal').modal('show');
                        },
                        error: function(data) {}
                    });
                }

                modal.modal('show');
            });

            // close modal pop up
            modal.on('hide.bs.modal', function()
            {

                var $this = jQuery(this);
                $this.find('#mixerpopup').find('.form-control').val('');
                var $alertas = $('#mixerpopup');
                $alertas.validate().resetForm();
                $alertas.find('.error').removeClass('error');


                $this.find('#mixerpopup').find('.pip').remove();
                $this.find('form').removeAttr('action');
                $this.find('input[name="category[]"]:checked').each(function(){
                    $(this).prop('checked', false);
                });
                $this.find('input[name="_method"]').remove();
            });

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
                //alert("Your browser doesn't support to File API")
                XS.Common.handleSwalSuccessWithoutReload("Your browser doesn't support to File API.");
            }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [day, month, year].join('-');
        }
        load_data();

        function load_data(data = null) {
            var table = $('.drink_datatable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                order: [[1, 'desc']],
                ajax: {
                    url: moduleConfig.getMixerlist,
                    data: data,
                },
                columns: [
                    {
                        "data": "", // can be null or undefined
                        "defaultContent": "",
                        "width": "10%",
                        "sortable": false,
                        render: function(data, type, row) {
                            return '<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="' +
                                row.id + '"><span class="checkmark"></span></label>'
                        }
                    },
                    {
                        "data": "name", // can be null or undefined ->type
                        "defaultContent": "",
                        "width": "30%",
                        render: function(data, type, row) {
                            var color = (row.is_available == 1) ? "green" : "red";
                            return '<div class="prdname ' + color + '"> ' + row.name +
                                ' </div><a href="javascript:void(0)" data-type="Edit" data-mixer_id="'+row.id+'" class="edit mixer_model" data-id=" ' + row.id +
                                ' ">Edit</a>  <div class="add-date">Added ' + formatDate(row
                                    .created_at) + '</div>'
                        }
                    },
                    {
                        "data": "price", // can be null or undefined
                        "defaultContent": "",
                        "width": "30%",
                        "bSortable": false,
                        render: function(data, type, row) {
                            var text = "";
                            if (row.variations.length > 0) {
                                for (let i = 0; i < row.variations.length; i++) {
                                    text += `<label class="price">${moduleConfig.currency}${row.variations[i]['price']}</label>`;
                                }
                                return text
                            }
                            return `<label class="price">${moduleConfig.currency}${row.price}</label>`;
                        }
                    },
                    {
                        "data": "status", // can be null or undefined
                        "defaultContent": "",
                        "width": "30%",
                        "bSortable": false,
                        render: function(data, type, row) {
                            var html = '';
                            if (row.is_featured == 1) {
                                html += '<div class="green"><strong>Featured Drink</strong> </div>'
                            }
                            if (row.is_available == 1) {
                                html += '<div class="green"><strong> In-Stock</strong></div>'
                            } else {
                                html += '<div class="red"><strong>  Out Of Stock</strong></div>'
                            }
                            return html
                        }
                    },
                ],
                drawCallback: function ( settings )
                {
                    $('.drink_datatable').find('tbody tr').find('td:first').addClass('dt-center');
                }
            });
        }

        $("#search").keyup(function() {
            $('.drink_datatable').DataTable().destroy();
            var data = [];
            data['search_main'] = this.value;
            load_data(data);
        });

        $('#allcheck').on('click', function(){
                // Get all rows with search applied
                $('.drink_datatable tbody :checkbox').prop('checked', $(this).is(':checked'));
                e.stopImmediatePropagation();
        });

        $(document).ready(function() {
            $('.checkboxitem').click(function() {
                // alert(1);
                if ($(this).is(':checked')) {
                    $('#disable').removeAttr('disabled');
                    $('#enable').removeAttr('disabled');
                } else {
                    $('#id_of_your_button').attr('disabled');
                }
            });

            // $('#allcheck').click(function(e) {
            //     e.preventDefault();
            //     $('input[name="id"]').attr('checked', 'checked');
            //     // $(this).val('uncheck all');
            // }, function() {
            //     $('input[name="id"]').removeAttr('checked');
            //     //$(this).val('check all');
            // })
        });
        $("#mixerpopup").validate({
            ignore:[],
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                price: {
                    required: true,
                    number: true,
                    maxlength: 10
                },
                "category[]": {
                    required: true,
                },
                image: {
                    required: true,
                    // accept: "image/jpg,image/jpeg,image/png,image/gif"
                },
                message: {
                    required: true
                },
            },
            messages: {
                name: {
                    required: "Please enter name",
                    maxlength: "Your name maxlength should be 50 characters long."
                },
                price: {
                    required: "Please enter price",
                },
                "category[]": {
                    required: "Please Select category",
                },
                image: {
                    required: "Please enter files", //accept: 'Not an image!'
                }
            },
            errorPlacement: function(error, element) {
                if(element.attr("type") == "checkbox") {
                    error.insertAfter($(element).closest('div'));
                } else {
                    error.insertAfter($(element));
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var id = $('#mixer_model_a').data('id');
                $('#submitBtn').html('Please Wait...');
                $("#submitBtn").attr("disabled", true);

                var data = new FormData(form);

                $.ajax({
                    url: form.getAttribute('action'),
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                        //alert('Mixer has been added successfully');
                        //location.reload(true);
                        XS.Common.handleSwalSuccess('Mixer has been added successfully.');
                    },
                    error: function(xhr)
                    {
                        if( xhr.status === 422 )
                        {
                            const {error}   = xhr.responseJSON;
                            const {message} = error;

                            $.each(message, function(index, val)
                            {
                                var elem = $("#mixerpopup").find(`[name="${index}"]`);

                                if(elem.is("input:text"))
                                {
                                    elem.closest('#price').after(`<label class="error">${val[0]}</label>`);
                                }
                            });
                        }
                    },
                    complete: function()
                    {
                        XS.Common.btnProcessingStop($("#submitBtn"));
                    }
                });
            }
        });
    </script>
@endsection
