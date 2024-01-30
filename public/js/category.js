(function () {
    XS.Category = {
        init: function (){
            this.addHandler();
        },

        selectors: {
            categoryModal:         jQuery('.add_category'),
            categoryModalShow:     jQuery('#cat_modal'),
            submitButton:          jQuery('#submitCatBtn'),


           
        },


        addHandler: function (){
            var context = this;

            // context.makeDatatable();
            XS.Common.fileReaderBindImage();
            
        },

        openCategoryModel: function() {
            var context = this;
            
        },

        closeCategoryModal: function()
        {
            
        },

        addCategoryFormValidation: function()
        {

        },
    }
})();