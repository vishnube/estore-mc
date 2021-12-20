$(document).ready(function () {

    // Initializing the search form with default values.
    $('form#pgp_search_form').initForm();

    // Loading all price_groups. Before doing this you should initialize the search form
    load_price_groups(0);

    $('form#pgp_search_form').on('submit', function (e) {
        e.preventDefault();
        load_price_groups(0);
    });

    // Detect pagination click
    $("#pgp_pagination").on("click", "a", function (e) {
        e.preventDefault();
        var pageno = $(this).attr("data-ci-pagination-page");
        if (typeof pageno == "undefined")
            return;
        load_price_groups(pageno);
    });
});