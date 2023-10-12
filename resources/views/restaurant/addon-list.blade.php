@extends('layouts.restaurant.mainlayout')
@section('topbar')
    @include('restaurant.partials.addontopbar')
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
        <div class="mb-4 table-en-ds">
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
    <div class="modal fade" id="exampleModal" tabindex="-1"  data-crudetype="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-start ">
                    <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i
                            class="icon-left"></i></button>
                    <h2><span class="model_title">Add</span> Addon</h2>
                </div>
                <div class="modal-body">
                    <form name="addmixer" id="addonpopup" method="post" >
                        @csrf
                        <div style="min-height: 300px;">
                            <div class="form-group mb-4">
                                <input id="addon_id" type="hidden" class="addon_id" name="addon_id" />
                                <input type="text" name="name" class="form-control vari2" placeholder="Addon Name">
                            </div>
                            <div class="form-group mb-4">
                                <input type="number" name="price" class="form-control vari2" placeholder="Addon Price">
                            </div>
                            <div class="list-catg">
                                @foreach ($drink_categories as $child)
                                    <label>
                                        <?php  ?>
                                        <input type="checkbox" name="category[]" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                                @foreach ($food_categories as $child)
                                    <label>
                                        <?php  ?>
                                        <input type="checkbox" name="category[]" id="category" value="{{ $child->id }}">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
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
<script src="{{asset('js/enableSweetalert.js')}}"></script>
<script src="{{asset('js/disableSweetalert.js')}}"></script>
    @parent
    <script type="text/javascript">
        var moduleConfig = {
            'getAddonlist': "{!! route('restaurants.addons.index') !!}",
            'addAddon': "{!! route('restaurants.addons.store') !!}",
            'getAddon': "{!! route('restaurants.addons.show', ':ID') !!}",
            'updateAddon': "{!! route('restaurants.addons.update', ':ID') !!}",
        };
        var modal = $("#exampleModal");
            // modal open pop up
            $('body').on('click', '.addon_model', function(e)
            {
                e.preventDefault();

                var $this       = $(this),
                    addon_id    = $this.data('addon_id'),
                    type        = $this.data('type');
                modal.find('.model_title').html(`${type}`);

                if(type == 'Add')
                {
                    console.log(type);
                    $('.model_title').html('Add');
                    modal.find('form').find('input[name="_method"]').remove();
                    modal.find('form').attr('action',moduleConfig.addAddon);
                } else {

                    $('.model_title').html('Edit');
                    $.ajax({
                        url: moduleConfig.getAddon.replace(':ID', addon_id),
                        type: "GET",
                        success: function(response) {
                            console.log(response);
                            modal.find('form').attr('action',moduleConfig.updateAddon.replace(':ID',addon_id));
                            modal.find('form').append(`<input type="hidden" name="_method" value="PUT" />`);
                            $("input[name=name]").val(response.addon.name);
                            $("input[name=price]").val(response.addon.price);
                            $('input[name="category[]"]').val(response.addon.categories);
                            var image = `
                                            <div class="pip">
                                                <img class="imageThumb" src="${ response.addon.image!="" ?response.addon.image :'#'}" title="" />
                                                <i class="icon-trash remove"></i>
                                            </div>
                                        `;

                            if(response.addon.image != '')
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
                        error: function(addon) {}
                    });
                }

                modal.modal('show');
            });

            // close modal pop up
            modal.on('hide.bs.modal', function()
            {
                var $this = jQuery(this);

                $this.find('#addonpopup').find('.form-control').val('');
                var $alertas = $('#addonpopup');
                $alertas.validate().resetForm();
                $alertas.find('.error').removeClass('error');

                $this.find('#addonpopup').find('.pip').remove();
                $this.find('form').removeAttr('action');
                $this.find('input[name="category[]"]:checked').each(function() {
                    $(this).prop('checked',false);
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
                alert("Your browser doesn't support to File API");
            }

        jQuery(document).ready(function() {
            $('#sidebarToggle1').on('click',function(e) {
               e.preventDefault();
               $('body') .removeClass('sb-sidenav-toggled');
            });
        })

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
                order: [[1, 'asc']],
                ajax: {
                    url: moduleConfig.getAddonlist,
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
                                ' </div><a href="javascript:void(0)" data-type="Edit" data-addon_id="'+row.id+'" class="edit addon_model" data-id=" ' + row.id +
                                ' " >Edit</a>  <div class="add-date">Added ' + formatDate(row
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

        $("#search").keyup(function() {
            $('.drink_datatable').DataTable().destroy();
            var data = [];
            data['search_main'] = this.value;
            load_data(data);
        });

        $(document).ready(function() {
            $('.checkboxitem').click(function() {
                alert(1);
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
        $("#addonpopup").validate({
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

                var data = new FormData(form);
                // let name = $("input[name=name]").val();
                // let price = $("input[name=price]").val();
                // var photo = $('#upload').prop('files')[0];
                // var category = [];
                // $.each($("input[name='category']:checked"), function(i) {
                //     category[i] = $(this).val();
                // });
                // data.append('name', name);
                // data.append('photo', photo);
                // data.append('price', price);
                // data.append('category', category);
                $.ajax({
                    url: $(form).attr('action'),
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#submitBtn').html('Submit');
                        $("#submitBtn").attr("disabled", false);
                        alert('Addon has been added successfully');
                        location.reload(true);
                        //document.getElementById("categorypopup").reset();
                    }
                });
            }
        });
        function getAddon(id) {
          
        }
    </script>
@endsection
