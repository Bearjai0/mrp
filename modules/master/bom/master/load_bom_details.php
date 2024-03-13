<?php
    require_once("../../../../session.php");
    
    $bom_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare(
        "SELECT * FROM tbl_bom_mst WHERE bom_uniq = :bom_uniq"
    );
    $fst->bindParam(':bom_uniq', $bom_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);

    $lock_read = $fstResult['bom_lock_type'] == "Unlock" ? '' : "readonly";
?>
<form id="_update_bom_details" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">BOM - Update Details</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">BOM Status :</td>
                            <td class="p-0">
                                <select id="bom_status" name="bom_status" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="Active" <?=$fstResult['bom_status'] == "Active" ? "selected" : ""?>>Active</option>
                                    <option value="InActive" <?=$fstResult['bom_status'] == "InActive" ? "selected" : ""?>>InActive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">BOM Uniq :</td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_uniq" name="bom_uniq" class="form__field p-0" value="<?=$fstResult['bom_uniq']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Sale Type :</td>
                            <td class="p-0">
                                <select id="sale_type" name="sale_type" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="Sale" <?=$fstResult['sale_type'] == "SET" ? "selected" : ""?>>Sale</option>
                                    <option value="Not Sale" <?=$fstResult['sale_type'] == "COMPONENT" ? "selected" : ""?>>Not Sale</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">FG Type :</td>
                            <td class="p-0">
                                <select id="fg_type" name="fg_type" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="SET" <?=$fstResult['fg_type'] == "SET" ? "selected" : ""?>>SET</option>
                                    <option value="COMPONENT" <?=$fstResult['fg_type'] == "COMPONENT" ? "selected" : ""?>>COMPONENT</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Group Name :</td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_group_set" name="bom_group_set" class="form__field p-0" value="<?=$fstResult['bom_group_set']?>" data-parsley-required="true"></td>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Carton Code Normal :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ctn_code_normal" name="ctn_code_normal" class="form__field p-0" value="<?=$fstResult['ctn_code_normal']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Description :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_description" name="fg_description" class="form__field p-0" value="<?=$fstResult['fg_description']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Ship Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ship_to_type" name="ship_to_type" class="form__field p-0" value="<?=$fstResult['ship_to_type']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Box Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="box_type" name="box_type" class="form__field p-0" value="<?=$fstResult['box_type']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cus_code" name="cus_code" class="form__field p-0" value="<?=$fstResult['cus_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project_type" name="project_type" class="form__field p-0" value="<?=$fstResult['project_type']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project" name="project" class="form__field p-0" value="<?=$fstResult['project']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">FG Dimension(OD)</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Width :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_w" name="fg_w" class="form__field p-0" value="<?=$fstResult['fg_size_width']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Long :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_l" name="fg_l" class="form__field p-0" value="<?=$fstResult['fg_size_long']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Height :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_h" name="fg_h" class="form__field p-0" value="<?=$fstResult['fg_size_height']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_ft2" name="fg_ft2" class="form__field p-0" value="<?=$fstResult['fg_ft2']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Drawing Details</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">DWG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="dwg_code" name="dwg_code" class="form__field p-0" value="<?=$fstResult['dwg_code']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Usage</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">PD Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="pd_usage" name="pd_usage" class="form__field p-0" value="<?=number_format($fstResult['pd_usage'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FFMC Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ffmc_usage" name="ffmc_usage" class="form__field p-0" value="<?=number_format($fstResult['ffmc_usage'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG / Page :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_perpage" name="fg_perpage" class="form__field p-0" value="<?=number_format($fstResult['fg_perpage'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">WIP :</td>
                            <td class="pt-1 pb-1"><input type="text" id="wip" name="wip" class="form__field p-0" value="<?=number_format($fstResult['wip'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Usage / FG :</td>
                            <td class="pt-1 pb-1"><input type="text" id="laminate" name="laminate" class="form__field p-0" value="<?=number_format($fstResult['laminate'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Packing Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="packing_usage" name="packing_usage" class="form__field p-0" value="<?=number_format($fstResult['packing_usage'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">MOQ :</td>
                            <td class="pt-1 pb-1"><input type="text" id="moq" name="moq" class="form__field p-0" value="<?=number_format($fstResult['moq'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Material Usage</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Supplier :</td>
                            <td class="p-0">
                                <select id="sup_uniq" name="sup_uniq" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $supl = $db_con->query("SELECT sup_uniq, sup_name_en FROM tbl_supplier_mst");
                                        while($suplResult = $supl->fetch(PDO::FETCH_ASSOC)){
                                            $s = $suplResult['sup_uniq'] == $fstResult['bom_sup_code'] ? 'selected' : '';
                                            echo '<option value="'.$suplResult['sup_uniq'].'">'.$suplResult['sup_name_en'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_code" name="rm_code" onfocusout="MaterialDetails(this.value)" class="form__field p-0" value="<?=$fstResult['rm_code']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Spec :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_spec" name="rm_spec" class="form__field p-0" value="<?=$fstResult['rm_spec']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Flute :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_flute" name="rm_flute" class="form__field p-0" value="<?=$fstResult['rm_flute']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_ft2" name="rm_ft2" class="form__field p-0" value="<?=number_format($fstResult['rm_ft2'], 2)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">VMI & WMS</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">WMS Max :</td>
                            <td class="pt-1 pb-1"><input type="text" id="wms_max" name="wms_max" class="form__field p-0" value="<?=number_format($fstResult['wms_max'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">WMS Min :</td>
                            <td class="pt-1 pb-1"><input type="text" id="wms_min" name="wms_min" class="form__field p-0" value="<?=number_format($fstResult['wms_min'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">VMI Max :</td>
                            <td class="pt-1 pb-1"><input type="text" id="vmi_max" name="vmi_max" class="form__field p-0" value="<?=number_format($fstResult['vmi_max'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">VMI Min :</td>
                            <td class="pt-1 pb-1"><input type="text" id="vmi_min" name="vmi_min" class="form__field p-0" value="<?=number_format($fstResult['vmi_min'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">VMI APP :</td>
                            <td class="p-0">
                                <select id="vmi_app" name="vmi_app" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="Y" <?=$fstResult['vmi_app'] == "Y" ? "selected" : ""?>>Y</option>
                                    <option value="N" <?=$fstResult['vmi_app'] == "N" ? "selected" : ""?>>N</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Cost & Selling Price</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost RM :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cost_rm" name="cost_rm" class="form__field p-0" value="<?=number_format($fstResult['cost_rm'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost DL :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cost_dl" name="cost_dl" class="form__field p-0" value="<?=number_format($fstResult['cost_dl'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost OH :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cost_oh" name="cost_oh" class="form__field p-0" value="<?=number_format($fstResult['cost_oh'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost Total :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cost_total" name="cost_total" class="form__field p-0" value="<?=number_format($fstResult['cost_total'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost Total + OH :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cost_total_oh" name="cost_total_oh" class="form__field p-0" value="<?=number_format($fstResult['cost_total_oh'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Selling Price :</td>
                            <td class="pt-1 pb-1"><input type="text" id="selling_price" name="selling_price" class="form__field p-0" value="<?=number_format($fstResult['selling_price'], 2)?>" data-parsley-required="true" <?=$lock_read?>></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Remarks</td>
                            <td class="p-0">
                                <textarea id="remarks" name="remarks" class="form-control m-0" data-parsley-required="true" style="height: 9em;"></textarea>
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
        $("#bom_status, #bom_group_set, #ctn_code_normal, #fg_description, #box_type, #project_type, #sale_type, #fg_type, #fg_w, #fg_l, #fg_h, #fg_ft2, #dwg_code, #pd_usage, #ffmc_usage, #fg_perpage, #wip, #laminate, #packing_usage, #moq, #sup_uniq, #rm_code, #rm_spec, #cost_rm, #wms_max, #wms_min, #vmi_max, #vmi_min, #vmi_app, #cost_dl, #cost_oh, #cost_total, #cost_total_oh, #selling_price").css('border-bottom', 'dashed 1px #0088cc')
        $("#bom_status, #bom_group_set, #ctn_code_normal, #fg_description, #box_type, #project_type, #sale_type, #fg_type, #fg_w, #fg_l, #fg_h, #fg_ft2, #dwg_code, #pd_usage, #ffmc_usage, #fg_perpage, #wip, #laminate, #packing_usage, #moq, #sup_uniq, #rm_code, #rm_spec, #cost_rm, #wms_max, #wms_min, #vmi_max, #vmi_min, #vmi_app, #cost_dl, #cost_oh, #cost_total, #cost_total_oh, #selling_price").addClass('text-blue')
    })

    function MaterialDetails(val){
        $.post('<?=$CFG->wwwroot?>/protocol', { protocol: 'tbl_rm_mst', cii_code: 'identity', variab: val }, function(data){
            try {
                const result = JSON.parse(data)
                const datas = result.datas

                if(datas.length > 0){
                    $("#rm_spec").val(datas[0].spec)
                    $("#rm_flute").val(datas[0].flute)
                    $("#rm_ft2").val(currency(datas[0].ft_rm, { seperator: ',', symbol: '', precision: 2 }).format())
                }else{
                    $("#rm_spec").val('')
                    $("#rm_flute").val('')
                    $("#rm_ft2").val('')
                }
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถแสดงผลข้อมูล Raw Material ได้ ' + e.message)
            }
        })
    }

    $("#_update_bom_details").submit(function(e){
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
            text: 'ยืนยันการอัพเดทข้อมูลหรือไม่?',
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
                        formData.append('protocol', 'UpdateBOMDetails')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->func_bom_master?>/management",
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