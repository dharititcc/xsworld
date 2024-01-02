<nav class="navbar navbar-expand-lg custom-nav bord">
    <div class="container-fluid">
        <div class="btn-elemts wraped">
            <button class="btn" id="sidebarToggle"><i class="icon-menu"></i></button>
            <span class="btn-title">Drinks List</span>
            <div class="showin-mob">
                <a href="javascript:void(0);" id="drink_modal" class="drink_modal bor-btn ms-3 m-icon">
                    @include('layouts.restaurant.add-icon')
                    <span>Add a Drink</span></a>
                <a href="#" class="upload_drink_modal bor-btn ms-3 m-icon">
                    @include('layouts.restaurant.upload-icon')
                    {{-- <input type="file" name="upload_drink" hidden /> --}}
                    <span>Import Drinks from Source</span></a>
            </div>
        </div>
    </div>
</nav>