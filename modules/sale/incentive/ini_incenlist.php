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
                <h1 class="page-header mb-3">Incentive Control</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Incentive Control</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="d-flex justify-content-start">
                            <div class="flex-item">
                                <small>Select Month</small>
                                <input type="month" id="inc_month" name="inc_month" class="form-control">
                            </div>
                            <div class="flex-item">
                                <small class="text-white">x</small><br>
                                <button onclick="ConfigIncentive()" class="btn bg-gradient-blue text-white ms-2 fw-700">Config</button>
                            </div>
                        </div>
                        <hr>
                        <table id="table_incenlist" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Details / Actions</th>
                                    <th class="text-nowrap">Status</th>
                                    <th class="text-nowrap">Period</th>
                                    <!-- <th class="text-nowrap">Year</th>
                                    <th class="text-nowrap">Month</th> -->
                                    <th class="text-nowrap">Revenue</th>
                                    <th class="text-nowrap">Amount Cost</th>
                                    <th class="text-nowrap">Margin</th>
                                    <th class="text-nowrap">Margin %</th>
                                    <th class="text-nowrap">Total CFF</th>
                                    <th class="text-nowrap">Total Incentive</th>
                                    <th class="text-nowrap">Attach file</th>
                                    <th class="text-nowrap">Create Datetime</th>
                                    <th class="text-nowrap">Create By</th>
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
        FilterData("#table_incenlist")
    })

    function ConfigIncentive(){
        const inc_month = $("#inc_month").val()

        if(inc_month == ''){
            SwalOnlyText('error', 'เลือกข้อมูลเดือนก่อนดำเนินการ!')
            return false
        }
        
        OpenViewDetail('#load_view_detail', 'load_incenlist', inc_month)
    }

    function FilterData(tbl){
        Swal.fire({
            title: 'กรุณารอสักครู่...',
            text: 'กำลังดำเนินการแสดงผลข้อมูล',
            imageUrl: 'https://lib.albatrosslogistic.com/assets/gif/ajax-loader.gif',
            showConfirmButton: false,
            showCancelButton: false,
            didOpen: () => {
                $.post('management', { protocol: 'IncenLists' }, function(data){
                    console.log('data is ' + data)
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
                                    return '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_incentive\', \''+data.inc_uniq+'\')" class="btn badge bg-gradient-orange fw-600 text-dark"><i class="fa-solid fa-pencil"></i> Revise</button>' +
                                           '<button onclick="OpenViewDetail(\'#load_view_detail\', \'load_incendet\', \''+data.inc_uniq+'\')" class="btn badge bg-gradient-blue fw-600 text-white ms-2"><i class="fa-solid fa-circle-info"></i> View Details</button>' +
                                           '<a href="https://lib.albatrosslogistic.com/print/document/mrp/print_sale_incentive?inc_uniq='+data.inc_uniq+'" target="_blank" class="btn badge bg-gradient-dark fw-600 text-white ms-2"><i class="fa-regular fa-file-excel"></i> Documents</a>'
                                }, className: "text-nowrap text-center" },
                                { data: function(data){ return '<span class="badge rounded fw-600 text-white '+data.class_color+'">'+data.inc_status+'</span>' }, className: "text-nowrap text-center" },
                                { data: function(data){ return data.inc_period }, className: "text-nowrap" },
                                // { data: function(data){ return data.inc_year }, className: "text-nowrap" },
                                // { data: function(data){ return data.inc_month }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_revenue, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_amount_cost, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_margin, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_margin_perc, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_total_cff, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return currency(data.inc_total_incentive, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap" },
                                { data: function(data){ return data.inc_attach_file }, className: "text-nowrap" },
                                { data: function(data){ return moment(data.inc_create_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap" },
                                { data: function(data){ return data.inc_create_by }, className: "text-nowrap" },
                                { data: function(data){ return data.inc_remarks }, className: "text-nowrap" },



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