<?php
    require_once("../../../session.php");
    require_once("../../fg-inven/route-mod.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $machine_code = isset($_POST['machine_code']) ? $_POST['machine_code'] : '';
    $list_conf_no = isset($_POST['list_conf_no']) ? $_POST['list_conf_no'] : '';
    $list_confirm_fg = isset($_POST['list_confirm_fg']) ? $_POST['list_confirm_fg'] : '';
    $list_confirm_ng = isset($_POST['list_confirm_ng']) ? $_POST['list_confirm_ng'] : '';
    $list_exceed_fg = isset($_POST['list_exceed_fg']) ? $_POST['list_exceed_fg'] : '';

    $start_datetime = isset($_POST['start_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['start_datetime'])) : '';
    $end_datetime = isset($_POST['end_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['end_datetime'])) : '';
    
    $job_no = isset($_POST['job_no']) ? $_POST['job_no'] : '';
    $project = isset($_POST['project']) ? $_POST['project'] : '';
    $pallet_id = isset($_POST['pallet_id']) ? $_POST['pallet_id'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    
    $json = array();

    if($protocol == "SyncTigthingList"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY list_uniq DESC) AS list, A.*, B.class_color, C.selling_price, C.cost_total_oh
                 FROM tbl_confirm_print_list AS A 
                 LEFT JOIN tbl_status_color AS B ON A.list_status = B.hex_status
                 LEFT JOIN tbl_bom_mst AS C ON A.list_bom_id = C.bom_uniq
                 WHERE list_pending_qty > 0 AND list_status != 'Cancel'
                 ORDER BY list_uniq DESC"
            );
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                $list_conf_no = $listResult['list_conf_no'];

                $det = $db_con->query("SELECT conf_job_no FROM tbl_confirm_print_tags WHERE conf_code = '$list_conf_no'");
                while($detResult = $det->fetch(PDO::FETCH_ASSOC)){
                    $job_no .= $detResult['conf_job_no'] . ', ';
                }
                $job_no = rtrim($job_no, ', ');

                $listResult['job_no'] = rtrim($job_no, ', ');
                array_push($json, $listResult);
                $job_no = '';
            }

            $json = array('code'=>200, 'message'=>'ok', 'datas'=>$json);
        } catch(Exception $e) {
            $json = array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage());
        }

        echo json_encode($json);
        $db_con = null;
        return;
    }else if($protocol == "ConfirmTigthing"){
        $quantity = $list_confirm_fg + $list_confirm_ng;
        if($list_confirm_fg == 0 && $list_confirm_ng > 0){
            $condi = false;
        }else{
            $condi = true;
        }
        //************************** CHeck Cover Status *************************//
        //************************************************************************/
        $list = $db_con->query("SELECT * FROM tbl_confirm_print_list WHERE list_conf_no = '$list_conf_no'");
        $listResult = $list->fetch(PDO::FETCH_ASSOC);
        $allow_status = ['Prepare','Pending','Put-Away'];

        if($listResult['list_conf_no'] == ""){
            echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูล Covert sheet number สำหรับดำเนินการ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
            $db_con = null;
            return;
        }

        if(!in_array($listResult['list_status'], $allow_status)){
            echo json_encode(array('code'=>400, 'message'=>'สถานะของ Cover Number ไม่พร้อมสำหรับการมัด ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
            $db_con = null;
            return;
        }

        if(intval($listResult['list_pending_qty']) < $quantity){
            echo json_encode(array('code'=>400, 'message'=>'จำนวนที่ Confirm(FG+NG) มีมากกว่างานบนระบบ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
            $db_con = null;
            return;
        }

        //************************** Select List of Confirm Print Listt in Confirm print tags *************************//
        //**************************************************************************************************************/
        $tns = $db_con->query("SELECT * FROM tbl_semi_inven_transactions_mst WHERE t_sem_list_conf_no = '$list_conf_no' ORDER BY t_sem_uniq");
        while($tnsResult = $tns->fetch(PDO::FETCH_ASSOC)){
            $t_sem_job_no = $tnsResult['t_sem_job_no'];
            $job_no .= $tnsResult['t_sem_job_no'] . ', ';

            //************************** Update job fg set *************************//
            //***********************************************************************/
            try {
                $wd = $db_con->prepare("SELECT job_ffmc_usage, job_fg_usage FROM tbl_job_mst WHERE job_no = :job_no");
                $wd->bindParam(':job_no', $tnsResult['t_sem_job_no']);
                $wd->execute();
                $wdResult = $wd->fetch(PDO::FETCH_ASSOC);

                $fg_set = 0;
                $fg_set_per_job = 0;
                $fg_qty = 0;


                //todo Check combine machine >>>>>>>>>>
                $ccmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Combine'");
                $ccmc->bindParam(':job_no', $tnsResult['t_sem_job_no']);
                $ccmc->execute();
                $ccmcResult = $ccmc->fetch(PDO::FETCH_ASSOC);

                //todo Check Assembly >>>>>>>>>>
                $asmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Assembly'");
                $asmc->bindParam(':job_no', $tnsResult['t_sem_job_no']);
                $asmc->execute();
                $asmcResult = $ccmc->fetch(PDO::FETCH_ASSOC);

                if($asmcResult['list'] > 0){
                    $fg_set_per_job = floor($list_confirm_fg * $wdResult['job_ffmc_usage']);
                    $fg_set = floor($fg_set_per_job / $wdResult['job_ffmc_usage']);
                    $fg_qty = floor($fg_set_per_job * $wdResult['job_fg_usage']);
                }else{
                    $fg_set_per_job = $list_confirm_fg;
                    $fg_set = floor( $fg_set_per_job / $wdResult['job_ffmc_usage']);
                    $fg_qty = floor($fg_set_per_job * $wdResult['job_fg_usage']);
                }

                $up = $db_con->prepare("UPDATE tbl_job_mst SET job_plan_fg_set += :fg_set, job_plan_fg_set_per_job += :set_per_job, job_plan_fg_qty += :fg_qty WHERE job_no = '$t_sem_job_no'");
                $up->bindParam(':fg_set', $fg_set);
                $up->bindParam(':set_per_job', $fg_set_per_job);
                $up->bindParam(':fg_qty', $fg_qty);
                $up->execute();
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดท Job FG Set ได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            try {
                //todo >>>>>>>>>> CHeck Tigthing Machine in Job Operation >>>>>>>>>>
                //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                $chck = $db_con->query("SELECT * FROM tbl_job_operation WHERE ope_job_no = '$t_sem_job_no' AND ope_mc_code = 'TG'");
                $chckResult = $chck->fetch(PDO::FETCH_ASSOC);
                if($chckResult['ope_mc_code'] == ""){
                    echo json_encode(array('code'=>400, 'message'=>'ไม่พบเครื่องมัดสำหรับ Job number นี้ ตรวจสอบข้อมูลและลองใหม่อีกครั้ง'));
                    $db_con = null;
                    return;
                }

                if($chckResult['ope_fg_sendby'] < 0 || ($chckResult['ope_fg_sendby'] - $quantity) < 0){
                    echo json_encode(array('code'=>400, 'message'=>'ข้อมูลไม่ถูกต้อง จำนวนหลังจากทำการมัดยอดมีน้อยกว่า 0 ไม่สามารถดำเนินการได้'));
                    $db_con = null;
                    return;
                }
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบข้อมูลเครื่องมัดได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            if($condi){    
                try {
                    $opi = $db_con->prepare(
                        "UPDATE tbl_job_operation
                         SET ope_fg_ttl += :confirm_fg,
                             ope_ng_ttl += :confirm_ng,
                             ope_fg_sendby = FLOOR(ope_fg_sendby - :quantity),
                             ope_status = CASE WHEN ope_fg_sendby - FLOOR(:sendby) = 0 THEN 'done' ELSE ope_status END,
                             start_datetime = CASE WHEN start_datetime IS NULL THEN :start_datetime ELSE start_datetime END,
                             ope_finish_datetime = CASE WHEN ope_fg_sendby - FLOOR(:sendby2) = 0 THEN :finish_datetime ELSE ope_finish_datetime END,
                             ope_finish_by = CASE WHEN ope_fg_sendby - FLOOR(:sendby3) = 0 THEN :finish_by ELSE ope_finish_by END
                         WHERE ope_job_no = :ope_job_no AND ope_mc_code = 'TG'"
                    );
                    $opi->bindParam(':ope_job_no', $t_sem_job_no);
                    $opi->bindParam(':confirm_fg', $list_confirm_fg);
                    $opi->bindParam(':confirm_ng', $list_confirm_ng);
                    $opi->bindParam(':quantity', $quantity);
                    $opi->bindParam(':sendby', $quantity);
                    $opi->bindParam(':sendby2', $quantity);
                    $opi->bindParam(':sendby3', $quantity);
                    $opi->bindParam(':start_datetime', $start_datetime);
                    $opi->bindParam(':finish_datetime', $end_datetime);
                    $opi->bindParam(':finish_by', $mrp_user_name_mst);
                    $opi->execute();
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล job operations ได้ ' . $e->getMessage()));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }
                
                try {
                    $opp = $db_con->query(
                        "INSERT INTO tbl_job_confirm_passing_transaction(pass_job_no, pass_mc_orders, pass_mc_code, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime)
                         SELECT '$t_sem_job_no', ope_orders, '$machine_code', ope_in, ope_out, $list_confirm_fg, $list_confirm_ng, 'Passed', '$mrp_user_name_mst', '$buffer_datetime', '$start_datetime', '$end_datetime' FROM tbl_job_operation WHERE ope_job_no = '$t_sem_job_no' AND ope_mc_code = 'TG'"
                    );
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทืกข้อมูล Passing transactions ได้ ' . $e->getMessage()));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }
            }else{
                try {
                    $opi = $db_con->prepare(
                        "UPDATE tbl_job_operation
                         SET ope_ng_ttl += :confirm_ng,
                             ope_fg_sendby = FLOOR(ope_fg_sendby - :quantity),
                             ope_status = CASE WHEN ope_fg_sendby - FLOOR(:sendby) = 0 THEN 'done' ELSE ope_status END,
                             start_datetime = CASE WHEN start_datetime IS NULL THEN :start_datetime ELSE start_datetime END,
                             ope_finish_datetime = CASE WHEN ope_fg_sendby - FLOOR(:sendby2) = 0 THEN :finish_datetime ELSE ope_finish_datetime END,
                             ope_finish_by = CASE WHEN ope_fg_sendby - FLOOR(:sendby3) = 0 THEN :finish_by ELSE ope_finish_by END
                         WHERE ope_job_no = :ope_job_no AND ope_mc_code = 'TG'"
                    );
                    $opi->bindParam(':ope_job_no', $t_sem_job_no);
                    $opi->bindParam(':confirm_ng', $list_confirm_ng);
                    $opi->bindParam(':quantity', $quantity);
                    $opi->bindParam(':sendby', $quantity);
                    $opi->bindParam(':sendby2', $quantity);
                    $opi->bindParam(':sendby3', $quantity);
                    $opi->bindParam(':start_datetime', $start_datetime);
                    $opi->bindParam(':finish_datetime', $end_datetime);
                    $opi->bindParam(':finish_by', $mrp_user_name_mst);
                    $opi->execute();
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล job operations ได้ ' . $e->getMessage()));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }

                try {
                    $opp = $db_con->query(
                        "INSERT INTO tbl_job_confirm_passing_transaction(pass_job_no, pass_mc_orders, pass_mc_code, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime)
                         SELECT '$t_sem_job_no', ope_orders, '$machine_code', ope_in, ope_out, $list_confirm_fg, $list_confirm_ng, 'Confirm NG', '$mrp_user_name_mst', '$buffer_datetime', '$start_datetime', '$end_datetime' FROM tbl_job_operation WHERE ope_job_no = '$t_sem_job_no' AND ope_mc_code = 'TG'"
                    );
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทืกข้อมูล Passing and tags transactions ได้ ' . $e->getMessage()));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }
            }

            //todo >>>>>>>>>>>>>>>>>>>> CHeck closed job >>>>>>>>>>>>>>>>>>>>
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $exis = $db_con->query("SELECT COUNT(ope_job_no) AS exis FROM tbl_job_operation WHERE ope_job_no = '$t_sem_job_no' AND ope_status = 'pending'");
            $exisResult = $exis->fetch(PDO::FETCH_ASSOC);
            if($exisResult['exis'] == 0){
                $cls = $db_con->query("UPDATE tbl_job_mst SET job_machine_now_in = '', job_now_in = '', job_status = 'complete', job_complete_datetime = '$end_datetime' WHERE job_no = '$t_sem_job_no'");
            }
        }


        if($condi){
            $condi_quantity = intval($list_confirm_fg + $list_exceed_fg);
            //************************** Time to generate the pallet ID *************************//
            //************************************************************************************/
            try {
                $prefix = 'PLM' . $buffer_year_2digit . $buffer_month;
                $cps = $db_con->query("SELECT COUNT(pallet_id) AS count_pallet FROM tbl_fg_inven_mst WHERE pallet_id LIKE '$prefix%'");
                $cpsResult = $cps->fetch(PDO::FETCH_ASSOC);
                $pallet_id = SetPrefix($prefix, $cpsResult['count_pallet']);
                $job_no = rtrim($job_no, ', ');


                $ins = $db_con->query(
                    "INSERT INTO tbl_fg_inven_mst(pallet_id, pallet_lot_no, pallet_receive_qty, pallet_stock_qty, pallet_used_qty, pallet_status, pallet_gen_datetime, pallet_gen_by, pallet_bom_uniq, pallet_fg_codeset, pallet_fg_code, pallet_part_customer, pallet_comp_code, pallet_fg_description, pallet_cus_code, pallet_project, pallet_ship_to_type, pallet_job_set, pallet_aging_date, pallet_exceed_qty)
                    VALUES('$pallet_id', '$list_conf_no', $condi_quantity, 0, 0, 'Prepare', '$buffer_datetime', '$mrp_user_name_mst', '".$listResult['list_bom_id']."', '".$listResult['list_fg_codeset']."', '".$listResult['list_fg_code']."', '".$listResult['list_part_customer']."', '".$listResult['list_comp_code']."', '".$listResult['list_fg_description']."', '".$listResult['list_cus_code']."', '".$listResult['list_project']."', '".$listResult['list_ship_to_type']."', '$job_no', '$buffer_date', $list_exceed_fg)"
                );
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถสร้าง Pallet ID สำหรับเก็บงาน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง ' . $e->getMessage()));
                $db_con->rollBack();
                $db_con = null;
                return;
            }

            //************************** Generate the pallet transactions *************************//
            //**************************************************************************************/
            try {
                $dest = array(
                    'pallet_id' => $pallet_id,
                    'location_id' => '',
                    'lot_no' => $list_conf_no,
                    'lot_token' => $listResult['list_token'],
                    'bom_uniq' => $listResult['list_bom_id'],
                    'fg_codeset' => $listResult['list_fg_codeset'],
                    'fg_code' => $listResult['list_fg_code'],
                    'comp_code' => $listResult['list_comp_code'],
                    'part_customer' => $listResult['list_part_customer'],
                    'fg_description' => $listResult['list_fg_description'],
                    'cus_code' => $listResult['list_cus_code'],
                    'project' => $listResult['list_project'],
                    'ship_to_type' => $listResult['list_ship_to_type'],
                    'qty' => $condi_quantity,
                    'type' => 'Movement',
                    'status' => 'Warehouse Preparing',
                    'datetime' => $buffer_datetime,
                    'by' => $mrp_user_name_mst,
                    'remarks' => "งานเกินแผนผลิต $list_exceed_qty ชิ้น"
                );
        
                $trans = InsertTransactions($db_con, $dest);
                if(!$trans){
                    echo json_encode(array('code'=>'400', 'message'=>'ไม่สามารถบันทึกข้อมูล Pallet WH Transactions ได้ ' . GetErrorMessage($db_con)));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }
            } catch(Exception $e ) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึก Pallet Transactions ได้ ' . $e->getMessage()));
                $db_con->rollBack();
                $db_con = null;
                return;
            }
        }

        //************************** Update cover sheet details *************************//
        //********************************************************************************/
        try {
            $con = $db_con->prepare(
                "UPDATE tbl_confirm_print_list
                 SET list_pending_qty -= :quantity,
                     list_current_qty += :confirm_fg,
                     list_ng_qty += :confirm_ng,
                     list_status = 'Pending',
                     list_tig_datetime = :buffer_datetime,
                     list_tig_by = :tig_by
                 WHERE list_conf_no = :list_conf_no"
            );
            $con->bindParam(':quantity', $quantity);
            $con->bindParam(':confirm_fg', $list_confirm_fg);
            $con->bindParam(':confirm_ng', $list_confirm_ng);
            $con->bindParam(':buffer_datetime', $buffer_datetime);
            $con->bindParam(':tig_by', $mrp_user_name_mst);
            $con->bindParam(':list_conf_no', $list_conf_no);
            $con->execute();
        } catch(Exception $e ) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Covert sheet details ได้ ' . $e->getMessage()));
            $db_con->rollBack();
            $db_con = null;
            return;
        }

        if($condi){
            $json = array('code'=>'200', 'message'=>'ดำเนินการยืนยันการมัดงานและออกเลข Pallet สำเร็จ Pallet เลขที่ ==> ' . $pallet_id, 'route'=>"$CFG->printed_fg_pallet?pallet_id=$pallet_id");
        }else{
            $json = array('code'=>'200', 'message'=>'ดำเนินการยืนยันการ Confirm NG สำเร็จ');
        }


        echo json_encode($json);
        $db_con->commit();
        $db_con = null;
        return;
    }else if($protocol == "ConfirmReturnCombineset"){
        try {
            //************************** CHeck cover sheet details *************************//
            //*******************************************************************************/
            $list = $db_con->prepare("SELECT * FROM tbl_confirm_print_list WHERE list_conf_no = :list_conf_no");
            $list->bindParam(':list_conf_no', $list_conf_no);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['list_pending_qty'] != $listResult['list_receive_qty']){
                echo json_encode(array('code'=>400, 'message'=>'จำนวนที่รอการมัดไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            //************************** Loop to get the job and return to semi inventory *************************//
            //******************************************************************************************************/
            try {
                $cov = $db_con->query(
                    "SELECT A.*, B.job_merge_mc, B.job_merge_in, B.job_merge_out, B.job_ffmc_usage, B.job_fg_usage
                     FROM tbl_confirm_print_tags AS A 
                     LEFT JOIN tbl_job_mst AS B ON A.conf_job_no = B.job_no
                     WHERE conf_code = '$list_conf_no'"
                );
                while($covResult = $cov->fetch(PDO::FETCH_ASSOC)){
                    $job_no = $covResult['conf_job_no'];

                    $sem = $db_con->prepare(
                        "UPDATE tbl_semi_inven_mst
                         SET sem_stock_qty += :quantity1,
                             sem_used_qty -= :quantity2,
                             sem_status = 'Active',
                             sem_update_by = :sem_update_by,
                             sem_update_datetime = :sem_update_datetime
                         WHERE sem_job_no = :sem_job_no"
                    );
                    $sem->bindParam(':quantity1', $quantity);
                    $sem->bindParam(':quantity2', $quantity);
                    $sem->bindParam(':sem_update_by', $mrp_user_name_mst);
                    $sem->bindParam(':sem_update_datetime', $buffer_datetime);
                    $sem->bindParam(':sem_job_no', $job_no);
                    $sem->execute();

                    $delt = $db_con->query("DELETE FROM tbl_semi_inven_transactions_mst WHERE t_sem_list_conf_no = '$list_conf_no' AND t_sem_job_no = '$job_no'");
                    $opel = $db_con->prepare(
                        "UPDATE tbl_job_operation SET ope_status = 'pending', ope_fg_sendby -= :quantity4 WHERE ope_job_no = :job_no1 AND ope_mc_code = 'TG';
                         UPDATE tbl_job_operation SET ope_fg_sendby += :quantity2, ope_status = 'pending', ope_fg_ttl -= :quantity3 WHERE ope_job_no = :job_no2 AND ope_mc_code = :ope_mc_code"
                    );
                    $opel->bindParam(':ope_mc_code', $covResult['job_merge_mc']);
                    $opel->bindParam(':job_no1', $job_no);
                    $opel->bindParam(':job_no2', $job_no);
                    // $opel->bindParam(':quantity1', $quantity);
                    $opel->bindParam(':quantity2', $quantity);
                    $opel->bindParam(':quantity3', $quantity);
                    $opel->bindParam(':quantity4', $quantity);
                    $opel->execute();
                    

                    //************************** Update return job FG Set *************************//
                    //******************************************************************************/
                    try {
                        $fg_set = 0;
                        $fg_set_per_job = 0;
                        $fg_qty = 0;

                        //todo Check combine machine >>>>>>>>>>
                        $ccmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Combine'");
                        $ccmc->bindParam(':job_no', $job_no);
                        $ccmc->execute();
                        $ccmcResult = $ccmc->fetch(PDO::FETCH_ASSOC);

                        //todo Check Assembly >>>>>>>>>>
                        $asmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Assembly'");
                        $asmc->bindParam(':job_no', $job_no);
                        $asmc->execute();
                        $asmcResult = $ccmc->fetch(PDO::FETCH_ASSOC);

                        if($asmcResult['list'] > 0){
                            $fg_set_per_job = floor($quantity / $wdResult['job_ffmc_usage']);
                            $fg_set = floor($fg_set_per_job * $wdResult['job_ffmc_usage']);
                            $fg_qty = floor($fg_set_per_job / $wdResult['job_fg_usage']);
                        }else{
                            $fg_set_per_job = $quantity;
                            $fg_set = floor( $fg_set_per_job * $wdResult['job_ffmc_usage']);
                            $fg_qty = floor($fg_set_per_job * $wdResult['job_fg_usage']);
                        }

                        $jset = $db_con->prepare("UPDATE tbl_job_mst SET job_plan_fg_set -= :fg_set, job_plan_fg_set_per_job -= :fg_set_per_job, job_plan_fg_qty -= :fg_qty, job_status = 'on production' WHERE job_no = '$job_no'");
                        $jset->bindParam(':fg_set', $fg_set);
                        $jset->bindParam(':fg_set_per_job', $fg_set_per_job);
                        $jset->bindParam(':fg_qty', $fg_qty);
                        $jset->execute();
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล Update Job SET ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }
                }


                try {
                    $liv = $db_con->query("UPDATE tbl_confirm_print_tags SET conf_status = 'Cancel', remarks = '$remarks' WHERE conf_code = '$list_conf_no'");
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการ Cancel Confirm Print Tags ได้  ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }

                try {
                    $des = $db_con->query("UPDATE tbl_confirm_print_list SET list_status = 'Cancel', list_remarks = '$remarks', list_pending_qty = 0 WHERE list_conf_no = '$list_conf_no'");
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการ Cancel Confirm Print Tags ได้  ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึก Combine transactions ได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการ Return combine set สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;

        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการบันทึกข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }

?>