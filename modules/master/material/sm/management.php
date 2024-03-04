<?php
    require_once("../../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    
    $sm_code = isset($_POST['sm_code']) ? $_POST['sm_code'] : '';
    $sm_type = isset($_POST['sm_type']) ? $_POST['sm_type'] : '';
    $sm_name = isset($_POST['sm_name']) ? trim($_POST['sm_name']) : '';
    $sm_result = isset($_POST['sm_result']) ? trim($_POST['sm_result']) : '';
    $sub_unit_rate = isset($_POST['sub_unit_rate']) ? $_POST['sub_unit_rate'] : '';
    $sub_unit_type = isset($_POST['sub_unit_type']) ? trim($_POST['sub_unit_type']) : '';
    $sm_min = isset($_POST['sm_min']) ? $_POST['sm_min'] : '';
    $sm_max = isset($_POST['sm_max']) ? $_POST['sm_max'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $json = array();


    if($protocol == "ListSubMaterial"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY sm_uniq DESC) AS list, sm_code, sm_type, ref_name, sm_name, sub_unit_rate, A.sub_unit_type, sm_status, post_by, post_datetime, sub_type, sm_min, sm_max, class_color, class_txt_color,
                         COALESCE(SUM(C.stock_qty),0) AS stock_qty
                 FROM tbl_sm_mst AS A 
                 LEFT JOIN tbl_status_color AS B ON A.sm_status = B.hex_status
                 LEFT JOIN tbl_stock_inven_mst AS C ON A.sm_code = C.raw_code AND stock_qty > 0 AND area = 'Storage'
                 GROUP BY sm_uniq, sm_code, sm_type, ref_name, sm_name, sub_unit_rate, A.sub_unit_type, sm_status, post_by, post_datetime, sub_type, sm_min, sm_max, class_color, class_txt_color
                 ORDER BY sm_uniq DESC"
            );

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "NewSMDetails"){
        try {
            $exp = explode("|", $sm_type);

            $list = $db_con->query("SELECT sm_uniq FROM tbl_sm_mst WHERE ref_code = '$exp[0]' AND sm_name = '$sm_name'");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);
            if($listResult['sm_uniq'] != ''){
                echo json_encode(array('code'=>400, 'message'=>'พบรายการนี้บนระบบอยู่แล้ว ไม่สามารถบันทึกข้อมูลซ้ำได้'));
                $db_con = null;
                return;
            }

            $ps = $db_con->query("SELECT TOP(1) sm_code FROM tbl_sm_mst ORDER BY sm_uniq DESC");
            $psResult = $ps->fetch(PDO::FETCH_ASSOC);
            $sm_code = 'smid' . PadNumber(intval(substr($psResult['sm_code'], 4)) + 1, 5);

            $newsm = $db_con->prepare(
                "INSERT INTO tbl_sm_mst(sm_code, sm_type, ref_code, sm_name, sub_unit_rate, sm_status, post_by, post_datetime, sub_unit_type, sub_type, sm_min, sm_max)
                 VALUES(:sm_code, :sm_type, :ref_code, :sm_name, :sub_unit_rate, :sm_status, :post_by, :post_datetime, :sub_unit_type, :sub_type, :sm_min, :sm_max)"
            );
            $newsm->bindParam(':sm_code', $sm_code);
            $newsm->bindParam(':sm_type', $exp);

            echo json_encode(array('code'=>400, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateSMDetails"){

    }
?>