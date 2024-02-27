<?php
    require_once("../../../session.php");
    require_once("../../fg-inven/route-mod.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $bom_uniq = isset($_POST['bom_uniq']) ? $_POST['bom_uniq'] : '';
    $project = isset($_POST['project']) ? $_POST['project'] : '';
    $job_no = isset($_POST['job_no']) ? $_POST['job_no'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

    $pallet_id = isset($_POST['pallet_id']) ? $_POST['pallet_id'] : '';

    if($protocol == "GetDistinctProject"){
        try {
            $list = $db_con->query("SELECT DISTINCT(project) AS project FROM tbl_bom_mst ORDER BY project");
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
        }

        $db_con = null;
        return;
    }else if($protocol == "GetBOMByProject"){
        try {
            $list = $db_con->query("SELECT fg_code, bom_uniq, fg_description FROM tbl_bom_mst WHERE bom_status = 'Active' AND project = '$project'");
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
        }

        $db_con = null;
        return;
    }else if($protocol == "GetBOMDetails"){
        try {
            $list = $db_con->query("SELECT fg_code, fg_codeset, project, cus_code, part_customer, comp_code, fg_description FROM tbl_bom_mst WHERE bom_uniq = '$bom_uniq'");
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetch(PDO::FETCH_ASSOC)));
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
        }

        $db_con = null;
        return;
    }else if($protocol == "ConfirmManual"){
        //******************************* Get Master Details **********************************/
        //*************************************************************************************/
        $list = $db_con->query("SELECT * FROM tbl_bom_mst WHERE bom_uniq = '$bom_uniq'");
        $listResult = $list->fetch(PDO::FETCH_ASSOC);

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
                $u_niq = "SELECT job_bom_id AS bom_uniq, job_ft2_usage AS fg_ft2, job_fg_codeset AS fg_codeset, job_fg_code AS fg_code, job_part_customer AS part_customer, job_comp_code AS comp_code, job_fg_description AS fg_description, job_cus_code AS cus_code, job_project AS project, job_ship_to_type AS ship_to_type, job_packing_usage AS packing_usage FROM tbl_job_mst WHERE job_no = '$job_no[0]'";
            }else{
                $u_niq = "SELECT bom_uniq, fg_ft2, fg_codeset, fg_code, part_customer, comp_code, fg_description, cus_code, project, ship_to_type, packing_usage FROM tbl_bom_mst WHERE bom_uniq = '$bom_uniq'";
            }
            $exe_uniq = $db_con->query($u_niq);
            $listResult = $exe_uniq->fetch(PDO::FETCH_ASSOC);
            $ft2 = $listResult['fg_ft2'] ? $listResult['fg_ft2'] : 0;
            $fg_ft2 = $listResult['fg_ft2'] * $quantity;

            $in_cov = $db_con->query(
                "INSERT INTO tbl_confirm_print_list(list_conf_no, list_conf_type, list_token, list_bom_id, list_fg_codeset, list_fg_code, list_part_customer, list_comp_code, list_fg_description, list_cus_code, list_project, list_ship_to_type, list_receive_qty, list_pending_qty, list_current_qty, list_used_qty, list_packing_usage, list_status, list_conf_datetime, list_conf_by, list_fg_ft2, list_total_fg_ft2)
                 VALUES('$cov_no', 'Manual', '$cov_token', '$bom_uniq', '".$listResult['fg_codeset']."', '".$listResult['fg_code']."', '".$listResult['part_customer']."', '".$listResult['comp_code']."', '".$listResult['fg_description']."', '".$listResult['cus_code']."', '".$listResult['project']."', '".$listResult['ship_to_type']."', $quantity, 0, $quantity, 0, '".$listResult['packing_usage']."', 'Pending', '$buffer_datetime', '$mrp_user_name_mst', '$ft2', '$fg_ft2')"
            );
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการสร้าง Cover Sheet ได้ ' . $e->getMessage()));
            $db_con->rollBack();
            $db_con = null;
            return;
        }


        //************************** Time to generate the pallet ID *************************//
        //************************************************************************************/
        try {
            $prefix = 'PLM' . $buffer_year_2digit . $buffer_month;
            $cps = $db_con->query("SELECT COUNT(pallet_id) AS count_pallet FROM tbl_fg_inven_mst WHERE pallet_id LIKE '$prefix%'");
            $cpsResult = $cps->fetch(PDO::FETCH_ASSOC);
            $pallet_id = SetPrefix($prefix, $cpsResult['count_pallet']);
            $ins = $db_con->query(
                "INSERT INTO tbl_fg_inven_mst(pallet_id, pallet_lot_no, pallet_receive_qty, pallet_stock_qty, pallet_used_qty, pallet_status, pallet_gen_datetime, pallet_gen_by, pallet_bom_uniq, pallet_fg_codeset, pallet_fg_code, pallet_part_customer, pallet_comp_code, pallet_fg_description, pallet_cus_code, pallet_project, pallet_ship_to_type, pallet_job_set, pallet_aging_date)
                VALUES('$pallet_id', '$cov_no', $quantity, 0, 0, 'Prepare', '$buffer_datetime', '$mrp_user_name_mst', '".$listResult['bom_uniq']."', '".$listResult['fg_codeset']."', '".$listResult['fg_code']."', '".$listResult['part_customer']."', '".$listResult['comp_code']."', '".$listResult['fg_description']."', '".$listResult['cus_code']."', '".$listResult['project']."', '".$listResult['ship_to_type']."', '$job_no', '$buffer_date')"
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
                'lot_no' => $cov_no,
                'lot_token' => $cov_token,
                'bom_uniq' => $listResult['bom_uniq'],
                'fg_codeset' => $listResult['fg_codeset'],
                'fg_code' => $listResult['fg_code'],
                'comp_code' => $listResult['comp_code'],
                'part_customer' => $listResult['part_customer'],
                'fg_description' => $listResult['fg_description'],
                'cus_code' => $listResult['cus_code'],
                'project' => $listResult['project'],
                'ship_to_type' => $listResult['ship_to_type'],
                'qty' => $quantity,
                'type' => 'Movement',
                'status' => 'Warehouse Preparing',
                'datetime' => $buffer_datetime,
                'by' => $mrp_user_name_mst,
                'remarks' => 'Confirm Manual'
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


        $json = array('code'=>'200', 'message'=>'ดำเนินการยืนยันการมัดงานและออกเลข Pallet สำเร็จ Pallet เลขที่ ==> ' . $pallet_id, 'route'=>"$CFG->printed_fg_pallet?pallet_id=$pallet_id");
        echo json_encode($json);
        $db_con->commit();
        $db_con = null;
        return;
    }
?>