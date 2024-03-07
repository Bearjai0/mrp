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
    $inc_month = isset($_POST['inc_month']) ? date('Y-m', strtotime($_POST['inc_month'])) : '';

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

    if($protocol == "MatchFile"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            // $data = $spreadsheet->getActiveSheet();
            $data = $spreadsheet->setActiveSheetIndex(1);
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
                            return;
                        }

                        $vmts = $vmi_con->query("SELECT det_unit_type FROM tbl_inv_detail_mst WHERE det_inv_no = '$inv_no' GROUP BY det_unit_type");
                        $vmtsResult = $vmts->fetch(PDO::FETCH_ASSOC);

                        if($vmtsResult['det_unit_type'] == 'Set'){
                            $iv_cns_ql = "SELECT det_qty_set * bom_cost_per_pcs AS cost_total
                                          FROM tbl_inv_detail_mst AS A
                                          LEFT JOIN tbl_bom_mst AS C ON A.det_bom_uniq_of_set = C.bom_uniq
                                          WHERE det_inv_no = :inv_no AND det_status != 'Cancel'
                                          GROUP BY det_bom_uniq_of_set, det_qty_set, bom_cost_per_pcs, det_usage_running_code";
                        }else{
                            $iv_cns_ql = "SELECT SUM(det_qty * bom_cost_per_pcs) AS cost_total
                                          FROM tbl_inv_detail_mst AS A
                                          LEFT JOIN tbl_inv_mst AS Main ON A.det_inv_no = Main.inv_no
                                          LEFT JOIN tbl_dn_usage_conf AS B ON A.det_dn_usage_id = B.dn_usage_id
                                          LEFT JOIN tbl_bom_mst AS C ON B.dn_bom_uniq = C.bom_uniq
                                          WHERE det_inv_no = :inv_no AND det_status != 'Cancel'
                                          GROUP BY det_inv_no";
                        }
                        $cns = $vmi_con->prepare($iv_cns_ql);
                        $cns->bindParam(':inv_no', $inv_no);
                        $cns->execute();
                        while($cnsResult = $cns->fetch(PDO::FETCH_ASSOC)){
                            $cost_line_item += $cnsResult['cost_total'];
                        }
                    }else{ //MRP
                        if(number_format($ivResult['inv_total'], 2) != number_format($total, 2)){
                            echo json_encode(array('code'=>400, 'message'=>"ราคาขายของเอกสาร $inv_no ระหว่างไฟล์อัพโหลดและบนระบบไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                            $db_con = null;
                            return;
                        }

                        if($ivResult['inv_unit_type'] == 'set'){
                            $iv_cns_ql = "SELECT des_qty_set * cost_total_oh AS cost_total
                                          FROM tbl_inv_detail_mst AS A 
                                          LEFT JOIN tbl_bom_mst AS B ON A.des_bom_uniq_of_set = B.bom_uniq
                                          WHERE A.des_inv_no = :inv_no
                                          GROUP BY des_bom_uniq_of_set, des_qty_set, cost_total_oh";
                        }else{
                            $iv_cns_ql = "SELECT SUM(des_qty * cost_total_oh) AS cost_total
                                          FROM tbl_inv_detail_mst AS A
                                          LEFT JOIN tbl_order_dtn_detail_mst AS B ON A.des_det_uniq = B.det_uniq
                                          LEFT JOIN tbl_bom_mst AS C ON B.det_bom_uniq = C.bom_uniq
                                          WHERE des_inv_no = :inv_no
                                          GROUP BY des_inv_no";
                        }

                        $cns = $db_con->prepare($iv_cns_ql);
                        $cns->bindParam(':inv_no', $inv_no);
                        $cns->execute();
                        while($cnsResult = $cns->fetch(PDO::FETCH_ASSOC)){
                            $cost_line_item += $cnsResult['cost_total'];
                        }
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

                array_push($json, array('type'=> $type, 'user_code'=>$slistResult['user_code'], 'position'=>$slistResult['user_position'], 'revenue'=>$revenue, 'tiv_rate'=>number_format($condiResult['tiv_rate'], 2), 'incentive'=>$incentive));
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>