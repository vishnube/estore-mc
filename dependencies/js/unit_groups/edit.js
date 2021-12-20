$(document).ready(function () {

    $(document).on('click', '#tbl_ugp .edit_ugp', function () {
        var $ugp_group_no = $(this).closest('tr').find('.ugp_group_no').val();
        beforeEdit_ugp($ugp_group_no);
    });


    $('form#ugp_edit_form').on('submit', function (e) {
        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("unit_groups/edit"), input, afterUnitGroupSave, function (r, form) {

            if (typeof r.o_error != 'undefined' && r.o_error) {
                Swal.fire('Oops!', r.o_error, 'error');
                return false;
            }
            showValidationErrors(r.v_error, form);

            if ($.isEmptyObject(r.v_error))
                return;

            // Validating GST
            $.each(r.v_error, function (index, value) {
                // Taking index of the field
                var temp = index.match(/\[(.*)\]/);
                if (temp) {
                    var i = temp[1];
                    var field = index.substr(0, index.indexOf('['));
                    form.find('.' + field).eq(i).parent().append(value);
                }
            });

        })
    });

});

function beforeEdit_ugp(ugp_group_no) {
    var input = { ugp_group_no: ugp_group_no };
    $('#ugp_edit_modal .tbl-edit-ugp tbody').html('');
    $('form#ugp_eit_form').postForm(site_url("unit_groups/before_edit"), input, function (res, form) {
        $('#ugp_edit_modal').modal('show');
        $('#ugp_edit_modal .tbl-edit-ugp tbody').html(res.html);
    });
}

