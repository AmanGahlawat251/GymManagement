$(document).on('keyup blur', '.allowOnlyNumeric', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^0-9]/g, ''));
});
$('.allowOnlyNumeric').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^0-9]/g, ''));
});
$(document).on('keyup blur', '.allowOnlyAlphabets', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z ]/g, ''));
});
$('.allowOnlyAlphabets').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z ]/g, ''));
});
$(document).on('keyup blur', '.allowAlphaNumeric', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z0-9]/g, ''));
});
$(document).on('keyup blur', '.nospace', function (event) {
    var node = $(this);
    node.val(node.val().replace(/ /g, ''));
});
$('.allowAlphaNumeric').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z0-9]/g, ''));
});
$(document).on('keyup blur', '.allowOnlyNumericSpace', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^0-9 ]/g, ''));
});
$('.allowOnlyNumericSpace').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^0-9 ]/g, ''));
});
$(document).on('keyup blur', '.allowAlphaNumericSpace', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z0-9 ]/g, ''));
});
$('.allowAlphaNumericSpace').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^a-zA-Z0-9 ]/g, ''));
});
$(document).on('keyup blur', '.allowNumericFloat', function (event) {
    var node = $(this);
    node.val(node.val().replace(/[^0-9\.]/g, ''));
});
$('.allowNumericFloat').bind('input propertychange', function () {
    var node = $(this);
    node.val(node.val().replace(/[^0-9\.]/g, ''));
});
$(document).on('keyup blur', '.allowNumericAmount', function (event) {
    var node = $(this);
    //  node.val(node.val().replace(/[^0-9\s.]+|\.(?!\d)/g, ''));
    node.val(node.val().replace(/[^0-9\.]/g, ''));
});
$(document).on('blur', '.validateFloat', function (event) {
    var value = $(this).val();
    var valid = (value.match(/^-?\d*(\.\d+)?$/))
    if (!valid) {
        $(this).val(0);
    }
});
$(document).on('keyup blur', '.makeAlphabetsCapital', function (event) {
    this.value = this.value.toUpperCase();
});
$(document).on('keyup blur', '.removeChars', function (event) {
    var node = $(this);
    var stringToGoIntoTheRegex = $(this).data('regex');
    var regex = new RegExp(stringToGoIntoTheRegex, "g");
    node.val(node.val().replace(regex, ''));
});

$(document).on('keyup blur', '.removeChars_enter', function (event) {
    if (Event.keyCode != 13) {
        var node = $(this);
        var stringToGoIntoTheRegex = $(this).data('regex');
        var regex = new RegExp(stringToGoIntoTheRegex, "g");
        node.val(node.val().replace(regex, ''));
    }
});

$('.removeChars').bind('input propertychange', function () {
    var node = $(this);
    var stringToGoIntoTheRegex = $(this).data('regex');
    var regex = new RegExp(stringToGoIntoTheRegex, "g");
    node.val(node.val().replace(regex, ''));
});

$(document).on('blur', '.validateEmail', function (event) {
    var userinput = $(this).val();
    if (userinput != '') {
        var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
        if (!pattern.test(userinput)) {
            alert('Enter a valid e-mail address');
            $(this).val("");
            $(this).focus();
        }
    }
});

$(document).on('focusout', 'input', function () {

    var message = $(this).val();
    if (/<(br|basefont|hr|input|source|frame|param|area|meta|!--|col|link|option|base|img|wbr|!DOCTYPE|a|abbr|acronym|address|applet|article|aside|audio|b|bdi|bdo|big|blockquote|body|button|canvas|caption|center|cite|code|colgroup|command|datalist|dd|del|details|dfn|dialog|dir|div|dl|dt|em|embed|fieldset|figcaption|figure|font|footer|form|frameset|head|header|hgroup|h1|h2|h3|h4|h5|h6|html|i|iframe|ins|kbd|keygen|label|legend|li|map|mark|menu|meter|nav|noframes|noscript|object|ol|optgroup|output|p|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|span|strike|strong|style|sub|summary|sup|table|tbody|td|textarea|tfoot|th|thead|time|title|tr|track|tt|u|ul|var|video).*?>|<(video).*?<\/\2>/i.test(message) == true) {
        alert('HTML Tag are not allowed');
        $(this).val("");
        e.preventDefault();
    }
});

