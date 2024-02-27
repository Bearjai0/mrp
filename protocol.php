<?php
    require_once("application.php");

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    $cii_code = isset($_REQUEST['cii_code']) ? $_REQUEST['cii_code'] : '';
    $variab = isset($_REQUEST['variab']) ? $_REQUEST['variab'] : '';
    $t_varib = isset($_REQUEST['t_varib']) ? $_REQUEST['t_varib'] : ''; // type of variable ( use when collect a sub variable to check type of condition)

    $sql;
    $json = [];

    if($protocol == "tbl_machine_type_mst"){
        try {
            if($cii_code == "code_and_name"){
                $sql = $db_con->prepare("SELECT machine_type_code, machine_type_name FROM tbl_machine_type_mst ORDER BY machine_type_name");
            }else{
                $sql = $db_con->prepare("SELECT * FROM tbl_machine_type_mst ORDER BY machine_type_name");
            }
    
            $sql->execute();
            while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $result);
            }
    
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
        }
    }else if($protocol == "tbl_machine_mst"){
        try {
            if($cii_code == "code_and_name"){
                $sql = $db_con->prepare("SELECT machine_code, machine_name_en FROM tbl_machine_mst WHERE machine_type = '$variab' ORDER BY machine_name_en");
            }else{
                $sql = $db_con->prepare("SELECT * FROM tbl_machine_mst ORDER BY machine_name_en");
            }
    
            $sql->execute();
            while($result = $sql->fetch(PDO::FETCH_ASSOC)){
                array_push($json, $result);
            }
    
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
        }
    }else if($protocol == "tbl_bom_mst"){
        try {
            if($cii_code == "uniq"){
                $sql = $db_con->prepare("SELECT fg_code, fg_codeset, part_customer, fg_description, comp_code, packing_usage, cus_code, project FROM tbl_bom_mst WHERE bom_uniq = '$variab'");
            }else{
                $sql = $db_con->prepare("SELECT * FROM tbl_bom_mst WHERE bom_status = 'Active'");
            }
            $sql->execute();

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$sql->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
        }
    }else if($protocol == "tbl_rm_mst"){
        try {
            if($cii_code == "code"){
                $sql = $db_con->prepare("SELECT rm_code FROM tbl_rm_mst ORDER BY rm_code");
            }else if($cii_code == "code_and_spec"){
                $sql = $db_con->prepare("SELECT rm_code, spec FROM tbl_rm_mst ORDER BY rm_code");
            }else if($cii_code == "identity"){
                $sql = $db_con->prepare("SELECT * FROM tbl_rm_mst WHERE rm_code = :rm_code");
                $sql->bindParam(':rm_code', $variab);
            }else{
                $sql = $db_con->prepare("SELECT rm_code, spec, flute FROM tbl_bom_mst ORDER BY rm_code");
            }

            $sql->execute();

            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$sql->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
        }
    }
?>