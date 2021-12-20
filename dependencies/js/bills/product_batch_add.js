$(document).ready(function () {

    // Initializing the from with default values.
    $('form#pdbch_add_form').initForm();

    $('form#pdbch_add_form').on('submit', function (e) {
        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("product_batches/save"), input, afterProductBatchSave);
    });

});


function afterProductBatchSave(res, form, input) {
    var pdbch_id = getInputValue(input, 'pdbch_id');
    activateTab('existing-batch');
    loadProductBatches();
    showSuccessToast('Batch saved successfully');
}

function AfterInitProductBatchAddForm() {

    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');

    // If Purchase
    if (curTabID == "bill-type-purchase-tab")
        $('#product_window #new-batch-tab').removeClass('disabled')

    // On sale, disabling "Product Batch Add" Tab
    else
        $('#product_window #new-batch-tab').addClass('disabled')
}
