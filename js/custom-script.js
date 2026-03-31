$( document ).ready( function () {

	setTimeout(function(){ $('[data-toggle="tooltip"]').tooltip({
		container: 'body'
	}); }, 3000);
	
	
	$('.modal_close').on('hidden.bs.modal', function (e) {
		console.log(this.id);
		if(this.id != 'modal-add-person')
		{
			window.location.reload();
		}
	})

	
	
	$( '#search_by' ).change( function () {

		var search_by = $( '#search_by' ).val();
		if ( search_by == '' ) {
			$( '#search_value' ).removeAttr( 'required' );
		}
		else {
			$( "#search_value" ).prop( 'required', true );
		}
	} );
	
	//$('#search_date').parents().eq(2).hide();
	$('#search_date').attr("autocomplete","off");
	
	
	

	
} );


$(document).on('click', '.user-edit-form', function () {
  $("#preloader").show();
 
  $("#canvas_user_title").text("Edit User");
  var element = $(this).data();
  var user_id = element.id;
  $.each(element, function (index, data) {
	  console.log(index, data);
    $('#' + index).val(data);
  });

	// Membership Types (new flow)
	setTimeout(function () {
		$('#membership_type_id').val(element.membership_type_id).trigger('change');
		$('#gender').val(element.gender).trigger('change');
		getPlans();
		$('#plans').val(element.plan_id).trigger('change');
		// Paid/pending quick-fill (if columns exist in table_response data)
		if (element.paid_amount !== undefined) $('#paid_amount_member').val(element.paid_amount);
		if (element.total_amount !== undefined) {
			$('#total_amount').val(element.total_amount);
			// New field introduced: Final Amount (deal value)
			$('#final_amount_member').val(element.total_amount);
		}
		if (element.pending_amount !== undefined) {
			$('#pending_amount').val(element.pending_amount);
		} else if (element.total_amount !== undefined && element.paid_amount !== undefined) {
			var _t = parseFloat(element.total_amount || 0);
			var _p = parseFloat(element.paid_amount || 0);
			var _pending = _t - _p;
			if (_pending < 0) _pending = 0;
			$('#pending_amount').val(_pending.toFixed(2));
		}
		$('#picture').prop('required', true);
		$('#id').prop('required', true);
		$("#preloader").hide();
		$('#final_btns').show();
		$('#canvas_user').offcanvas('show');
	}, 500);
});

// Start login 
$( document ).on( 'submit', '#frm', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm', '', 'N' );
	//grecaptcha.reset();
} );
// End login 
// Start login 
// Declaration upload removed for gym version.
// End login 
// Start login 
$( document ).on( 'submit', '#frm_user', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_user', 'canvas_user', 'R' );
	//grecaptcha.reset();
} );
// End login 
// Start login 
$( document ).on( 'submit', '#freeze_member', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'freeze_member', 'freeze_popup', 'N' );
	//grecaptcha.reset();
} );
// End login 

// Start forget password  
$( document ).on( 'submit', '#frm_forgot_password', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_forgot_password', 'modal-forget', 'N' );
} );
// End forget password
// Start forget password  
$( document ).on( 'submit', '#renew_member', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'renew_member', 'renew_popup', 'N' );
} );
// End forget password

// Member profile (offcanvas)
function view_member_profile(memberRowId) {
	$('#member_profile_body').html('<div class="text-center text-muted">Loading...</div>');
	$.ajax({
		type: "POST",
		url: window.AJAX_URL,
		data: { tab: 'get_member_profile', member_row_id: memberRowId },
		success: function (html) {
			$('#member_profile_body').html(html);
			$('#canvas_member_profile').offcanvas('show');
		},
		error: function () {
			$('#member_profile_body').html('<div class="alert alert-danger">Unable to load profile.</div>');
		}
	});
}

function open_update_payment(memberRowId) {
	$('#member_row_id').val(memberRowId);
	$('#pay_amount').val('');
	$('#modal_update_payment').modal('show');
}

$(document).on('submit', '#frm_update_payment', function (e) {
	e.preventDefault();
	send_ajax_request('frm_update_payment', 'modal_update_payment', 'NOP');
	// refresh profile after a moment if open
	setTimeout(function () {
		var id = $('#member_row_id').val();
		if (id) {
			view_member_profile(id);
		}
	}, 800);
});

// Start Contact 
$( document ).on( 'submit', '#frm_plan', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_plan', 'modal_add_plan', 'N' );
	$("#frm_plan").trigger("reset");
} );
// End Contact 
// Start Contact 
$( document ).on( 'submit', '#frm_add_educonfig', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_add_educonfig', 'modal-add-educonfig', 'N' );
	$("#frm_add_educonfig").trigger("reset");
} );
// End Contact 
// Start Contact 
$( document ).on( 'submit', '#frm_add_data', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_add_data', 'modal-add-university', 'N' );
} );
// End Contact 

