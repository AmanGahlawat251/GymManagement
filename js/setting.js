$(document).ready(function () {
  dataTableLoad($("#page").val());
  $(".leaves_search_collapse").click();
  $("#dynamic_table").tablesorter();
});

// Start add request 
$(document).on('submit', '#frm_new_request', function (e) {
  e.preventDefault();
  common_ajax_request('frm_new_request', 'modal_new_request', 'Y');
});
// End add request 

$(document).on('click', '#addSubjectButton', function () {

  // Get the data from the form
  var subjectName = $("#subject-name").val();
  var action = $("#subject-tab").val();

  var form_data = {
    'action': action,
    'subjectName': subjectName
  };
  start_loader();
  $.ajax({
    url: "includes/common_ajax.php",
    data: form_data,
    cache: false,
    //contentType: false,
    //processData: false,
    method: 'POST',
    type: 'POST',
    dataType: 'json',
    error: err => {
      console.log(err)
      alert_toast("An error occurred while adding the subject.", 'error');
      end_loader();
    },
    success: function (resp) {

      //var obj = $.parseJSON(resp);
      var obj = resp;
      var msg_code = resp.msg_code;
      var msg = obj.msg;

      if (msg_code == '00') {
        Swal.fire({
          type: 'success',
          title: 'Success',
          text: msg,
          timer: 2000
        });
        $("#addSubjectModal").modal("hide");
        setTimeout(function () {
          window.location.reload();
        }, 2000);

      } else {

        Swal.fire({
          type: 'warning',
          title: 'Warning',
          text: msg,
          timer: 4000
        });
      }
    }
  })
});

$(document).on('click', '.subject-edit-form', function () {
  $("#preloader").show();
  $(".modal_title").text("Edit Subject");
  var element = $(this).data();
  
  $.each(element, function (index, data) {
    $('#subject_' + index).val(data);
  });
  $("#preloader").hide();
  $(".select2-width-100").trigger('change');
  $("#modal_new_request").modal('show');
});


// Start delet subject form generate
$(document).on('click', '.subject-delete-form', function () {
  if (confirm("Are you sure to delete this subject?") == true) {
    var action = $(this).data('action');
    var subject_id = $(this).data('subject_id');
    var form_data = { 'action': action, 'del_subject_id': subject_id };
    start_loader();
    $.ajax({
      url: "includes/common_ajax.php",
      data: form_data,
      cache: false,
      //contentType: false,
      //processData: false,
      method: 'POST',
      type: 'POST',
      dataType: 'json',
      error: err => {
        console.log(err)
        alert_toast("An error occurred", 'error');
        end_loader();
      },
      success: function (resp) {

        //var obj = $.parseJSON(resp);
        var obj = resp;
        var msg_code = resp.msg_code;
        var msg = obj.msg;

        Swal.fire({
          type: 'success',
          title: 'Success',
          text: msg,
          timer: 2000
        });

        setTimeout(function () {
          window.location.reload();
        }, 2000);
      }
    })

  }
});

