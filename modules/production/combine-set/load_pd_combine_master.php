<?php
    require_once("../../../session.php");
    
    $job_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';
    $machine_type_code = '';
    $mint = '';
    $fst;

    foreach($job_no as $item){
        $mint .= "'" . $item . "',";
    }

    $mint = rtrim($mint, ",");

    if(count($job_no) == 1){
        $fst = $db_con->query(
            "SELECT job_bom_id AS bom_uniq,
                    job_fg_code AS fg_code,
                    job_fg_codeset AS fg_codeset,
                    job_part_customer AS part_customer,
                    job_cus_code AS cus_code,
                    job_project AS project,
                    job_ctn_code_normal AS ctn_code_normal,
                    job_comp_code AS comp_code,
                    job_fg_description AS fg_description,
                    job_packing_usage AS packing_usage,
                    job_merge_mc
             FROM tbl_job_mst WHERE job_no IN($mint)"
        );
    
        $fstResult = $fst->fetchAll(PDO::FETCH_ASSOC);
        $machine_type_code = $fstResult[0]['job_merge_mc'];
    }else{
        $cks = $db_con->query("SELECT job_fg_codeset, job_project, job_merge_mc FROM tbl_job_mst WHERE job_no IN($mint) GROUP BY job_fg_codeset, job_project, job_merge_mc");
        $cksResult = $cks->fetch(PDO::FETCH_ASSOC);

        $cks_fg_codeset = $cksResult['job_fg_codeset'];
        $cks_project = $cksResult['job_project'];
        $machine_type_code = $cksResult['job_merge_mc'];

        $fst = $db_con->query("SELECT * FROM tbl_bom_mst WHERE fg_codeset = '$cks_fg_codeset' AND project = '$cks_project' AND bom_status = 'Active' AND fg_type = 'SET'");
        $fstResult = $fst->fetchAll(PDO::FETCH_ASSOC);
    }
