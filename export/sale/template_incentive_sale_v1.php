<?php 
    require_once("../../session.php");
    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    ini_set("memory_limit","-1");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $spreadsheet = new Spreadsheet();

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    $inc_uniq = isset($_POST['inc_uniq']) ? $_POST['inc_uniq'] : '';

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

    if($protocol == "Inbound"){
        ExportIncentive($db_con, $spreadsheet, 0, $bordered, $textright);
    }


    function ExportIncentive($db_con, $spreadsheet, $index, $bordered, $textright){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Summary incentive Report');
        $last_col = 'R';
        $i=5;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'Pallet ID')
              ->setCellValue('B4', 'Cover Number')
              ->setCellValue('C4', 'BOM Uniq')
              ->setCellValue('D4', 'FG Codeset')
              ->setCellValue('E4', 'FG Code')
              ->setCellValue('F4', 'Component')
              ->setCellValue('G4', 'Part Customer')
              ->setCellValue('H4', 'FG Description')
              ->setCellValue('I4', 'Customer')
              ->setCellValue('J4', 'Project')
              ->setCellValue('K4', 'Lost Quantity')
              ->setCellValue('L4', 'Receive Datetime')
              ->setCellValue('M4', 'Remarks');

        $sheet->getStyle("A4:M4")->getFont()->setSize(9);
        $sheet->getStyle("A4:M4")->getFont()->setBold(true);
        $sheet->getStyle('A4:M4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:M4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->query(
            "SELECT dmg_pallet_id, dmg_lot_no, dmg_bom_uniq, dmg_fg_codeset, dmg_fg_code, dmg_part_customer, dmg_comp_code, dmg_fg_description, dmg_cus_code, dmg_project, dmg_stock_qty, dmg_receive_datetime, dmg_remarks
             FROM tbl_damage_mst
             WHERE dmg_stock_qty > 0
             ORDER BY dmg_receive_datetime"
        );
        
        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $sheet->setCellValue("A$i", $result['dmg_pallet_id'])
                  ->setCellValue("B$i", $result['dmg_lot_no'])
                  ->setCellValue("C$i", $result['dmg_bom_uniq'])
                  ->setCellValue("D$i", $result['dmg_fg_codeset'])
                  ->setCellValue("E$i", $result['dmg_fg_code'])
                  ->setCellValue("F$i", $result['dmg_comp_code'])
                  ->setCellValue("G$i", $result['dmg_part_customer'])
                  ->setCellValue("H$i", $result['dmg_fg_description'])
                  ->setCellValue("I$i", $result['dmg_cus_code'])
                  ->setCellValue("J$i", $result['dmg_project'])
                  ->setCellValue("K$i", number_format($result['dmg_stock_qty'], 2, '.', ','))
                  ->setCellValue("L$i", date('d/m/Y H:i:s', strtotime($result['dmg_receive_datetime'])))
                  ->setCellValue("M$i", $result['dmg_remarks']);

            $sheet->getStyle("A$i:M$i")->applyFromArray($bordered);

            $i++;
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
    header('Content-Disposition: attachment;filename="Export Accounting FG Stock Report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    $writer->save('php://output');
?>