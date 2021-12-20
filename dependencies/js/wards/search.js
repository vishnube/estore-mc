$(document).ready(function () {
    $('#wrd_search_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#wrd_search_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });



    $('#wrd_search_form .tlk_option').change(function () {
        $(this).closest('form').find('.ars_option').noOption();
        var t = $(this).closest('form').find('.ars_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('areas/get_options', {
            tlk_id: d
        }, t, t.closest('div'));
    });

    // Initializing the search form with default values.
    $('form#wrd_search_form').initForm();

    // Loading all wards. Before doing this you should initialize the search form
    loadWards(0);

    $('form#wrd_search_form').on('submit', function (e) {
        e.preventDefault();
        loadWards(0);
    });
});

function afterWrdSearchFormReset() {
    $('#wrd_search_form .dst_option').noOption();
    $('#wrd_search_form .tlk_option').noOption();
    $('#wrd_search_form .ars_option').noOption();
    $('#wrd_search_form .estr_option').noOption();
}