<?php
    require_once("../../../session.php");
    
    $ts_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->query("SELECT * FROM tbl_tooling_mst WHERE ts_uniq = $ts_uniq");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_update_plate" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Update Tooling - Plate Die Cut</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <select id="ts_status" name="ts_status" class="form-control" data-parsley-required="true">
                                <option value="Active" <?=$fstResult['ts_status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="InActive" <?=$fstResult['ts_status'] == 'InActive' ? 'selected' : '' ?>>InActive</option>
                            </select>
                            <label for="ts_status" class="d-flex align-items-center text-gray-600 fs-13px">Status</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_fg_code" name="ts_fg_code" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_fg_code']?>" data-parsley-required="true" readonly />
                            <label for="ts_fg_code" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_fg_description" name="ts_fg_description" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_fg_description']?>" data-parsley-required="true" readonly />
                            <label for="ts_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Description</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_tooling_name" name="ts_tooling_name" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_tooling_name']?>" data-parsley-required="true" />
                            <label for="ts_tooling_name" class="d-flex align-items-center text-gray-600 fs-13px">Tooling Name</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <select id="ts_type" name="ts_type" class="form-control" data-parsley-required="true">
                                <option value="">เลือกรายการ</option>
                                <option value="Plate Die Cut" <?=$fstResult['ts_type'] == 'Plate Die Cut' ? 'selected' : '' ?>>Plate Die Cut</option>
                                <option value="Flexo Block Screen" <?=$fstResult['ts_type'] == 'Flexo Block Screen' ? 'selected' : '' ?>>Flexo Block Screen</option>
                            </select>
                            <label for="ts_type" class="d-flex align-items-center text-gray-600 fs-13px">Main Type</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-floating mb-20px">
                            <select id="ts_sup_uniq" name="ts_sup_uniq" class="form-control" data-parsley-required="true">
                                <option value="">เลือกรายการ</option>
                                <?php
                                    $ts_sup = $db_con->query("SELECT run_number, sup_name_en FROM tbl_supplier_mst ORDER BY run_number");
                                    while($tsResult = $ts_sup->fetch(PDO::FETCH_ASSOC)){
                                        $sected = $tsResult['run_number'] == $fstResult['ts_sup_uniq'] ? 'selected' : '';

                                        echo '<option '.$sected.' value="'.$tsResult['run_number'].'">'.$tsResult['sup_name_en'].'</option>';
                                    }                                    
                                ?>
                            </select>
                            <label for="ts_sup_uniq" class="d-flex align-items-center text-gray-600 fs-13px">Supplier Name</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_price" name="ts_price" class="form-control fs-13px h-45px" value="<?=number_format($fstResult['ts_price'], 2, 0, ',')?>" data-parsley-required="true" />
                            <label for="ts_price" class="d-flex align-items-center text-gray-600 fs-13px">Price</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_location" name="ts_location" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_location']?>" data-parsley-required="true" />
                            <label for="ts_location" class="d-flex align-items-center text-gray-600 fs-13px">Location</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_sub_type" name="ts_sub_type" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_sub_type']?>" data-parsley-required="true" />
                            <label for="ts_sub_type" class="d-flex align-items-center text-gray-600 fs-13px">Tool Type</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_stroke" name="ts_stroke" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_stroke']?>" data-parsley-required="true" />
                            <label for="ts_stroke" class="d-flex align-items-center text-gray-600 fs-13px">Stroke</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_layout" name="ts_layout" class="form-control fs-13px h-45px" value="<?=$fstResult['ts_layout']?>" data-parsley-required="true" />
                            <label for="ts_layout" class="d-flex align-items-center text-gray-600 fs-13px">Layout</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="file" id="ts_files" name="ts_files" class="form-control fs-13px h-45px" />
                            <label for="ts_files" class="d-flex align-items-center text-gray-600 fs-13px">DWG-Quotation</label>
                        </div>
                    </div>
                </div>
                <label for="#">DWG-Quotation(Attached)</label>
                <a href="https://pur.albatrosslogistic.com/pur/dwg-quotation/<?=$fstResult['ts_attach_file']?>" target="_blank"><?=$fstResult['ts_attach_file']?></a>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#_update_plate").submit(function(e){
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
            text: 'ยืนยันการอัพเดทข้อมูล Tooling หรือไม่?',
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
                        formData.append('protocol', 'UpdateTooling')
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