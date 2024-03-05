<?php
    require_once("../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';
    $job_plan_date = isset($_POST['job_plan_date']) ? $_POST['job_plan_date'] : '';

    $cal_sec = 0;
    $cal_datetime;

    $capacity = 28800;
    $produce_actual = 0;

    $json = array();

    if($protocol == "SimulationManagement"){
        try {
            $conpoint = $job_plan_date != '' ? " AND B.job_plan_date = '$job_plan_date' " : '';
            
            $list = $db_con->prepare(
                "SELECT ROW_NUMBER() OVER(ORDER BY B.job_pd_conf_datetime, B.job_plan_date, B.job_no) AS list, A.ope_orders, A.ope_mc_code, A.ope_in, A.ope_out, A.ope_status, A.ope_fg_ttl, A.ope_ng_ttl, A.ope_fg_sendby, B.job_rm_usage, B.job_no, B.job_status, B.job_plan_date, B.job_fg_description, C.setup_time_sec_per_job, C.running_time_sec_per_page
                 FROM tbl_job_operation AS A 
                 LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no
                 LEFT JOIN tbl_machine_type_mst AS C ON A.ope_mc_code = C.machine_type_code
                 WHERE B.job_status = 'on production' AND A.ope_mc_code = :type AND A.ope_status = 'pending' $conpoint
                 ORDER BY B.job_pd_conf_datetime, B.job_plan_date, B.job_priot, B.job_uniq"
            );
            $list->bindParam(':type', $type);
            $list->execute();
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                if($listResult['list'] == 1){
                    $settle = $db_con->prepare(
                        "SELECT TOP(1) pass_end_datetime
                         FROM tbl_job_confirm_passing_transaction AS A 
                         LEFT JOIN tbl_machine_mst AS B ON A.pass_mc_code = B.machine_code
                         WHERE B.machine_type = :machine_type AND CONVERT(date, pass_end_datetime) = :select_date
                         ORDER BY pass_end_datetime DESC"
                    );
                    
                    $settle->bindParam(':machine_type', $type);
                    $settle->bindParam(':select_date', $buffer_date);
                    $settle->execute();
                    $settleResult = $settle->fetch(PDO::FETCH_ASSOC);

                    if($settleResult['pass_end_datetime'] == ''){
                        $cal_datetime = date('Y-m-d H:i:s', strtotime($listResult['job_plan_date'] . ' 08:00:00'));
                    }else{
                        $cal_datetime = date('Y-m-d H:i:s', strtotime($settleResult['pass_end_datetime']));
                    }

                }

                $quantity = $listResult['job_rm_usage'];
                $pclist = $db_con->prepare("SELECT ope_in, ope_out, ope_fg_ttl, ope_ng_ttl, ope_fg_sendby FROM tbl_job_operation WHERE ope_job_no = :job_no AND ope_orders > 0 AND ope_orders <= :ope_orders ORDER BY ope_orders");
                $pclist->bindParam(':job_no', $listResult['job_no']);
                $pclist->bindParam(':ope_orders', $listResult['ope_orders']);
                $pclist->execute();
                while($pclistResult = $pclist->fetch(PDO::FETCH_ASSOC)){
                    if($listResult['ope_mc_code'] == $pclistResult['ope_mc_code']){
                        $quantity = (($quantity / $pclistResult['ope_in']) * $pclistResult['ope_out']) - ($pclistResult['ope_fg_ttl'] + $pclistResult['ope_ng_ttl']);
                    }else{
                        $quantity = (($quantity / $pclistResult['ope_in']) * $pclistResult['ope_out']) - $pclistResult['ope_ng_ttl'];
                    }
                }

                if($mrp_user_type_code_mst == "T005"){
                    $cal_sec = intval($listResult['setup_time_sec_per_job'] + ($listResult['running_time_sec_per_page'] * $quantity));
                    $listResult['wip'] = $quantity;
                    $listResult['start_datetime'] = $cal_datetime;
                    $end_datetime = date('Y-m-d H:i:s', strtotime($cal_datetime) + $cal_sec);

                    $all_sec = strtotime($end_datetime);
                    $all_sec = date('s', strtotime($end_datetime));
                    $diff_sec = $all_sec - (20 * 3600);
                    

                    // $listResult['sec_usage'] = $cal_sec;
                    // $listResult['sec_usage'] = gmdate('i:s', $cal_sec);
                    // แปลงจำนวนวินาทีเป็นชั่วโมง (hours), นาที (minutes), และวินาที (seconds)
                    $hours = floor($cal_sec / 3600); // หารด้วย 3600 เพื่อหาชั่วโมง
                    $minutes = floor(($cal_sec / 60) % 60); // หารด้วย 60 เพื่อหานาที และใช้เศษเพื่อหานาทีที่เหลือหลังหารด้วย 60
                    $seconds = $cal_sec % 60; // หารด้วย 60 เพื่อหาวินาที

                    // สร้างรูปแบบของเวลาในรูปแบบ "00:00:00"
                    $time_stamped = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                    $listResult['sec_usage'] = $time_stamped;
                    $listResult['end_datetime'] = $end_datetime;
                    $cal_datetime = $end_datetime;
                }else{
                    $cal_sec = intval($listResult['setup_time_sec_per_job'] + ($listResult['running_time_sec_per_page'] * $quantity));
                    $listResult['wip'] = $quantity;
                    $listResult['start_datetime'] = $cal_datetime;
                    $end_datetime = date('Y-m-d H:i:s', strtotime($cal_datetime) + $cal_sec);

                    // $listResult['sec_usage'] = $cal_sec;
                    // $listResult['sec_usage'] = gmdate('i:s', $cal_sec);
                    // แปลงจำนวนวินาทีเป็นชั่วโมง (hours), นาที (minutes), และวินาที (seconds)
                    $hours = floor($cal_sec / 3600); // หารด้วย 3600 เพื่อหาชั่วโมง
                    $minutes = floor(($cal_sec / 60) % 60); // หารด้วย 60 เพื่อหานาที และใช้เศษเพื่อหานาทีที่เหลือหลังหารด้วย 60
                    $seconds = $cal_sec % 60; // หารด้วย 60 เพื่อหาวินาที

                    // สร้างรูปแบบของเวลาในรูปแบบ "00:00:00"
                    $time_stamped = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                    $listResult['sec_usage'] = $time_stamped;
                    $listResult['end_datetime'] = $end_datetime;
                    $cal_datetime = $end_datetime;
                }

                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "PlanningSimulationManagement"){
        try {
            $conpoint = $job_plan_date != '' ? " AND B.job_plan_date = '$job_plan_date' " : '';
            
            $list = $db_con->prepare(
                "SELECT ROW_NUMBER() OVER(ORDER BY B.job_plan_date, B.job_no) AS list, A.ope_orders, A.ope_mc_code, A.ope_in, A.ope_out, A.ope_status, A.ope_fg_ttl, A.ope_ng_ttl, A.ope_fg_sendby, B.job_rm_usage, B.job_no, B.job_status, B.job_plan_date, B.job_fg_description, C.setup_time_sec_per_job, C.running_time_sec_per_page
                 FROM tbl_job_operation AS A 
                 LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no
                 LEFT JOIN tbl_machine_type_mst AS C ON A.ope_mc_code = C.machine_type_code
                 WHERE B.job_status IN('pending','on production', 'prepare') AND A.ope_mc_code = :type AND A.ope_status = 'pending' $conpoint
                 ORDER BY B.job_plan_date, B.job_priot, B.job_uniq"
            );
            $list->bindParam(':type', $type);
            $list->execute();
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                if($listResult['list'] == 1){
                    $settle = $db_con->prepare(
                        "SELECT TOP(1) pass_end_datetime
                         FROM tbl_job_confirm_passing_transaction AS A 
                         LEFT JOIN tbl_machine_mst AS B ON A.pass_mc_code = B.machine_code
                         WHERE B.machine_type = :machine_type AND CONVERT(date, pass_end_datetime) = :select_date
                         ORDER BY pass_end_datetime DESC"
                    );
                    
                    $settle->bindParam(':machine_type', $type);
                    $settle->bindParam(':select_date', $buffer_date);
                    $settle->execute();
                    $settleResult = $settle->fetch(PDO::FETCH_ASSOC);

                    if($settleResult['pass_end_datetime'] == ''){
                        $cal_datetime = date('Y-m-d H:i:s', strtotime($listResult['job_plan_date'] . ' 08:00:00'));
                    }else{
                        $cal_datetime = date('Y-m-d H:i:s', strtotime($settleResult['pass_end_datetime']));
                    }

                }

                $quantity = $listResult['job_rm_usage'];
                $pclist = $db_con->prepare("SELECT ope_in, ope_out, ope_fg_ttl, ope_ng_ttl, ope_fg_sendby FROM tbl_job_operation WHERE ope_job_no = :job_no AND ope_orders > 0 AND ope_orders <= :ope_orders ORDER BY ope_orders");
                $pclist->bindParam(':job_no', $listResult['job_no']);
                $pclist->bindParam(':ope_orders', $listResult['ope_orders']);
                $pclist->execute();
                while($pclistResult = $pclist->fetch(PDO::FETCH_ASSOC)){
                    $quantity = ($quantity / $pclistResult['ope_in']) * $pclistResult['ope_out'];
                }

                $cal_sec = intval($listResult['setup_time_sec_per_job'] + ($listResult['running_time_sec_per_page'] * $quantity));
                $produce_actual += $cal_sec;

                // $listResult['sec_usage'] = $cal_sec;
                // $listResult['sec_usage'] = gmdate('i:s', $cal_sec);
                // แปลงจำนวนวินาทีเป็นชั่วโมง (hours), นาที (minutes), และวินาที (seconds)
                $hours = floor($cal_sec / 3600); // หารด้วย 3600 เพื่อหาชั่วโมง
                $minutes = floor(($cal_sec / 60) % 60); // หารด้วย 60 เพื่อหานาที และใช้เศษเพื่อหานาทีที่เหลือหลังหารด้วย 60
                $seconds = $cal_sec % 60; // หารด้วย 60 เพื่อหาวินาที

                // สร้างรูปแบบของเวลาในรูปแบบ "00:00:00"
                $time_stamped = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $listResult['produce_actual'] = $cal_sec;
                $listResult['plan_quantity'] = $quantity;
                $listResult['start_datetime'] = $cal_datetime;
                $end_datetime = date('Y-m-d H:i:s', strtotime($cal_datetime) + $cal_sec);


                $listResult['sec_usage'] = $time_stamped;
                $listResult['end_datetime'] = $end_datetime;
                $cal_datetime = $end_datetime;
                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'message'=>'ok', 'capacity'=>$capacity, 'produce_actual'=>number_format($produce_actual, 0, '.', ','), 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>