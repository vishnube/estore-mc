/**
 * This function should be in global scope.
 * Don't put this function in "task/add.js".
 * Because we will use the "TASK ADD" form and "task/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterTasksave(res, form, input) {
    $('form#tsk_add_form .query').copyQuery(res.query); // Showing query and copying to clip board.
    showSuccessToast('Task saved successfully');
    loadTasks();
}

/**
 * This function should be in global scope.
 * Don't put this function in "task/add.js".
 */
$(document).on('click', '#tsk-main-container .edit_tsk', function () {
    var $tsk_id = $(this).closest('li').find('.tsk_id').val();
    activateTab('add');
    beforeEditTask($tsk_id);
});

$(document).on('click', '#tsk-main-container .deactivate_tsk', function () {
    var $tsk_id = $(this).closest('li').find('.tsk_id').val();
    var $tsk_name = $(this).closest('li').find('.tsk_name').val();
    var url = site_url("tasks/deactivate");
    changeStatus(url, { tsk_id: $tsk_id }, $tsk_name, INACTIVE, loadTasks);
});

$(document).on('click', '#tsk-main-container .activate_tsk', function () {
    var $tsk_id = $(this).closest('li').find('.tsk_id').val();
    var $tsk_name = $(this).closest('li').find('.tsk_name').val();
    var url = site_url("tasks/activate");
    changeStatus(url, { tsk_id: $tsk_id }, $tsk_name, ACTIVE, loadTasks);
});



