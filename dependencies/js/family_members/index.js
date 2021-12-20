$(document).on('click', '.show_fmlm', function () {
    var mbr_id = $(this).closest('tr').find('.mbr_id').val(); // member id of Family
    show_fmlm_details(mbr_id);
});



function reset_fmlm_count(fmly_id, operation) {
    var fmlm_counter = $("#tbl_fmly .fmly_id[value=" + fmly_id + "]").closest('tr').find('.fmlm_count')
    var fmlm_count = fmlm_counter.text();
    if (operation == '+')
        fmlm_counter.text(++fmlm_count);
    else if (operation == '-')
        fmlm_counter.text(--fmlm_count);
}

/**
 * 
 * @param {*} mbr_id : mbr_id of the Family
 */
function show_fmlm_details(mbr_id) {
    var input = { mbr_id: mbr_id }; // mbr_id of Family.
    $('#family_member_details_modal').postForm(site_url("family_members/get_details"), input, function (res) {
        $('#family_member_details_modal').modal('show');
        $('#family_member_details_modal .modal-content').html(res.html);
    });
}

/**
 * This function should be in global scope.
 * Don't put this function in "family_member/add.js".
 */
$(document).on('click', '#tbl_fmlm .edit_fmlm', function () {

    // If no task assigned
    if (!tsk_fmly_edit)
        return;

    $('form#fmlm_add_form .fmlm-back-btn').show();
    var mbr_id = $(this).closest('tr').find('.fmlm_mbr_id').val();
    beforeEditFamilyMember(mbr_id);
});

$(document).on('click', '#tbl_fmlm .deactivate_fmlm', function () {

    // If no task assigned
    if (!tsk_fmly_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.fmlm_mbr_id').val(); // member_id of Family Member
    var $fmly_id = $(this).closest('tr').find('.fmly_id').val();    // Family id of Family Member
    var $fmlm_name = $(this).closest('tr').find('.fmlm_name').val();// Name of the Member
    var url = site_url("family_members/deactivate");
    changeStatus(url, { mbr_id: $mbr_id }, $fmlm_name, INACTIVE, function () {
        reset_fmlm_count($fmly_id, '-');
        show_fmlm_details($("#tbl_fmly .fmly_id[value=" + $fmly_id + "]").closest('td').find('.mbr_id').val());
    });
});

$(document).on('click', '#tbl_fmlm .activate_fmlm', function () {

    // If no task assigned
    if (!tsk_fmly_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.fmlm_mbr_id').val(); // member_id of Family Member
    var $fmly_id = $(this).closest('tr').find('.fmly_id').val();    // Family id of Family Member
    var $fmlm_name = $(this).closest('tr').find('.fmlm_name').val();// Name of the Member
    var url = site_url("family_members/activate");
    changeStatus(url, { mbr_id: $mbr_id }, $fmlm_name, ACTIVE, function () {
        reset_fmlm_count($fmly_id, '+');
        show_fmlm_details($("#tbl_fmly .fmly_id[value=" + $fmly_id + "]").closest('td').find('.mbr_id').val());
    });
});
