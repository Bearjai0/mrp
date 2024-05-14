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
                <h1 class="page-header mb-3">Confirm Tightening</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Confirm Tightening</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="alert alert-warning alert-dismissible rounded-0 mb-0 fade show">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
					</div>
                    <button onclick="ConfirmManual()" class="btn bg-gradient-blue-indigo text-white mt-2 fw-bold w-350px"><i class="fa-solid fa-layer-group"></i> Confirm work outside production plan</button>
					<div class="panel-body">
                        <table id="table_pd_combine_set" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Details / Actions</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Cover Number</th>
                                    <th class="text-nowrap">Receive (จำนวนรวมที่รับมามัด)</th>
                                    <th class="text-nowrap">Quantity (จำนวนที่รอมัด)</th>
                                    <th class="text-nowrap">Used (มัดไปแล้วแต่ WF ยังไม่รับ)</th>
                                    <th class="text-nowrap">Complete (WF รับไปแล้ว)</th>
                                    <th class="text-nowrap">Semi - FG</th>
                                    <th class="text-nowrap">Packing Usage</th>
                                    <th class="text-nowrap">Job Number</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">FG Description</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">Component</th>
                                    <th class="text-nowrap">SHIP TO TYPE</th>
                                    <th class="text-nowrap">Eff On.</th>
                                    <th class="text-nowrap">Eff By</th>
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
<div id="load_view_detail" class="modal fade" tabindex="-1"></div>


<script type="text/javascript">
    $(document).ready(function(){
        calldata('#table_pd_combine_set')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_pd_tigthing?>/management', { protocol: 'SyncTigthingList', }, function(data){
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
                            return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_tigthing?>/load_tigthing_cv\', \''+data.list_conf_no+'\')" class="btn btn-icon rounded bg-gradient-blue-indigo text-white" data-toggle="tooltip" data-placement="top" title="Confirm tigthing"><i class="fa-regular fa-thumbs-up"></i></button>' +
                                   '<button onclick="OpenViewDetail(\'#load_view_detail\', \'<?=$CFG->fol_pd_tigthing?>/load_tigthing_rt\', \''+data.list_conf_no+'\')" class="btn btn-icon rounded bg-gradient-dark text-white ms-2" data-toggle="tooltip" data-placement="top" title="Return combine set"><i class="fa-solid fa-arrow-rotate-left"></i></button>'
                        }, className: "text-nowrap" },
                        { data: function(data){ return '<span class="badge '+data.class_color+'">'+data.list_status+'</span>' }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.list_conf_no }, className: "text-nowrap" },
                        { data: function(data){ return data.list_receive_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.list_pending_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.list_current_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.list_used_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.list_pending_qty + data.list_current_qty }, className: "text-nowrap" },
                        { data: function(data){ return data.list_packing_usage }, className: "text-nowrap" },
                        { data: function(data){ return data.job_no } },
                        { data: function(data){ return data.list_fg_codeset }, className: "text-nowrap" },
                        { data: function(data){ return data.list_fg_code }, className: "text-nowrap" },
                        { data: function(data){ return data.list_fg_description }, className: "text-nowrap" },
                        { data: function(data){ return data.list_cus_code }, className: "text-nowrap" },
                        { data: function(data){ return data.list_project }, className: "text-nowrap" },
                        { data: function(data){ return data.list_part_customer }, className: "text-nowrap" },
                        { data: function(data){ return data.list_comp_code }, className: "text-nowrap" },
                        { data: function(data){ return data.list_ship_to_type }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.list_conf_datetime).format("DD/MM/YYYY, HH:mm") }, className: "text-nowrap" },
                        { data: function(data){ return data.list_conf_by }, className: "text-nowrap" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }

    function ConfirmManual(){
        OpenViewDetail('#load_view_detail', '<?=$CFG->fol_pd_tigthing?>/load_tigthing_manual')
    }
</script>