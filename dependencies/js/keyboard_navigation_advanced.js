/**
 *  Table traversor:
 *  moving front/back/up/down through input elements in a container when pressing ENTER/UP/DOWN/LEFT/RIGHT Arrows.
 *  and creating new rows at the end of the last row.
 * 
 *  Documentation: 
 *  1. Use class 'sr-input-movement-container' as parent container .
 *     It is needed only when you need to scroll the table content under a fixed height container. 
 *  2. Use class 'sr-input-movement' for container table which is the nearest child of 'sr-input-movement-container'
 *  3. Use class 'sr-movement-row' for each rows.
 *  4. Use class 'rem' for icon to delete row. (Also [Alt + x])
 *  5. Use class 'sr-movement-slno' if you need to keep serial No.
 *  6. Use class 'next-input' for each input need to traverse.
 *  7. Use class 'last-input' for the last input, where the new row will be created on ENTER/RIGHT ARROW press.
 *  8. Use class 'no-movement' for inputs wich are not participating in traversion.
 * 
 * Values passed through DATA attribute of the 'sr-input-movement-container'.
 *  1.  data-scrlHt     =>  [Optional] A fixed lenght to scroll when focusing to each rows. 
 *                          If not set, it'll be calculated automatically by row height.
 *  2.  data-howMany    =>  [Optional] How many times 'data-scrlHt' should be applied. If not set it will be 1.
 *                          Eg: if data-howMany = 2, then scroll length will be 'data-scrlHt' * 2
 * 
 *  Values passed through DATA attribute of the 'sr-input-movement'.
 *  1. data-bfrNew => Name of the function which will be called before the creation of a new row. Function parameters are 'container, lastRow'
 *  2. data-afrNew => Name of the function which will be called after the creation of a new row. Function parameters are 'container, lastRow, newRow'
 *                  Eg: you may need to initialize the select2, date fields ect.
 * 
 *  3. data-bfrRem => Name of the function which will be called before removing a row. Function parameters are 'container, row'
 *  4. data-afrRem => Name of the function which will be called after removal of a row. Function parameters are 'container'
 *  5. data-radFormat => true: All radio buttons will be renamed when a new row created or removed.
 *                       false (or undefined):  No formats on radios.
 *  6. data-onInit => The function will be called on initialization when initMovementTable() function invoked. 
 *  7. data-initNewRow => Default = true; When creating a new row, Initializing all elements in the new row by initForm() function.
 *  8. data-blockNewRow =>  Default = false; New rows won't be created. Rather traversing through existing rows
 * 
 *  Use class 'enter-lock' with each input element to skip the javascript actions due to the 'js\keyboard_navigation.js' if the input is enclosed by <form class="sr-form">.
 *  Use 'data-default' attribute with each elements to keep its own default values when creating a new row.
 * 
 * ##  Example  ##
 * ---------------
 * 
 * 
 *  <div class="sr-input-movement-container" style="height:400px; overflow: auto" data-scrlHt="20" data-howMany="1">
 *       <table class="sr-input-movement" data-bfrNew="" data-afrNew="" data-bfrRem="" data-afrRem=""
 *                        data-radFormat="true" data-onInit="" data-initNewRow="true" data-blockNewRow="">
 *           <tr class="sr-movement-row">
 *               <td>   
 *                      <i class="fal fa-times-circle rem cursor-pointer" title="Delete [Alt + x]"></i> 
 *                      <span class="sr-movement-slno"></span>
 *               </td>
 *               <td> <input type="text" class="next-input enter-lock" data-default="Shihab"></td>
 *               <td>
 *                   <input type="radio" name="status2" class="next-input enter-lock" data-default="true">Active
 *                   <input type="radio" name="status2" class="next-input enter-lock">Inactive
 *               </td>
 *               <td><input type="checkbox" class="next-input enter-lock" data-default="true">Validate</td>
 *               <td> <input type="text" class="next-input enter-lock"></td>
 *               <td>
 *                   <select class="next-input enter-lock">
 *                       <option value="1">Pencil</option>
 *                       <option value="2">Book</option>
 *                       <option value="3">Soap</option>
 *                   </select>
 *               </td>
 *               <td><textarea class="next-input enter-lock"></textarea></td>
 *               <td> <input type="text" class="next-input last-input enter-lock"></td>
 *           </tr>
 *       </table>
 *  </div>
 *  
 * */

