<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $json = array();

    if($protocol == "CustomerOrder"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY A.wf_order_ref, A.wf_post_datetime) AS list, A.*, DATEDIFF(day, '$buffer_date', A.wf_delivery_date) AS datediff,
                        (SELECT COALESCE(SUM(pallet_stock_qty), 0) FROM tbl_fg_inven_mst WHERE pallet_fg_code = A.wf_fg_code AND pallet_status IN('Active','Non-Movement')) AS stock,
                        (SELECT COALESCE(SUM(pallet_receive_qty), 0) FROM tbl_fg_inven_mst WHERE pallet_fg_code = A.wf_fg_code AND pallet_status = 'Prepare') AS wait_putaway,
                        (SELECT COALESCE(SUM(job_plan_set_per_job - job_plan_fg_set_per_job), 0) FROM tbl_job_mst WHERE job_status = 'on production' AND job_fg_code = A.wf_fg_code) AS job_wip,
                        (SELECT COALESCE(SUM((list_pending_qty / job_merge_out) * job_merge_in),0) FROM tbl_confirm_print_list LEFT JOIN tbl_confirm_print_tags ON list_conf_no = conf_code LEFT JOIN tbl_job_mst ON conf_job_no = job_no WHERE list_fg_code = A.wf_fg_code) AS tigthing,
                        (SELECT COALESCE(SUM(sem_stock_qty),0) FROM tbl_semi_inven_mst LEFT JOIN tbl_job_mst ON sem_job_no = job_no WHERE job_fg_code = A.wf_fg_code) AS semi,
                        (SELECT COALESCE(SUM(job_plan_set_per_job),0) FROM tbl_job_mst WHERE job_fg_code = A.wf_fg_code AND job_status = 'pending') AS job_pending,
                        (SELECT COALESCE(SUM(job_plan_set_per_job),0) FROM tbl_job_mst WHERE job_fg_code = A.wf_fg_code AND job_status = 'prepare') AS job_prepare,
                        CASE
                            WHEN DATEDIFF(day, '$buffer_date', A.wf_delivery_date) < 0 THEN 'bg-gradient-dark text-white'
                            WHEN DATEDIFF(day, '$buffer_date', A.wf_delivery_date) < 7 THEN 'bg-gradient-red text-dark'
                            ELSE 'bg-gradient-blue text-white'
                        END AS 'css_class',
                        CASE
                            WHEN DATEDIFF(day, '$buffer_date', A.wf_delivery_date) < 0 THEN 'Delay'
                            WHEN DATEDIFF(day, '$buffer_date', A.wf_delivery_date) < 7 THEN 'Urgent'
                            ELSE 'Normal'
                        END AS 'status'
                 FROM tbl_ffmc_order_mst AS A
                 WHERE A.wf_status IN('Pending','Splitting')
                 ORDER BY A.wf_delivery_date ASC, A.wf_order_ref ASC, A.wf_post_datetime ASC"
            );
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->GetErrorMessage()()));
            $db_con = null;
            return;
        }
    }
?>