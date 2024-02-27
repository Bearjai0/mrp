<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $ts_uniq = isset($_POST['ts_uniq']) ? $_POST['ts_uniq'] : '';

    if($protocol == "WithdrawList"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY ts_uniq) AS list, A.*, B.sup_name_en, C.class_color, C.class_txt_color
                 FROM tbl_tooling_mst AS A
                 LEFT JOIN tbl_supplier_mst AS B ON A.ts_sup_uniq = B.run_number
                 LEFT JOIN tbl_status_color AS C ON A.ts_status = C.hex_status
                 WHERE ts_status = 'In Use' ORDER BY ts_uniq"
            );
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ReturnTooling"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_tooling_mst WHERE ts_uniq = :ts_uniq");
            $list->bindParam(':ts_uniq', $ts_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['ts_status'] != 'In Use'){
                echo json_encode(array('code'=>400, 'message'=>'สถานะของ Tooling ไม่พร้อมสำหรับการ Return Tooling ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            try {
                $up = $db_con->query("UPDATE tbl_tooling_mst SET ts_status = 'Active' WHERE ts_uniq = $ts_uniq");

                $tns = $db_con->query(
                    "INSERT INTO tbl_tooling_transactions_mst(tns_ts_uniq, tns_ts_type, tns_ts_sub_type, tns_ts_tooling_name, tns_ts_location, tns_ts_layout, tns_ts_price, tns_ts_stroke, tns_ts_sup_uniq, tns_ts_status, tns_datetime, tns_by)
                     SELECT ts_uniq, ts_type, ts_sub_type, ts_tooling_name, ts_location, ts_layout, ts_price, ts_stroke, ts_sup_uniq, 'Return', '$buffer_datetime', '$mrp_user_name_mst' FROM tbl_tooling_mst WHERE ts_uniq = $ts_uniq"
                );
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูลได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการ Return Tooling สำเร็จ'));
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