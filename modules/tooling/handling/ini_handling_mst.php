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
                <h1 class="page-header mb-3">Handling Tooling</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Handling Tooling</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <table id="table_handling_tooling" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap not-export-col">Actions / Details</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">Part Name</th>
                                    <th class="text-nowrap">Tooling Name</th>
                                    <th class="text-nowrap">Location</th>
                                    <th class="text-nowrap">Tool Type</th>
                                    <th class="text-nowrap">Layout</th>
                                    <th class="text-nowrap">Price</th>
                                    <th class="text-nowrap">Stroke</th>
                                    <th class="text-nowrap">Supplier</th>
                                    <th class="text-nowrap">Add By</th>
                                    <th class="text-nowrap">Add Datetime</th>
                                    <th class="text-nowrap">Update By</th>
                                    <th class="text-nowrap">Update Datetime</th>
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
        calldata('#table_handling_tooling')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_handling?>/management', { protocol: 'WithdrawList' }, function(data){
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
                            return '<button onclick="ReturnTooling('+data.ts_uniq+', \''+data.ts_fg_code+'\')" class="btn btn-xs rounded bg-gradient-blue-indigo text-white fw-700" data-toggle="tooltip" data-placement="top" title="Return Tooling"><i class="fa-solid fa-arrow-rotate-left"></i> Return Tooling</button>'
                        }, className: "text-nowrap" },
                        { data: function(data){ return '<span class="badge rounded '+data.class_color+' text-dark">'+data.ts_status+'</span>' }, className: "text-nowrap text-center fw-700" },
                        { data: function(data){ return data.ts_fg_code }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_fg_description }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_tooling_name }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_location }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_sub_type }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_layout }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.ts_price, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.ts_stroke, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sup_name_en }, className: "text-nowrap text-center" },
                        { data: function(data){ return moment(data.ts_upload_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_upload_by }, className: "text-nowrap text-center" },
                        { data: function(data){ return moment(data.ts_update_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ts_update_by }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }

    function ReturnTooling(ts_uniq, fg_code){
        Swal.fire({
            icon: 'info',
            text: 'ยืนยัน Return Tooling สำหรับ '+ fg_code +' หรือไม่?',
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
                        $.post('<?=$CFG->fol_handling?>/management', { protocol: 'ReturnTooling', ts_uniq: ts_uniq }, function(data){
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