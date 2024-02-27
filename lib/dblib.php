<?php
    function InsertRMInventoryTransactions($db_con, $datas){
        $arr = [];

        try {
            $sql = $db_con->prepare(
                "INSERT INTO tbl_inven_transaction_mst(cus_code, pallet_id, pr_no, inv_no, grn, gdn, item_descript, product_type, category_type, qty, unit, area, pick_no, location_id, trans_type, trans_remarks, trans_by, trans_date, trans_time, job_ship_ref, stock_lot_no, tns_comp_qty)
                 VALUES('GDJM, :pallet_id, :pr_no, :inv_no, :grn, :gdn, :item_descript, :product_type, :category_type, :qty, :unit, :area, :pick_no, :location_id, :trans_type, :trans_remarks, :trans_by, :trans_date, :trans_time, :job_ship_ref, :stock_lot_no, :tns_comp_qty)"
            );
            $list->bindParam(':pallet_id', isset($datas['pallet_id']) ? $datas['pallet_id'] : NULL);
            $list->bindParam(':pr_no', isset($datas['pr_no']) ? $datas['pr_no'] : NULL);
            $list->bindParam(':inv_no', isset($datas['inv_no']) ? $datas['inv_no'] : NULL);
            $list->bindParam(':grn', isset($datas['grn']) ? $datas['grn'] : NULL);
            $list->bindParam(':gdn', isset($datas['gdn']) ? $datas['gdn'] : NULL);
            $list->bindParam(':item_descript', isset($datas['item_descript']) ? $datas['item_descript'] : NULL);
            $list->bindParam(':product_type', isset($datas['product_type']) ? $datas['product_type'] : NULL);
            $list->bindParam(':category_type', isset($datas['category_type']) ? $datas['category_type'] : NULL);
            $list->bindParam(':qty', isset($datas['qty']) ? $datas['qty'] : NULL);
            $list->bindParam(':unit', isset($datas['unit']) ? $datas['unit'] : NULL);
            $list->bindParam(':area', isset($datas['area']) ? $datas['area'] : NULL);
            $list->bindParam(':pick_no', isset($datas['pick_no']) ? $datas['pick_no'] : NULL);
            $list->bindParam(':location_id', isset($datas['location_id']) ? $datas['location_id'] : NULL);
            $list->bindParam(':trans_type', isset($datas['trans_type']) ? $datas['trans_type'] : NULL);
            $list->bindParam(':trans_remarks', isset($datas['trans_remarks']) ? $datas['trans_remarks'] : NULL);
            $list->bindParam(':trans_by', isset($datas['trans_by']) ? $datas['trans_by'] : NULL);
            $list->bindParam(':trans_date', isset($datas['trans_date']) ? $datas['trans_date'] : NULL);
            $list->bindParam(':trans_time', isset($datas['trans_time']) ? $datas['trans_time'] : NULL);
            $list->bindParam(':job_ship_ref', isset($datas['job_ship_ref']) ? $datas['job_ship_ref'] : NULL);
            $list->bindParam(':stock_lot_no', isset($datas['stock_lot_no']) ? $datas['stock_lot_no'] : NULL);
            $list->bindParam(':tns_comp_qty', isset($datas['tns_comp_qty']) ? $datas['tns_comp_qty'] : NULL);
    
            $list->execute();

            $arr = ['code'=>200, 'message'=>'ok'];
        } catch(Exception $e) {
            $arr = ['code'=>400, 'message'=>'ไม่สามารถบันทึก Transactions ได้ ' . $e->getMessage()];
        }

        return json_encode($arr);
    }
?>