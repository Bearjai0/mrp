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
                <h1 class="page-header mb-3">WIP Inventory</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - WIP Inventory Management</h4>
						<?php require_once("../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <table id="table_wip_inven" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <!-- <th class="text-nowrap not-export-col">Actions / Details</th> -->
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">Description</th>
                                    <th class="text-nowrap">Receive Qty</th>
                                    <th class="text-nowrap">Stock Qty</th>
                                    <th class="text-nowrap">Used Qty</th>
                                    <th class="text-nowrap">Upload Datetime</th>
                                    <th class="text-nowrap">Upload By</th>
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
    <?php require_once("../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){
        calldata('#table_wip_inven')
    })

    function calldata(tbl){
        $.post('<?=$CFG->mod_wip_inven?>/management', { protocol: 'WIPList' }, function(data){
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
                        { data: function(data){ return data.list }, className: 'text-nowrap text-center' },
                        // { data: function(data){ 
                        //     return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_adjust_wip\', \''+data.inven_uniq+'\')" class="btn btn-xs rounded bg-gradient-blue-indigo text-white fw-600" data-toggle="tooltip" data-placement="top" title="Adjust WIP"><i class="fa-solid fa-pencil"></i> Adjust WIP</button>'
                        // }, className: 'text-nowrap text-center' },
                        { data: function(data){return '<span class="badge fw-600 bg-gradient-blue text-white">Active</span>' }, className: 'text-nowrap' },
                        { data: function(data){ return data.inven_fg_code }, className: 'text-nowrap text-center' },
                        { data: function(data){ return data.inven_fg_desc }, className: 'text-nowrap' },
                        { data: function(data){ return data.inven_receive_qty }, className: 'text-nowrap' },
                        { data: function(data){ return data.inven_stock_qty }, className: 'text-nowrap' },
                        { data: function(data){ return data.inven_used_qty }, className: 'text-nowrap' },
                        { data: function(data){ return data.receive_datetime }, className: 'text-nowrap' },
                        { data: function(data){ return data.receive_by }, className: 'text-nowrap' },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }

    function CancelOrder(job_uniq){
        Swal.fire({
            icon: 'info',
            text: 'ยืนยัน Cancel Order ผลิตนี้หรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){
                Swal.fire({
                    title: 'กำลังดำเนินการบันทึกข้อมูล...',
                    text: 'กรุณารอสักครู่',
                    imageUrl: '<?=$CFG->sub_gif?>/ajax-loader.gif',
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        $.post('<?=$CFG->fol_planning_manage?>/management', { protocol: 'CancelOrder', job_uniq: job_uniq }, function(data){
                            try {
                                const result = JSON.parse(data)

                                if(result.code == 200){
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ดำเนินการสำเร็จ',
                                        text: result.message
                                    }).then(() => {
                                        location.reload()
                                    })
                                }else{
                                    SwalOnlyText('error', 'ไม่สามารถประมวลผลได้ ' + err.message)
                                }
                            } catch(err) {
                                SwalOnlyText('error', 'ไม่สามารถประมวลผลได้ ' + err.message)
                            }
                        })
                    }
                })
            }
        })
    }
</script>