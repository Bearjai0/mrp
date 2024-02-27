<?php
    require_once("../../../session.php");

    $job_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare(
        "SELECT A.job_ref, A.job_delivery_date, A.job_ffmc_conf_qty, A.job_conf_qty,
                B.fg_code, B.fg_codeset, B.part_customer, B.comp_code, B.cus_code, B.project,
                B.fg_perpage, B.pd_usage, B.rm_code, B.machine_order, B.machine_mp, B.moq,
                (SELECT COALESCE(SUM(pallet_stock_qty),0) FROM tbl_fg_inven_mst WHERE pallet_fg_code = A.job_bom_id) AS stock_qty
         FROM tbl_job_detail AS A
         LEFT JOIN tbl_bom_mst AS B ON A.job_bom_id = B.bom_uniq
         WHERE A.job_uniq = :job_uniq"
    );
    $fst->bindParam(':job_uniq', $job_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_create_new_plan" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Planning Card</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Order Ref# :</td>
                            <td class="pt-1 pb-1"><input type="text" id="job_ref" name="job_ref" class="form__field p-0" value="<?=$fstResult['job_ref']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Codeset :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_codeset" name="fg_codeset" class="form__field p-0" value="<?=$fstResult['fg_codeset']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code" class="form__field p-0" value="<?=$fstResult['fg_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Part Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer" class="form__field p-0" value="<?=$fstResult['part_customer']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Component Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="comp_code" name="comp_code" class="form__field p-0" value="<?=$fstResult['comp_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cus_code" name="cus_code" class="form__field p-0" value="<?=$fstResult['cus_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project" name="project" class="form__field p-0" value="<?=$fstResult['project']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Delivery Date :</td>
                            <td class="pt-1 pb-1"><input type="text" id="delivery_date" name="delivery_date" class="form__field p-0" value="<?=$fstResult['job_delivery_date']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Plan Date <span class="text-red">**</span> :</td>
                            <td class="pt-1 pb-1"><input type="date" id="job_plan_date" name="job_plan_date" class="form__field p-0 text-blue" style="border-bottom: dashed 1px #0088cc;" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Usage Details</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG in Stock(Pcs.) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="stock_fg_qty" name="stock_fg_qty" value="<?=$fstResult['stock_qty']?>" class="form__field p-0" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">MOQ :</td>
                            <td class="pt-1 pb-1"><input type="text" id="moq" name="moq" class="form__field p-0" value="<?=$fstResult['moq']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG / Page :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_perpage" name="fg_perpage" onfocusout="SetRMUsage()" class="form__field p-0 text-blue" class="form__field p-0" value="<?=$fstResult['fg_perpage']?>" style="border-bottom: dashed 1px #0088cc;" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">PD Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="pd_usage" name="pd_usage" onfocusout="SetRMUsage()" class="form__field p-0 text-blue" class="form__field p-0" value="<?=$fstResult['pd_usage']?>" style="border-bottom: dashed 1px #0088cc;" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Order(SET) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ffmc_quantity" name="ffmc_quantity" onfocusout="SetRMUsage()" class="form__field p-0 text-blue" value="<?=number_format($fstResult['job_ffmc_conf_qty'], 0)?>" style="border-bottom: dashed 1px #0088cc;" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Order(Pcs.) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="conf_quantity" name="conf_quantity" class="form__field p-0 text-orange-600" style="border-bottom: dashed 1px #CC7700;" value="<?=number_format($fstResult['job_conf_qty'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Material Use :</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Material Code :</td>
                            <td class="p-0">
                                <select id="rm_code" name="rm_code" class="form__field p-0 fw-600" data-sp-change="RMPicking()" data-parsley-required="true" data-live-search="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $rmList = $db_con->query("SELECT raw_code FROM tbl_stock_inven_mst WHERE product_type = 'RAW MAT' AND stock_qty > 0 GROUP BY raw_code ORDER BY raw_code");
                                        while($rmListResult = $rmList->fetch(PDO::FETCH_ASSOC)){
                                            $sect = $rmListResult['raw_code'] == $fstResult['rm_code'] ? 'selected' : '';
                                            echo '<option class="fw-600" value="'.$rmListResult['raw_code'].'" '.$sect.'>'.$rmListResult['raw_code'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Material Type :</td>
                            <td class="p-0">
                                <select id="rm_type" name="rm_type" class="form__field p-0" data-live-search="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $rmList = $db_con->query("SELECT rm_type FROM tbl_stock_inven_mst WHERE product_type = 'RAW MAT' AND stock_qty > 0 AND rm_type != '' AND rm_type IS NOT NULL GROUP BY rm_type ORDER BY rm_type");
                                        while($rmListResult = $rmList->fetch(PDO::FETCH_ASSOC)){
                                            echo '<option class="fw-600" value="'.$rmListResult['rm_type'].'">'.$rmListResult['rm_type'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Paper Spec :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_spec" name="rm_spec" class="form__field p-0 text-orange-600" style="border-bottom: dashed 1px #CC7700;" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Flute :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_flute" name="rm_flute" class="form__field p-0 text-orange-600" style="border-bottom: dashed 1px #CC7700;" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Stock RM(Pcs.) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_stock_qty" name="rm_stock_qty" class="form__field p-0 text-orange-600" style="border-bottom: dashed 1px #CC7700;" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Usage(Pcs.) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_usage_qty" name="rm_usage_qty" class="form__field p-0 text-orange-600" style="border-bottom: dashed 1px #CC7700;" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="p-0" colspan="2">
                                <table id="table_reserve_pallet" class="table table-bordered table-striped" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap text-center" width="2%">#</th>
                                            <th class="text-nowrap text-center" width="15%">Pallet ID</th>
                                            <th class="text-nowrap text-center" width="40%">Descriptions</th>
                                            <th class="text-nowrap text-center" width="">Locations</th>
                                            <th class="text-nowrap text-center" width="10%">Q`TY</th>
                                            <th class="text-nowrap text-center" width="">Type</th>
                                            <th class="text-nowrap text-center" width="">Color</th>
                                        </tr>
                                    </thead>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Machine Management</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Machine List :</td>
                            <td class="pt-1 pb-1">
                                <table class="table table-bordered table-striped" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <td nowrap>#</td>
                                            <td nowrap>Machine</td>
                                            <td nowrap>In</td>
                                            <td nowrap>Out</td>
                                            <td nowrap>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-600">
                                        <tr>
                                            <td class="pt-1 pb-0 text-center">1</td>
                                            <td class="pt-1 pb-0">Cutting Machine M/C</td>
                                            <td class="pt-0 pb-0"><input type="text" id="ct_in" name="ct_in" class="form__field text-center pt-1 pb-1" value="1" min="1" data-parsley-required="true" readonly></td>
                                            <td class="pt-0 pb-0"><input type="text" id="ct_out" name="ct_out" class="form__field text-center pt-1 pb-1" style="border-bottom: dashed 1px #0088cc;" value="1" min="1" data-parsley-required="true"></td>
                                            <td class="pt-0 pb-0 text-center"><input type="checkbox" id="ct_status" name="ct_status" data-render="switchery" data-theme="blue" /></td>
                                        </tr>
                                        <?php foreach(json_decode($fstResult['machine_order'], TRUE) as $id => $item): ?>
                                            <tr>
                                                <td class="pt-1 pb-1 text-center"><?=($id+2)?></td>
                                                <td class="pt-1 pb-1">
                                                    <?php
                                                        $mlist = $db_con->prepare("SELECT machine_type_name FROM tbl_machine_type_mst WHERE machine_type_code = :machine_type");
                                                        $mlist->bindParam(':machine_type', $item['machine_code']);
                                                        $mlist->execute();
                                                        $mlistResult = $mlist->fetch(PDO::FETCH_ASSOC);
                                                        echo $mlistResult['machine_type_name'];
                                                    ?>
                                                </td>
                                                <td class="pt-1 pb-1 text-center"><?=$item['in']?></td>
                                                <td class="pt-1 pb-1 text-center"><?=$item['out']?></td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Card note **</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Remarks <span class="text-red">(** กรุณากรอกข้อมูลทุกครั้งหากท่านออกแผนการผลิตต่ำกว่า MOQ)</span></td>
                            <td class="p-0">
                                <textarea id="remarks" name="remarks" class="form-control m-0" style="height: 9em;"></textarea>
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
    $(document).ready(function(){
        $("#rm_code, #rm_type").picker({ search: true })
        SyncRMDetails()

        if ($("[data-render='switchery']").length !== 0) {
            $("[data-render='switchery']").each(function() {
                var themeColor = app.color.blue; //success red blue purple orange black
                var switchery = new Switchery(this, {
                    color: themeColor
                });
            });
        }
    })

    async function SyncRMDetails(){
        await RMPicking()
        await StockRM()
        await SetRMUsage()
        await RMPalletReserveList()
    }

    $('#rm_code').on('sp-change', function (e) {
        SyncRMDetails()
    });

    $('#rm_type').on('sp-change', function (e) {
        StockRM()
        RMPalletReserveList()
    });

    function RMPicking(){
        $.post('<?=$CFG->wwwroot?>/protocol', { protocol: 'tbl_rm_mst', cii_code: 'identity', varib: $("#rm_code").val() }, function(data){
            try {
                const result = JSON.parse(data)

                $("#rm_spec").val(result.datas[0].spec)
                $("#rm_flute").val(result.datas[0].flute)
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + e.message)
            }
        })
    }

    function StockRM(){
        $.post('<?=$CFG->mod_planning?>/planning_route', { protocol: 'StockRM', rm_code: $("#rm_code").val(), rm_type: $("#rm_type").val() }, function(data){
            try {
                const result = JSON.parse(data)

                $("#rm_stock_qty").val(currency(result.datas.stock_qty, { separator: ',', symbol: '', precision: 0 }).format())
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + e.message)
            }
        })
    }
    
    function SetRMUsage(){
        try {
            var ffmc_quantity = $("#ffmc_quantity").val()
            var qty_set = ffmc_quantity.split(",").join("")
            var fg_perpage = $("#fg_perpage").val()
            var pd_usage = $("#pd_usage").val()

            var current_qty = Math.ceil(qty_set * pd_usage)
            var rm_usage = Math.ceil(current_qty * fg_perpage)


            $("#conf_quantity").val(current_qty)
            $("#rm_usage_qty").val(rm_usage)

            StockRM()
            RMPalletReserveList()
        } catch(err) {
            SwalOnlyText('warning', 'ไม่สามารถคำนวณข้อมูลได้ ' + err.message)
            $("#conf_quantity").val('')
            $("#qty_ffmc_quantity_set").val('')
            $("#fg_perpage").val('')
            $("#pd_usage").val('')
        }
    }

    function RMPalletReserveList(){
        $.post('<?=$CFG->mod_planning?>/planning_route', { protocol: 'PalletReserveList', rm_code: $("#rm_code").val(), rm_type: $("#rm_type").val(), rm_usage_qty: $("#rm_usage_qty").val() }, function(data){
            console.log(data)
            try {
                const result = JSON.parse(data)
                
                if(result.code == 200){
                    var table = $("#table_reserve_pallet");
                    $("#table_reserve_pallet tbody").empty()

                    if (result.datas.length > 0) {
                        var tableBody = $("<tbody></tbody>")

                        $.each(result.datas, function(id, item) {
                            var list = id + 1;
                            var row = $("<tr></tr>")

                            // เพิ่มเซลล์ข้อมูลลงในแถว
                            row.append('<td class="pt-0 pb-0"><input type="text" value="' + list + '" class="form__field font-weight-bold text-center" readonly></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="pallet_idx' + list + '" name="usage_pallet_id[]" value="' + item['pallet_id'] + '" class="form__field font-weight-bold text-center" readonly><input type="hidden" id="invenuniqx' + list + '" name="inven_uniq[]" value="' + item['inven_uniq'] + '"></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="rm_descriptX' + list + '" name="usage_rm_descript[]" value="' + item['rm_descript'] + '" class="form__field font-weight-bold text-center" readonly></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="location_name_enx' + list + '" name="usage_location_name_en[]" value="' + item['location_name_en'] + '" class="form__field font-weight-bold text-center" readonly></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="pallet_qtyx' + list + '" name="usage_pallet_qty[]" value="' + currency(item['qty'], { seperator: ',', symbol: '', precision: 0 }).format() + '" class="form__field font-weight-bold text-center" readonly></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="usage_rm_typex' + list + '" name="usage_rm_type[]" value="' + item['rm_type'] + '" class="form__field font-weight-bold text-center" readonly></td>');
                            row.append('<td class="pt-0 pb-0"><input type="text" id="rm_colorx' + list + '" name="usage_rm_color[]" value="' + item['rm_color'] + '" class="form__field font-weight-bold text-center" readonly></td>');

                            tableBody.append(row)
                        })

                        table.append(tableBody)
                    }
                }else{
                    SwalOnlyText('warning', result.message)
                }
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + e.message)
            }
        })
    }

    $("#_create_new_plan").submit(function(e){
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
            text: 'ยืนยันการออกแผนการผลิตหรือไม่?',
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
                        formData.append('protocol', 'CreateNormalPlan')
                        formData.append('job_uniq', '<?=$job_uniq?>')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->fol_planning_manage?>/management",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                console.log(data)
                                try {
                                    const result = JSON.parse(data)
                                    if(result.code == 200){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'ดำเนินการสำเร็จ',
                                            text: result.message,
                                        }).then(() => {
                                            location.reload()
                                        })
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