$(document).on('keyup blur', '.removeChars', function (event) {
    var node = $(this);
    var stringToGoIntoTheRegex = $(this).data('regex');
    var regex = new RegExp(stringToGoIntoTheRegex, "g");
    node.val(node.val().replace(regex, ''));
});



function randomPassword() {
    var length = 12;
    var chars = "abcdefghjkmnpqrstuvwxyz!@#%*()-+ABCDEFGHJKMNP123456789";
    //var chars = "1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}

function generate() {
    //$('.password').removeAttr('disabled');
    $('.password').val(randomPassword());
    var pass = $(".password")[0];
    $('.password').select();
    document.execCommand("copy");
    $('#clipboard').fadeIn(2000);
    $('#clipboard').fadeOut(2000);
}

function show_more(hide_id, show_id, btn_show_more) {
    $("#" + hide_id).toggle();
    $("#" + show_id).toggle();
    var txt = $("#" + btn_show_more).text();
    if (txt == 'Show More') {
        $("#" + btn_show_more).text('Show Less');
    }
    else {
        $("#" + btn_show_more).text('Show More');
    }
}

function showpassword() {
    if ($("input[name=password]").attr("type") === "password") {
        $("input[name=password]").attr("type", "text");
    }
    else {
        $("input[name=password]").attr("type", "password");
    }
}

function copyToclipboard(txt_id) {
    $('#' + txt_id).select();
    document.execCommand("copy");
    $('#clipboard').fadeIn(500);
    $('#clipboard').fadeOut(2000);
    $('#' + txt_id).hide();
}

function fileUploadFileNames(inputId, outputId) {
    var input = document.getElementById(inputId);
    var output = document.getElementById(outputId);
    var children = "";
    for (var i = 0; i < input.files.length; ++i) {
        if (i == 0) {
            children += input.files.item(i).name;
        }
        else {
            children += ', ' + input.files.item(i).name;
        }
    }
    output.innerHTML = "<b>" + children + " (" + input.files.length + " files are selected)</b>";
}

function countTextAreaChar(txtarea, maxLimit, label) {
    var len = $(txtarea).val().length;
    if (len > maxLimit) $(txtarea).val($(txtarea).val().slice(0, maxLimit));
    else $('#' + label).text((len) + "/" + maxLimit);
}

function fileUploadFileNames(inputId, outputId) {
    var input = document.getElementById(inputId);
    var output = document.getElementById(outputId);
    var children = "";
    for (var i = 0; i < input.files.length; ++i) {
        if (i == 0) {
            children += input.files.item(i).name;
        }
        else {
            children += ', ' + input.files.item(i).name;
        }
    }
    output.innerHTML = "<b>" + children + " (" + input.files.length + " files are selected)</b>";
}

function countTextAreaChar(txtarea, maxLimit, label) {
    var len = $(txtarea).val().length;
    if (len > maxLimit) $(txtarea).val($(txtarea).val().slice(0, maxLimit));
    else $('#' + label).text((len) + "/" + maxLimit);
}

/**
 * Sends an AJAX request to the server and handles the response.
 *
 * @param {string} from_id - the ID of the form to be submitted
 * @param {string} modal_id - the ID of the modal window to be hidden (optional)
 * @param {string} reload_table - determines how the table should be reloaded after the request (optional)
 * @param {string} div_hide - the ID of the element to be hidden (optional)
 * @param {string} div_show - the ID of the element to be shown (optional)
 * @param {string} div_append - the ID of the element to append the response to (optional)
 */
function ajax_request_withoutform(post_json_data, request_url, request_method, return_type) {
    /*var post_json_data = { tab : 'get_client_details', contact_num : 'contact_num' } */
    return new Promise((resolve, reject) => {
        $.ajax({
            type: request_method,
            url: request_url,
            data: post_json_data,
            beforeSend: function () {
                $("#preloader").show();
            },
            success: function (data) {
                $("#preloader").hide();
                if (return_type == "JSON") {
                    resolve(data);
                }
                else {
                    var obj = $.parseJSON(data);
                    console.log(obj.msg_code);
                    resolve(obj);
                }
            },
            error: function (jqXHR, exception) {
                $("#preloader").hide();
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                var response = '{"msg_code":"500", "msg":msg, "data":null}';
                if (return_type == "JSON") {
                    reject(response);
                }
                else {
                    var obj = $.parseJSON(response);
                    reject(obj);
                }
            }
        });

    });
}

function common_ajax_request(from_id, modal_id, reload_table, div_hide = '', div_show = '', div_append = "") {
    var frm = new FormData($('#' + from_id)[0]);
    var request_url = "includes/common_ajax.php";

    console.log(frm);

    $.ajax
        ({
            type: "POST",
            url: request_url,
            data: frm,
            processData: false,
            contentType: false,
            beforeSend: function () {
                if (reload_table != 'NOP') {
                    $("#preloader").show();
                }
            },
            success: function (data) {
                $("#preloader").hide();
                //console.log( data );

                console.log(data);
                try {
                    var obj = $.parseJSON(data);
                    var msg_code = obj.msg_code;
                    var msg = obj.msg;
                    //console.log( obj );
                    if (msg_code != '00') {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: msg
                        })

                        if (msg_code == '007') {
                            window.location.reload();
                        }
                    }
                    else {

                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: msg,
                            timer: 2000
                        })

                        if (reload_table == 'N') {
                            //console.log( 'redirect' );
                            if (modal_id != '') {
                                $('#' + modal_id).modal('hide');
                            }
                            setTimeout(function () {
                                if (obj.hasOwnProperty('redirect')) {
                                    window.location.href = obj.redirect;
                                }
                            }, 1000);
                        }
                        else if (reload_table == 'Y') {
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        }
                        else if (reload_table == 'DF') {
                            $('#' + from_id).remove()
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        }
                        else if (reload_table == 'NW') {
                            if (obj.hasOwnProperty('redirect')) {

                                var win = window.open(obj.redirect, '_blank');
                                if (win) {
                                    win.focus();
                                } else {
                                    alert('Please allow popups for this website');
                                }
                                window.location.href = obj.redirect1;
                            }

                        }

                    }
                }
                catch (error) {
                    console.log('error');
                    Swal.fire({
                        type: 'error',
                        title: '!!Error!!',
                        text: 'Invalid response received from server'
                    })
                }
            },
            error: function (jqXHR, exception) {
                $("#preloader").hide();
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                if (from_id == frm_po_entry) {
                    window.location.reload();
                }

                Swal.fire({
                    type: 'error',
                    title: '!!Error!!',
                    text: msg
                })


            }
        });
}

