<?php
    /*call the FPDF library*/
    require_once("../../application.php");
    require_once('../../../library/fpdf184/code128.php');

    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
    $pallet_id = isset($_REQUEST['pallet_id']) ? $_REQUEST['pallet_id'] : '';

    $sql = $db_con->query("SELECT * FROM tbl_fg_inven_mst WHERE pallet_id = '$pallet_id'");
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    $GLOBALS['watermarks'] = $result['pallet_status'];

    if($result['pallet_id'] == ""){
        echo 'ไม่พบข้อมูล';
        return;
    }


    /*A4 width : 219mm*/
    /*Cell(width , height , text , border , end line , [align] )*/
    class PDF extends PDF_Code128 {
        public function Header(){
            //Put the watermark
            if($GLOBALS['watermarks'] == "Active"){
                $this->SetFont('Arial' ,'B' ,70);
                $this->SetTextColor(220, 220, 220);
                $this->RotatedText(70,160,'MRP PALLET ID',30);
                $this->SetTextColor(0,0,0);
            }
        }

        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            $this->SetFont('Arial','I',6);
            $this->Cell(0,10,'Pallet ID ' . date('d/m/Y'), 0, 0, 'R');
        }
    }

    $pdf = new PDF('L','mm','A4');
    // $pdf->SetMargins(13, 10, 15);
    $pdf->SetFont('Arial', 'B', 13);

    $pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $pdf->AddFont('angsa','','angsa.php');

    $pdf->AddPage();
    $pdf->AliasNbPages();
    
    //todo >>>>>>>>> Structure View
    //todo >>>>>>>>>>>>>>>>>>>>>>>>
    $pdf->SetFont('Arial', 'B', 80);

    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Cell(277, 30, $result['pallet_id'], 1, 1, 'C');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(92.33333, 20, 'FG Code : ' . $result['pallet_fg_code'], 'TL', 0, 'C');
    $pdf->Cell(92.33333, 20, 'Customer : ' . $result['pallet_cus_code'], 'T', 0, 'C');
    $pdf->Cell(92.33333, 20, 'Project : ' . $result['pallet_project'], 'TR', 1, 'C');

    $pdf->SetFont('Arial', '', 15);
    $pdf->Cell(277, 15, 'Descriptions', 'LR', 1, 'C');

    if(utf8_strlen($result['pallet_fg_description'])){
        $pdf->SetFont('THSarabunNew', 'B', 45);
    }else{
        $pdf->SetFont('Arial', 'B', 60);
    }
    $pdf->Cell(190, 4, '', 0, 1);
    $pdf->CellFitScale(277, 15, iconv('TIS-620', 'UTF-8', $result['pallet_fg_description']), 'LR', 1, 'C');
    $pdf->Cell(277, 10, '', 'LR', 1);

    $pdf->SetFont('Arial', 'B', 35);
    $quantity = $result['pallet_status'] == "Prepare" ? $result['pallet_receive_qty'] : $result['pallet_stock_qty'];
    $pdf->Cell(277, 20, 'Q`TY : ' . number_format($quantity, 0, '.', ',') . ' Pcs.', 'LR', 1, 'C');
    $pdf->Cell(277, 5, '', 'LR', 1);
    
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(277, 10, 'Received Date: ' . date('d/m/Y', strtotime($result['pallet_gen_datetime'])), 'LR', 1, 'C');

    $pdf->Cell(277, 27, '', 'LR', 1);
    $pdf->SetFont('Arial', 'B', 23);
    // $pdf->Cell(277, 8, 'PALLET ID: ' . $result['pallet_id'], 'LR', 1, 'C');
    $pdf->Cell(277, 7, 'Location: ' . $result['pallet_location'], 'LR', 1, 'C');
    $pdf->Cell(277, 15, '', 'LBR', 1);

    $pdf->Code128(100, 142, $result['pallet_id'], 100, 20);
    $pdf->Image('https://lib.albatrosslogistic.com/assets/images/company_logo/gdj_v3.png', 17, 11, 30, 28, 'PNG');


    if($GLOBALS['watermarks'] == "Already Used"){
        $pdf->SetFont('Arial' ,'B' ,100);
        $pdf->SetTextColor(201, 0, 44);
        $pdf->RotatedText(55,160,'Already Used',30);
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
