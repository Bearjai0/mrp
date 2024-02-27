<?php
    require_once("../../../../session.php");
    
    $bom_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';
    
?>
<form id="_form_sending_mail_selling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">BOM - Update Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body"> 
                <h6>Mail Content</h6>
                <textarea id="mail_content" name="mail_content" class="form-control" style="height: 11em;" data-parsley-required="true"></textarea>
                <h6 class="mt-3">FG List</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th nowrap class="bg-gradient-black text-white text-center" width="10%">#</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="15%">BOM Uniq</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="20%">FG Code</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="25%">Part Customer</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="15%">Old Price</th>
                            <th nowrap class="bg-gradient-yellow text-black text-center" width="15%">New Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($bom_uniq as $id => $item):
                                $list = $db_con->prepare("SELECT ROW_NUMBER() OVER(ORDER BY bom_uniq) AS list, bom_uniq, fg_code, part_customer, selling_price FROM tbl_bom_mst WHERE bom_uniq = :bom_uniq");
                                $list->bindParam(':bom_uniq', $item);
                                $list->execute();
                                $listResult = $list->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <td class="pt-1 pb-1 text-center"><?=$listResult['list']?></td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_uniq" name="bom_uniq[]" class="form__field text-center p-0" value="<?=$listResult['bom_uniq']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code[]" class="form__field p-0" value="<?=$listResult['fg_code']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer[]" class="form__field p-0" value="<?=$listResult['part_customer']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="old_selling_price" name="old_selling_price[]" class="form__field text-center p-0" value="<?=number_format($listResult['selling_price'], 2)?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="selling_price" name="selling_price[]" class="form__field text-center p-0" value="0" min="1" data-parsley-required="true"></td>
                        </tr>
                        <?php endforeach; ?>
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
        $("#bom_status, #ctn_code_normal, #fg_description, #project_type, #fg_w, #fg_l, #fg_h, #fg_ft2, #dwg_code, #pd_usage, #ffmc_usage, #fg_perpage, #wip, #laminate, #packing_usage, #rm_code, #cost_rm, #wms_max, #wms_min, #vmi_max, #vmi_min, #vmi_app, #cost_dl, #cost_oh, #cost_total, #cost_total_oh, #selling_price").css('border-bottom', 'dashed 1px #0088cc')
        $("#bom_status, #ctn_code_normal, #fg_description, #project_type, #fg_w, #fg_l, #fg_h, #fg_ft2, #dwg_code, #pd_usage, #ffmc_usage, #fg_perpage, #wip, #laminate, #packing_usage, #rm_code, #cost_rm, #wms_max, #wms_min, #vmi_max, #vmi_min, #vmi_app, #cost_dl, #cost_oh, #cost_total, #cost_total_oh, #selling_price").addClass('text-blue')
    })

    $("#_form_sending_mail_selling").submit(function(e){
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
                        formData.append('protocol', 'SendMailSelling')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->func_bom_issue?>/management",
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