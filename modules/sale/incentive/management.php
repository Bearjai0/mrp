<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $inc_month = isset($_POST['inc_month']) ? date('Y-m', strtotime($_POST['inc_month'])) : '';

    $json = [];
    $pril = [];
    $arr = [];

    if($protocol == "GetRevenue"){
        try {
            // $MRev = $db_con->query(
            //     "SELECT CASE
            //                 WHEN inv_cus_code IN ('AAP', 'TKM', 'TIT', 'LAT') THEN inv_cus_code
            //                 ELSE 'Included VMI'
            //             END AS GroupCustomer,
            //             SUM(con_res_qty * des_sell_per_pcs) AS revenue, 
            //             SUM(cost_total_oh) AS cost,
            //             SUM(con_res_qty * des_sell_per_pcs) - SUM(cost_total_oh) AS margin
            //      FROM tbl_inv_detail_mst AS A
            //      LEFT JOIN tbl_inv_mst AS B ON A.des_inv_no = B.inv_no
            //      LEFT JOIN tbl_order_dtn_detail_mst AS C ON A.des_det_uniq = C.det_uniq
            //      LEFT JOIN tbl_ffmc_reserve_order AS D ON C.det_ref_code = D.con_ref_code AND D.con_status = 'Picking'
            //      LEFT JOIN tbl_fg_inven_mst AS E ON D.con_res_pallet_id = E.pallet_id
            //      LEFT JOIN tbl_confirm_print_list AS F ON E.pallet_lot_no = F.list_conf_no
            //      LEFT JOIN tbl_bom_mst AS G ON E.pallet_bom_uniq = G.bom_uniq
            //      LEFT JOIN tbl_customer_mst AS H ON B.inv_cus_code = H.cus_code
            //      WHERE B.inv_date LIKE '$inc_month%' AND A.des_status IN('INV','Post Invoice')
            //      GROUP BY 
            //         CASE
            //             WHEN inv_cus_code IN ('AAP', 'TKM', 'TIT', 'LAT') THEN inv_cus_code
            //             ELSE 'Included VMI'
            //         END"
            // );

            $MRev = $db_con->query(
                "SELECT 'Included VMI' AS GroupCustomer,
                        SUM(con_res_qty * des_sell_per_pcs) AS revenue, 
                        SUM(con_res_qty * cost_total_oh) AS cost,
                        SUM(con_res_qty * des_sell_per_pcs) - SUM(con_res_qty * cost_total_oh) AS margin
                 FROM tbl_inv_detail_mst AS A
                 LEFT JOIN tbl_inv_mst AS B ON A.des_inv_no = B.inv_no
                 LEFT JOIN tbl_order_dtn_detail_mst AS C ON A.des_det_uniq = C.det_uniq
                 LEFT JOIN tbl_ffmc_reserve_order AS D ON C.det_ref_code = D.con_ref_code AND D.con_status = 'Picking'
                 LEFT JOIN tbl_fg_inven_mst AS E ON D.con_res_pallet_id = E.pallet_id
                 LEFT JOIN tbl_confirm_print_list AS F ON E.pallet_lot_no = F.list_conf_no
                 LEFT JOIN tbl_bom_mst AS G ON E.pallet_bom_uniq = G.bom_uniq
                 LEFT JOIN tbl_customer_mst AS H ON B.inv_cus_code = H.cus_code
                 WHERE B.inv_date LIKE '$inc_month%' AND A.des_status IN('INV','Post Invoice') AND inv_cus_code NOT IN('AAP1', 'TKM', 'TIT', 'LAT') AND H.cus_type IN('B2B','GDJM')"
            );
            $MResult = $MRev->fetch(PDO::FETCH_ASSOC);


            //todo >>>>>>>>>>>>>>>>>>>> VMI Cost and Selling Details >>>>>>>>>>>>>>>>>>>>
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $VMev = $vmi_con->query(
                "SELECT SUM(G.ps_t_tags_packing_std * det_sell_per_pcs) AS revenue,
                        SUM(G.ps_t_tags_packing_std * J.bom_cost_per_pcs) AS cost,
                        SUM(G.ps_t_tags_packing_std * det_sell_per_pcs) - SUM(G.ps_t_tags_packing_std * J.bom_cost_per_pcs) AS margin
                 FROM tbl_inv_mst AS A
                 LEFT JOIN tbl_inv_detail_mst AS B ON A.inv_no = B.det_inv_no
                 LEFT JOIN tbl_dn_head AS E ON B.det_dn_h_id = E.dn_h_id
                 LEFT JOIN tbl_dn_tail AS F ON B.det_dn_t_id = F.dn_t_id AND dn_t_status != 'Return'
                 LEFT JOIN tbl_picking_tail AS G ON F.dn_t_picking_code = G.ps_t_picking_code
                 LEFT JOIN tbl_receive AS H ON G.ps_t_tags_code = H.receive_tags_code
                 INNER JOIN tbl_dn_usage_conf AS I ON B.det_dn_usage_id = I.dn_usage_id
                                                 AND G.ps_t_picking_code = I.dn_picking_code
                                                 AND G.ps_t_bom_uniq = I.dn_bom_uniq
                                                 AND H.receive_repn_id = I.dn_repn_id
                 LEFT JOIN tbl_bom_mst AS J ON I.dn_bom_uniq = J.bom_uniq
                 LEFT JOIN tbl_fg_inven_mst AS K ON G.ps_t_pallet_code = K.pallet_code
                 LEFT JOIN tbl_confirm_print_list AS L ON K.pallet_job_no = L.list_conf_no
                 WHERE G.ps_t_status != 'Cancel' AND B.det_status != 'Cancel' AND B.det_issue_inv_from = 'GTN' AND A.inv_date LIKE '$inc_month%'"
            ); //! Usage Confirm
            $VMResult = $VMev->fetch(PDO::FETCH_ASSOC);
            
            $MResult['revenue'] += $VMResult['revenue'];
            $MResult['cost'] += $VMResult['cost'];
            $MResult['margin'] += $VMResult['margin'];
            array_push($json, $MResult);
            

            $MIcl = $db_con->query(
                "SELECT inv_cus_code AS GroupCustomer,
                        SUM(con_res_qty * des_sell_per_pcs) AS revenue, 
                        SUM(con_res_qty * cost_total_oh) + (SUM(con_res_qty * des_sell_per_pcs) * cus_cff_ratio) AS cost,
                        SUM(con_res_qty * des_sell_per_pcs) - (SUM(con_res_qty * cost_total_oh) + (SUM(con_res_qty * des_sell_per_pcs) * cus_cff_ratio)) AS margin
                 FROM tbl_inv_detail_mst AS A
                 LEFT JOIN tbl_inv_mst AS B ON A.des_inv_no = B.inv_no
                 LEFT JOIN tbl_order_dtn_detail_mst AS C ON A.des_det_uniq = C.det_uniq
                 LEFT JOIN tbl_ffmc_reserve_order AS D ON C.det_ref_code = D.con_ref_code AND D.con_status = 'Picking'
                 LEFT JOIN tbl_fg_inven_mst AS E ON D.con_res_pallet_id = E.pallet_id
                 LEFT JOIN tbl_confirm_print_list AS F ON E.pallet_lot_no = F.list_conf_no
                 LEFT JOIN tbl_bom_mst AS G ON E.pallet_bom_uniq = G.bom_uniq
                 LEFT JOIN tbl_customer_mst AS H ON B.inv_cus_code = H.cus_code
                 WHERE B.inv_date LIKE '$inc_month%' AND A.des_status IN('INV','Post Invoice') AND H.cus_type IN('B2B','GDJM') AND H.cus_sale_pic IN('GDJ00313','GDJ00310')
                 GROUP BY inv_cus_code, cus_cff_ratio"
            );
            while($MIclResult = $MIcl->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $MIclResult);
            }

            //todo >>>>>>>>>>>>>>>>>>>> Fetch sale list >>>>>>>>>>>>>>>>>>>>
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            foreach($json as $id=>$item){
                $SQList = '';
                if($item['GroupCustomer'] == 'Included VMI'){
                    $SQList = "SELECT user_code, user_name_en, user_position FROM tbl_user where user_dep_id = 'D011' AND user_enable = 1 AND user_position != 'Sale Executive'";
                }else{
                    $SQList = "SELECT user_code, user_name_en, user_position
                               FROM tbl_user AS A 
                               INNER JOIN tbl_customer_mst AS B ON A.user_code = B.cus_sale_pic
                               WHERE B.cus_code = '".$item['GroupCustomer']."'";
                }

                $normalList = $db_con->query($SQList);
                while($normalResult = $normalList->fetch(PDO::FETCH_ASSOC)){
                    $inc_rate = (($item['revenue'] - $item['cost']) / $item['cost']) * 100;
                    $inc = $db_con->prepare("SELECT tiv_rate FROM IncentiveRates WHERE tiv_position = :position AND :incenrate BETWEEN tiv_min AND tiv_max ORDER BY tiv_uniq");
                    $inc->bindParam(':position', $normalResult['user_position']);
                    $inc->bindParam(':incenrate', $inc_rate);
                    $inc->execute();
                    $incResult = $inc->fetch(PDO::FETCH_ASSOC);
                    
                    $normalResult['inc_rate'] = $incResult['tiv_rate'];
                    $normalResult['inc_total'] = $item['revenue'] * ($incResult['tiv_rate'] / 100);
                    array_push($pril, $normalResult);
                }
            }
            
            foreach ($pril as $id=>$item) {
                $item['list'] = $id + 1;
                $user_code = $item["user_code"];
                $user_name_en = $item["user_name_en"];
                $user_position = $item["user_position"];
                $incTotal = $item["inc_total"];
            
                // ถ้ายังไม่มีข้อมูลสำหรับ user_name_en นี้ใน $result ให้เพิ่มเข้าไป
                if (!isset($arr[$user_code])) {
                    $arr[$user_code] = $item;
                } else {
                    // หากมีแล้วให้บวกค่า inc_total เข้ากับข้อมูลเดิม
                    $arr[$user_code]["inc_total"] += $incTotal;
                }
            }


            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json, 'prilla'=>array_values($arr)));
            $db_con = null;
            $vmi_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            $vmi_con = null;
            return;
        }
    }
?>