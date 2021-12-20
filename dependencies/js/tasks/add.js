$(document).ready(function () {

    // Initializing the from with default values.
    $('form#tsk_add_form').initForm();

    // Clearing previous query if exist.
    $('form#tsk_add_form #dv-query').html('');

    $('form#tsk_add_form').on('submit', function (e) {

        e.preventDefault();

        // Clearing previous query if exist.
        $('form#tsk_add_form #dv-query').html('');

        var input = $(this).serializeArray();

        $(this).postForm(site_url("tasks/save"), input, afterTasksave)
    });

});

function beforeEditTask(tsk_id) {
    var input = { tsk_id: tsk_id };

    // Clearing previous query if exist.
    $('form#tsk_add_form #dv-query').html('');

    // There may take some time to respond ajax. So here we setting the form title before postForm().
    $('#tsk_add_form .sr-form-tag .sr-form-title').html('EDIT TASK');

    $('form#tsk_add_form').postForm(site_url("tasks/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);

        // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
        $('#tsk_add_form .sr-form-tag .sr-form-title').html('EDIT TASK');
    });
}