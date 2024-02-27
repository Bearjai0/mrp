<?php
    require_once("../../../session.php");
    
    $list_conf_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $list = $db_con->query("SELECT * FROM tbl_confirm_print_list WHERE list_conf_no = '$list_conf_no'");
    $listResult = $list->fetch(PDO::FETCH_ASSOC);
    
?>
<form id="_confirm_issue_return" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 50%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Return combine set</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_conf_no" name="list_conf_no" value="<?=$listResult['list_conf_no']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_conf_no" class="d-flex align-items-center text-gray-600 fs-13px">Cover Number</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_fg_description" value="<?=$listResult['list_fg_description']?>" class="form-control fs-13px h-45px"  data-parsley-required="true" readonly />
                            <label for="list_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Descriptions</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_cus_code" value="<?=$listResult['list_cus_code']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_cus_code" class="d-flex align-items-center text-gray-600 fs-13px">Customer</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_project" value="<?=$listResult['list_project']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_project" class="d-flex align-items-center text-gray-600 fs-13px">Project</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-20px">
                            <input type="number" id="quantity" name="quantity" value="<?=$listResult['list_pending_qty']?>" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="quantity" class="d-flex align-items-center text-gray-600 fs-13px">Quantity to return</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="remarks" class="d-flex align-items-center text-gray-600 fs-13px">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control" style="height: 7em;" data-parsley-required="true"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn bg-gradient-blue text-white">Confirm Return</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#_confirm_issue_return").submit(function(e){
        e.preventDefault()
        var form = $(this)
        var formData = new FormData($(this)[0])

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('warning', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        if($("#quantity").val() <= 0){
            SwalOnlyText('warning', 'กรุณากรอกจำนวนที่ต้องการ Return ให้มากกว่า 0 ชิ้น')
            return false
        }


        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการ Return งานรอมัดหรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    text: 'กำลังดำเนินการแสดงผลข้อมูล',
                    imageUrl: '<?=$CFG->sub_gif?>/ajax-loader.gif',
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        $.post('<?=$CFG->fol_pd_tigthing?>/management', form.serialize()+'&protocol=ConfirmReturnCombineset', function(data){
                            console.log(data)
                            try {
                                const result = JSON.parse(data)
                                if(result.code == 200){
                                    SwalReload('success', '', result.message, result.route)
                                }else{
                                    SwalOnlyText('error', '', result.message)
                                }
                            } catch(err) {
                                SwalOnlyText('error', '', err.message)
                            }
                        })
                    }
                })
            }
        })
    })
</script>