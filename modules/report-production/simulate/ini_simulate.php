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
                <h1 class="page-header mb-3">Job Simulator</h1>
                <div class="panel panel-inverse">
					<div class="panel-heading">
						<h4 class="panel-title">DataTable - Job Simulator</h4>
						<?php require_once("../../../comp_ui/panel-header.php"); ?>
					</div>
					<div class="panel-body">
                        <div class="file-manager" id="fileManager">
                            <div class="file-manager-container">
                                <div class="file-manager-sidebar">
                                    <div class="file-manager-sidebar-mobile-toggler">
                                        <button type="button" class="btn" data-toggle-class="file-manager-sidebar-mobile-toggled" data-target="#fileManager"><i class="far fa-lg fa-folder"></i></button>
                                    </div>
                                    <div class="file-manager-sidebar-content">
                                        <div data-scrollbar="true" data-height="100%" class="p-3">
                                            <input type="text" class="form-control form-control-sm mb-3" placeholder="Seach file..." />
                                            <div class="file-tree mb-3">
                                                <div class="file-node has-sub expand selected">
                                                    <a href="javascript:;" class="file-link">
                                                        <span class="file-arrow"></span>
                                                        <span class="file-info">
                                                            <span class="file-icon"><i class="fa fa-folder fa-lg text-warning"></i></span>
                                                            <span class="file-text">Machine Simulation</span>
                                                        </span>
                                                    </a>
                                                    <div class="file-tree">
                                                        <?php
                                                            $machine = $db_con->query("SELECT machine_type_code, machine_type_name FROM tbl_machine_type_mst WHERE machine_status = 'Active' AND machine_work_type != 'Setup' ORDER BY machine_type_code");
                                                            while($machineResult = $machine->fetch(PDO::FETCH_ASSOC)):
                                                        ?>
                                                        <div class="file-node">
                                                            <a onclick="test('<?=$machineResult['machine_type_code']?>')" class="file-link">
                                                                <span class="file-arrow"></span>
                                                                <span class="file-info">
                                                                    <span class="file-icon"><i class="fa-regular fa-folder-open text-body text-opacity-50"></i></span>
                                                                    <span class="file-text"><?=$machineResult['machine_type_name']?></span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="file-manager-content d-flex flex-column">
                                    <div class="mb-0 d-flex text-nowrap p-3 border-bottom">
                                        <button type="button" class="btn btn-sm btn-white me-2 px-2"><i class="fa fa-fw fa-home"></i></button>
                                        <input type="date" id="job_plan_date" name="job_plan_date" class="btn btn-sm border me-2" value="<?=$buffer_date?>">
                                        <button type="button" id="cls_date" class="btn btn-sm btn-white me-2"><i class="fa-solid fa-eraser ms-n1"></i>  Clear Date (To query all date)</button>

                                        <div class="btn-group me-2">
                                            <button type="button" class="btn btn-sm btn-white" disabled><i class="fa me-1 fa-arrow-left"></i> Back</button>
                                            <button type="button" class="btn btn-sm btn-white text-opacity-50" disabled><i class="fa me-1 fa-arrow-right"></i> Forward</button>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-white me-2 px-2" disabled><i class="fa fa-fw fa-arrows-rotate"></i></button>
                                
                                        <div class="btn-group me-2">
                                            <button type="button" class="btn btn-sm btn-white" disabled><i class="fa fa-fw fa-check ms-n1"></i> Select All</button>
                                            <button type="button" class="btn btn-sm btn-white" disabled><i class="far fa-fw fa-square ms-n1"></i> Unselect All</button>
                                        </div>
                                    </div>
                                    <div class="flex-1 overflow-hidden border-top">
                                        <!-- <div data-scrollbar="true" data-skip-mobile="true" data-height="100%" class="p-0"> -->
                                            <table id="table_production_simulation" class="table table-striped table-borderless table-sm m-0 text-nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr class="border-bottom">
                                                        <th class="w-10px ps-10px"></th>
                                                        <th class="px-10px">Job Status</th>
                                                        <th class="px-10px">Plan Date</th>
                                                        <th class="px-10px">Job Number</th>
                                                        <th class="px-10px">FG Description</th>
                                                        <th class="px-10px">FG</th>
                                                        <th class="px-10px">NG</th>
                                                        <th class="px-10px">WIP</th>
                                                        <th class="px-10px">Start</th>
                                                        <th class="px-10px">End</th>
                                                        <th class="px-10px">Duration(Min) Calculate without FG, NG</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("../../../js_css_footer.php"); ?>
</html>
<div id="load_view_detail" class="modal fade"></div>


<script type="text/javascript">
    $(document).ready(function(){
        var fileHasSubNodes = document.querySelectorAll(".file-node.has-sub");

        fileHasSubNodes.forEach(node => {
            var fileArrow = node.querySelector(".file-link > .file-arrow");
            
            fileArrow.addEventListener("click", function (event) {
                event.preventDefault();
                node.classList.toggle("expand");
            });
        });

        var fileInfoNodes = document.querySelectorAll(".file-node");

        fileInfoNodes.forEach(node => {
            var fileInfo = node.querySelector(".file-link > .file-info");
            
            fileInfo.addEventListener("click", function (event) {
                event.preventDefault();
                fileInfoNodes.forEach(otherNode => {
                    if (otherNode !== node) {
                        otherNode.classList.remove("selected");
                    }
                });
                node.classList.add("expand");
                node.classList.add("selected");
            });
        });
    })

    $("#cls_date").click(function(e){
        $("#job_plan_date").val('')
    })

    function test(type){
        $.post('<?=$CFG->fol_rep_simulate?>/management', { protocol: 'SimulationManagement', type: type, job_plan_date: $("#job_plan_date").val() }, function(data){
            console.log(data)
            try {
                const result = JSON.parse(data)
                $('#table_production_simulation').DataTable({
                    dom: '<"dataTables_wrapper dt-bootstrap"<"row"<"col-lg-8 d-block d-sm-flex d-lg-block justify-content-center"<"d-block d-lg-inline-flex me-0 me-md-3"l><"d-block d-lg-inline-flex"B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row"<"col-md-5"i><"col-md-7"p>>>',
                    buttons: [
                        { extend: 'copy', className: 'btn-sm' },
                        { extend: 'csv', className: 'btn-sm' },
                        { extend: 'excel', className: 'btn-sm' },
                        { extend: 'pdf', className: 'btn-sm' },
                    ],
                    scrollX   : true,
                    bDestroy  : true,
                    paging    : false,
                    // searching : false,
                    lengthMenu  : [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                    pageLength  : 100,
                    // scrollY   : '480px',
                    data      : result.datas,
                    columns : [
                        { data: function(data){ return data.list + '.' }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_status }, className: "text-nowrap text-center" },
                        { data: function(data){ return moment(data.job_plan_date).format('DD/MM/YYYY') }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_no }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.job_fg_description }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ope_fg_ttl }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.ope_ng_ttl }, className: "text-nowrap text-center" },
                        { data: function(data){ return Math.floor(data.wip) }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.start_datetime }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.end_datetime }, className: "text-nowrap text-center" },
                        { data: function(data){ return data.sec_usage }, className: "text-nowrap text-center" },
                    ]
                }).draw(false)
            } catch(e) {
                SwalOnlyText('warning', e.message)
            }
        })
    }

    
</script>