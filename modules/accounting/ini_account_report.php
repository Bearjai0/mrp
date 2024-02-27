<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../session.php");
        require_once("../../js_css_header.php");
    ?>
    <body>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('../../navbar.php');
                require_once("../../menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            <div id="content" class="app-content">
                <h1 class="page-header mb-3">Warehouse Report</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataForm - Warehouse Report</h4>
						<?php require_once("../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-center">Export Inventory Transactions</h5>
                                <div class="d-flex justify-content-center">
                                    <div class="flex-item">
                                        <small class="font-weight-bold">Start Date</small>
                                        <input type="date" id="start_date" name="start_date" class="form-control font-weight-bold" value="<?=$buffer_date?>">
                                    </div>
                                    <div class="flex-item ms-2">
                                        <small class="font-weight-bold">End Date (Due Date)</small>
                                        <input type="date" id="end_date" name="start_date" class="form-control font-weight-bold" value="<?=$buffer_date?>">
                                    </div>
                                    <div class="flex-item ms-2">
                                        <small class="font-weight-bold">Inventory</small>
                                        <select id="inven_type" name="inven_type" class="form-control" data-live-search="true" data-style="btn-white">
                                            <option class="font-weight-bold" value="Raw Material">Raw Material</option>
                                            <option class="font-weight-bold" value="Sub Material">Sub Material</option>
                                            <option class="font-weight-bold" value="Finished Good">Finished Good</option>
                                        </select>
                                    </div>
                                    <div class="flex-item ms-2">
                                        <small class="font-weight-bold">Report Type</small>
                                        <select id="protocol" name="protocol" class="form-control" data-live-search="true" data-style="btn-white">
                                            <option class="fw-800" value="Inbound">Inbound</option>
                                            <option class="fw-800" value="Outbound">Outbound</option>
                                            <option class="fw-800" value="ExceededInvoice">ExceededInvoice</option>
                                            <option class="fw-800" value="InboundAndOutbound">InboundAndOutbound</option>
                                            <option class="fw-800" value="BalanceOnHand">Balance on Hand</option>
                                            <option class="fw-800" value="Full Transactions">Full Transactions</option>
                                            <option class="fw-800" value="Stock Lost">Stock Lost</option>
                                            <option class="fw-800" value="Stock Damage">Stock Damage</option>
                                        </select>
                                    </div>
                                    <div class="flex-item ms-2">
                                        <small class="font-weight-bold text-white">.</small> <br>
                                        <button onclick="ExportData()" class="btn bg-gradient-dark text-white"><i class="fa-solid fa-file-arrow-down"></i> &nbsp;&nbsp;Export Data</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#inven_type, #protocol").picker({ search: true })
    })

    function ExportData(){
        var start_date = $("#start_date").val()
        var end_date = $("#end_date").val()
        var protocol = $("#protocol").val()
        var inven_type = $("#inven_type").val()

        var route = ''

        switch(inven_type){
            case 'Raw Material' : route = '<?=$CFG->export_acc_report_rm?>'; break;
            case 'Sub Material' : route = ''; break;
            case 'Finished Good' : route = '<?=$CFG->export_acc_report_fg?>'; break;
        }

        window.open(route+'?protocol='+protocol+'&start_date='+start_date+'&end_date='+end_date, '_blank')
    }
</script>