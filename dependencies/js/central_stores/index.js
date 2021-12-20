$(document).ready(function () {
    $('#cstr_search_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#cstr_search_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });
});

/**
 * This function should be in global scope.
 * Don't put this function in "central_store/add.js".
 * Because we will use the "central_store ADD" form and "central_store/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterCentralStoreSave(res, form, input) {

    loadCentralStoreOptions();

    var pageNo = '';
    var mbr_id = input.get('mbr_id'); // Here input is FormData() obj

    // If the action was Edit
    if (typeof mbr_id != 'undefined' && mbr_id > 0) {
        pageNo = $("#cstr_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Central store saved successfully');
    loadCentralStores(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "central_store/add.js".
 */
$(document).on('click', '#tbl_cstr .edit_mbr', function () {

    // If no task assigned
    if (!tsk_cstr_edit)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    activateTab('add');
    beforeEditcentralStore($mbr_id);
});

$(document).on('click', '#tbl_cstr .deactivate_mbr', function () {

    // If no task assigned
    if (!tsk_cstr_deactivate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $cstr_id = $(this).closest('tr').find('.cstr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("central_stores/deactivate");
    changeStatus(url, { mbr_id: $mbr_id, cstr_id: $cstr_id }, $mbr_name, INACTIVE, function () {
        var pageNo = $("#cstr_pagination").curPage();
        loadCentralStores(pageNo);
    });
});

$(document).on('click', '#tbl_cstr .activate_mbr', function () {

    // If no task assigned
    if (!tsk_cstr_activate)
        return;

    var $mbr_id = $(this).closest('tr').find('.mbr_id').val();
    var $cstr_id = $(this).closest('tr').find('.cstr_id').val();
    var $mbr_name = $(this).closest('tr').find('.mbr_name').val();
    var url = site_url("central_stores/activate");
    changeStatus(url, { mbr_id: $mbr_id, cstr_id: $cstr_id }, $mbr_name, ACTIVE, function () {
        var pageNo = $("#cstr_pagination").curPage();
        loadCentralStores(pageNo);
    });
});



function aftercstrSearchFormReset() {
    $('#cstr_search_form .dst_option').noOption();
    $('#cstr_search_form .tlk_option').noOption();
}