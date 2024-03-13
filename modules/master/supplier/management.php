<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $json = [];


    if($protocol == "MasterSupplier"){
        try {
            $list = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY sup_name_en) AS list, sup_code, sup_name_en, branch, update_datetime, update_by, active FROM tbl_supplier_mst ORDER BY sup_name_en");
            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>