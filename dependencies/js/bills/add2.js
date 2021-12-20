
function onProductChanged(obj) {
    // loadProductUnitsOptions(obj);
    loadProductData(obj);
}

function onCentralStoreChanged(obj) {
    loadGodowns(obj);
    loadCentralStoreState(obj);
    var curTabID = $("ul#bill-type-tab.nav-tabs a.active").attr('id');
    if (curTabID == "bill-type-sale-tab")
        loadFamilies(obj);
}

function onPartyChanged(obj) {
    loadGSTDetails(obj);

    var isTax = $('#bls_add_form .taxable').prop('checked');
    if (isTax)
        showHideTaxCols();
}

function onFamilyChanged(obj) {
    if (obj.val())
        setTimeout(function () {
            $('#bls_add_form .blp-tbl .sr-movement-row:first-child .next-input').eq(0).focus();
        }, 10);

}

function onGSTNoChanged(obj) {
    setState(obj);
    showHideTaxCols();
}

function onTaxTypeChanged(invokeCalculate) {
    showHideTaxCols(invokeCalculate);
}