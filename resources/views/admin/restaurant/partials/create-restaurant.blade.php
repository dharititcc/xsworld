<!-- Global popup -->
<div class="modal fade" id="restaurant_modal" tabindex="0" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form name="adddrink" id="create_update_restaurant" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header dri-heder">
                    <div class="head-left">
                        <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
                        <h2><span class="model_title"> </span> Create</h2>
                    </div>
                    <div class="head-right">
                        <a href="javascript:void(0)" data-is_favorite="0" class="favorite is_favorite null"></a>

                        <button class="bor-btn" id="submitBtn" type="submit">Save</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group grey-brd-box d-flex featured-img">
                                <input id="upload" type="file" class="files" name="image" accept="image/*" hidden />
                                <label for="upload"><span> Restaurant Image</span> <i class="icon-plus"></i></label>
                            </div>
                            <input type="text" name="price" id="price" class="form-control vari2 mb-3" placeholder="Enter Price">
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-4">
                                <input type="text" name="name" id="name" class="form-control vari3" placeholder="Restaurant Name">
                            </div>
                            <div class="extr-info">
                                <div class="head">
                                    <h2 class="yellow">Address Information</h2> <span class="address-info"></span>
                                </div>
                                <div class="form-group full-w-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="street1" id="street1" class="form-control vari1" placeholder="Street1">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="street2" id="street2" class="form-control vari1" placeholder="Street2">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group full-w-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <select class="form-control vari1" name="country_id">
                                                <option>Select Country</option>
                                                <option value="1">India</option>
                                                <option value="2">United States of America</option>
                                                <option value="3">Australlia</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="state" id="state" class="form-control vari1" placeholder="State">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group full-w-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="city" id="city" class="form-control vari1" placeholder="City">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="postcode" id="postcode" class="form-control vari1" placeholder="Postcode">
                                        </div>
                                    </div>
                                </div>

                                <textarea id="description" name="description" placeholder="Product descriptor goes into this box it can be brief or it can be long, this is to be displayed when the user clicks on the specific beverage." class="prd-desc"></textarea>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- Global popup -->