/* Tooltip: Source: https://www.w3schools.com/css/css_tooltip.asp */

$(document).on('click', '.srtip-container', function () {
    $('.srtiptext').css('visibility', 'hidden')
    $(this).find('.srtiptext').css('visibility', 'visible')
})

// Hiding on outside click
$(document).mouseup(function (e) {
    var container = $(".srtiptext");

    // If the target of the click isn't the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.css('visibility', 'hidden')
    }
});