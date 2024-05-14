<?php 
    function InsertTransactions($db_con, $datas){
        $returnVal = true;

        $cus_code = $datas['cus_code'];
        $pallet_id = $datas['pallet_id'];
        $pr_no = $datas['pr_no'];
        $inv_no = $datas['inv_no'];
        $grn = $datas['grn'];
        $gdn = isset($datas['gdn']) ? $datas['gdn'] : '';
        $item_descript = $datas['item_descript'];
        $product_type = $datas['product_type'];
        $category_type = $datas['category_type'];
        $qty = $datas['qty'];
        $unit = $datas['unit'];
        $area = $datas['area'];
        $pick_no = isset($datas['pick_no']) ? $datas['pick_no'] : '';
        $location_id = isset($datas['location_id']) ? $datas['location_id'] : '';
        $ship_to = isset($datas['ship_to']) ? $datas['ship_to'] : '';
        $ship_datetime = isset($datas['ship_datetime']) ? $datas['ship_datetime'] : '';
        $trans_remarks = isset($datas['trans_remarks']) ? $datas['trans_remarks'] : '';
        $trans_type = $datas['trans_type'];
        $trans_by = $datas['trans_by'];
        $trans_date = $datas['trans_date'];
        $trans_time = $datas['trans_time'];
        $job_ship_ref = isset($datas['job_ship_ref']) ? $datas['job_ship_ref'] : '';
        $stock_lot_no = isset($datas['stock_lot_no']) ? $datas['stock_lot_no'] : '';
        $rm_type = $datas['rm_type'];
        $rm_color = $datas['rm_color'];
        $trans_remarks = $datas['trans_remarks'];
        $comp_qty = isset($datas['comp_qty']) ? $datas['comp_qty'] : 0;

        $inv = $db_con->prepare(
            "INSERT INTO tbl_inven_transaction_mst(pallet_id, pr_no, inv_no, grn, item_descript, product_type, category_type, qty, unit, area, pick_no, location_id, ship_to, ship_datetime, trans_type, trans_remarks, trans_by, trans_date, trans_time, job_ship_ref, rm_type, stock_lot_no, tns_comp_qty)
             VALUES(:pallet_id, :pr_no, :inv_no, :grn, :item_descript, :product_type, :category_type, :qty, :unit, :area, :pick_no, :location_id, :ship_to, :ship_datetime, :trans_type, :trans_remarks, :trans_by, :trans_date, :trans_time, :job_ship_ref, :rm_type, :stock_lot_no, :tns_comp_qty)"
        );

        $sql = "INSERT INTO tbl_inven_transaction_mst(cus_code, pallet_id, pr_no, inv_no, grn, gdn, item_descript, product_type, category_type, qty, unit, area, pick_no, location_id, ship_to, ship_datetime, trans_type, trans_remarks, trans_by, trans_date, trans_time, job_ship_ref, rm_type, rm_color, stock_lot_no, tns_comp_qty)
                 VALUES('$cus_code', '$pallet_id', '$pr_no', '$inv_no', '$grn', '$gdn', '$item_descript', '$product_type', '$category_type', '$qty', '$unit', '$area', '$pick_no', '$location_id', '$ship_to', '$ship_datetime', '$trans_type', '$trans_remarks', '$trans_by', '$trans_date', '$trans_time', '$job_ship_ref', '$rm_type', '$rm_color', '$stock_lot_no', $comp_qty)";
        $query = sqlsrv_query($db_con, $sql);
        if(!$query){
            $returnVal = false;
        }


        return $returnVal;
    }
?>