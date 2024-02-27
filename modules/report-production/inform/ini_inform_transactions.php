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
                                <button onclick="calldata()" class="btn bg-gradient-blue text-white w-100">Filter</button>
                            </div>
                        </div>
                        <hr>
                        <table id="table_inform_transactions" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Actions</th>
                                    <th class="text-nowrap">STATUS</th>
                                    <th class="text-nowrap">JOB NO.</th>
                                    <th class="text-nowrap">Planning Date</th>
                                    <th class="text-nowrap">FG Code GDJ</th>
                                    <th class="text-nowrap">Description</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">Plan Q`ty (Set.)</th>
                                    <th class="text-nowrap">Plan Q`ty (Pcs.)</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">MRD Code#</th>
                                    <th class="text-nowrap">ft<sup>2</sup></th>
                                    <th class="text-nowrap">ft<sup>2</sup> Total</th>
                                    <th class="text-nowrap">Post By</th>
                                    <th class="text-nowrap">Post Datetime</th>
                                    <th class="text-nowrap">Remarks</th>
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

    function calldata(tbl = '#table_inform_transactions'){
        $.post('<?=$CFG->fol_rep_inform?>/management', { protocol: 'SummaTransactions', start_date: $("#start_date").val(), end_date: $("#end_date").val(), job_status: $("#job_status").val() }, function(data){
            console.log(data)
            try {
                const result = JSON.parse(data)

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
                        { data: function(data){ return data.list }, className: "text-nowrap font-weight-bold text-center" },
                        { data: function(data){
                            var html = '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_rep_inform?>/load_inform_transactions\', \''+data.job_no+'\')" class="btn btn-icon rounded bg-gradient-blue-indigo text-white" data-toggle="tooltip" data-placement="top" title="Confirm Job"><i class="fa-regular fa-folder-open"></i></button>' +
                                       '<a target="_blank" href="<?=$CFG->printed_job?>?job_no='+data.job_no+'" class="btn btn-icon rounded bg-gradient-dark text-white ms-2"><i class="fa-solid fa-print"></i></a>'
                            return html
                        }, className: "text-nowrap text-center" },
                        { data: function(data){ return '<span class="badge fw-700 rounded '+data.class_color+'">'+data.job_status+'</span>' }, className: 'text-nowrap font-weight-bold text-center' },
                        { data: function(data){ return data.job_no }, className: 'text-nowrap font-weight-bold text-center' },
                        { data: function(data){ return moment(data.job_plan_date).format('DD/MM/YYYY') }, className: 'text-nowrap font-weight-bold text-center' },
                        { data: function(data){ return data.job_fg_code }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return data.job_fg_description }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return data.job_fg_codeset }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return currency(data.job_plan_set, { seperator: ',', symbol: '', }).format() }, className: 'text-nowrap font-weight-bold text-right' },
                        { data: function(data){ return currency(data.job_plan_qty, { seperator: ',', symbol: '', }).format() }, className: 'text-nowrap font-weight-bold text-right' },
                        { data: function(data){ return data.job_cus_code }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return data.job_project }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return data.job_ref }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return currency(data.job_ft2_perpage, { seperator: ',', symbol: '', }).format() }, className: 'text-nowrap font-weight-bold text-right' },
                        { data: function(data){ return currency(data.job_ft2_usage, { seperator: ',', symbol: '', }).format() }, className: 'text-nowrap font-weight-bold text-right' },
                        { data: function(data){ return data.job_pc_conf_by }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return moment(data.job_pc_conf_datetime).format('DD/MM/YYYY HH:mm') }, className: 'text-nowrap font-weight-bold' },
                        { data: function(data){ return data.job_remarks }, className: 'text-nowrap font-weight-bold' },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }
</script>