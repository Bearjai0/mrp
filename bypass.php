<?php
    require_once("application.php");

    $usercode = isset($_REQUEST['usercode']) ? $_REQUEST['usercode'] : '';
    $by_route = isset($_REQUEST['by_route']) ? $_REQUEST['by_route'] : "$CFG->wwwroot/home";

    try {
        $list = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user");
        $list->bindParam(':user', $usercode);
        $list->execute();
        $listResult = $list->fetch(PDO::FETCH_ASSOC);

        if($list->rowCount() == 0){
            echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูลผู้ใช้งานหรือข้อมูลไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการอีกครั้ง ' . $list->rowCount()));
            $db_con = null;
            return;
        }

        // $user_default_pass_type = $listResult['user_pass_md5'] == md5('12345') ? 'New' : 'Member';
        $user_default_pass_type = 'Member';
        
        if($user_default_pass_type == "Member"){
            setcookie("mrp_user_code_mst", $listResult['user_code'], time() + 604800, "/");
            setcookie("mrp_user_name_mst", $listResult['user_name_en'], time() + 604800, "/");
            setcookie("mrp_user_dep_id_mst", $listResult['user_dep_id'], time() + 604800, "/");
            setcookie("mrp_user_position_mst", $listResult['user_position'], time() + 604800, "/");
            setcookie("mrp_user_work_type_mst", $listResult['user_work_type'], time() + 604800, "/");
            setcookie("mrp_user_type_code_mst", $listResult['user_type_code'], time() + 604800, "/");
            setcookie("mrp_user_signature_mst", $listResult['user_signature'], time() + 604800, "/");
        }



        header("Location: $by_route");
        $db_con = null;
        return;
    } catch(PDOException $e) {
        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
        $db_con = null;
        return;
    }
?>