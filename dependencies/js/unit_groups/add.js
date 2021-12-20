$(document).ready(function () {


    $(document).on('click', '#add_ugp', function () {

        // Initializing the modal with default values.
        $('#ugp_add_modal #ugp_add_form').initForm();

        $('.tbl-add-ugp').initMovementTable();

        // First row contains allways basic unit. So remove button hiding.
        $('.tbl-add-ugp').find('.rem').eq(0).hide();

        $('#ugp_add_modal').find('.modal-title').text('ADD UNIT GROUP');

        $('#ugp_add_modal').modal('show');
    });


    $('form#ugp_add_form').on('submit', function (e) {
        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("unit_groups/save"), input, afterUnitGroupSave, function (r, form) {

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

// On basic unit changed. (The unit selected in first row is considered as Basic Unit)
$(document).on('change', '.tbl-add-ugp .sr-movement-row:first-child .ugp_fk_units', function () {
    var basic_unt = $(this).find('option:selected').text();
    $(".tbl-add-ugp .sr-movement-row:not(:first-child)").find(".basic_unt_txt").html(basic_unt);
});

function initUnitGroupTable(container) {

    container.find('.sr-movement-row').slice(1).remove();
    container.find('.sr-movement-row').initForm();

    // First row contains allways basic unit. So remove button hiding.
    container.find('.rem').eq(0).hide();

    // First row contains allways basic unit. ugp_rel (Relation with basic unit) is not applicable for basic unit.
    container.find('.rel-container').eq(0).hide();
    container.find('.one-span').eq(0).hide();
    container.find('.ugp_rel').eq(0).val('');
}

function on_new_ugp_add_row_created(container, lastRow, newRow) {
    // First row is allways basic unit. So it can't be delete or don't having a realtion value.
    container.find('.rem').eq(0).hide();
    container.find('.ugp_rel').eq(0).val('');
    container.find('.one-span').eq(0).hide();
    container.find('.rel-container').eq(0).hide();

    var basic_unt = container.find('.ugp_fk_units').eq(0).find('option:selected').text();
    newRow.find('.rem').show();
    newRow.find('.ugp_fk_units').focus();
    newRow.find('.one-span').show();
    newRow.find('.rel-container').show();
    newRow.find('.basic_unt_txt').html(basic_unt);
    newRow.find('.ugp_default').val(2);
}

function on_ugp_row_delted(container) {
    // First row is allways basic unit. So it can't be delete or don't having a realtion value.
    container.find('.rem').eq(0).hide();
    container.find('.ugp_rel').eq(0).val('');
    container.find('.one-span').eq(0).hide();
    container.find('.rel-container').eq(0).hide();
}
