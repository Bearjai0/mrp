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
                <h1 class="page-header mb-3">Production work on process</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Work order on process</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="alert alert-warning alert-dismissible rounded-0 mb-0 fade show">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
					</div>
					<div class="panel-body">
                        <table id="table_pd_work_list" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap not-export-col">Actions / Details</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Job type</th>
                                    <th class="text-nowrap">Job number</th>
                                    <th class="text-nowrap">Plan date</th>
                                    <th class="text-nowrap">Plan qty</th>
                                    <th class="text-nowrap">Station</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">Component Code</th>
                                    <th class="text-nowrap">FG Description</th>
                                    <th class="text-nowrap">RM Code</th>
                                    <th class="text-nowrap">RM SPec</th>
                                    <th class="text-nowrap">Job Owner</th>
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
        calldata('#table_pd_work_list')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_pd_work?>/management', { protocol: 'SyncPlanOnProcess', }, function(data){
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
                        { data: function(data){ return data.list }, className: "text-nowrap text-center" },
                        { data: function(data){
                            return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_work?>/load_pd_work_details\', \''+data.job_no+'\')" class="btn btn-icon rounded bg-gradient-blue-indigo text-white" data-toggle="tooltip" data-placement="top" title="View job details"><i class="fa-solid fa-expand"></i></button>' +
                                   '<a target="_blank" href="<?=$CFG->printed_job?>?job_no='+data.job_no+'" class="btn btn-icon rounded bg-gradient-dark text-white ms-2" data-toggle="tooltip" data-placement="top" title="Print job.pdf"><i class="fa-regular fa-file-pdf"></i></a>' +
                                   '<a target="_blank" href="<?=$CFG->func_bom_master?>/ini_drawing?bom_uniq='+data.job_bom_id+'" class="btn btn-icon rounded bg-gradient-cyan-indigo text-dark ms-2" data-toggle="tooltip" data-placement="top" title="Print drawing.pdf"><i class="fas fa-file-signature"></i></a>' +
                                   '<button onclick="ResetJob(\''+data.job_no+'\')" class="btn btn-icon rounded bg-gradient-yellow-red text-dark ms-2" data-toggle="tooltip" data-placement="top" title="Reset job"><i class="fa-solid fa-arrow-rotate-left"></i></button>' + 
                                   '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_work?>/load_pd_priority\', \''+data.job_no+'\')" class="btn btn-icon rounded bg-gradient-orange text-dark ms-2" data-toggle="tooltip" data-placement="top" title="Reset job"><i class="fa-solid fa-arrow-up-1-9"></i></button>'
                        }, className: "text-nowrap" },
                        { data: function(data){ return '<span class="badge rounded '+ data.class_color + ' ' + data.class_txt_color +'">'+data.job_status+'</span>' }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fac_type }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_no }, className: "text-nowrap text-center" },
                        { data: function(data){ return moment(data.job_plan_date).format('DD/MM/YYYY') }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.job_plan_qty, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-end" },
                        { data: function(data){ return data.machine_type_name }, className: "text-nowrap text-orange" },
                        { data: function(data){ return data.job_fg_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_codeset }, className: "text-nowrap" },
                        { data: function(data){ return data.job_comp_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_description }, className: "text-nowrap" },
                        { data: function(data){ return data.job_rm_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_rm_spec }, className: "text-nowrap" },
                        { data: function(data){ return data.job_pc_conf_by }, className: "text-nowrap" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }

    function ResetJob(job_no){
        Swal.fire({
            icon: 'info',
            title: 'ต้องการ Reset แผนการผลิตหรือไม่?',
            text: 'การ Reset จะทำได้เฉพาะรายการที่ยังไม่ Combine Job เท่านั้น',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ตกลง!',
            showCancelButton: true,
            input: 'text',
            inputAttributes: { autocapitalize: 'off' },
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: (checkVal) => {
                if(checkVal == ''){
                    Swal.showValidationMessage('กรุณากรอกเหตุผลในการ​ Reset Job นี้')
                }
            } 
        }).then((thens) => {
            if(thens.isConfirmed){
                $.post('<?=$CFG->fol_pd_work?>/management', { protocol: 'ResetJob', job_no: job_no, remarks: thens.value }, function(data){
                    console.log(data)
                    try {
                        const result = JSON.parse(data)
                        if(result['code'] == '200'){
                            Swal.fire({
                                icon: 'success',
                                text: result['message'],
                            }).then(() => {
                                location.reload()
                            })
                        }else{
                            SwalOnlyText('error', result['message'])
                        }
                    } catch(err) {
                        SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + err)
                    }
                })
            }
        })
    }
</script>