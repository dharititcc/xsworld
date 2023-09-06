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
                                     <button onclick="return deleteConform({{$child->id}});"><i class="icon-trash"></i></button>
                                    {{-- <a  onclick="return deleteConform('Are you sure?')" href="#"><i class="icon-trash"></i></a> --}}
                                    <figure onClick="updateCategory({{$child->id}})"><img src="{{ $child->image }}" alt="{{ $child->name }}">
                                        <figcaption><span> {{ $child->name }}</span></figcaption>
                                    </figure>
                                </div>
                            @endforeach
                            <a href="javascript:void(0);" onClick="getCategory({{$category->id}})" data-bs-toggle="modal" data-bs-target="#exampleModal" class="catg-box add overly">
                                <figure><i class="icon-plus"> </i></figure><!--<input type="text" required="" autofocus=""> -->
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-start ">
            <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
            <h2>Add Food Category</h2>
        </div>
        <div class="modal-body">
        <form name="addcategory" id="categorypopup" method="post" action="javascript:void(0)">
                @csrf
           <div style="min-height: 300px;">
                <div class="form-group mb-4">
                    <input type="text" name="name" class="form-control vari2" placeholder="Category Name" >
                    <input id="category_id" type="hidden"  class="category_id" name="category_id" />
                </div>
                <div class="form-group mb-4">
                    {{-- <div class="list-catg">
                    <label>
                        <input type="checkbox" name="contain_addon" id="contain_addon" class="" value="1">
                        <span>Contain Addon</span>
                    </label>
                    <label>
                        <input type="checkbox" name="contain_mixer" id="contain_mixer" class="" value="1">
                        <span>Contain Mixer</span>
                    </label>
                    </div> --}}
                    {{-- <label for="contain_addon"> Contain Addon</label>
                    <input type="checkbox" name="contain_mixer" id="contain_mixer" class="" value="1">
                    <label for="contain_mixer">Contain Mixer</label><br> --}}
                    <input id="cat_id" type="hidden"  class="cat_id" name="cat_id" />
                </div>
                <div class="form-group grey-brd-box custom-upload mb-5">
                    <input id="upload" type="file"  class="files" name="image" />
                    <label for="upload"><span> Add Category Feature Image (This can be changed).</span> <i class="icon-plus"></i></label>
                </div>
            </div>
              <button class="bor-btn w-100 font-26" id="submitBtn" type="submit">Save</button>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- Global popup -->
<!-- Global update popup -->
<div class="modal fade" id="updatecatModal" tabindex="-1" aria-labelledby="updatecatModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header justify-content-start ">
            <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
            <h2>Update Food Category</h2>
        </div>
        <div class="modal-body">
        <form name="updatecategorypopup" id="updatecategorypopup" method="post" action="javascript:void(0)">
                @csrf
           <div style="min-height: 300px;">
                <div class="form-group mb-4">
                    <input type="text" name="name" id="updatename" class="form-control vari2" placeholder="Category Name" >
                    <input id="cat_id" type="hidden"  class="cat_id" name="cat_id" />
                </div>
                <div class="form-group mb-4">
                    {{-- <input type="checkbox" name="contain_addon" id="contain_addon" class="form-control" value="1">
                    <label for="vehicle1"> Contain Addon</label><br>
                    <input type="checkbox" name="contain_mixer" id="contain_mixer" class="form-control" value="1">
                    <label for="vehicle1"> Contain Mixer</label><br> --}}
                    <input id="cat_id" type="hidden"  class="cat_id" name="cat_id" />
                </div>
                <div class="form-group grey-brd-box custom-upload mb-5">
                    <input id="updateupload" type="file"  class="updatefiles" name="updateimage" />
                    <label for="upload"><span> Add Category Feature Image (This can be changed).</span> <i class="icon-plus"></i></label>
                </div>
            </div>
              <button class="bor-btn w-100 font-26" id="updatesubmitBtn" type="submit">Update</button>
            </form>
        </div>
      </div>
    </div>
  </div>
<!-- Global update popup -->
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
            <h2 class="yellow mb-4">{{$category->name}} Categories</h2>
            <div class="list-catg">
                @foreach ($category->children as $child)
                <label>
                    <input type="checkbox" name="category" id="category" value="{{$child->id}}">
                    <span>{{$child->name}}</span>
                </label>
                @endforeach
            </div>
             <div class="gldnline-sepr"></div>
            @endforeach
            </div>
              <button id="bulkdelete" class="bor-btn w-100 font-26" type="button">Remove</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('pagescript')
