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
                <h1 class="page-header mb-3">Customer order</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Manage customer order</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <table id="table_ffmc_order" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Select</th>
                                    <th class="text-nowrap">Actions/Details</th>
                                    <th class="text-nowrap">Order Status</th>
                                    <th class="text-nowrap">Order Ref#</th>
                                    <th class="text-nowrap">Delivery Date</th>
                                    <th class="text-nowrap">Date Diff</th>
                                    <th class="text-nowrap">Plan Pack</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">Balance On Hand (Pcs.)</th>
                                    <th class="text-nowrap">Plan Q`ty (Pcs.)</th>
                                    <th class="text-nowrap">รอ Put-Away</th>
                                    <th class="text-nowrap">WIP(on production)</th>
                                    <th class="text-nowrap">Semi</th>
                                    <th class="text-nowrap">Pending</th>
                                    <th class="text-nowrap">Prepare</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">Component</th>
                                    <th class="text-nowrap">Descriptions</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Remark</th>
                                    <th class="text-nowrap">Upload On</th>
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
    <?php require_once("../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){
        calldata('#table_ffmc_order')
    })

    function calldata(tbl){
        $.post('<?=$CFG->fol_ffmc_manage?>/management', { protocol: 'CustomerOrder' }, function(data){
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
                        { data: function(data){ return '<input type="checkbox" id="'+data.wf_ref_code+'" name="CheckList[]" style="width: 2em; height: 2em;">'}, className: 'text-center' },
                        { data: function(data){
                            const disab = data['wf_status'] == 'Pending' ? '' : 'disabled'
                            const disapp = parseInt(data.wf_plan_qty) <= parseInt(data.stock) ? 'disabled' : ''

                            var html = '<button onclick="Route(\''+data.wf_ref_code+'\',\'load_ffmc_order\')" class="btn btn-icon rounded bg-gradient-red text-white ms-2" data-toggle="tooltip" data-placement="top" title="Update order details"><i class="fa-solid fa-pencil"></i></button>' +
                                    '<button onclick="Route(\''+data.wf_ref_code+'\',\'load_split_order\')" class="btn btn-icon rounded bg-gradient-blue text-white ms-2" data-toggle="tooltip" data-placement="top" title="Split Plan"><i class="fa-solid fa-up-right-and-down-left-from-center"></i></button>' +
                                    '<button onclick="Route(\''+data.wf_ref_code+'\',\'load_confirm_produce\')" class="btn btn-icon rounded bg-gradient-orange text-white ms-2" data-toggle="tooltip" data-placement="top" title="Confirm Produce" '+disapp+'><i class="fa-solid fa-industry"></i></button>'
                            return html
                        }, className: 'text-nowrap' },
                        { data: function(data){ return '<span class="badge rounded '+data.css_class+'">'+data.status+'</span>' }, className : 'text-center' },
                        { data: function(data){ return data.wf_order_ref }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_delivery_date).format('DD/MM/YYYY') }, className: 'text-nowrap' },
                        { data: function(data){ return data.datediff + ' Day' }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_plan_pack).format('DD/MM/YYYY') ?? '' }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_fg_code }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_fg_codeset }, className: 'text-nowrap' },
                        { data: function(data){ return data.stock }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_plan_qty }, className: 'text-nowrap' },
                        { data: function(data){ return data.wait_putaway }, className: 'text-nowrap' },
                        { data: function(data){ return currency(data.job_wip, { symbol: '', precision: 0 }).format() }, className: 'text-nowrap' },
                        { data: function(data){ return currency(data.tigthing + data.semi, { symbol: '', precision: 0 }).format() }, className: 'text-nowrap' },
                        { data: function(data){ return currency(data.job_pending, { symbol: '', precision: 0 }).format() }, className: 'text-nowrap' },
                        { data: function(data){ return currency(data.job_prepare, { symbol: '', precision: 0 }).format() }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_part_customer }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_comp_code }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_fg_description }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_cus_code }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_project }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_remarks }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_post_datetime).format('DD/MM/YYYY, HH:mm') }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_post_by }, className: 'text-nowrap'},
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