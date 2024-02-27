<?php
    /*call the FPDF library*/
    require_once("../../application.php");
    require_once('../../../library/fpdf184/code128.php');

    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
    $dtn_no = isset($_REQUEST['dtn_no']) ? $_REQUEST['dtn_no'] : '';

    $sql = $db_con->query("SELECT A.*, B.cus_name_en, B.cus_address
    FROM tbl_order_dtn_mst AS A
    LEFT JOIN tbl_customer_mst AS B ON A.dtn_cus_code = B.cus_code
    WHERE A.dtn_no = '$dtn_no'");
    $firstResult = $sql->fetch(PDO::FETCH_ASSOC);
    if($firstResult['dtn_no'] == ''){
        echo 'ไม่พบข้อมูล';
        return;
    }

    $GLOBALS['dtn_no'] = $firstResult['dtn_no'];
    $GLOBALS['dtn_generate_datetime'] = date('d/m/Y', strtotime($firstResult['dtn_generate_datetime']));
    $GLOBALS['dtn_generate_by'] = $firstResult['dtn_generate_by'];
    $GLOBALS['dtn_cus_code'] = $firstResult['cus_name_en'];
    $GLOBALS['dtn_cus_address'] = $firstResult['cus_address'] ?? '-';
    $GLOBALS['dtn_project'] = $firstResult['dtn_project'];
    $GLOBALS['dtn_total_qty'] = $firstResult['dtn_total_qty'];

    $GLOBALS['dtn_truck_type'] = $firstResult['dtn_truck_type'];
    $GLOBALS['dtn_driver'] = $firstResult['dtn_driver'];

    $GLOBALS['print_by'] = $session_user_fullname_mst;
    $GLOBALS['print_on'] = date('d/m/Y', strtotime($buffer_datetime));


    /*A4 width : 219mm*/
    /*Cell(width , height , text , border , end line , [align] )*/
    class PDF extends PDF_Code128 {
        public function Header(){
            $this->SetFillColor(0, 0, 0);
            $this->SetTextColor(0,0,0);
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(190, 10, 'DELIVERY TRANSFER NOTE', 0, 1, 'C');
            $this->Cell(190, 10, '', 0, 1);
            // $this->Image('https://kb.albatrosslogistic.com/mrp/images/company_logo/fac_main.png', 10, 10, 23, 0, 'PNG');
            $this->Image('https://kb.albatrosslogistic.com/library/images/company_logo/fac_main.png', 10, 10, 35, 23, 'PNG');
            $this->Code128(65, 20, $GLOBALS['dtn_no'], 80, 10);
            $this->Image("https://kb.albatrosslogistic.com/library/fpdf184/genqr.php?code=" . $GLOBALS['dtn_no'], 182.35, 12, 20, 20, "png");
            
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(190, 3, '', 0, 1);
            $this->Cell(190, 2, '', 'LTR', 1);
            $this->Cell(25, 4, 'Customer: ', 'L', 0);
            $this->SetFont('Arial', '', 10);
            $this->CellFitScale(105, 4, $GLOBALS['dtn_cus_code'], 0, 0);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(20, 4, 'Project: ', 0, 0, 'C');
            $this->SetFont('Arial', '', 10);
            $this->Cell(40, 4, $GLOBALS['dtn_project'], 'R', 1);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(25, 6, 'Address: ', 'LB', 0);
            if(utf8_strlen($GLOBALS['dtn_cus_address'])){
                $this->SetFont('THSarabunNew', 'B', 15);
            }else{
                $this->SetFont('Arial', '', 10);
            }
            $this->CellFitScale(165, 6, LenTH($GLOBALS['dtn_cus_address']), 'RB', 1);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(190, 2, '', 'LTR', 1);
            $this->Cell(42, 5, 'Delivery Transfer Note:', 'L', 0);
            $this->SetFont('Arial', '', 10);
            $this->Cell(53, 5, $GLOBALS['dtn_no'], 0, 0);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(22, 5, 'Issue Date:', 'L', 0);
            $this->SetFont('Arial', '', 10);
            $this->Cell(73, 5, $GLOBALS['dtn_generate_datetime'], 'R', 1);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(25, 5, 'Driver Name:', 'L', 0);
            if(utf8_strlen($GLOBALS['dtn_driver'])){
                $this->SetFont('THSarabunNew', 'B', 15);
            }else{
                $this->SetFont('Arial', '', 10);
            }
            $this->Cell(70, 5, LenTH($GLOBALS['dtn_driver']), 0, 0);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(22, 5, 'Truck Type:', 'L', 0);
            $this->SetFont('Arial', '', 10);
            $this->Cell(73, 5, $GLOBALS['dtn_truck_type'], 'R', 1);
            $this->Cell(190, 1, '', 'LBR', 1);

            $this->Cell(190, 3, '', 0, 1);
            $this->SetFillColor(192, 192, 192);

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(10, 10, 'NO.', 'LTB', 0, 'C', TRUE);
            $this->Cell(30, 10, 'Refill Order No#', 1, 0, 'C', TRUE);
            $this->Cell(35, 5, 'FG Codeset', 1, 0, 'C', TRUE);
            $this->Cell(40, 5, 'Part Customer', 1, 0, 'C', TRUE);
            $this->Cell(15, 10, 'Packing', 1, 0, 'C', TRUE);
            $this->Cell(30, 5, 'Total(Set.)', 1, 0, 'C', TRUE);
            $this->Cell(30, 10, 'Total(Pcs.)', 1, 0, 'C', TRUE);
            $this->Cell(10, 5, '', 0, 1);

            $this->Cell(10, 0, '', 0, 0, 'C');
            $this->Cell(30, 0, '', 0, 0, 'C');
            $this->Cell(35, 5, 'FG Code', 1, 0, 'C', TRUE);
            $this->Cell(40, 5, 'Descriptions', 1, 0, 'C', TRUE);
            $this->Cell(15, 0, '', 0, 0, 'C');
            $this->Cell(30, 5, 'Usage', 1, 0, 'C', TRUE);
            $this->Cell(30, 0, '', 0, 0, 'C');
            $this->Cell(10, 5, '', 0, 1);
        }

        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            $this->SetFont('Arial','I',6);
            $this->Cell(0,10,'Print Picking Sheet Documents', 0, 1, 'R');
        }
    }

    $pdf = new PDF('P','mm','A4');
    // $pdf->SetMargins(13, 12, 15);
    $pdf->SetFont('Arial', 'B', 13);

    $pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $pdf->AddFont('angsa','','angsa.php');

    $pdf->AddPage();
    $pdf->AliasNbPages();
    
    //todo >>>>>>>>> Structure View
    //todo >>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetFillColor(255, 255, 255);

    // $sql = "SELECT ROW_NUMBER() OVER(ORDER BY list_uniq ASC) AS Rowx, * FROM tbl_order_picking_list_mst WHERE list_dtn_no = '$dtn_no' ORDER BY list_uniq";
    $sql = $db_con->query("SELECT * FROM tbl_order_dtn_detail_mst WHERE det_dtn_no = '$dtn_no' ORDER BY det_uniq");
    $i = 0;
    while($result = $sql->fetch(PDO::FETCH_ASSOC)){
        if($pdf->getY() > 260){
            $pdf->AddPage();
            $pdf->AliasNbPages();
        }

        $ffmc_usage = $result['det_ffmc_usage'] && $result['det_ffmc_usage'] != 0 ? $result['det_ffmc_usage'] : 1;
        if($dtn_no == 'DTN2304100021'){
            $ffmc_usage = 1;
        }

        $i++;
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10, 10, $i . '.', 'LTB', 0, 'C', TRUE);
        $pdf->CellFitScale(30, 10, $result['det_order_ref'], 1, 0, 'C', TRUE);
        // $pdf->CellFitScale(30, 10, $pdf->getY(), 1, 0, 'C', TRUE);
        $pdf->CellFitScale(35, 5, $result['det_fg_codeset'], 1, 0, 'C', TRUE);
        $pdf->CellFitScale(40, 5, $result['det_part_customer'], 1, 0, 'C', TRUE);
        $pdf->CellFitScale(15, 10, $result['det_packing_usage'], 1, 0, 'C', TRUE);
        $pdf->CellFitScale(30, 5, str_comma_unit(ceil($result['det_shipping_qty'] / $ffmc_usage)), 1, 0, 'C', TRUE);
        $pdf->CellFitScale(30, 10, str_comma_unit($result['det_shipping_qty']), 1, 0, 'C', TRUE);
        $pdf->Cell(10, 5, '', 0, 1);

        $pdf->Cell(10, 0, '', 0, 0, 'C');
        $pdf->Cell(30, 0, '', 0, 0, 'C');
        $pdf->CellFitScale(35, 5, $result['det_fg_code'], 1, 0, 'C', TRUE);
        if(utf8_strlen($result['det_fg_description'])){
            $pdf->SetFont('THSarabunNew', 'B', 13);
        }else{
            $pdf->SetFont('Arial', '', 8);
        }
        $pdf->CellFitScale(40, 5, LenTH($result['det_fg_description']), 1, 0, 'C', TRUE);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 0, '', 0, 0, 'C');
        $pdf->CellFitScale(30, 5, $ffmc_usage, 1, 0, 'C', TRUE);
        $pdf->Cell(30, 0, '', 0, 0, 'C');
        $pdf->Cell(10, 5, '', 0, 1);
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(160, 10, 'Total          ', 'LTB', 0, 'R', TRUE);
    $pdf->Cell(30, 10, str_comma_unit($firstResult['dtn_total_qty']), 1, 1, 'C', TRUE);


    $rmk = $db_con->query("SELECT det_fg_description, det_fg_code, SUM(det_shipping_qty) AS det_sum
    FROM tbl_order_dtn_detail_mst
    WHERE det_dtn_no = '$dtn_no'
    GROUP BY det_fg_description, det_fg_code
    ORDER BY det_fg_description, det_fg_code");
    $rmkQuery = sqlsrv_query($db_con, $rmk);
    $remarks = array();
    while($rmkResult = sqlsrv_fetch_array($rmkQuery, SQLSRV_FETCH_ASSOC)){
        $rmk = $rmkResult['det_fg_description'] . " / Q`ty: " . str_comma_unit($rmkResult['det_sum']) . " Pcs.";

        array_push($remarks, $rmk);
    }
    

    $line = 4.4; //แต่ละ row จะมีค่า  4.4
    $chck_line = (count($remarks) * 4.4) + $pdf->GetY();
    if($chck_line >= 265){
        $pdf->AddPage();
        $pdf->AliasNbPages();
    }

    $pdf->Cell(190, 5, '', 0, 1);
    $pdf->SetFont('Arial', 'BU', 10);
    $pdf->Cell(190, 5, 'Remarks', 0, 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(190, 2, '', 'LTR', 1);

    foreach($remarks as $val) {
        $x = $pdf->GetY();
        if($x >= 270.2){
            $pdf->Cell(190, 5, '', 'T', 1);
            $pdf->AddPage();
            $pdf->AliasNbPages();

            $pdf->Cell(190, 5, '', 'LTR', 1);
        }
        if(utf8_strlen($val)){
            $pdf->SetFont('THSarabunNew', 'B', 14);
        }else{
            $pdf->SetFont('Arial', '', 9);
        }
        $pdf->Cell(190, 4.4, LenTH($val), 'LR', 1);
    }
    $pdf->Cell(190, 10, '', 'T', 1);



    if($pdf->getY() > 241){
        $pdf->AddPage();
        $pdf->AliasNbPages();
    }

    $pdf->Cell(190, 5, '', 0, 1);
    $pdf->Cell(63.3, 4, '', 'LTR', 0);    
    $pdf->Cell(63.3, 4, '', 'LTR', 0);    
    $pdf->Cell(63.3, 4, '', 'LTR', 1);

    $pdf->Cell(20, 5, 'Data:', 'L', 0, 'R');
    $pdf->Cell(33.3, 5, '', 'B', 0);
    $pdf->Cell(10, 5, '', 'R', 0);
    if($firstResult['dtn_generate_sign'] != ''){
        $pdf->Image($firstResult['dtn_generate_sign'], 27, $pdf->GetY() - 3, 35, 0, 'PNG');
    }

    $pdf->Cell(20, 5, 'Driver:', 'L', 0, 'R');
    $pdf->Cell(33.3, 5, '', 'B', 0);
    $pdf->Cell(10, 5, '', 'R', 0);

    $pdf->Cell(20, 5, 'Customer:', 'L', 0, 'R');
    $pdf->Cell(33.3, 5, '', 'B', 0);
    $pdf->Cell(10, 5, '', 'R', 1);
    if($firstResult['dtn_receive_sign'] != ''){
        $pdf->Image($firstResult['dtn_receive_sign'], 155, $pdf->GetY() - 8, 35, 0, 'PNG');
    }

    $pdf->Cell(63.3, 2, '', 'LR', 0);    
    $pdf->Cell(63.3, 2, '', 'LR', 0);    
    $pdf->Cell(63.3, 2, '', 'LR', 1);

    $pdf->Cell(20, 5, 'Date/Time:', 'L', 0, 'R');
    $pdf->Cell(33.3, 5, STRToDatetime($firstResult['dtn_generate_datetime']), 'B', 0, 'C');
    $pdf->Cell(10, 5, '', 'R', 0);

    $pdf->Cell(20, 5, 'Date/Time:', 'L', 0, 'R');
    $pdf->Cell(33.3, 5, '', 'B', 0);
    $pdf->Cell(10, 5, '', 'R', 0);

    $pdf->Cell(20, 5, 'Date/Time:', 'L', 0, 'R');
    if($firstResult['dtn_receive_datetime'] != NULL){
        $pdf->Cell(33.3, 5, STRToDatetime($firstResult['dtn_receive_datetime'] ?? ''), 'B', 0, 'C');
    }else{
        $pdf->Cell(33.3, 5, '', 'B', 0, 'C');
    }
    $pdf->Cell(10, 5, '', 'R', 1);

    $pdf->Cell(63.3, 5, '', 'LRB', 0);    
    $pdf->Cell(63.3, 5, '', 'LRB', 0);    
    $pdf->Cell(63.3, 5, '', 'LRB', 1);





    if($firstResult['dtn_status'] == "Cancel"){
        $pdf->SetFont('Arial' ,'B' ,70);
        $pdf->SetTextColor(201, 0, 44);
        $pdf->RotatedText(20,160,'Cancel Delivery Transfer Note',30);
        $pdf->SetTextColor(0,0,0);
    }

    sqlsrv_close($db_con);
    $pdf->Output();



    //todo >>>>>>>>>>>>>>>>>>>>>>>> SETUP FUNC
    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    function utf8_strlen($s) {
        $ascii = [209, 212, 213, 214, 215, 216, 217, 218, 232, 233, 234, 235, 236, 237, 238];
        $c = strlen($s);
        $enc = false;
        $l = 0; 
        for ($i = 0; $i < $c; ++$i){
            if(ord($s[$i]) > 160){
                $enc = true;
                break;
            }
        }
        return $enc;
    }
?>