// Start Contact 
$( document ).on( 'submit', '#frm_add_csv', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_add_csv', 'modal-upload-csv', 'N' );
} );
// End Contact 

 
 

// Add person  Start
$( document ).on( 'submit', '#frm_add_person', function ( e ) {
	e.preventDefault();
	var tab = $( '#tab' ).val();
	send_ajax_request( 'frm_add_person', 'modal-add-person', 'Y' );
} );
// Add person End

// Add person  Start
$( document ).on( 'submit', '#frm_add_person_order', function ( e ) {
	e.preventDefault();
	var tab = $( '#tab' ).val();
	send_ajax_request( 'frm_add_person_order', 'modal-add-person', 'N' );
} );
// Add person End

// Chnage client status Start
$( document ).on( 'submit', '#frm_active_person', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_active_person', 'modal-active-person', 'Y' )
} );
// Chnage client status End

// Delete client Start
$( document ).on( 'submit', '#frm_remove_data', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_remove_data', 'modal-remove-data', 'Y' )
} );
// Delete client End

//  Add Attendance
$( document ).on( 'submit', '#frm_attendance', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_attendance', '', 'R');
} );
// Add  Attendance

//  Add notes
$( document ).on( 'submit', '#frm_add_notes', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_add_notes', '', '');	
	var note_source = $("#source").val();
	var source_id = $("#note_source_id").val();
	load_notes(note_source,source_id,"notes_list");	
	$("#frm_add_notes").trigger("reset");
	$("#note_details").focus().keyup();
} );
// Add  notes


// Change Password Start

$( document ).on( 'submit', '#frm_change_password', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_change_password', 'modal-change-password', 'N' )
} );



//  Upload docs Start
$( document ).on( 'submit', '#frm_upload_doc', function ( e ) {
	e.preventDefault();
	send_ajax_request( 'frm_upload_doc', 'modal-upload-doc', 'N');
} );
// Upload docs End

$( document ).on( 'keyup blur', '.allowOnlyNumeric', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^0-9]/g, '' ) );
} );
$( '.allowOnlyNumeric' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^0-9]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowOnlyAlphabets', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z ]/g, '' ) );
} );
$( '.allowOnlyAlphabets' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z ]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowAlphaNumeric', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z0-9]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.nospace', function ( event ) {
	var node = $( this );
	node.val( node.val().replace(/ /g,'') );
} );
$( '.allowAlphaNumeric' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z0-9]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowOnlyNumericSpace', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^0-9 ]/g, '' ) );
} );
$( '.allowOnlyNumericSpace' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^0-9 ]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowAlphaNumericSpace', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z0-9 ]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowAlphaNumericSpacenew', function ( event ) {
    var node = $( this );
    node.val( node.val().replace( /[^a-zA-Z0-9]/g, '' ) ); // Remove space from the regex
} );
$( '.allowAlphaNumericSpace' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^a-zA-Z0-9 ]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowNumericFloat', function ( event ) {
	var node = $( this );
	node.val( node.val().replace( /[^0-9\.]/g, '' ) );
} );
$( '.allowNumericFloat' ).bind( 'input propertychange', function () {
	var node = $( this );
	node.val( node.val().replace( /[^0-9\.]/g, '' ) );
} );
$( document ).on( 'keyup blur', '.allowNumericAmount', function ( event ) {
	var node = $( this );
	//  node.val(node.val().replace(/[^0-9\s.]+|\.(?!\d)/g, ''));
	node.val( node.val().replace( /[^0-9\.]/g, '' ) );
} );
$( document ).on( 'blur', '.validateFloat', function ( event ) {
	var value = $( this ).val();
	var valid = ( value.match( /^-?\d*(\.\d+)?$/ ) )
	if ( !valid ) {
		$( this ).val( 0 );
	}
} );
$( document ).on( 'keyup blur', '.makeAlphabetsCapital', function ( event ) {
	this.value = this.value.toUpperCase();
} );
$( document ).on( 'keyup blur', '.removeChars', function ( event ) {
	var node = $( this );
	var stringToGoIntoTheRegex = $( this ).data( 'regex' );
	var regex = new RegExp( stringToGoIntoTheRegex, "g" );
	node.val( node.val().replace( regex, '' ) );
} );


