<?php
    require_once("../../../../session.php");
    
    $bom_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare(
        "SELECT * FROM tbl_bom_mst WHERE bom_uniq = :bom_uniq"
    );
    $fst->bindParam(':bom_uniq', $bom_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_update_bom_details" data-parsley-validate="true">
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
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">BOM Uniq :</td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_uniq" name="bom_uniq" class="form__field p-0" value="<?=$fstResult['bom_uniq']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Codeset :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_codeset" name="fg_codeset" class="form__field p-0" value="<?=$fstResult['fg_codeset']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code" class="form__field p-0" value="<?=$fstResult['fg_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Part Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer" class="form__field p-0" value="<?=$fstResult['part_customer']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Component Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="comp_code" name="comp_code" class="form__field p-0" value="<?=$fstResult['comp_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Carton Code Normal :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ctn_code_normal" name="ctn_code_normal" class="form__field p-0" value="<?=$fstResult['ctn_code_normal']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Description :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_description" name="fg_description" class="form__field p-0" value="<?=$fstResult['fg_description']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Ship Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ship_to_type" name="ship_to_type" class="form__field p-0" value="<?=$fstResult['ship_to_type']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Customer :</td>
                            <td class="pt-1 pb-1"><input type="text" id="cus_code" name="cus_code" class="form__field p-0" value="<?=$fstResult['cus_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project Type :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project_type" name="project_type" class="form__field p-0" value="<?=$fstResult['project_type']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Project :</td>
                            <td class="pt-1 pb-1"><input type="text" id="project" name="project" class="form__field p-0" value="<?=$fstResult['project']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_ft2" name="fg_ft2" class="form__field p-0" value="<?=$fstResult['fg_ft2']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Drawing Details</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">DWG Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="dwg_code" name="dwg_code" class="form__field p-0" value="<?=$fstResult['dwg_code']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Usage</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">PD Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="pd_usage" name="pd_usage" class="form__field p-0" value="<?=number_format($fstResult['pd_usage'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FFMC Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="ffmc_usage" name="ffmc_usage" class="form__field p-0" value="<?=number_format($fstResult['ffmc_usage'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG / Page :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_perpage" name="fg_perpage" class="form__field p-0" value="<?=number_format($fstResult['fg_perpage'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">WIP :</td>
                            <td class="pt-1 pb-1"><input type="text" id="wip" name="wip" class="form__field p-0" value="<?=number_format($fstResult['wip'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">FG Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_usage" name="fg_usage" class="form__field p-0" value="<?=number_format($fstResult['fg_usage'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Packing Usage :</td>
                            <td class="pt-1 pb-1"><input type="text" id="packing_usage" name="packing_usage" class="form__field p-0" value="<?=number_format($fstResult['packing_usage'], 0)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Material Usage</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Code :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_code" name="rm_code" onfocusout="MaterialDetails(this.value)" class="form__field p-0" value="<?=$fstResult['rm_code']?>" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Spec :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_spec" name="rm_spec" class="form__field p-0" value="<?=$fstResult['rm_spec']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Flute :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_flute" name="rm_flute" class="form__field p-0" value="<?=$fstResult['rm_flute']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">RM Ft<sup>2</sup> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="rm_ft2" name="rm_ft2" class="form__field p-0" value="<?=number_format($fstResult['rm_ft2'], 2)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-black text-white text-center fw-600" colspan="2">Material Management</td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1" colspan="2">
                                <table id="table_manage_process" class="table table-bordered table-striped" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap text-center" width="2%">#</th>
                                            <th class="text-nowrap text-center" width="53%">Machine<button type="button" onclick="addRows()" class="btn btn-xs text-white bg-gradient-blue"><i class="fa-solid fa-plus"></i></button></th>
                                            <th class="text-nowrap text-center" width="15%">In</th>
                                            <th class="text-nowrap text-center" width="15%">Out</th>
                                            <th class="text-nowrap text-center" width="15%">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_body">
                                        <?php
                                            $mclist = $db_con->query("SELECT machine_type_code, machine_type_name FROM tbl_machine_type_mst WHERE machine_status = 'Active' AND machine_work_type != 'Setup' ORDER BY machine_type_name");
                                            $mclistRest = $mclist->fetchAll(PDO::FETCH_ASSOC);

                                            $mc_order = $fstResult['machine_order'] != "[]" ? json_decode($fstResult['machine_order'], TRUE) : [array('order' => 1, 'machine_code' => 'none', 'in' => 0, 'out' => 0)];
                                            foreach($mc_order as $id => $item):
                                                $row_id = $id + 1;
                                        ?>
                                        <tr id="<?=$id?>">
                                            <td class="pt-0 pb-0"><input type="text" value="<?=$row_id?>" class="form__field fw-600 text-center pt-1 pb-1" readonly></td>
                                            <td class="p-0">
                                                <select id="machine_type_name<?=$id?>" name="machine_type_name[]" class="form-control pt-1 pb-1" data-parsley-required="true">
                                                    <option value="">เลือกรายการ</option>
                                                    <?php
                                                        foreach($mclistRest as $machine){
                                                            $sected = $machine['machine_type_code'] == $item['machine_code'] ? 'selected' : '';
                                                            echo '<option '.$sected.' value="'.$machine['machine_type_code'].'">'.$machine['machine_type_name'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </td>
                                            <td class="pt-0 pb-0"><input type="text" id="machine_in<?=$id?>" name="machine_in[]" value="<?=$item['in']?>" min="1" class="form__field fw-600 text-center pt-1 pb-1"></td>
                                            <td class="pt-0 pb-0"><input type="text" id="machine_out<?=$id?>" name="machine_out[]" value="<?=$item['out']?>" min="1" class="form__field fw-600 text-center pt-1 pb-1"></td>
                                            <td class="pt-0 pb-0 text-center"><button type="button" onclick="delRows('<?=$id?>')" class="btn btn-xs bg-gradient-red text-white mt-3px" <?=$id == 0 ? 'disabled' : ''?>><span class="fas fa-trash"></span></button></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

    function addRows(){
        var select_list = ''
        $.each(JSON.parse('<?=json_encode($mclistRest)?>'), function(id, item){
            select_list += '<option value="'+item.machine_type_code+'">'+item.machine_type_name+'</option>'
        })

        var row_id = MakeID(5)
        var row = $('<tr id="'+row_id+'"></tr>')
        row.append('<td class="p-0"><input type="text" value="#" class="form__field fw-600 text-center pt-1 pb-1" readonly></td>')
        row.append('<td class="p-0"><select id="machine_type_name'+row_id+'" name="machine_type_name[]" class="form-control pt-1 pb-1" data-parsley-required="true"><option value="">เลือกรายการ</option>'+select_list+'</select></td>')
        row.append('<td class="p-0"><input type="text" id="machine_in'+row_id+'" name="machine_in[]" value="0" min="1" class="form__field fw-600 text-center pt-1 pb-1"></td>')
        row.append('<td class="p-0"><input type="text" id="machine_out'+row_id+'" name="machine_out[]" value="0" min="1" class="form__field fw-600 text-center pt-1 pb-1"></td>')
        row.append('<td class="pt-0 pb-0 text-center"><button type="button" onclick="delRows(\''+row_id+'\')" class="btn btn-xs bg-gradient-red text-white mt-3px"><span class="fas fa-trash"></span></button></td>')
        $("#table_body").append(row)
    }

    function delRows(row_id){
        var row = document.getElementById(row_id)
        row.parentNode.removeChild(row)
    }

    $("#_update_bom_details").submit(function(e){
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
                        formData.append('protocol', 'UpdateBOMProcess')

                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->func_bom_master?>/management",
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