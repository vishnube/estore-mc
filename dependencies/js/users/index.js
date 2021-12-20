/**
 * This function should be in global scope.
 * Don't put this function in "user/add.js".
 * Because we will use the "USER ADD" form and "user/add.js" in several places of this application.
 * On each of these places the purpose of this function may be different.
 * @param {*} res       :   Ajax Response
 * @param {*} form      :   Invoked Form Element
 * @param {*} input     :   $_POST values
 */
function afterUsersave(res, form, input) {

    loadUserOptions();

    // Reseting the values of usr_add_form > usr_fk_members
    loadMembers();

    var pageNo = '';
    var usr_id = getInputValue(input, 'usr_id');

    // If the action was Edit
    if (typeof usr_id != 'undefined' && usr_id > 0) {
        pageNo = $("#usr_pagination").curPage();
        activateTab('list');
    }

    // If the action was Add
    else
        pageNo = 0;

    showSuccessToast('User saved successfully');
    loadUsers(pageNo);
}

/**
 * This function should be in global scope.
 * Don't put this function in "user/add.js".
 */
$(document).on('click', '#tbl_usr .edit_usr', function () {
    var $usr_id = $(this).closest('tr').find('.usr_id').val();
    beforeEditUser($usr_id);
});

$(document).on('click', '#tbl_usr .deactivate_usr', function () {
    var $usr_id = $(this).closest('tr').find('.usr_id').val();
    var $usr_name = $(this).closest('tr').find('.usr_name').val();
    var url = site_url("users/deactivate");
    changeStatus(url, { usr_id: $usr_id }, $usr_name, INACTIVE, function () {
        var pageNo = $("#usr_pagination").curPage();
        loadUsers(pageNo);
        loadUserOptions();
    });
});

$(document).on('click', '#tbl_usr .activate_usr', function () {
    var $usr_id = $(this).closest('tr').find('.usr_id').val();
    var $usr_name = $(this).closest('tr').find('.usr_name').val();
    var url = site_url("users/activate");
    changeStatus(url, { usr_id: $usr_id }, $usr_name, ACTIVE, function () {
        var pageNo = $("#usr_pagination").curPage();
        loadUsers(pageNo);
        loadUserOptions();
    });
});

$(document).on('click', '#tbl_usr .unlock_usr', function () {
    var $usr_id = $(this).closest('tr').find('.usr_id').val();
    var $usr_name = $(this).closest('tr').find('.usr_name').val();
    var url = site_url("users/unlock");
    Swal.fire({
        title: 'Are you sure?',
        html: "This will unlock <b>" + $usr_name + "</b>",
        icon: 'warning',
        iconHtml: '<i class="fas fa-unlock"></i>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, unlock him!'
    }).then((result) => {
        if (result.value) {
            $.post(url, { usr_id: $usr_id }, function (r) {
                if (r.status == 1) {
                    Swal.fire(
                        'Unlocked!',
                        '<b>' + $usr_name + '</b> has been unlocked',
                        'success'
                    );
                    var pageNo = $("#usr_pagination").curPage();
                    loadUsers(pageNo);
                }
                else {
                    Swal.fire(
                        'Error!',
                        'Couldn\'t unlock',
                        'error'
                    );
                }
            }, 'json');
        }
    });
});