?>
<form id="_combine_master_set" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Combine master set</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <select id="bom_uniq" name="bom_uniq" onchange="ChangeUnique()" class="form-control" data-style="btn-white" data-parsley-required="true" data-live-search="true">
                                <option value="">เลือกรายการ</option>
                                <?php 
                                    foreach($fstResult as $id => $item){
                                        if(count($fstResult) == 1 && $id == 0){
                                            echo '<option value="'.$item['bom_uniq'].'" selected>'.$item['fg_code'].'</option>';
                                        }else{
                                            echo '<option value="'.$item['bom_uniq'].'">'.$item['fg_code'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                            <label for="bom_uniq" class="d-flex align-items-center text-gray-600 fs-13px">FG Code</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="fg_description" name="fg_description" class="form-control fs-13px h-45px" value="<?=$fstResult['fg_description']?>" data-parsley-required="true" readonly />
                            <label for="fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Descriptions</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="cus_code" name="cus_code" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="cus_code" class="d-flex align-items-center text-gray-600 fs-13px">Customer</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="project" name="project" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="project" class="d-flex align-items-center text-gray-600 fs-13px">Project</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="packing_usage" name="packing_usage" class="form-control fs-13px h-45px" data-parsley-required="true" readonly />
                            <label for="packing_usage" class="d-flex align-items-center text-gray-600 fs-13px">Packing Usage</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <select id="machine_code" name="machine_code" class="form-control" data-style="btn-white" data-parsley-required="true" data-live-search="true">
                                <option value="">เลือกรายการ</option>
                                <?php
                                    $mst = $db_con->query("SELECT machine_code, machine_name_en FROM tbl_machine_mst WHERE machine_type = '$machine_type_code' ORDER BY machine_code");
                                    $mstResult = $mst->fetchAll(PDO::FETCH_ASSOC);
                                    $sected = count($mstResult) == 1 ? "selected" : "";
                                    foreach($mstResult as $item):
                                ?>
                                <option value="<?=$item['machine_code']?>" <?=$sected?>><?=$item['machine_name_en']?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="machine_code" class="d-flex align-items-center text-gray-600 fs-13px">Combine Machine</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control font-weight-bold" data-parsley-required="true">
                            <label for="start_datetime" class="d-flex align-items-center text-gray-600 fs-13px">Start Time</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control font-weight-bold" data-parsley-required="true">
                            <label for="end_datetime" class="d-flex align-items-center text-gray-600 fs-13px">End Time</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <?php
                                $comp = $db_con->query("SELECT TOP(1) sem_stock_qty AS Minimum FROM tbl_semi_inven_mst AS A WHERE A.sem_job_no IN($mint) ORDER BY Minimum ASC");
                                $compResult = $comp->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <input type="text" id="combine_fg" name="combine_fg" value="<?=$compResult['Minimum']?>" class="form-control fs-13px h-45px" data-parsley-required="true" data-parsley-max="<?=$compResult['Minimum']?>" />
                            <label for="combine_fg" class="d-flex align-items-center text-gray-600 fs-13px">FG <span class="text-danger">**</span> (Minimum in Set)</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="combine_ng" name="combine_ng" value="0" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="combine_ng" class="d-flex align-items-center text-gray-600 fs-13px">NG <span class="text-danger">**</span></label>
                        </div>
                    </div>
                </div>
                <div class="setup-view">
                    <div class="alert alert-warning alert-dismissible fade show mb-0">
                        <strong>การลงเวลาตั้งค่าเครื่องจักรที่ใช้ในการผลิต</strong>
                    </div>
                    <div class="row mt-1">
                        <div class="col-3">
                            <div class="form-floating mb-20px">
                                <input type="datetime-local" id="setting_start_datetime" name="setting_start_datetime" class="form-control fs-15px fw-600 h-45px" />
                                <label for="setting_start_datetime" class="d-flex align-items-center text-gray-700 fs-13px">เวลาเริ่มตั้งค่าเครื่องจักร</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-floating mb-20px">
                                <input type="datetime-local" id="setting_end_datetime" name="setting_end_datetime" class="form-control fs-15px fw-600 h-45px" />
                                <label for="setting_end_datetime" class="d-flex align-items-center text-gray-700 fs-13px">เวลาจบการตั้งค่าเครื่องจักร</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h6>List job usage </h6>
                <table class="table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-nowrap" width="01%">#</th>
                            <th class="text-nowrap" width="15%">JOB NO.</th>
                            <th class="text-nowrap" width="24%">FG Codeset</th>
                            <th class="text-nowrap" width="15%">FG Code</th>
                            <th class="text-nowrap" width="25%">Description</th>
                            <th class="text-nowrap text-center" width="5%">IN</th>
                            <th class="text-nowrap text-center" width="5%">OUT</th>
                            <th class="text-nowrap text-center" width="10%">FG(Pcs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $joblist = $db_con->query(
                                "SELECT ROW_NUMBER() OVER(ORDER BY job_no ASC) AS list,
                                        A.sem_job_no, A.sem_stock_qty, B.job_fg_description, B.job_comp_code,
                                        B.job_fg_codeset, B.job_fg_code, B.job_merge_in, B.job_merge_out
                                 FROM tbl_semi_inven_mst AS A
                                 LEFT JOIN tbl_job_mst AS B ON A.sem_job_no = B.job_no
                                 WHERE A.sem_job_no IN($mint)
                                 ORDER BY A.sem_job_no ASC"
                            );
                            while($listResult = $joblist->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr>
                            <td class="text-nowrap"><labe><?=$listResult['list']?></labe></td>
                            <td class="text-nowrap"><labe><?=$listResult['sem_job_no']?></labe></td>
                            <td class="text-nowrap"><labe><?=$listResult['job_fg_codeset']?></labe></td>
                            <td class="text-nowrap"><labe><?=$listResult['job_fg_code']?></labe></td>
                            <td class="text-nowrap"><labe><?=$listResult['job_fg_description']?></labe></td>
                            <td class="text-nowrap text-center"><labe><?=$listResult['job_merge_in']?></labe></td>
                            <td class="text-nowrap text-center"><labe><?=$listResult['job_merge_out']?></labe></td>
                            <td class="text-nowrap text-center"><labe><?=$listResult['sem_stock_qty']?></labe></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn bg-gradient-blue text-white">Confirm combine set</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('small').addClass('font-weight-bold')
        $('input').addClass('form-control font-weight-bold')
        $("#tags_bom_uniq").picker({ search: true })

        ChangeUnique()

        if('<?=$machine_type_code?>' == 'ST'){
            $("#setup-view").css('display', 'block')
        }else{
            $("#setup-view").css('display', 'none')
        }
    })

    function ChangeUnique(){
        var bom_uniq = $("#bom_uniq").val()

        if(bom_uniq == ''){
            return false
        }

        $.post('<?=$CFG->wwwroot?>/protocol', { protocol: 'tbl_bom_mst', cii_code: 'uniq', variab: bom_uniq }, function(data){
            console.log(data)
            console.log('uniq is ' + bom_uniq)
            try {
                const res = JSON.parse(data)

                console.log(res)

                if(res.code == 200){
                    $("#fg_description").val(res.datas[0].fg_description)
                    $("#packing_usage").val(res.datas[0].packing_usage)
                    $("#cus_code").val(res.datas[0].cus_code)
                    $("#project").val(res.datas[0].project)
                }else{
                    $("#fg_description").val('')
                    $("#packing_usage").val('')
                    $("#cus_code").val('')
                    $("#project").val('')
                }
            } catch(err) {
                SwalOnlyText('warning', 'ไม่สามารถดำเนินการได้ ' + err.message)

                $("#fg_description").val('')
                $("#packing_usage").val('')
                $("#cus_code").val('')
                $("#project").val('')
            }
        })
    }

    $("#_combine_master_set").submit(function(e){
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
            text: 'ต้องการ Combine Set หรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){
                var jify_job = JSON.parse('<?=json_encode($job_no)?>')

                formData.append('protocol', 'CombineMaster')
                formData.append('job_no', jify_job)

                $.ajax({
                    method: "POST",
                    url: "<?=$CFG->fol_pd_combine?>/management",
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
                

                // Swal.fire({
                //     title: 'กรุณารอสักครู่...',
                //     text: 'กำลังดำเนินการแสดงผลข้อมูล',
                //     imageUrl: imgLoader,
                //     showConfirmButton: false,
                //     showCancelButton: false,
                //     didOpen: () => {
                //         $.post('<?=$CFG->fol_pd_combine?>/management', form.serialize()+'&protocol=CombineMaster', function(data){
                //             console.log(data)
                //             try {
                //                 const result = JSON.parse(data)
                //                 if(result.code == 200){
                //                     SwalReload('success', '', result.message, result.route)
                //                 }else{
                //                     SwalOnlyText('error', '', result.message)
                //                 }
                //             } catch(err) {
                //                 SwalOnlyText('error', '', err.message)
                //             }
                //         })
                //     }
                // })
            }
        })
    })
</script>