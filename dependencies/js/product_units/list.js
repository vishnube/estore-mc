

function createProductUnitAddTable(res) {
    var tbl = $("#tbl_punt_add"); // @ product_units/add.php
    tbl.find("tbody").empty();

    var str = "";
    if (!res.unit_group_data.length) {
        str = '	<h3 class="text-danger text-center">' +
            '		<i class="fas fa-exclamation-triangle fa-lg"></i>' +
            '		<span class="pl-3">NO UNITS FOUND</span>' +
            '	</h3>';
    }
    $.each(res.unit_group_data, function (i, row) {
        str += "<tr>";
        str += '<td>' +
            '		<div class="form-group clearfix mt-2">' +
            '			<div class="icheck-success d-inline">' +
            '				<input name="punt_group_no" id="punt-' + row.ugp_group_no + '" class="punt_group_no" type="radio" value="' + row.ugp_group_no + '">' +
            '					<label for="punt-' + row.ugp_group_no + '"></label>' +
            '			</div>' +
            '		</div>' +
            '	</td>';


        str += "<td>";
        str += row.text;
        str += "</td>";

        str += "</tr>";
    });
    tbl.find("tbody").append(str);
}