//common functionalities for all the javascript features
var XS = {}; // common variable used in all the files of the backend
var XS_Admin = {}; // common variable used in all the files of the backend

(function () {
    let theEditor;
    XS.Common = {
        ajaxSetup: function () {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
        },

        checkNumberInput: function(e)
        {
            var val = this.value;
            var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
            var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
            if (re.test(val))
            {
                //do something here
            }
            else
            {
                val = re1.exec(val);
                if (val) {
                    this.value = val[0];
                } else {
                    this.value = "";
                }
            }
        },

        /**
         * CSRF token
         * @returns string
         */
        csrfToken: function () {
            return jQuery('meta[name="csrf-token"]').attr('content')
        },

        /**
         * Btn Processing Start
         * @param {*} btnElem
         */
        btnProcessingStart: function (btnElem) {
            btnElem.html('Please Wait...');
            btnElem.attr('disabled', true);
        },

        /**
         * Btn Processing Stop
         * @param {*} btnElem
         */
        btnProcessingStop: function (btnElem, text = 'Submit') {
            btnElem.html(text);
            btnElem.attr('disabled', false);
        },

        /**
         * Handle Validation Errors
         * @param {*} xhr
         * @returns bool
         */
        handleErrors: function (xhr) {
            var context = this,
                errorClass = 'invalid-feedback';

            // remove validation error messages
            jQuery('.invalid-feedback').remove();

            if (xhr.status == 422) {
                var errors = xhr.responseJSON.errors;

                $.each(errors, function (key, value) {
                    var name = $("input[name='" + key + "']");

                    if (key.indexOf(".") != -1) {
                        var arr = key.split(".");
                        name = $("input[name='" + arr[0] + "[]']:eq(" + arr[1] + ")");
                    }

                    // append validation errors
                    if (name.length > 1) {
                        name.parent().parent().append('<div class="fv-plugins-message-container ' + errorClass + '"><div data-field="text_input" data-validator="notEmpty">' + value[0] + '</div></div>');
                    } else {
                        name.parent().append('<div class="fv-plugins-message-container ' + errorClass + '"><div data-field="text_input" data-validator="notEmpty">' + value[0] + '</div></div>');
                    }
                });

                return false;
            }

            if (xhr.status == 403) {

                context.handleSwalError(xhr.responseJSON.error.message);

                return false;
            }

            if (xhr.status == 302) {
                context.handleSwalError('Your active session has been expired to process this request.', true);

                return false;
            }
        },

        /**
         * Handle Swal Error
         * @param {*} message
         */
        handleSwalError: function (message, clickStat = false) {
            swal({
                title: `${message}`,
                icon: "warning",
                button: "Ok!",
            });
        },

        /**
         * Handle Swal Success
         * @param {*} message
         * @param {*} url
         */
        handleSwalSuccess: function (message, url = false) {

            swal({
                icon: "success",
                title: message
            }).then((value) => {
                if( url )
                {
                    window.location.href = url;
                }
                else
                {
                    window.location.reload();
                }
            });
        },

        /**
         * Handle Swal Success Without Reload
         * @param {*} message
         * @param {*} url
         */
        handleSwalSuccessWithoutReload: function (message) {
            swal({
                icon: "success",
                title: message
            });
        },

        /**
         * Handle Swal Confirm
         * @param {*} message
         * @param {*} callback
         */
        handleSwalConfirm: function (message = 'Are you sure?', callback) {
            swal.fire({
                title: message,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    return false;
                }
            });
        },

        /**
         * Check If Array Is Unique
         * @param {*} myArray
         * @returns bool
         */
        checkIfArrayIsUnique: function (myArray) {
            return (new Set(myArray)).size !== myArray.length;
        },

        /**
         * Show Modal
         * @param {*} modalId
         */
        showModal: function (modalId) {
            jQuery(modalId).modal('show');
        },

        /**
         * Add Class
         * @param {*} elem
         * @param {*} className
         */
        addClass: function (elem, className) {
            jQuery(elem).addClass(className);
        },

        /**
         * Remove Class
         * @param {*} elem
         * @param {*} className
         */
        removeClass: function (elem, className) {
            jQuery(elem).removeClass(className);
        },

        /**
         * Show Loader
         * @param {*} elem
         */
        showLoader: function (elem) {
            NBC.Common.removeClass(elem, 'd-none');
        },

        /**
         * Hide Loader
         * @param {*} elem
         */
        hideLoader: function (elem) {
            NBC.Common.addClass(elem, 'd-none');
        },

        /** Datatable related stuff */
        DataTable: {
            getPageLengthDatatable: function()
            {
                return [[10, 25, 50, -1], [10, 25, 50, "All"]];
            }
        },

        /**
         * Initialize Request TinyMce Editor
         * @param {*} elem 
         * @param {*} options 
         */
        initializeRequestEditor: function(elem, options = {})
        {
            var defaults = {
                target: elem,
                menubar:false,
                statusbar: false,
                plugins: 'link',
                min_height: 100,
                height: 500,
                content_style: "p { margin: 0; } h1,h2,h3,h4,h5,h6 { margin: 0; }",
                toolbar: 'styleselect | bold italic underline | link',
                default_link_target:"_blank",
                style_formats: [
                    {title: 'Paragraph', format: 'p'},
                    {title: 'Heading 1', format: 'h1'},
                    {title: 'Heading 2', format: 'h2'},
                    {title: 'Heading 3', format: 'h3'},
                    {title: 'Heading 4', format: 'h4'},
                    {title: 'Heading 5', format: 'h5'},
                    {title: 'Heading 6', format: 'h6'}
                ],
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            };

            options = $.extend({}, defaults, (options || {}));

            tinymce.init(options);
        },

        /**
         * Initialize Request TinyMce Editor
         * @param {*} elem 
         * @param {*} options 
         */
        initializeRequestEditorBySelector: function(selector, options = {})
        {
            var defaults = {
                selector: selector,
                menubar:false,
                statusbar: false,
                plugins: 'link',
                min_height: 100,
                height: 500,
                content_style: "p { margin: 0; } h1,h2,h3,h4,h5,h6 { margin: 0; }",
                toolbar: 'styleselect | bold italic underline | link',
                default_link_target:"_blank",
                style_formats: [
                    {title: 'Paragraph', format: 'p'},
                    {title: 'Heading 1', format: 'h1'},
                    {title: 'Heading 2', format: 'h2'},
                    {title: 'Heading 3', format: 'h3'},
                    {title: 'Heading 4', format: 'h4'},
                    {title: 'Heading 5', format: 'h5'},
                    {title: 'Heading 6', format: 'h6'}
                ],
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            };

            options = $.extend({}, defaults, (options || {}));

            tinymce.init(options);
        },

        enableSweetAlert: function(table)
        {            
            $('#enable').click(function(event) {
                var $this   = $(this),
                    form    = $(this).closest("form"),
                    data    = [],
                    i       = 0;
                event.preventDefault();

                $this.addClass('enable_clicked');
            
                // check if atleast one item selected
                if( $('.checkboxitem:checked').length == 0 )
                {
                    swal({
                        title: `Please select atleast one item?`,
                        icon: "warning",
                        button: "Ok!",
                    });
                    return false;
                }
            
                swal({
                    title: `Are you sure you want to Enable this Records?`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        table.ajax.reload();
                        $this.removeClass('enable_clicked');
                    }
                });
            });
        },

        disableSweetAlert: function(table)
        {            
            $('#disable').click(function(event) {
                var $this   = $(this),
                    form    = $(this).closest("form"),
                    data    = [],
                    i       = 0;
                event.preventDefault();

                $this.addClass('disable_clicked');
            
                // check if atleast one item selected
                if( $('.checkboxitem:checked').length == 0 )
                {
                    swal({
                        title: `Please select atleast one item?`,
                        icon: "warning",
                        button: "Ok!",
                    });
                    return false;
                }
            
                swal({
                    title: `Are you sure you want to Disable this Records?`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        table.ajax.reload();
                    }
                    $this.removeClass('disable_clicked');
                });
            });
        },

        reinitSortableTinymce: function()
        {
            const templRequestDiv = document.getElementById('tempRequestDiv');

            $(templRequestDiv).find('.request_accordion').each(function(index, elem){
                // destroy tinymce
                tinyMCE.execCommand('mceRemoveEditor', 'false', $(elem).find('.ckeditor').attr('id'));

                // initialize ckeditor
                setTimeout(function() {
                    NBC.Common.initializeRequestEditor($(elem).find('.ckeditor').get(0));
                }, 1000);

                // initialize drop
                var dropzoneControl = $(elem).find('div.my_upload')[0].dropzone;
                // check if dropzone already attached
                if( dropzoneControl === undefined )
                {
                    $(elem).find('div.my_upload').dropzone(initializeDropzone());
                }
            });
        },

        insertAtIndex: function(index, parentElement, content)
        {
            if(index === 0) {
                $(parentElement).prepend(content);
                return;
            }
            // $("#tempRequestDiv > div:nth-child(" + (index) + ")").after(content); // working solution
            $(parentElement).children().eq(index-1).after(content);
        },

        /**
         * Format date into dd-mm-yyyy
         * @param {*} date
         * @returns
         */
        formatDate: function(date)
        {
            var context = this;

            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [day, month, year].join('-');
        },

        fileReaderBind: function()
        {
            if (window.File && window.FileList && window.FileReader) {
                $(".files").on("change", function(e) {
                    var clickedButton   = this,
                        files           = e.target.files,
                        filesLength     = files.length;

                    for (var i = 0; i < filesLength; i++) {
                        var f = files[i],
                            fileReader = new FileReader();

                        fileReader.onload = (function(e)
                        {
                            var file        = e.target,
                                data        = fileReader.result,
                                thumbnail   = `
                                    <div class="pip">
                                        <img class="imageThumb" src="${e.target.result}" title="${f.name}" />
                                        <i class="icon-trash remove"></i>
                                    </div>
                                `;

                            if (!data.match(/^data:image\//))
                            {
                                XS.Common.handleSwalError('Please select image only.');
                                return false;
                            }

                            $(thumbnail).insertAfter(clickedButton);
                            $(".remove").click(function() {
                                $(this).parent(".pip").remove();
                            });
                        });
                        fileReader.readAsDataURL(f);
                    }
                });
            } else {
                // alert("Your browser doesn't support to File API")
                XS.Common.handleSwalSuccessWithoutReload("Your browser doesn't support to File API.");
            }
        },

        randomId: function(length)
        {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
            }
            return result;
        },
    }
})();