<?php 
    require_once("../../session.php");
    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    // ini_set("memory_limit","-1");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $spreadsheet = new Spreadsheet();

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    
    $due_date = isset($_REQUEST['due_date']) ? $_REQUEST['due_date'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';

    // ArrayFunctions
    $bordered = array('borders'=>array(
        'left'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'right'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'top'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'bottom'=>['borderStyle'=> \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    ));

    if($protocol == "Inbound"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }else if($protocol == "Outbound"){
        Outbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }else if($protocol == "InboundAndOutbound"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
        $sheet = $spreadsheet->createSheet();
        Outbound($db_con, $spreadsheet, 1, $start_date, $end_date, $bordered);
    }else if($protocol == "BalanceOnHand"){
        Onhand($db_con, $spreadsheet, 0, $end_date, $bordered);
    }else if($protocol == "Full Transactions"){
        Inbound($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
        $sheet = $spreadsheet->createSheet();
        Outbound($db_con, $spreadsheet, 1, $start_date, $end_date, $bordered);
        $sheet = $spreadsheet->createSheet();
        Onhand($db_con, $spreadsheet, 2, $end_date, $bordered);
        $sheet = $spreadsheet->createSheet();
        Exceeded($db_con, $spreadsheet, 3, $start_date, $end_date, $bordered);
    }else if($protocol == "ExceededInvoice"){
        Exceeded($db_con, $spreadsheet, 0, $start_date, $end_date, $bordered);
    }
    
    function Inbound($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายการรับกระดาษ');
        $last_col = 'P';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $total_cost = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:P1'); $sheet->setCellValue('A1', 'บริษัทกล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:P2'); $sheet->setCellValue('A2', 'รายการรับเข้าวัตถุดิบ ( RAW MATERIAL )');
        $sheet->mergeCells('A3:P3'); $sheet->setCellValue('A3', 'ระหว่างวันที่ ' . date('d/m/Y', strtotime($start_date)) . ' ถึงวันที่ ' . date('d/m/Y', strtotime($end_date)) );
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'Lot + RM Code');
        $sheet->setCellValue('B4', 'วันที่');
        $sheet->setCellValue('C4', 'GRN Number');
        $sheet->setCellValue('D4', 'Pallet ID');
        $sheet->setCellValue('E4', 'Ref# Number');
        $sheet->setCellValue('F4', 'Supplier Name');
        $sheet->setCellValue('G4', 'INV Number');
        $sheet->setCellValue('H4', 'RM Code');
        $sheet->setCellValue('I4', 'Item Descriptions');
        $sheet->setCellValue('J4', 'Area');
        $sheet->setCellValue('K4', 'Transactions Type');
        $sheet->setCellValue('L4', 'Quantity');
        $sheet->setCellValue('M4', 'Unit');
        $sheet->setCellValue('N4', 'Unit Price');
        $sheet->setCellValue('O4', 'Summary');
        $sheet->setCellValue('P4', 'Remarks');

        $sheet->getStyle("A4:P4")->getFont()->setSize(9);
        $sheet->getStyle("A4:P4")->getFont()->setBold(true);
        $sheet->getStyle('A4:P4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:P4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT A.pallet_id, A.qty, A.item_descript, A.area, A.grn, A.trans_type, A.trans_remarks, B.uom, B.receive_date, B.location_name_en, B.rm_type, B.product_type, B.raw_code, CAST(B.unit_price AS DECIMAL(10, 4)) AS unit_price, B.project, C.inv_sup_code, C.inv_sup_name, C.inv_no, C.ref_no
             FROM tbl_inven_transaction_mst AS A
             LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
             LEFT JOIN tbl_invoice_mst AS C ON A.grn = C.grn AND A.inv_no = C.inv_no
             WHERE A.area = 'IN' AND A.trans_date BETWEEN :start_date AND :end_date AND B.product_type = 'RAW MAT'
             ORDER BY B.receive_date, A.pallet_id"
        );
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $quantity = $result['qty'];
            $unit = $result['uom'];

            $corner = ['SMID00038','SMID00087','SMID00083','SMID00037'];

            if(in_array($result['raw_code'], $corner)){
                $quantity = number_format(($quantity / 1100) / 1000, 2, '.', ',');
                $unit = 'Rolls';
            }

            $total_cost = $quantity * $result['unit_price'];
            $sum_pcs += $quantity;
            $sum_cost += $total_cost;
            
            $sheet->setCellValue("A$i", $result['raw_code'])
                  ->setCellValue("B$i", $result['receive_date'])
                  ->setCellValue("C$i", $result['grn'])
                  ->setCellValue("D$i", $result['pallet_id'])
                  ->setCellValue("E$i", $result['ref_no'])
                  ->setCellValue("F$i", $result['inv_sup_name'])
                  ->setCellValue("G$i", $result['inv_no'])
                  ->setCellValue("H$i", $result['raw_code'])
                  ->setCellValue("I$i", $result['item_descript'])
                  ->setCellValue("J$i", $result['area'])
                  ->setCellValue("K$i", $result['trans_type'])
                  ->setCellValue("L$i", $quantity)
                  ->setCellValue("M$i", $unit)
                  ->setCellValue("N$i", $result['unit_price'] . ' ')
                  ->setCellValue("O$i", number_format($total_cost, 2) . ' ')
                  ->setCellValue("P$i", $result['trans_remarks']);

            $sheet->getStyle("A$i:P$i")->applyFromArray($bordered);
            $sheet->getStyle("L$i:O$i")->getAlignment()->setHorizontal('right');


            $i++;
        }

        $sheet->setCellValue("L$i", number_format($sum_pcs, 2));
        $sheet->setCellValue("O$i", number_format($sum_cost, 2));
        $sheet->getStyle("L$i:O$i")->getAlignment()->setHorizontal('right');


        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }

    function Outbound($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายการเบิกจ่ายกระดาษ');
        $last_col = 'O';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $total_cost = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:P1'); $sheet->setCellValue('A1', 'บริษัทกล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:P2'); $sheet->setCellValue('A2', 'รายการเบิกวัตถุดิบ ( RAW MATERIAL )');
        $sheet->mergeCells('A3:P3'); $sheet->setCellValue('A3', 'ระหว่างวันที่ ' . date('d/m/Y', strtotime($start_date)) . ' ถึงวันที่ ' . date('d/m/Y', strtotime($end_date)) );
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'Lot + RM Code');
        $sheet->setCellValue('B4', 'วันที่');
        $sheet->setCellValue('C4', 'เลขที่เอกสาร');
        $sheet->setCellValue('D4', 'Job No.');
        $sheet->setCellValue('E4', 'FG Code');
        $sheet->setCellValue('F4', 'Pallet ID');
        $sheet->setCellValue('G4', 'RM Code');
        $sheet->setCellValue('H4', 'Item Descriptions');
        $sheet->setCellValue('I4', 'Area');
        $sheet->setCellValue('J4', 'Transactions Type');
        $sheet->setCellValue('K4', 'Quantity');
        $sheet->setCellValue('L4', 'Unit');
        $sheet->setCellValue('M4', 'Unit Price');
        $sheet->setCellValue('N4', 'Amount');
        $sheet->setCellValue('O4', 'Remarks');

        $sheet->getStyle("A4:O4")->getFont()->setSize(9);
        $sheet->getStyle("A4:O4")->getFont()->setBold(true);
        $sheet->getStyle('A4:O4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:O4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');

        $sql = $db_con->prepare(
            "SELECT A.pallet_id, A.gdn, A.item_descript, A.qty, A.unit, A.area, A.pick_no, A.trans_type, A.trans_remarks, A.trans_date, A.job_ship_ref, CAST(B.unit_price AS DECIMAL(10, 4)) AS unit_price, B.raw_code, C.job_fg_code
             FROM tbl_inven_transaction_mst AS A
             LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
             LEFT JOIN tbl_job_mst AS C ON A.job_ship_ref = C.job_no
             WHERE A.area = 'OUT' AND A.trans_date BETWEEN :start_date AND :end_date AND B.product_type = 'RAW MAT'
             ORDER BY trans_date, trans_time"
        );
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $quantity = $result['qty'];
            $unit = $result['unit'];

            $corner = ['SMID00038','SMID00087','SMID00083','SMID00037'];

            if(in_array($result['raw_code'], $corner)){
                $quantity = number_format(($quantity / 1100) / 1000, 2, '.', ',');
                $unit = 'Rolls';
            }
            
            $total_cost = $quantity * $result['unit_price'];
            $sum_pcs += $quantity;
            $sum_cost += $total_cost;
            
            $sheet->setCellValue("A$i", $result['pallet_id'] . $result['raw_code']);
            $sheet->setCellValue("B$i", $result['trans_date']);
            $sheet->setCellValue("C$i", $result['pick_no']);
            $sheet->setCellValue("D$i", $result['job_ship_ref']);
            $sheet->setCellValue("E$i", $result['job_fg_code']);
            $sheet->setCellValue("F$i", $result['pallet_id']);
            $sheet->setCellValue("G$i", $result['raw_code']);
            $sheet->setCellValue("H$i", $result['item_descript']);
            $sheet->setCellValue("I$i", $result['area']);
            $sheet->setCellValue("J$i", $result['trans_type']);
            $sheet->setCellValue("K$i", $quantity);
            $sheet->setCellValue("L$i", $unit);
            $sheet->setCellValue("M$i", number_format($result['unit_price'], 4) . ' ');
            $sheet->setCellValue("N$i", number_format($total_cost, 2) . ' ');
            $sheet->setCellValue("O$i", $result['trans_remarks']);

            
            $sheet->getStyle("A$i:O$i")->applyFromArray($bordered);
            $sheet->getStyle("K$i:N$i")->getAlignment()->setHorizontal('right');

            $i++;
        }

        $sheet->setCellValue("K$i", number_format($sum_pcs, 2));
        $sheet->setCellValue("N$i", number_format($sum_cost, 2));
        

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }
    
    function Onhand($db_con, $spreadsheet, $index, $due_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายการสินค้าคงคลัง');
        $last_col = 'O';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $total_cost = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:P1'); $sheet->setCellValue('A1', 'บริษัทกล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:P2'); $sheet->setCellValue('A2', 'รายการสินค้าคงคลัง ( RAW MATERIAL )');
        $sheet->mergeCells('A3:P3'); $sheet->setCellValue('A3', 'วันที่ ' . date('d/m/Y', strtotime($due_date)));
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);
        
        $sheet->setCellValue('A4', 'Lot + RM Code');
        $sheet->setCellValue('B4', 'RM Code');
        $sheet->setCellValue('C4', 'Item Descrptions');
        $sheet->setCellValue('D4', 'วันที่รับเข้า');
        $sheet->setCellValue('E4', 'Pallet ID');
        $sheet->setCellValue('F4', 'Unit');
        $sheet->setCellValue('G4', 'Quantity');
        $sheet->setCellValue('H4', 'Unit Price / Pcs.');
        $sheet->setCellValue('I4', 'Amount');
        $sheet->setCellValue('J4', 'Remarks');

        $sheet->getStyle("A4:J4")->getFont()->setSize(9);
        $sheet->getStyle("A4:J4")->getFont()->setBold(true);
        $sheet->getStyle('A4:J4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:J4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');


        $sql = $db_con->prepare(
            "SELECT A.pallet_id, A.receive_qty, A.stock_qty, COALESCE(SUM(B.picking_qty),0) AS pick_qty, (SELECT COALESCE(SUM(qty),0) FROM tbl_inven_transaction_mst AS t_inv WHERE t_inv.pallet_id = A.pallet_id AND t_inv.area = 'OUT' AND trans_date > :due_date) AS after_calculate, A.grn, A.project, A.raw_code, A.rm_descript, A.product_type, A.location_name_en, A.pallet_id, A.receive_qty, A.used_qty, A.receive_date, CAST(A.unit_price AS DECIMAL(10, 4)) AS unit_price, A.uom
             FROM tbl_stock_inven_mst AS A
             LEFT JOIN tbl_picking_item_mst AS B ON A.pallet_id = B.pallet_id AND picking_status IN('reserve','shipping','generate','Return Materials')
             WHERE receive_date <= :receive_date AND A.product_type = 'RAW MAT'
             GROUP BY A.pallet_id, A.receive_qty, A.stock_qty, A.grn, A.project, A.raw_code, A.rm_descript, A.product_type, A.location_name_en, A.pallet_id, A.receive_qty, A.used_qty, A.receive_date, CAST(A.unit_price AS DECIMAL(10, 4)), A.uom
             HAVING COALESCE(SUM(B.picking_qty),0) + (SELECT COALESCE(SUM(qty),0) FROM tbl_inven_transaction_mst AS t_inv WHERE t_inv.pallet_id = A.pallet_id AND t_inv.area = 'IN') - ((SELECT COALESCE(SUM(qty),0) FROM tbl_inven_transaction_mst AS t_inv WHERE t_inv.pallet_id = A.pallet_id AND t_inv.area = 'OUT') - (SELECT COALESCE(SUM(qty),0) FROM tbl_inven_transaction_mst AS t_inv WHERE t_inv.pallet_id = A.pallet_id AND t_inv.area = 'OUT' AND trans_date > :trans_date)) > 0"
        );
        $sql->bindParam(':due_date', $due_date);
        $sql->bindParam(':receive_date', $due_date);
        $sql->bindParam(':trans_date', $due_date);
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $quantity = floatval($result['stock_qty']) + floatval($result['pick_qty']) + floatval($result['after_calculate']);
            $unit = $result['uom'];

            $corner = ['SMID00038','SMID00087','SMID00083','SMID00037'];

            if(in_array($result['raw_code'], $corner)){
                $quantity = number_format(($quantity / 1100) / 1000, 2, '.', '');
                $unit = 'Rolls';
            }

            if($quantity > 0){
                $total_cost = $quantity * $result['unit_price'];
                $sum_pcs += $quantity;
                $sum_cost += $total_cost;

                $sheet->setCellValue("A$i", $result['pallet_id'] . $result['raw_code']);
                $sheet->setCellValue("B$i", $result['raw_code']);
                $sheet->setCellValue("C$i", $result['rm_descript']);
                $sheet->setCellValue("D$i", $result['receive_date']);
                $sheet->setCellValue("E$i", $result['pallet_id']);
                $sheet->setCellValue("F$i", $unit);
                $sheet->setCellValue("G$i", number_format($quantity, 2, '.', ','));
                $sheet->setCellValue("H$i", number_format($result['unit_price'], 4) . ' ');
                $sheet->setCellValue("I$i", number_format($total_cost, 2) . ' ');
                $sheet->setCellValue("J$i", '');

                $sheet->getStyle("A$i:J$i")->applyFromArray($bordered);
                $sheet->getStyle("G$i:I$i")->getAlignment()->setHorizontal('right');

                $i++;
            }
        }
        
        $sheet->setCellValue("G$i", number_format($sum_pcs, 2));
        $sheet->setCellValue("I$i", number_format($sum_cost, 2));

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }


    function Exceeded($db_con, $spreadsheet, $index, $start_date, $end_date, $bordered){
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex($index);
        $sheet = $spreadsheet->getActiveSheet()->setTitle('รายการของแถม');
        $last_col = 'P';
        $i=5;
        $sum_pcs = 0;
        $sum_cost = 0;
        $total_cost = 0;

        SetupSheet($sheet);

        $sheet->mergeCells('A1:P1'); $sheet->setCellValue('A1', 'บริษัทกล่องดวงใจ เมนูแฟคเจอริ่ง จำกัด');
        $sheet->mergeCells('A2:P2'); $sheet->setCellValue('A2', 'รายการรับเข้าวัตถุดิบ ( RAW MATERIAL )');
        $sheet->mergeCells('A3:P3'); $sheet->setCellValue('A3', 'ระหว่างวันที่ ' . date('d/m/Y', strtotime($start_date)) . ' ถึงวันที่ ' . date('d/m/Y', strtotime($end_date)) );
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:A3')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getFont()->setSize(20);

        $sheet->setCellValue('A4', 'Lot + RM Code');
        $sheet->setCellValue('B4', 'วันที่');
        $sheet->setCellValue('C4', 'GRN Number');
        $sheet->setCellValue('D4', 'Pallet ID');
        $sheet->setCellValue('E4', 'Ref# Number');
        $sheet->setCellValue('F4', 'Supplier Name');
        $sheet->setCellValue('G4', 'INV Number');
        $sheet->setCellValue('H4', 'RM Code');
        $sheet->setCellValue('I4', 'Item Descriptions');
        $sheet->setCellValue('J4', 'Area');
        $sheet->setCellValue('K4', 'Transactions Type');
        $sheet->setCellValue('L4', 'Quantity');
        $sheet->setCellValue('M4', 'Unit');
        $sheet->setCellValue('N4', 'Unit Price');
        $sheet->setCellValue('O4', 'Summary');
        $sheet->setCellValue('P4', 'Remarks');

        $sheet->getStyle("A4:P4")->getFont()->setSize(9);
        $sheet->getStyle("A4:P4")->getFont()->setBold(true);
        $sheet->getStyle('A4:P4')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $sheet->getStyle('A4:P4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff362c35');


        $sql = $db_con->prepare(
            "SELECT A.pallet_id, A.qty, A.item_descript, A.area, A.grn, A.trans_type, A.trans_remarks, B.uom, B.receive_date, B.location_name_en, B.rm_type, B.product_type, B.raw_code, CAST(B.unit_price AS DECIMAL(10, 4)) AS unit_price, B.project, C.inv_sup_code, C.inv_sup_name, C.inv_no, C.ref_no
             FROM tbl_inven_transaction_mst AS A
             LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
             LEFT JOIN tbl_invoice_mst AS C ON A.grn = C.grn AND A.inv_no = C.inv_no
             WHERE A.area = 'Movement' AND A.trans_type = 'Receive Exceed Invoice' AND A.trans_date BETWEEN :start_date AND :end_date AND B.product_type = 'RAW MAT'
             ORDER BY B.receive_date, A.pallet_id"
        );
        $sql->bindParam(':start_date', $start_date);
        $sql->bindParam(':end_date', $end_date);
        $sql->execute();

        while($result = $sql->fetch(PDO::FETCH_ASSOC)){
            $quantity = $result['qty'];
            $unit = $result['uom'];

            if($result['raw_code'] == "SMID00038" || $result['raw_code'] == "SMID00087" || $result['raw_code'] == "SMID00037"){
                $quantity = ($quantity / 1100) / 1000;
            }

            $total_cost = $quantity * $result['unit_price'];
            $sum_pcs += $quantity;
            $sum_cost += $total_cost;
            
            $sheet->setCellValue("A$i", $result['raw_code'])
                  ->setCellValue("B$i", $result['receive_date'])
                  ->setCellValue("C$i", $result['grn'])
                  ->setCellValue("D$i", $result['pallet_id'])
                  ->setCellValue("E$i", $result['ref_no'])
                  ->setCellValue("F$i", $result['inv_sup_name'])
                  ->setCellValue("G$i", $result['inv_no'])
                  ->setCellValue("H$i", $result['raw_code'])
                  ->setCellValue("I$i", $result['item_descript'])
                  ->setCellValue("J$i", $result['area'])
                  ->setCellValue("K$i", $result['trans_type'])
                  ->setCellValue("L$i", $result['qty'])
                  ->setCellValue("M$i", $unit)
                  ->setCellValue("N$i", $result['unit_price'] . ' ')
                  ->setCellValue("O$i", number_format($total_cost, 2) . ' ')
                  ->setCellValue("P$i", $result['trans_remarks']);

            $sheet->getStyle("A$i:P$i")->applyFromArray($bordered);
            $sheet->getStyle("L$i:O$i")->getAlignment()->setHorizontal('right');


            $i++;
        }

        $sheet->setCellValue("L$i", number_format($sum_pcs, 2));
        $sheet->setCellValue("O$i", number_format($sum_cost, 2));
        $sheet->getStyle("L$i:O$i")->getAlignment()->setHorizontal('right');


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
    header('Content-Disposition: attachment;filename="Export Accounting RM Stock Report.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

    $writer->save('php://output');
    unset($writer);
?>