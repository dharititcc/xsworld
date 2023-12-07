$('.show_confirm').click(function(event) {
    var form =  $(this).closest("form");
    event.preventDefault();
    swal({
        title: `Are you sure you want to delete this Records?`,
        // text: "It will gone forevert",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
                XS.Common.handleSwalSuccess('Record Deleted successfully.');
            }
        });
});