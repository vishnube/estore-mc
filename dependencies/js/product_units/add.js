$(document).ready(function () {


    $(document).on('click', '.add_punt', function () {

        // If no task assigned
        if (!tsk_prd_add)
            return;

        // Initializing the modal with default values.
        $('#punt_add_modal #punt_add_form').initForm();
        $('#punt_add_modal').find('.modal-title').text('ADD UNITS');
        $('#punt_add_modal').modal('show');

        var prd_id = $(this).closest('tr').find('.prd_id').val();
        var prd_name = $(this).closest('tr').find('.prd_name').val();

        $('#punt_add_modal').find('.prd_name').text(prd_name);
        $('#punt_add_modal').find('.punt_fk_products').val(prd_id);
    });


    $('form#punt_add_form').on('submit', function (e) {
        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Only for validation purpose.
        input.push({ name: "group_no_required", value: $(this).find('.punt_group_no:checked').val() });

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("product_units/save"), input, afterProductUnitsSave, function (r, form) {
            if (r.v_error['group_no_required'])
                form.find('.val_error_group_no_required').html(r.v_error['group_no_required']);

            // Showing product validation as  an alert
            if (typeof r.o_error != 'undefined' && r.o_error) {
                Swal.fire('Oops!', r.o_error, 'error');
                return false;
            }
        })
    });

});