$( document ).on( 'keyup blur', '.removeChars_enter', function ( event ) {
	if (Event.keyCode != 13)
	{
		var node = $( this );
		var stringToGoIntoTheRegex = $( this ).data( 'regex' );
		var regex = new RegExp( stringToGoIntoTheRegex, "g" );
		node.val( node.val().replace( regex, '' ) );
	}
} );

$( '.removeChars' ).bind( 'input propertychange', function () {
	var node = $( this );
	var stringToGoIntoTheRegex = $( this ).data( 'regex' );
	var regex = new RegExp( stringToGoIntoTheRegex, "g" );
	node.val( node.val().replace( regex, '' ) );
} );

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
	
	 

	$(document).on('blur', '#validity1', function () {
		var x = parseInt($("#validity1").val());
		//alert(x);
		var s = new Date().addHours(x).toString();
		//alert(s);
		var ssd = s.split("GMT");
		var d = new Date(ssd[0]);
		var date12 = d.toLocaleDateString();
		date12 = date12.split("/");
		//alert(date12);
		if(date12[0] < 10)
		{
			date12[0] = "0"+date12[0];
		}
		if(date12[1] < 10)
		{
			date12[1] = "0"+date12[1];
		}
		date12 = date12[2]+"-"+date12[0]+"-"+date12[1];
		$("#exp").text(date12);
        $("#validity2").val(date12);
    });
	
	


