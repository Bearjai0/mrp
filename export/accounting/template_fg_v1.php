<?php 
    require_once("../../session.php");
    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    ini_set("memory_limit","-1");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $spreadsheet = new Spreadsheet();

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    
    $sec_date = isset($_REQUEST['sec_date']) ? $_REQUEST['sec_date'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';

    $spreadsheet->getProperties()
                ->setCreator('IT Digital Team')
                ->setLastModifiedBy('Bearjai0')
                ->setTitle('Export Costing Files')
                ->setSubject('Export Costing Files')
                ->setDescription('Export Costing Files')
                ->setKeywords('Costing Files')
                ->setCategory('Costing Files');

    // ArrayFunctions
    $bordered = array('borders'=>array('bottom'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]));
    $textright = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)));
    $textleft = array('borders' => array('outline' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)));

    if($protocol == "Inbound"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered, $textright);
    }else if($protocol == "Outbound"){
        Outbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }else if($protocol == "InboundAndOutbound"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered, $textright);
        $sheet = $spreadsheet->createSheet();
        Outbound($db_con, $spreadsheet, 1, $start_date, $end_date, $bordered);
    }else if($protocol == "BalanceOnHand"){
        Onhand($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }else if($protocol == "Full Transactions"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered, $textright);
        $sheet = $spreadsheet->createSheet();
        Outbound($db_con, $spreadsheet, 1, $start_date, $end_date, $bordered);
        $sheet = $spreadsheet->createSheet();
        Onhand($db_con, $spreadsheet, 2, $start_date, $end_date, $bordered);
    }else if($protocol == "Stock Lost"){
        StockLost($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }else if($protocol == "Stock Damage"){
        StockDamage($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }



    function Inbound($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered, $textright){
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

        $sheet->setCellValue('A4', 'Pallet ID & FG Code')
              ->setCellValue('B4', 'Event Date')
              ->setCellValue('C4', 'Pallet ID')
              ->setCellValue('D4', 'Locations')
              ->setCellValue('E4', 'Cover No#')
              ->setCellValue('F4', 'Receive Type')
              ->setCellValue('G4', 'Job Number')
              ->setCellValue('H4', 'FG Codeset')
              ->setCellValue('I4', 'FG Code')
              ->setCellValue('J4', 'FG Descriptions')
              ->setCellValue('K4', 'FG Component')
              ->setCellValue('L4', 'Part Customer')
              ->setCellValue('M4', 'Customer')
              ->setCellValue('N4', 'Project')
              ->setCellValue('O4', 'Ship to Type')
              ->setCellValue('P4', 'Type')
              ->setCellValue('Q4', 'Status')
              ->setCellValue('R4', 'Quantity')
              ->setCellValue('S4', 'ต้นทุนการผลิต / ชิ้น')
              ->setCellValue('T4', 'รวมต้นทุนการผลิต')
              ->setCellValue('U4', 'ราคาขาย / ชิ้น')
              ->setCellValue('V4', 'รวมราคาขาย')
              ->setCellValue('W4', 'Remarks');
        
        $sheet->getStyle("A4:W4")->getFont()->setSize(9);
        $sheet->getStyle("A4:W4")->getFont()->setBold(true);
        $sheet->getStyle('A4:W4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:W4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT A.t_inv_pallet_id, A.t_inv_datetime, A.t_inv_location, A.t_inv_lot_no, A.t_inv_fg_codeset, A.t_inv_fg_code, A.t_inv_comp_code, A.t_inv_part_customer, A.t_inv_fg_description,
                    A.t_inv_cus_code, A.t_inv_project, A.t_inv_ship_to_type, A.t_inv_type, A.t_inv_status, A.t_inv_qty, A.t_inv_remarks,
                    B.list_conf_type, B.list_remarks,
                    C.cost_total, C.selling_price,
                    Main.pallet_job_set
             FROM tbl_fg_inven_transactions_mst AS A
             LEFT JOIN tbl_fg_inven_mst AS Main ON A.t_inv_pallet_id = Main.pallet_id
             LEFT JOIN tbl_confirm_print_list AS B ON A.t_inv_lot_no = B.list_conf_no
             LEFT JOIN tbl_bom_mst AS C ON A.t_inv_bom_uniq = C.bom_uniq
             WHERE t_inv_type = 'IN' AND CONVERT(DATE, t_inv_datetime) BETWEEN :start_date AND :end_date
             ORDER BY A.t_inv_datetime, A.t_inv_uniq"
        );
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $total_cost = $result['t_inv_qty'] * $result['cost_total'];
            $total_selling = $result['t_inv_qty'] * $result['selling_price'];
            $sum_pcs += $result['t_inv_qty'];
            $sum_cost += $total_cost;
            $sum_selling += $total_selling;

            $job_issue = $result['list_conf_type'] == "Manual" ? $result['list_remarks'] : $result['pallet_job_set'];

            $sheet->setCellValue("A$i", $result['t_inv_pallet_id'] . $result['t_inv_fg_code'])
                  ->setCellValue("B$i", $result['t_inv_datetime'])
                  ->setCellValue("C$i", $result['t_inv_pallet_id'])
                  ->setCellValue("D$i", $result['t_inv_location'])
                  ->setCellValue("E$i", $result['t_inv_lot_no'])
                  ->setCellValue("F$i", $result['list_conf_type'])
                  ->setCellValue("G$i", $job_issue)
                  ->setCellValue("H$i", $result['t_inv_fg_codeset'])
                  ->setCellValue("I$i", $result['t_inv_fg_code'])
                  ->setCellValue("J$i", $result['t_inv_fg_description'])
                  ->setCellValue("K$i", $result['t_inv_comp_code'])
                  ->setCellValue("L$i", $result['t_inv_part_customer'])
                  ->setCellValue("M$i", $result['t_inv_cus_code'])
                  ->setCellValue("N$i", $result['t_inv_project'])
                  ->setCellValue("O$i", $result['t_inv_ship_to_type'])
                  ->setCellValue("P$i", $result['t_inv_type'])
                  ->setCellValue("Q$i", $result['t_inv_status'])
                  ->setCellValue("R$i", number_format($result['t_inv_qty'], 2))
                  ->setCellValue("S$i", number_format($result['cost_total'], 2))
                  ->setCellValue("T$i", number_format($total_cost, 2))
                  ->setCellValue("U$i", number_format($result['selling_price'], 2))
                  ->setCellValue("V$i", number_format($total_selling, 2))
                  ->setCellValue("W$i", $result['t_inv_remarks']);

            
            $sheet->getStyle("A$i:W$i")->applyFromArray($bordered);
            $sheet->getStyle("R$i:V$i")->applyFromArray($textright);

            $i++;
        }

        $sheet->setCellValue("R$i", $sum_pcs);
        $sheet->setCellValue("T$i", number_format($sum_cost, 2, '.', ','));
        $sheet->setCellValue("V$i", number_format($sum_selling, 2, '.', ','));
        

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function Outbound($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายงานการเบิกออกสินค้า');
        $last_col = 'AB';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $sum_selling = 0;
        $total_cost = 0;
        $total_selling = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:AB1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:AB2'); $sheet->setCellValue('A2', 'รายงานการเบิกออกสินค้า ( FINISHED GOOD )');
        $sheet->mergeCells('A3:AB3'); $sheet->setCellValue('A3', 'ระหว่างวันที่ ' . date('d/m/Y', strtotime($start_date)) . ' ถึงวันที่ ' . date('d/m/Y', strtotime($end_date)) );
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'MRD Code')
              ->setCellValue('B4', 'Event Date')
              ->setCellValue('C4', 'INV')
              ->setCellValue('D4', 'DTN No#')
              ->setCellValue('E4', 'Pallet ID')
              ->setCellValue('F4', 'Cover No#')
              ->setCellValue('G4', 'Receive Type')
              ->setCellValue('H4', 'Job Number')
              ->setCellValue('I4', 'MRD No#')
              ->setCellValue('J4', 'FG Codeset')
              ->setCellValue('K4', 'FG Code')
              ->setCellValue('L4', 'FG Descriptions')
              ->setCellValue('M4', 'FG Component')
              ->setCellValue('N4', 'Part Customer')
              ->setCellValue('O4', 'Customer')
              ->setCellValue('P4', 'Project')
              ->setCellValue('Q4', 'Ship to Type')
              ->setCellValue('R4', 'Type')
              ->setCellValue('S4', 'Status')
              ->setCellValue('T4', 'Period Billing')
              ->setCellValue('U4', 'Q`ty Out')
              ->setCellValue('V4', 'Billing Pcs.')
              ->setCellValue('W4', 'Balance')
              ->setCellValue('X4', 'ต้นทุนการผลิต/ชิ้น')
              ->setCellValue('Y4', 'รายการต้นทุนผลิต')
              ->setCellValue('Z4', 'ราคาขาย/ชิ้น')
              ->setCellValue('AA4','รวมราคาขาย')
              ->setCellValue('AB4','Remarks');

        $sheet->getStyle("A4:AB4")->getFont()->setSize(9);
        $sheet->getStyle("A4:AB4")->getFont()->setBold(true);
        $sheet->getStyle('A4:AB4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:AB4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT A.t_inv_pallet_id, A.t_inv_datetime, A.t_inv_lot_no, A.t_inv_fg_codeset, A.t_inv_fg_code, A.t_inv_fg_description, A.t_inv_comp_code, A.t_inv_part_customer, A.t_inv_cus_code, A.t_inv_project, A.t_inv_ship_to_type, A.t_inv_type, A.t_inv_status, SUM(A.t_inv_qty) AS quantity, A.t_inv_dtn_no,
                    B.pallet_job_set,
                    C.cost_total, C.selling_price,
                    D.dtn_inv_no,
                    E.int_no,
                    F.list_conf_type, F.list_remarks
             FROM tbl_fg_inven_transactions_mst AS A
             LEFT JOIN tbl_fg_inven_mst AS B ON A.t_inv_pallet_id = B.pallet_id
             LEFT JOIN tbl_bom_mst AS C ON A.t_inv_bom_uniq = C.bom_uniq
             LEFT JOIN tbl_order_dtn_mst AS D ON A.t_inv_dtn_no = D.dtn_no
             LEFT JOIN tbl_no_instructions_mst AS E ON D.dtn_int_no = E.int_no
             LEFT JOIN tbl_confirm_print_list AS F ON B.pallet_lot_no = F.list_conf_no
             WHERE A.t_inv_type = 'OUT' AND CONVERT(DATE, A.t_inv_datetime) BETWEEN :start_date AND :end_date
             GROUP BY A.t_inv_pallet_id, A.t_inv_datetime, A.t_inv_lot_no, A.t_inv_fg_codeset, A.t_inv_fg_code, A.t_inv_fg_description, A.t_inv_comp_code, A.t_inv_part_customer, A.t_inv_cus_code, A.t_inv_project, A.t_inv_ship_to_type, A.t_inv_type, A.t_inv_status, A.t_inv_dtn_no,
                      B.pallet_job_set,
                      C.cost_total, C.selling_price,
                      D.dtn_inv_no,
                      E.int_no,
                      F.list_conf_type, F.list_remarks
             ORDER BY A.t_inv_datetime"
        );
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();
        
        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $document_no = '';
            $total_cost = $result['quantity'] * $result['cost_total'];
            $total_selling = $result['quantity'] * $result['selling_price'];
            $sum_pcs += $result['quantity'];
            $sum_cost += $total_cost;
            $sum_selling += $total_selling;

            $job_issue = $result['list_conf_type'] == "Manual" ? $result['list_remarks'] : $result['pallet_job_set'];
            if($result['t_inv_status'] == "Transferring"){
                $document_no = $result['t_inv_dtn_no'];
            }else{
                $document_no = isset($result['int_no']) ? $result['int_no'] : $result['dtn_inv_no'];
            }

            $sheet->setCellValue("A$i",  $result['t_inv_pallet_id'])
                  ->setCellValue("B$i",  $result['t_inv_datetime'])
                  ->setCellValue("C$i",  $document_no)
                  ->setCellValue("D$i",  $result['t_inv_dtn_no'])
                  ->setCellValue("E$i",  $result['t_inv_pallet_id'])
                  ->setCellValue("F$i",  $result['t_inv_lot_no'])
                  ->setCellValue("G$i",  $result['list_conf_type'])
                  ->setCellValue("H$i",  $job_issue)
                  ->setCellValue("I$i",  '')
                  ->setCellValue("J$i",  $result['t_inv_fg_codeset'])
                  ->setCellValue("K$i",  $result['t_inv_fg_code'])
                  ->setCellValue("L$i",  $result['t_inv_fg_description'])
                  ->setCellValue("M$i",  $result['t_inv_comp_code'])
                  ->setCellValue("N$i",  $result['t_inv_part_customer'])
                  ->setCellValue("O$i",  $result['t_inv_cus_code'])
                  ->setCellValue("P$i",  $result['t_inv_project'])
                  ->setCellValue("Q$i",  $result['t_inv_ship_to_type'])
                  ->setCellValue("R$i",  $result['t_inv_type'])
                  ->setCellValue("S$i",  $result['t_inv_status'])
                  ->setCellValue("T$i", '')
                  ->setCellValue("U$i", $result['quantity'])
                  ->setCellValue("V$i", $result['quantity'])
                  ->setCellValue("W$i", 0)
                  ->setCellValue("X$i", number_format($result['cost_total'], 2, '.', ','))
                  ->setCellValue("Y$i", number_format($total_cost, 2, '.', ','))
                  ->setCellValue("Z$i", number_format($result['selling_price'], 2, '.', ','))
                  ->setCellValue("AA$i",number_format($total_selling, 2, '.', ','))
                  ->setCellValue("AB$i",'');

            $sheet->getStyle("A$i:AB$i")->applyFromArray($bordered);

            $i++;
        }

        $sheet->setCellValue("U$i", $sum_pcs);
        $sheet->setCellValue("X$i", number_format($sum_cost, 2, '.', ','));
        $sheet->setCellValue("AA$i", number_format($sum_selling, 2, '.', ','));
        

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }


    function Onhand($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายงานสินค้าคงคลัง');
        $last_col = 'R';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $sum_selling = 0;
        $total_cost = 0;
        $total_selling = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:R2'); $sheet->setCellValue('A2', 'รายงานสินค้าคงคลัง ( FINISHED GOOD )');
        $sheet->mergeCells('A3:R3'); $sheet->setCellValue('A3', 'วันที่ ' . date('d/m/Y', strtotime($end_date)));
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'ProjectPallet Stock Q`ty')
              ->setCellValue('B4', 'Pallet ID')
              ->setCellValue('C4', 'Job Number#')
              ->setCellValue('D4', 'Cover Number#')
              ->setCellValue('E4', 'Type')
              ->setCellValue('F4', 'FG Codeset')
              ->setCellValue('G4', 'FG Code')
              ->setCellValue('H4', 'FG Descriptions')
              ->setCellValue('I4', 'Part Customer')
              ->setCellValue('J4', 'Customer')
              ->setCellValue('K4', 'Project')
              ->setCellValue('L4', 'Pallet Stock Q`ty')
              ->setCellValue('M4', 'ต้นทุนการผลิต / ชิ้น')
              ->setCellValue('N4', 'รวมต้นทุนการผลิต')
              ->setCellValue('O4', 'ราคาขาย / ชิ้น')
              ->setCellValue('P4', 'รวมราคาขาย')
              ->setCellValue('Q4', 'Remarks')
              ->setCellValue('R4', 'Receive Date');

        $sheet->getStyle("A4:R4")->getFont()->setSize(9);
        $sheet->getStyle("A4:R4")->getFont()->setBold(true);
        $sheet->getStyle('A4:R4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:R4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT COALESCE(SUM(D.con_res_qty),0) AS res_qty, A.pallet_job_set, A.pallet_id, A.pallet_stock_qty, A.pallet_id, A.pallet_lot_no, A.pallet_fg_codeset, A.pallet_fg_code, A.pallet_fg_description, A.pallet_part_customer, A.pallet_cus_code, A.pallet_project, A.pallet_stock_qty, A.pallet_receive_date,
                    B.list_conf_type, B.list_remarks, C.cost_total, C.selling_price,
                    (SELECT COALESCE(SUM(t_inv_qty),0) FROM tbl_fg_inven_transactions_mst AS t_inv WHERE t_inv.t_inv_pallet_id = A.pallet_id AND t_inv.t_inv_type = 'OUT') - (SELECT COALESCE(SUM(t_inv_qty),0) FROM tbl_fg_inven_transactions_mst AS t_inv WHERE t_inv.t_inv_pallet_id = A.pallet_id AND t_inv.t_inv_type = 'OUT' AND CONVERT(date, t_inv.t_inv_datetime) > :t_inv_date) AS after_calculate
             FROM tbl_fg_inven_mst AS A
             LEFT JOIN tbl_confirm_print_list AS B ON A.pallet_lot_no = B.list_conf_no
             LEFT JOIN tbl_bom_mst AS C ON A.pallet_bom_uniq = C.bom_uniq
             LEFT JOIN tbl_ffmc_reserve_order AS D ON A.pallet_id = D.con_res_pallet_id AND D.con_status = 'Pending'
             WHERE CONVERT(date, A.pallet_gen_datetime) < :pallet_gen_date
             GROUP BY A.pallet_job_set, A.pallet_id, A.pallet_lot_no, A.pallet_fg_codeset, A.pallet_fg_code, A.pallet_fg_description, A.pallet_part_customer, A.pallet_cus_code, A.pallet_project, A.pallet_stock_qty, A.pallet_receive_date, B.list_conf_type, B.list_remarks, C.cost_total, C.selling_price
             HAVING COALESCE(SUM(D.con_res_qty),0) + (SELECT COALESCE(SUM(t_inv_qty),0) FROM tbl_fg_inven_transactions_mst AS t_inv WHERE t_inv.t_inv_pallet_id = A.pallet_id AND t_inv.t_inv_type = 'IN') - ((SELECT COALESCE(SUM(t_inv_qty),0) FROM tbl_fg_inven_transactions_mst AS t_inv WHERE t_inv.t_inv_pallet_id = A.pallet_id AND t_inv.t_inv_type = 'OUT') - (SELECT COALESCE(SUM(t_inv_qty),0) FROM tbl_fg_inven_transactions_mst AS t_inv WHERE t_inv.t_inv_pallet_id = A.pallet_id AND t_inv.t_inv_type = 'OUT' AND CONVERT(date, t_inv.t_inv_datetime) > :having_end_date)) > 0
             ORDER BY A.pallet_id"
        );
        $sql->bindParam(':t_inv_date', $end_date);
        $sql->bindParam(':pallet_gen_date', $end_date);
        $sql->bindParam(':having_end_date', $end_date);
        $sql->execute();
        
        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $quantity = intval($result['pallet_stock_qty']) + intval($result['res_qty'] + intval($result['after_calculate']));
            $job_issue = $result['list_conf_type'] == "Manual" ? $result['list_remarks'] : $result['pallet_job_set'];

            $sheet->setCellValue("A$i", $result['pallet_id'])
                  ->setCellValue("B$i", $result['pallet_id'])
                  ->setCellValue("C$i", $job_issue) 
                  ->setCellValue("D$i", $result['pallet_lot_no'])
                  ->setCellValue("E$i", $result['list_conf_type'])
                  ->setCellValue("F$i", $result['pallet_fg_codeset'])
                  ->setCellValue("G$i", $result['pallet_fg_code'])
                  ->setCellValue("H$i", $result['pallet_fg_description'])
                  ->setCellValue("I$i", $result['pallet_part_customer'])
                  ->setCellValue("J$i", $result['pallet_cus_code'])
                  ->setCellValue("K$i", $result['pallet_project'])
                  ->setCellValue("L$i", number_format($quantity, 2, '.', ','))
                  ->setCellValue("M$i", number_format($result['cost_total'], 2, '.', ','))
                  ->setCellValue("N$i", number_format($quantity * $result['cost_total'], 2, '.', ','))
                  ->setCellValue("O$i", number_format($result['selling_price'], 2, '.', ','))
                  ->setCellValue("P$i", number_format($quantity * $result['selling_price'], 2, '.', ','))
                  ->setCellValue("Q$i", '')
                  ->setCellValue("R$i", $result['pallet_receive_date']);

            $sheet->getStyle("A$i:Q$i")->applyFromArray($bordered);

            $i++;
        }

        $sheet->setCellValue("U$i", $sum_pcs);
        $sheet->setCellValue("X$i", number_format($sum_cost, 2, '.', ','));
        $sheet->setCellValue("AA$i", number_format($sum_selling, 2, '.', ','));
        

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function StockLost($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายงานสินค้าสูญหาย');
        $last_col = 'M';
        $i=5;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:R2'); $sheet->setCellValue('A2', 'รายงานสินค้าสูญหาย');
        $sheet->mergeCells('A3:R3'); $sheet->setCellValue('A3', 'ณ​ วันที่ ' . date('d/m/Y', strtotime($end_date)));
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
            "SELECT lost_pallet_id, lost_location, lost_lot_no, lost_bom_uniq, lost_fg_codeset, lost_fg_code, lost_comp_code, lost_part_customer,
                    lost_fg_description, lost_cus_code, lost_project, lost_stock_qty, lost_receive_datetime, lost_remarks
             FROM tbl_lost_item_mst
             WHERE lost_stock_qty > 0
             ORDER BY lost_receive_datetime"
        );
        
        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $sheet->setCellValue("A$i", $result['lost_pallet_id'])
                  ->setCellValue("B$i", $result['lost_lot_no'])
                  ->setCellValue("C$i", $result['lost_bom_uniq'])
                  ->setCellValue("D$i", $result['lost_fg_codeset'])
                  ->setCellValue("E$i", $result['lost_fg_code'])
                  ->setCellValue("F$i", $result['lost_comp_code'])
                  ->setCellValue("G$i", $result['lost_part_customer'])
                  ->setCellValue("H$i", $result['lost_fg_description'])
                  ->setCellValue("I$i", $result['lost_cus_code'])
                  ->setCellValue("J$i", $result['lost_project'])
                  ->setCellValue("K$i", number_format($result['lost_stock_qty'], 2, '.', ','))
                  ->setCellValue("L$i", date('d/m/Y H:i:s', strtotime($result['lost_receive_datetime'])))
                  ->setCellValue("M$i", $result['lost_remarks']);

            $sheet->getStyle("A$i:M$i")->applyFromArray($bordered);

            $i++;
        }

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function StockDamage($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายงานสินค้าเสียหาย');
        $last_col = 'M';
        $i=5;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:R1'); $sheet->setCellValue('A1', 'บริษัท กล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:R2'); $sheet->setCellValue('A2', 'รายงานสินค้าเสียหาย');
        $sheet->mergeCells('A3:R3'); $sheet->setCellValue('A3', 'ณ​ วันที่ ' . date('d/m/Y', strtotime($end_date)));
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