function dyna_ddl_ajaxload(sourse_ddl_option_field, sourse_ddl_option_id, destination_ddl_value, destination_ddl_label, destination_ddl_id, tbl, selected = "") {
    if (sourse_ddl_option_id == "") {
        return false;
    }
    var request_url = "includes/common_ajax.php";
    var action = $("#dyna_ddl_ajaxload").val();
    $.ajax
        ({
            type: "POST",
            url: request_url,
            data: "sourse_ddl_option_field=" + sourse_ddl_option_field + "&sourse_ddl_option_id=" + sourse_ddl_option_id + "&destination_ddl_value=" + destination_ddl_value + "&destination_ddl_label=" + destination_ddl_label + "&destination_ddl_id=" + destination_ddl_id + "&action=" + action + "&tbl=" + tbl + "&selected=" + selected,
            success: function (data) {

                var obj = $.parseJSON(data);
                var msg_code = obj.msg_code;
                var msg = obj.msg;
                var data = obj.data;

                $("#" + destination_ddl_id).find('option').remove().end().append(data).trigger('change');
                $("#" + destination_ddl_id).prop('required', true);

                if (msg_code != '00') {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: msg
                    });
                    return false;
                } else {
                    //$('#select_id').empty();
                    //$("#" + destination_ddl_id).find('option').remove().end().append(data).trigger('change');
                    //$("#" + destination_ddl_id).prop('required', true);
                }
            }
        });

}

function crop_image(file_input_id, crop_modal_id, uploaded_image_id, crop_button_id, cropped_image_id, txt_base64) {
    //<script src="js/croppie.min.js"></script>
    // Remember to add "croppie" library before call the function     
    $('#' + file_input_id).on('change', function () {
        var reader = new FileReader();
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#' + crop_modal_id).modal('show');
    });

    $image_crop = $('#' + uploaded_image_id).croppie({
        enableExif: true,
        viewport: {
            width: 150, // new width
            height: 150, // new width
            type: 'square' //circle
        },
        boundary: {
            width: 400,
            height: 400
        }
    });

    $('#' + crop_button_id).click(function (event) {
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (response) {
            $('#' + crop_modal_id).modal('hide');
            $('#' + cropped_image_id).attr("src", response);
            $('#' + cropped_image_id).show();
            $('#' + txt_base64).val(response);
        });
    });
}

