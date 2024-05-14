<?php
    require_once("../../session.php");

    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : 'UpdateResult';

    if($protocol == "UpdateResult"){
        try {
            $list = $db_con->query("SELECT job_no, job_ffmc_usage, job_fg_usage FROM tbl_job_mst WHERE job_status = 'complete' AND job_plan_date BETWEEN '2024-05-01' AND '2024-05-13' ORDER BY job_no");
            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                $job_no = $listResult['job_no'];
                $fg_set = 0;
                $fg_set_per_job = 0;
                $fg_qty = 0;

                $quan = $db_con->query("SELECT TOP(1) ope_fg_ttl FROM tbl_job_operation WHERE ope_job_no = '$job_no' ORDER BY ope_orders DESC");
                $quanResult = $quan->fetch(PDO::FETCH_ASSOC);
                $quantity = $quanResult['ope_fg_ttl'];


                if($quantity > 0){
                    //todo Check combine machine >>>>>>>>>>
                    $ccmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Combine'");
                    $ccmc->bindParam(':job_no', $job_no);
                    $ccmc->execute();
                    $ccmcResult = $ccmc->fetch(PDO::FETCH_ASSOC);

                    //todo Check Assembly >>>>>>>>>>
                    $asmc = $db_con->prepare("SELECT COUNT(ope_job_no) AS list FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND machine_work_type = 'Assembly'");
                    $asmc->bindParam(':job_no', $job_no);
                    $asmc->execute();
                    $asmcResult = $asmc->fetch(PDO::FETCH_ASSOC);

                    // if($ccmcResult['list'] > 0 && $asmcResult['list'] > 0){
                    if($asmcResult['list'] > 0){
                        $fg_set_per_job = floor($quantity * $listResult['job_ffmc_usage']);
                        $fg_set = floor($fg_set_per_job / $listResult['job_ffmc_usage']);
                        $fg_qty = floor($fg_set_per_job * $listResult['job_fg_usage']);
                    }else{
                        $fg_set_per_job = $quantity;
                        $fg_set = floor( $fg_set_per_job / $listResult['job_ffmc_usage']);
                        $fg_qty = floor($fg_set_per_job * $listResult['job_fg_usage']);
                    }

                    echo $job_no . " " . $fg_set_per_job . "<br>";

                    $up = $db_con->prepare("UPDATE tbl_job_mst SET job_plan_fg_set = :fg_set, job_plan_fg_set_per_job = :set_per_job, job_plan_fg_qty = :fg_qty WHERE job_no = '$job_no'");
                    $up->bindParam(':fg_set', $fg_set);
                    $up->bindParam(':set_per_job', $fg_set_per_job);
                    $up->bindParam(':fg_qty', $fg_qty);
                    $up->execute();
                }
            }

            echo 'success';
            $db_con->commit();
            $db_con = null;
        } catch(Exception $e) {
            echo 'failed ' . $e->getMessage();
            $db_con = null;
            return;
        }
    }
?>