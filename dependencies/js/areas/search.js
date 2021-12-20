$(document).ready(function () {
    $('#ars_search_form .stt_option').change(function () {
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

    $('#ars_search_form .dst_option').change(function () {
        $(this).closest('form').find('.tlk_option').noOption();

        var t = $(this).closest('form').find('.tlk_option');
        var d = $(this).val();
        if (!d)
            return;
        loadOption('taluks/get_options', {
            tlk_fk_districts: d
        }, t, t.closest('div'));
    });

    // Initializing the search form with default values.
    $('form#ars_search_form').initForm();

    // Loading all areas. Before doing this you should initialize the search form
    loadAreas(0);

    $('form#ars_search_form').on('submit', function (e) {
        e.preventDefault();
        loadAreas(0);
    });

    // Detect pagination click
    $("#ars_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        loadAreas(pageno);
    });
});