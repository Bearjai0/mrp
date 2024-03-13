<?php
    require_once("../../../session.php");

    $inc_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq");
    $fst->bindParam(':inc_uniq', $inc_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);

    $indet = $db_con->prepare("SELECT * FROM tbl_sale_incentive_detail WHERE det_inc_uniq = :inc_uniq AND det_inc_rev = :inc_rev ORDER BY det_uniq");
    $indet->bindParam(':inc_uniq', $inc_uniq);
    $indet->bindParam(':inc_rev', $fstResult['inc_rev']);
    $indet->execute();
    $indetResult = $indet->fetchAll(PDO::FETCH_ASSOC);

?>
<form id="_incenlist" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 95%;">
            <div class="modal-header">
                <h4 class="modal-title">Incentive of <?=date('F Y', strtotime($fstResult['inc_period']))?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th nowrap class="bg-gradient-black text-white text-center" width="20%">Field Name</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="80%">Field Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end text-end">Incentive Month :</td>
                            <td class="pt-1 pb-1"><input type="text" id="inc_period" name="inc_period" class="form__field p-0" value="<?=date('F Y', strtotime($fstResult['inc_period']))?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end">Choose income file :</td>
                            <td class="pt-1 pb-1">
                                <input type="file" id="upfile" name="upfile" class="form__field p-0" data-parsley-required="true">
                                <button type="button" id="confirmUpfile" class="btn badge bg-gradient-blue text-white">Confirm</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end"><input type="text" name="det_details[]" value="<?=$indetResult[0]['det_details']?>" class="form__field text-end" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Amount Cost 24%</th>
                                            <th nowrap>Margin (baht)</th>
                                            <th nowrap>Margin %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td nowrap class="p-0"><input type="text" id="all_revenue" name="all_revenue[]" value="<?=number_format($indetResult[0]['det_revenue'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="all_amount_cost" name="all_amount_cost[]" value="<?=number_format($indetResult[0]['det_amount_cost'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="all_margin_baht" name="all_margin_baht[]" value="<?=number_format($indetResult[0]['det_margin'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="all_margin_percent" name="all_margin_percent[]" value="<?=number_format($indetResult[0]['det_margin_perc'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end"><input type="text" name="det_details[]" value="<?=$indetResult[1]['det_details']?>" class="form__field text-end" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Amount Cost 24%</th>
                                            <th nowrap>Margin (baht)</th>
                                            <th nowrap>Margin %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td nowrap class="p-0"><input type="text" id="not_include_revenue" name="all_revenue[]" value="<?=number_format($indetResult[1]['det_revenue'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="not_include_amount_cost" name="all_amount_cost[]" value="<?=number_format($indetResult[1]['det_amount_cost'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="not_include_margin_baht" name="all_margin_baht[]" value="<?=number_format($indetResult[1]['det_margin'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="not_include_margin_percent" name="all_margin_percent[]" value="<?=number_format($indetResult[1]['det_margin_perc'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end"><input type="text" name="det_details[]" value="<?=$indetResult[2]['det_details']?>" class="form__field text-end" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Amount Cost 24%</th>
                                            <th nowrap>Margin (baht)</th>
                                            <th nowrap>Margin %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td nowrap class="p-0"><input type="text" id="no_cff_revenue" name="all_revenue[]" value="<?=number_format($indetResult[2]['det_revenue'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="no_cff_amount_cost" name="all_amount_cost[]" value="<?=number_format($indetResult[2]['det_amount_cost'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="no_cff_margin_baht" name="all_margin_baht[]" value="<?=number_format($indetResult[2]['det_margin'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="no_cff_margin_percent" name="all_margin_percent[]" value="<?=number_format($indetResult[2]['det_margin_perc'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end"><input type="text" name="det_details[]" value="<?=$indetResult[3]['det_details']?>" class="form__field text-end" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Amount Cost 24%</th>
                                            <th nowrap>Margin (baht)</th>
                                            <th nowrap>Margin %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td nowrap class="p-0"><input type="text" id="cff_revenue" name="all_revenue[]" value="<?=number_format($indetResult[3]['det_revenue'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="cff_amount_cost" name="all_amount_cost[]" value="<?=number_format($indetResult[3]['det_amount_cost'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="cff_margin_baht" name="all_margin_baht[]" value="<?=number_format($indetResult[3]['det_margin'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="cff_margin_percent" name="all_margin_percent[]" value="<?=number_format($indetResult[3]['det_margin_perc'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end">CFF Customer</td>
                            <td class="pt-1 pb-1">
                                <table id="table_cff_list" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Customer</th>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>CFF Ratio</th>
                                            <th nowrap>CFF(Baht)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cfc = $db_con->prepare("SELECT * FROM tbl_sale_incentive_cff WHERE cff_inc_uniq = :inc_uniq AND cff_inc_rev = :inc_rev ORDER BY cff_uniq");
                                            $cfc->bindParam(':inc_uniq', $inc_uniq);
                                            $cfc->bindParam(':inc_rev', $fstResult['inc_rev']);
                                            $cfc->execute();
                                            while($cfcResult = $cfc->fetch(PDO::FETCH_ASSOC)):
                                        ?>

                                        <tr>
                                            <td class="pt-0 pb-0"><input type="text" name="cff_cus_code[]" value="<?=$cfcResult['cff_cus_code']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="cff_revenue[]"  value="<?=number_format($cfcResult['cff_revenue'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="cff_ratio[]"    value="<?=number_format($cfcResult['cff_ratio'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="cff_amount[]"   value="<?=number_format($cfcResult['cff_amount'], 2, '.', ',')?>" oninput="CalculateCFF()" class="form__field fw-600 text-center"></td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <tr>
                                            <td class="pt-0 pb-0" colspan="3"><input type="text" value="Total" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" id="cff_grand_total" name="cff_grand_total" value="<?=$fstResult['inc_total_cff']?>" class="form__field fw-600 text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end"><input type="text" name="det_details[]" value="<?=$indetResult[4]['det_details']?>" class="form__field text-end" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Amount Cost 24%</th>
                                            <th nowrap>Margin (baht)</th>
                                            <th nowrap>Margin %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td nowrap class="p-0"><input type="text" id="final_cff_revenue" name="all_revenue[]" value="<?=number_format($indetResult[4]['det_revenue'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="final_cff_amount_cost" name="all_amount_cost[]" value="<?=number_format($indetResult[4]['det_amount_cost'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="final_cff_margin_baht" name="all_margin_baht[]" value="<?=number_format($indetResult[4]['det_margin'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                            <td nowrap class="p-0"><input type="text" id="final_cff_margin_percent" name="all_margin_percent[]" value="<?=number_format($indetResult[4]['det_margin_perc'], 2, '.', ',')?>" class="form__field text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end">Summary Incentive</td>
                            <td class="pt-1 pb-1">
                                <table id="table_final_incentive" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>Employee ID</th>
                                            <th nowrap>Employee Name</th>
                                            <th nowrap>Position</th>
                                            <th nowrap>Revenue</th>
                                            <th nowrap>Rate</th>
                                            <th nowrap>Incentive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $slist = $db_con->prepare("SELECT * FROM tbl_sale_incentive_list WHERE list_inc_uniq = :inc_uniq AND list_inc_rev = :inc_rev ORDER BY list_uniq");
                                            $slist->bindParam(':inc_uniq', $inc_uniq);
                                            $slist->bindParam(':inc_rev', $fstResult['inc_rev']);
                                            $slist->execute();
                                            while($slistResult = $slist->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_user_code[]" value="<?=$slistResult['list_user_code']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_user_name[]" value="<?=$slistResult['list_user_name']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_position[]"  value="<?=$slistResult['list_position']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_revenue[]"   value="<?=number_format($slistResult['list_revenue'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_rate[]"      value="<?=number_format($slistResult['list_rate'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="fn_incentive[]" value="<?=number_format($slistResult['list_total'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <tr>
                                            <td class="pt-0 pb-0" colspan="5"></td>
                                            <td class="pt-0 pb-0"><input type="text" id="inc_total_incentive" name="inc_total_incentive" value="<?=number_format($fstResult['inc_total_incentive'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end">Remarks</td>
                            <td class="pt-1 pb-1">
                                <textarea id="inc_remarks" name="inc_remarks" class="form__field" style="height: 11em;" data-parsley-required="true"><?=$fstResult['inc_remarks']?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <center>
                    <button type="submit" class="btn bg-gradient-blue-indigo fw-600 text-white ps-5 pe-5">Confirm</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-white fw-600 ms-5 ps-5 pe-5">Close</button>
                </center>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(data){
    
    })

    $("#confirmUpfile").click(function(){
        var upfile = $("#upfile")[0].files[0]
        var formData = new FormData()
        formData.append('upfile', upfile)
        formData.append('protocol', 'MatchFile')

        $.ajax({
            url: 'management',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                const result = JSON.parse(resp)

                $("#all_revenue").val(currency(result.datas.revenue, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#all_amount_cost").val(currency(result.datas.cost_total, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#all_margin_baht").val(currency(result.datas.margin_a, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#all_margin_percent").val(currency(result.datas.margin_b * 100, { seperator: ',', symbol: '', precision: 2 }).format())

                $("#not_include_revenue").val(currency(result.datas_not_include.revenue, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#not_include_amount_cost").val(currency(result.datas_not_include.cost_total, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#not_include_margin_baht").val(currency(result.datas_not_include.margin_a, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#not_include_margin_percent").val(currency(result.datas_not_include.margin_b * 100, { seperator: ',', symbol: '', precision: 2 }).format())

                $("#no_cff_revenue").val(currency(result.datas_ex_no_cff.revenue, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#no_cff_amount_cost").val(currency(result.datas_ex_no_cff.cost_total, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#no_cff_margin_baht").val(currency(result.datas_ex_no_cff.margin_a, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#no_cff_margin_percent").val(currency(result.datas_ex_no_cff.margin_b * 100, { seperator: ',', symbol: '', precision: 2 }).format())

                $("#cff_revenue").val(currency(result.datas_ex_cff.revenue, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#cff_amount_cost").val(currency(result.datas_ex_cff.cost_total, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#cff_margin_baht").val(currency(result.datas_ex_cff.margin_a, { seperator: ',', symbol: '', precision: 2 }).format())
                $("#cff_margin_percent").val(currency(result.datas_ex_cff.margin_b * 100 , { seperator: ',', symbol: '', precision: 2 }).format())

                var table = $("#table_cff_list")
                var tableBody = $("<tbody></tbody>")
                $("#table_cff_list tbody").empty()

                $.each(result.cus_cff_details, function(id, item){
                    var row = $("<tr></tr>")
                    row.append('<td class="pt-0 pb-0"><input type="text" name="cff_cus_code[]" value="'+item.cus_code+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="cff_revenue[]"  value="'+item.revenue+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="cff_ratio[]"    value="'+(item.cff_ratio * 100)+'" class="form__field fw-600 text-center"></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="cff_amount[]" oninput="CalculateCFF()" value="0" class="form__field fw-600 text-center"></td>')
                    tableBody.append(row)
                })
                var row = $("<tr></tr>")
                row.append('<td class="pt-0 pb-0" colspan="3"><input type="text" value="Total" class="form__field fw-600 text-center" readonly></td>')
                row.append('<td class="pt-0 pb-0"><input type="text" id="cff_grand_total" name="cff_grand_total" value="0" class="form__field fw-600 text-center"></td>')
                tableBody.append(row)

                table.append(tableBody)

                CalculateFinalCFF()
            }, error: function(xhr, status, error) {
                console.log('failed on upload')
            }
        })
    })

    function CalculateCFF(){
        var sum = 0;
        $('input[name="cff_amount[]"]').each(function() {
            sum += parseFloat($(this).val())
        });

        $("#cff_grand_total").val(sum)

        CalculateFinalCFF()
    }

    function CalculateFinalCFF(){
        var cff_revenue = $("#cff_revenue").val().replaceAll(',','')
        var cff_amount_cost = $("#cff_amount_cost").val().replaceAll(',','')
        var cff_grand_total = $("#cff_grand_total").val().replaceAll(',','')
        var final_cff_amount = parseFloat(cff_amount_cost) + parseFloat(cff_grand_total)
        var final_margin = cff_revenue - final_cff_amount

        $("#final_cff_revenue").val(currency(cff_revenue, { seperator: ',', symbol: '', precision: 2 }).format())
        $("#final_cff_amount_cost").val(currency(final_cff_amount, { seperator: ',', symbol: '', precision: 2 }).format())
        $("#final_cff_margin_baht").val(currency(final_margin, { seperator: ',', symbol: '', precision: 2 }).format())
        $("#final_cff_margin_percent").val(currency(((cff_revenue - final_cff_amount) / cff_revenue) * 100, { seperator: ',', symbol: '', precision: 2 }).format())

        CalculateFinalIncentive()
    }

    function CalculateFinalIncentive(){
        var not_include_revenue = $("#not_include_revenue").val().replaceAll(',','')
        var not_include_margin_percent = $("#not_include_margin_percent").val().replaceAll(',','')

        var no_cff_revenue = $("#no_cff_revenue").val().replaceAll(',','')
        var no_cff_margin_percent = $("#no_cff_margin_percent").val().replaceAll(',','')

        var final_cff_revenue = $("#final_cff_revenue").val().replaceAll(',','')
        var final_cff_margin_percent = $("#final_cff_margin_percent").val().replaceAll(',','')

        $.post('management', {
            protocol: 'CalculateFinalIncentive',
            not_include_revenue: not_include_revenue,
            not_include_margin_percent: not_include_margin_percent,
            no_cff_revenue: no_cff_revenue,
            no_cff_margin_percent: no_cff_margin_percent,
            final_cff_revenue: final_cff_revenue,
            final_cff_margin_percent: final_cff_margin_percent
        }, function(data){
            try {
                $('#loading').hide();
                const result = JSON.parse(data)

                var summary = 0
                var table = $("#table_final_incentive")
                var tableBody = $("<tbody></tbody>")
                $("#table_final_incentive tbody").empty()

                $.each(result.datas, function(id, item){
                    summary += item.incentive
                    var row = $("<tr></tr>")
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_user_code[]" value="'+item.user_code+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_user_name[]" value="'+item.user_name+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_position[]" value="'+item.position+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_revenue[]"  value="'+currency(item.revenue, {seperator: ',', symbol: '', precision: 2}).format()+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_rate[]"     value="'+item.tiv_rate+'" class="form__field fw-600 text-center" readonly></td>')
                    row.append('<td class="pt-0 pb-0"><input type="text" name="fn_incentive[]" value="'+currency(item.incentive, {seperator: ',', symbol: '', precision: 2}).format()+'" class="form__field fw-600 text-center" readonly></td>')
                    tableBody.append(row)
                })

                var row = $("<tr></tr>")
                row.append('<td class="pt-0 pb-0" colspan="5"></td>')
                row.append('<td class="pt-0 pb-0"><input type="text" id="inc_total_incentive" name="inc_total_incentive" value="'+currency(summary, {seperator: ',', symbol: '', precision: 2}).format()+'" class="form__field fw-600 text-center" readonly></td>')
                tableBody.append(row)

                table.append(tableBody)
            } catch(e) {

            }
        })
    }

    $("#_incenlist").submit(function(e){
        e.preventDefault()
        var form = $(this)
        var formData = new FormData($(this)[0])

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการอัพเดทข้อมูล Incentive สำหรับ Period ' + $("#inc_month").val() + 'หรือไม่?',
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
                        formData.append('protocol', 'UpdateIncentive')
                        formData.append('inc_uniq', '<?=$fstResult['inc_uniq']?>')

                        $.ajax({
                            method: "POST",
                            url: "management",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                console.log(data)
                                try {
                                    const result = JSON.parse(data)
                                    if(result.code == 200){
                                        SwalReload('success', 'ดำเนินการสำเร็จ', result.message, result.route)
                                    }else{
                                        SwalOnlyText('warning', result.message)
                                    }
                                } catch (err) {
                                    SwalOnlyText('error', 'ไม่สามารถประมวลได้ ' + err)
                                }
                            }, error: function(err){
                                SwalOnlyText('error', 'ไม่สามารถประมวลได้ ' + err)
                            }
                        })
                    }
                })
            }
        })
    })
</script>