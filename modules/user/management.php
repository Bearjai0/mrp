<?php
    require_once("../../application.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $usercode = isset($_POST['usercode']) ? $_POST['usercode'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_name_en = isset($_POST['user_name_en']) ? $_POST['user_name_en'] : '';
    $user_name_th = isset($_POST['user_name_th']) ? $_POST['user_name_th'] : '';
    $user_position = isset($_POST['user_position']) ? $_POST['user_position'] : '';
    $user_dep_id = isset($_POST['user_dep_id']) ? $_POST['user_dep_id'] : '';
    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    $user_enable = isset($_POST['user_enable']) ? $_POST['user_enable'] : '';
    $user_cost_access = isset($_POST['user_cost_access']) ? $_POST['user_cost_access'] : '';


    if($protocol == "CHeckAdminLogin"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user AND user_pass_md5 = :pass");
            $list->bindParam(':user', $usercode);
            $list->bindParam(':pass', md5($password));
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($list->rowCount() == 0){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูลผู้ใช้งานหรือข้อมูลไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการอีกครั้ง ' . $list->rowCount()));
                $db_con = null;
                return;
            }

            if($listResult['user_enable'] == 0){
                echo json_encode(array('code'=>400, 'message'=>'ผู้ใช้ถูกจำกัดสิทธิ์การเข้าใช้งาน กรุณาติดต่อ Admin'));
                $db_con = null;
                return;
            }

            $user_default_pass_type = $listResult['user_pass_md5'] == md5('12345') ? 'New' : 'Member';
            
            if($user_default_pass_type == "Member"){
                setcookie("mrp_user_code_mst", $listResult['user_code'], time() + 604800, "/");
                setcookie("mrp_user_name_mst", $listResult['user_name_en'], time() + 604800, "/");
                setcookie("mrp_user_dep_id_mst", $listResult['user_dep_id'], time() + 604800, "/");
                setcookie("mrp_user_position_mst", $listResult['user_position'], time() + 604800, "/");
                setcookie("mrp_user_work_type_mst", $listResult['user_work_type'], time() + 604800, "/");
                setcookie("mrp_user_type_code_mst", $listResult['user_type_code'], time() + 604800, "/");
                setcookie("mrp_user_signature_mst", $listResult['user_signature'], time() + 604800, "/");
            }

            echo json_encode(array('code'=>200, 'message'=>'ok', 'user_code'=>$listResult['user_code'], 'user_name_en'=>$listResult['user_name_en'], 'type'=>$user_default_pass_type, 'route'=>"$CFG->wwwroot/home"));
            $db_con = null;
            return;
        } catch(PDOException $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ChangePassword"){
        try {
            if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
                echo json_encode(array('code'=>400, 'message'=>'กรุณาตรวจสอบรหัสผ่าน ต้องประกอบไปด้วยตัวพิมพ์เล็ก พิมพ์ใหญ่ ตัวเลข และมีจำนวนไม่น้อยกว่า 8 ตัวอักษร'));
                return;
            }

            $list = $db_con->prepare("UPDATE tbl_user SET user_pass_md5 = :pass WHERE user_code = :user");
            $list->bindParam(':user', $usercode);
            $list->bindParam(':pass', md5($password));
            $list->execute();

            $det = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user");
            $det->bindParam(':user', $usercode);
            $det->execute();
            $detResult = $det->fetch(PDO::FETCH_ASSOC);

            setcookie("mrp_user_code_mst", $listResult['user_code'], time() + 604800, "/");
            setcookie("mrp_user_name_mst", $listResult['user_name_en'], time() + 604800, "/");
            setcookie("mrp_user_dep_id_mst", $listResult['user_dep_id'], time() + 604800, "/");
            setcookie("mrp_user_position_mst", $listResult['user_position'], time() + 604800, "/");
            setcookie("mrp_user_work_type_mst", $listResult['user_work_type'], time() + 604800, "/");
            setcookie("mrp_user_type_code_mst", $listResult['user_type_code'], time() + 604800, "/");
            setcookie("mrp_user_signature_mst", $listResult['user_signature'], time() + 604800, "/");

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการอัพเดทรหัสผ่านสำเร็จ กำลังดำเนินการเข้าสู่ระบบ', 'route'=>"$CFG->wwwroot/home"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(PDOException $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UserLogout"){
        setcookie("mrp_user_code_mst", "", time() + 1000, "/");
        setcookie("mrp_user_name_mst", "", time() + 1000, "/");
        setcookie("mrp_user_dep_id_mst", "", time() + 1000, "/");
        setcookie("mrp_user_position_mst", "", time() + 1000, "/");
        setcookie("mrp_user_work_type_mst", "", time() + 1000, "/");
        setcookie("mrp_user_type_code_mst", "", time() + 1000, "/");
        setcookie("mrp_user_signature_mst", "", time() + 1000, "/");

        echo json_encode(array('code'=>'200', 'message'=>'ออกจากระบบสำเร็จ', 'route'=>"$CFG->wwwroot/index"));
        return;
    }else if($protocol == "AllUser"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY user_id DESC) AS list, user_code, user_name_th, user_name_en, user_position, user_type_code, user_email, user_enable, user_issue_datetime, user_issue_by, dep_name_en,
                        CASE WHEN user_enable = 1 THEN 'Active' ELSE 'InActive'END  AS user_status
                 FROM tbl_user AS A 
                 LEFT JOIN tbl_department_mst AS B ON A.user_dep_id = B.dep_id
                 ORDER BY user_id DESC"
            );

            echo json_encode(array('code'=>400, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(PDOException $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateUserDetails"){
        try {
            $s = '';
            if($_FILES['myfile']['tmp_name']){
                $path = $_FILES['myfile']['tmp_name'];
                $image_to_base64 = 'data:image/';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $image_to_base64 .= $type . ';base64,' . base64_encode($data);
                $s = " user_signature = '$image_to_base64'";
            }

            $up = $db_con->prepare(
                "UPDATE tbl_user
                 SET user_name_en = :user_name_en,
                     user_name_th = :user_name_th,
                     user_dep_id = :user_dep_id,
                     user_position = :user_position,
                     user_email = :user_email,
                     user_enable = :user_enable,
                     user_cost_access = :user_cost_access,
                     user_issue_datetime = :issue_datetime,
                     user_issue_by = :issue_by,
                     $s
                 WHERE user_code = :user_code"
            );
            $up->bindParam(':user_name_en', $user_name_en);
            $up->bindParam(':user_name_th', $user_name_th);
            $up->bindParam(':user_dep_id', $user_dep_id);
            $up->bindParam(':user_position', $user_position);
            $up->bindParam(':user_email', $user_email);
            $up->bindParam(':user_enable', $user_enable);
            $up->bindParam(':user_cost_access', $user_cost_access);
            $up->bindParam(':issue_datetime', $buffer_datetime);
            $up->bindParam(':issue_by', $mrp_user_name_mst);
            $up->bindParam(':user_code', $usercode);
            $up->execute();

            echo json_encode(array('code'=>200, 'message'=>'อัพเดทข้อมูลผู้ใช้งานสำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(PDOException $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>