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
                                            <td class="pt-0 pb-0"><input type="text" name="cff_amount[]"   value="<?=number_format($cfcResult['cff_total'], 2, '.', ',')?>" oninput="CalculateCFF()" class="form__field fw-600 text-center"></td>
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
                            <td class="pt-1 pb-1 bg-gray-200 fw-600 text-end">Summary Incentive</td>
                            <td class="pt-1 pb-1">
                                <table id="table_inv_details" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th nowrap>INV</th>
                                            <th nowrap>Customer</th>
                                            <th nowrap>Project</th>
                                            <th nowrap>Cost</th>
                                            <th nowrap>Selling</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $vlist = $db_con->prepare("SELECT * FROM tbl_inv_mst WHERE inv_inc_uniq = :inc_uniq");
                                            $vlist->bindParam(':inc_uniq', $inc_uniq);
                                            $vlist->execute();
                                            while($vResult = $vlist->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <td class="pt-0 pb-0"><input type="text" name="[]" value="<?=$vResult['inv_no']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="[]" value="<?=$vResult['inv_cus_code']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="[]" value="<?=$vResult['inv_project']?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="[]" value="<?=number_format($vResult['inv_cost_total'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" name="[]" value="<?=number_format($vResult['inv_total'], 2, '.', ',')?>" class="form__field fw-600 text-center" readonly></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Result INV</td>
                            <td class="pt-1 pb-1"><a href="../../../export/sale/incentive_summary_inv?inc_uniq=<?=$inc_uniq?>" target="_blank"><?=$fstResult['inc_attach_file']?></a></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Attach files</td>
                            <td class="pt-1 pb-1"><a href="https://lib.albatrosslogistic.com/attachfile/mrp-incentive/<?=$fstResult['inc_attach_file']?>" target="_blank"><?=$fstResult['inc_attach_file']?></a></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Summary .pdf</td>
                            <td class="pt-1 pb-1"><a href="https://lib.albatrosslogistic.com/print/document/mrp/print_sale_incentive?inc_uniq=<?=$fstResult['inc_uniq']?>" target="_blank">Incentive Period <?=$fstResult['inc_period']?>.pdf</a></td>
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
                    <button type="button" data-bs-dismiss="modal" class="btn btn-white fw-600 ms-5 ps-5 pe-5">Close</button>
                </center>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(data){
    
    })
</script>