@parent
<script>
     $(document).ready(function()
            {
                if (window.File && window.FileList && window.FileReader)
                {
                    $(".files").on("change", function(e)
                    {
                        var clickedButton   = this,
                            files           = e.target.files,
                            filesLength     = files.length;
                        for (var i = 0; i < filesLength; i++)
                        {
                            var f               = files[i],
                                fileReader      = new FileReader();
                            fileReader.onload   = (function(e)
                            {
                                var file        = e.target,
                                    thumbnail   = `
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
                }
                else
                {
                    alert("Your browser doesn't support to File API")
                }
            });

            jQuery(document).ready(function()
            {
                $('#sidebarToggle1').on('click', function(e)
                {
                    e.preventDefault();

                    $('body').removeClass('sb-sidenav-toggled');
                });
            });
//if ($("#categorypopup").length > 0) {
$("#categorypopup").validate({
    rules: {
        name: {
        required: true,
        maxlength: 50
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
    image: {
        required: "Please enter files",//accept: 'Not an image!'
    }
    },
    submitHandler: function(form) {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  $('#submitBtn').html('Please Wait...');
  $("#submitBtn"). attr("disabled", true);

  var data = new FormData();
  let name = $("input[name=name]").val();
  let category_id = $("input[name=category_id]").val();
  var photo = $('#upload').prop('files')[0];
//   var contain_addon = $("input[name='contain_addon']:checked").val() ? 1 : 0;
//   var contain_mixer = $("input[name='contain_mixer']:checked").val() ? 1: 0;
   data.append('name', name);
   data.append('photo', photo);
//    data.append('contain_addon', contain_addon);
//    data.append('contain_mixer', contain_mixer);
   data.append('category_id', category_id);
  $.ajax({
    url: 'categories',
    type: "POST",
    data: data,
    processData: false,
    contentType: false,
    success: function( response ) {
      $('#submitBtn').html('Submit');
      $("#submitBtn"). attr("disabled", false);
      alert('Ajax form has been submitted successfully');
      location.reload(true);
      document.getElementById("categorypopup").reset();
    }
   });
  }
  });
  /// Update category
  $("#updatecategorypopup").validate({
  rules: {
    name: {
    required: true,
    maxlength: 50
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
  image: {
    required: "Please enter files",//accept: 'Not an image!'
  }
  },
  submitHandler: function(form) {
  $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#updatesubmitBtn').html('Please Wait...');
  $("#updatesubmitBtn"). attr("disabled", true);

  var data = new FormData();
  let name = $("#updatename").val();
  let category_id = $("input[name=cat_id]").val();
  let contain_addon = $("input[name='contain_addon']:checked").val();
  let contain_mixer = $("input[name='contain_mixer']:checked").val();
  
  var photo = $('#updateupload').prop('files')[0];
   data.append('name', name);
   data.append('photo', photo);
   data.append('category_id', category_id);
  $.ajax({
    url: 'categories/'+category_id,
    type: "PUT",
    data: data,
    processData: false,
    contentType: false,
    success: function( response ) {
      $('#updatesubmitBtn').html('Submit');
      $("#updatesubmitBtn"). attr("disabled", false);
      alert('Ajax form has been update submitted successfully');
      //location.reload(true);
     // document.getElementById("categorypopup").reset();
    }
   });
  }
  });
function getCategory(id)
{
    $('#category_id').val(id);
}
function updateCategory(id)
{
    $('#cat_id').val(id);
    $.ajax({
            url: "categories/"+id,
            type: "GET",
            success: function( response ) {
                $("input[name=name]").val(response.name);
                $("input[name=image]").val(response.image);
                var image =`
                                    <div class="pip">
                                        <img class="imageThumb" src="${response.image}" title="" />
                                        <i class="icon-trash remove"></i>
                                    </div>
                                `;
               // $(".files").insertAfter(image);
                $('#updatecatModal').modal('show');
                //alert('Category is update successfully');
            //location.reload(true);
            },
            error: function(data) {
             }
         });
}
$(function(){
      $('#disable').click(function(e) {
          e.preventDefault();
          $.confirmModal('<label>Are you sure you want to do this?</label>', function(el) {
            var data = [];
            var i= 0;
            data['disable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
            $('.drink_datatable').DataTable().destroy();
            load_data(data);
              //console.log(data);
          });
        });
    });

  function deleteConform(id) {
      if(!confirm("Are You Sure to delete this 'Category' and 'All Items'?")){
      event.preventDefault();
      }
      else
      {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: "categories/"+id,
            type: "DELETE",
            success: function( response ) {
            alert('Delete Category is deleted successfully');
            location.reload(true);
            }
         });
      }
  }
  $(function(){
      $('#bulkdelete').click(function(e) {
        if(!confirm("Are You Sure to delete this 'Categories' and 'All Items'?")){
      event.preventDefault();
      }
      else
      {
          e.preventDefault();
        //   $.confirmModal("<label>Are You Sure to delete this 'Categories' and 'All Items' ?</label>"), function(el) 
        //   {
            // var val = [];
            // // <input type="checkbox" name="category[]" id="category" value="{{$child->id}}">
            //     $('#category:checkbox:checked').each(function(i){
            //     val[i] = $(this).val();
            //     });
                var category = [];
              $.each($("input[name='category']:checked"), function(i){
                category[i] = $(this).val();
              });
              console.log(category);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url: "categories/multidelete",
                type: "POST",
                data:{category},
                success: function( response ) {
                alert('Categories are deleted successfully');
                location.reload(true);
                }
             });
          }
        });
    });


</script>
@endsection