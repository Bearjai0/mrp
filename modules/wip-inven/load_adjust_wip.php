<?php
    require_once("../../session.php");
    
    $inven_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare("SELECT * FROM tbl_wip_inven_mst AS A LEFT JOIN tbl_job_mst AS B ON A.receive_job_ref = B.job_no WHERE inven_uniq = :inven_uniq");
    $fst->bindParam(':inven_uniq', $inven_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_adjust_wip" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Work Order - Adjust WIP</h4>
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Job Ref# :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sem_job_no" name="sem_job_no" class="form__field p-0" value="<?=$fstResult['job_no']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Codeset :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_codeset" name="fg_codeset" class="form__field p-0" value="<?=$fstResult['job_fg_codeset']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code" class="form__field p-0" value="<?=$fstResult['job_fg_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Part Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer" class="form__field p-0" value="<?=$fstResult['job_part_customer']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Component Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="comp_code" name="comp_code" class="form__field p-0" value="<?=$fstResult['job_comp_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cus_code" name="cus_code" class="form__field p-0" value="<?=$fstResult['job_cus_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project" name="project" class="form__field p-0" value="<?=$fstResult['job_project']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Plan Date :</td>
                            <td class="pt-1 pb-1"><input type="date" id="job_plan_date" name="job_plan_date" class="form__field p-0" value="<?=$fstResult['job_plan_date']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Qty <span class="text-red">**</span> :</td>
                            <td class="pt-1 pb-1"><input type="number" id="quantity" name="quantity" class="form__field p-0" value="<?=number_format($fstResult['inven_stock_qty'], 0)?>" data-parsley-required="true" min="1"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Remarks <span class="text-red">**</span></td>
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

    })

    $("#_adjust_wip").submit(function(e){
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
            text: 'ต้องการ Transfer to WIP หรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){

                formData.append('protocol', 'AdjustWIP')
                formData.append('job_no', '<?=$fstResult['inven_uniq']?>')

                $.ajax({
                    method: "POST",
                    url: "<?=$CFG->mod_wip_inven?>/management",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        console.log(data)
                        try {
                            const result = JSON.parse(data)
                            if (result['code'] == '200') {
                                Swal.fire({
                                    icon: 'success',
                                    text: result['message'],
                                }).then(() => {
                                    location.reload()
                                })
                            } else {
                                SwalOnlyText('error', result['message'])
                            }
                        } catch (err) {
                            SwalOnlyText('error', 'ไม่สามารถดำเนินการได้ ' + err)
                        }
                    }, error: function(err){
                        SwalOnlyText('error', err)
                    }
                })
            }
        })
    })
</script>