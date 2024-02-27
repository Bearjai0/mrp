<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    if($protocol == "IncenRateList"){
        try {
            $list = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY tiv_position, tiv_min) AS list, tiv_name, tiv_position, tiv_min, tiv_max, CAST(tiv_rate AS NVARCHAR(10)) AS tiv_rate, tiv_update_datetime, tiv_update_by, tiv_remarks FROM IncentiveRates ORDER BY tiv_position, tiv_min");
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>