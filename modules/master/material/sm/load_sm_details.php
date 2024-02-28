<?php
    require_once("../../../../session.php");
    
    $sm_code = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $protocol = $rm_code == '' ? 'NewSMDetails' : 'UpdateSMDetails';

    $fst = $db_con->prepare("SELECT * FROM tbl_sm_mst WHERE sm_code = :sm_code");
    $fst->bindParam(':sm_code', $sm_code);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_update_rm_details" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Sub Material - Update Details</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">SM Status :</td>
                            <td class="p-0">
                                <select id="sm_status" name="sm_status" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="Active" <?=$fstResult['sm_status'] == "Active" ? "selected" : ""?>>Active</option>
                                    <option value="InActive" <?=$fstResult['sm_status'] == "InActive" ? "selected" : ""?>>InActive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">SM Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sm_code" name="sm_code" class="form__field p-0" value="<?=$fstResult['sm_code']?>" disabled></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Material Type :</td>
                            <td class="p-0">
                                <select id="sm_type" name="sm_type" onchange="MaterialResult()" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $sm_type = $db_con->query("SELECT type_code, type_name_en FROM tbl_sm_type_mst ORDER BY type_uniq");
                                        while($typeResult = $sm_type->fetch(PDO::FETCH_ASSOC)){
                                            $sected = $typeResult['type_code'] == $fstResult['ref_code'] ? 'selected' : '';
                                            echo '<option '.$sected.' value="'.$typeResult['type_code'] . '|' . $typeResult['type_name_en'] .'">'.$typeResult['type_name_en'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Material Name :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sm_name" name="sm_name" oninput="MaterialResult()" class="form__field p-0" value="<?=$fstResult['sm_name']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Result :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sm_result" name="sm_result" class="form__field p-0" value="<?=$fstResult['ref_name'] . ' ' . $fstResult['sm_name']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Unit Rate :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sub_unit_rate" name="sub_unit_rate" class="form__field p-0" value="<?=number_format($fstResult['sub_unit_rate'], 2, '.', '')?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Unit Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sub_unit_type" name="sub_unit_type" class="form__field p-0" value="<?=$fstResult['sub_unit_type']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Stock Min(MOQ) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sm_min" name="sm_min" class="form__field p-0" value="<?=number_format($fstResult['sm_min'], 0)?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Stock Max :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sm_max" name="sm_max" class="form__field p-0" value="<?=number_format($fstResult['sm_max'], 0)?>" data-parsley-required="true"></td>
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
        
    })
    
    function MaterialResult(){
        var sm_type = ($("#sm_type").val()).split("|")[1]
        var sm_name = $("#sm_name").val()

        $("#sm_result").val(sm_type + ' ' + sm_name)
    }

    $("#_update_rm_details").submit(function(e){
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
                        formData.append('protocol', '<?=$protocol?>')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->func_material_sm?>/management",
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