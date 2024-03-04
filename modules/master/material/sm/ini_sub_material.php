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
                <h1 class="page-header mb-3">Sub Materials</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Sub Materials</h4>
						<?php require_once("../../../../comp_ui/panel-header.php"); ?>
					</div>
                    <div class="panel-body">
                        <button onclick="OpenViewDetail('#load_view_detail', 'load_sm_details')" class="btn bg-gradient-blue text-white fw-600"><i class="fa-solid fa-plus"></i> New sub material</button>
                        <hr>
                        <table id="table_sub_material" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Details / Actions</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">SM Code</th>
                                    <th class="text-nowrap">Type name(EN)</th>
                                    <th class="text-nowrap">Type name(TH)</th>
                                    <th class="text-nowrap">Material Name</th>
                                    <th class="text-nowrap">Stock Q`ty</th>
                                    <th class="text-nowrap">Unit Rate</th>
                                    <th class="text-nowrap">Unit</th>
                                    <th class="text-nowrap">Stock Min</th>
                                    <th class="text-nowrap">Stock Max</th>
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
        FilterData('#table_sub_material')
    })

    function FilterData(tbl){
        Swal.fire({
            title: 'กรุณารอสักครู่...',
            text: 'กำลังดำเนินการแสดงผลข้อมูล',
            imageUrl: 'https://lib.albatrosslogistic.com/assets/gif/ajax-loader.gif',
            showConfirmButton: false,
            showCancelButton: false,
            didOpen: () => {
                $.post('<?=$CFG->func_material_sm?>/management', { protocol: 'ListSubMaterial' }, function(data){
                    console.log(data)
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

                        var table = $(tbl, i = 0).DataTable({
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
                                { data: function(data){ i++; return i }, className: "text-nowrap text-center" },
                                { data: function(data){
                                    return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_sm_details\', \''+data.sm_code+'\')" class="btn badge bg-gradient-blue fw-600 text-white"><i class="fa-solid fa-pencil"></i> Update</button>'
                                }, className: "text-nowrap text-center" },
                                { data: function(data){ return '<span class="badge rounded fw-600 text-white '+data.class_color+'">'+data.sm_status+'</span>' }, className: "text-nowrap text-center" },
                                { data: function(data){ return data.sm_code }, className: "text-nowrap" },
                                { data: function(data){ return data.sm_type }, className: "text-nowrap" },
                                { data: function(data){ return data.ref_name }, className: "text-nowrap" },
                                { data: function(data){ return data.ref_name + ' ' + data.sm_name }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.stock_qty, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.sub_unit_rate, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return data.sub_unit_type }, className: "text-nowrap" },
                                { data: function(data){ return data.sm_min }, className: "text-nowrap" },
                                { data: function(data){ return data.sm_max }, className: "text-nowrap" },
                                { data: function(data){ return moment(data.post_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap" },
                                { data: function(data){ return data.post_by }, className: "text-nowrap" },
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