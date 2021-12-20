$(document).ready(function () {

    //$(document).on('click', '#add_usr', function () {
    $('#add_usr').click(function () {

        // Initializing the modal with default values.
        $('#usr_add_modal #usr_add_form').initForm();

        $('#usr_add_modal').find('.modal-title').text('ADD USER');

        // If $('#usr_add_form .mbr_fk_member_types') is <input type="text">, We need to load Members.
        loadMembers();

        $('#usr_add_modal').modal('show');
    });

    $('#usr_add_form .mbr_fk_member_types').change(loadMembers);


    $('form#usr_add_form, form#usr_edit_form').on('submit', function (e) {
        e.preventDefault();

        var input = $(this).serializeArray();

        $(this).postForm(site_url("users/save"), input, afterUsersave);
    });

});



function loadMembers() {
    if (!$('#usr_add_form .mbr_fk_member_types').val()) {
        $('#usr_add_form .usr_fk_members').noOption('No Members');
        return;
    }
    loadOption('members/get_non_user_options', { mbr_fk_member_types: $('#usr_add_form .mbr_fk_member_types').val() }, $('#usr_add_form .usr_fk_members'));
}

function beforeEditUser(usr_id) {
    var input = { usr_id: usr_id };
    $('form#usr_edit_form').postForm(site_url("users/before_edit"), input, function (res, form) {
        form.loadFormInputs(res);
        $('#usr_edit_modal').modal('show');
    });
}









// function beforeEditUser(usr_id) {
//     var input = { usr_id: usr_id };

//     // There may take some time to respond ajax. So here we setting the form title before postForm().
//     $('#usr_add_form .sr-form-tag .sr-form-title').html('EDIT USER');

//     $('form#usr_add_form').postForm(site_url("users/before_edit"), input, function (res, form) {
//         form.loadFormInputs(res);

//         // Inside postForm() it will reset the '.sr-form-title content'. So here we reseting it again
//         $('#usr_add_form .sr-form-tag .sr-form-title').html('EDIT USER');
//     });
// }