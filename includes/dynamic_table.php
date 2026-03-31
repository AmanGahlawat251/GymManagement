<script>
	// Some pages (like Employees) don't include timer elements.
	// Guard these calls to prevent JS runtime errors blocking AJAX table loads.
	if (document.getElementById('timercontroller')) {
		stoptimer();
	}
	if (document.getElementById('timee')) {
		showtime('15');
	}
	$(document).ready(function () {
		loadTableRecords(<?php echo $pageno; ?>);
		$(document).on("click", "#dynamic_div li.actives", function () {
			var page = $(this).attr('p');
			loadTableRecords(page);
		}); 					
		$(document).on("click", "#search", function () {		
			loadTableRecords(1);		
		});
		$(document).on("click", ".btn-modal-close", function () {		
			var page11 = $(".pagination").find(".active").attr('p');	
			loadTableRecords(page11);		
		});
		
	});
	
	
	function goto1(page) {
        document.frm.page.value = page;
        document.frm.submit();
    }

    function gosort(orderon, orderby) {
        document.frm.orderby.value = orderby;
        document.frm.orderon.value = orderon;
        document.frm.submit();
    }
	
	function download_csv() {
		var download = $('#btn_download').data('url');
		$('#download').val(download);
        var ser_data = $('#frm_search').serialize();
		var request_url = $('#url').val();
        $.ajax
        ({
            type: "POST",
            url: request_url, //'table_response.php',
            data: ser_data,
            beforeSend: function () {
                $('#preloader').show(); 
            },
			timeout: 3000000, 
            success: function (msg) {
                //alert(msg);
				$('#download').val('');
                window.location.href = trim(msg);
                $('#preloader').hide();
            },
			error: function ( jqXHR, exception ) {
				$( "#preloader" ).hide();
			}
        });

    }
	
	function loadTableRecords(page) {
       $('#preloader').show();		
		var ser_data = $('#frm_search').serialize();  
		var request_url = $('#url').val();
        $.ajax
        ({
            type: "POST",            
            url: request_url, //'table_response.php',
            data: ser_data + "&page="+ page ,
            beforeSend: function () {
                $("#preloader").show();
            },
            success: function (msg) {
                $('#preloader').hide();
                $("#dynamic_div").html(msg); 
				//$('#live_search').parents().eq(1).hide();
				sortTbl();								
                var total_records = $('#total_records').val();
                if (total_records > 0) {
                    $('#lbl_total').html('(<b>' + total_records + '</b>)');
                }
                else {
                    $('#lbl_total').html('(<b>0</b>)');
                }
				
				var attrSort = $("#dynamic_table").attr('data-sort');
				if (typeof attrSort !== 'undefined' && attrSort !== false)
				{
					$("#dynamic_table").tablesorter(); //https://mottie.github.io/tablesorter/docs/#Demo
				}
				
                $('#btn_download').click(function () {
                    download_csv();
                });
				
				var limitt = $('#record_limit').val();				
				$('#record_limit_change').val(limitt);
				
				$('#record_limit_change').change(function () {
					//var page = $(".pagination").find(".active").attr('p');
					$('#record_limit').val($(this).val());
					loadTableRecords(1);
				}); 

				$("#live_search").on("keyup", function() {
					var value = $(this).val().toLowerCase();
					$("#dynamic_table tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
						if($('#tbody tr:visible').length < 10)
						{
							$('.pagination').css("display","none");
						}
						else
						{
							$('.pagination').css("display","");
						}
					});
				});
				
				$("#live_search11").on("keyup", function() {
					var value = $(this).val().toLowerCase();
					$("#dynamic_table tbody tr").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
						if($('#tbody tr:visible').length < 10)
						{
							$('.pagination').css("display","none");
						}
						else
						{
							$('.pagination').css("display","");
						}
					});
				});
				$("#checkAll").click(function () {
					$('input:checkbox').not(this).prop('checked', this.checked);
				});
				
            },
			error: function ( jqXHR, exception ) {
				$( "#preloader" ).hide();
			}
        });
    }
	
	function showtime(tt) {
		if (!document.getElementById('timee')) return;
        if (tt < 10)
            tt = '0' + tt;
        document.getElementById('timee').innerHTML = tt;
        tt--;
        if (tt < 0) {
            tt = '15';
            loadTableRecords(1);
            //location.reload(true);
        }
        if (timer_bool == 0) {
            setTimeout("showtime(" + tt + ");", 1000);
        }
    }
	
	function stoptimer() {
		if (!document.getElementById('timercontroller')) return;
        document.getElementById('timercontroller').removeAttribute('class');
        document.getElementById('timercontroller').setAttribute('class', 'fa fa-play icon-lg');
        document.getElementById('timercontroller').setAttribute('title', 'Play');
        timer_bool = 1;
        document.getElementById('timercontroller').removeAttribute('onclick');
        document.getElementById('timercontroller').setAttribute('onclick', 'starttimer()');
    } 

    function starttimer() {
        document.getElementById('timercontroller').removeAttribute('class');
        document.getElementById('timercontroller').setAttribute('class', 'fa fa-pause icon-lg');
        document.getElementById('timercontroller').setAttribute('title', 'Pause');
        timer_bool = 0;
        document.getElementById('timercontroller').removeAttribute('onclick');
        document.getElementById('timercontroller').setAttribute('onclick', 'stoptimer()');
        var latestime_value = document.getElementById('timee').innerHTML;
        showtime(latestime_value);
    }
	
	function sortTbl(tbl = "")
	{
			if(tbl == "")
			{
				var table = $('#dynamic_table');  
			}
			else
			{
				var table = $('#dynamic_table123'); 
			}				
			$('.sort').append('<i style="margin-left:3px;" class="fa fa-sort"></i>').each(function(){            
			var th = $(this),
				thIndex = th.index(),
				inverse = false;
			
			th.click(function(){
				
				table.find('td').filter(function(){
					
					return $(this).index() === thIndex;
					
				}).sortElements(function(a, b){
					
					return $.text([a]) > $.text([b]) ?
						inverse ? -1 : 1
						: inverse ? 1 : -1;
					
				}, function(){
					
					// parentNode is the element we want to move
					return this.parentNode; 
					
				});
				
				inverse = !inverse;
					
			});
		});
	}		
	
	
</script>