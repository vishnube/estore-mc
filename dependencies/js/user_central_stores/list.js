$('#ucs_search_form .stt_option').change(function () {
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

$('#ucs_search_form .dst_option').change(function () {
    $(this).closest('form').find('.tlk_option').noOption();

    var t = $(this).closest('form').find('.tlk_option');
    var d = $(this).val();
    if (!d)
        return;
    loadOption('taluks/get_options', {
        tlk_fk_districts: d
    }, t, t.closest('div'));
});

$(document).ready(function () {

    // Initializing the search form with default values.
    $('form#ucs_search_form').initForm();

    $('form#ucs_search_form').on('submit', function (e) {
        e.preventDefault();
        loadUserCentralStores();
    });


    $(document).on('click', '#tbl_ucs .add_ucs', function () {
        var obj = $(this);
        usr_id = obj.closest('tr').find('.usr_id').val();
        cstr_id = obj.closest('tr').find('.cstr_id').val();
        var url = site_url("user_central_stores/insert_ucs");
        var container = obj.closest('td');
        $('#tbl_ucs').postForm(url, { usr_id: usr_id, cstr_id: cstr_id }, function (r, form, input) {
            obj.attr('class', r.button).attr('title', 'REMOVE');
            $('.usr_ucs_count').html(eval($('.usr_ucs_count').html()) + 1);
        }, '', container, false);
    });


    $(document).on('click', '#tbl_ucs .remove_ucs', function () {
        var obj = $(this);
        usr_id = obj.closest('tr').find('.usr_id').val();
        cstr_id = obj.closest('tr').find('.cstr_id').val();
        var url = site_url("user_central_stores/remove_ucs");
        var container = obj.closest('td');
        $('#tbl_ucs').postForm(url, { usr_id: usr_id, cstr_id: cstr_id }, function (r, form, input) {
            obj.attr('class', r.button).attr('title', 'ADD');
            $('.usr_ucs_count').html(eval($('.usr_ucs_count').html()) - 1);
        }, '', container, false);
    });

});


// Load pagination
function loadUserCentralStores(pagno) {
    var usr_id = $('form#ucs_search_form #usr_id').val();
    if (!usr_id) {
        Swal.fire('Oops!', 'No users are selected', 'error');
        return;
    }
    var input = $('form#ucs_search_form').serializeArray();
    var url = site_url("user_central_stores/get_ucss");
    $('form#ucs_search_form').postForm(url, input, function (r, form, input) {
        $('#ucs-result-container').html(r.html)
    }, '', $('#ucs-result-container'), false);
}


