<?php
    require_once("../../../session.php");
    
    $job_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->query("SELECT A.*, B.job_ref FROM tbl_job_mst AS A LEFT JOIN tbl_job_detail AS B ON A.job_no = B.job_no WHERE A.job_no = '$job_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);

    $txt_clr = '';
    switch($fstResult['job_status']){
        case 'prepare' : $txt_clr = 'text-red'; break;
        case 'complete' : $txt_clr = 'text-green'; break;
        case 'pending' : $txt_clr = 'text-blue'; break;
        default: $txt_clr = 'text-yellow'; break;
    }
?>
<form id="_confirm_work" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">Work order - Job number <?=$fstResult['job_no']?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="nav-wizards-container">
                    <div class="nav nav-wizards-1 mb-2">
                        <?php
                            $lism = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY ope_orders) AS list, ope_mc_code, ope_status, machine_type_name FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = '$job_no' ORDER BY ope_orders");
                            while($lismResult = $lism->fetch(PDO::FETCH_ASSOC)):
                                $stat = $lismResult['ope_status'] == 'done' ? 'active' : '';
                        ?>
                        <div class="nav-item col">
                            <a href="#" class="nav-link <?=$stat?>">
                                <div class="nav-no"><?=$lismResult['list']?></div>
                                <div class="nav-text"><?=$lismResult['machine_type_name']?></div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Machine</th>
                                    <th>FG</th>
                                    <th>NG</th>
                                    <th>Status</th>
                                    <th>Post Datetime</th>
                                    <th>Post By</th>
                                </tr>
                            </thead>
                            <tbody class="fw-600">
                                <?php
                                    $olem = $db_con->query(
                                        "SELECT ROW_NUMBER() OVER(ORDER BY ope_orders) AS list, A.ope_mc_code, A.ope_fg_ttl, A.ope_ng_ttl, A.ope_status, A.ope_finish_datetime, A.ope_finish_by, B.machine_type_name, C.class_color
                                         FROM tbl_job_operation AS A
                                         LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code
                                         LEFT JOIN tbl_status_color AS C ON A.ope_status = C.hex_status
                                         WHERE A.ope_job_no = '$job_no' ORDER BY ope_orders"
                                    );
                                    while($olemResult = $olem->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td class="text-nowrap text-center"><?=$olemResult['list']?></td>
                                    <td class="text-nowrap"><?=$olemResult['machine_type_name']?></td>
                                    <td class="text-nowrap text-center"><?=$olemResult['ope_fg_ttl']?></td>
                                    <td class="text-nowrap text-center"><?=$olemResult['ope_ng_ttl']?></td>
                                    <td class="text-nowrap text-center"><?=$olemResult['ope_status']?></td>
                                    <td class="text-nowrap text-center"><?=$olemResult['ope_finish_datetime']?></td>
                                    <td class="text-nowrap text-center"><?=$olemResult['ope_finish_by']?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-4">
                        <table id="user" class="table table-bordered">
							<thead>
								<tr class="text-center">
									<th style="width: 30%">Field Name</th>
									<th style="width: 40%">Field Value</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">Job Status</td>
									<td class="pb-1 pt-1 <?=$txt_clr?> fw-700"><?=$fstResult['job_status']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">Job Number</td>
									<td class="pb-1 pt-1 text-blue fw-700"><?=$fstResult['job_no']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">Plan Date</td>
									<td class="pb-1 pt-1"><?=date('d/m/Y', strtotime($fstResult['job_plan_date'] ))?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">FG Code</td>
									<td class="pb-1 pt-1 text-blue"><?=$fstResult['job_fg_code']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">FG Codeset</td>
									<td class="pb-1 pt-1"><?=$fstResult['job_fg_codeset']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">Part Customer</td>
									<td class="pb-1 pt-1"><?=$fstResult['job_part_customer']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">FG Description</td>
									<td class="pb-1 pt-1 text-blue"><?=$fstResult['job_fg_description']?></td>
								</tr>
								<tr>
									<td class="pb-1 pt-1 bg-bodybg-opacity-50 text-end fw-600">MRD Number</td>
									<td class="pb-1 pt-1 text-red"><?=$fstResult['job_ref']?></td>
								</tr>
								<tr>
									<td class="pb-2 pt-2 bg-bodybg-opacity-50 text-end fw-600">Complete Date</td>
									<td class="pb-2 pt-2 fw-600"><?=$fstResult['job_complete_datetime'] != NULL ? date('d/m/Y', strtotime($fstResult['job_complete_datetime'])) : ''?></td>
								</tr>
							</tbody>
						</table>
                    </div>
                </div>
                <h4>Transactions</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Machine Name</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>FG</th>
                                    <th>NG</th>
                                    <th>Status</th>
                                    <th>Start Datetime</th>
                                    <th>End Datetime</th>
                                    <th>Trans By</th>
                                    <th>Trans Datetime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $ltns = $db_con->query(
                                        "SELECT ROW_NUMBER() OVER(ORDER BY uniq_id) AS list, pass_mc_code, machine_name_en, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime
                                         FROM tbl_job_confirm_passing_transaction AS A 
                                         LEFT JOIN tbl_machine_mst AS B ON A.pass_mc_code = B.machine_code
                                         WHERE pass_job_no = '$job_no'
                                         ORDER BY uniq_id"
                                    );
                                    while($ltnsResult = $ltns->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['list']?></td>
                                    <td class="pb-1 pt-1 text-nowrap"><?=$ltnsResult['machine_name_en']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['pass_in']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['pass_out']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['pass_fg']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['pass_ng']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=$ltnsResult['pass_status']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=date('d/m/Y, H:i', strtotime($ltnsResult['pass_start_datetime']))?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=date('d/m/Y, H:i', strtotime($ltnsResult['pass_end_datetime']))?></td>
                                    <td class="pb-1 pt-1 text-nowrap"><?=$ltnsResult['pass_by']?></td>
                                    <td class="pb-1 pt-1 text-nowrap text-center"><?=date('d/m/Y, H:i', strtotime($ltnsResult['pass_datetime']))?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        
    })
</script>