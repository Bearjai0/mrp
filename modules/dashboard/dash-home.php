<?php 
    require_once("../../application.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('H:i:s');
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('H:i:s');
    $plan_date = isset($_POST['plan_date']) ? $_POST['plan_date'] : '';

    $current_rm = 0;
    $main_job = '';
    $count_job = 0;
    $total_sec = 0;

    $json = array();
    $res = array();
    $name_arr = array();
    $name_value = array();
    $name_cap = array();
    $name_act = array();
    $jipad = array();

    if($protocol == "MachineCapacity"){
        $mat = $db_con->query(
            "SELECT machine_type_code, machine_type_name, setup_time_sec_per_job, COUNT(machine_type) AS count_mc
             FROM tbl_machine_type_mst AS A
             LEFT JOIN tbl_machine_mst AS B ON A.machine_type_code = B.machine_type
             WHERE machine_work_type != 'Setup' AND machine_status = 'Active'
             GROUP BY machine_type_code, machine_type_name, setup_time_sec_per_job"
        );

        while($matResult = $mat->fetch(PDO::FETCH_ASSOC)){
            $machine_type = $matResult['machine_type_code'];
            $json[$machine_type]['id'] = $matResult['machine_type_code'];
            $json[$machine_type]['name'] = $matResult['machine_type_name'];
            $json[$machine_type]['num'] = $matResult['count_mc'];
            $json[$machine_type]['setup_time'] = $matResult['setup_time_sec_per_job'];
            $json[$machine_type]['value'] = 0;
            $json[$machine_type]['actual'] = 0;
            $json[$machine_type]['count_job'] = 0;
        }
        
        $dash = $db_con->query(
            "SELECT COUNT(A.ope_job_no) AS count_job, A.ope_mc_code
             FROM tbl_job_operation AS A
             LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no
             WHERE B.job_plan_date BETWEEN '$start_date' AND '$end_date' AND A.ope_orders > 0
             GROUP BY A.ope_mc_code
             ORDER BY A.ope_mc_code"
        );

        while($dashResult = $dash->fetch(PDO::FETCH_ASSOC)){
            $machine_type = $dashResult['ope_mc_code'];
            $json[$machine_type]['count_job'] = $dashResult['count_job'];
        }

        $list = $db_con->query(
            "SELECT ROW_NUMBER() OVER(ORDER BY A.ope_job_no, A.ope_orders) AS list, A.ope_job_no, A.ope_mc_code, A.ope_in, A.ope_out, A.ope_fg_ttl,
                    B.job_plan_date, B.job_rm_usage, B.job_fac_type, B.job_plan_qty,
                    C.setup_time_sec_per_job, C.running_time_sec_per_page, C.machine_type_name
             FROM tbl_job_operation AS A
             LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no
             LEFT JOIN tbl_machine_type_mst AS C ON A.ope_mc_code = C.machine_type_code
             WHERE B.job_plan_date BETWEEN '$start_date' AND '$end_date' AND A.ope_orders > 0
             ORDER BY A.ope_job_no, A.ope_orders"
        );

        while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
            if($main_job != $listResult['ope_job_no'] && $listResult['list'] != 1){
                $main_job = $listResult['ope_job_no'];
                if($listResult['job_fac_type'] == "CORNER"){
                    $current_rm = $listResult['job_plan_qty'];
                }else{
                    $current_rm = $listResult['job_rm_usage'];
                }

                $json[$listResult['ope_mc_code']]['value'] += $listResult['setup_time_sec_per_job'];
            }else{
                $main_job = $listResult['ope_job_no'];
            }

            $json[$listResult['ope_mc_code']]['value'] += ($listResult['running_time_sec_per_page'] * $current_rm);
            $json[$listResult['ope_mc_code']]['actual'] += ($listResult['running_time_sec_per_page'] * $listResult['ope_fg_ttl']);
            $current_rm = ($current_rm / $listResult['ope_in']) * $listResult['ope_out'];
        }

        $bet = strtotime($end_date) - strtotime($start_date);
        $datediff = round($bet / (60 * 60 * 24)) + 1;
        
        $datediff = $datediff == 0 ? 1 : $datediff;

        $actual_sec = $datediff * 28800;

        foreach($json as $item){
            array_push($name_act, $item['actual'] / 60);
            array_push($name_arr, $item['name'] . '(' . $item['num'] . ')');
            array_push($name_value, $item['value'] / 60);

            $gangi = $item['count_job'] == 0 ? 0 : (($item['count_job'] * $item['setup_time']) / 60);
            $cap = (($item['num'] * $actual_sec) / 60) - $gangi;
            $cap = $cap < 0 ? 0 : $cap;
            array_push($name_cap, $cap);
        }

        $jipad['start_date'] = date('d/m/Y', strtotime($start_date));
        $jipad['end_date'] = date('d/m/Y', strtotime($end_date));

        echo json_encode(array('code'=>'200', 'name'=>$name_arr, 'value'=>$name_value, 'cap'=>$name_cap, 'actual'=>$name_act, 'jipad'=>$jipad));
        sqlsrv_close($db_con);
        return;
    }else if($protocol == "StationCapCompairActual"){
        $listmc = $db_con->prepare(
            "SELECT machine_type_code, machine_type_name, setup_time_sec_per_job, COUNT(machine_type) AS count_mc
             FROM tbl_machine_type_mst AS A
             LEFT JOIN tbl_machine_mst AS B ON A.machine_type_code = B.machine_type
             WHERE machine_work_type != 'Setup' AND machine_status = 'Active'
             GROUP BY machine_type_code, machine_type_name, setup_time_sec_per_job"
        );
    }else if($protocol == "ProductionTime"){
        $mcList = $db_con->query("SELECT machine_type_code, machine_alt_name, setup_time_sec_per_job, running_time_sec_per_page FROM tbl_machine_type_mst WHERE machine_status = 'Active' AND machine_work_type != 'Setup' ORDER BY machine_type_name");
        while($mcResult = $mcList->fetch(PDO::FETCH_ASSOC)){
            $machine_type_code = $mcResult['machine_type_code'];

            $dist = $db_con->prepare("SELECT COUNT(machine_code) AS count_mc FROM tbl_machine_mst WHERE machine_type = :machine_type");
            $dist->bindParam(':machine_type', $machine_type_code);
            $dist->execute();
            $distResult = $dist->fetch(PDO::FETCH_ASSOC);

            $cap_ttl = 480 * $distResult['count_mc'];

            array_push($name_arr, $mcResult['machine_alt_name'] . "(" . $distResult['count_mc'] . ")");
            array_push($name_cap, $cap_ttl);
            array_push($name_act, $cap_ttl);

            $acList = $db_con->prepare("SELECT job_no, ope_orders, job_rm_usage FROM tbl_job_operation AS A LEFT JOIN tbl_job_mst AS B ON A.ope_job_no = B.job_no WHERE B.job_plan_date = :job_plan_date AND A.ope_mc_code = :mc_code GROUP BY job_no, ope_orders, job_rm_usage ORDER BY job_no");
            $acList->bindParam(':job_plan_date', $start_date);
            $acList->bindParam(':mc_code', $machine_type_code);
            $acList->execute();
            while($acListResult = $acList->fetch(PDO::FETCH_ASSOC)){
                $job_no = $acListResult['job_no'];
                $wip = $acListResult['job_rm_usage'];
                $orders = $acListResult['ope_orders'];

                $opList = $db_con->prepare("SELECT ope_in, ope_out FROM tbl_job_operation WHERE ope_job_no = :job_no AND ope_orders > 0 AND ope_orders <= :ope_orders ORDER BY ope_orders");
                $opList->bindParam(':job_no', $job_no);
                $opList->bindParam(':ope_orders', $orders);
                $opList->execute();
                while($opListResult = $opList->fetch(PDO::FETCH_ASSOC)){
                    $wip = intval(($wip / $opListResult['ope_in']) * $opListResult['ope_out']);
                }

                $actual_sec = $mcResult['setup_time_sec_per_job'] + ($wip * $mcResult['running_time_sec_per_page']);
                $total_sec += $actual_sec;
            }

            array_push($name_value, number_format($total_sec / 60, 2));
            $total_sec = 0;
        }

        echo json_encode(array('code'=>200, 'cap'=>$name_cap, 'machine_list'=>$name_arr, 'actual_min'=>$name_value, 'ot'=>$name_act));
        $db_con = null;
        return;
    }
?>