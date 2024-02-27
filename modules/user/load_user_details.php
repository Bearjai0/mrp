<?php
    require_once("../../session.php");
    
    $usercode = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user_code");
    $fst->bindParam(':user_code', $usercode);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_update_user_details" data-parsley-validate="true">
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">User Status :</td>
                            <td class="p-0">
                                <select id="user_enable" name="user_enable" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="1" <?=$fstResult['user_enable'] == "1" ? "selected" : ""?>>Active</option>
                                    <option value="0" <?=$fstResult['user_enable'] == "0" ? "selected" : ""?>>InActive</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Cost & Selling price Access :</td>
                            <td class="p-0">
                                <select id="user_cost_access" name="user_cost_access" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="1" <?=$fstResult['user_cost_access'] == "1" ? "selected" : ""?>>Allow</option>
                                    <option value="0" <?=$fstResult['user_cost_access'] == "0" ? "selected" : ""?>>Not Allow</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Usercode :</td>
                            <td class="pt-1 pb-1"><input type="text" id="usercode" name="usercode" class="form__field p-0" value="<?=$fstResult['user_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Username (EN) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="user_name_en" name="user_name_en" class="form__field p-0" value="<?=$fstResult['user_name_en']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Username (TH) :</td>
                            <td class="pt-1 pb-1"><input type="text" id="user_name_th" name="user_name_th" class="form__field p-0" value="<?=$fstResult['user_name_th']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Department :</td>
                            <td class="p-0">
                                <select id="user_dep_id" name="user_dep_id" class="form-control pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        $dep = $db_con->query("SELECT dep_id, dep_name_en FROM tbl_department_mst ORDER BY dep_id");
                                        while($depResult = $dep->fetch(PDO::FETCH_ASSOC)){
                                            $c = $depResult['dep_id'] == $fstResult['user_dep_id'] ? 'selected' : '';
                                            echo '<option '.$c.' value="'.$depResult['dep_id'].'">'.$depResult['dep_name_en'].'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Position :</td>
                            <td class="pt-1 pb-1"><input type="text" id="user_position" name="user_position" class="form__field p-0" value="<?=$fstResult['user_position']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Email :</td>
                            <td class="pt-1 pb-1"><input type="email" id="user_email" name="user_email" class="form__field p-0" value="<?=$fstResult['user_email']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Signature :</td>
                            <td class="pt-1 pb-1">
                                <input type="file" id="myfile" name="myfile" onchange="checkfile(this.value)" class="form__field p-0">
                                <img id="preView" src="<?=$fstResult['user_signature']?>" width="300px" alt="loaded" />
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

    function checkfile(files){
		var sec = document.getElementById('myfile')
		for(var i=0; i<sec.files.length; i++){
			var ext = sec.files[i].name.substr((sec.files[i].name.lastIndexOf('.') + 1)) // check type
			const size = (sec.files[i].size / 1024 / 1024).toFixed(2) // check size
			if(ext == "png" || ext == "PNG"){
				var reader = new FileReader()
				reader.onload = function(e){
					$("#preView").attr('src', e.target.result)
				}
				reader.readAsDataURL(sec.files[0])
			}else{
				Swal.fire({
					icon: 'error',
					text: 'ไม่สามารถใช้ไฟล์สกุลอื่นนอกจาก png, PNG',
					showConfirmButton: false,
					timer: 3000
				})

				$("#myfile").replaceWith($("#myfile").val('').clone(true));
				$("#myfile").val('');
				$("#myfile").focus();
			}
		}
	}

    $("#_update_user_details").submit(function(e){
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
                        formData.append('protocol', 'UpdateUserDetails')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->mod_user?>/management",
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