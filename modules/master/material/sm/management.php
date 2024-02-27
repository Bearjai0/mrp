<?php
    require_once("../../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $json = array();


    if($protocol == "ListSubMaterial"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(sm_uniq DESC) AS list, sm_code, sm_type, ref_name, sm_name, sub_unit_rate, sm_status, post_by, post_datetime, sub_type, sm_min, sm_max, class_color, class_txt_color
                 FROM tbl_sm_mst AS A 
                 LEFT JOIN tbl_status_color AS B ON A.sm_status = B.hex_status
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
    }
?>