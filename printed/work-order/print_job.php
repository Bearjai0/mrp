<?php
    /*call the FPDF library*/
    require_once("../../application.php");
    require_once('../../../library/fpdf184/code128.php');
    require_once('../../../library/fpdf184/exfpdf.php');
    require_once('../../../library/fpdf184/easyTable.php');

    $job_no = isset($_REQUEST['job_no']) ? $_REQUEST['job_no'] : '';

    $fst = $db_con->query("SELECT * FROM tbl_job_mst WHERE job_no = '$job_no'");
    $firstResult = $fst->fetch(PDO::FETCH_ASSOC);

    if($firstResult['job_no'] == ''){
        echo 'ไม่พบข้อมูล';
        return;
    }

    $GLOBALS['job_no'] = $job_no;

    class PDF extends exFPDF {
        public function Header(){
            $this->Image("../../../library/assets/images/company_logo/fac_main.png", 15, 8, 25, 17, 'PNG');
            $this->Code128(160, 11,$GLOBALS['job_no'],35, 11);
        }

        function Footer(){
            $this->SetY(-10);
            $this->SetFont('Arial','I',9);
            $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
            $this->Line(10, 280, 200, 280);
        }
    }

    $pdf = new PDF();
    $pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $pdf->AddFont('angsa','','angsa.php');
    $pdf->SetFont('Arial','B',10); // default font size

    $pdf->AddPage();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(false);

    $header = new easyTable($pdf, '%{ 20, 60, 20 }', 'line-height: 1.5;');
    $header->easyCell('', 'border: 1;');
    $header->easyCell(iconv('UTF-8', 'TIS-620', 'Work order of Production (ใบสั่งงานผลิต)'), 'font-family:THSarabunNew; font-size: 20; font-style: B; font-color: #000; border: 1;');
    $header->easyCell('', 'border: 1;');
    $header->printRow();


    // $quote = new easyTable($pdf, '%{ 100 }', 'line-height: 1.5;');
    // $quote->printRow();
    // $quote->endTable();

    // $sub = new easyTable($pdf, '%{ 30, 70 }', 'line-height: 1;');
    // $sub->easyCell('QUOTATION NO.', 'font-size: 11; font-style: B; font-color: #000; border: B;');
    // $sub->easyCell(" : " . $firstResult['quo_code'], 'font-size: 11; font-style: N; font-color: #000; border: B;');
    // $sub->printRow();

    // $sub->easyCell('ATTENTION', 'font-size: 11; font-style: B; font-color: #000; border: B;');
    // if (preg_match('/[\p{Thai}]/u', $firstResult['quo_attention'])) {
    //     $sub->easyCell(" : " . iconv('UTF-8', 'TIS-620', $firstResult['quo_attention']), 'font-family:THSarabunNew; font-size: 16; font-style: B; font-color: #000; border: B;');
    // } else {
    //     $sub->easyCell(" : " . $firstResult['quo_attention'], 'font-size: 11; font-style: N; font-color: #000; border: B;');
    // }
    // $sub->printRow();



    sqlsrv_close($db_con);
    $pdf->Output();
?>