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
                <h1 class="page-header mb-3">Receive Materials</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Receive Materials</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="alert alert-warning alert-dismissible rounded-0 mb-0 fade show">
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						กรุณาตรวจสอบรายการสั่งซื้อให้ถูกต้องทุกครั้งก่อนทำการรับรายการ หาก POs ไหนที่ทำรับหมดแล้วแต่ยังมีรายการค้างรับให้ทำการ Close PO ด้วยตนเอง (อาจเกิดจากการแก้ไขข้อมูลฝั่งจัดซื้อในเรื่องของจำนวนและราคาต่างๆ ทำให้จำนวนที่รอทำให้ยังปรากฎให้ทำรับได้อีก)
					</div>
					<div class="panel-body">
                        <table id="table-material-inbound" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap not-export-col">Actions / Details</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Type</th>
                                    <th class="text-nowrap">PO Number.</th>
                                    <th class="text-nowrap">Vendor Code</th>
                                    <th class="text-nowrap">Vendor Name</th>
                                    <th class="text-nowrap">Credit Term</th>
                                    <th class="text-nowrap">Total</th>
                                    <th class="text-nowrap">Vat</th>
                                    <th class="text-nowrap">Grand Total</th>
                                    <th class="text-nowrap">Remarks</th>
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
        calldata('#table-material-inbound')
    })

    function calldata(tbl, list = 0){
        $.post('<?=$CFG->fol_material_inbound?>/management', { protocol: 'POsDetails', type: 'POsList' }, function(data){
            try {
                const result = JSON.parse(data)
                console.log(result)
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
                        { data: function(data){ list++; return list; }, className: "text-nowrap text-center" },
                        { data: function(data){
                            return '<button onclick="OpenViewDetail(\'#load_view_detail\', \''+data.route+'\', \''+data.po_no+'\')" class="btn badge rounded bg-gradient-green text-dark fw-700 me-2" data-toggle="modal"><i class="fa-solid fa-receipt"></i> Receive Materials</button>'+
                                   '<button onclick="CloseReceive(\''+data['po_no']+'\')"  class="btn badge rounded bg-gradient-red text-dark fw-700" data-toggle="modal"><i class="fa-solid fa-ban"></i> Closed PO</button>'
                        }, className: "text-nowrap text-center" },
                        { data: function(data){ return '<span class="badge fw-600 bg-gradient-blue">Pending</span>' }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.post_type }, className: "text-nowrap font-weight-bold text-center" },
                        { data: function(data){ return data.po_no }, className: "text-nowrap font-weight-bold" },
                        { data: function(data){ return data.vendor_code }, className: "text-nowrap font-weight-bold" },
                        { data: function(data){ return data.vendor_name }, className: "text-nowrap font-weight-bold" },
                        { data: function(data){ return data.credit_term }, className: "text-nowrap font-weight-bold" },
                        { data: function(data){ return currency(data.total, { separator: ',', symbol: '' }).format() }, className: "text-nowrap font-weight-bold text-right" },
                        { data: function(data){ return currency(data.vat, { separator: ',', symbol: '' }).format() }, className: "text-nowrap font-weight-bold text-right" },
                        { data: function(data){ return currency(data.summary_budget, { separator: ',', symbol: '' }).format() }, className: "text-nowrap font-weight-bold text-right" },
                        { data: function(data){ return data.purchase_remark }, className: "text-nowrap font-weight-bold" },
                    ]
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }
</script>