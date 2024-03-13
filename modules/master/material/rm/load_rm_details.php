<?php
    require_once("../../../../session.php");
    
    $rm_code = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $disab = $rm_code == '' ? '' : 'readonly';
    $protocol = $rm_code == '' ? 'NewRMDetails' : 'UpdateRMDetails';

    $fst = $db_con->prepare("SELECT * FROM tbl_rm_mst WHERE rm_code = :rm_code");
    $fst->bindParam(':rm_code', $rm_code);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_update_rm_details" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Raw Material - Update Details</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">RM Status :</td>
                            <td class="p-0">
                                <select id="rm_status" name="rm_status" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="Active" <?=$fstResult['rm_status'] == "Active" ? "selected" : ""?>>Active</option>
                                    <option value="InActive" <?=$fstResult['rm_status'] == "InActive" ? "selected" : ""?>>InActive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">RM Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_code" name="rm_code" class="form__field p-0" value="<?=$fstResult['rm_code']?>" data-parsley-required="true" <?=$disab?>></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Spec :</td>
                            <td class="pt-1 pb-1"><input type="text" id="spec" name="spec" class="form__field p-0" value="<?=$fstResult['spec']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Flute :</td>
                            <td class="p-0">
                                <select id="flute" name="flute" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $flut_code = $db_con->query("SELECT fluted_code FROM tbl_fluted_mst ORDER BY fluted_code");
                                        while($flutResult = $flut_code->fetch(PDO::FETCH_ASSOC)){
                                            $sected = $flutResult['fluted_code'] == $fstResult['flute'] ? 'selected' : '';
                                            echo '<option '.$sected.' value="'.$flutResult['fluted_code'].'">'.$flutResult['fluted_code'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Width(Inch) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="width_inch" name="width_inch" class="form__field p-0" value="<?=number_format($fstResult['width_inch'], 2, '.', '')?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Long(Inch) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="long_inch" name="long_inch" class="form__field p-0" value="<?=number_format($fstResult['long_inch'], 2, '.', '')?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Width(mm) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="width_mm" name="width_mm" class="form__field p-0" value="<?=number_format($fstResult['width_mm'], 2, '.', '')?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Long(mm) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="long_mm" name="long_mm" class="form__field p-0" value="<?=number_format($fstResult['long_mm'], 2, '.', '')?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ft_rm" name="ft_rm" class="form__field p-0" value="<?=number_format($fstResult['ft_rm'], 2, '.', '')?>" data-parsley-required="true"></td>
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
        $("#rm_status, #spec, #flute, #width_inch, #long_inch, #width_mm, #long_mm, #ft_rm, #remarks").css('border-bottom', 'dashed 1px #0088cc')
        $("#rm_code, #rm_spec").css('text-transform', 'uppercase')
    })

    $("#width_inch, #long_inch").on("input", function() {
        var long_inch = parseFloat($("#long_inch").val())
        var width_inch = parseFloat($("#width_inch").val())
        var ft2 = (long_inch * width_inch) * 0.0833333333
        var long_mm = long_inch * 25.4
        var width_mm = width_inch * 25.4

        $("#ft_rm").val(currency(ft2, { seperator: '', symbol: '', precision: 0 }).format())
        $("#long_mm").val(currency(long_mm, { seperator: '', symbol: '', precision: 0 }).format())
        $("#width_mm").val(currency(width_mm, { seperator: '', symbol: '', precision: 0 }).format())
    })

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
                            url: "<?=$CFG->func_material_rm?>/management",
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