<?php
    require_once("../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $inven_uniq = isset($_POST['inven_uniq']) ? $_POST['inven_uniq'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $json = array();

    if($protocol == "WIPList"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY inven_uniq DESC) AS list, inven_uniq, inven_wrn, inven_bom_uniq, inven_fg_code, inven_fg_desc,inven_stock_qty, inven_receive_qty, inven_used_qty, receive_datetime, receive_by, receive_job_ref, inven_remarks
                 FROM tbl_wip_inven_mst AS A
                 WHERE inven_stock_qty > 0
                 ORDER BY inven_uniq DESC"
            );
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "AdjustWIP"){
        try {
            $list = $db_con->query("SELECT * FROM tbl_wip_inven_mst WHERE inven_uniq = $inven_uniq");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);
            $job_no = $listResult['receive_job_ref'];

            if($listResult['inven_stock_qty'] < $quantity){
                echo json_encode(array('code'=>400, 'message'=>'จำนวนที่กรอกเข้ามามีมากกว่าข้อมูลบนระบบ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $list = $db_con->query(
                "UPDATE tbl_wip_inven_mst
                 SET inven_stock_qty -= $quantity,
                     inven_used_qty -= $quantity,
                 WHERE inven_uniq = $inven_uniq"
            );

            $upsemtns = $db_con->query(
                "INSERT INTO tbl_semi_inven_transactions_mst(t_sem_job_no, t_sem_bom_uniq, t_sem_fg_codeset, t_sem_fg_code, t_sem_fg_description, t_sem_part_customer, t_sem_comp_code, t_sem_cus_code, t_sem_project, t_sem_ship_to_type, t_sem_qty, t_sem_type, t_sem_status, t_sem_datetime, t_sem_by, t_sem_remarks)
                 SELECT job_no, job_bom_id, job_fg_codeset, job_fg_code, job_fg_description, job_part_customer, job_comp_code, job_cus_code, job_project, job_ship_to_type, $quantity, 'OUT', 'Transfer To WIP', '$buffer_datetime', '$mrp_user_name_mst', '$remarks' FROM tbl_job_mst WHERE job_no = '$job_no'"
            );
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>