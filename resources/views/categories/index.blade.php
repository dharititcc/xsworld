@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('categories.partials.topbar')
@endsection
@section('content')
    <!-- Page content-->

    <div class="outrbox">
        @if ($categories->count())
            @php
                $cnt = 1;
            @endphp
            @foreach ($categories as $category)
                <div class="d-flex mb-4 justify-content-between doubl-line">
                    <h2 class="yellow">{{ $category->name }} Categories</h2>
                    <div class="count-item">Total: {{ $category->children->count() }}</div>
                </div>

                <div class="grid colmn-5 f-ctg">
                    @if ($category->children->count())
                        @foreach ($category->children as $child)
                            <div class="catg-box overly">

                                <form method="POST" action="{{ route('restaurants.categories.destroy', $child->id) }}">
                                    @csrf
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button type="submit" class="show_confirm" data-toggle="tooltip" title='Delete'><i class="icon-trash"></i></button>
                                </form>

                                {{-- <button onclick="return deleteConform({{ $child->id }});"><i
                                        class="icon-trash"></i></button> --}}
                                {{-- <a  onclick="return deleteConform('Are you sure?')" href="#"><i class="icon-trash"></i></a> --}}
                                <figure onClick="updateCategory({{ $child->id }})" data-child_id="{{ $child->id }}" data-type="Edit" data-parent_id="{{ $category->id }}" data-parent="{{ $category->name }}" class="category_model"><img src="{{ $child->image != '' ? $child->image : asset('img/logo.png') }}"
                                        alt="{{ $child->name }}">
                                    <figcaption><span> {{ $child->name }}</span></figcaption>
                                </figure>
                            </div>
                        @endforeach
                        @endif
                        <a href="javascript:void(0);" data-parent="{{ $category->name }}" data-parent_id="{{ $category->id }}" data-type="Add" class="catg-box add overly category_model">
                            <figure><i class="icon-plus"> </i></figure>
                        </a>
                </div>
                @if ($categories->count() !== $cnt)
                    <div class="gldnline-sepr mb-5 mt-5"></div>
                @endif
                @php
                    $cnt++;
                @endphp
            @endforeach
        @endif
    </div>


    <!-- Global popup -->
    <div class="modal fade" id="exampleModal" data-crudetype="1" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="model_title">Add Food</span> Category</h2>
                </div>
                <div class="modal-body">
                    <form name="addcategory" id="categorypopup" method="post" action="javascript:void(0)">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <div class="list-catg">
                                    {{-- @foreach ($categories as $category)
                                        <label>
                                            <input type="checkbox" name="category" id="category" value="{{ $category->id }}">
                                            <span>{{ $category->name }}</span>
                                        </label>
                                    @endforeach --}}
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <input type="text" name="name" class="form-control vari2" placeholder="Category Name">
                                <input id="category_id" type="hidden" class="category_id" name="category_id" />
                                <span class="error" id="duplicate_category"></span>
                            </div>
                            <div class="form-group mb-4">
                                <input id="cat_id" type="hidden" class="cat_id" name="cat_id" />
                            </div>
                            <div class="form-group grey-brd-box custom-upload mb-5 image_box">
                                <input id="upload" type="file" class="files" name="image" accept="image/*" hidden />
                                <label for="upload"><span> Add Category Feature Image (This can be changed).</span> <i
                                        class="icon-plus"></i></label>
                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26" id="submitBtn" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Global popup -->

    <!-- Add category popup -->
    <div class="modal fade" id="cat_modal" data-crudetype="1" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="model_title">Add </span> Category</h2>
                </div>
                <div class="modal-body">
                    <form name="addcategory" id="add_form_category" method="post" action="javascript:void(0)">
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <div class="list-catg">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <select class="cat_name form-control vari2" >Category List
                                    <option value="">Food </option>
                                    <option value="">Drinks </option>
                                </select>
                            </div>
                        </div>
                        <button class="bor-btn w-100 font-26" id="submitCatBtn" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove categories -->
    <div class="modal fade wd800" id="remove_Ctg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- <div class="modal-header justify-content-start ">
                        <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
                        <h2>Remove Categories</h2>
                    </div> -->
                <div class="modal-body">
                    <div style="min-height: 300px;">
                        @foreach ($categories as $category)
                            <h2 class="yellow mb-4">{{ $category->name }} Categories</h2>
                            <div class="list-catg">
                                @foreach ($category->children as $child)
                                    <label>
                                        <input type="checkbox" name="category" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="gldnline-sepr"></div>
                        @endforeach
                    </div>
                    <button class="bor-btn w-100 font-26 show_confirm" type="button">Remove</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script src="{{asset('js/sweetalert.js')}}"></script>
    @parent
    <script>
        var moduleConfig = {
            'addCategory': "{!! route('restaurants.categories.store') !!}",
            'getCategory': "{!! route('restaurants.categories.show', ':ID') !!}",
            'updateCategory': "{!! route('restaurants.categories.update', ':ID') !!}",
            'categoryName': "{!! route('restaurants.categoryName') !!}",
        };
        $(document).ready(function() {
            var modal = $("#exampleModal");

            XS.Common.fileReaderBind();
            // modal open pop up
            $('.category_model').on('click', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    parent      = $this.data('parent'),
                    parent_id   = $this.data('parent_id'),
                    type        = $this.data('type');

                modal.find('.model_title').html(`${type} ${parent}`);
                modal.find('#categorypopup').find('#category_id').val(parent_id);

                modal.modal('show');
            });

            // close modal pop up
            modal.on('hide.bs.modal', function()
            {
                var $this = jQuery(this);

                $this.find('#categorypopup').find('.form-control').val('');

                $this.find('#categorypopup').find('.pip').remove();
                $this.find('#categorypopup').find('#category_id').val('');
                $this.find('#categorypopup').find('#duplicate_category').text('');
                var $alertas = $('#categorypopup');
                $alertas.validate().resetForm();
                $alertas.find('.error').remove();
            });

            $('#sidebarToggle1').on('click', function(e) {
                e.preventDefault();

                $('body').removeClass('sb-sidenav-toggled');
            });
        });

    $('.add_category').on("click",function() {
        $('#cat_modal').modal('show');
    });

    $('#submitCatBtn').on("click", function() {
        var $this = $(this);
        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#submitBtn').html('Please Wait...');
                $("#submitBtn").attr("disabled", true);

                var data = new FormData(),
                    cat_name = $( ".cat_name option:selected" ).text();

                    console.log(cat_name);
                data.append('name', cat_name);

                $.ajax({
                    url: moduleConfig.categoryName,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response)
                    {
                        $("#cat_modal").modal('hide');
                        XS.Common.handleSwalSuccess('Category form has been submitted successfully');
                    },
                    error: function(xhr)
                    {
                        if( xhr.status == 403 )
                        {
                            var {error} = xhr.responseJSON;
                            $this.closest('#add_form_category').find('.cat_name').after(`<span class="error">${error.message}</span>`);

                            console.log($this.closest('#add_form_category'));
                        }
                    },
                    complete: function()
                    {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                    }
                });
    });

    $('#submitBtn').click(function(e)
    {
        var crudetype = $('#exampleModal').data('crudetype'); //getter
        if (crudetype === 1)
        {
                $("#categorypopup").validate({
                rules: {
                    name: {
                        maxlength: 20
                    },
                    image: {
                        required: true,
                    },

                    message: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 20 characters long."
                    },
                    image: {
                        required: "Please enter files", //accept: 'Not an image!'
                    }
                },
                submitHandler: function(form) {
                    formsubmit(form);
                }
            });
        }
        else
        {
            $("#categorypopup").validate({
                rules: {
                    name: {
                        maxlength: 20
                    },
                    message: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 20 characters long."
                    }
                },
                submitHandler: function(form) {
                    formsubmit(form);
                }
            });
        }
    });
    function formsubmit(form)
    {
        $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#submitBtn').html('Please Wait...');
                $("#submitBtn").attr("disabled", true);
                var route = "";
                var crudetype = $('#exampleModal').data('crudetype'); //getter
                var data = new FormData(),
                    name = $("input[name=name]").val(),
                    category_id = $("input[name=cat_id]").val(),
                    parent_id = $("input[name=category_id]").val(),
                    photo = $('#upload').prop('files')[0];

                data.append('name', name);
                if( $('#upload').prop('files').length > 0 )
                {
                    data.append('photo', photo);
                }
                data.append('category_id', category_id);
                data.append('parent_id', parent_id);

                if (crudetype === 1) {
                    route = moduleConfig.addCategory;
                } else {
                    route = moduleConfig.updateCategory.replace(':ID', category_id),
                    data.append('_method', 'PUT');
                }

                // remove error classes
                jQuery(form).find('.error').remove();

                if( jQuery(form).find('.pip').length == 0 )
                {
                    jQuery(form).find('input[type="file"]').closest('.form-group').after(`<span class="error">The image field is required.</span>`);
                    $('#submitBtn').html('Save');
                    $("#submitBtn").removeAttr("disabled");
                    return false;
                }

                $.ajax({
                    url: route,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                        $("#exampleModal").modal('hide');
                        XS.Common.handleSwalSuccess('Category form has been submitted successfully');
                    },
                    error: function(xhr)
                    {
                        if( xhr.status === 422 )
                        {
                            var {error} = xhr.responseJSON,
                                fields  = jQuery(form).find('input[type="text"], input[type="file"]'),
                                messages= error.message;

                            $.each(messages, function(eIndex, eMessage)
                            {
                                fields.each(function(index, elem)
                                {
                                    if( jQuery(elem).attr('name') ==  eIndex)
                                    {
                                        if( jQuery(elem).attr('type') == 'file' )
                                        {
                                            jQuery(elem).closest('.form-group').after(`<span class="error">${eMessage[0]}</span>`);
                                        }
                                        else
                                        {
                                            jQuery(elem).after(`<span class="error">${eMessage[0]}</span>`);
                                        }
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
                        $('#submitBtn').html('Save');
                        $("#submitBtn").removeAttr("disabled");
                    }
                });
    }

    function getCategory(id)
    {
        $('#category_id').val(id);
        $('#exampleModal').data('crudetype', 1);
        $('.model_title').html('Add ');
    }

    function updateCategory(id)
    {
        $('.model_title').html('Edit ');
        $('#cat_id').val(id);
        $.ajax({
            url: moduleConfig.getCategory.replace(':ID', id),
            type: "GET",
            success: function(response) {
                console.log(response);
                $("input[name=name]").val(response.data.name);
                // $("input[name=file]").val(response.data.image);
                var image = `
                                <div class="pip">
                                    <img class="imageThumb" src="${ response.data.image!="" ? response.data.image : '#'}" title="${response.data.image_name}" />
                                    <i class="icon-trash remove"></i>
                                </div>
                            `;

                $(".image_box").children('.pip').remove();
                if( response.data.image!= "" )
                {
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
    $(function()
    {
        $('#disable').click(function(e) {
            e.preventDefault();
            $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
                var data = [];
                var i = 0;
                data['disable'] = $.map($('input[name="id"]:checked'), function(c) {
                    return c.value;
                })
                $('.drink_datatable').DataTable().destroy();
                load_data(data);
            });
        });
    });

    $('.show_confirm').click(function(event)
    {
        event.preventDefault();
        swal({
            title: `Are you sure you want to delete this Records?`,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                var category = [],
                    form     = $(this).closest('form');

                if( form.get(0) )
                {
                    category.push(form.closest('.catg-box').find('figure').data('child_id'));
                }
                else
                {
                    $.each($("input[name='category']:checked"), function(i) {
                        category[i] = $(this).val();
                    });
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "categories/multidelete",
                    type: "POST",
                    data: {
                        category
                    },
                    success: function(response) {
                        if( category.length > 1 )
                        {
                            XS.Common.handleSwalSuccess('Categories deleted successfully.');
                        }
                        else
                        {
                            XS.Common.handleSwalSuccess('Category deleted successfully.');
                        }
                    },
                    error: function(xhr, errors)
                    {
                        swal({
                            title: xhr.responseJSON.error.message,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        });
                    }
                });
            }
        });
    });
    </script>
@endsection
