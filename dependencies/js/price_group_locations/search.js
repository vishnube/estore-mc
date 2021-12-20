$(document).ready(function () {

    $('#pgpl_search_form .stt_option').change(function () {
        $(this).closest('form').find('.dst_option').noOption();
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();

        var t = $(this).closest('form').find('.dst_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('districts/get_options', {
            dst_fk_states: d
        }, t, t.closest('div'));
    });

    $('#pgpl_search_form .dst_option').change(function () {
        $(this).closest('form').find('.ars_option').noOption();
        $(this).closest('form').find('.wrd_option').noOption();

        var t = $(this).closest('form').find('.ars_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('areas/get_options_by_district', {
            dst_id: d
        }, t, t.closest('div'));
    });

    $('#pgpl_search_form .ars_option').change(function () {
        $(this).closest('form').find('.wrd_option').noOption();
        var d = $(this).val();
        if (!d)
            return;

        t = $(this).closest('form').find('.wrd_option');
        loadOption('wards/get_options2', {
            ars_id: d
        }, t, t.closest('div'));
    });

    // Initializing the search form with default values.
    $('form#pgpl_search_form').initForm();

    // Loading all price_group_locations. Before doing this you should initialize the search form
    load_price_group_locations(0);

    $('form#pgpl_search_form').on('submit', function (e) {
        e.preventDefault();
        load_price_group_locations(0);
    });

    // Detect pagination click
    $("#pgpl_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        load_price_group_locations(pageno);
    });
});