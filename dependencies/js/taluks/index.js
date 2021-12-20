$(document).ready(function () {
    $('#tlk_search_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });
});

/**
 * This function should be in global scope.
 * Don't put this function in "taluk/add.js".
 * Because we will use the "TALUK ADD" form and "taluk/add.js" in several places of this applitlkion.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterTalukSave(res, form, input) {
    // loadTalukOptions(); // Taluk option will be loaded only after District Option changed.
    var pageNo = '';
    var tlk_id = getInputValue(input, 'tlk_id');

    // If the action was Edit
    if (typeof tlk_id != 'undefined' && tlk_id > 0) {
        pageNo = $("#tlk_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('Taluk saved successfully');
    loadTaluks(pageNo);
}


/**
 * This function should be in global scope.
 * Don't put this function in "taluk/add.js".
 */
$(document).on('click', '#tbl_tlk .edit_tlk', function () {
    var $tlk_id = $(this).closest('tr').find('.tlk_id').val();
    beforeEdit_tlk($tlk_id);
});



$(document).on('click', '#tbl_tlk .deactivate_tlk', function () {
    var $tlk_id = $(this).closest('tr').find('.tlk_id').val();
    var $tlk_name = $(this).closest('tr').find('.tlk_name').val();
    var url = site_url("taluks/deactivate");
    changeStatus(url, { tlk_id: $tlk_id }, $tlk_name, INACTIVE, afterChangeTalukStatus);
});

$(document).on('click', '#tbl_tlk .activate_tlk', function () {
    var $tlk_id = $(this).closest('tr').find('.tlk_id').val();
    var $tlk_name = $(this).closest('tr').find('.tlk_name').val();
    var url = site_url("taluks/activate");
    changeStatus(url, { tlk_id: $tlk_id }, $tlk_name, ACTIVE, afterChangeTalukStatus);
});

function afterChangeTalukStatus() {
    loadTaluks();
    loadTalukOptions();
}


