$(document).ready(function () {


    $(document).on('click', '#add_grp', function () {

        // Initializing the modal with default values.
        $('#grp_add_modal #grp_add_form').initForm();

        $('#grp_add_modal').find('.modal-title').text('ADD GROUP')

        $('#grp_add_modal').modal('show');
    });


    $('form#grp_add_form').on('submit', function (e) {

        e.preventDefault();

        var input = $(this).serializeArray();

        $(this).postForm(site_url("groups/save"), input, aftergroupSave)
    });

});

function beforeEdit_grp(grp_id) {
    var input = { grp_id: grp_id };
    $('form#grp_add_form').postForm(site_url("groups/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#grp_add_modal').modal('show');
    });
}
