<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../../session.php");
        require_once("../../../js_css_header.php");
    ?>
    <body>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('../../../navbar.php');
                require_once("../../../menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            <div id="content" class="app-content">
                <h1 class="page-header mb-3">Production station confirmation</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Station confirmation</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <form id="_confirm_station" data-parsley-validate="true">
                            <div class="row">
                                <div class="col-4 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="job_no" name="job_no" oninput="CHeckJob(this.value)" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="job_no" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Job number</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="job_fg_description" name="job_fg_description" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="job_fg_description" class="d-flex align-items-center text-gray-700 fs-15px fw-600">FG Description</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 offset-2">
                                    <label for="machine_type" class="d-flex align-items-center text-gray-700 fs-13px fw-600">เลือกประเภทเครื่องจักร</label>
                                    <select id="machine_type" name="machine_type" onchange="ChangeMachineType(this.value)" class="form-control" data-parsley-required="true"></select>
                                </div>
                                <div class="col-4">
                                    <label for="machine_code" class="d-flex align-items-center text-gray-700 fs-13px fw-600">เลือกเครื่องจักรที่ใช้ในการผลิต</label>
                                    <select id="machine_code" name="machine_code" class="form-control" data-parsley-required="true" data-live-search="true" data-style="btn-white"></select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-1 offset-2">
                                    <div class="form-floating mb-20px"> 
                                        <input type="text" id="ope_in" name="ope_in" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="ope_in" class="d-flex align-items-center text-gray-700 fs-15px fw-600">In</label>
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="ope_out" name="ope_out" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="ope_out" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Out</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="ope_fg_sendby" name="ope_fg_sendby" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="ope_fg_sendby" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Total output</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-floating mb-20px"> 
                                        <input type="text" id="ope_fg_ttl" name="ope_fg_ttl" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="ope_fg_ttl" class="d-flex align-items-center text-gray-700 fs-15px fw-600">กรอกจำนวน FG ที่ผลิตได้</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="ope_ng_ttl" name="ope_ng_ttl" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="ope_ng_ttl" class="d-flex align-items-center text-gray-700 fs-15px fw-600">กรอกจำนวน NG ที่ผลิตได้</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="datetime-local" id="start_datetime" name="start_datetime" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="start_datetime" class="d-flex align-items-center text-gray-700 fs-15px fw-600">เวลาเริ่มการผลิต</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-20px">
                                        <input type="datetime-local" id="end_datetime" name="end_datetime" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="end_datetime" class="d-flex align-items-center text-gray-700 fs-15px fw-600">เวลาจบการผลิต</label>
                                    </div>
                                </div>
                            </div>
                            <div class="setup-view" style="display: block;">
                                <div class="row mt-2">
                                    <div class="col-8 offset-2">
                                        <div class="alert alert-warning alert-dismissible fade show mb-0">
                                            <strong>การลงเวลาตั้งค่าเครื่องจักรที่ใช้ในการผลิต</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-4 offset-2">
                                        <div class="form-floating mb-20px">
                                            <input type="datetime-local" id="start_setup" name="start_setup" class="form-control fs-15px fw-600 h-45px" />
                                            <label for="start_setup" class="d-flex align-items-center text-gray-700 fs-15px fw-600">เวลาเริ่มตั้งค่าเครื่องจักร</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-floating mb-20px">
                                            <input type="datetime-local" id="end_setup" name="end_setup" class="form-control fs-15px fw-600 h-45px" />
                                            <label for="end_setup" class="d-flex align-items-center text-gray-700 fs-15px fw-600">เวลาจบการตั้งค่าเครื่องจักร</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-lg bg-gradient-blue text-white">
                                    <span class="d-flex align-items-center text-start ps-5 pe-5">
                                        
                                        <i class="fa fa-thumbs-up fa-bounce fa-1x me-2 text-block"></i>
                                        <span>
                                            <span class="d-block"><b>ยืนยันบันทึกข้อมูล</b></span>
                                            <!-- <span class="d-block fs-12px opacity-7">Lorem ipsum</span> -->
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("../../../js_css_footer.php"); ?>
</html>


<script type="text/javascript">
    $(document).ready(function(){
        $("#job_no").focus()
    })

    function CHeckJob(job_no){
        if(job_no.length == 9){
            $.post('<?=$CFG->fol_pd_station?>/management', { protocol: 'ListPassingMachine', job_no: job_no }, function(data){
                try {
                    const result = JSON.parse(data)
                    
                    if(result.code == 200){
                        $("#job_fg_description").val(result.job_fg_description)
                        var machine_type = $("#machine_type")
                        var machine_code = $("#machine_code")

                        machine_type.html('<option value="">เลือกรายการ</option>')
                        machine_code.html('<option value="">เลือกรายการ</option>')

                        $.each(result.datas, function(id, item){
                            machine_type.append(
                                $("<option></option>").val(item.ope_mc_code).html(item.machine_type_name)
                            )
                        })
                        machine_type.select2()
                        machine_code.select2()
                    }else{
                        SwalOnlyText('warning', '', result.message)
                    }
                } catch(err) {
                    SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
                }
            })
        }
    }

    function ChangeMachineType(machine_type){
        $.post('<?=$CFG->wwwroot?>/protocol', { protocol: 'tbl_machine_mst', cii_code: 'code_and_name', variab: machine_type }, function(data){
            try {
                const result = JSON.parse(data)
                if(result.code == 200){
                    var machine_code = $("#machine_code")

                    machine_code.html('<option value="">เลือกรายการ</option>')

                    $.each(result.datas, function(id, item){
                        machine_code.append(
                            $("<option></option>").val(item.machine_code).html(item.machine_name_en)
                        )
                    })
                    machine_code.select2()
                }else{
                    SwalOnlyText('error', '', result.message)
                }
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })

        $.post('<?=$CFG->fol_pd_station?>/management', { protocol: 'CHeckSetupTime', job_no: $("#job_no").val(), machine_type: machine_type }, function(data){
            console.log(data)
            try {
                const result = JSON.parse(data)
                $(".setup-view").css('display', result.display)
                $("#ope_in").val(result.ope_in)
                $("#ope_out").val(parseInt(result.ope_out))
                $("#ope_fg_sendby").val(parseInt(result.ope_fg_sendby))
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถตรวจสอบ Setting time ของเครื่องจักรได้ ' + err.message)
            }
        })
    }

    $("#_confirm_station").submit(function(e){
        e.preventDefault()
        var form = $(this)

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันงาน FG สำหรับ Station นี้หรือไม่?',
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
                        $.post('<?=$CFG->fol_pd_station?>/management', form.serialize()+'&protocol=ConfirmStation', function(data){
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