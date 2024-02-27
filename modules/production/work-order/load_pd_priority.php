<?php
    require_once("../../../session.php");
    
    $job_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_setup_priority" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Adjust priority level</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <select id="job_priot" name="job_priot" class="form-control" data-parsley-required="true">
                                <option value="">Select a priority</option>
                                <?php
                                    for($i=1;$i<=100;$i++){
                                        $sected = $i == $fstResult['job_priot'] ? 'selected' : '';
                                        echo '<option value="'.$i.'" '.$sected.'>'.$i.'</option>';
                                    }
                                ?>
                            </select>
                            <label for="job_priot" class="d-flex align-items-center text-gray-600 fs-13px">Priority Level</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_no" name="job_no" class="form-control fs-13px h-45px" value="<?=$fstResult['job_no']?>" data-parsley-required="true" readonly />
                            <label for="job_no" class="d-flex align-items-center text-gray-600 fs-13px">Job Number</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_no" name="job_no" class="form-control fs-13px h-45px" value="<?=date('d/m/Y', strtotime($fstResult['job_plan_date']))?>" data-parsley-required="true" readonly />
                            <label for="job_no" class="d-flex align-items-center text-gray-600 fs-13px">Plan Date</label>
                        </div>
                    </div>
                </div>
                <table id="table_job_priority" class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr class="text-center bg-gradient-dark">
                            <th class="text-nowrap text-white">#</th>
                            <th class="text-nowrap text-white">Status</th>
                            <th class="text-nowrap text-white">Job Number</th>
                            <th class="text-nowrap text-white">Plan Date</th>
                            <th class="text-nowrap text-white">Prority Level</th>
                        </tr>
                    </thead>
                        <?php
                            $lev = $db_con->prepare("SELECT ROW_NUMBER() OVER(ORDER BY job_priot, job_uniq) AS list, job_no, FORMAT(job_plan_date, 'dd/MM/yyyy') AS job_plan_date, job_status, job_priot FROM tbl_job_mst WHERE job_plan_date = :plan_date ORDER BY job_priot, job_uniq");
                            $lev->bindParam(':plan_date', $fstResult['job_plan_date']);
                            $lev->execute();
                            while($levResult = $lev->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <tr>
                                <td class="text-center pt-2 pb-2"><?=$levResult['list']?></td>
                                <td class="text-center pt-2 pb-2"><?=$levResult['job_status']?></td>
                                <td class="text-center pt-2 pb-2"><?=$levResult['job_no']?></td>
                                <td class="text-center pt-2 pb-2"><?=$levResult['job_plan_date']?></td>
                                <td class="text-center pt-2 pb-2"><?=$levResult['job_priot']?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-blue text-white">Confirm</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $("#_setup_priority").submit(function(e){
        e.preventDefault()
        var form = $(this)

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการจัดลำดับ Priority ใหม่หรือไม่?',
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
                        $.post('<?=$CFG->fol_pd_work?>/management', { protocol: 'UpdatePriority', job_no: '<?=$job_no?>', job_priot: $("#job_priot").val() }, function(data){
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