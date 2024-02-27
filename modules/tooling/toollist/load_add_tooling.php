<?php require_once("../../../session.php"); ?>
<form id="_add_tooling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Add New Tooling</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_status" name="ts_status" value="Active" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="ts_status" class="d-flex align-items-center text-gray-600 fs-13px">Status</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <label for="ts_fg_code" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        <select id="ts_fg_code" name="ts_fg_code" onchange="SelectFG(this.value)" class="form-control" data-parsley-required="true" data-live-search="true" data-style="btn-white">
                            <option value="">เลือกรายการ</option>
                        </select>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_fg_description" name="ts_fg_description" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="ts_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Description</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_tooling_name" name="ts_tooling_name" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="ts_tooling_name" class="d-flex align-items-center text-gray-600 fs-13px">Tooling Name</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <select id="ts_type" name="ts_type" class="form-control" data-parsley-required="true">
                                <option value="">เลือกรายการ</option>
                                <option value="Plate Die Cut">Plate Die Cut</option>
                                <option value="Flexo Block Screen">Flexo Block Screen</option>
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

                                        echo '<option value="'.$tsResult['run_number'].'">'.$tsResult['sup_name_en'].'</option>';
                                    }                                    
                                ?>
                            </select>
                            <label for="ts_sup_uniq" class="d-flex align-items-center text-gray-600 fs-13px">Supplier Name</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_price" name="ts_price" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="ts_price" class="d-flex align-items-center text-gray-600 fs-13px">Price</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_location" name="ts_location" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="ts_location" class="d-flex align-items-center text-gray-600 fs-13px">Location</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_sub_type" name="ts_sub_type" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="ts_sub_type" class="d-flex align-items-center text-gray-600 fs-13px">Tool Type</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_stroke" name="ts_stroke" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="ts_stroke" class="d-flex align-items-center text-gray-600 fs-13px">Stroke</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="ts_layout" name="ts_layout" class="form-control fs-13px h-45px" data-parsley-required="true" />
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
        $.post('<?=$CFG->fol_toollist?>/management', { protocol: 'FGList' }, function(data){
            try {
                const result = JSON.parse(data)
                var ts_fg_code = $("#ts_fg_code")

                $.each(result.datas, function(id, item){
                    ts_fg_code.append($("<option></option>").val(item.fg_code + '|' + item.fg_description).html(item.fg_code + ' - ' + item.fg_description))
                })
                $(ts_fg_code).picker({ search: true })
            } catch(err) {
                SwalOnlyText('error', 'ไม่สามารถแสดงผลข้อมูล List FG Code ได้ ' + err.message)
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