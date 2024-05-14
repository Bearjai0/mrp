<?php
    require_once("../../../session.php");
    require_once("../../../../library/PHPSpreadSheet/vendor/autoload.php");
    require_once('../../../../library/PHPMailer/class.phpmailer.php');
    require_once("../../../../library/PHPMailer/sender.php");
    require_once("../../../email/approve_incentive.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $inc_uniq = isset($_POST['inc_uniq']) ? $_POST['inc_uniq'] : '';
    $inc_period = isset($_POST['inc_period']) ? date('Y-m', strtotime($_POST['inc_period'])) : '';
    $user_code = isset($_POST['user_code']) ? $_POST['user_code'] : '';
    $user_mail = 'suphotp@glong-duang-jai.com';

    $column_head = ['Period', 'Invoice Number', 'Invoice Date', 'Invoice Type', 'Customer Name', 'Amount', 'VAT Amount', 'Total Amount', 'RV Number', 'RV Date', 'Project Name'];
    $column_name = ['A','B','C','D','E','F','G','H','I','J','K'];
    $revenue = 0;
    $rv_not_include = 0;
    $cost_not_include = 0;
    $rv_ex_no_cff = 0;
    $cost_ex_no_cff = 0;
    $rv_ex_cff = 0;
    $cost_ex_cff = 0;
    $cost_total = 0;
    $margin = 0;
    $cost_line_item = 0;
    $ivd;
    $cus_code = '';
    $not_include_cus_code = ['AAP','AAP1','TIT','TKM','LAT','LAT1','B2C','ABT','ABT1','MST'];
    $sale_ex_cff = 'GDJ00310';
    $sale_ex_no_cff = 'GDJ00313';

    $json = [];
    $details = [];
    $cus_cff_code = [];
    $cus_cff_detail = [];


    $not_include_revenue = isset($_POST['not_include_revenue']) ? $_POST['not_include_revenue'] : '';
    $not_include_margin_percent = isset($_POST['not_include_margin_percent']) ? $_POST['not_include_margin_percent'] : '';
    $no_cff_revenue = isset($_POST['no_cff_revenue']) ? $_POST['no_cff_revenue'] : '';
    $no_cff_margin_percent = isset($_POST['no_cff_margin_percent']) ? $_POST['no_cff_margin_percent'] : '';
    $final_cff_revenue = isset($_POST['final_cff_revenue']) ? $_POST['final_cff_revenue'] : '';
    $final_cff_margin_percent = isset($_POST['final_cff_margin_percent']) ? $_POST['final_cff_margin_percent'] : '';

    $det_details = isset($_POST['det_details']) ? $_POST['det_details'] : '';
    $all_revenue = isset($_POST['all_revenue']) ? $_POST['all_revenue'] : '';
    $all_amount_cost = isset($_POST['all_amount_cost']) ? $_POST['all_amount_cost'] : '';
    $all_margin_baht = isset($_POST['all_margin_baht']) ? $_POST['all_margin_baht'] : '';
    $all_margin_percent = isset($_POST['all_margin_percent']) ? $_POST['all_margin_percent'] : '';
    
    $cff_cus_code = isset($_POST['cff_cus_code']) ? $_POST['cff_cus_code'] : '';
    $cff_revenue = isset($_POST['cff_revenue']) ? $_POST['cff_revenue'] : '';
    $cff_ratio = isset($_POST['cff_ratio']) ? $_POST['cff_ratio'] : '';
    $cff_amount = isset($_POST['cff_amount']) ? $_POST['cff_amount'] : '';
    $cff_grand_total = isset($_POST['cff_grand_total']) ? $_POST['cff_grand_total'] : '';
    
    $fn_user_code = isset($_POST['fn_user_code']) ? $_POST['fn_user_code'] : '';
    $fn_user_name = isset($_POST['fn_user_name']) ? $_POST['fn_user_name'] : '';
    $fn_position = isset($_POST['fn_position']) ? $_POST['fn_position'] : '';
    $fn_revenue = isset($_POST['fn_revenue']) ? $_POST['fn_revenue'] : '';
    $fn_rate = isset($_POST['fn_rate']) ? $_POST['fn_rate'] : '';
    $fn_incentive = isset($_POST['fn_incentive']) ? $_POST['fn_incentive'] : '';
    $inc_total_incentive = isset($_POST['inc_total_incentive']) ? $_POST['inc_total_incentive'] : '';

    $inc_remarks = isset($_POST['inc_remarks']) ? $_POST['inc_remarks'] : '';


    if($protocol == "IncenLists"){
        try {
            $list = $db_con->query("SELECT ROW_NUMBER() OVER(ORDER BY inc_status, inc_uniq DESC) AS list, A.*, B.class_color, B.class_txt_color FROM tbl_sale_incentive AS A LEFT JOIN tbl_status_color AS B ON A.inc_status = B.hex_status ORDER BY inc_status, inc_uniq DESC");
            
            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "MatchFile"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            // $data = $spreadsheet->getActiveSheet();
            $data = $spreadsheet->setActiveSheetIndex(0);
            $highestRow = $data->getHighestRow();

            foreach($column_name as $id=>$item){
                if(trim($data->getCell($item . "3")->getValue()) != $column_head[$id]){
                    echo json_encode(array('code'=>400, 'message'=>'หัว Column ' . $item . '3 ไม่ใช่ ' . $column_head[$id] . ' ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    return;
                }   
            }

            $cfcql = $db_con->query("SELECT cus_code, cus_cff_ratio FROM tbl_customer_mst WHERE cus_cff_ratio > 0");
            while($cfcResult = $cfcql->fetch(PDO::FETCH_ASSOC)){
                array_push($cus_cff_code, $cfcResult['cus_code']);
                $cus_cff_detail[$cfcResult['cus_code']]['revenue'] = 0;
                $cus_cff_detail[$cfcResult['cus_code']]['cus_code'] = $cfcResult['cus_code'];
                $cus_cff_detail[$cfcResult['cus_code']]['cff_ratio'] = number_format($cfcResult['cus_cff_ratio'], 2);
            }



            for($i=4;$i<=$highestRow;$i++){
                $inv_period  = trim($data->getCell("A$i")->getValue());
                $inv_no      = trim($data->getCell("B$i")->getValue());
                $inv_date    = trim($data->getCell("C$i")->getValue());
                $inv_type    = trim($data->getCell("D$i")->getValue());
                $cus_name_en = trim($data->getCell("E$i")->getValue());
                $total       = floatval(str_replace(",","", trim($data->getCell("F$i")->getValue())));
                $vat         = floatval(str_replace(",","", trim($data->getCell("G$i")->getValue())));
                $grand_total = floatval(str_replace(",","", trim($data->getCell("H$i")->getValue())));
                $rv_no       = trim($data->getCell("I$i")->getValue());
                $rv_date     = trim($data->getCell("J$i")->getValue());
                $project     = trim($data->getCell("K$i")->getValue());
                
                if($inv_no != ''){
                    $ivfile = $db_con->prepare("SELECT * FROM tbl_inv_mst WHERE inv_no = :inv_no");
                    $ivfile->bindParam(':inv_no', $inv_no);
                    $ivfile->execute();
                    $ivResult = $ivfile->fetch(PDO::FETCH_ASSOC);
                    $iv_cns_ql = '';
                    $cus_code = $ivResult['inv_cus_code'];

                    if($ivResult['inv_no'] == ''){ //VMI
                        $vmfile = $vmi_con->query("SELECT * FROM tbl_inv_mst WHERE inv_no = '$inv_no'");
                        $vmResult = $vmfile->fetch(PDO::FETCH_ASSOC);
                        $cus_code = $vmResult['inv_cus_code'];
                        
                        if($vmResult['inv_no'] == ''){
                            echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูลเอกสาร $inv_no ทั้งบนระบบ MRP & VMI ตรวจสอบข้อมูลและดำเนินการอีกครั้ง"));
                            $db_con = null;
                            $vmi_con = null;
                            return;
                        }

                        if(number_format($vmResult['inv_total'], 2) != number_format($total, 2)){
                            echo json_encode(array('code'=>400, 'message'=>"ราคาขายของเอกสาร $inv_no ระหว่างไฟล์อัพโหลดและบนระบบไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                            $db_con = null;
                            $vmi_con = null;
                            return;
                        }

                        // $vmts = $vmi_con->query("SELECT det_unit_type FROM tbl_inv_detail_mst WHERE det_inv_no = '$inv_no' GROUP BY det_unit_type");
                        // $vmtsResult = $vmts->fetch(PDO::FETCH_ASSOC);

                        // if($vmtsResult['det_unit_type'] == 'Set'){
                        //     $iv_cns_ql = "SELECT det_qty_set * bom_cost_per_pcs AS cost_total
                        //                   FROM tbl_inv_detail_mst AS A
                        //                   LEFT JOIN tbl_bom_mst AS C ON A.det_bom_uniq_of_set = C.bom_uniq
                        //                   WHERE det_inv_no = :inv_no AND det_status != 'Cancel'
                        //                   GROUP BY det_bom_uniq_of_set, det_qty_set, bom_cost_per_pcs, det_usage_running_code";
                        // }else{
                        //     $iv_cns_ql = "SELECT SUM(det_qty * bom_cost_per_pcs) AS cost_total
                        //                   FROM tbl_inv_detail_mst AS A
                        //                   LEFT JOIN tbl_inv_mst AS Main ON A.det_inv_no = Main.inv_no
                        //                   LEFT JOIN tbl_dn_usage_conf AS B ON A.det_dn_usage_id = B.dn_usage_id
                        //                   LEFT JOIN tbl_bom_mst AS C ON B.dn_bom_uniq = C.bom_uniq
                        //                   WHERE det_inv_no = :inv_no AND det_status != 'Cancel'
                        //                   GROUP BY det_inv_no";
                        // }
                        // $cns = $vmi_con->prepare("SELECT inv_cost_total FROM tbl_inv_mst WHERE inv_no = :inv_no");
                        // $cns->bindParam(':inv_no', $inv_no);
                        // $cns->execute();
                        // $cnsResult = $cns->fetch(PDO::FETCH_ASSOC);
                        if($vmResult['inv_cost_total'] <= 0 || $vmResult['inv_cost_total'] == null){
                            echo json_encode(array('code'=>400, 'message'=>"รายการ $inv_no ไม่พบข้อมูล Cost ตรวจสอบข้อมูลและดำเนินการอีกครั้ง"));
                            $vmi_con = null;
                            $db_con = null;
                            return;
                        }

                        $cost_line_item = $vmResult['inv_cost_total'];
                        // while($cnsResult = $cns->fetch(PDO::FETCH_ASSOC)){
                        //     $cost_line_item += $cnsResult['cost_total'];
                        // }
                    }else{ //MRP
                        // if(number_format($ivResult['inv_total'], 2) != number_format($total, 2)){
                        //     echo json_encode(array('code'=>400, 'message'=>"ราคาขายของเอกสาร $inv_no ระหว่างไฟล์อัพโหลดและบนระบบไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        //     $db_con = null;
                        //     return;
                        // }

                        // if($ivResult['inv_unit_type'] == 'set'){
                        //     $iv_cns_ql = "SELECT des_qty_set * cost_total_oh AS cost_total
                        //                   FROM tbl_inv_detail_mst AS A 
                        //                   LEFT JOIN tbl_bom_mst AS B ON A.des_bom_uniq_of_set = B.bom_uniq
                        //                   WHERE A.des_inv_no = :inv_no
                        //                   GROUP BY des_bom_uniq_of_set, des_qty_set, cost_total_oh";
                        // }else{
                        //     $iv_cns_ql = "SELECT SUM(des_qty * cost_total_oh) AS cost_total
                        //                   FROM tbl_inv_detail_mst AS A
                        //                   LEFT JOIN tbl_order_dtn_detail_mst AS B ON A.des_det_uniq = B.det_uniq
                        //                   LEFT JOIN tbl_bom_mst AS C ON B.det_bom_uniq = C.bom_uniq
                        //                   WHERE des_inv_no = :inv_no
                        //                   GROUP BY des_inv_no";
                        // }

                        // $cns = $db_con->prepare($iv_cns_ql);
                        // $cns->bindParam(':inv_no', $inv_no);
                        // $cns->execute();
                        // while($cnsResult = $cns->fetch(PDO::FETCH_ASSOC)){
                        //     $cost_line_item += $cnsResult['cost_total'];
                        // }
                        if($ivResult['inv_cost_total'] <= 0 || $ivResult['inv_cost_total'] == null){
                            echo json_encode(array('code'=>400, 'message'=>"รายการ $inv_no ไม่พบข้อมูล Cost ตรวจสอบข้อมูลและดำเนินการอีกครั้ง"));
                            $vmi_con = null;
                            $db_con = null;
                            return;
                        }

                        $cost_line_item = $ivResult['inv_cost_total'];
                    }

                    $revenue += $total;
                    $cost_total += $cost_line_item;

                    if(!in_array($cus_code, $not_include_cus_code)){
                        $rv_not_include += $total;
                        $cost_not_include += $cost_line_item;
                    }

                    if(in_array($cus_code, $cus_cff_code)){
                        $cus_cff_detail[$cus_code]['revenue'] += $total;
                    }

                    //Check cff and carreer
                    $cusql = $db_con->query("SELECT * FROM tbl_customer_mst WHERE cus_code = '$cus_code'");
                    $cusResult = $cusql->fetch(PDO::FETCH_ASSOC);
                    if($cusResult['cus_sale_pic'] == $sale_ex_no_cff){
                        $rv_ex_no_cff += $total;
                        $cost_ex_no_cff += $cost_line_item;
                    }else if($cusResult['cus_sale_pic'] == $sale_ex_cff){
                        $rv_ex_cff += $total;
                        $cost_ex_cff += $cost_line_item;
                    }

                    array_push($details, array('inv_no'=>$inv_no, 'cost_total'=>$cost_line_item));
                    $cost_line_item = 0;
                }
            }

            $datas = array(
                'revenue' => $revenue,
                'cost_total' => $cost_total,
                'margin_a' => $revenue - $cost_total,
                'margin_b' => ($revenue - $cost_total) / $revenue
            );
            $datas_not_includes = array(
                'revenue' => $rv_not_include,
                'cost_total' => $cost_not_include,
                'margin_a' => $rv_not_include - $cost_not_include,
                'margin_b' => ($rv_not_include - $cost_not_include) / $rv_not_include
            );
            $datas_ex_no_cff = array(
                'revenue' => $rv_ex_no_cff,
                'cost_total' => $cost_ex_no_cff,
                'margin_a' => $rv_ex_no_cff - $cost_ex_no_cff,
                'margin_b' => ($rv_ex_no_cff - $cost_ex_no_cff) / $rv_ex_no_cff
            );
            $datas_ex_cff = array(
                'revenue' => $rv_ex_cff,
                'cost_total' => $cost_ex_cff,
                'margin_a' => $rv_ex_cff - $cost_ex_cff,
                'margin_b' => ($rv_ex_cff - $cost_ex_cff) / $rv_ex_cff
            );

            $salt = $db_con->query("SELECT user_code, user_name_en, user_position FROM tbl_user WHERE user_dep_id = 'D011' AND user_enable = 1");
            
            
            echo json_encode(array('code'=>200, 'details'=> $details, 'datas'=>$datas, 'datas_not_include'=>$datas_not_includes, 'datas_ex_no_cff'=>$datas_ex_no_cff, 'datas_ex_cff'=>$datas_ex_cff, 'cus_cff_details'=>$cus_cff_detail, 'sale_data'=>$salt->fetch(PDO::FETCH_ASSOC)));
            $db_con = null;
            $vmi_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            $vmi_con = null;
            return;
        }
    }else if($protocol == "CalculateFinalIncentive"){
        try {
            $rate = 0;
            $type = '';
            $slist = $db_con->query("SELECT user_code, user_name_en, user_position FROM tbl_user WHERE user_dep_id = 'D011' AND user_enable = 1");
            while($slistResult = $slist->fetch(PDO::FETCH_ASSOC)){
                if($slistResult['user_position'] == "Sale Executive" && $slistResult['user_code'] == $sale_ex_cff){
                    $rate = $final_cff_margin_percent;
                    $revenue = $final_cff_revenue;
                    $type = 'A';
                }else if($slistResult['user_position'] == "Sale Executive" && $slistResult['user_code'] == $sale_ex_no_cff){
                    $rate = $no_cff_margin_percent;
                    $revenue = $no_cff_revenue;
                    $type = 'B';
                }else{
                    $rate = $not_include_margin_percent;
                    $revenue = $not_include_revenue;
                    $type = 'C';
                }

                $condi = $db_con->prepare("SELECT tiv_rate FROM IncentiveRates WHERE tiv_position = :tiv_position AND $rate BETWEEN tiv_min AND tiv_max");
                $condi->bindParam('tiv_position', $slistResult['user_position']);
                $condi->execute();
                $condiResult = $condi->fetch(PDO::FETCH_ASSOC);
                $incentive = ($revenue * $condiResult['tiv_rate']) / 100;

                array_push($json, array('type'=> $type, 'user_code'=>$slistResult['user_code'], 'user_name'=>$slistResult['user_name_en'], 'position'=>$slistResult['user_position'], 'revenue'=>$revenue, 'tiv_rate'=>number_format($condiResult['tiv_rate'], 2), 'incentive'=>$incentive));
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "CreateIncentive"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_period = :inc_period");
            $list->bindParam(':inc_period', $inc_period);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['inc_period'] != ''){
                echo json_encode(array('code'=>400, 'message'=>'พบข้อมูล Period ดังกล่าวบนระบบอยู่แล้ว ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            $inc_rev = '00';
            $filename = $inc_period . '-' . $inc_rev . '.xlsx';
            $inc_year = explode("-", $inc_period)[0];
            $inc_month = explode("-", $inc_period)[1];
            
            $incn = $db_con->prepare("INSERT INTO tbl_sale_incentive(inc_period, inc_rev, inc_year, inc_month, inc_revenue, inc_amount_cost, inc_margin, inc_margin_perc, inc_total_cff, inc_total_incentive, inc_attach_file, inc_status, inc_create_datetime, inc_create_by, inc_remarks) VALUES(:inc_period, :inc_rev, :inc_year, :inc_month, :inc_revenue, :inc_amount_cost, :inc_margin, :inc_margin_perc, :inc_total_cff, :inc_total_incentive, :inc_attach_file, 'In-Review', :inc_create_datetime, :inc_create_by, :inc_remarks)");
            $incn->bindParam(':inc_period', $inc_period);
            $incn->bindParam(':inc_rev', $inc_rev);
            $incn->bindParam(':inc_year', $inc_year);
            $incn->bindParam(':inc_month', $inc_month);
            $incn->bindParam(':inc_revenue', str_replace(",","", $all_revenue[0]));
            $incn->bindParam(':inc_amount_cost', str_replace(",","", $all_amount_cost[0]));
            $incn->bindParam(':inc_margin', str_replace(",","", $all_margin_baht[0]));
            $incn->bindParam(':inc_margin_perc', str_replace(",","", $all_margin_percent[0]));
            $incn->bindParam(':inc_total_cff', str_replace(",","", $cff_grand_total));
            $incn->bindParam(':inc_total_incentive', str_replace(",","", $inc_total_incentive));
            $incn->bindParam(':inc_attach_file', $filename);
            $incn->bindParam(':inc_create_datetime', $buffer_datetime);
            $incn->bindParam(':inc_create_by', $mrp_user_name_mst);
            $incn->bindParam(':inc_remarks', $inc_remarks);
            $incn->execute();

            $inc_uniq = $db_con->lastInsertId();

            foreach($det_details as $id=>$item){
                $detql = $db_con->prepare("INSERT INTO tbl_sale_incentive_detail(det_inc_uniq, det_inc_rev, det_details, det_revenue, det_amount_cost, det_margin, det_margin_perc) VALUES(:det_inc_uniq, :det_inc_rev, :det_details, :det_revenue, :det_amount_cost, :det_margin, :det_margin_perc)");
                $detql->bindParam(':det_inc_uniq', $inc_uniq);
                $detql->bindParam(':det_inc_rev', $inc_rev);
                $detql->bindParam(':det_details', $item);
                $detql->bindParam(':det_revenue', str_replace(",","", $all_revenue[$id]));
                $detql->bindParam(':det_amount_cost', str_replace(",","", $all_amount_cost[$id]));
                $detql->bindParam(':det_margin', str_replace(",","", $all_margin_baht[$id]));
                $detql->bindParam(':det_margin_perc', str_replace(",","", $all_margin_percent[$id]));
                $detql->execute();
            }

            foreach($cff_cus_code as $id=>$item){
                $cffql = $db_con->prepare("INSERT INTO tbl_sale_incentive_cff(cff_inc_uniq, cff_inc_rev, cff_cus_code, cff_revenue, cff_ratio, cff_total) VALUES(:cff_inc_uniq, :cff_inc_rev, :cff_cus_code, :cff_revenue, :cff_ratio, :cff_total)");
                $cffql->bindParam(':cff_inc_uniq', $inc_uniq);
                $cffql->bindParam(':cff_inc_rev', $inc_rev);
                $cffql->bindParam(':cff_cus_code', $item);
                $cffql->bindParam(':cff_revenue', str_replace(",","", $cff_revenue[$id]));
                $cffql->bindParam(':cff_ratio', str_replace(",","", $cff_ratio[$id]));
                $cffql->bindParam(':cff_total', str_replace(",","", $cff_amount[$id]));
                $cffql->execute();
            }

            foreach($fn_user_code as $id=>$item){
                $incenlist = $db_con->prepare("INSERT INTO tbl_sale_incentive_list(list_inc_uniq, list_inc_rev, list_user_code, list_user_name, list_position, list_revenue, list_rate, list_total) VALUES(:list_inc_uniq, :list_inc_rev, :list_user_code, :list_user_name, :list_position, :list_revenue, :list_rate, :list_total)");
                $incenlist->bindParam(':list_inc_uniq', $inc_uniq);
                $incenlist->bindParam(':list_inc_rev', $inc_rev);
                $incenlist->bindParam(':list_user_code', $item);
                $incenlist->bindParam(':list_user_name', $fn_user_name[$id]);
                $incenlist->bindParam(':list_position', $fn_position[$id]);
                $incenlist->bindParam(':list_revenue', str_replace(",","", $fn_revenue[$id]));
                $incenlist->bindParam(':list_rate', str_replace(",","", $fn_rate[$id]));
                $incenlist->bindParam(':list_total', str_replace(",","", $fn_incentive[$id]));
                $incenlist->execute();
            }

            //todo >>>>>>>>>> Fix uniq_id to sale table 
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            // $data = $spreadsheet->getActiveSheet();
            $data = $spreadsheet->setActiveSheetIndex(0);
            $highestRow = $data->getHighestRow();

            foreach($column_name as $id=>$item){
                if(trim($data->getCell($item . "3")->getValue()) != $column_head[$id]){
                    echo json_encode(array('code'=>400, 'message'=>'หัว Column ' . $item . '3 ไม่ใช่ ' . $column_head[$id] . ' ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    return;
                }   
            }
            for($i=4;$i<=$highestRow;$i++){
                $inv_no = trim($data->getCell("B$i")->getValue());

                if($inv_no != ''){
                    $doc = $db_con->prepare("SELECT inv_no FROM tbl_inv_mst WHERE inv_no = :inv_no");
                    $doc->bindParam(':inv_no', $inv_no);
                    $doc->execute();
                    $docResult = $doc->fetch(PDO::FETCH_ASSOC);
                    if($docResult['inv_no'] == ''){
                        $vmfile = $vmi_con->query("SELECT inv_no FROM tbl_inv_mst WHERE inv_no = '$inv_no'");
                        $vmResult = $vmfile->fetch(PDO::FETCH_ASSOC);
                        if($vmResult['inv_no'] == ''){
                            echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล $inv_no ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                            $db_con = null;
                            $vmi_con = null;
                            return;
                        }

                        $set = $vmi_con->prepare("UPDATE tbl_inv_mst SET inv_inc_uniq = :inc_uniq WHERE inv_no = :inv_no");
                        $set->bindParam(':inc_uniq', $inc_uniq);
                        $set->bindParam(':inv_no', $inv_no);
                        $set->execute();
                    }else{
                        $set = $db_con->prepare("UPDATE tbl_inv_mst SET inv_inc_uniq = :inc_uniq WHERE inv_no = :inv_no");
                        $set->bindParam(':inc_uniq', $inc_uniq);
                        $set->bindParam(':inv_no', $inv_no);
                        $set->execute();
                    }
                }
            }
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            $aplist = $db_con->prepare("INSERT INTO tbl_sale_incentive_approve(app_inc_uniq, app_user_code, app_user_name, app_position, app_status) SELECT $inc_uniq, user_code, user_name_en, user_position, 'Pending' FROM tbl_user WHERE user_code IN('GDJ00312','GDJ00258','TTV03124','TTV00830','ABT00058','TTV02995') ORDER BY CASE WHEN user_code = 'GDJ00312' THEN 1 WHEN user_code = 'GDJ00258' THEN 2 WHEN user_code = 'TTV03124' THEN 3 WHEN user_code = 'TTV00830' THEN 4 WHEN user_code = 'ABT00058' THEN 5 WHEN user_code = 'TTV02995' THEN 6 END");
            $aplist->execute();

            $tck = $db_con->prepare("INSERT INTO tbl_sale_incentive_tracking(tck_inc_uniq, tck_user_code, tck_details, tck_status, tck_datetime) VALUES($inc_uniq, '$mrp_user_code_mst', '$inc_remarks', 'Publish', '$buffer_datetime')");
            $tck->execute();


            $upfile = isset($_FILES['upfile']) ? $_FILES['upfile']['tmp_name'] : '';
            if($upfile){
                if(!move_uploaded_file($upfile, '../../../../library/attachfile/mrp-incentive/' . $filename)){
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพโหลดไฟล์ได้'));
                    $db_con = null;
                    return;
                }
            }

            $upnow = $db_con->prepare("UPDATE tbl_sale_incentive_approve SET app_status = 'Approved', app_datetime = '$buffer_datetime', app_signature = '$mrp_user_signature_mst' WHERE app_inc_uniq = '$inc_uniq' AND app_user_code = '$mrp_user_code_mst'");
            $upnow->execute();

            $nowin = $db_con->prepare("UPDATE tbl_sale_incentive SET inc_now_in = (SELECT TOP(1) app_user_code FROM tbl_sale_incentive_approve WHERE app_inc_uniq = $inc_uniq AND app_status = 'Pending') WHERE inc_uniq = $inc_uniq");
            $nowin->execute();

            $nowlist = $db_con->prepare("SELECT user_email FROM tbl_sale_incentive AS A LEFT JOIN tbl_user AS B ON A.inc_now_in = B.user_code WHERE inc_uniq = :inc_uniq");
            $nowlist->bindParam(':inc_uniq', $inc_uniq);
            $nowlist->execute();
            $nowResult = $nowlist->fetch(PDO::FETCH_ASSOC);

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
            $mail_title = " (Sale Incentive)";
            $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

            try {
                $body = HTMLForm($db_con, $CFG, $inc_uniq);
                $mail->Subject = $t_subject;
                $mail->MsgHTML($body);
                $mail->AddAddress($nowResult['user_email']);
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


            echo json_encode(array('code'=>200, 'message'=>"ดำเนินการสร้าง Incentive สำหรับ Period $inc_period สำเร็จ", 'route'=>"$CFG->print_sale_incentive?inc_uniq=$inc_uniq"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateIncentive"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq");
            $list->bindParam(':inc_uniq', $inc_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);


            // migrate old version to revision table
            $migrate = $db_con->prepare(
                "INSERT INTO tbl_sale_incentive_revision(rev_inc_uniq, rev_period, rev_inc_rev, rev_year, rev_month, rev_revenue, rev_amount_cost, rev_margin, rev_margin_perc, rev_total_cff, rev_total_incentive, rev_attach_file, rev_status, rev_datetime, rev_by, rev_remarks)
                 SELECT inc_uniq, inc_period, inc_rev, inc_year, inc_month, inc_revenue, inc_amount_cost, inc_margin, inc_margin_perc, inc_total_cff, inc_total_incentive, inc_attach_file, inc_status, inc_create_datetime, inc_create_by, inc_remarks FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq"
            );
            $migrate->bindParam(':inc_uniq', $inc_uniq);
            $migrate->execute(); 




            $inc_rev = PadNumber(intval($listResult['inc_rev']) + 1, 2);
            $filename = $listResult['inc_period'] . '-' . $inc_rev . '.xlsx';
            
            $incn = $db_con->prepare(
                "UPDATE tbl_sale_incentive
                 SET inc_rev = :inc_rev,
                     inc_revenue = :inc_revenue,
                     inc_amount_cost = :inc_amount_cost,
                     inc_margin = :inc_margin,
                     inc_margin_perc = :inc_margin_perc,
                     inc_total_cff = :inc_total_cff,
                     inc_total_incentive = :inc_total_incentive,
                     inc_attach_file = :inc_attach_file,
                     inc_remarks = :inc_remarks
                 WHERE inc_uniq = :inc_uniq"
            );
            
            $incn->bindParam(':inc_rev', $inc_rev);
            $incn->bindParam(':inc_revenue', str_replace(",","", $all_revenue[0]));
            $incn->bindParam(':inc_amount_cost', str_replace(",","", $all_amount_cost[0]));
            $incn->bindParam(':inc_margin', str_replace(",","", $all_margin_baht[0]));
            $incn->bindParam(':inc_margin_perc', str_replace(",","", $all_margin_percent[0]));
            $incn->bindParam(':inc_total_cff', str_replace(",","", $cff_grand_total));
            $incn->bindParam(':inc_total_incentive', str_replace(",","", $inc_total_incentive));
            $incn->bindParam(':inc_attach_file', $filename);
            $incn->bindParam(':inc_remarks', $inc_remarks);
            $incn->bindParam(':inc_uniq', $inc_uniq);
            $incn->execute();


            foreach($det_details as $id=>$item){
                $detql = $db_con->prepare("INSERT INTO tbl_sale_incentive_detail(det_inc_uniq, det_inc_rev, det_details, det_revenue, det_amount_cost, det_margin, det_margin_perc) VALUES(:det_inc_uniq, :det_inc_rev, :det_details, :det_revenue, :det_amount_cost, :det_margin, :det_margin_perc)");
                $detql->bindParam(':det_inc_uniq', $inc_uniq);
                $detql->bindParam(':det_inc_rev', $inc_rev);
                $detql->bindParam(':det_details', $item);
                $detql->bindParam(':det_revenue', str_replace(",","", $all_revenue[$id]));
                $detql->bindParam(':det_amount_cost', str_replace(",","", $all_amount_cost[$id]));
                $detql->bindParam(':det_margin', str_replace(",","", $all_margin_baht[$id]));
                $detql->bindParam(':det_margin_perc', str_replace(",","", $all_margin_percent[$id]));
                $detql->execute();
            }

            foreach($cff_cus_code as $id=>$item){
                $cffql = $db_con->prepare("INSERT INTO tbl_sale_incentive_cff(cff_inc_uniq, cff_inc_rev, cff_cus_code, cff_revenue, cff_ratio, cff_total) VALUES(:cff_inc_uniq, :cff_inc_rev, :cff_cus_code, :cff_revenue, :cff_ratio, :cff_total)");
                $cffql->bindParam(':cff_inc_uniq', $inc_uniq);
                $cffql->bindParam(':cff_inc_rev', $inc_rev);
                $cffql->bindParam(':cff_cus_code', $item);
                $cffql->bindParam(':cff_revenue', str_replace(",","", $cff_revenue[$id]));
                $cffql->bindParam(':cff_ratio', str_replace(",","", $cff_ratio[$id]));
                $cffql->bindParam(':cff_total', str_replace(",","", $cff_amount[$id]));
                $cffql->execute();
            }

            foreach($fn_user_code as $id=>$item){
                $incenlist = $db_con->prepare("INSERT INTO tbl_sale_incentive_list(list_inc_uniq, list_inc_rev, list_user_code, list_user_name, list_position, list_revenue, list_rate, list_total) VALUES(:list_inc_uniq, :list_inc_rev, :list_user_code, :list_user_name, :list_position, :list_revenue, :list_rate, :list_total)");
                $incenlist->bindParam(':list_inc_uniq', $inc_uniq);
                $incenlist->bindParam(':list_inc_rev', $inc_rev);
                $incenlist->bindParam(':list_user_code', $item);
                $incenlist->bindParam(':list_user_name', $fn_user_name[$id]);
                $incenlist->bindParam(':list_position', $fn_position[$id]);
                $incenlist->bindParam(':list_revenue', str_replace(",","", $fn_revenue[$id]));
                $incenlist->bindParam(':list_rate', str_replace(",","", $fn_rate[$id]));
                $incenlist->bindParam(':list_total', str_replace(",","", $fn_incentive[$id]));
                $incenlist->execute();
            }

            $aplist = $db_con->prepare("UPDATE tbl_sale_incentive_approve SET app_status = 'Pending', app_datetime = NULL, app_signature = NULL WHERE app_inc_uniq = $inc_uniq");
            $aplist->execute();

            $upnow = $db_con->prepare("UPDATE tbl_sale_incentive_approve SET app_status = 'Approved', app_datetime = '$buffer_datetime', app_signature = '$mrp_user_signature_mst' WHERE app_inc_uniq = '$inc_uniq' AND app_user_code = '$mrp_user_code_mst'");
            $upnow->execute();

            $tck = $db_con->prepare("INSERT INTO tbl_sale_incentive_tracking(tck_inc_uniq, tck_user_code, tck_details, tck_status, tck_datetime) VALUES($inc_uniq, '$mrp_user_code_mst', '$inc_remarks', 'Revise', '$buffer_datetime')");
            $tck->execute();


            $upfile = isset($_FILES['upfile']) ? $_FILES['upfile']['tmp_name'] : '';
            if($upfile){
                if(!move_uploaded_file($upfile, '../../../../library/attachfile/mrp-incentive/' . $filename)){
                    echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถอัพโหลดไฟล์ได้'));
                    $db_con = null;
                    return;
                }
            }

            $nowin = $db_con->prepare("UPDATE tbl_sale_incentive SET inc_now_in = (SELECT TOP(1) app_user_code FROM tbl_sale_incentive_approve WHERE app_inc_uniq = $inc_uniq AND app_status = 'Pending') WHERE inc_uniq = $inc_uniq");
            $nowin->execute();

            $nowlist = $db_con->prepare("SELECT user_email FROM tbl_sale_incentive AS A LEFT JOIN tbl_user AS B ON A.inc_now_in = B.user_code WHERE inc_uniq = :inc_uniq");
            $nowlist->bindParam(':inc_uniq', $inc_uniq);
            $nowlist->execute();
            $nowResult = $nowlist->fetch(PDO::FETCH_ASSOC);

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
            $mail_title = " (Sale Incentive)";
            $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

            try {
                $body = HTMLForm($db_con, $CFG, $inc_uniq);
                $mail->Subject = $t_subject;
                $mail->MsgHTML($body);
                $mail->AddAddress($nowResult['user_email']);
                $mail->AddCC('wiwatt@all2gether.net');
                $mail->Send();
            }catch (phpmailerException $e){
                echo $e->errorMessage();
                echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                sqlsrv_rollback($db_con);
                return;
            } catch (Exception $e) {
                echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                sqlsrv_rollback($db_con);
                return;
            }


            echo json_encode(array('code'=>200, 'message'=>"ดำเนินการสร้าง Incentive สำหรับ Period $inc_period สำเร็จ", 'route'=>"$CFG->print_sale_incentive?inc_uniq=$inc_uniq"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "ApproveIncentive"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq");
            $list->bindParam(':inc_uniq', $inc_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['inc_period'] == ''){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูล Request ID ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }
            
            $usd = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user_code");
            $usd->bindParam(':user_code', $user_code);
            $usd->execute();
            $usdResult = $usd->fetch(PDO::FETCH_ASSOC);

            if($usdResult['user_code'] == ''){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูลผู้ใช้งาน ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            $app = $db_con->prepare("UPDATE tbl_sale_incentive_approve SET app_datetime = :app_datetime, app_status = 'Approved', app_signature = :app_signature WHERE app_inc_uniq = :inc_uniq AND app_user_code = :user_code");
            $app->bindParam(':app_datetime', $buffer_datetime);
            $app->bindParam(':app_signature', $usdResult['user_signature']);
            $app->bindParam(':inc_uniq', $inc_uniq);
            $app->bindParam(':user_code', $usdResult['user_code']);
            $app->execute();

            $tck = $db_con->prepare("INSERT INTO tbl_sale_incentive_tracking(tck_inc_uniq, tck_user_code, tck_details, tck_status, tck_datetime) VALUES(:inc_uniq, :user_code, :remarks, 'Approved', :buffer_datetime)");
            $tck->bindParam(':inc_uniq', $inc_uniq);
            $tck->bindParam(':user_code', $usdResult['user_code']);
            $tck->bindParam(':remarks', $inc_remarks);
            $tck->bindParam(':buffer_datetime', $buffer_datetime);
            $tck->execute();

            $nextp = $db_con->prepare("SELECT TOP(1) * FROM tbl_sale_incentive_approve AS A LEFT JOIN tbl_user AS B ON A.app_user_code = B.user_code WHERE app_inc_uniq = :inc_uniq AND app_status = 'Pending' ORDER BY app_uniq");
            $nextp->bindParam(':inc_uniq', $inc_uniq);
            $nextp->execute();
            $nextResult = $nextp->fetch(PDO::FETCH_ASSOC);

            if($nextResult['app_user_code'] == ''){
                $up = $db_con->prepare("UPDATE tbl_sale_incentive SET inc_now_in = '', inc_status = 'Approved' WHERE inc_uniq = :inc_uniq");
                $up->bindParam(':inc_uniq', $inc_uniq);
                $up->execute();

                try {
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
                    $mail_title = " (Sale Incentive)";
                    $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;
                    $body = HTMLFormComplete($db_con, $CFG, $inc_uniq);
                    $mail->Subject = $t_subject;
                    $mail->MsgHTML($body);
                    // $mail->AddAddress('bearjai0@gmail.com');
                    $mail->AddAddress('wanida.t@ttv-supplychain.com');
                    $mail->AddCC('ameenap@glong-duang-jai.com');
                    $mail->AddCC('bearjai0@gmail.com');
                    $mail->Send();
                }catch (phpmailerException $e){
                    echo $e->errorMessage();
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                    sqlsrv_rollback($db_con);
                    return;
                } catch (Exception $e) {
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                    sqlsrv_rollback($db_con);
                    return;
                }
            }else{
                $up = $db_con->prepare("UPDATE tbl_sale_incentive SET inc_now_in = :now_in WHERE inc_uniq = :inc_uniq");
                $up->bindParam(':inc_uniq', $inc_uniq);
                $up->bindParam(':now_in', $nextResult['app_user_code']);
                $up->execute();

                try {
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
                    $mail_title = " (Sale Incentive)";
                    $t_subject  = "Information From " . $CFG->AppNameTitle . $mail_title;

                    $body = HTMLForm($db_con, $CFG, $inc_uniq);
                    $mail->Subject = $t_subject;
                    $mail->MsgHTML($body);
                    $mail->AddAddress($nextResult['user_email']);
                    $mail->AddCC('ameenap@glong-duang-jai.com');
                    $mail->Send();
                }catch (phpmailerException $e){
                    echo $e->errorMessage();
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้.'));
                    sqlsrv_rollback($db_con);
                    return;
                } catch (Exception $e) {
                    echo json_encode(array('code'=>'400', 'message'=>'บันทึกข้อมูลไม่สำเร็จ ไม่สามารถส่งอีเมล์เพื่อขออนุมัติได้'));
                    sqlsrv_rollback($db_con);
                    return;
                }
            }

            echo json_encode(array('code'=>200, 'message'=>"อนุมัติสำเร็จ", 'route'=>"$CFG->print_sale_incentive?inc_uniq=$inc_uniq"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "RejectIncentive"){
        try {
            $list = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq");
            $list->bindParam(':inc_uniq', $inc_uniq);
            $list->execute();
            $listResult = $list->fetch(PDO::FETCH_ASSOC);

            if($listResult['inc_period'] == ''){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูล Request ID ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }
            
            $usd = $db_con->prepare("SELECT * FROM tbl_user WHERE user_code = :user_code");
            $usd->bindParam(':user_code', $user_code);
            $usd->execute();
            $usdResult = $usd->fetch(PDO::FETCH_ASSOC);

            if($usdResult['user_code'] == ''){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบข้อมูลผู้ใช้งาน ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            if($user_code != $listResult['inc_now_in']){
                echo json_encode(array('code'=>400, 'message'=>'ไม่พบสิทธิ์การใช้งาน ไม่สามารถดำเนินการได้'));
                $db_con = null;
                return;
            }

            $up = $db_con->prepare("UPDATE tbl_sale_incentive SET inc_now_in = 'Rejected' WHERE inc_uniq = :inc_uniq");
            $up->bindParam(':inc_uniq', $inc_uniq);
            $up->execute();

            $tck = $db_con->prepare("INSERT INTO tbl_sale_incentive_tracking(tck_inc_uniq, tck_user_code, tck_details, tck_status, tck_datetime) VALUES(:inc_uniq, :user_code, :remarks, 'Rejected', :buffer_datetime)");
            $tck->bindParam(':inc_uniq', $inc_uniq);
            $tck->bindParam(':user_code', $user_code);
            $tck->bindParam(':remarks', $inc_remarks);
            $tck->bindParam(':buffer_datetime', $buffer_datetime);
            $tck->execute();

            echo json_encode(array('code'=>200, 'message'=>"Reject สำเร็จ"));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>