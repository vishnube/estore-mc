// Block form submition on ENTER key press on inputs exept SUBMIT button.
// Also jumpts to next element, But bypass hidden elements.
// Navigation will be blocked if the element has class 'enter-lock'
$(document).on('keydown', '.sr-form :input:not([type="submit"],textarea)', function (e) {

    var key = e.keyCode || e.which;

    var allowed = ':input:not([disabled]):not([type="hidden"]):not(.no-enter):visible,checkbox:visible,radio:visible';
    var inputs = $(this).closest(".sr-form").find(allowed);

    // Moving back on Shift + ENTER
    if (e.shiftKey && (key == ENTER)) {

        // Skipping the movement if the element has 'enter-lock' class.
        if ($(this).hasClass('enter-lock'))
            return false;

        var prev = inputs[inputs.index(this) - 1];

        if (prev != null) {
            prev.focus();
        }
        return false;
    }

    // Moving forward on ENTER
    else if (key == ENTER) {

        // Skipping the movement if the element has 'enter-lock' class.
        if ($(this).hasClass('enter-lock'))
            return false;

        var next = inputs[inputs.index(this) + 1];

        if (next != null) {
            next.focus();
        }
        return false;
    }
});

$(document).on('keydown', function (e) {
    var F1 = 112;
    var F2 = 113;
    var F3 = 114;
    var key = e.keyCode || e.which;

    if (key == F1) {
        e.preventDefault();
        window.open(site_url('purchases'), '_blank');
    }
});


// Jump to next input  having a 'tabindex' attribute which is greater than this element on TAB key press.
// By default TAB key doing this. So why this function?
// 1. By default it is not jumping to checkbox, radio. But this function doing this.
// 2. This function bypasses hidden elements.
// $(document).on('keydown', '.sr-form :input', function (e) {

//     var key = e.keyCode || e.which;

//     if (key == TAB) {
//         e.preventDefault();
//         var tabed = ':input[tabindex]:not([disabled]):not([type="hidden"]):visible,textarea[tabindex]:visible,checkbox[tabindex]:visible,radio[tabindex]:visible';
//         var form = $(this).closest(".sr-form");

//         // Collecting all elements those having set 'tabindex' attribute.
//         var tabedInputs = form.find(tabed);

//         if (!tabedInputs.length)
//             return false;

//         // Don't put minTabindex = 1. Because we are considering here only the elements under this form.
//         // Starting tabindex under this form may be like 10, 5, ect.
//         var minTabindex = 10000;
//         var maxTabindex = 0;
//         var found = false; // couldn't find next tabindexed element.

//         // Finding Maximum value of tabindex
//         tabedInputs.each(function () {
//             if (Number($(this).attr('tabindex')) <= 0)
//                 return true;
//             minTabindex = Math.min(minTabindex, Number($(this).attr('tabindex')));
//             maxTabindex = Math.max(maxTabindex, Number($(this).attr('tabindex')));
//         });

//         // If the TAB key is pressed on SUBMIT button, focusing to the first tabindexed element.
//         if ($(this).attr('type') == 'submit') {
//             form.find('[tabindex=' + minTabindex + ']').focus();
//             return false;
//         }

//         var thisTabindex = $(this).attr('tabindex');

//         // IF the invoked element is the last tabindexed element, focusing to the first tabindexed element.
//         if (thisTabindex == maxTabindex) {
//             form.find('[tabindex=' + minTabindex + ']').focus();
//             return false;
//         }

//         // If this invoked element has a tabindex property
//         else if (thisTabindex) {
//             thisTabindex++; //increment tabindex

//             //after increment of tabindex ,make the next element focus
//             $(':input[tabindex=' + thisTabindex + ']').focus();
//             return false;
//         }

//         // If this invoked element has no tabindex property.
//         // Finding next element having next tabindex.
//         else {
//             var allowed = ':input:not([disabled]):not([type="hidden"]):visible,textarea:visible,checkbox:visible,radio:visible';
//             var allInputs = form.find(allowed);
//             var thisIndex = allInputs.index(this);
//             var lastTabindex = 0;
//             var nextTabindex = lastTabindex + 1;
//             $.each(allInputs, function (i, target) {
//                 var targetTabindex = $(target).prop('tabindex');
//                 var targetIndex = allInputs.index($(target));