function createNewSrInputRow(container, row, invokeCallbacks) {
    invokeCallbacks = ifDef(invokeCallbacks, invokeCallbacks, true)
    var lastRow = container.find('.sr-movement-row:last-child'); // Last row of the container

    // Callbacks
    if (invokeCallbacks) {
        var knaBeforeNew = eval(container.attr("data-bfrNew")); // Callback function will be called before creating new row
        var knaAfterNew = eval(container.attr('data-afrNew')); // Callback function will be called after creating new row
        var knaNewRowInit = eval(container.attr('data-initNewRow')); // True/False, Default is True, Determining the inputs in new row should be initialized or not.
        knaNewRowInit = (typeof knaNewRowInit == 'undefined') ? true : knaNewRowInit;

        // Calling function before creating a new row.
        if (typeof knaBeforeNew == "function")
            knaBeforeNew(container, lastRow);
    }

    var clone = row.clone();

    // Unchecking radios in new row to prevent the checked statatus of radios on previous rows.
    clone.find('input[type=radio]').prop('checked', false);

    newRow = clone.insertAfter(lastRow);
    newRow.find('.next-input').eq(0).focus();

    // Formating names of radios.
    if (container.attr('data-radFormat') == 'true')
        container.kna_formatRadios();

    // Initializing with default values (by data-default attribute @ C:\wamp\www\myapp\dependencies\js\common.js)
    if (invokeCallbacks && knaNewRowInit) {
        newRow.initForm();
    }

    container.resetSRMovementSlNo(); // Reseting serial no.

    // Calling function after creating a new row.
    if (invokeCallbacks && (typeof knaAfterNew == "function"))
        knaAfterNew(container, lastRow, newRow);
    $(newRow).srMovementScrollTo();
}


$(document).on('keydown', '.sr-input-movement :input:not([type="submit"],textarea,.no-movement)', function (e) {
    var key = e.keyCode || e.which;
    var container = $(this).closest(".sr-input-movement");
    var rows = container.find('.sr-movement-row');
    var row = $(this).closest(".sr-movement-row"); // Current row
    var lastRow = container.find('.sr-movement-row:last-child'); // Last row of the container
    var curInput = $(this); // Current input element.
    var allInputs = container.find('.next-input:visible:not([disabled]):not([type="hidden"])');  // All input elements
    var rowInputs = row.find('.next-input');  // All input elements
    var prevRow = rows[rows.index(row) - 1];
    var nextRow = rows[rows.index(row) + 1];
    var newRow = '';
    var leftArrow = 37;
    var upArrow = 38;
    var rightArrow = 39;
    var downArrow = 40;

    // Allow to create new row. 
    // Default = true. If false, new row won't be created rather only can travers through the table
    var createRow = eval(container.attr('data-blockNewRow'));
    createRow = ifDef(createRow, createRow, true);

    // Moving back on Shift + ENTER (or Left Arrow)
    if ((e.shiftKey && (key == ENTER)) || (key == leftArrow)) {
        var prev = allInputs[allInputs.index(curInput) - 1];

        if (prev != null) {
            prev.focus();
            $(prev).closest('.sr-movement-row').srMovementScrollTo();
        }
        return false;
    }

    // If ENTER/Right Arrow key pressed.
    else if (key == ENTER || key == rightArrow) {

        // If the the element is the last (having a class 'last-input') element of the last row, creating new row
        if (row.is(lastRow) && curInput.hasClass('last-input') && createRow) {
            createNewSrInputRow(container, row);
        }

        // Moving forward on ENTER
        else {
            var next = allInputs[allInputs.index(curInput) + 1];

            if (next != null) {
                next.focus();
                if (curInput.hasClass('last-input'))
                    $(next).closest('.sr-movement-row').srMovementScrollTo();
            }
            return false;
        }
    }

    // If UP ARROW key pressed
    else if (key == upArrow) {
        if (typeof prevRow != 'undefined') {
            var thisIndex = rowInputs.index(curInput);
            var input = $(prevRow).find('.next-input').eq(thisIndex);
            input.focus();
            input.closest('.sr-movement-row').srMovementScrollTo();
        }
    }

    // If DOWN ARROW key pressed
    else if (key == downArrow) {
        if (typeof nextRow != 'undefined') {
            var thisIndex = rowInputs.index(curInput);
            var input = $(nextRow).find('.next-input').eq(thisIndex)
            input.focus();
            input.closest('.sr-movement-row').srMovementScrollTo();
        }
    }
});


