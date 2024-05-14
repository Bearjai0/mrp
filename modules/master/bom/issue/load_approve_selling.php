<?php
    require_once("../../../../session.php");
    
    $rev_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare("SELECT * FROM tbl_rev_selling WHERE rev_uniq = :rev_uniq");
    $fst->bindParam(':rev_uniq', $rev_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_approve_selling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">BOM - Approve Selling</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body"> 
                <h6>Mail Content</h6>
                <textarea id="mail_content" name="mail_content" class="form-control" style="height: 7em;" data-parsley-required="true" readonly><?=$fstResult['rev_content']?></textarea>
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
                            $list = $db_con->prepare("SELECT ROW_NUMBER() OVER(ORDER BY bom_uniq) AS list, A.*, A.det_bom_uniq, B.fg_code, B.part_customer FROM tbl_rev_selling_detail AS A LEFT JOIN tbl_bom_mst AS B ON A.det_bom_uniq = B.bom_uniq WHERE det_rev_uniq = :rev_uniq");
                            $list->bindParam(':rev_uniq', $rev_uniq);
                            $list->execute();
                            while($listResult = $list->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <td class="pt-1 pb-1 text-center"><?=$listResult['list']?></td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_uniq" name="bom_uniq[]" class="form__field text-center p-0" value="<?=$listResult['det_bom_uniq']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code[]" class="form__field text-center p-0" value="<?=$listResult['fg_code']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer[]" class="form__field text-center p-0" value="<?=$listResult['part_customer']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="old_selling_price" name="old_selling_price[]" class="form__field text-center p-0" value="<?=number_format($listResult['det_old_price'], 2)?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="selling_price" name="selling_price[]" class="form__field text-blue text-center p-0" style="border-bottom: dashed 1px #0088cc;" value="<?=number_format($listResult['det_new_price'], 2)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <h4 style="text-decoration-line: underline; text-decoration-style: double;">Authorized By</h4>
                <div class="row">
                    <div class="col-3">
                        <?php
                            if($fstResult['rev_aprv_by'] != null):
                                $sign = $db_con->prepare("SELECT user_position, user_signature FROM tbl_user WHERE user_name_en = :user_name_en");
                                $sign->bindParam(':user_name_en', $fstResult['rev_aprv_by']);
                                $sign->execute();
                                $signResult = $sign->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <img src="<?=$signResult['user_signature']?>" height="70px" alt="">
                        <label><?=$fstResult['rev_aprv_by']?></label><br>
                        <small><?=date('d/m/Y H:i', strtotime($fstResult['rev_aprv_datetime']))?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-3">
                        <?php
                            if($fstResult['rev_apfv_by'] != null):
                                $sign = $db_con->prepare("SELECT user_name_en, user_position, user_signature FROM tbl_user WHERE user_name_en = :user_name_en");
                                $sign->bindParam(':user_name_en', $fstResult['rev_apfv_by']);
                                $sign->execute();
                                $signResult = $sign->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <img src="<?=$signResult['user_signature']?>" height="70px" alt="">
                        <label><?=$signResult['user_name_en']?></label><br>
                        <small><?=date('d/m/Y H:i', strtotime($fstResult['rev_apfv_datetime']))?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
                <center>
                    <button type="submit" class="btn bg-gradient-blue-indigo fw-600 text-white ps-5 pe-5">Approve</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-white fw-600 ms-5 ps-5 pe-5">Close</button>
                </center>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        
    })

    $("#_approve_selling").submit(function(e){
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
                        formData.append('protocol', 'ApproveSelling')
                        formData.append('rev_uniq', '<?=$fstResult['rev_uniq']?>')

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