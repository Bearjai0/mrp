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
                <h1 class="page-header mb-3">Machine station transactions</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Machine station transactions</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="d-flex">
                            <div class="flex-item">
                                <small>Start plan date</small>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?=$buffer_date?>">
                            </div>
                            <div class="flex-item ms-2">
                                <small>End plan date</small>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?=$buffer_date?>">
                            </div>
                            <div class="flex-item ms-2">
                                <small class="text-white">x</small><br>
                                <button onclick="calldata()" class="btn bg-gradient-blue text-white w-100">Filter</button>
                            </div>
                        </div>
                        <hr>
                        <table id="table_station_transactions" class="table table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">Plan Date</th>
                                    <th class="text-nowrap">JOB NO.</th>
                                    <th class="text-nowrap">FG Code GDJ</th>
                                    <th class="text-nowrap">FG Descriptions</th>
                                    <th class="text-nowrap">Ft</th>
                                    <th class="text-nowrap">FG (Pcs.)</th>
                                    <th class="text-nowrap">Ft<sup>2</sup></th>
                                    <th class="text-nowrap">NG (Pcs.)</th>
                                    <th class="text-nowrap">Ft<sup>2</sup></th>
                                    <th class="text-nowrap">Machine Name</th>
                                    <th class="text-nowrap">Start Setup</th>
                                    <th class="text-nowrap">Finish Setup</th>
                                    <th class="text-nowrap">Total Setup</th>
                                    <th class="text-nowrap">Start Production</th>
                                    <th class="text-nowrap">Finish Production</th>
                                    <th class="text-nowrap">Total Production</th>
                                    <th class="text-nowrap">Idle Time</th>
                                    <th class="text-nowrap">Remarks</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

    function calldata(tbl = '#table_station_transactions'){
        $.post('<?=$CFG->fol_rep_shipping?>/management', { protocol: 'StationTransactionList', start_date: $("#start_date").val(), end_date: $("#end_date").val() }, function(data){
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

                var spinnerContainer = $("#spinnerContainer");
                spinnerContainer.show();

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
                        { data: function(data){ return data.list }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.job_plan_date).format('DD/MM/YYYY') }, className: "text-nowrap" },
                        { data: function(data){ return data.pass_job_no }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_code }, className: "text-nowrap" },
                        { data: function(data){ return data.job_fg_description }, className: "text-nowrap" },
                        { data: function(data){ return '' }, className: "text-nowrap" },
                        { data: function(data){ return currency(data.pass_fg, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.pass_fg_ft2, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.pass_ng, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return currency(data.pass_ng_ft2, { seperator: ',', symbol: '' }).format() }, className: "text-nowrap text-right" },
                        { data: function(data){ return data.machine_name_en }, className: "text-nowrap" },
                        { data: function(data){ return data.ope_setting_start_datetime ? moment(data.ope_setting_start_datetime).format('DD/MM/YYYY HH:mm') : '' }, className: "text-nowrap" },
                        { data: function(data){ return data.ope_setting_end_datetime ? moment(data.ope_setting_end_datetime).format('DD/MM/YYYY HH:mm') : '' }, className: "text-nowrap" },
                        { data: function(data){ return data.settingDiff }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.pass_start_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap" },
                        { data: function(data){ return moment(data.pass_end_datetime).format('DD/MM/YYYY HH:mm') }, className: "text-nowrap" },
                        { data: function(data){ return data.dateDiff }, className: "text-nowrap text-right" },
                        { data: function(data){ return '' }, className: "text-nowrap" },
                        { data: function(data){ return '' }, className: "text-nowrap" },
                    ],
                    initComplete: function(settings, json) {
                        spinnerContainer.hide();
                    }
                }).draw(false)
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    }
</script>