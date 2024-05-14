<?php 
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $job_status = isset($_POST['job_status']) ? $_POST['job_status'] : '';

    $json = [];

    if($protocol == "SummaList"){
        try {
            $stat = $job_status != '' ? " AND job_status = '$job_status'" : '';

            $list = $db_con->prepare(
                "SELECT ROW_NUMBER() OVER(ORDER BY job_no ASC) AS list, 
                        COALESCE(SUM((F.list_pending_qty / A.job_merge_out) * A.job_merge_in),0) AS tigthing,
                        A.job_no, A.job_plan_date, A.job_bom_id, A.job_plan_set, A.job_plan_fg_set, A.job_plan_set_per_job, A.job_plan_fg_set_per_job, A.job_plan_qty, A.job_plan_fg_qty, A.job_plan_ng_qty, A.job_status,
                        A.job_fg_codeset, A.job_fg_code, A.job_fg_description, A.job_cus_code, A.job_project, A.job_part_customer, A.job_ship_to_type, A.job_dwg_code, A.job_rm_code, A.job_rm_spec, A.job_rm_usage,
                        A.job_pc_conf_datetime, A.job_pc_conf_by, A.job_complete_datetime,
                        A.job_fg_perpage, A.job_pd_usage, A.job_pd_conf_datetime, A.job_est_end_datetime, A.job_fac_type,
                        B.machine_type_name,
                        C.cost_rm, C.cost_total, C.selling_price, D.class_color,
                        COALESCE(sem.sem_stock_qty, 0) AS sem_stock_qty
                FROM tbl_job_mst AS A
                LEFT JOIN tbl_semi_inven_mst AS sem ON A.job_no = sem.sem_job_no
                LEFT JOIN tbl_machine_type_mst AS B ON A.job_machine_now_in = B.machine_type_code
                LEFT JOIN tbl_bom_mst AS C ON A.job_bom_id = C.bom_uniq
                LEFT JOIN tbl_status_color AS D ON A.job_status = D.hex_status
                LEFT JOIN tbl_confirm_print_tags AS E ON A.job_no = E.conf_job_no AND E.conf_status = 'combine'
                LEFT JOIN tbl_confirm_print_list AS F ON E.conf_code = F.list_conf_no AND F.list_status != 'Cancel'
                WHERE A.job_plan_date BETWEEN :start_x AND :end_date $stat
                GROUP BY A.job_no, A.job_plan_date, A.job_bom_id, A.job_plan_set, A.job_plan_fg_set, A.job_plan_set_per_job, A.job_plan_fg_set_per_job, A.job_plan_qty, A.job_plan_fg_qty, A.job_plan_ng_qty, A.job_status,
                        A.job_fg_codeset, A.job_fg_code, A.job_fg_description, A.job_cus_code, A.job_project, A.job_part_customer, A.job_ship_to_type, A.job_dwg_code, A.job_rm_code, A.job_rm_spec, A.job_rm_usage,
                        A.job_pc_conf_datetime, A.job_pc_conf_by, A.job_complete_datetime,
                        A.job_fg_perpage, A.job_pd_usage, A.job_pd_conf_datetime, A.job_est_end_datetime, A.job_fac_type,
                        B.machine_type_name,
                        C.cost_rm, C.cost_total, C.selling_price, D.class_color, sem.sem_stock_qty
                ORDER BY A.job_no DESC"
            );
            $list->bindParam(':start_x', $start_date);
            $list->bindParam(':end_date', $end_date);
            $list->execute();
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                if($listResult['job_bom_id'] == 'FG0001-03090'){
                    $cql = $db_con->prepare(
                        "SELECT TOP(1) unit_price FROM tbl_picking_item_mst AS A 
                        LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
                        WHERE picking_job_no = :job_no AND picking_status != 'Cancel'
                        ORDER BY unit_price DESC"
                    );
                    $cql->bindParam(':job_no', $listResult['job_no']);
                    $cql->execute();
                    $cqlResult = $cql->fetch(PDO::FETCH_ASSOC);

                    $result['cost_total'] = $cqlResult['unit_price'];

                    //todo >>>>>>>>>>>>>>>>>>>> CHeck IF job in not complete, compair and extimated the expected time for completion of production >>>>>>>>>>>>>>>>>>>>>>>>>
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                }

                $costQL = "";
                if($listResult['job_fac_type'] == "CORNER"){
                    $cost = $db_con->prepare("SELECT SUM(((picking_qty / 1000) / 1100) * unit_price) AS cost_total FROM tbl_picking_item_mst AS A LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id WHERE picking_job_no = :job_no AND picking_status IN('reserve','generate','Received','shipping')");
                }else{
                    $cost = $db_con->prepare("SELECT SUM(picking_qty * unit_price) AS cost_total FROM tbl_picking_item_mst AS A LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id WHERE picking_job_no = :job_no AND picking_status IN('reserve','generate','Received','shipping')");
                }

                $cost->bindParam(':job_no', $listResult['job_no']);
                $cost->execute();
                $costResult = $cost->fetch(PDO::FETCH_ASSOC);
                $listResult['rm_cost_total'] = $costResult['cost_total'];
                if($listResult['job_plan_fg_set'] == 0){
                    $listResult['actual_cost_rm'] = 0;
                }else{
                    $listResult['actual_cost_rm'] = $costResult['cost_total'] / ($listResult['job_plan_fg_set'] - $listResult['tigthing']);
                }

                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "SummaTransactions"){
        try {
            $sql = '';
            $stat = $job_status != '' ? " AND A.job_status = '$job_status'" : '';

            if($start_date == $end_date && $job_status == ""){
                $sql = "SELECT TOP(1000) ROW_NUMBER() OVER(ORDER BY A.job_no DESC) AS list,
                                        A.job_no, A.job_plan_date, A.job_status, A.job_fg_code, A.job_fg_codeset, A.job_fg_description, A.job_plan_set,
                                        A.job_plan_set_per_job, A.job_cus_code, A.job_project, A.job_pc_conf_datetime, A.job_pc_conf_by, A.job_remarks,
                                        A.job_ft2_perpage, A.job_ft2_usage, A.job_rm_code, A.job_rm_spec, A.job_rm_flute, A.job_fg_perpage, A.job_pd_usage, A.job_rm_usage, A.job_plan_qty,
                                        B.job_ref,
                                        C.class_color
                        FROM tbl_job_mst AS A
                        LEFT JOIN tbl_job_detail AS B ON A.job_no = B.job_no
                        LEFT JOIN tbl_status_color AS C ON A.job_status = C.hex_status
                        ORDER BY A.job_no DESC";
            }else{
                $sql = "SELECT ROW_NUMBER() OVER(ORDER BY A.job_no DESC) AS list,
                                    A.job_no, A.job_plan_date, A.job_status, A.job_fg_code, A.job_fg_codeset, A.job_fg_description, A.job_plan_set,
                                    A.job_plan_set_per_job, A.job_cus_code, A.job_project, A.job_pc_conf_datetime, A.job_pc_conf_by, A.job_remarks,
                                    A.job_ft2_perpage, A.job_ft2_usage, A.job_rm_code, A.job_rm_spec, A.job_rm_flute, A.job_fg_perpage, A.job_pd_usage, A.job_rm_usage, A.job_plan_qty,
                                    B.job_ref,
                                    C.class_color
                        FROM tbl_job_mst AS A
                        LEFT JOIN tbl_job_detail AS B ON A.job_no = B.job_no
                        LEFT JOIN tbl_status_color AS C ON A.job_status = C.hex_status
                        WHERE A.job_plan_date BETWEEN '$start_date' AND '$end_date' $stat
                        ORDER BY A.job_no DESC";
            }
            $list = $db_con->query($sql);

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถแสดงผลข้อมูลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
        
    }
?>