<?php
    require_once("../../../session.php");
    
    $job_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';
    $masResult = [];

    $fst = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);

    $_master_mc = $db_con->query("SELECT machine_type_code, machine_type_name FROM tbl_machine_type_mst WHERE stroke_passing NOT IN ('manual') ORDER BY machine_type_name");
    foreach($_master_mc as $item){
        array_push($masResult, array('machine_type_code'=>$item['machine_type_code'], 'machine_type_name'=>$item['machine_type_name']));
    }
?>
<form id="_confirm_machine" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Setting Machine</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show mb-0">
			    	<strong>Please verify the correctness before accepting the job consistently.</strong>
			    	กรุณาตรวจสอบความถูกต้องของ ของลำดับเครื่องจักรก่อนดำเนินการทุกครั้ง
			    </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="text" id="job_no" name="job_no" class="form-control fs-13px h-45px" value="<?=$fstResult['job_no']?>" data-parsley-required="true" readonly />
                            <label for="job_no" class="d-flex align-items-center text-gray-600 fs-13px">Job Number</label>
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
                <table id="table_machine_management" class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th class="text-nowrap" width="1%">Order</th>
                            <th class="text-nowrap" width="60%">Machine</th>
                            <th class="text-nowrap" width="15%">In</th>
                            <th class="text-nowrap" width="15%">Out</th>
                            <th class="text-nowrap" width="9%">Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $list = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY ope_orders) AS list, ope_mc_code, ope_in, ope_out FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_orders > 0 ORDER BY ope_orders");
                            while($listResult = $list->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr id="newRows<?=$listResult['list']?>" class="text-center">
                            <th class="text-nowrap"><?=$listResult['list']?></th>
                            <th class="text-nowrap text-start">
                                <select name="machine_order[]" class="form-control select-machine-order" onchange="showcase()" data-parsley-required="true" data-live-search="true" data-style="btn-white">
                                    <option value="">เลือกรายการ</option>
                                    <?php
                                        foreach($masResult as $item){
                                            $sec = $item['machine_type_code'] == $listResult['ope_mc_code'] ? 'selected' : '';
                                            echo '<option value="'.$item['machine_type_code'].'|'.$item['machine_type_name'].'" '.$sec.'> '.$item['machine_type_name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </th>
                            <th class="text-nowrap">
                                <input type="number" name="ope_in[]" value="<?=intval($listResult['ope_in'])?>" class="text-center" data-parsley-required="true">
                            </th>
                            <th class="text-nowrap">
                                <input type="number" name="ope_out[]" value="<?=intval($listResult['ope_out'])?>" class="text-center" data-parsley-required="true">
                            </th>
                            <th class="text-nowrap">
                                <button type="button" onclick="DelRows('<?=$listResult['list']?>')" class="btn btn-icon rounded bg-gradient-red text-white" <? if ($listResult['list'] == 1) { echo 'disabled'; } ?>>
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            </th>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <button type="button" id="addNewRows" class="btn btn-danger" style="float: right;">+Add Order</button>
                <p><u>Machine Order showcase</u></p>
                <h6 id="mc_show_off"><?=$fstResult['job_machine_step']?></h6>
                <input type="hidden" id="mc_showcase" name="mc_showcase" value="<?=$fstResult['job_machine_step']?>">
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
        $('.select-machine-order').picker({ search: true })
        showcase()
    })

    function showcase(){
        var showcase = ''
        $('select[name="machine_order[]"]').each(function(i) {
            i++
            var name = this.value.split("|")[1]
            showcase += i + '). ' + name + ' >>> '
        })

        $("#mc_showcase").val(showcase)
        $("#mc_show_off").html(showcase)
    }

    function DelRows(i){
        var row = document.getElementById("newRows"+i)
        row.parentNode.removeChild(row)
        showcase()
    }

    $("#addNewRows").on("click", function() {
        $.post('<?=$CFG->wwwroot?>/protocol', { protocol: 'tbl_machine_type_mst' }, function(data){
            try {
                const result = JSON.parse(data)
                if(result.code == 200){
                    var txtOption = '<option value="" selected>เลือกรายการ</option>'
                    $.each(result.datas, function(id, item){
                        txtOption += '<option value="'+item.machine_type_code+'|'+item.machine_type_name+'">'+item.machine_type_name+'</option>'
                    })

                    var tbl = document.getElementById('table_machine_management')
                    var prev = tbl.rows.length
                    var row = tbl.insertRow(prev)

                    row.id = 'newRows' + prev
                    
                    var c1 = row.insertCell(0)
                    var c2 = row.insertCell(1)
                    var c3 = row.insertCell(2)
                    var c4 = row.insertCell(3)
                    var c5 = row.insertCell(4)

                    c1.innerHTML = prev
                    c2.innerHTML = '<select id="idx'+prev+'" name="machine_order[]" class="form-control" onchange="showcase()" data-parsley-required="true" data-live-search="true" data-style="btn-white">'+txtOption+'</select>'
                    c3.innerHTML = '<input type="number" name="ope_in[]" class="form-control text-center" data-parsley-required="true">'
                    c4.innerHTML = '<input type="number" name="ope_out[]" class="form-control text-center" data-parsley-required="true">'
                    c5.innerHTML = '<button type="button" id="delRowsX' + prev + '" onclick="DelRows(' + prev + ')" class="btn btn-icon rounded bg-gradient-red text-white"><i class="fa-solid fa-ban"></i></button>'

                    c1.className = 'text-center'
                    c5.className = 'text-center'

                    $("#idx"+prev).picker({ search: true });
                }else{
                    SwalOnlyText('error', '', result.message)
                }
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถแสดงผลข้อมูลเครื่องจักรได้ ' + err.message)
            }
        })
    })

    $("#_confirm_machine").submit(function(e){
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
                        $.post('<?=$CFG->fol_pd_work?>/management', form.serialize()+'&protocol=ConfirmUpdateMachine&job_no=<?=$job_no?>', function(data){
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