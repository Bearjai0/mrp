<?php
    /*call the FPDF library*/
    require_once("../../../application.php");
    require_once('../../../../library/fpdf184/code128.php');

    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
    $list_conf_no = isset($_REQUEST['list_conf_no']) ? $_REQUEST['list_conf_no'] : '';
    $lot_no = isset($_REQUEST['lot_no']) ? $_REQUEST['lot_no'] : '';

    $sql = "SELECT A.*, B.lot_qty, B.lot_no
            FROM tbl_confirm_print_list AS A
            LEFT JOIN tbl_cover_lot_list AS B ON A.list_conf_no = B.lot_list_no AND B.lot_no = '$lot_no'
            WHERE A.list_conf_no = '$list_conf_no'";
    $query = sqlsrv_query($db_con, $sql);
    $result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
    $quantity = $result['lot_no'] == null ? $result['list_current_qty'] : $result['lot_qty'];
    $lot_number = $result['lot_no'] == null ? $result['list_conf_no'] : $result['list_conf_no'] . ' - ' . $result['lot_no'];

    if($result['list_conf_no'] == ""){
        echo 'ไม่พบข้อมูล';
        return;
    }


    /*A4 width : 219mm*/
    /*Cell(width , height , text , border , end line , [align] )*/
    class PDF extends PDF_Code128 {
        public function Header(){
            //Put the watermark
            $this->SetFont('Arial' ,'B' ,70);
            $this->SetTextColor(220, 220, 220);
            $this->RotatedText(70,160,'MRP LOT COVER',30);
            $this->SetTextColor(0,0,0);
        }

        function Footer(){
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            $this->SetFont('Arial','I',6);
            $this->Cell(0,10,'Print MRP Lot Cover', 0, 0, 'R');
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
    $pdf->SetFont('Arial', 'B', 35);

    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Cell(277, 30, 'COVER SHIPPING LOT', 1, 1, 'C');

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(92.33333, 20, 'FG Code : ' . $result['list_fg_code'], 'TL', 0, 'C');
    $pdf->Cell(92.33333, 20, 'Customer : ' . $result['list_cus_code'], 'T', 0, 'C');
    $pdf->Cell(92.33333, 20, 'Project : ' . $result['list_project'], 'TR', 1, 'C');

    $pdf->SetFont('Arial', '', 15);
    $pdf->Cell(277, 15, 'Descriptions', 'LR', 1, 'C');

    if(utf8_strlen($result['list_fg_description'])){
        $pdf->SetFont('THSarabunNew', 'B', 33);
    }else{
        $pdf->SetFont('Arial', 'B', 22);
    }
    $pdf->CellFitScale(277, 15, LenTH($result['list_fg_description']), 'LR', 1, 'C');
    $pdf->Cell(277, 10, '', 'LR', 1);

    $pdf->SetFont('Arial', 'B', 60);
    $pdf->Cell(277, 20, 'Q`TY : ' . str_comma_unit($quantity) . ' Pcs.', 'LR', 1, 'C');
    $pdf->Cell(277, 10, '', 'LR', 1);
    
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(277, 10, 'Shipping Date: ' . date('d/m/Y', strtotime($result['list_conf_datetime'])), 'LR', 1, 'C');

    $pdf->Cell(277, 30, '', 'LR', 1);
    $pdf->Cell(277, 8, 'Lot Number: ' . $lot_number, 'LR', 1, 'C');
    $pdf->Cell(277, 7, 'Location: ' . 'FFMC WAREHOUSE', 'LR', 1, 'C');
    $pdf->Cell(277, 3, '', 'LBR', 1);

    $pdf->Code128(90, 142, $result['list_conf_no'], 125, 22);
    $pdf->Image('https://kb.albatrosslogistic.com/library/images/company_logo/fac_main.png', 15, 10, 45, 30, 'PNG');


    sqlsrv_close($db_con);

    $pdf->SetTitle('LOT-' . $result['list_conf_no'], TRUE);

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