$( document ).on( 'focusout', 'input', function () {

	var message = $(this).val();
	if(/<(br|basefont|hr|input|source|frame|param|area|meta|!--|col|link|option|base|img|wbr|!DOCTYPE|a|abbr|acronym|address|applet|article|aside|audio|b|bdi|bdo|big|blockquote|body|button|canvas|caption|center|cite|code|colgroup|command|datalist|dd|del|details|dfn|dialog|dir|div|dl|dt|em|embed|fieldset|figcaption|figure|font|footer|form|frameset|head|header|hgroup|h1|h2|h3|h4|h5|h6|html|i|iframe|ins|kbd|keygen|label|legend|li|map|mark|menu|meter|nav|noframes|noscript|object|ol|optgroup|output|p|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|span|strike|strong|style|sub|summary|sup|table|tbody|td|textarea|tfoot|th|thead|time|title|tr|track|tt|u|ul|var|video).*?>|<(video).*?<\/\2>/i.test(message) == true) {
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
	for ( var x = 0; x < length; x++ ) {
		var i = Math.floor( Math.random() * chars.length );
		pass += chars.charAt( i );
	}
	return pass;
}

function generate() {
	//$('.password').removeAttr('disabled');
	$('.password').val( randomPassword() );
	var pass = $(".password")[0];	
	$('.password').select();
	document.execCommand("copy");  
	$('#clipboard').fadeIn(2000);
	$('#clipboard').fadeOut(2000);
}

function check_active_login() {
	$.ajax( {
		url: 'check_active_login.php',
		data: '',
		success: function ( data ) {
		},
		type: 'POST'
	} );
	}


function send_ajax_request( from_id, modal_id, reload_table, div_hide = '', div_show = '', div_append = "" ) {
	var frm = new FormData( $( '#' + from_id )[ 0 ] );
	var request_url = $( "#" + from_id + " input[name=url]" ).val();
	$.ajax
		( {
			type: "POST",
			url: request_url,
			data: frm,
			processData: false,
			contentType: false,
			beforeSend: function () {
				if(reload_table != 'NOP')
				{
					$( "#preloader" ).show();
				}
			},
			success: function ( data ) {
				$( "#preloader" ).hide();
				//console.log( data );
				try {
					var obj = $.parseJSON( data );
					var msg_code = obj.msg_code;
					var msg = obj.msg;
					//console.log( obj );
					if ( msg_code != '00' ) {
						toastr.error(msg);
						
					}
					else {
						//console.log( 'swal' );
						toastr.success(msg);
						
						
						if ( reload_table == 'Y' ) {
							//console.log( 'load_table' );
							$("#"+from_id).trigger("reset");
							var page11 = $( ".pagination" ).find( ".active" ).attr( 'p' );
							loadTableRecords( page11 );
							if(modal_id != '')
							{
								$( '#' + modal_id ).modal( 'hide' );
							}
						}
						else if(reload_table == 'NOP')
						{
							var page11 = $( ".pagination" ).find( ".active" ).attr( 'p' );
							// If pagination isn't rendered yet, default to first page.
							if (page11 === undefined || page11 === null || page11 === '') {
								page11 = 1;
							}
							loadTableRecords( page11 );
						}
						else if(reload_table == 'M')
						{
							$( '#' + modal_id ).modal( 'hide' );
							$("#"+from_id).trigger("reset");
						}
						else if(reload_table == 'F')
						{
							$("#"+from_id).trigger("reset");
						}
						else if(reload_table == 'C')
						{
							
							//window.location.reload();
							setTimeout( function () {								 
									window.location.reload()
							}, 2000 );
						}else if(reload_table == 'R')
						{
							$( '#' + modal_id ).offcanvas('show');
							setTimeout( function () {								 
									window.location.reload()
							}, 2000 );
						}
						else if ( reload_table == 'N' ) {
							//console.log( 'redirect' );
							if(modal_id != '')
							{
								$( '#' + modal_id ).modal( 'hide' );
							}
							setTimeout( function () {
								if ( obj.hasOwnProperty( 'redirect' ) ) {
									window.location.href = obj.redirect;
								}
							}, 1000 );
							
							
							
						}
						
						else if ( reload_table == 'APPEND' ) {
							if ( obj.hasOwnProperty( 'data' ) ) {

								$( '#' + div_append ).append( obj.data );
								$( '#' + div_hide ).hide();
								$( '#' + div_show ).show();
							}
						}
						
					}
				}
				catch ( error ) {
					console.log( 'error' );
					toastr.error('Invalid response received from server');
				}
			},
			error: function ( jqXHR, exception ) {
				$( "#loading" ).hide();
				var msg = '';
				if ( jqXHR.status === 0 ) {
					msg = 'Not connect.\n Verify Network.';
				} else if ( jqXHR.status == 404 ) {
					msg = 'Requested page not found. [404]';
				} else if ( jqXHR.status == 500 ) {
					msg = 'Internal Server Error [500].';
				} else if ( exception === 'parsererror' ) {
					msg = 'Requested JSON parse failed.';
				} else if ( exception === 'timeout' ) {
					msg = 'Time out error.';
				} else if ( exception === 'abort' ) {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n' + jqXHR.responseText;
				}
				
				if (from_id == frm_po_entry)
				{
					window.location.reload();
				}
				
				toastr.error(msg);
			}
		} );
}

function showpassword() 
{
	if($("input[name=password]").attr("type") === "password") 
	{
		$("input[name=password]").attr("type","text");
	} 
	else
	{
		$("input[name=password]").attr("type","password");
	}
}

function copyToclipboard(txt_id)
{
	$('#'+txt_id).select();	 
	document.execCommand("copy");  	
	$('#clipboard').fadeIn(500);
	$('#clipboard').fadeOut(2000);
	$('#'+txt_id).hide();
}
function snackbar(msg) {
  $('#snackbar').text(msg);
  $('#snackbar').addClass('show');
  setTimeout(function(){ $('#snackbar').removeClass('show'); }, 3000);
}

function countTextAreaChar(txtarea, maxLimit,label){
    var len = $(txtarea).val().length;
    if (len > maxLimit) $(txtarea).val($(txtarea).val().slice(0, maxLimit));
    else $('#'+label).text((len) +"/"+maxLimit);
    }

/* function edit_plans(id) 
{
	var element = $('#'+id).data(); 
	var type = $('#'+id).data('type'); 
	$.each(element, function (index, data) {			
			if(index == 'lead_source_id')
			{
				$('#'+index).val(data).trigger('change');
			}
			else
			{				
				$('#'+index).val(data);	
			}
	});
	$('.modal-title').text("Edit Plans");	
	$('#lead_id').val(lead_id);	
	$('#add_lead').hide();
	$('#lead_details').keyup();
	$('#update_lead').show();
	$( '#modal-add-lead' ).modal( 'show' );
	
} */
function edit_plans(id)
	{
		$( "#preloader" ).show();
		var record_id = $('#'+id).data('id');		
		var plan_type = $('#'+id).data('plan_type');		
		var price = $('#'+id).data('price');		
		var title = $('#'+id).data('title');		
		var duration = $('#'+id).data('duration');		
		// Stored duration is in DAYS; dropdown expects MONTHS now.
		var daysToMonths = {
			30: 1,
			90: 3,
			180: 6,
			365: 12,
			730: 24
		};
		var durationDays = parseInt(duration, 10);
		if (daysToMonths[durationDays] !== undefined) {
			duration = daysToMonths[durationDays];
		}
		$('#edit_id').val(record_id);
		$('#plan_head').html('Edit Plan');
		$('#frm_plan #plan_type').val(plan_type);		
		$('#frm_plan #price').val(price);		
		$('#frm_plan #title').val(title);		
		$('#frm_plan #duration').val(duration);	
		$( "#preloader" ).hide();		
		$('#modal_add_plan').modal('show');
	}
	function upload_dec(id)
	{
		var record_id = $('#'+id).data('id');				
		$('#edit_id').val(record_id);	
		$('#upload_declaration').modal('show');
	}
	function freeze_popup(id)
	{ 
		var record_id = $('#'+id).data('id');				
		$('#freeze_popup #edit_id').val(id);	
		$('#freeze_popup').modal('show');
	}
	function renew_popup(id)
	{ 
		var record_id = $('#'+id).data('id');				
		var membership_type = $('#'+id).data('membership_type');			
		var name = $('#'+id).data('name');			
		var plan_id = $('#'+id).data('plan_id');			
		var member_id = $('#'+id).data('member_id');
		getRenewPlans(membership_type);
		//getRenewBase(plan_id);
		$('#renew_popup #edit_id').val(id);	
		$('#renew_popup #member_id').val(member_id);	
		$('#renew_popup #renew_plans').val(plan_id);
		$('#renew_popup #renew_plans').trigger('change');
		$('#renewHead').html('Renew Membership For : '+ name + ' <span class="badge light badge-danger badge-sm">'+member_id+'</span>');
		$('#renew_popup').modal('show');
	}
function edit_uni_data(id)
	{
		var record_id = $('#'+id).data('id');		
		var name = $('#'+id).data('name');		
		var qualification = $('#'+id).data('qualification');		
		var gpa = $('#'+id).data('gpa');		
		var cost_of_living = $('#'+id).data('cost_of_living');		
		var cost_of_tution = $('#'+id).data('cost_of_tution');		
		var application_fee = $('#'+id).data('application_fee');		
		var pte = $('#'+id).data('pte');		
		var ielts = $('#'+id).data('ielts');		
		var toefl = $('#'+id).data('toefl');		
		var duolingo = $('#'+id).data('duolingo');		
		var uni_tat = $('#'+id).data('uni_tat');		
		var university_rank = $('#'+id).data('university_rank');		
		var acceptance_rate = $('#'+id).data('acceptance_rate');		
		var extra_test = $('#'+id).data('extra_test');				
		$('#edit_id').val(record_id);
		$('#m-title').html('Update Data');
		$('#submit').hide();
		$('#update').show();
		$('#frm_add_data #uni_name').val(name);		
		$('#frm_add_data #qualification').val(qualification);		
		$('#frm_add_data #gpa').val(gpa);		
		$('#frm_add_data #cost_of_living').val(cost_of_living);		
		$('#frm_add_data #tution_cost').val(cost_of_tution);		
		$('#frm_add_data #fees').val(application_fee);		
		$('#frm_add_data #pte').val(pte);		
		$('#frm_add_data #ielts').val(ielts);		
		$('#frm_add_data #toefl').val(toefl);		
		$('#frm_add_data #duolingo').val(duolingo);		
		$('#frm_add_data #tat').val(uni_tat);		
		$('#frm_add_data #uni_rank').val(university_rank);		
		$('#frm_add_data #acceptance_rate').val(acceptance_rate);		
		$('#frm_add_data #extra_test').val(extra_test);
		// Triggering Select2 to update the selected option
		$('#frm_add_data #extra_test').trigger('change');
		$('#modal-add-university').modal('show');
	}
function delete_popup(id)
	{
		var record_id = $('#'+id).data('id');		
		$('#del_record_id').val(record_id);	
		$('#modal-remove-data').modal('show');
	}

// -------------------- WhatsApp Messaging --------------------
function send_whatsapp_member(el) {
	// Expects data attributes from table_response (data-id, data-mobile, data-name).
	var $el = $(el);
	var memberId = $el.data('id');
	var mobile = $el.data('mobile');
	var name = $el.data('name');

	if (!memberId || !mobile) {
		toastr.error("Unable to find member mobile number.");
		return;
	}

	var defaultMsg = "Hello " + (name ? name : "there") + ", thank you for being with Evosapiens Movement. Keep training!";

	Swal.fire({
		title: 'Send WhatsApp',
		input: 'textarea',
		inputValue: defaultMsg,
		showCancelButton: true,
		confirmButtonText: 'Send',
		cancelButtonText: 'Cancel',
		buttonsStyling: false,
	}).then(function (result) {
		if (!result.value) return;

		$.ajax({
			type: "POST",
			url: window.AJAX_URL,
			dataType: "json",
			data: {
				tab: 'send_whatsapp_single',
				member_id: memberId,
				message_type: 'custom',
				message_text: result.value
			},
			success: function (obj) {
				if (obj.msg_code == '00') toastr.success(obj.msg);
				else toastr.error(obj.msg);
			},
			error: function () {
				toastr.error("Unable to send WhatsApp. Please try again.");
			}
		});
	});
}