
<!--**********************************footer start ***********************************-->
        <div class="footer">
            <div class="copyright">
               <p>
  Copyright© @<?php echo COMPANY_NAME; ?> <?php echo date('Y'); ?>,
  Crafted with ❤️ by Aman Gahlawat
</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->
		
        <!--**********************************
           Support ticket button end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
	<script src="./vendor/chart.js/Chart.bundle.min.js"></script>
	<script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<!-- tagify -->
	<script src="./vendor/tagify/dist/tagify.js"></script> 
	<!-- Apex Chart -->
	<script src="vendor/bootstrap-datetimepicker/js/moment.js"></script>
	<script src="vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/custom-script.js"></script>
	<script src="./js/deznav-init.js"></script>
    <script src="vendor/toastr/toastr.min.js"></script>
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <script>
        // Used by inline actions (like "Send WhatsApp") to call server endpoints.
        window.AJAX_URL = "index.php?<?php echo $mysqli->encode('stat=ajax'); ?>";
        window.TABLE_RESPONSE_URL = "index.php?<?php echo $mysqli->encode('stat=table_response'); ?>";
    </script>
    <!--<script src="./js/demo.js"></script>
    <script src="./js/styleSwitcher.js"></script>-->
