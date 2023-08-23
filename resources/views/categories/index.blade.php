@extends('layouts.restaurant.mainlayout')
@section('topbar')
@include('categories.partials.topbar')
@endsection
@section('content')
<!-- Page content-->
<div class="container-fluid">
    <main>
        <div class="outrbox">
            @if ($categories->count())
                @php
                    $cnt = 1;
                @endphp
                @foreach ($categories as $category)
                    <div class="d-flex mb-4 justify-content-between">
                        <h2 class="yellow">{{ $category->name }} Categories</h2>
                        <div class="count-item">Total: {{ $category->children->count() }}</div>
                    </div>

                    <div class="grid colmn-5">
                        @if ($category->children->count())
                            @foreach ($category->children as $child)
                                <div class="catg-box overly">
                                    <button><i class="icon-trash"></i></button>
                                    <figure><img src="{{ $child->image }}" alt="{{ $child->name }}">
                                        <figcaption><span> {{ $child->name }}</span></figcaption>
                                    </figure>
                                </div>
                            @endforeach
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModal" class="catg-box add overly">
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
    </main>
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
            <div style="min-height: 300px;">
                    <div class="form-group mb-4">
                        <input type="text" class="form-control vari2" placeholder="Category Name" >
                    </div>
                    <div class="form-group grey-brd-box custom-upload mb-5">
                        <input id="upload" type="file" class="files" name="files[]" hidden />
                        <label for="upload"><span> Add Category Feature Image (This can be changed).</span> <i class="icon-plus"></i></label>
                    </div>
                </div>
                <button class="bor-btn w-100 font-26" type="button">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Global popup -->
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
</script>
@endsection