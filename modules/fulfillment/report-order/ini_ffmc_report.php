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
                <h1 class="page-header mb-3">Report customer order</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Report customer order</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="d-flex justify-content-start">
                            <div class="flex-item">
                                <small>Start Date</small>
                                <input type="date" id="start_date" name="start_date" value="<?=$buffer_date?>" class="form-control">
                            </div>
                            <div class="flex-item ms-2">
                                <small>End Date</small>
                                <input type="date" id="end_date" name="end_date" value="<?=$buffer_date?>" class="form-control">
                            </div>
                            <div class="flex-item ms-2">
                                <small>Document Type</small>
                                <select id="doc_type" name="doc_type" class="form-control">
                                    <option value="DTN">DTN</option>
                                    <option value="INT">INT</option>
                                </select>
                            </div>
                            <div class="flex-item ms-2">
                                <small class="text-white">x</small><br>
                                <button onclick="calldata()" class="btn bg-gradient-blue text-white ps-5 pe-5"><i class="fa-solid fa-file-excel"></i> Filter</button>
                            </div>
                        </div>
                        <hr>
                        <table id="table_ffmc_report" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Order Ref#</th>
                                    <th class="text-nowrap">Delivery Date</th>
                                    <th class="text-nowrap">Upload Date</th>
                                    <th class="text-nowrap">Actual Delivery Date</th>
                                    <th class="text-nowrap">Date Diff</th>
                                    <th class="text-nowrap">Plan Pack</th>
                                    <th class="text-nowrap">FG Code</th>
                                    <th class="text-nowrap">FG Codeset</th>
                                    <th class="text-nowrap">Plan Q`ty (Pcs.)</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">Component</th>
                                    <th class="text-nowrap">Descriptions</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Remark</th>
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
    <?php require_once("../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){
        calldata()
    })

    function calldata(tbl = '#table_ffmc_report'){
        $.post('<?=$CFG->fol_ffmc_report?>/management', { protocol: 'CustomerOrder', start_date: $("#start_date").val(), end_date: $("#end_date").val(), doc_type: $("#doc_type").val() }, function(data){
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
                        { data: function(data){ return data.list }, className: 'text-nowrap text-center' },
                        { data: function(data){ return data.wf_order_ref }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_delivery_date).format('DD/MM/YYYY') }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_post_datetime).format('DD/MM/YYYY') }, className: 'text-nowrap' },
                        { data: function(data){
                            var act_date = data.create_datetime == null ? '' : moment(data.create_datetime).format('DD/MM/YYYY')
                            return act_date
                        }, className: 'text-nowrap' },
                        { data: function(data){
                            var date_diff = data.datediff == null ? '' : data.datediff + ' Day'
                            return date_diff
                        }, className: 'text-nowrap' },
                        { data: function(data){ return moment(data.wf_plan_pack).format('DD/MM/YYYY') ?? '' }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_fg_code }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_fg_codeset }, className: 'text-nowrap' },
                        { data: function(data){ return data.wf_plan_qty }, className: 'text-nowrap' },
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