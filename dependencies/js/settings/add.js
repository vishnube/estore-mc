$(document).ready(function () {

    // Initializing the from with default values.
    $('form#st_add_form').initForm();

    $('form#st_add_form').on('submit', function (e) {

        e.preventDefault();

        // Enabling disabled elements to take its values.
        // Because serializeArray() will not include the values of disabled elements.   
        var disabled = $(this).find(':input:disabled').removeAttr('disabled');

        var input = $(this).serializeArray();

        // Again desabling the desabled elements
        disabled.attr('disabled', 'disabled');

        $(this).postForm(site_url("settings/save"), input, afterSettingsave);
    });

    $(document).on('keydown', 'form#st_add_form #dv-pval-range #rangeTo', function (e) {
        var key = e.keyCode || e.which;
        if (key == ENTER)
            createRange();
    })

    $(document).on('click', 'form#st_add_form #dv-pval-range #go', function () {
        createRange();
    });

    $('form#st_add_form #st_input').on('change', function (e) {
        adjustPvals(true)
    });

    $(document).on('keyup', 'form#st_add_form #st_name', function (e) {
        $('form#st_add_form #st_desc').val($(this).val());
    });

    // $(document).on('keyup', 'form#st_add_form #st_dval', function (e) {
    //     $('form#st_add_form #cst_val').val($(this).val());
    // });

});

function adjustPvals(autoRadio) {
    var inputType = $('form#st_add_form #st_input').val();
    $('.sr-input-movement .sr-movement-row:gt(0)').remove();
    $('.sr-input-movement .next-input').val('');
    $('form#st_add_form #st_validation').prop('disabled', false);

    // If inputType is Textbox/Textarea
    if (inputType == 1 || inputType == 5) {
        $('.pvalblock').fadeOut(1000);
    }
    // If inputType is Dropdown/Radio
    else if (inputType == 2 || inputType == 3) {
        //Allowing to create new rows on enter key press. 
        // Because in the case of "Dropdown/Radio" multiple values are needed.
        // When enter key pressed on the class 'last-input' it will create a new row.
        if (!$('.sr-input-movement .next-input.val').hasClass('last-input'))
            $('.sr-input-movement .next-input.val').addClass('last-input');

        // You may need to create a range in the case of Dropdown/Radio.
        $('#dv-pval-range').show();

        $('.pvalblock').fadeIn(1000);

        // If inputType is Radio, creating values as 1 => Yes, 2 => No
        if (autoRadio === true && inputType == 3) {
            $('#dv-pval-range #rangeFrom').val(1);
            $('#dv-pval-range #rangeTo').val(2);
            createRange(['Yes', 'No']);
        }
    }


    // If inputType is Checkbox
    else if (inputType == 4) {
        // You should give the possible values.
        $('.pvalblock').fadeIn(1000);

        // No need to create a range in the case of Checkbox.
        $('#dv-pval-range').hide();

        //Blocking the creation of new rows on enter key press. 
        // Because in the case of "Checkbox" only one value is needed.
        // When enter key pressed on the class 'last-input' it will create a new row.
        $('.sr-input-movement .next-input.val').removeClass('last-input');

        $('form#st_add_form #st_validation').prop('disabled', true);
        $('form#st_add_form #st_validation').val('');
    }

}

function createRange($vals, $keyVals) {
    $('.sr-input-movement .sr-movement-row:gt(0)').remove();


    var inputType = $('form#st_add_form #st_input').val();

    // No need to create a range if the input type is Textbox/Checkbox/Textarea
    if (inputType == 1 || inputType == 5)
        return;

    var from = eval($('#dv-pval-range #rangeFrom').val());
    var to = eval($('#dv-pval-range #rangeTo').val());
    var clone;

    // Adding key-values for first row.
    if (typeof $vals != 'undefined' && $vals) {
        $('.sr-input-movement .sr-movement-row').eq(0).find('.next-input').eq(0).val(from);
        $('.sr-input-movement .sr-movement-row').eq(0).find('.next-input').eq(1).val($vals[0]);
    }
    else if (typeof $keyVals != 'undefined' && $keyVals) {
        // Geting the first key-value pair
        var key = Object.keys($keyVals)[0];
        var val = $keyVals[key];
        $('.sr-input-movement .sr-movement-row').eq(0).find('.next-input').eq(0).val(key);
        $('.sr-input-movement .sr-movement-row').eq(0).find('.next-input').eq(1).val(val);
    }
    else
        $('.sr-input-movement .sr-movement-row').eq(0).find('.next-input').val(from);


    // Adding key-values from 2nd row.
    for (var i = (from + 1); i <= to; i++) {
        clone = $('.sr-input-movement .sr-movement-row').eq(0).clone();

        if (typeof $vals != 'undefined' && $vals) {
            clone.find('.next-input').eq(0).val(i);
            clone.find('.next-input').eq(1).val($vals[i - 1]);
        }
        else if (typeof $keyVals != 'undefined' && $keyVals) {
            // Geting the first key-value pair
            var key = Object.keys($keyVals)[i - 1];
            var val = $keyVals[key];

            clone.find('.next-input').eq(0).val(key);
            clone.find('.next-input').eq(1).val(val);
        }
        else
            clone.find('.next-input').val(i);

        $(".sr-input-movement").append(clone);
    }
}

function beforeEditSettings(st_id) {
    var input = { st_id: st_id };

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#st_add_form .sr-form-tag .sr-form-title').html('EDIT SETTINGS');

    $('form#st_add_form').postForm(site_url("settings/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#st_add_form .sr-form-tag .sr-form-title').html('EDIT SETTINGS');

        adjustPvals(false);

        // Creating possible values rows
        var totalRows = Object.keys(res.st_pval).length;
        $('#dv-pval-range #rangeFrom').val(1);
        $('#dv-pval-range #rangeTo').val(totalRows);
        createRange(false, res.st_pval);
    });
}