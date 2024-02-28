<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../../../session.php");
        require_once("../../../../js_css_header.php");
    ?>
    <body>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('../../../../navbar.php');
                require_once("../../../../menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            <div id="content" class="app-content">
                <h1 class="page-header mb-3">Master BOM SET</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Master BOM</h4>
						<?php require_once("../../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <table id="table_bom_set" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Details / Actions</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Set Code</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Project</th>
                                    <th class="text-nowrap">Component Code</th>
                                    <th class="text-nowrap">Part Customer</th>
                                    <th class="text-nowrap">CTN Code Normal</th>
                                    <th class="text-nowrap">Set Description</th>
                                    <th class="text-nowrap">DWG Code</th>
                                    <th class="text-nowrap">FG Ft<sup>2</sup></th>
                                    <th class="text-nowrap">Cost RM</th>
                                    <th class="text-nowrap">Cost DL</th>
                                    <th class="text-nowrap">Cost OH</th>
                                    <th class="text-nowrap">Cost Total</th>
                                    <th class="text-nowrap">Cost Total OH</th>
                                    <th class="text-nowrap">Selling Price</th>
                                    <th class="text-nowrap">Production Time</th>
                                    <th class="text-nowrap">SNP</th>
                                    <th class="text-nowrap">MOQ</th>
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
    <?php require_once("../../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>

<script type="text/javascript">
    $(document).ready(function(){
        FilterData('#table_bom_set')
    })

    function FilterData(tbl){
        Swal.fire({
            title: 'กรุณารอสักครู่...',
            text: 'กำลังดำเนินการแสดงผลข้อมูล',
            imageUrl: 'lib.albatrosslogistic.com/assets/gif/ajax-loader.gif',
            showConfirmButton: false,
            showCancelButton: false,
            didOpen: () => {
                $.post('<?=$CFG->func_bom_master?>/management', { protocol: 'MasterBOMSet' }, function(data){
                    try {
                        var result = JSON.parse(data)

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
                                { data: function(data){ return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_set_details\', \''+data.set_code+'\')" class="btn badge bg-gradient-blue fw-600 text-white"><i class="fa-solid fa-pencil"></i> View Set</button>' }, className: "text-nowrap text-center" },
                                { data: function(data){ return '<span class="badge rounded text-white '+data.class_color+'">'+data.set_status+'</span>' }, className: "text-nowrap" },
                                { data: function(data){ return data.set_code }, className: "text-nowrap" },
                                { data: function(data){ return data.set_cus_code }, className: "text-nowrap" },
                                { data: function(data){ return data.set_project }, className: "text-nowrap" },
                                { data: function(data){ return data.set_comp_code }, className: "text-nowrap" },
                                { data: function(data){ return data.set_part_customer }, className: "text-nowrap" },
                                { data: function(data){ return data.set_ctn_code_normal }, className: "text-nowrap" },
                                { data: function(data){ return data.set_description }, className: "text-nowrap" },
                                { data: function(data){ return data.set_dwg_code }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_ft2, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_cost_rm, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_cost_dl, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_cost_oh, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_cost_total, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_cost_total_oh, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_selling_price, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.set_prod_time, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return data.set_snp }, className: "text-nowrap" },
                                { data: function(data){ return data.set_moq }, className: "text-nowrap" },
                                { data: function(data){ return data.set_remarks }, className: "text-nowrap" },
                                { data: function(data){ return moment(data.set_update_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap" },
                                { data: function(data){ return data.set_update_by }, className: "text-nowrap" },
                            ]
                        }).draw(false)
                    } catch(err) {
                        SwalOnlyText('warning', 'ไม่สามารถดำเนินการได้ ' + err.message)
                    }

                    Swal.fire({
                        icon: 'success',
                        text: 'แสดงผลข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 1000,
                    })
                })
            }
        })
    }
</script>