function loadDataTable(tableID, totalRecords, orderByClumnIndex, orderType, columnObject) {
    var search_form_data = $('#search_form').serializeArray();
    var FormObject = {};
    $.each(search_form_data,
        function (i, v) {
            FormObject[v.name] = v.value;
        });
    var table = $('#' + tableID).dataTable({
        "processing": true,
        'serverSide': true,
        "dom": "Blfrtip",
        "autoWidth": true,
        'serverMethod': 'post',
        "ajax": {
            'type': 'POST',
            'url': 'includes/dataTable.php',
            'data': {
                request: "list_users",
                searchData: FormObject
            },
        },
        // "aoColumnDefs": [
        //     { "bSortable": false, "aTargets": [ 1,2,3,4,5,6,7,8,9 ] }, 
        // ],
        "ordering": false,
        buttons: [
            'colvis', 'csv', 'excel',
            {
                text: 'Refresh',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            }

        ],
        language: {
            paginate: {
                next: '<i class="fa-solid fa-angle-right"></i>',
                previous: '<i class="fa-solid fa-angle-left"></i>'
            }
        },
        "pageLength": totalRecords,
        "aaSorting": [[orderByClumnIndex, orderType]],
        "columns": columnObject
    });
    return table;
}

function waitforme(millisec) {
    return new Promise(resolve => {
        setTimeout(() => { resolve('') }, millisec);
    })
}

//#########################################################DataTable#########################################
function dataTableLoad(page_no) {
    $("#preloader").show();
    var search_form_data = $('#search_form').serialize();
    var request_file = $('#url').val();
    $.ajax
        ({
            type: "POST",
            url: 'includes/dataTable.php',
            data: search_form_data + "&page=" + page_no,
            beforeSend: function () {
                $("#preloader").show()
            },
            success: function (msg) {
                $("#preloader").hide()
                $("#table_body").html(msg);
                sortTbl();
                var total_records = $('#total_records').val();
                if (total_records > 0) {
                    $('#lbl_total').html('(<b>' + total_records + '</b>)');
                }
                else {
                    $('#lbl_total').html('(<b>0</b>)');
                }
                /*
                 var attrSort = $("#dynamic_table").attr('data-sort');
                 console.log(attrSort);
                 if (typeof attrSort !== 'undefined' && attrSort !== false)
                 {
                     $("#dynamic_table").tablesorter(); //https://mottie.github.io/tablesorter/docs/#Demo
                 }
                */

                $('#btn_download').click(function () {
                    download_csv();
                });

                var limitt = $('#record_limit').val();
                $('#record_limit_change').val(limitt);

                $('#record_limit_change').change(function () {
                    //var page = $(".pagination").find(".active").attr('p');
                    $('#record_limit').val($(this).val());
                    dataTableLoad(1);
                });

                $("#live_search").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#table_body tbody tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        if ($('#tbody tr:visible').length < 10) {
                            $('.pagination').css("display", "none");
                        }
                        else {
                            $('.pagination').css("display", "");
                        }
                    });
                });

                $("#checkAll").click(function () {
                    $('input:checkbox').not(this).prop('checked', this.checked);
                });

            },
            error: function (jqXHR, exception) {
                $("#preloader").hide();
            }
        });
}

