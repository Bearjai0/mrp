<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../../session.php");
        require_once("../../../js_css_header.php");

        $ts_type = isset($_REQUEST['ts_type']) ? $_REQUEST['ts_type'] : '';
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
                <h1 class="page-header mb-3"><?=$ts_type?></h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Control <?=$ts_type?></h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <button onclick="OpenViewDetail('#load_view_detail', 'load_add_tooling')" class="btn bg-gradient-blue text-white fw-700"><i class="fa-solid fa-plus"></i> Add New Tool</button>
                        <button onclick="OpenViewDetail('#load_view_detail', 'load_upload_tooling')" class="btn bg-gradient-dark text-white ms-3 fw-700"><i class="fa-solid fa-cloud-arrow-up"></i> Upload new toolings</button>
                        <hr>
                        <table id="table_plate_list" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap not-export-col">Actions / Details</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Type</th>
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
        calldata('#table_plate_list')
    })

    function calldata(tbl, title = 'Tooling List', titleAttr = 'ToolingListAttribute'){
        $.post('<?=$CFG->fol_toollist?>/management', { protocol: 'ToolingList', type: '<?=$ts_type?>' }, function(data){
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
                        { extend: 'copy', text: 'Copy',  className: 'btn-sm', title: title, titleAttr: titleAttr, exportOptions: {  modifier: { page: 'all' }, columns: function (idx, data, th) { if ($(th).hasClass('not-export-col')) { return false } return table.column(idx).visible(); }, } },
                        { extend: 'csv',  text: 'CSV',   className: 'btn-sm', title: title, titleAttr: titleAttr, exportOptions: {  modifier: { page: 'all' }, columns: function (idx, data, th) { if ($(th).hasClass('not-export-col')) { return false } return table.column(idx).visible(); }, } },
                        { extend: 'excel',text: 'Excel', className: 'btn-sm', title: title, titleAttr: titleAttr, exportOptions: {  modifier: { page: 'all' }, columns: function (idx, data, th) { if ($(th).hasClass('not-export-col')) { return false } return table.column(idx).visible(); }, } },
                        { extend: 'pdf',  text: 'PDF',   className: 'btn-sm', title: title, titleAttr: titleAttr, exportOptions: {  modifier: { page: 'all' }, columns: function (idx, data, th) { if ($(th).hasClass('not-export-col')) { return false } return table.column(idx).visible(); }, } },
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
                            const disab = data.ts_status != 'Active' ? 'disabled' : ''
                            return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_withdraw_tooling\', \''+data.ts_uniq+'\')" '+disab+' class="btn btn-xs rounded bg-gradient-blue-indigo text-white fw-700" data-toggle="tooltip" data-placement="top" title="Withdraw Tooling"><i class="fa-solid fa-scroll"></i> Withdraw</button>' +
                                   '<a href="https://pur.albatrosslogistic.com/pur/dwg-quotation/'+data.ts_attach_file+'" target="_blank" class="btn btn-xs rounded bg-gradient-dark text-white fw-700 ms-2" data-toggle="tooltip" data-placement="top" title="View Drawing"><i class="fa-solid fa-compass-drafting"></i> DWG</a>' +
                                   '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_tooling_details\', \''+data.ts_uniq+'\')" class="btn btn-xs rounded bg-gradient-orange text-dark fw-700 ms-2" data-toggle="tooltip" data-placement="top" title="View plate die cut details"><i class="fa-solid fa-pencil"></i> Update</button>'
                        }, className: "text-nowrap" },
                        { data: function(data){ return '<span class="badge rounded '+data.class_color+' text-dark">'+data.ts_status+'</span>' }, className: "text-nowrap text-center fw-700" },
                        { data: function(data){ return data.ts_type }, className: "text-nowrap text-center" },
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
</script>