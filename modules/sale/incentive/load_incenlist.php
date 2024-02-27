<?php
    require_once("../../../session.php");

    $inc_month = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';
?>
<form id="_add_tooling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Incentive Management</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <small class="fw-600">Month / Year</small>
                <input type="text" id="inc_month" name="inc_month" value="<?=date('F Y', strtotime($inc_month))?>" class="form-control" data-parsley-required="true" readonly>
                
                <h4 class="mb-0 mt-3">Revenue <?=date('F Y', strtotime($inc_month))?> of B2B</h4>
                <table id="tbl_revenue_details" class="table table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Revenue</th>
                            <th>Amount Cost 24%</th>
                            <th>Margin (Bath)</th>
                            <th>Margin %</th>
                        </tr>
                    </thead>
                </table>
                
                <h4 class="mb-0 mt-4">Summary sale incentive</h4>
                <table id="tbl_incetive_sale_list" class="table table-bordered" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Position</th>
                            <th>Sale Name</th>
                            <th>Incentive Rate</th>
                            <th>Total Incentive</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(data){
        GetRevenue()
    })


    function GetRevenue(){
        $.post('<?=$CFG->fol_sale_incentive?>/management', { protocol: 'GetRevenue', inc_month: '<?=$inc_month?>' }, function(data){
            try {
                const result = JSON.parse(data)
                console.log(result)

                $('#tbl_revenue_details').DataTable({
                    // scrollX   : true,
                    searching : false,
                    paging    : false,
                    ordering  : false,
                    info      : false,
                    bDestroy  : true,
                    data      : result.datas,
                    columns : [
                        { data: function(data){ return data.GroupCustomer }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.revenue, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.cost, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.margin, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(((data.revenue - data.cost) / data.cost) * 100, { seperator: ',', symbol: '', precision: 2 }).format() + '%' }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)

                $('#tbl_incetive_sale_list').DataTable({
                    searching : false,
                    paging    : false,
                    ordering  : false,
                    info      : false,
                    bDestroy  : true,
                    data      : result.prilla,
                    columns : [
                        { data: function(data){ return data.list }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.user_position }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.user_name_en }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.inc_rate, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-center" },
                        { data: function(data){ return currency(data.inc_total, { seperator: ',', symbol: '', precision: 2 }).format() }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถแสดงข้อมูล Revenue ได้ ' + e.message())
            }
        })
    }
</script>