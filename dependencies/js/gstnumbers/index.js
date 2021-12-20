$(document).on('click', '.show_gst', function () {
    var mbr_id = $(this).closest('tr').find('.mbr_id').val(); // member id of Member
    show_gst_details(mbr_id);
});

/**
 * 
 * @param {*} mbr_id : mbr_id of the Member
 */
function show_gst_details(mbr_id) {
    var input = { mbr_id: mbr_id }; // mbr_id of Member.
    $('#gstnumber_details_modal').postForm(site_url("gstnumbers/get_details"), input, function (res) {
        $('#gstnumber_details_modal').modal('show');
        $('#gstnumber_details_modal .modal-content').html(res.html);
    });
}




/**
 * This function should be in global scope.
 * Don't put this function in "gstnumber/add.js".
 */
$(document).on('click', '#tbl_gst .edit_gst', function () {
    $('form#gst_add_form .gst-back-btn').show();
    var gst_id = $(this).closest('tr').find('.gst_id').val();
    beforeEditMemberMember(gst_id);
});

$(document).on('click', '#tbl_gst .deactivate_gst', function () {
    var $gst_id = $(this).closest('tr').find('.gst_id').val();
    var $gst_name = $(this).closest('tr').find('.gst_name').val();
    var mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var url = site_url("gstnumbers/deactivate");
    changeStatus(url, { gst_id: $gst_id }, $gst_name, INACTIVE, function () {
        afterGstStatusChanged(mbr_id);
    });
});

$(document).on('click', '#tbl_gst .activate_gst', function () {
    var $gst_id = $(this).closest('tr').find('.gst_id').val(); // member_id of PARTY
    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();    // Member id of PARTY
    var $gst_name = $(this).closest('tr').find('.gst_name').val();// Name of the Member
    var url = site_url("gstnumbers/activate");
    changeStatus(url, { gst_id: $gst_id }, $gst_name, ACTIVE, function () {
        afterGstStatusChanged($mbr_id);
    });
});
