<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $po_no = isset($_POST['po_no']) ? $_POST['po_no'] : '';
    $item_code = isset($_POST['item_code']) ? $_POST['item_code'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    if($protocol == "POsDetails"){
        try {
            if($type == "POsList"){
                $list = $pu_con->query(
                    "SELECT ROW_NUMBER() OVER(ORDER  BY po_no DESC) AS list, po_no, vendor_code, vendor_name, credit_term, total, vat, summary_budget, purchase_remark, period
                     FROM tbl_purchase_order
                     WHERE vendor_code IN('SDCP','SSTP','SLFB','SSSP','SIFC','SIEC','SPAN','SSLT') AND po_type = 'refer' AND receipt = 'none'
                     ORDER BY po_no DESC"
                );
            }else if($type == "POsListItem"){
                $list = $pu_con->query("SELECT item_code, request_for FROM tbl_po_detail WHERE po_no = '$po_no'");
            }else if($type == "POsItemDetails"){
                $list = $pu_con->query("SELECT material_unitprice, CAST(material_qty_mrp_path AS INT) AS material_qty_mrp_path, material_summary_mrp_path FROM tbl_po_detail WHERE po_no = '$po_no' AND item_code = '$item_code'");
            }

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $pu_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการแสดงผลข้อมูล POs ได้ ' . $e->getMessage()));
            $pu_con = null;
            return;
        }
    }else if($protocol == "ReceiveRawMaterial"){
        
    }
?>