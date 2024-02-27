<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $job_no = isset($_POST['job_no']) ? $_POST['job_no'] : '';
    $job_priot = isset($_POST['job_priot']) ? $_POST['job_priot'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;

    $machine_order = isset($_POST['machine_order']) ? $_POST['machine_order'] : '';
    $ope_in = isset($_POST['ope_in']) ? $_POST['ope_in'] : '';
    $ope_out = isset($_POST['ope_out']) ? $_POST['ope_out'] : '';

    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $mainql = '';

    $json = array();


    if($protocol == "SyncPendingPlan"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY job_plan_date, job_uniq ASC) AS list, A.job_no, A.job_fac_type, A.job_plan_date, A.job_plan_qty, A.job_plan_set, A.job_bom_id, A.job_fg_code, A.job_fg_codeset, A.job_comp_code, A.job_fg_description, A.job_rm_code, A.job_rm_spec, A.job_pc_conf_by, A.job_status,
                        B.class_color, B.class_txt_color
                 FROM tbl_job_mst AS A
                 LEFT JOIN tbl_status_color AS B ON A.job_status = B.hex_status
                 WHERE job_status IN('Prepare','prepare') OR (job_type = 'WIP' AND job_status IN('pending','Pending'))
                 ORDER BY job_plan_date, job_uniq ASC"
            );
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e){
            echo json_encode(array('code'=>200, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "SyncPlanOnProcess"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY job_plan_date, job_uniq ASC) AS list, A.job_no, A.job_fac_type, A.job_plan_date, A.job_plan_qty, A.job_plan_set, A.job_bom_id, A.job_fg_code, A.job_fg_codeset, A.job_comp_code, A.job_fg_description, A.job_rm_code, A.job_rm_spec, A.job_pc_conf_by, A.job_status,
                        B.class_color, B.class_txt_color,
                        C.machine_type_name
                 FROM tbl_job_mst AS A
                 LEFT JOIN tbl_status_color AS B ON A.job_status = B.hex_status
                 LEFT JOIN tbl_machine_type_mst AS C ON A.job_machine_now_in = C.machine_type_code
                 WHERE job_status IN('on process', 'On Process','on production')
                 ORDER BY job_plan_date, job_uniq ASC"
            );
            
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>200, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ConfirmWork"){
            //****************** CHeck job details ******************/
            //*******************************************************/
            $list = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if(ucfirst($listResult['job_status']) != 'Prepare'){
                echo json_encode(array('code'=>400, 'message'=>'สถานะไม่พร้อมสำหรับการรับงาน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            if($listResult['job_fac_type'] == "" || $listResult['job_fac_type'] == NULL){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบ Factory Type ไม่สามารถดำเนินการได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            
            //****************** Pickup reserve pallet ******************/
            //***********************************************************/
            try {
                if($listResult['job_fac_type'] != 'WIP'){
                    $pickup = $db_con->query(
                        "SELECT A.pick_no, A.picking_job_no, A.item_code, A.gdn, A.pallet_id, A.item_descript, A.pick_location, A.picking_qty, A.picking_unit,
                                B.grn, B.cus_code, B.project, B.product_type, B.category_type, B.location_name_en, B.area, B.truck_id, B.stock_qty, B.raw_code
                         FROM tbl_picking_item_mst AS A
                         LEFT JOIN tbl_stock_inven_mst AS B
                         ON A.pallet_id = B.pallet_id AND A.picking_rm_code = B.raw_code
                         WHERE picking_job_no = '$job_no' AND picking_status = 'shipping'"
                    );
        
                    if($pickup->rowCount() == 0){
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ไม่พบข้อมูลใน Inventory shipping order ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                        $db_con = null;
                        return;
                    }

                    while($pickResult = $pickup->fetch(PDO::FETCH_ASSOC)){
                        $inv = $db_con->query(
                            "INSERT INTO tbl_inven_transaction_mst(cus_code, pallet_id, pr_no, inv_no, grn, gdn, pick_no, item_descript, product_type, category_type, qty, unit, area, location_id, ship_to, ship_datetime, truck_id, truck_driver, trans_type, trans_by, trans_date, trans_time, job_ship_ref, rm_type, rm_color)
                             SELECT A.cus_code, A.pallet_id, B.ref_no, B.inv_no, B.grn, '".$pickResult['gdn']."', '".$pickResult['pick_no']."', A.rm_descript, A.product_type, A.category_type, '".$pickResult['picking_qty']."', '".$pickResult['picking_unit']."', 'OUT', A.location_name_en, 'Production', '$buffer_datetime', B.truck_id, B.driver_name, 'Production Confirm', '$session_user_fullname_mst', '$buffer_date', '$buffer_time', '$job_no', A.rm_type, A.rm_color
                             FROM tbl_stock_inven_mst AS A
                             LEFT JOIN tbl_invoice_mst AS B ON A.grn = B.grn
                             WHERE A.pallet_id = '".$pickResult['pallet_id']."'"
                        );
        
                        $quantity += $pickResult['picking_qty'];
                    }
        
                    if($quantity > $listResult['job_rm_usage'] && $listResult['job_fac_type'] == "FG"){
                        echo json_encode(array('code'=>400, 'message'=>'ข้อมูล Raw materials ที่เบิกใช้มีปริมาณไม่ถูกต้องตาม Job usage ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                        $db_con = null;
                        return;
                    }
        
                    $pick_received = $db_con->query("UPDATE tbl_picking_item_mst SET picking_status = 'Received' WHERE picking_Job_no = '$job_no' AND picking_status = 'shipping'");
                }
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ [Picking-001] ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            //!----------------- CHeck machine working step to validate work flow --------------//
            //!----------------------- เช็คเครื่องจักรว่ามีกี่เครื่อง เข้าเงื่อนไขไหนบ้าง -------------------------//
            //* Stage 1.Passed Station, 2.Combine Station, 3.Tigthing Station
            //* 3 Ways Conditions
            //* 1. Normal Flow = เงื่อนไขปกติ เข้าครบทั้ง 3 Stage ไม่ต้องทำอะไรให้ Flow ไหลปกติ
            //* 2. Combine Flow = เข้าแค่ 2 Machine คือเข้า Combine แล้วไป Tigthing เลย *Note: ตัวนี้เข้า Semi Inventory ได้เลย
            //* 3. Tigthing Flow = รับงานมาเพื่อมัดอย่างเดียว *Note: ตัวนี้ต้อง Generate Semi Inventory และสร้าง Coversheet เลย
            try {
                $mst = $db_con->query("SELECT COUNT(ope_mc_code) AS mc_count FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_orders > 0");
                $mstResult = $mst->fetch(PDO::FETCH_ASSOC);
                if($mstResult['mc_count'] < 3){
                    if($listResult['job_fac_type'] == "WIP"){
                        $quantity = $listResult['wip_usage'];
                    }else{
                        $quantity = $listResult['job_rm_usage'];
                    }
                    //todo >>>>>>>>>> CHeck semi inventory details >>>>>>>>>>
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    $checksem = $db_con->query("SELECT COUNT(sem_job_no) AS count_sem FROM tbl_semi_inven_mst WHERE sem_job_no = '$job_no'");
                    $checksemResult = $checksem->fetch(PDO::FETCH_ASSOC);
                    if($checksemResult['count_sem'] > 0){
                        echo json_encode(array('code'=>400, 'message'=>'พบ Job นี้บนฐานข้อมูล Combine set อยู่แล้ว ไม่สามารถดำเนินการได้'));
                        $db_con = null;
                        return;
                    }
                    
                    $exeMain = $db_con->query("UPDATE tbl_job_mst SET job_status = 'on production', job_plan_fg_qty += $quantity, job_plan_fg_set_per_job += $quantity, job_pd_conf_datetime = '$buffer_datetime', job_pd_conf_by = '$mrp_user_name_mst' WHERE job_no = '$job_no'");

                    if($mstResult['mc_count'] == 1){
                        //todo >>>>>>>>>>>>>>>>>>>>>>>> Generate Coversheet >>>>>>>>>>>>>>>>>>>>>>>>>
                        //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                        $prefix = 'SP' . $buffer_year_2digit . $buffer_month;
                        $covp = $db_con->query("SELECT COUNT(list_conf_no) AS sp_count FROM tbl_confirm_print_list WHERE list_conf_no LIKE '%$prefix%'");
                        $covpResult = $covp->fetch(PDO::FETCH_ASSOC);
                        $cov_no = SetPrefix($prefix, $covpResult['sp_count']);
                        $cov_token = base64_encode($cov_no);
                        
                        $plist = $db_con->query(
                            "INSERT INTO tbl_confirm_print_list(list_conf_no, list_conf_type, list_token, list_bom_id, list_fg_codeset, list_fg_code, list_part_customer, list_comp_code, list_fg_description, list_cus_code, list_project, list_ship_to_type, list_receive_qty, list_current_qty, list_used_qty, list_packing_usage, list_status, list_conf_datetime, list_conf_by, list_pending_qty)
                            SELECT '$cov_no', 'MRP', '$cov_token', job_bom_id, job_fg_codeset, job_fg_code, job_part_customer, job_comp_code, job_fg_description, job_cus_code, job_project, job_ship_to_type, $quantity, 0, 0, job_packing_usage, 'Prepare', '$buffer_datetime', '$mrp_user_name_mst', $quantity FROM tbl_job_mst WHERE job_no = '$job_no';
                            INSERT INTO tbl_confirm_print_tags(conf_code, conf_job_no, conf_packing_usage, conf_status, conf_qty, remarks, conf_datetime, conf_by)
                            SELECT '$cov_no', '$job_no', job_packing_usage, 'combine', '$quantity', '', '$buffer_datetime', '$mrp_user_name_mst' FROM tbl_job_mst WHERE job_no = '$job_no'"
                        );

                        $seminv = $db_con->query(
                            "INSERT INTO tbl_semi_inven_mst(sem_job_no, sem_receive_qty, sem_stock_qty, sem_used_qty, sem_status, sem_gen_datetime, sem_gen_by, sem_update_by, sem_update_datetime)
                            VALUES('$job_no', CAST($quantity AS INT), 0, CAST($quantity AS INT), 'Already Used', '$buffer_datetime', '$mrp_user_name_mst', '$mrp_user_name_mst', '$buffer_datetime');
                            INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by)
                            VALUES('$job_no', CAST($quantity AS INT), 'IN', 'By Passed', '$buffer_datetime', '$mrp_user_name_mst'),('$job_no', CAST($quantity AS INT), 'OUT', 'By Passed', '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                    }else{
                        $seminv = $db_con->query(
                            "INSERT INTO tbl_semi_inven_mst(sem_job_no, sem_receive_qty, sem_stock_qty, sem_used_qty, sem_status, sem_gen_datetime, sem_gen_by, sem_update_by, sem_update_datetime)
                            VALUES('$job_no', CAST($quantity AS INT), CAST($quantity AS INT), 0, 'Active', '$buffer_datetime', '$mrp_user_name_mst', '$mrp_user_name_mst', '$buffer_datetime');
                            INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by)
                            VALUES('$job_no', CAST($quantity AS INT), 'IN', 'Receive WIP', '$buffer_datetime', '$mrp_user_name_mst')"
                        );
                    }
                }else{
                    $exeMain = $db_con->query("UPDATE tbl_job_mst SET job_status = 'on production', job_pd_conf_datetime = '$buffer_datetime', job_pd_conf_by = '$mrp_user_name_mst' WHERE job_no = '$job_no'");
                }
                
                // //todo >>>>>>>>>>>>>>>>>>>>>>>> Stamp receive in Operation orders >>>>>>>>>>>>>>>>>>>>>>>>>>
                // //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                $smp = $db_con->query("UPDATE tbl_job_operation SET ope_status = 'done', ope_finish_datetime = '$buffer_datetime', ope_finish_by = '$mrp_user_name_mst' WHERE ope_job_no = '$job_no' AND ope_mc_code = 'SP'");

                //!----------------- CHeck combine machine to stamp on job details --------------//
                //!----------------- เช็คเครื่อง Combine ในขั้นตอนสุดท้าย เผื่อหลุด -------------------------//
                try {
                    $nulled = $db_con->query("SELECT job_merge_mc FROM tbl_job_mst WHERE job_no = '$job_no'");
                    $nulledResult = $nulled->fetch(PDO::FETCH_ASSOC);
                    if($nulledResult['job_merge_mc'] == NULL || $nulledResult['job_merge_mc'] == ""){
                        $comb = $db_con->query("SELECT TOP(1) ope_mc_code, ope_in, ope_out FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_round = 1 AND ope_mc_code != 'TG' ORDER BY ope_orders");
                        $combResult = $comb->fetch(PDO::FETCH_ASSOC);

                        $ope_mc_code = $combResult['ope_mc_code'];
                        $ope_in = intval($combResult['ope_in']);
                        $ope_out = intval($combResult['ope_out']);

                        $upj = $db_con->query("UPDATE tbl_job_mst SET job_merge_mc = '$ope_mc_code', job_merge_in = '$ope_in', job_merge_out = '$ope_out' WHERE job_no = '$job_no'");
                    }
                } catch (Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ [Picking-001] ' . $e->getMessage()));
                    $db_con = null;
                    return;
                }


                $dub = $db_con->prepare("SELECT ope_mc_code FROM tbl_job_operation WHERE ope_job_no = :job_no GROUP BY ope_mc_code HAVING COUNT(ope_mc_code) > 1");
                $dub->bindParam(':job_no', $job_no);
                $dub->execute();
                $dubResult = $dub->fetchAll(PDO::FETCH_ASSOC);
                if(count($dubResult) > 0){
                    echo json_encode(array('code'=>400, 'message'=>'พบเครื่องจักรประเภทเดียวกันมากกว่า 1 รายการ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    $db_con = null;
                    return;
                }

                $lstmc = $db_con->prepare("SELECT TOP(1) ope_mc_code FROM tbl_job_operation WHERE ope_job_no = :job_no AND ope_orders > 0 ORDER BY ope_orders DESC");
                $lstmc->bindParam(':job_no', $job_no);
                $lstmc->execute();
                $lstmcResult = $lstmc->fetch(PDO::FETCH_ASSOC);

                if($lstmcResult['ope_mc_code'] != 'TG'){
                    echo json_encode(array('code'=>400, 'message'=>'เครื่องจักรเครื่องสุดท้ายไม่ใช่เครื่องมัด ไม่สามารถดำเนินการได้'));
                    return;
                }

                
                echo json_encode(array('code'=>200, 'message'=>'ยืนยันรับแผนการผลิตสำเร็จ'));
                $db_con->commit();
                $db_con = null;
                return;
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
                $db_con->rollBack();
                $db_con = null;
                return;
            }
    }else if($protocol == "ConfirmUpdateMachine"){
        try {
            if(count($machine_order) < 1){
                echo json_encode(array('code'=>400, 'message'=>'ตั้งค่าเครื่องจักรไม่ถูกต้อง พบเครื่องจักรน้อยกว่า 1 เครื่อง ไม่สามารถดำเนินการได้'));
                return;
            }

            if(explode("|", end($machine_order))[0] != 'TG'){
                // echo json_encode(array('code'=>400, 'message'=>end($machine_order)));
                echo json_encode(array('code'=>400, 'message'=>'เครื่องจักรเครื่องสุดท้ายไม่ใช่เครื่องมัด ไม่สามารถดำเนินการได้'));
                return;
            }

            $vc = array_count_values($machine_order);
            $vc_max = max($vc);
            if($vc_max > 1){
                echo json_encode(array('code'=>400, 'message'=>'มีเครื่องจักรประเภทเดียวมากกว่า 1 รายการ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                return;
            }

            //******************** CHeck job details ***************************/
            //******************************************************************/
            $list = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if(ucfirst($listResult['job_status']) != "Prepare"){
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูลได้เนื่องจากสถานะของ Job number ไม่ใช่ Prepare ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $del = $db_con->query("DELETE FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_orders > 0");
    
            foreach($machine_order as $id=>$item){
                $orders = $id+1;
                $sendby = 0;
                $round = NULL;
                $machine = explode("|", $item);

                if($id >= count($machine_order) - 2){
                    $round = 1;
                }

                if($id == 0){
                    if($listResult['job_fac_type'] == "WIP"){   
                        $sendby = $listResult['wip_usage'];
                    }else{
                        $sendby = intval(($listResult['job_rm_usage'] / $ope_in[$id]) * $ope_out[$id]);
                    }
                }
                
                $newmc = $db_con->query(
                    "INSERT INTO tbl_job_operation(ope_job_no, ope_orders, ope_mc_code, ope_in, ope_out, ope_status, ope_fg_ttl, ope_ng_ttl, ope_fg_sendby, ope_create_datetime, ope_create_by, ope_round)
                     VALUES('$job_no', '$orders', '$machine[0]', '$ope_in[$id]', '$ope_out[$id]', 'pending', 0, 0, $sendby, '$buffer_datetime', '$mrp_user_name_mst', '$round')"
                );

                if($orders == count($machine_order) && $machine[0] != 'TG'){
                    echo json_encode(array('code'=>400, 'message'=>'เครื่องจักรเครื่องสุดท้ายไม่ใช่เครื่องมัด ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    $db_con = null;
                    return;
                }
            }

            // $secT = $db_con->query("SELECT TOP(1) ope_mc_code, CAST(ope_in AS INT) AS ope_in, CAST(ope_out AS INT) AS ope_out FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_round = 1 AND ope_mc_code != 'TG' ORDER BY ope_orders");
            $secT = $db_con->query("SELECT TOP(1) ope_mc_code, CAST(ope_in AS INT) AS ope_in, CAST(ope_out AS INT) AS ope_out FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_round = 1 ORDER BY ope_orders");
            if($secT->rowCount() == 0){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูลเครื่องจักรสำหรับ Combine Set หรือ เครื่องมัด ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $secTResult = $secT->fetch(PDO::FETCH_ASSOC);
            $upMain = $db_con->query("UPDATE tbl_job_mst SET job_merge_mc = '".$secTResult['ope_mc_code']."', job_merge_in = '".$secTResult['ope_in']."', job_merge_out = '".$secTResult['ope_out']."' WHERE job_no = '$job_no'");

            echo json_encode(array('code'=>200, 'message'=>"อัพเดทข้อมูลเครื่องจักรสำหรับ Job $job_no สำเร็จ"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถยืนยันรับงานได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "RejectPlan"){
        try {
            //****************** CHeck job details ******************/
            //*******************************************************/
            $list = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if(ucfirst($listResult['job_status']) != 'Prepare'){
                echo json_encode(array('code'=>400, 'message'=>'สถานะของ Job ไม่ใช่ Prepare ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            //****************** Insert return transactions ******************/
            //****************************************************************/
            $tns = $db_con->query(
                "SELECT A.pallet_id, A.picking_qty, A.gdn, A.pick_no, B.rm_descript, B.product_type, B.rm_type, B.rm_color, B.location_name_en, B.uom, B.grn, C.inv_no, C.ref_no
                FROM tbl_picking_item_mst AS A
                LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
                LEFT JOIN tbl_invoice_mst AS C ON B.grn = C.grn
                WHERE A.picking_job_no = '$job_no' AND A.picking_status = 'shipping'"
            );
            while($tnsResult = $tns->fetch(PDO::FETCH_ASSOC)){
                $in_tns = $db_con->query(
                    "INSERT INTO tbl_inven_transaction_mst(pallet_id, pr_no, inv_no, grn, gdn, item_descript, product_type, qty, unit, area, pick_no, location_id, trans_type, trans_remarks, trans_by, trans_date, trans_time, job_ship_ref, rm_type, rm_color)
                    VALUES('".$tnsResult['pallet_id']."', '".$tnsResult['ref_no']."', '".$tnsResult['inv_no']."', '".$tnsResult['grn']."', '".$tnsResult['gdn']."', '".$tnsResult['rm_descript']."', '".$tnsResult['product_type']."', '".$tnsResult['picking_qty']."', '".$tnsResult['uom']."', 'Movement', '".$tnsResult['pick_no']."', '".$tnsResult['location_name_en']."', 'Production Rejected', '$remarks', '$mrp_user_name_mst', '$buffer_date', '$buffer_time', '$job_no', '".$tnsResult['rm_type']."', '".$tnsResult['rm_color']."')"
                );
            }


            $main = $db_con->query("UPDATE tbl_job_mst SET job_now_in = 'Planning', job_status = 'Rejected' WHERE job_no = '$job_no'");
            $pick = $db_con->query("UPDATE tbl_picking_item_mst SET picking_status = 'Return Materials', pick_return_datetime = '$buffer_datetime', pick_return_by = '$mrp_user_name_mst', pick_return_remarks = '$remarks' WHERE picking_job_no = '$job_no' AND picking_status = 'shipping'");

            echo json_encode(array('code'=>200, 'message'=>"ดำเนินการ Reject job number $job_no สำเร็จ"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ResetJob"){
        //****************** CHeck the job status and details ******************/
        //**********************************************************************/
        $list = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
        $listResult = $list->fetch(PDO::FETCH_ASSOC);
        if($listResult['job_status'] != 'on production'){
            echo json_encode(array('code'=>400, 'message'=>'สถานะของ Job ไม่พร้อมสำหรับการ Reset ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
            $db_con = null;
            return;
        }

        try {
            $sem = $db_con->query("SELECT * FROM tbl_semi_inven_mst WHERE sem_job_no = '$job_no'");
            $semResult = $sem->fetch(PDO::FETCH_ASSOC);
            if($semResult['sem_used_qty'] != 0 || $semResult['sem_receive_qty'] != $semResult['sem_stock_qty']){
                echo json_encode(array('code'=>400, 'message'=>'มีการ combine งานไปแล้ว ไม่สามารถ Reset Job ได้ '));
                $db_con = null;
                return;
            }

            $del = $db_con->query(
                "DELETE FROM tbl_inven_transaction_mst WHERE area = 'OUT' AND job_ship_ref = '$job_no';
                 DELETE FROM tbl_semi_inven_mst WHERE sem_job_no = '$job_no';
                 DELETE FROM tbl_semi_inven_transactions_mst WHERE t_sem_job_no = '$job_no';
                 DELETE FROM tbl_job_confirm_passing_transaction WHERE pass_job_no = '$job_no'"
            );
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }

        
        try {
            $up = $db_con->query("UPDATE tbl_job_mst SET job_status = 'prepare', job_plan_fg_set_per_job = 0, job_plan_fg_qty = 0, job_plan_fg_set = 0 WHERE job_no = '$job_no'");
            $rec = $db_con->query("UPDATE tbl_picking_item_mst SET picking_status = 'shipping' WHERE picking_job_no = '$job_no' AND picking_status = 'Received'");
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
        
        try {
            $set = $db_con->query(
                "UPDATE tbl_job_operation
                 SET ope_status = 'pending',
                     ope_fg_ttl = 0,
                     ope_ng_ttl = 0,
                     ope_finish_datetime = NULL,
                     ope_finish_by = NULL,
                     ope_fg_sendby = 0,
                     start_datetime = NULL,
                     ope_setting_start_datetime = NULL,
                     ope_setting_end_datetime = NULL
                 WHERE ope_job_no = '$job_no' AND ope_orders >= 0"
            );


            $secT = $db_con->query("SELECT TOP(1) * FROM tbl_job_operation WHERE ope_job_no = '$job_no' AND ope_orders = 1");
            $secTResult = $secT->fetch(PDO::FETCH_ASSOC);

            $sendby = 0;
            if($listResult['job_fac_type'] == "WIP"){
                $sendby = $listResult['wip_usage'];
            }else{
                $sendby = intval(($listResult['job_rm_usage'] / $secTResult['ope_in']) * $secTResult['ope_out']); 
            }

            $newSend = $db_con->query("UPDATE tbl_job_operation SET ope_fg_sendby = $sendby WHERE ope_job_no = '$job_no' AND ope_orders = 1");
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถเคลียร์ข้อมูลเครื่องจักรสำหรับ job number นี้ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
        
        echo json_encode(array('code'=>200, 'message'=>'ดำเนินการ Reset job สำเร็จ กรุณายืนยันรับงานสำหรับ job นี้ที่เมนู Work order อีกครั้ง'));
        $db_con->commit();
        $db_con = null;
        return;
    }else if($protocol == "UpdatePriority"){
        try {
            $list = $db_con->prepare("SELECT job_priot FROM tbl_job_mst WHERE job_no = :job_no");
            $list->bindParam(':job_no', $job_no);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($job_priot > $listResult['job_priot']){
                $rlls = $db_con->prepare("UPDATE tbl_job_mst SET job_priot -= 1 WHERE job_plan_date = :job_plan_date AND job_priot >= :job_priot; UPDATE tbl_job_mst SET job_priot = :job_priot1 WHERE job_no = :job_no");
                $rlls->bindParam(':job_plan_date', $listResult['job_plan_date']);
                $rlls->bindParam(':job_priot', $job_priot);
                $rlls->bindParam(':job_priot1', $job_priot);
                $rlls->bindParam(':job_no', $job_no);
                $rlls->execute();
            }else if($job_priot < $listResult['job_priot']){
                $rlls = $db_con->prepare("UPDATE tbl_job_mst SET job_priot += 1 WHERE job_plan_date = :job_plan_date AND job_priot <= :job_priot; UPDATE tbl_job_mst SET job_priot = :job_priot1 WHERE job_no = :job_no");
                $rlls->bindParam(':job_plan_date', $listResult['job_plan_date']);
                $rlls->bindParam(':job_priot', $job_priot);
                $rlls->bindParam(':job_priot1', $job_priot);
                $rlls->bindParam(':job_no', $job_no);
                $rlls->execute();
            }else{
                $rlls = $db_con->prepare("UPDATE tbl_job_mst SET job_priot = :job_priot WHERE job_no = :job_no");
                $rlls->bindParam(':job_priot', $job_priot);
                $rlls->bindParam(':job_no', $job_no);
                $rlls->execute();
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการ Adjust Priority สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถเคลียร์ข้อมูลเครื่องจักรสำหรับ job number นี้ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>