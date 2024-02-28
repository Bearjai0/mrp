<?php
    require_once("../../../session.php");
    require_once("../../fg-inven/route-mod.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $job_no = isset($_POST['job_no']) ? $_POST['job_no'] : '';
    $bom_uniq = isset($_POST['bom_uniq']) ? $_POST['bom_uniq'] : '';
    $machine_code = isset($_POST['machine_code']) ? $_POST['machine_code'] : '';
    $machine_type_code = isset($_POST['machine_type_code']) ? $_POST['machine_type_code'] : '';
    $start_datetime = isset($_POST['start_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['start_datetime'])) : '';
    $end_datetime = isset($_POST['end_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['end_datetime'])) : '';
    $setting_start_datetime = isset($_POST['setting_start_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['setting_start_datetime'])) : 'NULL';
    $setting_end_datetime = isset($_POST['setting_end_datetime']) ? date('Y-m-d H:i:s', strtotime($_POST['setting_end_datetime'])) : 'NULL';

    $combine_fg = isset($_POST['combine_fg']) ? $_POST['combine_fg'] : '';
    $combine_ng = isset($_POST['combine_ng']) ? $_POST['combine_ng'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

    $cov_no = isset($_POST['cov_no']) ? $_POST['cov_no'] : '';
    $condi = isset($_POST['condi']) ? $_POST['condi'] : '';
    $mint = isset($_POST['mint']) ? $_POST['mint'] : '';

    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $json = array();


    if($protocol == "SyncSemiInventory"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY sem_uniq DESC) AS list, A.sem_job_no, A.sem_stock_qty, A.sem_receive_qty, A.sem_used_qty, A.sem_gen_datetime, A.sem_gen_by, A.sem_status,
                B.job_cus_code, B.job_fac_type, B.job_project, B.job_bom_id, B.job_fg_code, B.job_fg_codeset, B.job_fg_description, B.job_part_customer, B.job_comp_code, B.job_ship_to_type, B.job_plan_date, B.job_ctn_code_normal, B.job_packing_usage,
                C.class_color,
                D.machine_type_name
                FROM tbl_semi_inven_mst AS A
                LEFT JOIN tbl_job_mst AS B ON A.sem_job_no = B.job_no
                LEFT JOIN tbl_status_color AS C ON A.sem_status = C.hex_status
                LEFT JOIN tbl_machine_type_mst AS D ON B.job_merge_mc = D.machine_type_code
                WHERE sem_status = 'Active' AND sem_stock_qty > 0
                ORDER BY sem_uniq DESC"
            );
            $json = $list->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e){
            echo json_encode(array('code'=>200, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "CHeckMasterSet"){
        try {
            foreach($job_no as $item){
                $mint .= "'" . $item . "',";
            }
            
            $mint = rtrim($mint, ",");
    
            $dim = $db_con->query("SELECT job_fg_codeset, job_project, job_merge_mc FROM tbl_job_mst WHERE job_no IN($mint) GROUP BY job_fg_codeset, job_project, job_merge_mc");
            $dimResult = $dim->fetchAll(PDO::FETCH_ASSOC);
    
            if(count($dimResult) > 1){
                echo json_encode(array('code'=>400, 'message'=>'ข้อมูลไม่ถูกต้อง พบข้อมูล Codeset, Project หรือเครื่องจักร Combine ที่ไม่ตรงกันของ Job ที่เลือก ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            
            // if(count($job_no) > 1){
            //     $mat = $db_con->prepare("SELECT COUNT(fg_codeset) AS count_check FROM tbl_bom_mst WHERE fg_codeset = :fg_codeset AND project = :project AND fg_type = 'Component' AND bom_status = 'Active'");
            //     $mat->bindParam(':fg_codeset', $dimResult['job_fg_codeset']);
            //     $mat->bindParam(':project', $dimResult['job_project']);
            //     $mat->execute();
            //     $matResult = $mat->fetchAll(PDO::FETCH_ASSOC);

            //     if(count($matResult) != count($job_no)){
            //         echo json_encode(array('code'=>400, 'message'=>'ข้อมูลไม่ถูกต้อง จำนวน job ที่ต้องการ Combine กับจำนวน Component ของ BOM ไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
            //         $db_con = null;
            //         return;
            //     }
            // }
    
            echo json_encode(array('code'=>200, 'message'=>'ok'));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "CombineMaster"){
        try {
            $quantity = $combine_fg + $combine_ng;
            if($combine_fg == 0 && $combine_ng > 0){
                $condi = false;
            }else{
                $condi = true;
            }


            if($condi){
                try {
                    //todo >>>>>>> Create new Picking Sheet Number
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    $prefix = 'SP' . $buffer_year_2digit . $buffer_month;
                    $covp = $db_con->query("SELECT COUNT(list_conf_no) AS sp_count FROM tbl_confirm_print_list WHERE list_conf_no LIKE '%$prefix%'");
                    $covpResult = $covp->fetch(PDO::FETCH_ASSOC);
                    $cov_no = SetPrefix($prefix, $covpResult['sp_count']);
                    $cov_token = base64_encode($cov_no);
        
        
                    //! Get Master uniq details >>>>>>>>>>
                    //! >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    $u_niq = "";
                    if($bom_uniq == "FG0001-03090"){
                        $exp = explode(",", $job_no)[0];
                        $u_niq = "SELECT job_bom_id AS bom_uniq, job_ft2_usage AS fg_ft2, job_fg_codeset AS fg_codeset, job_fg_code AS fg_code, job_part_customer AS part_customer, job_comp_code AS comp_code, job_fg_description AS fg_description, job_cus_code AS cus_code, job_project AS project, job_ship_to_type AS ship_to_type, job_packing_usage AS packing_usage FROM tbl_job_mst WHERE job_no = '$exp'";
                    }else{
                        $u_niq = "SELECT bom_uniq, fg_ft2, fg_codeset, fg_code, part_customer, comp_code, fg_description, cus_code, project, ship_to_type, packing_usage FROM tbl_bom_mst WHERE bom_uniq = '$bom_uniq'";
                    }
                    $exe_uniq = $db_con->query($u_niq);
                    $uniqResult = $exe_uniq->fetch(PDO::FETCH_ASSOC);
                    $ft2 = $uniqResult['fg_ft2'] ? $uniqResult['fg_ft2'] : 0;
                    $fg_ft2 = $uniqResult['fg_ft2'] * $combine_fg;
                    // $pack_usage = intval($uniqResult['packing_usage']);
        
                    $in_cov = $db_con->query(
                        "INSERT INTO tbl_confirm_print_list(list_conf_no, list_conf_type, list_token, list_bom_id, list_fg_codeset, list_fg_code, list_part_customer, list_comp_code, list_fg_description, list_cus_code, list_project, list_ship_to_type, list_receive_qty, list_pending_qty, list_current_qty, list_used_qty, list_packing_usage, list_status, list_conf_datetime, list_conf_by, list_fg_ft2, list_total_fg_ft2)
                         VALUES('$cov_no', 'MRP', '$cov_token', '$bom_uniq', '".$uniqResult['fg_codeset']."', '".$uniqResult['fg_code']."', '".$uniqResult['part_customer']."', '".$uniqResult['comp_code']."', '".$uniqResult['fg_description']."', '".$uniqResult['cus_code']."', '".$uniqResult['project']."', '".$uniqResult['ship_to_type']."', $combine_fg, $combine_fg, 0, 0, 0, 'Prepare', '$buffer_datetime', '$mrp_user_name_mst', '$ft2', '$fg_ft2')"
                    );
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการสร้าง Cover Sheet ได้ ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }
            }

            try {
                $mts = $db_con->prepare("SELECT machine_type FROM tbl_machine_mst WHERE machine_code = :machine_code");
                $mts->bindParam(':machine_code', $machine_code);
                $mts->execute();
                $mtsResult = $mts->fetch(PDO::FETCH_ASSOC);
                $machine_type_code = $mtsResult['machine_type'];
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบข้อมูลเครื่องจักรได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            foreach(explode(",", $job_no) as $id => $item){
                try {
                    $iList = $db_con->prepare("SELECT sem_stock_qty FROM tbl_semi_inven_mst WHERE sem_job_no = :job_no");
                    $iList->bindParam(':job_no', $item);
                    $iList->execute();
                    $iListResult = $iList->fetch(PDO::FETCH_ASSOC);
        
                    if($iListResult['sem_stock_qty'] < $quantity){
                        echo json_encode(array('code'=>400, 'message'=>'จำนวนคงเหลือของ ' . $item . ' ไม่พอสำหรับการรวมเซ็ท ตรวจสอบข้อมูลและลองใหม่อีกครั้ง'));
                        $db_con = null;
                        return;
                    }
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบข้อมูล stock semi - inventory ได้ ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }

                //************************* ตัดออกจาก Semi inventory และเพิ่มไปยัง job operation ************************/
                //**************************************************************************************************/
                try {
                    $sem = $db_con->prepare(
                        "UPDATE tbl_semi_inven_mst
                        SET sem_stock_qty -= :stock_qty,
                            sem_used_qty += :used_qty,
                            sem_status = CASE  WHEN sem_stock_qty - :quantity = 0 THEN 'Already Used' ELSE sem_status END,
                            sem_update_datetime = '$buffer_datetime',
                            sem_update_by = '$mrp_user_name_mst'
                        WHERE sem_job_no = :job_no"
                    );
                    $sem->bindParam(':stock_qty', $quantity);
                    $sem->bindParam(':used_qty', $quantity);
                    $sem->bindParam(':quantity', $quantity);
                    $sem->bindParam(':job_no', $item);
                    $sem->execute();
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล stock semi - inventory ได้ ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }
                try {
                    if($condi){
                        $sem_inv = $db_con->query(
                            "INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by, t_sem_list_conf_no)
                            VALUES('$item', $quantity, 'OUT', 'Combine Component', '$buffer_datetime', '$mrp_user_name_mst', '$cov_no')"
                        );
                    }else{
                        $sem_inv = $db_con->query(
                            "INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by)
                            VALUES('$item', $quantity, 'OUT', 'Confirm NG', '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                    }
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล stock semi - inventory transactions ได้ ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }
                
                if($condi){
                    try {
                        $sst = $db_con->query("SELECT ope_setting_start_datetime, ope_setting_end_datetime FROM tbl_job_operation WHERE ope_job_no = '$item' AND ope_mc_code = '$machine_type_code'");
                        $sstResult = $sst->fetch(PDO::FETCH_ASSOC);
                        if(($sstResult['ope_setting_start_datetime'] == "" && $setting_start_datetime == "") || ($sstResult['ope_setting_end_datetime'] == "" && $setting_end_datetime == "")){
                            echo json_encode(array('code'=>400, 'message'=>'กรุณากรอกข้อมูลเวลาในการตั้งค่าเครื่องจักรเพื่อดำเนินการต่อ'));
                            $db_con = null;
                            return;
                        }
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบ Setting time ของเครื่องจักรได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }
        
                    try {
                        $opi = $db_con->prepare(
                            "UPDATE tbl_job_operation
                            SET ope_fg_ttl += :combine_fg,
                                ope_ng_ttl += :combine_ng,
                                ope_fg_sendby = FLOOR(ope_fg_sendby - :quantity),
                                ope_status = CASE WHEN ope_fg_sendby - FLOOR(:sendby) = 0 THEN 'done' ELSE ope_status END,
                                start_datetime = CASE WHEN start_datetime IS NULL THEN :start_datetime ELSE start_datetime END,
                                ope_finish_datetime = CASE WHEN ope_fg_sendby - FLOOR(:sendby2) = 0 THEN :finish_datetime ELSE ope_finish_datetime END,
                                ope_finish_by = CASE WHEN ope_fg_sendby - FLOOR(:sendby3) = 0 THEN :finish_by ELSE ope_finish_by END,
                                ope_setting_start_datetime = CASE WHEN ope_setting_start_datetime IS NULL THEN :setting_start_datetime ELSE ope_setting_start_datetime END,
                                ope_setting_end_datetime = CASE WHEN ope_setting_end_datetime IS NULL THEN :setting_end_datetime ELSE ope_setting_end_datetime END
                            WHERE ope_job_no = '$item' AND ope_mc_code = '$machine_type_code'"
                        );
                        $opi->bindParam(':combine_fg', $combine_fg);
                        $opi->bindParam(':combine_ng', $combine_ng);
                        $opi->bindParam(':quantity', $quantity);
                        $opi->bindParam(':sendby', $quantity);
                        $opi->bindParam(':sendby2', $quantity);
                        $opi->bindParam(':sendby3', $quantity);
                        $opi->bindParam(':start_datetime', $start_datetime);
                        $opi->bindParam(':finish_datetime', $end_datetime);
                        $opi->bindParam(':finish_by', $mrp_user_name_mst);
                        $opi->bindParam(':setting_start_datetime', $setting_start_datetime);
                        $opi->bindParam(':setting_end_datetime', $setting_end_datetime);
                        $opi->execute();
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล job operations ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }
                    
                    try {
                        $opp = $db_con->query(
                            "INSERT INTO tbl_job_confirm_passing_transaction(pass_job_no, pass_mc_orders, pass_mc_code, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime)
                            SELECT $item, ope_orders, '$machine_code', ope_in, ope_out, $combine_fg, $combine_ng, 'Passed', '$mrp_user_name_mst', '$buffer_datetime', '$start_datetime', '$end_datetime' FROM tbl_job_operation WHERE ope_job_no = '$item' AND ope_mc_code = '$machine_type_code'"
                        );
        
                        $tags = $db_con->prepare(
                            "INSERT INTO tbl_confirm_print_tags(conf_code, conf_job_no, conf_packing_usage, conf_status, conf_qty, conf_datetime, conf_by)
                            VALUES(:cov_no, '$item', :packing_usage, 'combine', $combine_fg, '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                        $tags->bindParam(':cov_no', $cov_no);
                        $tags->bindParam(':packing_usage', intval($uniqResult['packing_usage']));
                        $tags->execute();
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทืกข้อมูล Passing and tags transactions ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }


                    try {
                        $upx = $db_con->query("UPDATE tbl_job_operation SET ope_fg_sendby += $combine_fg WHERE ope_job_no = '$item' AND ope_mc_code = 'TG'");
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทืกข้อมูล FG Sendby ได้ ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }

                }else{
                    try {
                        $opi = $db_con->prepare(
                            "UPDATE tbl_job_operation
                            SET ope_ng_ttl += :combine_ng,
                                ope_fg_sendby = FLOOR(ope_fg_sendby - :quantity),
                                ope_status = CASE WHEN ope_fg_sendby - FLOOR(:sendby) = 0 THEN 'done' ELSE ope_status END,
                                start_datetime = CASE WHEN start_datetime IS NULL THEN :start_datetime ELSE start_datetime END,
                                ope_finish_datetime = CASE WHEN ope_fg_sendby - FLOOR(:sendby2) = 0 THEN :finish_datetime ELSE ope_finish_datetime END,
                                ope_finish_by = CASE WHEN ope_fg_sendby - FLOOR(:sendby3) = 0 THEN :finish_by ELSE ope_finish_by END
                            WHERE ope_job_no = '$item' AND ope_mc_code = '$machine_type_code'"
                        );
                        $opi->bindParam(':combine_ng', $combine_ng);
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
                        $db_con = null;
                        return;
                    }

                    try {
                        $opp = $db_con->query(
                            "INSERT INTO tbl_job_confirm_passing_transaction(pass_job_no, pass_mc_orders, pass_mc_code, pass_in, pass_out, pass_fg, pass_ng, pass_status, pass_by, pass_datetime, pass_start_datetime, pass_end_datetime)
                            SELECT $item, ope_orders, '$machine_code', ope_in, ope_out, $combine_fg, $combine_ng, 'Confirm NG', '$mrp_user_name_mst', '$buffer_datetime', '$start_datetime', '$end_datetime' FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_mc_code = '$machine_type_code'"
                        );
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทืกข้อมูล Passing and tags transactions ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }
                }

                //todo >>>>>>>>>>>>>>>>>>>> CHeck closed job >>>>>>>>>>>>>>>>>>>>
                //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                $exis = $db_con->query("SELECT COUNT(ope_job_no) AS exis FROM tbl_job_operation WHERE ope_job_no = '$item' AND ope_fg_sendby > 0");
                $exisResult = $exis->fetch(PDO::FETCH_ASSOC);
                if($exisResult['exis'] == 0){
                    $cls = $db_con->query("UPDATE tbl_job_mst SET job_machine_now_in = '', job_now_in = '', job_status = 'complete', job_complete_datetime = '$end_datetime' WHERE job_no = '$item'");
                    $updone = $db_con->query("UPDATE tbl_job_operation SET ope_status = 'done' WHERE ope_job_no = '$item'");
                }
            }

            $bycheck = $db_con->query("SELECT ope_mc_code FROM tbl_job_operation WHERE ope_job_no IN($job_no) AND ope_round = 1 AND ope_mc_code != '$machine_type_code' GROUP BY ope_mc_code");
            $bycheckResult = $bycheck->fetch(PDO::FETCH_ASSOC);
            if($bycheckResult['ope_mc_code'] == ''){
                try {
                    $con = $db_con->prepare(
                        "UPDATE tbl_confirm_print_list
                         SET list_pending_qty -= :quantity,
                             list_current_qty += :quantity2,
                             list_status = 'Pending',
                             list_tig_datetime = :buffer_datetime,
                             list_tig_by = :tig_by
                         WHERE list_conf_no = :list_conf_no"
                    );
                    $con->bindParam(':quantity', $combine_fg);
                    $con->bindParam(':quantity2', $combine_fg);
                    $con->bindParam(':buffer_datetime', $buffer_datetime);
                    $con->bindParam(':tig_by', $mrp_user_name_mst);
                    $con->bindParam(':list_conf_no', $cov_no);
                    $con->execute();
                } catch(Exception $e ) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Covert sheet details ได้ ' . $e->getMessage()));
                    $db_con->rollBack();
                    $db_con = null;
                    return;
                }

                try {
                    $prefix = 'PLM' . $buffer_year_2digit . $buffer_month;
                    $cps = $db_con->query("SELECT COUNT(pallet_id) AS count_pallet FROM tbl_fg_inven_mst WHERE pallet_id LIKE '$prefix%'");
                    $cpsResult = $cps->fetch(PDO::FETCH_ASSOC);
                    $pallet_id = SetPrefix($prefix, $cpsResult['count_pallet']);    
    
                    $ins = $db_con->query(
                        "INSERT INTO tbl_fg_inven_mst(pallet_id, pallet_lot_no, pallet_receive_qty, pallet_stock_qty, pallet_used_qty, pallet_status, pallet_gen_datetime, pallet_gen_by, pallet_bom_uniq, pallet_fg_codeset, pallet_fg_code, pallet_part_customer, pallet_comp_code, pallet_fg_description, pallet_cus_code, pallet_project, pallet_ship_to_type, pallet_job_set, pallet_aging_date, pallet_exceed_qty)
                        VALUES('$pallet_id', '$cov_no', $combine_fg, 0, 0, 'Prepare', '$buffer_datetime', '$mrp_user_name_mst', '".$uniqResult['bom_uniq']."', '".$uniqResult['fg_codeset']."', '".$uniqResult['fg_code']."', '".$uniqResult['part_customer']."', '".$uniqResult['comp_code']."', '".$uniqResult['fg_description']."', '".$uniqResult['cus_code']."', '".$uniqResult['project']."', '".$uniqResult['ship_to_type']."', '$job_no', '$buffer_date', 0)"
                    );
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถสร้าง Pallet ID สำหรับเก็บงาน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }

                $json = array('code'=>201, 'message'=>'Combine set สำเร็จ ไม่พบเครื่องจักรรอดำเนินการต่อ ระบบจะทำการออกเอกสาร Pallet ID เลขที่ ' . $pallet_id, 'route'=>"$CFG->printed_fg_pallet?pallet_id=$pallet_id");
            }else{
                $json = array('code'=>200, 'message'=>'Combine set สำเร็จ Cover sheet เลขที่ ' . $cov_no, 'cov_no'=>$cov_no);
            }


            echo json_encode($json);
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "TransferToWIP"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_semi_inven_mst AS A LEFT JOIN tbl_job_operation AS B ON A.sem_job_no = B.ope_job_no AND ope_mc_code != 'TG' AND ope_round = 1 LEFT JOIN tbl_job_mst AS C ON A.sem_job_no = C.job_no WHERE sem_job_no = :job_no");
            $list->bindParam(':job_no', $job_no);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            $check_sem_stock_qty = intval(($quantity / $listResult['ope_in']) * $listResult['ope_out']);
            $check_ope_sendby_qty = intval(($quantity * $listResult['ope_in']) / $listResult['ope_out']);

            if($listResult['sem_stock_qty'] < $check_sem_stock_qty){
                echo json_encode(array('code'=>400, 'message'=>'จำนวนที่ต้องการ Transfer มีมากกว่าจำนวนบนระบบ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            if($quantity > $check_ope_sendby_qty){
                echo json_encode(array('code'=>400, 'message'=>'จำนวนที่ต้องการ Transfer มีมากกว่าบนระบบ ไม่สามารถดำเนินการได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $prex = 'WIP' . date('ym');
            $countList = $db_con->query("SELECT COUNT(inven_wrn) AS count_wrn FROM tbl_wip_inven_mst WHERE inven_wrn LIKE '%$prex%'");
            $countListResult = $countList->fetch(PDO::FETCH_ASSOC);
            $wrn_no = SetPrefix($prex, $countListResult['count_wrn']);


            $inw = $db_con->query(
                "INSERT INTO tbl_wip_inven_mst(inven_wrn, location_name, inven_stage, inven_bom_uniq, inven_fg_code, inven_fg_desc, inven_receive_qty, inven_stock_qty, receive_datetime, receive_by, receive_from, receive_job_ref)
                 SELECT '$wrn_no', '$location_name', 'Storage', job_bom_id, job_fg_code, job_fg_description, $quantity, $quantity, '$buffer_datetime', '$mrp_user_name_mst', 'Job', '$job_no' FROM tbl_job_mst WHERE job_no = '$job_no'"
            );

            $tnw_tns = $db_con->query(
                "INSERT INTO tbl_wip_transactions_mst(stage_wrn, stage_status, stage_qty, stage_datetime, stage_by)
                 VALUES('$wrn_no', 'Storage',$quantity, '$buffer_datetime', '$mrp_user_name_mst')"
            );

            $upsem = $db_con->query(
                "UPDATE tbl_semi_inven_mst 
                 SET sem_stock_qty -= $check_sem_stock_qty,
                     sem_used_qty += $check_sem_stock_qty,
                     sem_status = CASE WHEN sem_stock_qty - $check_sem_stock_qty = 0 THEN 'Already Used' ELSE sem_status END
                 WHERE sem_job_no = '$job_no'"
            );

            $upsemtns = $db_con->query(
                "INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_bom_uniq, t_sem_fg_codeset, t_sem_fg_code, t_sem_fg_description, t_sem_part_customer, t_sem_comp_code, t_sem_cus_code, t_sem_project, t_sem_ship_to_type, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by, t_sem_remarks)
                 SELECT job_no, job_bom_id, job_fg_codeset, job_fg_code, job_fg_description, job_part_customer, job_comp_code, job_cus_code, job_project, job_ship_to_type, $check_sem_stock_qty, 'OUT', 'Transfer To WIP', '$buffer_datetime', '$mrp_user_name_mst', '$remarks' FROM tbl_job_mst WHERE job_no = '$job_no'"
            );
            
            $tList = $db_con->prepare(
                "UPDATE tbl_job_mst SET job_wip_qty += $quantity WHERE job_no = '$job_no';
                 UPDATE tbl_job_operation
                 SET ope_fg_sendby -= $check_sem_stock_qty,
                     ope_status = CASE WHEN ope_fg_sendby - $check_sem_stock_qty = 0 THEN 'done' ELSE ope_status END,
                     ope_finish_datetime = CASE WHEN ope_fg_sendby - $check_sem_stock_qty = 0 THEN '$buffer_datetime' ELSE NULL END,
                     ope_finish_by = CASE WHEN ope_fg_sendby - $check_sem_stock_qty = 0 THEN '$mrp_user_name_mst' ELSE NULL END
                 WHERE ope_job_no = '$job_no' AND ope_mc_code = :mc_code"
            );

            $tList->bindParam(':mc_code', $listResult['job_merge_mc']);
            $tList->execute();

            $tStat = $db_con->query("SELECT COUNT(ope_job_no) AS count_check FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_fg_sendby > 0");
            $tStatResult = $tStat->fetch(PDO::FETCH_ASSOC);
            if($tStatResult['count_check'] == 0){
                $upCJ = $db_con->query(
                    "UPDATE tbl_job_mst SET job_complete_datetime = '$buffer_datetime', job_machine_now_in = '', job_now_in = '', job_status = 'complete' WHERE job_no = '$job_no';
                     UPDATE tbl_job_operation SET ope_status = 'done' WHERE ope_job_no = '$job_no'"
                );
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการ Transfer ข้อมูลสำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>