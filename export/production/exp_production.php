<?php 
    require_once("../../session.php");
    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    ini_set("memory_limit","-1");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $spreadsheet = new Spreadsheet();

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    
    $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';

    $spreadsheet->getProperties()
                ->setCreator('IT Digital Team')
                ->setLastModifiedBy('Bearjai0')
                ->setTitle('Export Production')
                ->setSubject('Export Production')
                ->setDescription('Export Production')
                ->setKeywords('Export Production')
                ->setCategory('Export Production');

    // ArrayFunctions
    $bordered = array('borders'=>array('bottom'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]));
    $textright = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)));
    $textleft = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)));

    if($protocol == "exp_pd_v1"){
        ExportRes($db_con, $spreadsheet, 0, $start_date, $end_date, $status, $bordered, $textright);
    }



    function ExportRes($db_con, $spreadsheet, $index, $start_date, $end_date, $status, $bordered, $textright){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายการรับสินค้า');
        $last_col = 'W';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $sum_selling = 0;
        $total_cost = 0;
        $total_selling = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:W1'); $sheet->setCellValue('A1', 'บริษัทกล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:W2'); $sheet->setCellValue('A2', 'รายการรับสินค้า ( FINISHED GOOD )');
        $sheet->mergeCells('A3:W3'); $sheet->setCellValue('A3', 'ระหว่างวันที่ ' . date('d/m/Y', strtotime($start_date)) . ' ถึงวันที่ ' . date('d/m/Y', strtotime($end_date)) );
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', '#')
              ->setCellValue('B4', 'Job number')
              ->setCellValue('C4', 'Plan Date')
              ->setCellValue('D4', 'Machine Name')
              ->setCellValue('E4', 'Start Date')
              ->setCellValue('F4', 'End Date');
        
        $sheet->getStyle("A4:$last_col" . "4")->getFont()->setSize(9);
        $sheet->getStyle("A4:$last_col" . "4")->getFont()->setBold(true);
        $sheet->getStyle("A4:$last_col" . "4")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle("A4:$last_col" . "4")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT job_no, job_plan_date FROM tbl_job_mst WHERE job_status = '$status' AND job_plan_date BETWEEN '$start_date' AND '$end_date' ORDER BY job_no DESC"
        );
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $sheet->setCellValue("A$i", $i-4)
                  ->setCellValue("B$i", $result['job_no'])
                  ->setCellValue("C$i", $result['job_plan_date'])
                  ->setCellValue("D$i", '')
                  ->setCellValue("E$i", '')
                  ->setCellValue("F$i", '');
            $sheet->getStyle("A$i:W$i")->applyFromArray($bordered);
            $sheet->getStyle("R$i:V$i")->applyFromArray($textright);

            $i++;

            $mclist = $db_con->prepare("SELECT machine_type_name, start_datetime, ope_finish_datetime FROM tbl_job_operation AS A LEFT JOIN tbl_machine_type_mst AS B ON A.ope_mc_code = B.machine_type_code WHERE ope_job_no = :job_no AND ope_orders > 0 ORDER BY ope_orders");
            $mclist->bindParam(':job_no', $result['job_no']);
            $mclist->execute();
            while($mcResult = $mclist->fetch(PDO::FETCH_ASSOC)){
                $sheet->setCellValue("A$i", '')
                      ->setCellValue("B$i", '')
                      ->setCellValue("C$i", '')
                      ->setCellValue("D$i", $mcResult['machine_type_name'])
                      ->setCellValue("E$i", $mcResult['start_datetime'])
                      ->setCellValue("F$i", $mcResult['ope_finish_datetime']);
                $sheet->getStyle("A$i:W$i")->applyFromArray($bordered);
                $sheet->getStyle("R$i:V$i")->applyFromArray($textright);

                $i++;
            }
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

    $db_con = null;

    $writer = new Xlsx($spreadsheet);

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Export Accounting FG Stock Report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    $writer->save('php://output');
?>