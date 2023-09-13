@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('restaurant.partials.mixertopbar')
@endsection
@section('content')
    <style>
        table.dataTable tbody tr {
            background-color: #0f0e0e !important;
        }
    </style>
    <div class="outrbox">
        <div class="sort-by d-flex mb-4">
            <h2 class="yellow">Sort By</h2>
            <div class="searchbox"><input type="text" name="search" id="search" class="searchbar"
                    placeholder="Find a Drink"></div>
        </div>
        <div class="mb-4">
            <button class="bor-btn" id="disable">Disable Drink</button>
            <button class="bor-btn ms-3" id="enable">Enable Drink</button>
        </div>
        <div class="data-table drinks scroll-y h-600">
            <table width="100%" class="drink_datatable">
                <thead>
                    <tr valign="middle">
                        <th><label class="cst-check"><input type="checkbox" value=""><span
                                    class="checkmark"></span></label></th>
                        <th>
                            Name
                            <a href="#" class="sort-icon ms-1"><i class="icon-sort"></i></a>
                        </th>
                        <th class="price">Price</th>
                        <th class="popularity">Popularity</th>
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
                                <input type="text" name="name" class="form-control vari2" placeholder="Mixer Name">
                            </div>
                            <div class="form-group mb-4">
                                <input type="number" name="price" class="form-control vari2" placeholder="Mixer Price">
                            </div>
                            <div class="list-catg">
                                @foreach ($categories as $child)
                                    <label>
                                        <input type="checkbox" name="category" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="form-group grey-brd-box custom-upload mb-5">
                                <input id="upload" type="file" class="files" name="image" />
                                <label for="upload"><span> Add Mixer Feature Image (This can be changed).</span> <i
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
@endsection
@section('pagescript')
    @parent
    <script type="text/javascript">
        var moduleConfig = {
            'getMixerlist': "{!! route('restaurants.mixers.index') !!}",
            'addMixer': "{!! route('restaurants.mixers.store') !!}",
            'getMixer': "{!! route('restaurants.mixers.show', ':ID') !!}",
            'updateMixer': "{!! route('restaurants.mixers.update', ':ID') !!}",
        };
        var modal = $("#exampleModal");
            // modal open pop up
            $('.mixer_model').on('click', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    //parent      = $this.data('parent'),
                    //parent_id   = $this.data('parent_id'),
                    type        = $this.data('type');
                modal.find('.model_title').html(`${type}`);
                //modal.find('#mixerpopup').find('#category_id').val(parent_id);

                modal.modal('show');
            });

            // close modal pop up
            modal.on('hide.bs.modal', function()
            {
                var $this = jQuery(this);

                $this.find('#mixerpopup').find('.form-control').val('');

                $this.find('#mixerpopup').find('.pip').remove();
                //$this.find('#mixerpopup').find('#category_id').val('');
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

        jQuery(document).ready(function() {
            $('#sidebarToggle1').on('click', function(e) {
                e.preventDefault();

                $('body').removeClass('sb-sidenav-toggled');
            });
        });

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
            // console.log(data);
            var table = $('.drink_datatable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: moduleConfig.getMixerlist,
                    data: data,
                },
                columns: [{
                        "data": "id", // can be null or undefined
                        "defaultContent": "",
                        "bSortable": false,
                        render: function(data, type, row) {
                            return '<label class="cst-check"><input name="id" class="checkboxitem" type="checkbox" value="' +
                                row.id + '"><span class="checkmark"></span></label>'
                        }
                    },
                    {
                        "data": "name", // can be null or undefined ->type
                        "defaultContent": "",
                        render: function(data, type, row) {
                            var color = (row.is_available == 1) ? "green" : "red";
                            return '<div class="prdname ' + color + '"> ' + row.name +
                                ' </div><a href="javascript:void(0)" data-type="Edit" onClick="getMixer(' + row.id +
                                ')" class="edit mixer_model" >Edit</a>  <div class="add-date">Added ' + formatDate(row
                                    .created_at) + '</div>'
                        }
                    },
                    {
                        "data": "price", // can be null or undefined
                        "defaultContent": "",
                        "bSortable": false,
                        render: function(data, type, row) {
                            var text = "";
                            if (row.variations.length > 0) {
                                for (let i = 0; i < row.variations.length; i++) {
                                    text += '<label class="price">$' + row.variations[i]['price'] +
                                        "</label><br>";
                                }
                                return text
                            }
                            return row.price
                        }
                    },
                    {
                        "data": "description", // can be null or undefined
                        "defaultContent": "",
                        "bSortable": false,
                        render: function(data, type, row) {
                            return row.description
                        }
                    },
                    {
                        "data": "status", // can be null or undefined
                        "defaultContent": "",
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
                ]
            });
        }

        function getCategory(id) {
            var data = [];
            data['category'] = id;
            if (!data) {
                $('.drink_datatable').DataTable().destroy();
                load_data();
            } else {
                $('.drink_datatable').DataTable().destroy();
                load_data(data);
            }
        }
        $("#search").keyup(function() {
            $('.drink_datatable').DataTable().destroy();
            var data = [];
            data['search_main'] = this.value;
            load_data(data);
        });
        $(function() {
            $('#enable').click(function(e) {
                e.preventDefault();
                $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
                    var data = [];
                    var i = 0;
                    data['enable'] = $.map($('input[name="id"]:checked'), function(c) {
                        return c.value;
                    })
                    $('.drink_datatable').DataTable().destroy();
                    load_data(data);
                    //console.log(data);
                });
            });
        });
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

            $('#allcheck').click(function(e) {
                e.preventDefault();
                alert();
                $('input[name="id"]').attr('checked', 'checked');
                // $(this).val('uncheck all');
            }, function() {
                $('input[name="id"]').removeAttr('checked');
                //$(this).val('check all');
            })
        });
        $("#mixerpopup").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                category: {
                    onecheck: true
                },
                price: {
                    required: true,
                    number: true,
                    maxlength: 10
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
                image: {
                    required: "Please enter files", //accept: 'Not an image!'
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#submitBtn').html('Please Wait...');
                $("#submitBtn").attr("disabled", true);

                var data = new FormData();
                let name = $("input[name=name]").val();
                let price = $("input[name=price]").val();
                var photo = $('#upload').prop('files')[0];
                var category = [];
                $.each($("input[name='category']:checked"), function(i) {
                    category[i] = $(this).val();
                });
                data.append('name', name);
                data.append('photo', photo);
                data.append('price', price);
                data.append('category', category);
                var crudetype = $('#exampleModal').data('crudetype');
                if (crudetype === 1) {
                    route = moduleConfig.addMixer;
                } else {
                    route = moduleConfig.updateMixer.replace(':ID', id),
                        data.append('_method', 'PUT');
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
                        alert('Mixer has been added successfully');
                        location.reload(true);
                        //document.getElementById("categorypopup").reset();
                    }
                });
            }
        });

        function getMixer(id) {
             $('.model_title').html('Edit');
            // $('#cat_id').val(id);
            $.ajax({
                url: moduleConfig.getMixer.replace(':ID', id),
                type: "GET",
                success: function(response) {
                    console.log(response);
                    $("input[name=name]").val(response.data.name);
                    $("input[name=price]").val(response.data.price);
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
    </script>
@endsection
