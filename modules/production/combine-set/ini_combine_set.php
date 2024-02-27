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
                <h1 class="page-header mb-3">Combine Set</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Combine Set</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<!-- <div class="alert alert-warning alert-dismissible rounded-0 mb-0 fade show">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
					</div> -->
                    <button onclick="CombineMaster()" class="btn bg-gradient-blue text-white m-1 fw-bold"><i class="fa-solid fa-layer-group"></i> Combine master set</button>
					<div class="panel-body">
                        <table id="table_pd_combine_set" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap not-export-col">Select</th>
                                    <th class="text-nowrap not-export-col">Actions / Details</th>
                                    <th class="text-nowrap">Job type</th>
                                    <th class="text-nowrap">Job number</th>
                                    <th class="text-nowrap">Quantity (รอรวมเซ็ท)</th>
                                    <th class="text-nowrap">Plan Date</th>
                                    <th class="text-nowrap">Combine Machine</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">Descriptions</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">Packing Usage</th>
                                    <th class="text-nowrap">Receive Qty</th>
                                    <th class="text-nowrap">Used Qty</th>
                                    <th class="text-nowrap">Receive Datetime</th>
                                    <th class="text-nowrap">Receive By</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold"></tbody>
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
        calldata('#table_pd_combine_set')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_pd_combine?>/management', { protocol: 'SyncSemiInventory', }, function(data){
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
                    pageLength  : -1,
                    scrollY   : '480px',
                    data      : result.datas,
                    columns : [
                        { data: function(data){ return data.list }, className: "text-nowrap text-center" },
                        { data: function(data){ return '<input type="checkbox" id="'+data.sem_job_no+'" name="CheckList[]" class="form-check-input" style="width: 2em; height: 2em;">' }, className: "text-nowrap text-center" },
                        { data: function(data){
                            return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_work?>/load_pd_work_details\', \''+data.sem_job_no+'\')" class="btn btn-xs fw-600 rounded bg-gradient-blue-indigo text-white" data-toggle="tooltip" data-placement="top" title="View job details"><i class="fa-solid fa-expand"></i> Expand Job</button>' +
                                   '<a target="_blank" href="<?=$CFG->printed_job?>?job_no='+data.sem_job_no+'" class="btn btn-xs fw-600 rounded bg-gradient-dark text-white ms-2" data-toggle="tooltip" data-placement="top" title="Print job.pdf"><i class="fa-regular fa-file-pdf"></i> Print Job</a>' +
                                   '<a target="_blank" href="<?=$CFG->func_bom_master?>/ini_drawing?bom_uniq='+data.job_bom_id+'" class="btn btn-xs fw-600 rounded bg-gradient-orange text-dark ms-2" data-toggle="tooltip" data-placement="top" title="Print drawing.pdf"><i class="fas fa-file-signature"></i> Print Drawing</a>' +
                                   '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_combine?>/load_transfer_wip\', \''+data.sem_job_no+'\')" class="btn btn-xs fw-600 rounded bg-gradient-indigo text-white ms-2" data-toggle="tooltip" data-placement="top" title="View job details"><i class="fa-solid fa-shuffle"></i> Transfer WIP</button>'
                                //    '<button onclick="Resetjob(\''+data.sem_job_no+'\')" class="btn btn-icon rounded bg-gradient-yellow-red text-white ms-2" data-toggle="tooltip" data-placement="top" title="Reset job"><i class="fa-solid fa-arrow-rotate-left"></i></button>'
                        }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fac_type }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_job_no }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_stock_qty }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_plan_date }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.machine_type_name }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_cus_code }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_project }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_fg_codeset }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_fg_code }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_fg_description }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_part_customer }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_packing_usage }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_receive_qty }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_used_qty }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_gen_datetime }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sem_gen_by }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }

    function CombineMaster(){
        $("#table_pd_combine_set").DataTable().search('').columns().search('').draw()

        var job_no = $("input[name='CheckList[]']:checked").map(function(e){ return this.id }).get()
        
        if(job_no.length == 0){
            SwalOnlyText('warning', 'กรุณาเลือกรายการก่อนทำการ Combine Set')
        }else{
            $.post('<?=$CFG->fol_pd_combine?>/management', { protocol: 'CHeckMasterSet', job_no: job_no }, function(data){
                console.log(data)
                try {
                    const result = JSON.parse(data)
                    if(result.code == 200){
                        OpenViewDetail('#load_view_detail', '<?=$CFG->fol_pd_combine?>/load_pd_combine_master', job_no)
                    }else{
                        SwalOnlyText('warning', result.message)
                    }
                } catch(err) {
                    SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + err.message)
                }
            })
        }
    }
</script>