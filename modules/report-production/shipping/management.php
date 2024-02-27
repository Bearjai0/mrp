<?php 
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $job_status = isset($_POST['job_status']) ? $_POST['job_status'] : '';

    $json = array();

    if($protocol == "StationTransactionList"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY uniq_id ASC) AS list, A.pass_job_no, B.job_plan_date, B.job_fg_code, B.job_fg_description, A.pass_fg, A.pass_ng, C.machine_name_en, A.pass_start_datetime, A.pass_end_datetime, D.fg_ft2,
                        E.ope_setting_start_datetime, E.ope_setting_end_datetime,
                        DATEDIFF(SECOND, pass_start_datetime, pass_end_datetime) AS dateDiff, D.fg_ft2, (D.fg_ft2 * pass_fg) AS pass_fg_ft2, (D.fg_ft2 * pass_ng) AS pass_ng_ft2,
                        DATEDIFF(SECOND, ope_setting_start_datetime, ope_setting_end_datetime) AS settingDiff
                 FROM tbl_job_confirm_passing_transaction AS A 
                 LEFT JOIN tbl_job_mst AS B ON A.pass_job_no = B.job_no
                 LEFT JOIN tbl_machine_mst AS C ON A.pass_mc_code = C.machine_code
                 LEFT JOIN tbl_bom_mst AS D ON B.job_bom_id = D.bom_id OR B.job_bom_id = D.bom_uniq
                 LEFT JOIN tbl_job_operation AS E ON A.pass_job_no = E.ope_job_no AND C.machine_type = E.ope_mc_code
                 WHERE CAST(pass_start_datetime AS DATE) >= '$start_date 00:00:00' AND CAST(pass_end_datetime AS DATE) <= '$end_date 23:59:59'
                 ORDER BY uniq_id DESC"
            );

            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                $diff = $listResult['dateDiff'];
                $output = sprintf('%02d:%02d:%02d', ($diff/ 3600),($diff/ 60 % 60), $diff% 60);
                $listResult['dateDiff'] = $output;

                $sett = $listResult['settingDiff'];
                $settout = sprintf('%02d:%02d:%02d', ($sett/ 3600),($sett/ 60 % 60), $sett% 60);
                $listResult['settingDiff'] = $settout;

                array_push($json, $listResult);
                
                
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ShippingLots"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY pallet_uniq DESC) AS list, A.*, B.class_color, C.list_conf_type, C.list_fg_ft2, (C.list_fg_ft2 * A.pallet_receive_qty) AS list_total_fg_ft2
                 FROM tbl_fg_inven_mst AS A
                 LEFT JOIN tbl_status_color AS B ON A.pallet_status = B.hex_status
                 LEFT JOIN tbl_confirm_print_list AS C ON A.pallet_lot_no = C.list_conf_no
                 WHERE A.pallet_gen_datetime BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
                 ORDER BY pallet_uniq DESC"
            );

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ShippingDetails"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY conf_uniq ASC) AS list, A.list_conf_no, C.job_plan_date, A.list_conf_type, B.conf_job_no, A.list_cus_code, A.list_project, A.list_fg_code, A.list_fg_codeset, A.list_part_customer, A.list_fg_description, B.conf_qty, B.conf_packing_usage, A.list_conf_datetime, A.list_conf_by
                 FROM tbl_confirm_print_list AS A
                 LEFT JOIN tbl_confirm_print_tags AS B ON A.list_conf_no = B.conf_code
                 LEFT JOIN tbl_job_mst AS C ON B.conf_job_no = C.job_no
                 WHERE A.list_conf_datetime BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
                 ORDER BY conf_uniq DESC"
            );

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>