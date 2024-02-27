<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    
    $job_no = isset($_POST['job_no']) ? trim($_POST['job_no']) : '';
    $machine_type = isset($_POST['machine_type']) ? $_POST['machine_type'] : '';
    $machine_code = isset($_POST['machine_code']) ? $_POST['machine_code'] : '';
    $ope_fg_ttl = isset($_POST['ope_fg_ttl']) ? $_POST['ope_fg_ttl'] : '';
    $ope_ng_ttl = isset($_POST['ope_ng_ttl']) ? $_POST['ope_ng_ttl'] : '';
    $start_datetime = isset($_POST['start_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['start_datetime'])) : '';
    $end_datetime = isset($_POST['end_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['end_datetime'])) : '';
    $start_setup = isset($_POST['start_setup']) ? date('Y-m-d H:i:s', strtotime($_POST['start_setup'])) : '';
    $end_setup = isset($_POST['end_setup']) ? date('Y-m-d H:i:s',  strtotime($_POST['end_setup'])) : '';

    $json = [];


    if($protocol == "ListPassingMachine"){
        try {
            $des = $db_con->query("SELECT job_status, job_fg_description FROM tbl_job_mst WHERE job_no = '$job_no'");
            $desResult = $des->fetch(PDO::FETCH_ASSOC);

            if($des->rowCount() == 0){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูล Job ดังกล่าวบนระบบ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            if(ucwords($desResult['job_status']) == "Prepare"){
                echo json_encode(array('code'=>400, 'message'=>'กรุณายืนยันการผลิตงานที่เมนู Work order ก่อนดำเนินการ Confirm Station'));
                $db_con = null;
                return;
            }
            
            if(ucwords($desResult['job_status']) == "Complete"){
                echo json_encode(array('code'=>400, 'message'=>'มีการปิด Job ไปแล้ว ไม่สามารถ Confirm ยอดเพิ่มได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            
            if(ucwords($desResult['job_status']) != "On Production"){
                echo json_encode(array('code'=>400, 'message'=>'Job ไม่อยู่ในสถานะที่สามารถดำเนินการได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $list = $db_con->query(
                "SELECT ope_mc_code, machine_type_name
                 FROM tbl_job_operation AS A
                 LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code
                 WHERE ope_job_no = '$job_no' AND ope_status = 'pending' AND (ope_round IS NULL OR ope_round = 0)
                 ORDER BY ope_orders"
            );
            
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $listResult); 
            }
            

            if(count($json) > 0){
                echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json, 'job_fg_description'=>$desResult['job_fg_description']));
                $db_con = null;
                return;
            }else{
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบรายการรอ Confirm station หาก job ยังไม่ Complete รายการอาจรอมัดหรืออยู่ที่ Process combine set ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
        } catch(Exception $e){
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลข้อมูลได้ ' . $e->getMessage()));
        }
    }else if($protocol == "CHeckSetupTime"){
        try {
            $des = $db_con->query("SELECT ope_in, ope_out, ope_fg_sendby, ope_setting_start_datetime FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_mc_code = '$machine_type'");
            $desResult = $des->fetch(PDO::FETCH_ASSOC);
            $display = $desResult['ope_setting_start_datetime'] == NULL ? 'block' : 'none';
            // $display = 'block';

            echo json_encode(array('code'=>200, 'message'=>'ok', 'display'=>$display, 'ope_in'=>$desResult['ope_in'], 'ope_out'=>$desResult['ope_out'], 'ope_fg_sendby'=>$desResult['ope_fg_sendby']));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบเวลาตั้งค่าเครื่องจักรได้[PDO] ' . $e->getMessage()));
        }
    }else if($protocol == "ConfirmStation"){
        try {
            //********** CHeck job operation details ************/
            //***************************************************/
            $total_sendby = intval($ope_fg_ttl + $ope_ng_ttl);

            $list = $db_con->query("SELECT A.*, B.job_status FROM tbl_job_operation AS A LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no WHERE ope_job_no = '$job_no' AND ope_mc_code = '$machine_type'");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['job_status'] != 'on production'){
                echo json_encode(array('code'=>400, 'message'=>'สถานะของ Job ไม่พร้อมสำหรับการ Confirm ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            if($listResult['ope_fg_sendby'] < $total_sendby){
                echo json_encode(array('code'=>400, 'message'=>'ผลรวมของ FG และ NG มีค่ามากกว่าจำนวนที่รอ Confirm สำหรับ Station นี้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง '));
                $db_con = null;
                return;
            }

            try {
                $up = $db_con->query(
                    "UPDATE tbl_job_operation
                    SET ope_fg_ttl += $ope_fg_ttl,
                        ope_ng_ttl += $ope_ng_ttl,
                        ope_fg_sendby -= $total_sendby,
                        start_datetime = CASE WHEN ope_fg_ttl = 0 THEN '$buffer_datetime' ELSE start_datetime END,
                        start_by = CASE WHEN ope_fg_ttl = 0 THEN '$mrp_user_code_mst' ELSE start_by END,
                        ope_finish_datetime = CASE WHEN ope_fg_sendby - $total_sendby = 0 THEN '$buffer_datetime' ELSE ope_finish_datetime END,
                        ope_finish_by = CASE WHEN ope_fg_sendby - $total_sendby = 0 THEN '$mrp_user_code_mst' ELSE ope_finish_by END,
                        ope_status = CASE WHEN ope_fg_sendby - $total_sendby = 0 THEN 'done' ELSE ope_status END,
                        ope_setting_start_datetime = CASE WHEN ope_setting_start_datetime IS NULL THEN '$start_setup' ELSE ope_setting_start_datetime END,
                        ope_setting_end_datetime = CASE WHEN ope_setting_end_datetime IS NULL THEN '$end_setup' ELSE ope_setting_end_datetime END
                    WHERE ope_job_no = '$job_no' AND ope_mc_code = '$machine_type'"
                );
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Confirm station ได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            //**************** Insert to job operation transactions *****************/
            //***********************************************************************/
            $tns = $db_con->query(
                "INSERT INTO tbl_job_confirm_passing_transaction(pass_job_no, pass_mc_orders, pass_mc_code, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime)
                 SELECT '$job_no', ope_orders, '$machine_code', ope_in, ope_out, $ope_fg_ttl, $ope_ng_ttl, 'Passed', '$mrp_user_name_mst', '$buffer_datetime', '$start_datetime', '$end_datetime' FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_mc_code = '$machine_type'"
            );

            //todo >>>>>>>>>> Get the next stations >>>>>>>>>>
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $nxt = $listResult['ope_orders'] + 1;
            $st = $db_con->query("SELECT * FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_orders = '$nxt'");
            $stResult = $st->fetch(PDO::FETCH_ASSOC);

            $quantity = floor(($ope_fg_ttl / $stResult['ope_in']) * $stResult['ope_out']);

            if($ope_fg_ttl > 0){
                if($stResult['ope_round'] == 1){
                    //!! เข้านี่แสดงว่าเครื่องถัดไปเป็น combine set ให้เอาลง tbl_semi_inven_mst ได้เลย
                    $tsem;
                    $csem = $db_con->query("SELECT sem_job_no FROM tbl_semi_inven_mst WHERE sem_job_no = '$job_no'");
                    $csemResult = $csem->fetch(PDO::FETCH_ASSOC);
                    if($csemResult['sem_job_no'] == NULL){
                        $seminv = $db_con->prepare(
                            "INSERT INTO tbl_semi_inven_mst(sem_job_no, sem_receive_qty, sem_stock_qty, sem_used_qty, sem_status, sem_gen_datetime, sem_gen_by, sem_update_by, sem_update_datetime)
                            VALUES('$job_no', $quantity, $quantity, 0, 'Active', '$buffer_datetime', '$mrp_user_name_mst', '$mrp_user_name_mst', '$buffer_datetime');
                            INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by)
                            VALUES('$job_no', '$quantity', 'IN', 'Receive', '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                    }else{
                        $seminv = $db_con->prepare(
                            "UPDATE tbl_semi_inven_mst
                            SET sem_receive_qty += $quantity,
                                sem_stock_qty += $quantity,
                                sem_status = 'Active',
                                sem_update_datetime = '$buffer_datetime',
                                sem_update_by = '$mrp_user_name_mst'
                            WHERE sem_job_no = '$job_no';
                            INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by)
                            VALUES('$job_no', '$quantity', 'IN', 'Receive', '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                    }
                    $seminv->execute();
                    
                    //todo >>>>>>>>>> Update tbl_job_mst set job_fg_set_per_job and machine_now_in >>>>>>>>>>
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    $main = $db_con->query(
                        "UPDATE tbl_job_mst
                        SET job_plan_fg_set_per_job += $ope_fg_ttl, job_plan_fg_qty += $ope_fg_ttl
                        WHERE job_no = '$job_no'"
                    );
                }

                $nextRest = $db_con->query("UPDATE tbl_job_operation SET ope_fg_sendby += $quantity, ope_status = 'pending' WHERE ope_job_no = '$job_no' AND ope_orders = $nxt");

                $minpen = $db_con->query("SELECT TOP(1) ope_mc_code FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_status = 'pending' ORDER BY ope_orders");
                $minpenResult = $minpen->fetch(PDO::FETCH_ASSOC);

                $up = $db_con->query("UPDATE tbl_job_mst SET job_machine_now_in = '".$minpenResult['ope_mc_code']."' WHERE job_no = '$job_no'");
            }else{
                //!----------------------------------- ลงนี่แสดงว่า Confirm เป็น NG มา ให้เช็คว่ามี sendby อยู่ที่ไหนมั้ย ถ้าไม่มีให้ปิด job ได้เลย
                $senb = $db_con->query("SELECT ope_mc_code FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_fg_sendby > 0");
                $senbResult = $senb->fetchAll(PDO::FETCH_ASSOC);

                if(count($senbResult) == 0){
                    $cls = $db_con->query("UPDATE tbl_job_mst SET job_machine_now_in = '', job_now_in = '', job_remarks = 'close by confirm NG', job_status = 'complete', job_complete_datetime = '$buffer_datetime' WHERE job_no = '$job_no'");
                    $updone = $db_con->query("UPDATE tbl_job_operation SET ope_status = 'done' WHERE ope_job_no = '$job_no'");
                }
            }
            
            echo json_encode(array('code'=>200, 'message'=>"Confirm station สำหรับ Job number $job_no สำเร็จ FG จำนวน $ope_fg_ttl ชิ้น NG $ope_ng_ttl ชิ้น"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'1 - ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }


    }
?>