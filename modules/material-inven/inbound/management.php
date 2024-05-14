<?php
    require_once("../../../session.php");
    require_once("../t_inv_tns.php");

    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';
    $po_no = isset($_POST['po_no']) ? $_POST['po_no'] : '';
    $inv_no = isset($_POST['inv_no']) ? $_POST['inv_no'] : '';
    $sup_name = isset($_POST['sup_name']) ? $_POST['sup_name'] : '';
    $work_type = isset($_POST['work_type']) ? $_POST['work_type'] : '';
    $item_code = isset($_POST['item_code']) ? $_POST['item_code'] : '';
    $choose_color = isset($_POST['choose_color']) ? $_POST['choose_color'] : '';
    $quantity = isset($_POST['quantity']) ? str_replace(",", "", $_POST['quantity']) : '';
    $comp_item = isset($_POST['comp_item']) ? str_replace(",", "", $_POST['comp_item']) : '';
    $unit_price = isset($_POST['unit_price']) ? $_POST['unit_price'] : '';
    $summary = isset($_POST['summary']) ? str_replace(",", "", $_POST['summary']) : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $grn_no = '';

    $json = [];

    if($protocol == "POsDetails"){
        try {
            if($type == "POsList"){
                $list = $pu_con->query(
                    "SELECT po_no, vendor_code, vendor_name, credit_term, total, vat, summary_budget, purchase_remark, period
                     FROM tbl_purchase_order
                     WHERE vendor_code IN('SDCP','SSTP','SLFB','SSSP','SIFC','SIEC','SPAN','SSLT') AND po_type = 'refer' AND receipt = 'none'
                     ORDER BY po_no DESC"
                );
                while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                    $listResult['purchase_remark'] = nl2br($listResult['purchase_remark']);
                    $listResult['route'] = 'load_receive_rm';
                    $listResult['post_type'] = 'RAW MAT';
                    array_push($json, $listResult);
                }

                $sublist = $pu_con->query(
                    "SELECT A.po_no, A.pr_ref, C.post_type
                     FROM tbl_po_detail AS A
                     LEFT JOIN tbl_purchase_order AS B ON A.po_no = B.po_no
                     LEFT JOIN tbl_pr_detail_path AS C ON A.pr_ref = C.pr_no
                     WHERE C.post_from = 'MRP' AND C.post_type IN('CORNER','SUB MAT') AND B.receipt IN('none','split')
                     GROUP BY A.po_no, A.pr_ref, C.post_Type"
                );
                while($subResult = $sublist->fetch(PDO::FETCH_ASSOC)){
                    $subdet = $pu_con->prepare(
                        "SELECT po_no, vendor_code, vendor_name, credit_term, total, vat, summary_budget, purchase_remark, period
                         FROM tbl_purchase_order
                         WHERE po_no = :po_no AND now_in != 'disabled'"
                    );
                    $subdet->bindParam(':po_no', $subResult['po_no']);
                    $subdet->execute();
                    while($subdetResult = $subdet->fetch(PDO::FETCH_ASSOC)){
                        $subdetResult['route'] = $subResult['post_type'] == 'CORNER' ? 'load_receive_corner' : 'load_receive_sm';
                        $listResult['purchase_remark'] = nl2br($listResult['purchase_remark']);
                        $subdetResult['post_type'] = $subResult['post_type'];
                        array_push($json, $subdetResult);
                    }
                }
            }else if($type == "POsListItem"){
                $list = $pu_con->query("SELECT item_code, request_for FROM tbl_po_detail WHERE po_no = '$po_no'");
                $json = $list->fetchAll(PDO::FETCH_ASSOC);
            }else if($type == "POsItemDetails"){
                $list = $pu_con->query("SELECT material_unitprice, CAST(material_qty_mrp_path AS INT) AS material_qty_mrp_path, material_summary_mrp_path FROM tbl_po_detail WHERE po_no = '$po_no' AND item_code = '$item_code'");
                $json = $list->fetchAll(PDO::FETCH_ASSOC);
            }

            echo json_encode(array('code'=>200, 'datas'=>$json));
            $pu_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการแสดงผลข้อมูล POs ได้ ' . $e->getMessage()));
            $pu_con = null;
            return;
        }
    }else if($protocol == "ReceiveRawMaterial"){
        try {
            $prefix = 'GRN' . date('ymd', strtotime($buffer_datetime));

            $sup = $db_con->prepare("SELECT sup_code, sup_name_en FROM tbl_supplier_mst WHERE sup_code = :sup_code");
            $sup->bindParam(':sup_code', $sup_code);
            $sup->execute();
            $supResult = $sup->fetch(PDO::FETCH_ASSOC);

            $invList = $db_con->prepare("SELECT grn FROM tbl_invoice_mst WHERE inv_no = :inv_no AND ref_no = :ref_no");
            $invList->bindParam(':inv_no', $inv_no);
            $invList->bindParam(':ref_no', $po_no);
            $invList->execute();
            $invResult = $invList->fetch(PDO::FETCH_ASSOC);
            if($invResult['grn'] == ''){
                $gnList = $db_con->query("SELECT COUNT(grn) AS count_grn FROM tbl_invoice_mst WHERE grn LIKE '%$prefix%'");
                $gnResult = $gnList->fetch(PDO::FETCH_ASSOC);

                $grn_no = $prefix . PadNumber($gnResult['count_grn'] + 1, 4);

                $newiv = $db_con->prepare("INSERT INTO tbl_invoice_mst(grn, inv_no, inv_type, ref_no, receipt_status, receive_datetime, receive_by, inv_sup_code, inv_sup_name) VALUES(:grn, :inv_no, :inv_type, :ref_no, 'Received', :receive_datetime, :receive_by, :inv_sup_code, :inv_sup_name)");
                $newiv->bindParam(':grn', $grn_no);
                $newiv->bindParam(':inv_no', $inv_no);
                $newiv->bindParam(':inv_type', $work_type);
                $newiv->bindParam(':ref_no', $po_no);
                $newiv->bindParam(':receive_datetime', $buffer_datetime);
                $newiv->bindParam(':receive_by', $mrp_user_name_mst);
                $newiv->bindParam(':inv_sup_code', $supResult['sup_code']);
                $newiv->bindParam(':inv_sup_name', $supResult['sup_name_en']);
                $newiv->execute();
            }else{
                $grn_no = $invResult['grn'];
            }


            $det = $pu_con->prepare(
                "SELECT alt_material_name, alt_project, alt_material_type, A.request_for, A.material_unit
                 FROM tbl_po_detail AS A
                 INNER JOIN tbl_pr_detail_path AS B ON A.po_no = B.po_ref AND A.item_code = B.po_ref_item_code
                 WHERE po_no = :po_no AND A.item_code = :item_code"
            );
            $det->bindParam(':po_no', $po_no);
            $det->bindParam(':item_code', $item_code);
            $det->execute();
            $detResult = $det->fetch(PDO::FETCH_ASSOC);

            $up = $pu_con->prepare(
                "UPDATE tbl_po_detail
                 SET material_qty_mrp_path -= $quantity,
                     material_summary_mrp_path -= $summary,
                     receipt_status = CASE WHEN material_qty_mrp_path - $quantity = 0 THEN 'Received' ELSE receipt_status END
                 WHERE po_no = :po_no AND item_code = :item_code"
            );
            $up->bindParam(':po_no', $po_no);
            $up->bindParam(':item_code', $item_code);
            $up->execute();

            $prefix = 'PLID' . date('ymd', strtotime($buffer_datetime));
            
            $plc = $db_con->prepare("SELECT COUNT(pallet_id) AS count_pallet FROM tbl_stock_inven_mst WHERE pallet_id LIKE '%$prefix%'");
            $plc->execute();
            $plcResult = $plc->fetch(PDO::FETCH_ASSOC);

            $pallet_id = $prefix . PadNumber($plcResult['count_pallet'] + 1, 4);
            $alt_descript = str_replace($detResult['alt_material_name'] . " ", "", $detResult['request_for']);
            


            $inv = $db_con->prepare(
                "INSERT INTO tbl_stock_inven_mst(grn, project, raw_code, rm_descript, product_type, pallet_id, area, receive_qty, stock_qty, uom, receive_date, receive_time, receive_by, unit_price, rm_type, rm_color)
                 VALUES(:grn, :project, :raw_code, :rm_descript, :product_type, :pallet_id, 'Receive', :receive_qty, :stock_qty, :uom, :receive_date, :receive_time, :receive_by, :unit_price, :rm_type, :rm_color)");
            $inv->bindParam(':grn', $grn_no);
            $inv->bindParam(':project', $detResult['alt_project']);
            $inv->bindParam(':raw_code', $detResult['alt_material_name']);
            $inv->bindParam(':rm_descript', $alt_descript);
            $inv->bindParam(':product_type', $work_type);
            $inv->bindParam(':pallet_id', $pallet_id);
            $inv->bindParam(':receive_qty', intval($quantity + $comp_item));
            $inv->bindParam(':stock_qty', intval($quantity + $comp_item));
            $inv->bindParam(':uom', $detResult['material_unit']);
            $inv->bindParam(':receive_date', $buffer_date);
            $inv->bindParam(':receive_time', $buffer_time);
            $inv->bindParam(':receive_by', $mrp_user_name_mst);
            $inv->bindParam(':unit_price', $unit_price);
            $inv->bindParam(':rm_type', $detResult['alt_material_type']);
            $inv->bindParam(':rm_color', $choose_color);
            $inv->execute(); 


            $tck = array(
                'pallet_id' => $pallet_id,
                'pr_no' => $pr_no,
                'inv_no' => $inv_no,
                'grn'=> $grn_no,
                'item_descript' => $alt_descript,
                'product_type' => $work_type,
                'category_type' => 'Raw Materials',
                'qty' => $quantity,
                'unit' => $detResult['material_unit'],
                'area' => 'Movement',
                'trans_type' => 'Receive Raw Material',
                'trans_by' => $mrp_user_name_mst,
                'trans_date' => $buffer_date,
                'trans_time' => $buffer_time,
                'rm_type' => $rm_type,
                'rm_color' => $rm_color,
                'trans_remarks' => "Receive Raw Materials from $po_no\n\rInvoice Number: $inv_no\n\rRemarks : $remarks",
                'comp_qty' => $comp_item
            );
            $trans = InsertTransactions($db_con, $dest);
            if(!$trans){
                echo json_encode(array('code'=>'400', 'message'=>'ไม่สามารถบันทึกข้อมูล Receive Raw Mateirals ได้ ' . GetErrorMessage($db_con)));
                sqlsrv_close($db_pur_con);
                sqlsrv_close($db_con);
                return;
            }

            $tck = array(
                'pallet_id' => $pallet_id,
                'pr_no' => $pr_no,
                'inv_no' => $inv_no,
                'grn'=> $grn_no,
                'item_descript' => $alt_descript,
                'product_type' => $work_type,
                'category_type' => 'Raw Materials',
                'qty' => $comp_item,
                'unit' => $detResult['material_unit'],
                'area' => 'Movement',
                'trans_type' => 'Receive Exceed Invoice',
                'trans_by' => $mrp_user_name_mst,
                'trans_date' => $buffer_date,
                'trans_time' => $buffer_time,
                'rm_type' => $rm_type,
                'rm_color' => $rm_color,
                'trans_remarks' => "Receive Exceed Invoice from $po_no\n\rInvoice Number: $inv_no\n\rRemarks : $remarks",
            );
            $trans = InsertTransactions($db_con, $dest);
            if(!$trans){
                echo json_encode(array('code'=>'400', 'message'=>'ไม่สามารถบันทึกข้อมูล Receive Raw Mateirals ได้ ' . GetErrorMessage($db_con)));
                sqlsrv_close($db_pur_con);
                sqlsrv_close($db_con);
                return;
            }


            echo 'done';
            $pu_con = null;
            $db_con = null;
            return;

                //todo >>>> MARKS:: Update requisition set close receipt
                //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                $list = "SELECT po_no FROM tbl_po_detail WHERE po_no = '$po_no' AND receipt_status IS NULL";
                // $list = "SELECT pr_no FROM tbl_pr_detail_path WHERE pr_no = '$pr_no' AND LOWER(case_status) != 'received'";
                $listQuery = sqlsrv_query($db_pur_con, $list, array(), array("Scrollable"=>"static"));

                if(sqlsrv_num_rows($listQuery) == 0){
                    $smp = "UPDATE tbl_purchase_order SET receipt = 'Receive' WHERE po_no = '$po_no'";
                    $smpQuery = sqlsrv_query($db_pur_con, $smp);
                    if(!$smpQuery){ 
                        echo json_encode(array('code'=>'400', 'message'=>'ไม่สามารถ Stamp Receipt บนระบบจัดซื้อได้ ' . GetErrorMessage($db_pur_con)));
                        sqlsrv_close($db_pur_con);
                        sqlsrv_close($db_con);
                        return;
                    }
                }

                

                echo json_encode(array('code'=>'200', 'message'=>"บันทึกข้อมูลรับเข้า Invoice number $inv_no สำเร็จ! GRN เลขที่ $grn", 'grn'=>$grn));
                sqlsrv_commit($db_pur_con);
                sqlsrv_commit($db_con);
                sqlsrv_close($db_pur_con);
                sqlsrv_close($db_con);
                return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารประมวลผลได้ ' . $e->getMessage()));
            $db_con = null;
            $pu_con = null;
            return;
        }
    }
?>