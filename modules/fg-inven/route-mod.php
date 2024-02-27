<?php
    function InsertTransactions($db_con, $datas, $rtt = true){

        try {
            $pallet_id = $datas['pallet_id'];
            $location_id = $datas['location_id'];
            $lot_token = $datas['lot_token'];
            $bom_uniq = $datas['bom_uniq'];
            $fg_codeset = $datas['fg_codeset'];
            $fg_code = $datas['fg_code'];
            $comp_code = $datas['comp_code'];
            $part_customer = $datas['part_customer'];
            $fg_description = $datas['fg_description'];
            $cus_code = $datas['cus_code'];
            $project = $datas['project'];
            $ship_to_type = $datas['ship_to_type'];
            $qty = $datas['qty'];
            $status = $datas['status'];
            $datetime = $datas['datetime'];
            $by = $datas['by'];
            $type = $datas['type'];
            $remarks = $datas['remarks'];
            $dtn_no = isset($datas['dtn_no']) ? $datas['dtn_no'] : '';
            $pick_no = isset($datas['pick_no']) ? $datas['pick_no'] : '';
            $inv_no = isset($datas['inv_no']) ? $datas['inv_no'] : '';
            $resv_no = isset($datas['resv_no']) ? $datas['resv_no'] : '';
            $lot_no = isset($datas['lot_no']) ? $datas['lot_no'] : '';

            $sql = $db_con->query(
                "INSERT INTO tbl_fg_inven_transactions_mst(t_inv_pallet_id, t_inv_location, t_inv_lot_no, t_inv_lot_token, t_inv_bom_uniq, t_inv_fg_codeset, t_inv_fg_code, t_inv_comp_code, t_inv_part_customer, t_inv_fg_description, t_inv_cus_code, t_inv_project, t_inv_ship_to_type, t_inv_qty, t_inv_type, t_inv_status, t_inv_datetime, t_inv_by, t_inv_remarks, t_inv_resv_no, t_inv_pick_no, t_inv_dtn_no, t_inv_inv_no)
                VALUES('$pallet_id', '$location_id', '$lot_no', '$lot_token', '$bom_uniq', '$fg_codeset', '$fg_code', '$comp_code', '$part_customer', '$fg_description', '$cus_code', '$project', '$ship_to_type', '$qty', '$type', '$status', '$datetime', '$by', '$remarks', '$resv_no', '$pick_no', '$dtn_no', '$inv_no')"
            );
        } catch(Exception $e) {
            $rtt = false;
        }
        
        return $rtt;
    }
?>