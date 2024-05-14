<?php 
    require_once("../../session.php");
    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    ini_set("memory_limit","-1");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $spreadsheet = new Spreadsheet();

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : 'SummaryIncentive';
    $inc_uniq = isset($_REQUEST['inc_uniq']) ? $_REQUEST['inc_uniq'] : '';
    $rest = [];

    $fst = $db_con->prepare("SELECT * FROM tbl_sale_incentive WHERE inc_uniq = :inc_uniq");
    $fst->bindParam(':inc_uniq', $inc_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);

    $spreadsheet->getProperties()
                ->setCreator('IT Digital Team')
                ->setLastModifiedBy('Bearjai0')
                ->setTitle('Export Sale Incentive')
                ->setSubject('Export Sale Incentive')
                ->setDescription('Export Sale Incentive')
                ->setKeywords('Sale Incentive')
                ->setCategory('Sale Incentive');

    // ArrayFunctions
    $bordered = array('borders'=>array('bottom'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]));
    $textright = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)));
    $textleft = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)));

    if($protocol == "SummaryIncentive"){
        ExportIncentive($db_con, $vmi_con, $inc_uniq, $spreadsheet, 0, $bordered, $textright);
    }


    function ExportIncentive($db_con, $vmi_con, $inc_uniq, $spreadsheet, $index, $bordered, $textright){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Summary incentive Report');
        $last_col = 'I';
        $i=4;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', '#')
              ->setCellValue('B4', 'INV No.')
              ->setCellValue('C4', 'INV Date')
              ->setCellValue('D4', 'Customer')
              ->setCellValue('E4', 'Cost Total')
              ->setCellValue('F4', 'Selling Total')
              ->setCellValue('G4', 'Vat 7%')
              ->setCellValue('H4', 'Selling Grand Total')
              ->setCellValue('I4', 'Remarks');

        $sheet->getStyle("A4:".$last_col."4")->getFont()->setSize(9);
        $sheet->getStyle("A4:".$last_col."4")->getFont()->setBold(true);
        $sheet->getStyle("A4:".$last_col."4")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle("A4:".$last_col."4")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare("SELECT inv_no, inv_po_no, inv_type, inv_date, inv_cus_code, inv_total, inv_vat7per, inv_grand_total, inv_post_datetime, inv_cost_total FROM tbl_inv_mst WHERE inv_inc_uniq = :inc_uniq");
        $sql->bindParam(':inc_uniq', $inc_uniq);
        $sql->execute();
        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $i++;
            $sheet->setCellValue("A$i", $i)
                  ->setCellValue("B$i", $result['inv_no'])
                  ->setCellValue("C$i", $result['inv_date'])
                  ->setCellValue("D$i", $result['cus_code'])
                  ->setCellValue("E$i", $result['inv_cost_total'])
                  ->setCellValue("F$i", $result['inv_total'])
                  ->setCellValue("G$i", $result['inv_vat7per'])
                  ->setCellValue("H$i", $result['inv_grand_total'])
                  ->setCellValue("I$i", '');

            $sheet->getStyle("A$i:I$i")->applyFromArray($bordered);
        }

        $vml = $vmi_con->prepare("SELECT inv_no, inv_po_no, inv_type, inv_date, inv_cus_code, inv_total, inv_vat7per, inv_grand_total, inv_issue_datetime as inv_post_datetime, inv_cost_total FROM tbl_inv_mst WHERE inv_inc_uniq = :inc_uniq");
        $vml->bindParam(':inc_uniq', $inc_uniq);
        $vml->execute();
        while($result = $vml->fetch(PDO::FETCH_ASSOC)){
            $i++;
            $sheet->setCellValue("A$i", $i)
                  ->setCellValue("B$i", $result['inv_no'])
                  ->setCellValue("C$i", $result['inv_date'])
                  ->setCellValue("D$i", $result['cus_code'])
                  ->setCellValue("E$i", $result['inv_cost_total'])
                  ->setCellValue("F$i", $result['inv_total'])
                  ->setCellValue("G$i", $result['inv_vat7per'])
                  ->setCellValue("H$i", $result['inv_grand_total'])
                  ->setCellValue("I$i", '');

            $sheet->getStyle("A$i:I$i")->applyFromArray($bordered);
        }

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function SetupSheet($sheet){
        $sheet->setShowGridlines(true);
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.75);
        $sheet->getPageMargins()->setLeft(0.75);
        $sheet->getPageMargins()->setBottom(0.75);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getHeaderFooter()->setOddHeader('&R Page &P / &N &D &T')->setOddFooter('&L&B Printed on &D &R Powered By Digital Platform');
    }

    sqlsrv_close($db_con);

    $writer = new Xlsx($spreadsheet);

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Export sale result invoice incentive of '.$fstResult['inc_period'].'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    $writer->save('php://output');
?>