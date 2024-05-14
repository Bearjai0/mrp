<?php
    require_once("../../../session.php");
    
    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    if($protocol == "ListSaleOrder"){
        try {
            $list = $db_con->query(
                "SELECT A.*, B.fg_code, B.fg_codeset, B.part_customer, B.ctn_code_normal, B.fg_description, C.class_color, C.class_txt_color
                 FROM tbl_sale_order_mst AS A 
                 LEFT JOIN tbl_bom_mst AS B ON A.sel_bom_uniq = B.bom_uniq
                 LEFT JOIN tbl_status_color AS C ON A.sel_status = C.hex_status
                 WHERE A.sel_status = 'Pending' ORDER BY sel_uniq DESC"
            );
            
            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
        }
    }
?>