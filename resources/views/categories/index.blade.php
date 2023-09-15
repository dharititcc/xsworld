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
                                <figure onClick="updateCategory({{ $child->id }})" data-type="Edit" data-parent_id="{{ $category->id }}" data-parent="{{ $category->name }}" class="category_model"><img src="{{ $child->image }}"
                                        alt="{{ $child->name }}">
                                    <figcaption><span> {{ $child->name }}</span></figcaption>
                                </figure>
                            </div>
                        @endforeach
                        {{-- <a href="javascript:void(0);" onClick="getCategory({{ $category->id }})" data-parent="{{ $category->name }}" data-bs-toggle="modal"
                            data-bs-target="#exampleModal" class="catg-box add overly">
                            <figure><i class="icon-plus"> </i></figure>
                            <!--<input type="text" required="" autofocus=""> -->
                        </a> --}}
                        <a href="javascript:void(0);" data-parent="{{ $category->name }}" data-parent_id="{{ $category->id }}" data-type="Add" class="catg-box add overly category_model">
                            <figure><i class="icon-plus"> </i></figure>
                        </a>
                    @endif
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
                            </div>
                            <div class="form-group mb-4">
                                <input id="cat_id" type="hidden" class="cat_id" name="cat_id" />
                            </div>
                            <div class="form-group grey-brd-box custom-upload mb-5 image_box">
                                <input id="upload" type="file" class="files" name="image" hidden/>
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
        };
        $(document).ready(function() {
            var modal = $("#exampleModal");
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
                alert("Your browser doesn't support to File API")
            }
            $('#sidebarToggle1').on('click', function(e) {
                e.preventDefault();

                $('body').removeClass('sb-sidenav-toggled');
            });
        });
        //if ($("#categorypopup").length > 0) {
    $('#submitBtn').click(function(e)
    {
        var crudetype = $('#exampleModal').data('crudetype'); //getter
        if (crudetype === 1)
        {
                $("#categorypopup").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 50
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
                        maxlength: "Your name maxlength should be 50 characters long."
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
                        required: true,
                        maxlength: 50
                    },
                    message: {
                        required: true
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
    function formsubmit(from){
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
                data.append('photo', photo);
                data.append('category_id', category_id);
                data.append('parent_id', parent_id);
                console.log(crudetype);
                if (crudetype === 1) {
                    route = moduleConfig.addCategory;
                } else {
                    route = moduleConfig.updateCategory.replace(':ID', category_id),
                    data.append('_method', 'PUT');
                }
                console.log(route);
                $.ajax({
                    url: route,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                        alert('Ajax form has been submitted successfully');
                        document.getElementById("categorypopup").reset();
                        location.reload(true);
                    }
                });
    }

        function getCategory(id) {
            $('#category_id').val(id);
            $('#exampleModal').data('crudetype', 1);
            $('.model_title').html('Add ');
        }

        function updateCategory(id) {
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
                                        <img class="imageThumb" src="${ response.data.image!="" ?response.data.image :'#'}" title="" />
                                        <i class="icon-trash remove"></i>
                                    </div>
                                `;

                    $(".image_box").children('.pip').remove();
                    $("#upload").after(image);
                    $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                    });
                    $('#exampleModal').data('crudetype', 0);
                    $('#exampleModal').modal('show');
                },
                error: function(data) {}
            });
        }
        $(function() {
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
                    //console.log(data);
                });
            });
        });

        function deleteConform(id) {
            if (!confirm("Are You Sure to delete this 'Category' and 'All Items'?")) {
                event.preventDefault();
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "categories/" + id,
                    type: "DELETE",
                    success: function(response) {
                        alert('Delete Category is deleted successfully');
                        location.reload(true);
                    }
                });
            }
        }
        $('.show_confirm').click(function(event) {
            event.preventDefault();
            swal({
                title: `Are you sure you want to delete this Records?`,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        var category = [];
                        $.each($("input[name='category']:checked"), function(i) {
                            category[i] = $(this).val();
                        });
                        console.log(category);
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
                                location.reload(true);
                            }
                        });
                    }
                });
        });
        // $(function() {
        //     $('#bulkdelete').click(function(e) {
        //         if (!confirm("Are You Sure to delete this 'Categories' and 'All Items'?")) {
        //             event.preventDefault();
        //         } else {
        //             e.preventDefault();
        //             //   $.confirmModal("<label>Are You Sure to delete this 'Categories' and 'All Items' ?</label>"), function(el) 
        //             //   {
        //             // var val = [];
        //             // // <input type="checkbox" name="category[]" id="category" value="{{ $child->id }}">
        //             //     $('#category:checkbox:checked').each(function(i){
        //             //     val[i] = $(this).val();
        //             //     });
        //             var category = [];
        //             $.each($("input[name='category']:checked"), function(i) {
        //                 category[i] = $(this).val();
        //             });
        //             console.log(category);
        //             $.ajax({
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 url: "categories/multidelete",
        //                 type: "POST",
        //                 data: {
        //                     category
        //                 },
        //                 success: function(response) {
        //                     alert('Categories are deleted successfully');
        //                     location.reload(true);
        //                 }
        //             });
        //         }
        //     });
        // });
    </script>
@endsection
