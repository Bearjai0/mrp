<?php
    require_once("../../../session.php");
    
    $list_conf_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $list = $db_con->query("SELECT * FROM tbl_confirm_print_list WHERE list_conf_no = '$list_conf_no'");
    $listResult = $list->fetch(PDO::FETCH_ASSOC);
    
?>
<form id="_confirm_issue_tigthing" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 80%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Confirm Tightening</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_conf_no" name="list_conf_no" value="<?=$listResult['list_conf_no']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_conf_no" class="d-flex align-items-center text-gray-600 fs-13px">Cover Number</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_fg_description" value="<?=$listResult['list_fg_description']?>" class="form-control fs-13px h-45px"  data-parsley-required="true" readonly />
                            <label for="list_fg_description" class="d-flex align-items-center text-gray-600 fs-13px">FG Descriptions</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_cus_code" value="<?=$listResult['list_cus_code']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_cus_code" class="d-flex align-items-center text-gray-600 fs-13px">Customer</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_project" value="<?=$listResult['list_project']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_project" class="d-flex align-items-center text-gray-600 fs-13px">Project</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="text" id="list_packing_usage" value="<?=$listResult['list_packing_usage']?>" class="form-control fs-13px h-45px" style="text-transform: uppercase;" data-parsley-required="true" readonly />
                            <label for="list_packing_usage" class="d-flex align-items-center text-gray-600 fs-13px">Packing Usage</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <select id="machine_code" name="machine_code" class="form-control" data-parsley-required="true" data-style="btn-white" data-live-search="true">
                                <option value="">เลือกรายการ</option>
                                <?php
                                    $mc = $db_con->query("SELECT machine_type, machine_code, machine_name_en FROM tbl_machine_mst WHERE machine_type = 'TG' ORDER BY machine_uniq");
                                    while($mcResult = $mc->fetch(PDO::FETCH_ASSOC)){
                                        echo '<option value="'.$mcResult['machine_code'].'">'.$mcResult['machine_name_en'].'</option>';
                                    }
                                ?>
                            </select>
                            <label for="machine_code" class="d-flex align-items-center text-gray-600 fs-13px">Packing Usage</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="start_datetime" class="d-flex align-items-center text-gray-600 fs-13px">Start Datetime</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-20px">
                            <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="end_datetime" class="d-flex align-items-center text-gray-600 fs-13px">End Datetime</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="number" id="list_confirm_fg" name="list_confirm_fg" value="<?=$listResult['list_pending_qty']?>" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="list_confirm_fg" class="d-flex align-items-center text-gray-600 fs-13px">Confirm FG(Pcs)</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-20px">
                            <input type="number" id="list_confirm_ng" name="list_confirm_ng" value="0" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="list_confirm_ng" class="d-flex align-items-center text-gray-600 fs-13px">Confirm NG(Pcs)</label>
                        </div>
                    </div>
                    <div class="col-2 offset-1">
                        <div class="form-floating mb-20px">
                            <input type="number" id="list_exceed_fg" name="list_exceed_fg" value="0" class="form-control fs-13px h-45px" data-parsley-required="true" />
                            <label for="list_exceed_fg" class="d-flex align-items-center text-gray-600 fs-13px">Exceed FG(Pcs) (จำนวนงานเกินแผน)</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn bg-gradient-blue text-white">Confirm Tigthing</button>
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
    })

    $("#_confirm_issue_tigthing").submit(function(e){
        e.preventDefault()
        var form = $(this)
        var formData = new FormData($(this)[0])

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        const list_confirm_fg = parseInt($("#list_confirm_fg").val())
        const list_confirm_ng = parseInt($("#list_confirm_ng").val())

        const start_datetime = $("#start_datetime").val()
        const end_datetime = $("#end_datetime").val()

        if(start_datetime == end_datetime){
            SwalOnlyText('warning','ไม่สามารถบันทึกข้อมูลเริ่มและจบ Process ด้วยเวลาเดียวกันได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง')
            return false
        }else if(start_datetime >= end_datetime){
            SwalOnlyText('warning','ข้อมูลการเริ่มและจบ Process ไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง')
            return false
        }


        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการมัดงานหรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){
                Swal.fire({
                    title: 'กรุณารอสักครู่...',
                    text: 'กำลังดำเนินการแสดงผลข้อมูล',
                    imageUrl: '<?=$CFG->sub_gif?>/ajax-loader.gif',
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        $.post('<?=$CFG->fol_pd_tigthing?>/management', form.serialize()+'&protocol=ConfirmTigthing', function(data){
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