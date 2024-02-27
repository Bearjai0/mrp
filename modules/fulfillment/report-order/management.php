<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $doc_type = isset($_POST['doc_type']) ? $_POST['doc_type'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

    $sql = "";

    $json = array();

    if($protocol == "CustomerOrder"){
        try {
            if($doc_type == "DTN"){
                $sql = "SELECT ROW_NUMBER() OVER(ORDER BY A.wf_delivery_date, A.wf_post_datetime) AS list,
                                A.wf_order_ref, A.wf_delivery_date, A.wf_post_datetime, A.wf_plan_pack, A.wf_fg_code, A.wf_fg_codeset, A.wf_plan_qty, A.wf_part_customer, A.wf_comp_code, A.wf_fg_description, A.wf_cus_code, A.wf_project, A.wf_remarks, A.wf_post_by,
                                B.det_create_datetime AS create_datetime,
                                DATEDIFF(day, B.det_create_datetime, A.wf_delivery_date) AS datediff
                        FROM tbl_ffmc_order_mst AS A
                        LEFT JOIN tbl_order_dtn_detail_mst AS B ON A.wf_ref_code = B.det_ref_code AND B.det_status != 'Cancel'
                        WHERE wf_delivery_date BETWEEN '$start_date' AND '$end_date'
                        ORDER BY A.wf_delivery_date ASC, A.wf_post_datetime ASC";
            }else if($doc_type == "INT"){
                $sql = "SELECT ROW_NUMBER() OVER(ORDER BY A.wf_delivery_date, A.wf_post_datetime) AS list,
                                A.wf_order_ref, A.wf_delivery_date, A.wf_post_datetime, A.wf_plan_pack, A.wf_fg_code, A.wf_fg_codeset,
                                A.wf_plan_qty, A.wf_part_customer, A.wf_comp_code, A.wf_fg_description, A.wf_cus_code, A.wf_project, A.wf_remarks, A.wf_post_by,
                                B.det_int_create_datetime AS create_datetime,
                                DATEDIFF(day, B.det_int_create_datetime, A.wf_delivery_date) AS datediff
                        FROM tbl_ffmc_order_mst AS A
                        LEFT JOIN tbl_order_int_detail_mst AS B ON A.wf_ref_code = B.det_int_ref_code AND B.det_int_status != 'Cancel'
                        WHERE wf_delivery_date BETWEEN '$start_date' AND '$end_date'
                        ORDER BY A.wf_delivery_date ASC, A.wf_post_datetime ASC";
            }

            $list = $db_con->query($sql);
            
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