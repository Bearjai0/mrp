<?php
    require_once("../../application.php");
    
    $user_code = isset($_POST['sendingTask']) ? $_POST['sendingTask']['usercode'] : '';
    $user_name_en = isset($_POST['sendingTask']) ? $_POST['sendingTask']['user_name_en'] : '';
?>
<form id="_issue_chage_password" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 30%;">
            <div class="modal-header">
                <h4 class="modal-title">User Management - Change Password</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show mb-0">
			    	<strong>Please chage you're password first!</strong>
			    	รหัสผ่านของผู้ใช้เป็นรหัสผ่านพื้นฐาน กรุณาเปลี่ยนรหัสผ่านผู้ใช้ก่อนเข้าใช้งานระบบ
			    </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-1">Username</h6>
                        <input type="text" id="username" name="username" value="<?=$user_name_en?>" data-parsley-required="true" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="mb-1">New password</h6>
                        <input type="password" id="new-password" name="new-password" data-parsley-required="true">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <h6 class="mb-1">Re-type password</h6>
                        <input type="password" id="re-password" name="re-password" data-parsley-required="true">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('small').addClass('font-weight-bold')
        $('input').addClass('form-control font-weight-bold')
    })

    $("#_issue_chage_password").submit(function(e){
        e.preventDefault()
        var form = $(this)

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        if($("#new-password").val() != $("#re-password").val()){
            SwalOnlyText('warning', '', 'Password ไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการอัพเดทข้อมูล User หรือไม่?',
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
                        $.post('<?=$CFG->mod_user?>/management', form.serialize()+'&protocol=ChangePassword&usercode=<?=$user_code?>&password='+$("#new-password").val(), function(data){
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