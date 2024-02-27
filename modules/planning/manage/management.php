<?php
    require_once("../../../session.php");
    require_once("../../../../library/PHPSpreadSheet/vendor/autoload.php");
    require_once('../../../../library/PHPMailer/class.phpmailer.php');
    require_once("../../../../library/PHPMailer/sender.php");
    require_once("../../../email/moq_authorization_form.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    $job_uniq = isset($_POST['job_uniq']) ? $_POST['job_uniq'] : '';
    $rm_code = isset($_POST['rm_code']) ? $_POST['rm_code'] : '';
    $rm_type = isset($_POST['rm_type']) ? $_POST['rm_type'] : '';
    $rm_spec = isset($_POST['rm_spec']) ? $_POST['rm_spec'] : '';
    $rm_flute = isset($_POST['rm_flute']) ? $_POST['rm_flute'] : '';

    $delivery_date = isset($_POST['delivery_date']) ? $_POST['delivery_date'] : '';
    $job_plan_date = isset($_POST['job_plan_date']) ? $_POST['job_plan_date'] : '';

    $fg_perpage = isset($_POST['fg_perpage']) ? intval($_POST['fg_perpage']) : '';
    $pd_usage = isset($_POST['pd_usage']) ? intval($_POST['pd_usage']) : '';
    $rm_usage_qty = isset($_POST['rm_usage_qty']) ? intval($_POST['rm_usage_qty']) : '';

    $ffmc_quantity = isset($_POST['ffmc_quantity']) ? intval(str_replace(",", "", $_POST['ffmc_quantity'])) : '';
    $conf_quantity = isset($_POST['conf_quantity']) ? intval(str_replace(",", "", $_POST['conf_quantity'])) : '';
    $set_per_job_quantity = isset($_POST['set_per_job_quantity']) ? intval(str_replace(",", "", $_POST['set_per_job_quantity'])) : '';

    $pallet_id = isset($_POST['pallet_id']) ? $_POST['pallet_id'] : '';
    $pallet_qty = isset($_POST['pallet_qty']) ? intval($_POST['pallet_qty']) : '';

    $ct_in = isset($_POST['ct_in']) ? intval($_POST['ct_in']) : '';
    $ct_out = isset($_POST['ct_out']) ? intval($_POST['ct_out']) : '';
    $ct_status = isset($_POST['ct_status']) ? $_POST['ct_status'] : '';

    $usage_pallet_id = isset($_POST['usage_pallet_id']) ? $_POST['usage_pallet_id'] : '';
    $usage_pallet_qty = isset($_POST['usage_pallet_qty']) ? $_POST['usage_pallet_qty'] : '';

    
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    
    $machine_arr = array();
    $json = array();
    
    $order = 0;
    $varib = '';
    $apval = 'Y';
    $machine_mp = '';
    $machine_now_in = '';

    if($protocol == "PlanningOrder"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY A.job_uniq DESC) AS list,
                        A.job_uniq, A.job_ref, A.job_delivery_date, A.job_status, A.job_conf_datetime, A.job_conf_by, A.job_ffmc_conf_qty, A.job_conf_qty, A.job_cus_qty, A.job_rm_code, A.job_rm_spec, A.job_fac_type,
                        B.class_color, B.class_txt_color,
                        C.fg_code, C.fg_codeset, C.cus_code, C.project, C.fg_description, C.part_customer, C.comp_code
                 FROM tbl_job_detail AS A 
                 LEFT JOIN tbl_status_color AS B ON A.job_status = B.hex_status
                 LEFT JOIN tbl_bom_mst AS C ON A.job_bom_id = C.bom_uniq
                 WHERE job_now_in = 'Planning' AND job_status != 'Rejected'
                 ORDER BY A.job_uniq DESC"
            );
    
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->GetMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "CancelOrder"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_job_detail WHERE job_uniq = :job_uniq");
            $list->bindParam(':job_uniq', $job_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['job_status'] != 'Pending'){
                echo json_encode(array('code'=>400, 'message'=>'สถานะของ Order ไม่พร้อมสำหรับการยกเลิก ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            
            $up = $db_con->prepare("UPDATE tbl_job_detail SET job_now_in = 'Cancel', job_remarks = :job_remarks, job_eff_by = :job_eff_by, job_eff_on = :job_eff_on WHERE job_uniq = :job_uniq");
            $up->bindParam(':job_remarks', $remarks);
            $up->bindParam(':job_eff_by', $mrp_user_name_mst);
            $up->bindParam(':job_eff_on', $buffer_datetime);
            $up->bindParam(':job_uniq', $job_uniq);
            $up->execute();

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการยกเลิกรายการสำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->GetMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UploadNewPlan"){
        try {
            $upfile = isset($_POST['upfile']) ? $_POST['upfile'] : '';

            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow();

            for($i=2;$i<=$highestRow;$i++){
                $job_ref = trim($data->getCell("A$i")->getValue());
                $bom_uniq = trim($data->getCell("C$i")->getValue());
                $unit_type = trim($data->getCell("F$i")->getValue());
                $order_type = trim($data->getCell("G$i")->getValue());
                $quantity = str_replace(",", "", trim($data->getCell("E$i")->getValue()));
                $delivery_date   = $data->getCell("B$i")->getValue() ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data->getCell("B$i")->getValue())->format("Y-m-d") : '';

                if($job_ref == ""){
                    echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Order Ref# ในบรรทัดที่ $i ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                $cbom = $db_con->prepare("SELECT * FROM tbl_bom_mst WHERE bom_uniq = :bom_uniq");
                $cbom->bindParam(':bom_uniq', $bom_uniq);
                $cbom->execute();
                $cbomResult = $cbom->fetch(PDO::FETCH_ASSOC);

                if($cbomResult['bom_uniq'] == ""){
                    echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล BOM สำหรับ Uniq $bom_uniq ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                if($cbomResult['bom_status'] != 'Active'){
                    echo json_encode(array('code'=>400, 'message'=>"ข้อมูล BOM สำหรับ $bom_uniq สถานะไม่ถูก Active ไม่สามารถดำเนินการได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                if($cbomResult['fg_type'] == 'SET'){
                    $setList = $db_con->prepare("SELECT * FROM tbl_bom_mst WHERE fg_codeset = :fg_codeset AND project = :project AND bom_status = 'Active' ORDER BY fg_uniq");
                    $setList->bindParam(':fg_codeset', $cbomResult['fg_codeset']);
                    $setList->bindParam(':project', $cbomResult['project']);
                    $setList->execute();
                    while($setListResult = $setList->fetch(PDO::FETCH_ASSOC)){
                        $upList = $db_con->prepare(
                            "INSERT INTO tbl_job_detail(job_ref, job_order_type, job_delivery_date, job_cus_code, job_project, job_bom_id, job_rm_code, job_rm_spec, job_cus_qty, job_ffmc_conf_qty, job_conf_qty, job_unit_type, job_now_in, job_status, job_conf_datetime, job_conf_by, post_from, job_fac_type, job_pd_usage)
                             VALUES(:job_ref, :job_order_type, :job_delivery_date, :job_cus_code, :job_project, :job_bom_id, :job_rm_code, :job_rm_spec, :job_cus_qty, :job_ffmc_conf_qty, :job_conf_qty, 'Pcs', 'Planning', 'Pending', :job_conf_datetime, :job_conf_by, 'Planning Order', :job_fac_type, :job_pd_usage)"
                        );
                        $upList->bindParam(':job_ref', $job_ref);
                        $upList->bindParam(':job_order_type', $order_type);
                        $upList->bindParam(':job_delivery_date', $delivery_date);
                        $upList->bindParam(':job_cus_code', $setListResult['cus_code']);
                        $upList->bindParam(':job_project', $setListResult['project']);
                        $upList->bindParam(':job_bom_id', $setListResult['bom_uniq']);
                        $upList->bindParam(':job_rm_code', $setListResult['rm_code']);
                        $upList->bindParam(':job_rm_spec', $setListResult['rm_spec']);
                        $upList->bindParam(':job_cus_qty', $quantity);
                        $upList->bindParam(':job_ffmc_conf_qty', $quantity);
                        $upList->bindParam(':job_conf_qty', number_format($quantity * $setListResult['pd_usage'], 0));
                        $upList->bindParam(':job_conf_datetime', $buffer_datetime);
                        $upList->bindParam(':job_conf_by', $mrp_user_name_mst);
                        $upList->bindParam(':job_fac_type', $setListResult['fac_type']);
                        $upList->bindParam(':job_pd_usage', $setListResult['pd_usage']);
                        $upList->execute();
                    }
                }else{
                    $upList = $db_con->prepare(
                        "INSERT INTO tbl_job_detail(job_ref, job_order_type, job_delivery_date, job_cus_code, job_project, job_bom_id, job_rm_code, job_rm_spec, job_cus_qty, job_ffmc_conf_qty, job_conf_qty, job_unit_type, job_now_in, job_status, job_conf_datetime, job_conf_by, post_from, job_fac_type, job_pd_usage)
                         VALUES(:job_ref, :job_order_type, :job_delivery_date, :job_cus_code, :job_project, :job_bom_id, :job_rm_code, :job_rm_spec, :job_cus_qty, :job_ffmc_conf_qty, :job_conf_qty, 'Pcs', 'Planning', 'Pending', :job_conf_datetime, :job_conf_by, 'Planning Order', :job_fac_type, :job_pd_usage)"
                    );
                    $upList->bindParam(':job_ref', $job_ref);
                    $upList->bindParam(':job_order_type', $order_type);
                    $upList->bindParam(':job_delivery_date', $delivery_date);
                    $upList->bindParam(':job_cus_code', $cbomResult['cus_code']);
                    $upList->bindParam(':job_project', $cbomResult['project']);
                    $upList->bindParam(':job_bom_id', $cbomResult['bom_uniq']);
                    $upList->bindParam(':job_rm_code', $cbomResult['rm_code']);
                    $upList->bindParam(':job_rm_spec', $cbomResult['rm_spec']);
                    $upList->bindParam(':job_cus_qty', $quantity);
                    $upList->bindParam(':job_ffmc_conf_qty', $quantity);
                    $upList->bindParam(':job_conf_qty', number_format($quantity * $cbomResult['pd_usage'], 0));
                    $upList->bindParam(':job_conf_datetime', $buffer_datetime);
                    $upList->bindParam(':job_conf_by', $mrp_user_name_mst);
                    $upList->bindParam(':job_fac_type', $cbomResult['fac_type']);
                    $upList->bindParam(':job_pd_usage', $cbomResult['pd_usage']);
                    $upList->execute();
                }
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการอัพโหลดแผนการผลิตสำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->GetMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "CreateNormalPlan"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_job_detail AS A LEFT JOIN tbl_bom_mst AS B ON A.job_bom_id = B.bom_uniq WHERE job_uniq = :job_uniq");
            $list->bindParam(':job_uniq', $job_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['job_no'] != NULL){
                echo json_encode(array('code'=>400, 'message'=>'มีการออก Job ไปแล้ว ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            //***************************** Generate Job Number ******************************/
            $prefix = date('ym', strtotime($buffer_date));
            $jlist = $db_con->query("SELECT COUNT(job_no) AS count_job FROM tbl_job_mst WHERE job_no LIKE '$prefix%'");
            $jlistResult = $jlist->fetch(PDO::FETCH_ASSOC);
            $job_no = SetPrefix5Digit($prefix, $jlistResult['count_job']);
            
            //* 01 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>>>>>> CHeck quantity raw material >>>>>>>>>>>>>>>>>>>>>>>
            $concast = $rm_type == "" ? "" : " AND rm_type = '$rm_type'";
            $stList = $db_con->prepare("SELECT CAST(SUM(stock_qty) AS INT) AS stock_qty FROM tbl_stock_inven_mst WHERE raw_code = :rm_code $concast");
            $stList->bindParam(':rm_code', $rm_code);
            $stList->execute();
            $stListResult = $stList->fetch(PDO::FETCH_ASSOC);
            
            if($stListResult['stock_qty'] < $rm_usage_qty){
                echo json_encode(array('code'=>400, 'message'=>'Stock Raw Material ไม่พอสำหรับการใช้งาน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            //* 01 - End Condition >>>>>>>>>>>>>>>>>>>>>>>>>>>> CHeck quantity raw material >>>>>>>>>>>>>>>>>>>>>>>
            //* 02 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>>>> CHeck Machine management and details >>>>>>>>>>>>>>
            if($ct_status = 'on'){
                $order++;
                array_push($machine_arr, array('order'=>$order, 'machine_code'=>'CM', 'in'=>$ct_in, 'out'=>$ct_out));
                $machine_mp = 'Cutting M/C >>> ';
            }
            
            foreach(json_decode($listResult['machine_order'], TRUE) as $id => $item){
                $order++;
                $mplist = $db_con->prepare("SELECT machine_type_name FROM tbl_machine_type_mst WHERE machine_type_code = :machine_type_code");
                $mplist->bindParam(':machine_type_code', $item['machine_code']);
                $mplist->execute();
                $mplistResult = $mplist->fetch(PDO::FETCH_ASSOC);
                $varib = $item['machine_code'];
                $machine_mp .= $mplistResult['machine_type_name'] . ' >>> ';
                array_push($machine_arr, array('order'=>$order, 'machine_code'=>$item['machine_code'], 'in'=>$item['in'], 'out'=>$item['out']));
            }
            
            if($varib != 'TG'){
                echo json_encode(array('code'=>400, 'message'=>'เครื่องจักรเครื่องสุดท้ายไม่ใช่เครื่องมัด ไม่สามารถดำเนินการได้ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }
            //* 02 - End Condition >>>>>>>>>>>>>>>>>>>>>>>>>> CHeck Machine management and details >>>>>>>>>>>>>>>>
            //* 03 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>> Pallet Reserved >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            foreach($usage_pallet_id as $id=>$item){
                $invList = $db_con->prepare(
                    "SELECT *
                     FROM tbl_stock_inven_mst AS A
                     LEFT JOIN tbl_invoice_mst AS B ON A.grn = B.grn
                     WHERE pallet_id = :pallet_id"
                );
                $invList->bindParam(':pallet_id', $item);
                $invList->execute();
                $invResult = $invList->fetch(PDO::FETCH_ASSOC);

                if($invResult['stock_qty'] < $usage_pallet_qty[$id]){
                    echo json_encode(array('code'=>400, 'message'=>"Stock ของ $item ไม่พบสำหรับการใช้งาน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                $inp = $db_con->prepare(
                    "INSERT INTO tbl_picking_item_mst(picking_job_no, inven_uniq, pallet_id ,picking_rm_code, item_descript, pick_location, picking_qty, picking_unit, picking_reserve_by, picking_reserve_on, picking_status)
                     SELECT :job_no, inven_uniq, pallet_id, raw_code, rm_descript, location_name_en, :picking_qty, uom, :reserve_by, :reserve_on, 'reserve' FROM tbl_stock_inven_mst WHERE pallet_id = :pallet_id"
                );
                $inp->bindParam(':job_no', $job_no);
                $inp->bindParam(':pallet_id', $item);
                $inp->bindParam(':picking_qty', $usage_pallet_qty[$id]);
                $inp->bindParam(':reserve_by', $mrp_user_name_mst);
                $inp->bindParam(':reserve_on', $buffer_datetime);
                $inp->execute();


                $tns = $db_con->prepare(
                    "INSERT INTO tbl_inven_transaction_mst(cus_code, pallet_id, pr_no, inv_no, grn, item_descript, product_type, category_type, qty, unit, area, location_id, ship_to, trans_type, trans_by, trans_date, trans_time, job_ship_ref, rm_type, rm_color)
                     SELECT cus_code, pallet_id, ref_no, inv_no, A.grn, rm_descript, product_type, category_type, :picking_qty, uom, 'Movement', location_name_en, 'Production', 'Reserved', :trans_by, :trans_date, :trans_time, :job_no, rm_type, rm_color FROM tbl_stock_inven_mst AS A LEFT JOIN tbl_invoice_mst AS B ON A.grn = B.grn WHERE pallet_id = :pallet_id"
                );
                $tns->bindParam(':picking_qty', $usage_pallet_qty[$id]);
                $tns->bindParam(':trans_by', $mrp_user_name_mst);
                $tns->bindParam(':trans_date', $buffer_date);
                $tns->bindParam(':trans_time', $buffer_time);
                $tns->bindParam(':job_no', $job_no);
                $tns->bindParam(':pallet_id', $item);
                $tns->execute();

                $res = $db_con->prepare("UPDATE tbl_stock_inven_mst SET stock_qty -= :quantity1, used_qty += :quantity2 WHERE pallet_id = :pallet_id");
                $res->bindParam(':quantity1', $usage_pallet_qty[$id]);
                $res->bindParam(':quantity2', $usage_pallet_qty[$id]);
                $res->bindParam(':pallet_id', $item);
                $res->execute();
            }
            //* 03 - End Condition >>>>>>>>>>>>>>>>>>>>>>>>>> Pallet Reserved >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //* 04 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>> Machine Management >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $set_per_job_quantity = $rm_usage_qty;
            $osql = "INSERT INTO tbl_job_operation(ope_job_no, ope_orders, ope_mc_code, ope_in, ope_out, ope_fg_ttl, ope_ng_ttl, ope_status, ope_create_datetime, ope_create_by, ope_round, ope_fg_sendby)
                     VALUES('$job_no','-1','RM','0','0','0','0','pending','$buffer_datetime','$mrp_user_name_mst', NULL, 0),('$job_no','0','SP','0','0','0','0','pending','$buffer_datetime','$mrp_user_name_mst', NULL, 0)";

            foreach($machine_arr as $id => $item){
                $order = $item++;
                $sendby = 0;
                $round = 'NULL';

                if($id >= count($mc_arr) - 2){
                    $round = 1;
                }

                if($id == 0){
                    $machine_now_in = $val['machine_code'];
                    $sendby = intval(($rm_usage_qty / $item['in']) * $item['out']);
                }

                if($round != 1){
                    $set_per_job_quantity = intval(($set_per_job_quantity / $item['in']) * $item['out']);
                }

                $osql .= ",('$job_no','".$item['order']."','".$item['machine_code']."','".$item['in']."','".$item['out']."','0','0','pending','$buffer_datetime','$mrp_user_name_mst', $round, $sendby)";
            }
            $osQuery = $db_con->query($osql);
            //* 04 - End Condition >>>>>>>>>>>>>>>>>>>>>>>>>> Machine Management >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //* 05 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>> MOQ Condition >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            if($conf_quantity < $listResult['moq']){
                $apval = 'N';

                if($remarks == ''){
                    echo json_encode(array('code'=>400, 'message'=>'ออกแผนการผลิตต่ำกว่า MOQ กรุณากรอกช่อง Remarks ก่อนดำเนินการใหม่อีกครั้ง'));
                    $db_con = null;
                    return;
                }

                $moqList = $db_con->query(
                    "INSERT INTO tbl_approve_job_lessthan_moq(less_job_no, less_level, less_user_code, less_user_name, less_user_position, less_status)
                     VALUES('$job_no', '1', 'ABT02718', 'Anuchit  Tong-on', 'Senior Operation Manager', 'Pending'),('$job_no', '2', 'GDJ00285', 'Itsara Kaewcunok', 'Assistant Plant Director', 'Pending')"
                );

                ///////////////////////////////////////
                /////////// SESSION PATTERN ///////////
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->SMTPDebug  = 0;
                $mail->CharSet = "utf-8";
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Host = $CFG->mail_host;
                $mail->Port = $CFG->mail_port;
                $mail->Username = $CFG->user_smtp_mail;
                $mail->Password = $CFG->password_smtp_mail;
                $mail->SetFrom($CFG->from_mail, 'MRP - Manufacturing');
                $mail_title = " (New request for production approval below the MOQ.)";
                $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

                try {
                    $body = HTMLForm($CFG, 'ABT02718', 'Anuchit Tong-on', $job_no, $listResult['cus_code'], $listResult['fg_code'], $listResult['fg_description'], $listResult['moq'], $conf_quantity, $remarks);
                    $mail->Subject = $t_subject;
                    $mail->MsgHTML($body);
                    // $mail->AddAddress('anuchit.tongon@albatrossthai.com');
                    $mail->AddAddress('bearjai0@gmail.com');
                    $mail->AddCC('sukanyth@glong-duang-jai.com');
                    $mail->Send();
                }catch (phpmailerException $e){
                    echo $e->errorMessage();
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                    sqlsrv_rollback($db_con);
                    return;
                } catch (Exception $e) {
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                    sqlsrv_rollback($db_con);
                    return;
                }
            }
            //* 05 - End Condition >>>>>>>>>>>>>>>>>>>>>>>>>> MOQ Condition >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //* 05 - Start Condition >>>>>>>>>>>>>>>>>>>>>>>> New job post >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $newjob = $db_con->query(
                "INSERT INTO tbl_job_mst(job_no, job_plan_set, job_plan_set_per_job, job_plan_qty, job_delivery, job_plan_date, job_cus_code, job_project, job_bom_id, job_fg_codeset, job_fg_code, job_part_customer, job_ctn_code_normal, job_comp_code, job_fg_description, job_ship_to_type, job_dwg_code, job_rm_code, job_rm_type, job_rm_spec, job_rm_flute, job_rm_usage, job_ft2_perpage, job_ft2_usage, job_pd_usage, job_fg_perpage, job_packing_usage, job_machine_order, job_machine_step, job_type, job_now_in, job_machine_now_in, job_status, job_pc_conf_datetime, job_pc_conf_by, eff_on, eff_by, job_apval, job_remarks, job_moq, job_fac_type)
                 SELECT '$job_no', $ffmc_quantity, $set_per_job_quantity, $conf_quantity, '$delivery_date', '$job_plan_date', cus_code, project, bom_uniq, fg_codeset, fg_code, part_customer, ctn_code_normal, comp_code, fg_description, ship_to_type, dwg_code, '$rm_code', '$rm_type', '$rm_spec', '$rm_flute', $rm_usage_qty, rm_ft2, rm_ft2 * $rm_usage_qty, $pd_usage, $fg_perpage, packing_usage, '".json_encode($machine_arr)."', '$machine_mp', 'FG', 'Production', '$machine_now_in', 'Pending', '$buffer_datetime', '$mrp_user_name_mst', '$buffer_datetime', '$mrp_user_name_mst', '$apval', '$remarks', moq, job_fac_type FROM tbl_job_detail AS A LEFT JOIN tbl_bom_mst AS B ON A.job_bom_id = B.bom_uniq WHERE A.job_uniq = $job_uniq"
            );

            $updet = $db_con->query(
                "UPDATE tbl_job_detail
                 SET job_no = '$job_no',
                     job_now_in = 'Production',
                     job_status = 'Pending'
                 WHERE job_uniq = $job_uniq"
            );

            echo json_encode(array('code'=>200, 'message'=>'ออกแผนการผลิตสำเร็จ Job Number ' . $job_no, 'job_no'=>$job_no));
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