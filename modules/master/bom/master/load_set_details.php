<?php
    require_once("../../../../session.php");
    
    $set_code = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare(
        "SELECT * FROM tbl_bom_set_mst WHERE set_code = :set_code"
    );
    $fst->bindParam(':set_code', $set_code);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_set_details" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 95%;">
            <div class="modal-header">
                <h4 class="modal-title">BOM Set Details</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Set Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_code" name="set_code" class="form__field p-0" value="<?=$fstResult['set_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cus_code" name="set_cus_code" class="form__field p-0" value="<?=$fstResult['set_cus_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_project" name="set_project" class="form__field p-0" value="<?=$fstResult['set_project']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Component Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_comp_code" name="set_comp_code" class="form__field p-0" value="<?=$fstResult['set_comp_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Part Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_part_customer" name="set_part_customer" class="form__field p-0" value="<?=$fstResult['set_part_customer']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">CTN Code Normal :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_ctn_code_normal" name="set_ctn_code_normal" class="form__field p-0" value="<?=$fstResult['set_ctn_code_normal']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Description :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_description" name="set_description" class="form__field p-0" value="<?=$fstResult['set_description']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">DWG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_dwg_code" name="set_dwg_code" class="form__field p-0" value="<?=$fstResult['set_dwg_code']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Set Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_ft2" name="set_ft2" class="form__field p-0" value="<?=$fstResult['set_ft2']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost RM :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cost_rm" name="set_cost_rm" class="form__field p-0" value="<?=number_format($fstResult['set_cost_rm'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost DL :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cost_dl" name="set_cost_dl" class="form__field p-0" value="<?=number_format($fstResult['set_cost_dl'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost OH :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cost_oh" name="set_cost_oh" class="form__field p-0" value="<?=number_format($fstResult['set_cost_oh'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost Total :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cost_total" name="set_cost_total" class="form__field p-0" value="<?=number_format($fstResult['set_cost_total'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Cost Total + OH :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_cost_total_oh" name="set_cost_total_oh" class="form__field p-0" value="<?=number_format($fstResult['set_cost_total_oh'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Selling Price :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_selling_price" name="set_selling_price" class="form__field p-0" value="<?=number_format($fstResult['set_selling_price'], 2)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Production Time :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_prod_time" name="set_prod_time" class="form__field p-0" value="<?=$fstResult['set_prod_time']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">SNP :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_snp" name="set_snp" class="form__field p-0" value="<?=$fstResult['set_snp']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">MOQ :</td>
                            <td class="pt-1 pb-1"><input type="text" id="set_moq" name="set_moq" class="form__field p-0" value="<?=$fstResult['set_moq']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Remarks</td>
                            <td class="p-0">
                                <textarea id="remarks" name="remarks" class="form-control m-0" data-parsley-required="true" style="height: 9em;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1" colspan="2">
                                <h5>List component details</h5>
                                <table class="table table-bordered">
                                    <thead class="bg-black">
                                        <tr>
                                            <th class="text-center text-white" nowrap>#</th>
                                            <th class="text-center text-white" nowrap>Sale Type</th>
                                            <th class="text-center text-white" nowrap>Type</th>
                                            <th class="text-center text-white" nowrap>Group Set</th>
                                            <th class="text-center text-white" nowrap>BOM Uniq</th>
                                            <th class="text-center text-white" nowrap>FG Code</th>
                                            <th class="text-center text-white" nowrap>Part Customer</th>
                                            <th class="text-center text-white" nowrap>FG Description</th>
                                            <th class="text-center text-white" nowrap>Cost RM</th>
                                            <th class="text-center text-white" nowrap>Cost DL</th>
                                            <th class="text-center text-white" nowrap>Cost OH</th>
                                            <th class="text-center text-white" nowrap>Cost Total</th>
                                            <th class="text-center text-white" nowrap>Cost Total + OH</th>
                                            <th class="text-center text-white" nowrap>Selling Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $comp = $db_con->prepare("SELECT ROW_NUMBER() OVER(ORDER BY fg_codeset, bom_group_set, fg_type DESC) AS list, bom_uniq, fg_type, bom_group_set, sale_type, fg_code, part_customer, fg_description, cost_rm, cost_dl, cost_oh, cost_total, cost_total_oh, selling_price FROM tbl_bom_mst WHERE fg_codeset = :fg_codeset ORDER BY fg_codeset, bom_group_set, fg_type DESC");
                                            $comp->bindParam(':fg_codeset', $fstResult['set_code']);
                                            $comp->execute();
                                            while($compResult = $comp->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <th class="text-center p-1"><?=$compResult['list']?></th>
                                            <th class="text-center p-1"><?=$compResult['sale_type']?></th>
                                            <th class="text-center p-1"><?=$compResult['fg_type']?></th>
                                            <th class="text-center p-1"><?=$compResult['bom_group_set']?></th>
                                            <th class="text-center p-1"><?=$compResult['bom_uniq']?></th>
                                            <th class="p-1"><?=$compResult['fg_code']?></th>
                                            <th class="p-1"><?=$compResult['part_customer']?></th>
                                            <th class="p-1"><?=$compResult['fg_description']?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['cost_rm'], 2)?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['cost_dl'], 2)?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['cost_oh'], 2)?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['cost_total'], 2)?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['cost_total_oh'], 2)?></th>
                                            <th class="text-center p-1"><?=number_format($compResult['selling_price'], 2)?></th>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
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

    })


    $("#_set_details").submit(function(e){
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
            text: 'ยืนยันการอัพเดทข้อมูล Master Set หรือไม่?',
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
                        formData.append('protocol', 'UpdateMasterSet')

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
                                        SwalOnlyText('error', result.message)
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