//                 // Only element after the invoked elements 
//                 if (targetIndex > thisIndex && (targetTabindex == (Number(lastTabindex) + 1))) {
//                     $(target).focus();
//                     found = true;
//                     return false;
//                 }
//                 else if (targetTabindex == nextTabindex) {
//                     lastTabindex = targetTabindex;
//                     nextTabindex++;
//                 }
//             });
//         }

//         // If couldn't found next tabindexed element, focusing to first tabindex element.
//         if (!found) {
//             form.find('[tabindex=' + minTabindex + ']').focus();
//         }

//         return false;
//     }
// });



// Jump to previous input  having a 'tabindex' attribute which is greater than this element on SHIFT + TAB key press.
// By default SHIFT + TAB key doing this. So why this function?
// 1. By default it is not jumping to checkbox, radio. But this function doing this.
// 2. This function bypasses hidden elements.
// $(document).on('keydown', '.sr-form :input', function (e) {

//     var key = e.keyCode || e.which;

//     if (e.shiftKey && (key == TAB)) {
//         e.preventDefault();
//         var tabed = ':input[tabindex]:not([disabled]):not([type="hidden"]):visible,textarea[tabindex]:visible,checkbox[tabindex]:visible,radio[tabindex]:visible';
//         var form = $(this).closest(".sr-form");

//         // Collecting all elements those having set 'tabindex' attribute.
//         var tabedInputs = form.find(tabed);

//         if (!tabedInputs.length)
//             return false;

//         // Don't put minTabindex = 1. Because we are considering here only the elements under this form.
//         // Starting tabindex under this form may be like 10, 5, ect.
//         var minTabindex = 10000;
//         var maxTabindex = 0;
//         var found = false; // couldn't find next tabindexed element.

//         // Finding Maximum value of tabindex
//         tabedInputs.each(function () {
//             if (Number($(this).attr('tabindex')) <= 0)
//                 return true;
//             minTabindex = Math.min(minTabindex, Number($(this).attr('tabindex')));
//             maxTabindex = Math.max(maxTabindex, Number($(this).attr('tabindex')));
//         });

//         // If the TAB key is pressed on SUBMIT button, focusing to the first tabindexed element.
//         if ($(this).attr('type') == 'submit') {
//             form.find('[tabindex=' + maxTabindex + ']').focus();
//             return false;
//         }

//         var thisTabindex = $(this).attr('tabindex');

//         // IF the invoked element is the first tabindexed element, focusing to the last tabindexed element.
//         if (thisTabindex == minTabindex) {
//             form.find('[tabindex=' + maxTabindex + ']').focus();
//             return false;
//         }

//         // If this invoked element has a tabindex property
//         else if (thisTabindex) {
//             thisTabindex--; //decrement tabindex

//             //after increment of tabindex ,make the next element focus
//             $(':input[tabindex=' + thisTabindex + ']').focus();
//             return false;
//         }

//         // If this invoked element has no tabindex property.
//         // Finding next element having next tabindex.
//         else {
//             var allowed = ':input:not([disabled]):not([type="hidden"]):visible,textarea:visible,checkbox:visible,radio:visible';
//             var allInputs = form.find(allowed);
//             var thisIndex = allInputs.index(this);
//             var lastTabindex = maxTabindex;
//             var prevTabindex = lastTabindex - 1;

//             // Itrating in  reverse order
//             $.each(allInputs.get().reverse(), function (i, target) {
//                 var targetTabindex = $(target).prop('tabindex');
//                 var targetIndex = allInputs.index($(target));

//                 // Only element after the invoked elements 
//                 if (targetIndex < thisIndex && (targetTabindex == (Number(lastTabindex) - 1))) {
//                     $(target).focus();
//                     found = true;
//                     return false;
//                 }
//                 else if (targetTabindex == prevTabindex) {
//                     lastTabindex = targetTabindex;
//                     prevTabindex--;
//                 }
//             });
//         }

//         // If couldn't found next tabindexed element, focusing to first tabindex element.
//         if (!found) {
//             form.find('[tabindex=' + maxTabindex + ']').focus();
//         }

//         return false;
//     }
// });