<?php 
    require_once("../../../../session.php");
    require_once('../../../../../library/PHPMailer/class.phpmailer.php');
    require_once("../../../../../library/PHPMailer/sender.php");
    require_once("../../../../email/revise_selling.php");

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';

    $rev_uniq = isset($_POST['rev_uniq']) ? $_POST['rev_uniq'] : '';
    $bom_uniq = isset($_POST['bom_uniq']) ? $_POST['bom_uniq'] : '';
    $selling_price = isset($_POST['selling_price']) ? $_POST['selling_price'] : '';
    $mail_content = isset($_POST['mail_content']) ? $_POST['mail_content'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $json = array();

    if($protocol == "SendMailSelling"){
        try {
            $newRev = $db_con->prepare("INSERT INTO tbl_rev_selling(rev_content, rev_status, rev_datetime, rev_by, rev_now_in) VALUES(:rev_content, 'Pending', :rev_datetime, :rev_by, 'GDJ00232')");
            $newRev->bindParam(':rev_content', $mail_content);
            $newRev->bindParam(':rev_datetime', $buffer_datetime);
            $newRev->bindParam(':rev_by', $mrp_user_name_mst);
            $newRev->execute();
            
            $rev_uniq = $db_con->lastInsertId();
            
            foreach($bom_uniq as $id=>$item){
                $revDetail = $db_con->prepare("INSERT INTO tbl_rev_selling_detail(det_rev_uniq, det_bom_uniq, det_old_price, det_new_price) SELECT :det_rev_uniq, bom_uniq, selling_price, :selling_price FROM tbl_bom_mst WHERE bom_uniq = :bom_uniq");
                $revDetail->bindParam(':det_rev_uniq', $rev_uniq);
                $revDetail->bindParam(':selling_price', $selling_price[$id]);
                $revDetail->bindParam(':bom_uniq', $item);
                $revDetail->execute();
            }

            ///////////////////////////////////////
            /////////// SESSION PATTERN ///////////
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPDebug  = 0;
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = $CFG->mail_host;
            $mail->Port = $CFG->mail_port;
            $mail->Username = $CFG->user_smtp_mail;
            $mail->Password = $CFG->password_smtp_mail;
            $mail->SetFrom($CFG->from_mail, 'MRP - Manufacturing');
            $mail_title = " (Revise Selling Price)";
            $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

            try {
                $body = HTMLForm($db_con, $CFG, $rev_uniq);
                $mail->Subject = $t_subject;
                $mail->MsgHTML($body);
                // $mail->AddAddress('wiwatt@all2gether.net');
                $mail->AddAddress('ampol.k@ttv-supplychain.com');
                $mail->AddCC('wiwatt@all2gether.net');
                $mail->Send();
            }catch (phpmailerException $e){
                echo $e->errorMessage();
                echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                sqlsrv_rollback($db_con);
                return;
            } catch (Exception $e) {
                // echo $e->getMessage();
                echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                sqlsrv_rollback($db_con);
                return;
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการขออนุมัติแก้ไขราคาขายสำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ListReviseSelling"){
        try {
            $list = $db_con->query(
                "SELECT A.*, B.class_color, B.class_txt_color
                 FROM tbl_rev_selling AS A
                 LEFT JOIN tbl_status_color AS B ON A.rev_status = B.hex_status
                 ORDER BY
                    CASE
                        WHEN rev_status = 'Pending' THEN 1
                        WHEN rev_status = 'In-Review' THEN 2
                        ELSE 3
                    END"
            );

            while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                $listResult['rev_content'] = nl2br($listResult['rev_content']);

                array_push($json, $listResult);
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ApproveSelling"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_rev_selling WHERE rev_uniq = :rev_uniq");
            $list->bindParam(':rev_uniq', $rev_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['rev_status'] != 'Pending' && $listResult['rev_status'] != 'In-Review'){
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ สถานะของรายการไม่พร้อมสำหรับการอนุมัติ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            if($mrp_user_code_mst == "GDJ00258"){
                $conlist = $db_con->prepare("SELECT det_bom_uniq, det_new_price FROM tbl_rev_selling_detail WHERE det_rev_uniq = :rev_uniq");
                $conlist->bindParam(':rev_uniq', $rev_uniq);
                $conlist->execute();
                while($conResult = $conlist->fetch(PDO::FETCH_ASSOC)){
                    $up = $db_con->prepare("UPDATE tbl_bom_mst SET selling_price = :selling_price WHERE bom_uniq = :bom_uniq");
                    $up->bindParam(':selling_price', $conResult['det_new_price']);
                    $up->bindParam(':bom_uniq', $conResult['det_bom_uniq']);
                    $up->execute();

                    $vmup = $vmi_con->prepare("UPDATE tbl_bom_mst SET bom_price_sale_per_pcs = :selling_price WHERE bom_uniq = :bom_uniq");
                    $vmup->bindParam(':selling_price', $conResult['det_new_price']);
                    $vmup->bindParam(':bom_uniq', $conResult['det_bom_uniq']);
                    $vmup->execute();
                }

                $stamp_ql = "UPDATE tbl_rev_selling SET rev_status = 'Approved', rev_now_in = '', rev_apfv_datetime = '$buffer_datetime', rev_apfv_by = '$mrp_user_name_mst' WHERE rev_uniq = $rev_uniq";
            }else{
                $stamp_ql = "UPDATE tbl_rev_selling SET rev_status = 'In-Review', rev_now_in = 'GDJ00258', rev_aprv_datetime = '$buffer_datetime', rev_aprv_by = '$mrp_user_name_mst' WHERE rev_uniq = $rev_uniq";
            }

            $stamp = $db_con->prepare($stamp_ql);
            $stamp->execute();

            if($mrp_user_code_mst != 'GDJ00258'){
                ///////////////////////////////////////
                /////////// SESSION PATTERN ///////////
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->SMTPDebug  = 0;
                $mail->CharSet = "utf-8";
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';
                $mail->Host = $CFG->mail_host;
                $mail->Port = $CFG->mail_port;
                $mail->Username = $CFG->user_smtp_mail;
                $mail->Password = $CFG->password_smtp_mail;
                $mail->SetFrom($CFG->from_mail, 'MRP - Manufacturing');
                $mail_title = " (Revise Selling Price)";
                $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

                try {
                    $body = HTMLForm($db_con, $CFG, $rev_uniq);
                    $mail->Subject = $t_subject;
                    $mail->MsgHTML($body);
                    // $mail->AddAddress('wiwatt@all2gether.net');
                    $mail->AddAddress('suphotp@glong-duang-jai.com');
                    $mail->AddCC('wiwatt@all2gether.net');
                    $mail->Send();
                }catch (phpmailerException $e){
                    echo $e->errorMessage();
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                    sqlsrv_rollback($db_con);
                    return;
                } catch (Exception $e) {
                    // echo $e->getMessage();
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                    sqlsrv_rollback($db_con);
                    return;
                }
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการอนุมัติและอัพเดทข้อมูล Selling Price สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol ==  "CancelRequest"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_rev_selling WHERE rev_uniq = :rev_uniq");
            $list->bindParam(':rev_uniq', $rev_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['rev_status'] != "Pending"){
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ สถานะของรายการไม่ใช่ Pending ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                $db_con = null;
                return;
            }

            $up = $db_con->prepare("UPDATE tbl_rev_selling SET rev_status = 'Cancel', rev_remarks = :remarks WHERE rev_uniq = :rev_uniq");
            $up->bindParam(':remarks', $remarks);
            $up->bindParam(':rev_uniq', $rev_uniq);
            $up->execute();

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการยกเลิกรายการสำเร็จ'));
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