$(document).on('keydown', function (e) {
    // Avoiding Popup boxes.
    // Becaulse some popup boxes uses the same shortcut keys.
    // Eg: "Alt + S" is using in "Vehicle Quick Add" popup to save the data.
    if ($(e.target).parents('.popupBox').length)
        return;

    var key = e.keyCode || e.which;

    // Alt + d
    if ((key === 68) && e.altKey) {
        e.preventDefault();

        $('#bls_add_form .blp-tbl .bls_gross_disc').focus();
    }

    // // Shift + p/P
    // if ((key === 80) && e.shiftKey) {
    //     e.preventDefault();

    //     alert("Shift  + P")
    // }

    // // Shift + p/P
    // if ((key === 80) && e.ctrlKey) {
    //     e.preventDefault();

    //     alert("Ctrl  + P")
    // }

    // // Alt + F
    // if ((key === 70) && e.altKey) {
    //     e.preventDefault();

    //     $('#dv_bill .cert-container .sbf_fk_items:first-child').focus();
    // }

    // // Alt + r
    // if ((key === 82) && e.altKey) {
    //     e.preventDefault();

    //     $('#dv_bill .tbl_pay .round_off').focus();
    // }



    // // Alt + s/S
    // if ((key === 83) && e.altKey) {
    //     get_ready_print();
    //     e.preventDefault();
    //     submit_my_form(e, $('.my_form_post'))
    // }

    // // Alt + v/V
    // else if ((key === 86) && e.altKey) {
    //     e.preventDefault();

    //     // Temporary Vehicle
    //     if ($('#dv_bill .dv_vown input[name=vhcl_owner]:checked').val() == 4)
    //         $('#dv_bill #tmp_vhcl').focus();

    //     else
    //         $('#dv_bill .dv_vown_block #vhcl').focus();

    // }

    // // Alt + I
    // else if ((key === 73) && e.altKey) {
    //     e.preventDefault();
    //     $('.tbl_bill_body tbody tr:first').find('.itm_id').focus();
    // }


    // // Alt + G
    // else if ((key === 71) && e.altKey) {
    //     e.preventDefault();
    //     $('.tbl_bill_body tbody tr:first').find('.gdn_id').focus();
    // }


    // // Alt + C
    // else if ((key === 67) && e.altKey) {
    //     e.preventDefault();
    //     $('#dv_bill .tbl_pay .paid').focus();
    // }


});