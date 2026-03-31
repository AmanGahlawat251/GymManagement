$(document).ready(function () {
  $("#role_id").select2({ "dropdownParent": $("#canvas_user") });
  $("#user_designation_id").select2({ "dropdownParent": $("#canvas_user") });
  $("#search_user_designation_id").select2();
  $("#user_team_id").select2({ "dropdownParent": $("#canvas_user") });
  $("#search_user_team_id").select2();
  // $("#user_reporting_manager").select2({ "dropdownParent" : $("#canvas_user") });
  // $("#user_reporting_project_manager").select2({ "dropdownParent" : $("#canvas_user") });
  $("#user_type").select2({ "dropdownParent": $("#canvas_user") });
  var columnObject = [
    { data: "SN" },
    { data: "action" },
    { data: "user_name" },
    { data: "user_email" },
    { data: "password" },
    { data: "user_contact" },
    { data: "user_team" },
    { data: "user_designation" },
    { data: "active" },
    { data: "created_on" }
  ]
  crop_image("input_upload_image", "uploadimageModal", "uploaded_image", "crop_image_button", "croped_image", "profile_pic");
  var tbl = loadDataTable("tbl_users", "10", "9", "desc", columnObject);
  //console.log(tbl);
  //const promise = new Promise(() => {})  
});

$(document).on('hidden.bs.offcanvas', '#canvas_user', function () {
  window.location.reload();
  //tbl.ajax.reload();
});

$(document).on('submit', '#search_form', function (e) {

  $("#tbl_users").DataTable().clear().draw();
  $("#tbl_users").DataTable().destroy();

  var columnObject = [
    { data: "SN" },
    { data: "action" },
    { data: "user_name" },
    { data: "user_email" },
    { data: "password" },
    { data: "user_contact" },
    { data: "user_team" },
    { data: "user_designation" },
    { data: "active" },
    { data: "created_on" }
  ]
  var tbl = loadDataTable("tbl_users", "9", "8", "desc", columnObject);
});

// Start add/edit user 
$(document).on('submit', '#frm_user', function (e) {
  e.preventDefault();
  common_ajax_request('frm_user', 'canvas_user', 'Y');
});
// End add/edit user 

// Start edit user offcanvas open 
$(document).on('click', '.user-edit-form', function () {
  $("#preloader").show();
  $("#canvas_user_title").text("Edit User");
  var element = $(this).data();
  var user_id = element.id;
  $.each(element, function (index, data) {
    $('#' + index).val(data);
  });

  var load_data = function () {
    var deferred = new $.Deferred();

    deferred.resolve();
    return deferred.promise();
  };
  load_data().promise().done(function () {
    $(".select2-width-100").trigger('change');

    setTimeout(function () {
      $('#user_reporting_manager').val(element.user_reporting_manager).trigger('change');
      $("#preloader").hide();
      $('#canvas_user').offcanvas('show');
    }, 500);
  });
});
// End edit user  offcanvas open

// Start delete user form generate
$(document).on('click', '.user-delete-form', function () {
  if (confirm("Are you sure to delete this user?") == true) {
    var action = $(this).data('action');
    var user_id = $(this).data('user_id');
    var $form = $('<form />', { onsubmit: 'return false', method: 'POST', id: 'frm_delete_user' }),
      frmEmpId = $('<input />', { id: 'del_user_id', name: 'del_user_id', type: 'text', value: user_id }),
      frmAction = $('<input />', { id: 'action', name: 'action', type: 'text', value: action }),
      frmSave = $('<input />', { id: 'savebutton', type: 'submit', value: 'Delete' })
    $form.append(frmEmpId, frmAction, frmSave).appendTo($('#canvas_user'));
    $('#frm_delete_user').submit();
  }
});

// end delete user form generate

// Start delete user 
$(document).on('submit', '#frm_delete_user', function (e) {
  e.preventDefault();
  common_ajax_request('frm_delete_user', '', 'DF');
});
// End delete user 