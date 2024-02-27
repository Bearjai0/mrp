<?php
    require_once("../../../session.php");
    
    $job_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_confirm_work" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Confirm receive job</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show mb-0">
			    	<strong>Please verify the correctness before accepting the job consistently.</strong>
			    	กรุณาตรวจสอบความถูกต้องของ Job ก่อนทำการรับเพื่อผลิตงานทุกครั้ง!
			    </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_no" name="job_no" class="form-control fs-13px h-45px" value="<?=$fstResult['job_no']?>" data-parsley-required="true" readonly />
                            <label for="job_no" class="d-flex align-items-center text-gray-600 fs-13px">Job Number</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_plan_date" name="job_plan_date" class="form-control fs-13px h-45px" value="<?=date('d/m/Y', strtotime($fstResult['job_plan_date']))?>" data-parsley-required="true" readonly />
                            <label for="job_plan_date" class="d-flex align-items-center text-gray-600 fs-13px">Plan Date</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_cus_code" name="job_cus_code" class="form-control fs-13px h-45px" value="<?=$fstResult['job_cus_code']?>" data-parsley-required="true" readonly />
                            <label for="job_cus_code" class="d-flex align-items-center text-gray-600 fs-13px">Customer</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_project" name="job_project" class="form-control fs-13px h-45px"  value="<?=$fstResult['job_project']?>"data-parsley-required="true" readonly />
                            <label for="job_project" class="d-flex align-items-center text-gray-600 fs-13px">Project</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_fg_code" name="job_fg_code" class="form-control fs-13px h-45px" value="<?=$fstResult['job_fg_code']?>" data-parsley-required="true" readonly />
                            <label for="job_fg_code" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_fg_codeset" name="job_fg_codeset" class="form-control fs-13px h-45px" value="<?=$fstResult['job_fg_codeset']?>" data-parsley-required="true" readonly />
                            <label for="job_fg_codeset" class="d-flex align-items-center text-gray-600 fs-13px">FG Codeset</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_comp_code" name="job_comp_code" class="form-control fs-13px h-45px" value="<?=$fstResult['job_comp_code']?>" data-parsley-required="true" readonly />
                            <label for="job_comp_code" class="d-flex align-items-center text-gray-600 fs-13px">Component Code</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_part_customer" name="job_part_customer" class="form-control fs-13px h-45px" value="<?=$fstResult['job_part_customer']?>" data-parsley-required="true" readonly />
                            <label for="job_part_customer" class="d-flex align-items-center text-gray-600 fs-13px">Part Customer</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_fg_description" name="job_fg_description" class="form-control fs-13px h-45px" value="<?=$fstResult['job_fg_description']?>" data-parsley-required="true" readonly />
                            <label for="job_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Description</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_plan_set" name="job_plan_set" class="form-control fs-13px h-45px text-blue-600" value="<?=number_format($fstResult['job_plan_set'], 0, '.', ',')?>" data-parsley-required="true" readonly />
                            <label for="job_plan_set" class="d-flex align-items-center text-gray-600 fs-13px">Order (Set)</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_plan_set_per_job" name="job_plan_set_per_job" class="form-control fs-13px h-45px text-blue-600" value="<?=number_format($fstResult['job_plan_set_per_job'], 0, '.', ',')?>" data-parsley-required="true" readonly />
                            <label for="job_plan_set_per_job" class="d-flex align-items-center text-gray-600 fs-13px">Order (Set/Job)</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_plan_qty" name="job_plan_qty" class="form-control fs-13px h-45px text-blue-600" value="<?=number_format($fstResult['job_plan_qty'], 0, '.', ',')?>" data-parsley-required="true" readonly />
                            <label for="job_plan_qty" class="d-flex align-items-center text-gray-600 fs-13px">Order (Pcs / Job)</label>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_rm_code" name="job_rm_code" class="form-control fs-13px h-45px" value="<?=$fstResult['job_rm_code']?>" data-parsley-required="true" readonly />
                            <label for="job_rm_code" class="d-flex align-items-center text-gray-600 fs-13px">RM Code</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_rm_spec" name="job_rm_spec" class="form-control fs-13px h-45px" value="<?=$fstResult['job_rm_spec']?>" data-parsley-required="true" readonly />
                            <label for="job_rm_spec" class="d-flex align-items-center text-gray-600 fs-13px">RM Spec</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_rm_flute" name="job_rm_flute" class="form-control fs-13px h-45px" value="<?=$fstResult['job_rm_flute']?>" readonly />
                            <label for="job_rm_flute" class="d-flex align-items-center text-gray-600 fs-13px">Flute</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_rm_usage" name="job_rm_usage" class="form-control fs-13px h-45px" value="<?=$fstResult['job_rm_usage']?>" data-parsley-required="true" readonly />
                            <label for="job_rm_usage" class="d-flex align-items-center text-gray-600 fs-13px">RM Usage</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_fg_perpage" name="job_fg_perpage" class="form-control fs-13px h-45px" value="<?=$fstResult['job_fg_perpage']?>" data-parsley-required="true" readonly />
                            <label for="job_fg_perpage" class="d-flex align-items-center text-gray-600 fs-13px">FG Perpage</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_pd_usage" name="job_pd_usage" class="form-control fs-13px h-45px" value="<?=$fstResult['job_pd_usage']?>" data-parsley-required="true" readonly />
                            <label for="job_pd_usage" class="d-flex align-items-center text-gray-600 fs-13px">PD Usage</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_packing_usage" name="job_packing_usage" class="form-control fs-13px h-45px" value="<?=$fstResult['job_packing_usage']?>" data-parsley-required="true" readonly />
                            <label for="job_packing_usage" class="d-flex align-items-center text-gray-600 fs-13px">Packing Usage</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_ft2_perpage" name="job_ft2_perpage" class="form-control fs-13px h-45px" value="<?=number_format($fstResult['job_ft2_perpage'], 2, '.', ',')?>" data-parsley-required="true" readonly />
                            <label for="job_ft2_perpage" class="d-flex align-items-center text-gray-600 fs-13px">FT<sup>2</sup> / Page</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_ft2_usage" name="job_ft2_usage" class="form-control fs-13px h-45px" value="<?=number_format($fstResult['job_ft2_usage'], 2, '.', ',')?>" data-parsley-required="true" readonly />
                            <label for="job_ft2_usage" class="d-flex align-items-center text-gray-600 fs-13px">Sum FT<sup>2</sup></label>
                        </div>
                    </div>
                </div>
                <hr>
                <h5>Machine order >>>>>>>>>> <?=$fstResult['job_machine_step']?></h5>
                <table id="table_pd_work_mc" class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap" style="width: 70%;">Machine</th>
                            <th class="text-nowrap">In</th>
                            <th class="text-nowrap">Out</th>
                            <th class="text-nowrap">Results(Estimated FG)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $est = $fstResult['job_rm_usage'];
                            $mlist = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY ope_orders) AS list, A.*, B.machine_type_name FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE A.ope_job_no = '$job_no' AND ope_orders > 0 ORDER BY ope_orders");
                            while($mlistResult = $mlist->fetch(PDO::FETCH_ASSOC)):
                                if($mlistResult['ope_mc_code'] != 'TG'){
                                    $est = ($est / $mlistResult['ope_in']) * $mlistResult['ope_out'];
                                }
                        ?>
                        <tr>
                            <th class="text-nowrap text-center"><?=$mlistResult['list']?>.</th>
                            <th class="text-nowrap"><?=$mlistResult['machine_type_name']?></th>
                            <th class="text-nowrap text-center"><?=number_format($mlistResult['ope_in'], 0, '.', ',')?></th>
                            <th class="text-nowrap text-center"><?=number_format($mlistResult['ope_out'], 0, '.', ',')?></th>
                            <th class="text-nowrap text-center"><?=$est?></th>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">

    $("#_confirm_work").submit(function(e){
        e.preventDefault()
        var form = $(this)

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการรับงานเพื่อผลิตหรือไม่?',
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
                        $.post('<?=$CFG->fol_pd_work?>/management', form.serialize()+'&protocol=ConfirmWork&job_no=<?=$job_no?>', function(data){
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