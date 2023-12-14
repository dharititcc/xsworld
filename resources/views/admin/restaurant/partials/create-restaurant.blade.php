<!-- Global popup -->
<div class="modal fade" id="wd930" tabindex="0" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl justify-content-center">
        <form name="addrestaurant" id="create_update_restaurant" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header dri-heder">
                    <div class="head-left">
                        <button type="button" class="back" data-bs-dismiss="modal" aria-label="Close"><i class="icon-left"></i></button>
                        <h2><span class="model_title"> </span> </h2>
                    </div>
                    <div class="head-right">
                        <button class="bor-btn" id="submitBtn" type="submit">Save</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="grey-brd-box d-flex featured-img">
                                <input id="upload" type="file" class="files" name="image" accept="image/*" hidden />
                                <label for="upload" class="lbl-upload min-h-424"><span class="img-text"> Restaurant Image</span> <i class="icon-plus add-edit"></i></label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-4">
                                <input type="text" name="name" id="name" class="form-control vari3" placeholder="Restaurant Name">
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="extr-info">
                                        <div class="head">
                                            <h2 class="yellow">Address Information</h2> <span class="address-info"></span>
                                        </div>
                                        <div class="form-group full-w-form">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="street1" id="street1" class="form-control " placeholder="Street1">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="street2" id="street2" class="form-control " placeholder="Street2">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group full-w-form">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select class="form-control " name="country_id" id="country_id">
                                                        <option>Select Country</option>
                                                        <option value="1">India</option>
                                                        <option value="2">United States of America</option>
                                                        <option value="3">Australlia</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="state" id="state" class="form-control " placeholder="State">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group full-w-form">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="city" id="city" class="form-control " placeholder="City">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="postcode" id="postcode" class="form-control " placeholder="Postcode">
                                                </div>
                                            </div>
                                        </div>

                                        <textarea id="description" name="description" placeholder="Description" class="prd-desc h-96"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- static html -->
                                    <div class="extr-info">
                                        <div class="head">
                                            <h2 class="yellow">Owner Information</h2>
                                        </div>
                                        <div class="form-group full-w-form">
                                            <div class="row">
                                                <div class="col-md-12 form-group ">
                                                    <input type="text" name="first_name" id="first_name" class="form-control " placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="form-group full-w-form">
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" name="last_name" id="last_name" class="form-control " placeholder="last Name">
                                                </div>
                                            </div>
                                            </div>
                                            <div class="form-group full-w-form">
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" name="email" id="email" class="form-control " placeholder="Email">
                                                </div>
                                            </div>
                                            </div>
                                            <div class="form-group full-w-form">
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <input type="password" name="password" id="password" class="form-control " placeholder="password">
                                                </div>
                                            </div>
                                            </div>
                                                <div class="form-group full-w-form">
                                                    <div class="row">
                                                <div class="col-md-12">
                                                    <input type="text" name="phone" id="phone" class="form-control " placeholder="Phone">
                                                </div>
                                            </div>
                                            </div>

                                            </div>
                                        </div>
                                        <!-- end static -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- Global popup -->