// Removing rows
$(document).on('click', '.sr-input-movement .rem', function (e) {
    srDelRow($(this), e)
});


// Removing rows when [Alt + x] pressed
$(document).on('keydown', '.sr-movement-row', function (e) {

    var key = e.keyCode || e.which;
    // Alt + x
    if ((key === 88) && e.altKey) {
        e.preventDefault();
        srDelRow($(this).find('.rem'), e)
    }
});

function srDelRow(handler, e) {
    var container = handler.closest(".sr-input-movement");

    // If only one row
    if (container.find('.sr-movement-row').length == 1)
        return;

    var row = handler.closest(".sr-movement-row"); // Current row
    var knaBeforeRem = eval(container.attr('data-bfrRem')); // Callback function will be called before removing a row
    var knaAfterRem = eval(container.attr('data-afrRem')); // Callback function will be called after removing a row    

    // Calling function before removing a row.
    if (typeof knaBeforeRem == "function")
        knaBeforeRem(container, row);

    if (row.prev(".sr-movement-row").length)
        row.prev(".sr-movement-row").find('.last-input').focus();
    else if (row.next(".sr-movement-row").length)
        row.next(".sr-movement-row").find('.next-input').eq(0).focus();

    row.remove();

    container.resetSRMovementSlNo(); // Reseting serial no.


    // Formating names of radios.
    if (container.attr('data-radFormat') == 'true')
        container.kna_formatRadios();

    // Calling function after removing a row.
    if (typeof knaAfterRem == "function")
        knaAfterRem(container);
}


$.fn.srMovementScrollTo = function () {
    var scrollTo = $(this); // Current row. The container will be scrolled to this row to make it visible.

    if (typeof scrollTo == 'undefined' || typeof scrollTo.offset() == 'undefined')
        return;

    var myContainer = scrollTo.closest('.sr-input-movement-container')

    if (typeof myContainer == 'undefined' || typeof myContainer.offset() == 'undefined')
        return;

    // A fixed lenght to scroll when focusing to each rows. 
    var scrlHt = eval(myContainer.attr("data-scrlHt"));

    // If it is not given calculating height of the current row.
    if (!scrlHt) {
        var margin = parseInt(scrollTo.css("marginBottom").replace('px', '')) + parseInt(scrollTo.css("marginTop").replace('px', ''));
        scrlHt = scrollTo.height() + margin;
    }

    // How many times 'scrlHt' should be applied.
    var howMany = eval(myContainer.attr('data-howMany')); // Callback function will be called after creating new row
    if (!howMany)
        howMany = 1;

    myContainer.scrollTop(scrollTo.offset().top - myContainer.offset().top + myContainer.scrollTop() - (scrlHt * howMany));
}


$(document).on('focus', '.sr-input-movement input[type="text"],textarea', function () {
    $(this).select();
});

$.fn.kna_formatRadios = function () {
    var i = 1;
    $(this).find('.sr-movement-row').each(function () {
        $(this).find('input[type=radio]').each(function () {
            $(this).attr('name', $(this).attr('name').split('_srkna_').shift() + '_srkna_' + i);
        });
        i++;
    });
}

// Initializing by removing all rows except the first one
$.fn.initMovementTable = function (flag) {

    var onInit = eval($(this).attr('data-onInit')); // Callback function will be called before removing a row

    // Calling function after removing a row.
    if (typeof onInit == "function")
        onInit($(this));
    else {
        $(this).find('.sr-movement-row').slice(1).remove();

        if (flag)
            $(this).find('.sr-movement-row').initForm();// @ common.js
    }
    $(this).resetSRMovementSlNo(); // Reseting serial no.
}


$.fn.resetSRMovementSlNo = function () {
    var slNos = $(this).find('.sr-movement-slno');
    slNos.each(function () {
        $(this).html(slNos.index($(this)) + 1)
    });
}

$(document).on('keyup', '.sr-input-movement :input:not([type="submit"])', function (e) {
    var key = e.keyCode || e.which;
    var upArrow = 38;
    var downArrow = 40;
    if (key == upArrow || key == downArrow) {
        $(this).focus();
    }
});