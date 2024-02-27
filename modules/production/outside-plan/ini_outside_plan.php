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
                <h1 class="page-header mb-3">Confirm outside plan</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">Confirm outside plan</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <form id="_confirm_outside_plan" data-parsley-validate="true">
                            <div class="row">
                                <div class="col-2 offset-2 border-1">
                                    <label for="project" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Project</label>
                                    <select id="project" name="project" class="form-control" data-parsley-required="true">
                                        <option value="">เลือกรายการ</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="bom_uniq" class="d-flex align-items-center text-gray-700 fs-15px fw-600">BOM Uniq</label>
                                    <select id="bom_uniq" name="bom_uniq" class="form-control" data-parsley-required="true"></select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-4 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="job_no" name="job_no" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="job_no" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Job Number References</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="fg_code" name="fg_code" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="fg_code" class="d-flex align-items-center text-gray-700 fs-15px fw-600">FG Code</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="fg_codeset" name="fg_codeset" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="fg_codeset" class="d-flex align-items-center text-gray-700 fs-15px fw-600">FG Codeset</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="fg_description" name="fg_description" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="fg_description" class="d-flex align-items-center text-gray-700 fs-15px fw-600">FG Description</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="cus_code" name="cus_code" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="cus_code" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Customer</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="comp_code" name="comp_code" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="comp_code" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Component Code</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating mb-20px">
                                        <input type="text" id="part_customer" name="part_customer" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" readonly />
                                        <label for="part_customer" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Part Customer</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 offset-2">
                                    <div class="form-floating mb-20px">
                                        <input type="number" id="quantity" name="quantity" class="form-control fs-15px fw-600 h-45px" data-parsley-required="true" />
                                        <label for="quantity" class="d-flex align-items-center text-gray-700 fs-15px fw-600">Quantity</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-lg bg-gradient-blue text-white">
                                    <span class="d-flex align-items-center text-start ps-5 pe-5">
                                        <span>
                                            <span class="d-block"><b>Confirm</b></span>
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
        $.post('<?=$CFG->fol_pd_outside?>/management', { protocol: 'GetDistinctProject' }, function(data){
            try {
                const result = JSON.parse(data)

                $.each(result.datas, function(id, item){
                    $("#project").append(
                        $('<option></option>').val(item.project).html(item.project)
                    )
                })

                $("#project").select2()
            } catch(err) {
                SwalOnlyText('error', err.message)
            }
        })
    })

    $("#project").change(function(e){
        $.post('<?=$CFG->fol_pd_outside?>/management', { protocol: 'GetBOMByProject', project: e.target.value }, function(data){
            try {
                const result = JSON.parse(data)
                var bom_uniq = $("#bom_uniq")
                bom_uniq.html('<option value="">เลือกรายการ</option>')

                $.each(result.datas, function(id, item){
                    $(bom_uniq).append(
                        $('<option></option>').val(item.bom_uniq).html(item.fg_code + ' - ' + item.fg_description)
                    )
                })

                $(bom_uniq).select2()
            } catch(err) {
                SwalOnlyText('error', err.message)
            }
        })
    })

    $("#bom_uniq").change(function(e){
        $.post('<?=$CFG->fol_pd_outside?>/management', { protocol: 'GetBOMDetails', bom_uniq: e.target.value }, function(data){
            try {
                const result = JSON.parse(data)

                $("#fg_code").val(result.datas.fg_code)
                $("#fg_codeset").val(result.datas.fg_codeset)
                $("#fg_description").val(result.datas.fg_description)
                $("#cus_code").val(result.datas.cus_code)
                $("#part_customer").val(result.datas.part_customer)
                $("#comp_code").val(result.datas.comp_code)
            } catch(err) {
                SwalOnlyText('error', err.message)
            }
        })
    })




    $("#_confirm_outside_plan").submit(function(e){
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
                        $.post('<?=$CFG->fol_pd_outside?>/management', form.serialize()+'&protocol=ConfirmManual', function(data){
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