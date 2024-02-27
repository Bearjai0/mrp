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
                <h1 class="page-header mb-3">Incentive Rate</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Incentive Rate</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="alert alert-warning alert-dismissible fade show h-100 mb-0">
							<label class="fw-700 text-gradient-red">*** 1. Margin ข้างต้นคำนวณหลังจากหัก Cost 24%</label><br>
							<label class="fw-700 text-gradient-red">*** 2. ถ้าเป็นลูกค้าที่มี CFF ต้องหัก % CFF ออกก่อน แล้วค่อยคิด Margin ให้ Incentive กับฝ่ายขาย</label><br>
                            <label>มีผล Effective ของค่า Incentive ตั้งแต่เดือน January 2024 เป็นต้นไป </label>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
                        <hr>
                        <table id="table_incenrate" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Descriptions</th>
                                    <th class="text-nowrap">Position</th>
                                    <th class="text-nowrap">Rate Min</th>
                                    <th class="text-nowrap">Rate Max</th>
                                    <th class="text-nowrap">Rate Ratio</th>
                                    <th class="text-nowrap">Remarks</th>
                                    <th class="text-nowrap">Update Datetime</th>
                                    <th class="text-nowrap">Update By</th>
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
        calldata('#table_incenrate')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_sale_incenrate?>/management', { protocol: 'IncenRateList' }, function(data){
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
                        { data: function(data){ return data.tiv_name }, className: "text-nowrap" },
                        { data: function(data){ return data.tiv_position }, className: "text-nowrap" },
                        { data: function(data){ return data.tiv_min }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.tiv_max }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.tiv_rate }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.tiv_remarks }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.tiv_update_datetime).format('DD/MM/YY, HH:mm') }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.tiv_update_by }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }
</script>