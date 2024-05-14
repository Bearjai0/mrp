<?php
    require_once("../../session.php");
    
    try {
        $list = $db_con->query("SELECT TOP(100) pallet_id, raw_code, ref_no, rm_descript FROM tbl_stock_inven_mst AS A LEFT JOIN tbl_invoice_mst AS B ON A.grn = B.grn ORDER BY inven_uniq DESC");
        while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
            $request_for = $listResult['rm_descript'];
            $raw_code =$listResult['raw_code'];
            $po_no = $listResult['ref_no'];
            
            $det = $pu_con->prepare("SELECT po_no, item_code FROM tbl_po_detail WHERE po_no = :po_no AND CAST(request_for AS NVARCHAR(200)) = :request_for");
            $det->bindParam(':po_no', $po_no);
            $det->bindParam(':request_for', $request_for);
            $det->execute();
            $detResult = $det->fetch(PDO::FETCH_ASSOC);

            if($detResult['item_code'] != ''){
                $up = $db_con->prepare("UPDATE tbl_stock_inven_mst SET pallet_item_uniq = :item_code WHERE pallet_id = :pallet_id");
                $up->bindParam(':item_code', $detResult['item_code']);
                $up->bindParam(':pallet_id', $listResult['pallet_id']);
                $up->execute();
            }
        }

        echo 'complete';
        // $db_con->commit();
        // $pu_con->commit():
        $db_con = null;
        $pu_con = null;
        return;
    } catch(Exception $e) {
        echo 'failed';
        $db_con = null;
        $pu_con = null;
        return;
    }
?>