<?php require_once("../../../session.php"); ?>
<form id="_add_tooling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Add New Plan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-5">
                        <label for="fg_code" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        <select id="fg_code" name="fg_code" onchange="SelectFG(this.value)" class="form-control" data-parsley-required="true" data-live-search="true" data-style="btn-white">
                            <option value="">เลือกรายการ</option>
                        </select>
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
    $(document).ready(function(data){
        FGList()
    })

    function FGList(){
        $.post('<?=$CFG->fol_planning_manage?>/management', { protocol: 'FGList' }, function(data){
            try {
                const result = JSON.parse(data)
                var fg_code = $("#fg_code")

                $.each(result.datas, function(id, item){
                    fg_code.append(
                        $('<option></option>').val(item.fg_code).html(item.fg_code)
                    )
                })
                fg_code.picker({ search: true })
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถดำเนินการได้  ' + e.message)
            }
        })
    }

    $('#ts_fg_code').on('sp-change', function (e) {
        $("#ts_fg_description").val((e.currentTarget.value).split('|')[1])
    });


    $("#_add_tooling").submit(function(e){
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
                        formData.append('protocol', 'AddNewTooling')

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