function download_csv() {
    Swal.fire({
        title: "Do you want to download the data in excel format?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        showLoaderOnConfirm: true,
        preConfirm: (value) => {
            if (!value) {
                //return false;
            } else {
                download_csv2();
            }
            // You can process the user's input here or perform any other action.
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.isConfirmed) {
            download_csv2();
        }
    });
}
function download_csv2() {

    $('<input>').attr({
        type: 'hidden',
        id: 'export',
        value: 'export',
        name: 'export'
    }).appendTo('#search_form');

    //$('#search').click();

    $('#downloadCsvID').val(1);

    const saveData = (function () {
        const a = document.createElement("a");
        document.body.appendChild(a);
        a.style = "display: none";
        return function (data, fileName) {
            const blob = new Blob([data], { type: "octet/stream" }),
                url = window.URL.createObjectURL(blob);
            a.href = url;
            a.download = fileName;
            a.click();
            window.URL.revokeObjectURL(url);
        };
    }());

    var search_form_data = $('#search_form').serialize();
    var request_file = $('#url').val();

    var csv_file_name = $('#csv_file_name').val();

    console.log(search_form_data);
    $.ajax
        ({
            type: "POST",
            url: 'includes/dataTable.php',
            data: search_form_data,
            beforeSend: function () {
                $("#preloader").show()
            },
            success: function (data) {

                const d = new Date();
                let time = d.getTime();
                var fileName = time +"_"+csv_file_name + ".csv";
                saveData(data, fileName);
                $("#preloader").hide();
                $('#export').remove();
            },
            error: function (jqXHR, exception) {
                $("#preloader").hide();
            }
        });
}

function sortTbl(tbl = "") {
    if (tbl == "") {
        var table = $('#dynamic_table');
    }
    else {
        var table = $('#dynamic_table123');
    }
    $('.sort').append('<i style="margin-left:3px;" class="fa fa-sort"></i>').each(function () {
        var th = $(this),
            thIndex = th.index(),
            inverse = false;

        th.click(function () {

            table.find('td').filter(function () {

                return $(this).index() === thIndex;

            }).sortElements(function (a, b) {

                return $.text([a]) > $.text([b]) ?
                    inverse ? -1 : 1
                    : inverse ? 1 : -1;

            }, function () {

                // parentNode is the element we want to move
                return this.parentNode;

            });

            inverse = !inverse;

        });
    });
}

function exportToCsv() {
    var download = $('#btn_download').data('url');
    $('#download').val(download);
    var search_form_data = $('#search_form').serialize();
    var request_file = $('#url').val();
    $.ajax
        ({
            type: "POST",
            url: request_file, //'table_response.php',
            data: search_form_data,
            beforeSend: function () {
                $("#preloader").show();
            },
            timeout: 3000000,
            success: function (msg) {
                //alert(msg);
                $('#download').val('');
                window.location.href = trim(msg);
                $("#preloader").hide();
            },
            error: function (jqXHR, exception) {
                $("#preloader").hide();
            }
        });
}

function goto1(page) {
    document.frm.page.value = page;
    document.frm.submit();
}

function gosort(orderon, orderby) {
    document.frm.orderby.value = orderby;
    document.frm.orderon.value = orderon;
    document.frm.submit();
}

$(document).on("click", "#table_body li.page-item.active", function () {
    console.log('---------------------------');
    var page = $(this).attr('p');
    dataTableLoad(page);
});

$(document).on("click", "#search", function () {
    dataTableLoad(1);
});

$(document).on("click", ".btn-modal-close", function () {
    var page = $(".pagination").find(".active").attr('p');
    dataTableLoad(page);
});

//#########################################################DataTable#########################################

function start_loader() {
    $('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div>')
}
function end_loader() {
    $('#preloader').fadeOut('fast', function () {
        $('#preloader').remove();
    })
}

// function 
window.alert_toast = function ($msg = 'TEST', $bg = 'success', $pos = '') {
    var Toast = Swal.mixin({
        toast: true,
        position: $pos || 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    Toast.fire({
        icon: $bg,
        title: $msg
    })
}


$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});


$(document).ready(function () {
    $(document).on("click", ".sortable", function () {

        var column = $(this).data("sort");
        var $table = $(this).closest("table");
        var $tbody = $table.find("tbody");

        // Toggle sorting order
        var order = $(this).hasClass("asc") ? "desc" : "asc";
        
        // Remove sorting classes from all columns
        $table.find("th").removeClass("asc desc");

        // Add sorting class to the clicked column
        $(this).addClass(order);

        // Perform sorting based on the selected column and order
        // var rows = $tbody.find("tr").toArray();
        // rows.sort(function (a, b) {
        //     var aValue = $(a).find("td." + column).text();
        //     var bValue = $(b).find("td." + column).text();
        //     return order === "asc" ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        // });

        // // Append sorted rows to the tbody
        // $tbody.empty().append(rows);

        $('#sort_by').val(column);
        $('#sort_order').val(order);
        $('#search').click();
    });

    
    
});

