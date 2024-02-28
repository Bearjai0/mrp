<?php
    require_once("../../../../session.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $rm_code = isset($_POST['rm_code']) ? trim($_POST['rm_code']) : '';
    $rm_code = isset($_POST['spec']) ? trim($_POST['spec']) : '';
    $flute = isset($_POST['flute']) ? trim($_POST['flute']) : '';
    $ft_rm = isset($_POST['ft_rm']) ? $_POST['ft_rm'] : '';
    $width_inch = isset($_POST['width_inch']) ? $_POST['width_inch'] : '';
    $long_inch = isset($_POST['long_inch']) ? $_POST['long_inch'] : '';
    $width_mm = isset($_POST['width_mm']) ? $_POST['width_mm'] : '';
    $long_mm = isset($_POST['long_mm']) ? $_POST['long_mm'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $json = array();


    if($protocol == "ListRawMaterial"){
        try {
            $list = $db_con->query(
                "SELECT rm_code, rm_type, spec, flute, width_inch, long_inch, width_mm, long_mm, rm_uom, ft_rm, rm_status, A.create_datetime, A.create_by, update_datetime, update_by, class_color, class_txt_color
                 FROM tbl_rm_mst AS A
                 LEFT JOIN tbl_status_color AS B ON A.rm_status = B.hex_status
                 ORDER BY create_datetime DESC"
            );

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "NewRMDetails"){
        try {
            $list = $db_con->query("SELECT COUNT(rm_code) AS count_rm FROM tbl_rm_mst WHERE rm_code = :rm_code");
            $list->bindParam(':rm_code', $rm_code);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);
            if($listResult['count_rm'] > 0){
                echo json_decode(array('code'=>400, 'message'=>'พบข้อมูล Raw Material นี้บนระบบอยู่แล้วไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            $newb = $db_con->prepare(
                "INSERT INTO tbl_rm_mst(rm_code, rm_type, spec, flute, width_inch, long_inch, width_mm, long_mm, rm_uom, ft_rm, rm_status, create_datetime, create_by, update_datetime, update_by)
                 VALUES(:rm_code, 'PAPER', :spec, :flute, :width_inch, :long_inch, :width_mm, :long_mm, 'Pcs.', :ft_rm, :rm_status, :create_datetime, :create_by, :update_datetime, :update_by)"
            );
            $newb->bindParam(':rm_code', $rm_code);
            $newb->bindParam(':spec', $spec);
            $newb->bindParam(':flute', $flute);
            $newb->bindParam(':width_inch', $width_inch);
            $newb->bindParam(':long_inch', $long_inch);
            $newb->bindParam(':width_mm', $width_mm);
            $newb->bindParam(':long_mm', $long_mm);
            $newb->bindParam(':ft_rm', $ft_rm);
            $newb->bindParam(':rm_status', $rm_status);
            $newb->bindParam(':create_datetime', $buffer_datetime);
            $newb->bindParam(':create_by', $mrp_user_name_mst);
            $newb->bindParam(':update_datetime', $buffer_datetime);
            $newb->bindParam(':update_by', $mrp_user_name_mst);
            $newb->execute();

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการเพิ่มข้อมูล Raw Material สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateRMDetails"){
        try {
            $newb = $db_con->prepare(
                "UPDATE tbl_rm_mst
                 SET spec = :spec,
                     flute = :flute,
                     width_inch = :width_inch,
                     long_inch = :long_inch,
                     width_mm = :width_mm,
                     long_mm = :long_mm,
                     ft_rm = :ft_rm,
                     rm_status = :rm_status,
                     update_datetime = :update_datetime,
                     update_by = :update_by
                 WHERE rm_code = :rm_code"
            );
            $newb->bindParam(':rm_code', $rm_code);
            $newb->bindParam(':spec', $spec);
            $newb->bindParam(':flute', $flute);
            $newb->bindParam(':width_inch', $width_inch);
            $newb->bindParam(':long_inch', $long_inch);
            $newb->bindParam(':width_mm', $width_mm);
            $newb->bindParam(':long_mm', $long_mm);
            $newb->bindParam(':ft_rm', $ft_rm);
            $newb->bindParam(':rm_status', $rm_status);
            $newb->bindParam(':update_datetime', $buffer_datetime);
            $newb->bindParam(':update_by', $mrp_user_name_mst);
            $newb->execute();

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการอัพเดทข้อมูล Raw Material สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>