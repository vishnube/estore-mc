

$(document).on('click', '.pdbch-prices', function () {
    $('#batch_prices_modal').modal('show');
    var html = '';

    $(this).find('.price-row').each(function () {
        html += '<tr class="r1">';
        html += '<td rowspan="2">&nbsp;';
        if ($(this).hasClass('sel'))
            html += '<i class="fal fa-check text-success cursor-pointer" title="Selected Price Group" style="font-size: 30px;"></i>';
        html += '</td>'
        html += '<td>' + $(this).attr('data-pgp_name') + ' <span class="code-block">#PRC-' + $(this).attr('data-pgprd_id') + '</span>' + '</td>';
        html += '<td>' + parseFloat($(this).attr('data-pgprd_qty')) + ' ' + $(this).attr('data-unt_name') + '</td>';
        html += '<td>' + parseFloat($(this).attr('data-pgprd_rate')) + '</td>';
        html += '<td>' + $(this).attr('data-lev') + '</td>';
        // html += '<td>' + $.format.date($(this).attr('data-pgprd_date') + ' 00:00:00.546', "dd/MM/yyyy") + '</td>';
        html += '</tr>';
        html += '<tr class="r2"><td colspan="5">' + $(this).html() + '</td></tr>';
    });


    if (!html)
        html = get_no_result_row($('#batch_prices_modal #tbl_price'), 'NO PRICE GROUPS');
    $('#batch_prices_modal #tbl_price tbody').html(html);
});