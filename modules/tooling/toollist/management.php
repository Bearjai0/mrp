<?php
    require_once("../../../session.php");
    require_once("../../../../library/PHPSpreadSheet/vendor/autoload.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);


    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    $ts_uniq = isset($_POST['ts_uniq']) ? $_POST['ts_uniq'] : '';
    $ts_job_no = isset($_POST['ts_job_no']) ? $_POST['ts_job_no'] : '';
    $ts_fg_code = isset($_POST['ts_fg_code']) ? $_POST['ts_fg_code'] : '';
    $ts_fg_description = isset($_POST['ts_fg_description']) ? $_POST['ts_fg_description'] : '';
    $ts_tooling_name = isset($_POST['ts_tooling_name']) ? $_POST['ts_tooling_name'] : '';
    $ts_sup_uniq = isset($_POST['ts_sup_uniq']) ? $_POST['ts_sup_uniq'] : '';
    $ts_price = isset($_POST['ts_price']) ? $_POST['ts_price'] : '';
    $ts_location = isset($_POST['ts_location']) ? $_POST['ts_location'] : '';
    $ts_type = isset($_POST['ts_type']) ? $_POST['ts_type'] : '';
    $ts_sub_type = isset($_POST['ts_sub_type']) ? $_POST['ts_sub_type'] : '';
    $ts_stroke = isset($_POST['ts_stroke']) ? $_POST['ts_stroke'] : '';
    $ts_layout = isset($_POST['ts_layout']) ? $_POST['ts_layout'] : '';
    $ts_status = isset($_POST['ts_status']) ? $_POST['ts_status'] : '';
    $ts_remarks = isset($_POST['ts_remarks']) ? $_POST['ts_remarks'] : '';
    $ts_files = isset($_FILES['ts_files']) ? $_FILES['ts_files']['tmp_name'] : [];

    if($protocol == "ToolingList"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY ts_uniq) AS list, A.*, B.sup_name_en, C.class_color, C.class_txt_color
                 FROM tbl_tooling_mst AS A
                 LEFT JOIN tbl_supplier_mst AS B ON A.ts_sup_uniq = B.run_number
                 LEFT JOIN tbl_status_color AS C ON A.ts_status = C.hex_status
                 WHERE ts_type = '$type' ORDER BY ts_uniq"
            );
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
        }
    }else if($protocol == "UpdateTooling"){
        try {
            try {
                $up = $db_con->prepare(
                    "UPDATE tbl_tooling_mst
                     SET ts_type = :ts_type,
                         ts_sub_type = :ts_sub_type,
                         ts_tooling_name = :ts_tooling_name,
                         ts_location = :ts_location,
                         ts_layout = :ts_layout,
                         ts_price = :ts_price,
                         ts_sup_uniq = :ts_sup_uniq,
                         ts_remarks = :ts_remarks,
                         ts_status = :ts_status,
                         ts_update_datetime = :ts_update_datetime,
                         ts_update_by = :ts_update_by
                     WHERE ts_uniq = $ts_uniq"
                );
                $up->bindParam(':ts_type', $ts_type);
                $up->bindParam(':ts_sub_type', $ts_sub_type);
                $up->bindParam(':ts_tooling_name', $ts_tooling_name);
                $up->bindParam(':ts_location', $ts_location);
                $up->bindParam(':ts_layout', $ts_layout);
                $up->bindParam(':ts_price', str_replace(",","", $ts_price));
                $up->bindParam(':ts_sup_uniq', $ts_sup_uniq);
                $up->bindParam(':ts_remarks', $ts_remarks);
                $up->bindParam(':ts_status', $ts_status);
                $up->bindParam(':ts_update_datetime', $buffer_datetime);
                $up->bindParam(':ts_update_by', $mrp_user_name_mst);
                $up->execute();
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Tooling ได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            try {
                if($ts_files){
                    $tail_file = pathinfo($_FILES['ts_files']['name'])['extension'];
                    $file_name = $ts_uniq . '-' . $ts_fg_code . '.' . $tail_file;
                    
                    if(move_uploaded_file($ts_files, '../../../../pur/dwg-quotation/' . $file_name)){
                        try {
                            $setf = $db_con->prepare("UPDATE tbl_tooling_mst SET ts_attach_file = :ts_attach_file WHERE ts_uniq = $ts_uniq");
                            $setf->bindParam(':ts_attach_file', $file_name);
                            $setf->execute();
                        } catch(Exception $e) {
                            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Attached File ได้ ' . $e->getMessage()));
                            $db_con = null;
                            return;
                        }
                    }
                }
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Attach file ได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }
            
            echo json_encode(array('code'=>200, 'message'=>'อัพเดทข้อมูล Tooling สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "WithdrawTooling"){
        try {
            $list = $db_con->query("SELECT * FROM tbl_tooling_mst WHERE ts_uniq = $ts_uniq");
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['ts_status'] != 'Active'){
                echo json_encode(array('code'=>200, 'message'=>'สถานะไม่พร้อมสำหรับการเบิกใช้งาน ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            try {
                $jlist = $db_con->prepare("SELECT * FROM tbl_job_mst WHERE job_no = :job_no");
                $jlist->bindParam(':job_no', $ts_job_no);
                $jlist->execute();
                $jlistResult = $jlist->fetch(PDO::FETCH_ASSOC);

                if($jlistResult['job_no'] == ""){
                    echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูล Job number ดังกล่าวบนระบบ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    $db_con = null;
                    return;
                }


                $tns = $db_con->query(
                    "INSERT INTO tbl_tooling_transactions_mst(tns_ts_uniq, tns_ts_type, tns_ts_sub_type, tns_ts_tooling_name, tns_ts_location, tns_ts_layout, tns_ts_price, tns_ts_stroke, tns_ts_sup_uniq, tns_ts_status, tns_job_no, tns_datetime, tns_by)
                     SELECT ts_uniq, ts_type, ts_sub_type, ts_tooling_name, ts_location, ts_layout, ts_price, ts_stroke, ts_sup_uniq, 'Withdraw', '$ts_job_no', '$buffer_datetime', '$mrp_user_name_mst' FROM tbl_tooling_mst WHERE ts_uniq = $ts_uniq"
                );
            } catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถเบิกใช้งานได้ ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            try {
                $up = $db_con->query("UPDATE tbl_tooling_mst SET ts_status = 'In Use' WHERE ts_uniq = $ts_uniq");
            }catch(Exception $e) {
                echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถเบิกใช้งานได้x ' . $e->getMessage()));
                $db_con = null;
                return;
            }

            echo json_encode(array('code'=>200, 'message'=>'เบิกใช้งาน Tooling สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "FGList"){
        try {
            $list = $db_con->query("SELECT fg_code, fg_description FROM tbl_bom_mst WHERE bom_status = 'Active' GROUP BY fg_code, fg_description ORDER BY fg_code");
            
            echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "AddNewTooling"){
        $ts_fg_code = explode("|", $ts_fg_code)[0];
        
        try {
            $list = $db_con->prepare("SELECT COUNT(ts_uniq) AS count_uniq FROM tbl_tooling_mst WHERE ts_fg_code = :ts_fg_code AND ts_fg_description = :ts_fg_description");
            $list->bindParam(':ts_fg_code', $ts_fg_code);
            $list->bindParam(':ts_fg_description', $ts_fg_description);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);
            if($listResult['count_uniq'] > 0){
                echo json_encode(array('code'=>400, 'message'=>'พบรายการ FG Code, FG Description นี้มี Tooling บนระบบอยู่แล้ว ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง ' . $e->getMessage()));
                $db_con = null;
                return;
            }
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถ CHeck match master ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }

        try {
            $list = $db_con->prepare("SELECT COUNT(ts_uniq) AS count_uniq FROM tbl_tooling_mst WHERE ts_tooling_name = :ts_tooling_name");
            $list->bindParam(':ts_tooling_name', $ts_tooling_name);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);
            if($listResult['count_uniq'] > 0){
                echo json_encode(array('code'=>400, 'message'=>'มีการใช้งานชื่อ Tooling นี้บนระบบอยู่แล้ว เปลี่ยนชื่อ Tooling แล้วดำเนินการใหม่อีกครั้ง ' . $e->getMessage()));
                $db_con = null;
                return;
            }
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถ CHeck match master ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }

        try {
            $in = $db_con->prepare(
                "INSERT INTO tbl_tooling_mst(ts_type, ts_sub_type, ts_fg_code, ts_fg_description, ts_tooling_name, ts_location, ts_layout, ts_price, ts_stroke, ts_sup_uniq, ts_status, ts_remarks, ts_upload_datetime, ts_upload_by, ts_update_datetime, ts_update_by)
                 VALUES(:ts_type, :ts_sub_type, :ts_fg_code, :ts_fg_description, :ts_tooling_name, :ts_location, :ts_layout, :ts_price, :ts_stroke, :ts_sup_uniq, :ts_status, :ts_remarks, :ts_upload_datetime, :ts_upload_by, :ts_update_datetime, :ts_update_by)"
            );
            $in->bindParam(':ts_type', $ts_type);
            $in->bindParam(':ts_sub_type', $ts_sub_type);
            $in->bindParam(':ts_fg_code', $ts_fg_code);
            $in->bindParam(':ts_fg_description', $ts_fg_description);
            $in->bindParam(':ts_tooling_name', $ts_tooling_name);
            $in->bindParam(':ts_location', $ts_location);
            $in->bindParam(':ts_layout', $ts_layout);
            $in->bindParam(':ts_price', $ts_price);
            $in->bindParam(':ts_stroke', $ts_stroke);
            $in->bindParam(':ts_sup_uniq', $ts_sup_uniq);
            $in->bindParam(':ts_status', $ts_status);
            $in->bindParam(':ts_remarks', $ts_remarks);
            $in->bindParam(':ts_upload_datetime', $buffer_datetime);
            $in->bindParam(':ts_upload_by', $mrp_user_name_mst);
            $in->bindParam(':ts_update_datetime', $buffer_datetime);
            $in->bindParam(':ts_update_by', $mrp_user_name_mst);
            $in->execute();
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล New Tooling ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }

        try {
            if($ts_files){
                $getuniq = $db_con->query("SELECT ts_uniq FROM tbl_tooling_mst WHERE ts_fg_code = '$ts_fg_code' AND ts_fg_description = '$ts_fg_description'");
                $getResult = $getuniq->fetch(PDO::FETCH_ASSOC);
                $ts_uniq = $getResult['ts_uniq'];

                $tail_file = pathinfo($_FILES['ts_files']['name'])['extension'];
                $file_name = $ts_uniq . '-' . $ts_fg_code . '.' . $tail_file;
                
                if(move_uploaded_file($ts_files, '../../../../pur/dwg-quotation/' . $file_name)){
                    try {
                        $setf = $db_con->prepare("UPDATE tbl_tooling_mst SET ts_attach_file = :ts_attach_file WHERE ts_uniq = $ts_uniq");
                        $setf->bindParam(':ts_attach_file', $file_name);
                        $setf->execute();
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพเดทข้อมูล Attached File ได้ ' . $e->getMessage()));
                        $db_con = null;
                        return;
                    }
                }
            }
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูล Attach file ได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
        
        echo json_encode(array('code'=>200, 'message'=>'บันทึกข้อมูล New Tooling สำเร็จ'));
        $db_con->commit();
        $db_con = null;
        return;
    }else if($protocol == "UploadNewToolings"){
        try {
            $spreadsheet = $reader->load($ts_files);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow();
            
            for($i=3;$i<=$highestRow;$i++){
                $ts_type = trim($data->getCell("C$i")->getValue());
                $ts_fg_code = trim($data->getCell("D$i")->getValue());
                $ts_fg_description = trim($data->getCell("E$i")->getValue());
                $ts_tooling_name = trim($data->getCell("F$i")->getValue());
                $ts_location = trim($data->getCell("G$i")->getValue());
                $ts_sub_type = trim($data->getCell("H$i")->getValue());
                $ts_layout = trim($data->getCell("I$i")->getValue());
                $ts_price = trim($data->getCell("J$i")->getValue());
                $ts_stroke = trim($data->getCell("K$i")->getValue());
                $ts_sup_name = trim($data->getCell("L$i")->getValue());
                

                $check_bom = $db_con->prepare("SELECT COUNT(bom_uniq) AS count_uniq FROM tbl_bom_mst WHERE fg_code = :fg_code AND fg_description = :fg_description AND bom_status = 'Active'");
                $check_bom->bindParam(':fg_code', $ts_fg_code);
                $check_bom->bindParam(':fg_description', $ts_fg_description);
                $check_bom->execute();
                $checkResult = $check_bom->fetch(PDO::FETCH_ASSOC);

                if($checkResult['count_uniq'] == 0){
                    echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล FG Code => $ts_fg_code, FG Description => $ts_fg_description นี้บนระบบหรือสถานะของ BOM ไม่ Active ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                $check_sup = $db_con->prepare("SELECT run_number AS ts_sup_uniq, sup_name_en FROM tbl_supplier_mst WHERE sup_name_en = :sup_name_en ORDER BY run_number DESC");
                $check_sup->bindParam(':sup_name_en', $ts_sup_name);
                $check_sup->execute();
                $supResult = $check_sup->fetch(PDO::FETCH_ASSOC);
                if($supResult['sup_name_en'] == ''){
                    echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Supplier $ts_sup_name ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }

                try {
                    $decis = $db_con->prepare("SELECT COUNT(ts_uniq) AS decis FROM tbl_tooling_mst WHERE ts_fg_code = :ts_fg_code AND ts_fg_description = :ts_fg_description");
                    $decis->bindParam(':ts_fg_code', $ts_fg_code);
                    $decis->bindParam(':ts_fg_description', $ts_fg_description);
                    $decis->execute();
                    $decisResult = $decis->fetch(PDO::FETCH_ASSOC);

                    if($decisResult['decis'] == 0){
                        $newTool = $db_con->prepare(
                            "INSERT INTO tbl_tooling_mst(ts_type, ts_sub_type, ts_fg_code, ts_fg_description, ts_tooling_name, ts_location, ts_layout, ts_price, ts_stroke, ts_sup_uniq, ts_status, ts_remarks, ts_upload_datetime, ts_upload_by, ts_update_datetime, ts_update_by)
                             VALUES(:ts_type, :ts_sub_type, :ts_fg_code, :ts_fg_description, :ts_tooling_name, :ts_location, :ts_layout, :ts_price, :ts_stroke, :ts_sup_uniq, 'Active', :ts_remarks, :ts_upload_datetime, :ts_upload_by, :ts_update_datetime, :ts_update_by)"
                        );
                        $newTool->bindParam(':ts_type', $ts_type);
                        $newTool->bindParam(':ts_sub_type', $ts_sub_type);
                        $newTool->bindParam(':ts_fg_code', $ts_fg_code);
                        $newTool->bindParam(':ts_fg_description', $ts_fg_description);
                        $newTool->bindParam(':ts_tooling_name', $ts_tooling_name);
                        $newTool->bindParam(':ts_location', $ts_location);
                        $newTool->bindParam(':ts_layout', $ts_layout);
                        $newTool->bindParam(':ts_price', $ts_price);
                        $newTool->bindParam(':ts_stroke', $ts_stroke);
                        $newTool->bindParam(':ts_sup_uniq', $supResult['ts_sup_uniq']);
                        $newTool->bindParam(':ts_remarks', $ts_remarks);
                        $newTool->bindParam(':ts_upload_datetime', $buffer_datetime);
                        $newTool->bindParam(':ts_upload_by', $mrp_user_name_mst);
                        $newTool->bindParam(':ts_update_datetime', $buffer_datetime);
                        $newTool->bindParam(':ts_update_by', $mrp_user_name_mst);
                        $newTool->execute();
                    }else if($decisResult['decis'] == 1){
                        $updateTool = $db_con->prepare(
                            "UPDATE tbl_tooling_mst
                             SET ts_type = :ts_type,
                                 ts_sub_type = :ts_sub_type,
                                 ts_tooling_name = :ts_tooling_name,
                                 ts_location = :ts_location,
                                 ts_layout = :ts_layout,
                                 ts_price = :ts_price,
                                 ts_sup_uniq = :ts_sup_uniq,
                                 ts_remarks = :ts_remarks,
                                 ts_status = :ts_status,
                                 ts_update_datetime = :ts_update_datetime,
                                 ts_update_by = :ts_update_by
                             WHERE ts_fg_code = :ts_fg_code AND ts_fg_description = :ts_fg_description"
                        );
                        $updateTool->bindParam(':ts_type', $ts_type);
                        $updateTool->bindParam(':ts_sub_type', $ts_sub_type);
                        $updateTool->bindParam(':ts_tooling_name', $ts_tooling_name);
                        $updateTool->bindParam(':ts_location', $ts_location);
                        $updateTool->bindParam(':ts_layout', $ts_layout);
                        $updateTool->bindParam(':ts_price', str_replace(",","", $ts_price));
                        $updateTool->bindParam(':ts_sup_uniq', $ts_sup_uniq);
                        $updateTool->bindParam(':ts_remarks', $ts_remarks);
                        $updateTool->bindParam(':ts_status', $ts_status);
                        $updateTool->bindParam(':ts_update_datetime', $buffer_datetime);
                        $updateTool->bindParam(':ts_update_by', $mrp_user_name_mst);
                        $updateTool->bindParam(':ts_fg_code', $ts_fg_code);
                        $updateTool->bindParam(':ts_fg_description', $ts_fg_description);
                        $updateTool->execute();
                    }else{
                        echo json_encode(array('code'=>400, 'message'=>"จำนวน Toolings สำหรับ FG Code => $fg_code, Description => $fg_description มีมากกว่า 1 รายการ ไม่สามารถดำเนินการได้"));
                        $db_con = null;
                        return;
                    }

                    
                } catch(Exception $e) {
                    echo json_encode(array('code'=>400, 'message'=>"ไม่สามารถบันทึกข้อมูล Tooling สำหรับ FG Code => $ts_fg_code ได้ " . $e->getMessage()));
                    $db_con = null;
                    return;
                }
            }

            echo json_encode(array('code'=>200, 'message'=>'อัพโหลดข้อมูล New toolings สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถตรวจสอบข้อมูลไฟล์ได้  ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>