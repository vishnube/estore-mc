
$(document).on('click', '#tsk-main-container .add_many', function () {
    var $tsk_id = $(this).closest('li').find('.tsk_id').val();
    var $tsk_name = $(this).closest('li').find('.tsk_name').val();

    // Initializing the modal with default values.
    $('#add_many_modal #add_many_form').initForm();

    $('#add_many_modal').find('.modal-title').text('ADD CHILDREN FOR "' + $tsk_name + '"')
    $('#add_many_modal #parent_id').val($tsk_id);
    $('#add_many_modal').modal('show');

});

$(document).on('keyup', 'form#add_many_form #tbl_prefix', function () {
    var p = $(this).val();
    $('#add_many_modal .tbl-addmay tbody  tr .tsk_key').each(function () {
        $(this).val('tsk_' + p + '_' + $(this).attr("data-sufix"))
    })
});

$('form#add_many_form').on('submit', function (e) {

    e.preventDefault();

    var tsk_parent = $('#add_many_modal #parent_id').val();
    if (!tsk_parent) {
        Swal.fire('Oops!', "Parent id not found, Please try again", 'error');
        $('#add_many_modal').modal('hide');
        return;
    }

    var input = new Object();

    $('#add_many_modal .tbl-addmay tbody  tr').each(function (i, r) {
        input[i] = {
            tsk_parent: tsk_parent,
            tsk_name: $(this).find('.tsk_name').val(),
            tsk_key: $(this).find('.tsk_key').val(),
            tsk_menu: $(this).find('.tsk_menu').prop('checked') ? 1 : 2,
            tsk_type: $(this).find('.tsk_type').val()
        };
    });


    $('#add_many_modal #add_many_form').postForm(site_url("tasks/save_many"), input, function (r, form, input) {
        loadTasks();
        showSuccessToast('Tasks saved successfully');
        $('#add_many_modal .query').copyQuery(r.query); // Showing query and copying to clip board.
        setTimeout(function () { $('#add_many_modal').modal('hide'); }, 3000);
    });

});