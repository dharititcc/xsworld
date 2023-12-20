$('#enable').click(function(event) {
    var form =  $(this).closest("form");
    var data = [];
    var i = 0;
    event.preventDefault();

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
            data['enable'] = $.map($('input[name="id"]:checked'), function(c){return c.value; })
            $('.drink_datatable').DataTable().destroy();
            load_data(data);
        }
        $('#allcheck').prop( "checked", false );
    });
});