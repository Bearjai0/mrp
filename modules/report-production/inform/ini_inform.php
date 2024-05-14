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
                <h1 class="page-header mb-3">สรุปข้อมูลแผนการผลิต</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Job Summary</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="d-flex">
                            <div class="flex-item">
                                <small>Start plan date</small>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?=$buffer_date?>">
                            </div>
                            <div class="flex-item ms-2">
                                <small>End plan date</small>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?=$buffer_date?>">
                            </div>
                            <div class="flex-item ms-2">
                                <small>Job status</small>
                                <select id="job_status" name="job_status" class="form-control" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                <?php
                                    $list = $db_con->query("SELECT job_status FROM tbl_job_mst GROUP BY job_status");
                                    while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                                        $job_status = $listResult['job_status'];
                                        echo "<option value='$job_status'>$job_status</option>";
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="flex-item ms-2">
                                <small class="text-white">x</small><br>
                                <button onclick="calldata()" class="btn bg-gradient-yellow text-dark w-100"><i class="fa-solid fa-filter"></i> Filter</button>
                            </div>
                            <div class="flex-item ms-2">
                                <small class="text-white">x</small><br>
                                <button onclick="expStation()" class="btn bg-gradient-dark text-white w-100"><i class="fa-solid fa-square-poll-vertical"></i> Export station details</button>
                            </div>
                        </div>
                        <hr>
                        <table id="table_inform" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Plan Date</th>
                                    <th class="text-nowrap">JOB NO.</th>
                                    <th class="text-nowrap">Plan (Set)</th>
                                    <th class="text-nowrap">FG (Set)</th>
                                    <th class="text-nowrap">Plan (Set / Job)</th>
                                    <th class="text-nowrap">FG (Set / Job)</th>
                                    <th class="text-nowrap">Plan (Pcs.)</th>
                                    <th class="text-nowrap">FG (Pcs.)</th>
                                    <th class="text-nowrap">Semi</th>
                                    <th class="text-nowrap">WIP</th>
                                    <th class="text-nowrap">Cost / Pcs.</th>
                                    <th class="text-nowrap">Selling / Pcs.</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">FG Code GDJ</th>
                                    <th class="text-nowrap">FG Descriptions</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">Ship Type</th>
                                    <th class="text-nowrap">FG / Page</th>
                                    <th class="text-nowrap">PD Usage</th>
                                    <th class="text-nowrap">DWG Code</th>
                                    <th class="text-nowrap">RM Code</th>
                                    <th class="text-nowrap">RM Spec</th>
                                    <th class="text-nowrap">RM Usage(Pcs.)</th>
                                    <th class="text-nowrap">BOM Cost RM(Pcs.)</th>
                                    <th class="text-nowrap">BOM Cost RM Total</th>
                                    <th class="text-nowrap">Cost RM Total</th>
                                    <th class="text-nowrap">Actual Cost RM(Set.)</th>
                                    <th class="text-nowrap">Release Time(PC.)</th>
                                    <th class="text-nowrap">Release By(PC.)</th>
                                    <th class="text-nowrap">Start Datetime</th>
                                    <th class="text-nowrap">Estimated Complete Datetime</th>
                                    <th class="text-nowrap">Complete Datetime</th>
                                    <th class="text-nowrap">Currently Operation at..</th>
                                </tr>
                            </thead>
                            <tbody class="fw-600"></tbody>
                        </table>
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
        calldata()
    })

    function calldata(tbl = '#table_inform'){
        $.post('management', { protocol: 'SummaList', start_date: $("#start_date").val(), end_date: $("#end_date").val(), job_status: $("#job_status").val() }, function(data){
            console.log(data)
            // try {
                const result = JSON.parse(data)
                console.log(result)
                $(tbl+' thead tr').clone(true).appendTo(tbl+' thead')
                $(tbl+' thead tr:eq(0) th').each(function(i){
                    var title = $(this).text()
                    if(i > 2){
                        $(this).html('<input type="text" placeholder="Search by '+ title +'" />')
                        $('input', this).on('keyup change', function(){
                            if(table.column(i).search() !== this.value){
                                table.column(i).search(this.value).draw()
                            }
                        })
                    }else{
                        $(this).html('')
                    }
                })

                var table = $(tbl).DataTable({
                    dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-lg-8 d-block d-sm-flex d-lg-block justify-content-center"<"d-block d-lg-inline-flex me-0 me-md-3"l><"d-block d-lg-inline-flex"B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row"<"col-md-5"i><"col-md-7"p>>>',
                    buttons: [
                        { extend: 'copy', className: 'btn-sm' },
                        { extend: 'csv', className: 'btn-sm' },
                        { extend: 'excel', className: 'btn-sm' },
                        { extend: 'pdf', className: 'btn-sm' },
                    ],
                    colReorder: true,
                    keys      : true,
                    rowReorder: true,
                    select    : true,
                    scrollX   : true,
                    bDestroy  : true,
                    lengthMenu  : [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                    pageLength  : 100,
                    scrollY   : '480px',
                    data      : result.datas,
                    columns : [
                        { data: function(data){ return data.list }, className: "text-nowrap text-center" },
                        { data: function(data){ return '<span class="badge rounded text-white '+data.class_color+'">'+data.job_status+'</span>' }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.job_plan_date).format('DD/MM/YYYY') }, className: "text-nowrap" },
                        { data: function(data){ return data.job_no }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_set }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_fg_set - data.tigthing }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_set_per_job }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_fg_set_per_job }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.job_plan_fg_qty }, className: "text-nowrap" },
                        { data: function(data){ return parseInt(data.sem_stock_qty) + parseInt(data.tigthing) }, className: "text-nowrap" },
                        { data: function(data){ return data.job_status == "complete" ? '0' : parseInt(data.job_plan_set_per_job) - parseInt(data.job_plan_fg_set_per_job) - (parseInt(data.sem_stock_qty) + parseInt(data.tigthing)) }, className: "text-nowrap" },
                        { data: function(data){ return currency(data.cost_total, { seperator: ',', symbol: '', }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.selling_price, { seperator: ',', symbol: '', }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return data.job_fg_codeset }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_description }, className: "text-nowrap" },
                        { data: function(data){ return data.job_cus_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_project }, className: "text-nowrap" },
                        { data: function(data){ return data.job_part_customer }, className: "text-nowrap" },
                        { data: function(data){ return data.job_ship_to_type }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_perpage }, className: "text-nowrap" },
                        { data: function(data){ return data.job_pd_usage }, className: "text-nowrap" },
                        { data: function(data){ return data.job_dwg_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_rm_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_rm_spec }, className: "text-nowrap" },
                        { data: function(data){ return currency(data.job_rm_usage, { separator: ',', symbol: '' }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.cost_rm, { separator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.cost_rm * data.job_rm_usage, { separator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.rm_cost_total, { separator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.actual_cost_rm, { separator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return moment(data.job_pc_conf_datetime).format('DD/MM/YYYY') }, className: "text-nowrap" },
                        { data: function(data){ return data.job_pc_conf_by }, className: "text-nowrap" },
                        { data: function(data){ return data.job_pd_conf_datetime ? moment(data.job_pd_conf_datetime).format('DD/MM/YYYY HH:mm') : '' }, className: "text-nowrap" },
                        { data: function(data){ return data.job_est_end_datetime ? moment(data.job_est_end_datetime).format('DD/MM/YYYY HH:mm') : '' }, className: "text-nowrap" },
                        { data: function(data){ return data.job_complete_datetime ? moment(data.job_complete_datetime).format('DD/MM/YYYY HH:mm') : '' }, className: "text-nowrap" },
                        { data: function(data){ return data.machine_type_name }, className: "text-nowrap" }
                    ]
                }).draw(false)
            // } catch(err) {
            //     SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            // }
        })
    }

    function expStation(){
        var start_date = $("#start_date").val()
        var end_date = $("#end_date").val()
        var status = $("#job_status").val()

        console.log(status)

        window.open("<?=$CFG->export_pd?>"+'/exp_production?protocol=exp_pd_v1&start_date='+start_date+'&end_date='+end_date+'&status='+status, '_blank')
    }
</script>