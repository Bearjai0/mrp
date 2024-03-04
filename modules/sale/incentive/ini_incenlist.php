<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../../session.php");
        require_once("../../../js_css_header.php");
    ?>
    <body>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('../../../navbar.php');
                require_once("../../../menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            <div id="content" class="app-content">
                <h1 class="page-header mb-3">Incentive Control</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Incentive Control</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="d-flex justify-content-start">
                            <div class="flex-item">
                                <small>Select Month</small>
                                <input type="month" id="inc_month" name="inc_month" class="form-control">
                            </div>
                            <div class="flex-item">
                                <small class="text-white">x</small><br>
                                <button onclick="ConfigIncentive()" class="btn bg-gradient-blue text-white ms-2 fw-700">Config</button>
                            </div>
                        </div>
                        <hr>
                        
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){

    })

    function ConfigIncentive(){
        const inc_month = $("#inc_month").val()

        if(inc_month == ''){
            SwalOnlyText('error', 'เลือกข้อมูลเดือนก่อนดำเนินการ!')
            return false
        }
        
        // OpenViewDetail('#load_view_detail', 'load_incenlist', inc_month)
        OpenViewDetail('#load_view_detail', 'load_create_incentive', inc_month)
    }
</script>