<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("../../../../session.php");
        require_once("../../../../js_css_header.php");

        $allow_update_selling = !in_array($mrp_user_code_mst, ['GDJ00312','GDJ00316','GDJ00314','GDJ00293','GDJ00311']) ? 'disabled' : '';
        $allow_used_manage_bom = !in_array($mrp_user_code_mst, ['GDJ00216','GDJ00032','GDJ00293']) ? 'disabled' : '';
    ?>
    <body>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('../../../../navbar.php');
                require_once("../../../../menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            <div id="content" class="app-content">
                <h1 class="page-header mb-3">Master BOM</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Master BOM</h4>
						<?php require_once("../../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <button <?=$allow_used_manage_bom?> onclick="OpenViewDetail('#load_view_detail', '../manual/load_upload_bom')" class="btn bg-gradient-blue text-white m-1 fw-600"><i class="fa-solid fa-layer-group"></i> Upload New BOM</button>
                        <button <?=$allow_used_manage_bom?> onclick="OpenViewDetail('#load_view_detail', '../manual/load_update_column')" class="btn bg-gradient-blue text-white m-1 fw-600"><i class="fa-solid fa-layer-group"></i> Update BOM</button>
                        <button <?=$allow_used_manage_bom?> class="btn bg-gradient-dark text-white m-1 fw-600"><i class="fa-solid fa-layer-group"></i> Transfer Trading</button>
                        <button <?=$allow_update_selling?>  onclick="ReviseSelling()" class="btn bg-gradient-yellow text-dark m-1 fw-600"><i class="fa-solid fa-baht-sign"></i> Update Selling Price</button>
                        <hr>
                        <div class="row">
                            <div class="col-2">
                                <h6>BOM Status</h6>
                                <select id="ini_bom_status" name="ini_bom_status" class="form-control p-5">
                                    <option value="">All</option>
                                    <option value="Active" selected>Active</option>
                                    <option value="InActive">InActive</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <h6>Project</h6>
                                <select id="bom_project" name="bom_project" class="form-control">
                                    <option value="" selected>All</option>
                                    <?php
                                        $pjList = $db_con->query("SELECT DISTINCT(project) AS project FROM tbl_bom_mst ORDER BY project");
                                        while($pjListResult = $pjList->fetch(PDO::FETCH_ASSOC)){
                                            echo '<option value="'.$pjListResult['project'].'">'.$pjListResult['project'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-8">
                                <h6>Filtering choice</h6>
                                <select id="MultipleBomDetails" class="multiple-select2 form-control" multiple>
                                    <optgroup label="Fixed Header">
                                        <option selected value="bom_uniq">BOM Uniq</option>
                                        <option selected value="bom_status">BOM Status</option>
                                        <option selected value="sale_type">Sale Type</option>
                                        <option selected value="fg_type">Type</option>
                                    </optgroup>
                                    <optgroup label="Important Columns">
                                        <option selected value="fg_codeset">FG Codeset</option>
                                        <option selected value="fg_code">FG Code</option>
                                        <option selected value="part_customer">Part Customer</option>
                                        <option selected value="fg_description">FG Description</option>
                                        <option selected value="ctn_code_normal">Carton Code Normal</option>
                                        <option selected value="ship_to_type">Ship to type</option>
                                        <option selected value="box_type">Box Type</option>
                                        <option selected value="package_type">Package Type</option>
                                    </optgroup>
                                    <optgroup label="Customer Details">
                                        <option selected value="cus_type">Customer Type</option>
                                        <option selected value="cus_code">Customer Code</option>
                                        <option selected value="project_type">Project Type</option>
                                        <option selected value="project">Project</option>
                                    </optgroup>
                                    <optgroup label="Optional Value">
                                        <option selected value="dwg_code">DWG Code</option>
                                        <option selected value="fac_type">Factory Type</option>
                                        <option selected value="vmi_app">VMI App</option>
                                        <option selected value="machine_mp">Machine Order</option>
                                    </optgroup>
                                    <optgroup label="Usage Details">
                                        <option selected value="fg_ft2">FG Ft<sup>2</sup></option>
                                        <option selected value="pd_usage">PD Usage</option>
                                        <option selected value="ffmc_usage">FFMC Usage</option>
                                        <option selected value="fg_usage">FG Usage</option>
                                        <option selected value="fg_perpage">FG Page</option>
                                        <option selected value="wip">WIP / Page</option>
                                        <option selected value="packing_usage">WIP / Page</option>
                                        <option selected value="snp">SNP</option>
                                        <option selected value="moq">Min. Order</option>
                                        <option selected value="vmi_min">VMI Min</option>
                                        <option selected value="vmi_max">VMI Max</option>
                                        <option selected value="wms_min">WMS Min</option>
                                        <option selected value="wms_max">WMS Max</option>
                                    </optgroup>
                                    <optgroup label="Material Used">
                                        <option selected value="rm_code">RM Code</option>
                                        <option selected value="rm_spec">RM Spec</option>
                                        <option selected value="rm_flute">RM Flute</option>
                                        <option selected value="rm_ft2">RM Ft<sup>2</sup></option>
                                        <option selected value="sup_code">Supplier Code</option>
                                        <option selected value="sup_name_en">Supplier Name</option>
                                        <option selected value="rm_moq_min">Min MOQ(RM)</option>
                                    </optgroup>
                                    <optgroup label="Cost and Selling Price">
                                        <option selected value="cost_rm">Cost RM</option>
                                        <option selected value="cost_dl">Cost DL</option>
                                        <option selected value="cost_oh">Cost OH</option>
                                        <option selected value="cost_total">Cost Total</option>
                                        <option selected value="cost_total_oh">Cost Total & OH</option>
                                        <option selected value="selling_price">Selling Price</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <button onclick="FilterData()" class="btn bg-gradient-blue text-white mt-2 fw-600 text-right"><i class="fa-solid fa-arrow-down-short-wide"></i> Filter</button>
                        <hr>
                        <div id="table-container"></div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("../../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){
        $(".multiple-select2, #bom_project, #ini_bom_status").select2()
    })

    function FilterData(){
        var choice = $("#MultipleBomDetails").val()
        var bom_status = $("#ini_bom_status").val()
        var bom_project = $("#bom_project").val()

        if(choice.lengt == 0){
            SwalOnlyText('warning', 'กรุณาเลือกรายการก่อนดำเนินการ')
            return false
        }

        Swal.fire({
            title: 'กรุณารอสักครู่...',
            text: 'กำลังดำเนินการแสดงผลข้อมูล',
            imageUrl: '<?=$CFG->sub_gif?>/ajax-loader.gif',
            showConfirmButton: false,
            showCancelButton: false,
            didOpen: () => {
                $.post('<?=$CFG->func_bom_master?>/management', { protocol: 'MultipleBomFiltering', choice: choice, bom_status: bom_status, bom_project: bom_project }, function(data){
                    console.log('data is ' + data)
                    try {
                        var result = JSON.parse(data)
                        $('#table-container').html('<table id="myTable" class="table"></table>');
                        $('#myTable').DataTable({
                            dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-lg-8 d-block d-sm-flex d-lg-block justify-content-center"<"d-block d-lg-inline-flex me-0 me-md-3"l><"d-block d-lg-inline-flex"B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row"<"col-md-5"i><"col-md-7"p>>>',
                            colReorder: true,
                            scrollX: true,
                            scrollY: '480px',
                            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, 'All'] ],
                            pageLength: 100,
                            data: result.datas,
                            columns: result.column,
                            rowCallback: function(row, data){
                                $(row).addClass('fw-700')
                            },
                            columnDefs: [
                                {
                                    targets: '_all',
                                    className: 'text-nowrap'
                                }
                            ]
                        });
                    } catch(err) {
                        SwalOnlyText('warning', 'ไม่สามารถดำเนินการได้ ' + err.message)
                    }

                    Swal.fire({
                        icon: 'success',
                        text: 'แสดงผลข้อมูลสำเร็จ',
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 1000,
                    })
                })
            }
        })
    }

    function ReviseSelling(){
        var sendingTask = []
        $('input[name="CheckList[]"]:checked').each(function(e){
            const codes = $(this).attr('id')
            sendingTask.push(codes)
        })
        
        if(sendingTask.length == 0){
            SwalOnlyText('warning', 'กรุณาเลือกรายการก่อนดำเนินการ')
            return false
        }

        OpenViewDetail('#load_view_detail', '../issue/load_rev_selling', sendingTask)
    }
</script>