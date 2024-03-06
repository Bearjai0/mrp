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
    $amount_cost = 0;
    $margin = 0;
    $ivd;

    if($protocol == "MatchFile"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            // $data = $spreadsheet->getActiveSheet();
            $data = $spreadsheet->setActiveSheetIndex(1);
            $highestRow = intval($data->getHighestRow());

            foreach($column_name as $id=>$item){
                if(trim($data->getCell($item . "3")->getValue()) != $column_head[$id]){
                    echo json_encode(array('code'=>400, 'message'=>'หัว Column ' . $item . '3 ไม่ใช่ ' . $column_head[$id] . ' ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                    return;
                }   
            }

            echo count($data->getColumnIterator());

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
                    $ivfile = $db_con->prepare("SELECT inv_no, inv_cus_code, inv_status FROM tbl_inv_mst WHERE inv_no = :inv_no");
                    $ivfile->bindParam(':inv_no', $inv_no);
                    $ivfile->execute();
                    $ivResult = $ivfile->fetch(PDO::FETCH_ASSOC);

                    if($ivResult['inv_no'] == ''){
                        $vmfile = $vmi_con->query("SELECT inv_no, inv_cus_code, inv_status FROM tbl_inv_mst WHERE inv_no = '$inv_no'");
                        $vmResult = $vmfile->fetch(PDO::FETCH_ASSOC);
                        if($vmResult['inv_no'] == ''){
                            echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูลเอกสาร $inv_no ทั้งบนระบบ MRP & VMI ตรวจสอบข้อมูลและดำเนินการอีกครั้ง"));
                            $db_con = null;
                            $vmi_con = null;
                            return;
                        }

                        $ivd = $vmi_con->prepare(
                            "SELECT SUM(det_amount) AS des_amount,
                                    SUM(det_qty * bom_cost_per_pcs) AS des_cost,
                                    inv_total
                             FROM tbl_inv_detail_mst AS A
                             LEFT JOIN tbl_inv_mst AS Main ON A.det_inv_no = Main.inv_no
                             LEFT JOIN tbl_dn_usage_conf AS B ON A.det_dn_usage_id = B.dn_usage_id
                             LEFT JOIN tbl_bom_mst AS C ON B.dn_bom_uniq = C.bom_uniq
                             WHERE det_inv_no = '$inv_no'
                             GROUP BY det_inv_no, inv_total"
                        );
                    }else{
                        $ivd = $db_con->prepare(
                            "SELECT SUM(des_amount) AS des_amount,
                                    SUM(des_qty * cost_total_oh) AS des_cost,
                                    inv_total
                             FROM tbl_inv_detail_mst AS A
                             LEFT JOIN tbl_inv_mst AS Main ON A.des_inv_no = Main.inv_no
                             LEFT JOIN tbl_order_dtn_detail_mst AS B ON A.des_det_uniq = B.det_uniq
                             LEFT JOIN tbl_bom_mst AS C ON B.det_bom_uniq = C.bom_uniq
                             WHERE des_inv_no = '$inv_no'
                             GROUP BY des_inv_no, inv_total"
                        );
                    }

                    $ivd->execute();
                    $ivdResult = $ivd->fetch(PDO::FETCH_ASSOC);

                    if(number_format($ivdResult['inv_total'], 2) != number_format($total, 2)){
                        echo json_encode(array('code'=>400, 'message'=>"ราคาขายของเอกสาร $inv_no ระหว่างไฟล์อัพโหลดและบนระบบไม่ตรงกัน ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        return;
                    }

                    $revenue += $total;
                    $amount_cost += $ivdResult['des_cost'];
                }
            }
            
            echo "Revenue is $revenue\nAmount Cost 24% $amount_cost\nMargin " . ($revenue - $amount_cost) . "\nMargin % " . (($revenue - $amount_cost) / $revenue) ;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>