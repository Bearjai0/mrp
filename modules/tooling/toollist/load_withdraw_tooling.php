<?php
    require_once("../../../session.php");
    
    $ts_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->query("SELECT * FROM tbl_tooling_mst WHERE ts_uniq = $ts_uniq");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_withdraw_tooling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Withdraw - <?=$fstResult['ts_type']?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_fg_code" name="ts_fg_code" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_fg_code']?>" data-parsley-required="true" readonly />
                            <label for="ts_fg_code" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_fg_description" name="ts_fg_description" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_fg_description']?>" data-parsley-required="true" readonly />
                            <label for="ts_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Description</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_location" name="ts_location" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_location']?>" data-parsley-required="true" readonly />
                            <label for="ts_location" class="d-flex align-items-center text-gray-600 fs-13px">Location</label>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_tooling_name" name="ts_tooling_name" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_tooling_name']?>" data-parsley-required="true" readonly />
                            <label for="ts_tooling_name" class="d-flex align-items-center text-gray-600 fs-13px">Tooling Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_sub_type" name="ts_sub_type" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_sub_type']?>" data-parsley-required="true" readonly />
                            <label for="ts_sub_type" class="d-flex align-items-center text-gray-600 fs-13px">Tool Type</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_stroke" name="ts_stroke" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_stroke']?>" data-parsley-required="true" readonly />
                            <label for="ts_stroke" class="d-flex align-items-center text-gray-600 fs-13px">Stroke</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_layout" name="ts_layout" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_layout']?>" data-parsley-required="true" readonly />
                            <label for="ts_layout" class="d-flex align-items-center text-gray-600 fs-13px">Layout</label>
                        </div>
                    </div>
                </div>
                <label for="#">DWG-Quotation(Attached)</label>
                <a href="https://pur.albatrosslogistic.com/pur/dwg-quotation/<?=$fstResult['ts_attach_file']?>" target="_blank"><?=$fstResult['ts_attach_file']?></a>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_job_no" name="ts_job_no" class="form-control fs-13px h-45px" placeholder="" data-parsley-required="true" />
                            <label for="ts_job_no" class="d-flex align-items-center text-gray-600 fs-13px">Withdraw for (Job number)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary fw-700">Confirm Withdraw</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#_withdraw_tooling").submit(function(e){
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
            text: 'ยืนยันการเบิกใช้งาน Tooling หรือไม่?',
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
                        formData.append('protocol', 'WithdrawTooling')
                        formData.append('ts_uniq', '<?=$fstResult['ts_uniq']?>')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->fol_toollist?>/management",
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
                                            title: 'ดำเนินการเบิกใช้งาน Tooling สำเร็จ',
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