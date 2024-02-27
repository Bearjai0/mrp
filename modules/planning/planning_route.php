<?php
    require_once("../../session.php");

    $protocol = isset($_POST['protocol'] ) ? $_POST['protocol'] : '';
    $job_no = isset($_POST['job_no'] ) ? $_POST['job_no'] : '';
    $rm_code = isset($_POST['rm_code'] ) ? $_POST['rm_code'] : '';
    $rm_type = isset($_POST['rm_type'] ) ? $_POST['rm_type'] : '';
    $rm_usage_qty = isset($_POST['rm_usage_qty']) ? str_replace(",", "", $_POST['rm_usage_qty']) : '';

    $push = array();
    $json = array();

    if($protocol == "StockRM"){
        try {
            $concast = $rm_type == "" ? "" : " AND rm_type = '$rm_type'";
            $list = $db_con->prepare("SELECT CAST(SUM(stock_qty) AS INT) AS stock_qty FROM tbl_stock_inven_mst WHERE raw_code = :rm_code $concast");
            $list->bindParam(':rm_code', $rm_code);
            $list->execute();

            echo json_encode(array('code'=>200, 'datas'=>$list->fetch(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->GetMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "PalletReserveList"){
        try {
            if($rm_usage_qty > 0){
                $concast = $rm_type == "" ? "" : " AND rm_type = '$rm_type'";
                $list = $db_con->query(
                    "SELECT A.*, B.rm_type, B.rm_color
                     FROM tbl_picking_item_mst AS A 
                     LEFT JOIN tbl_stock_inven_mst AS B ON A.pallet_id = B.pallet_id
                     WHERE picking_job_no = '$job_no' AND picking_rm_code = '$rm_code' AND picking_status = 'reserve' $concast
                     ORDER BY B.pallet_id"
                );
                while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
                    $rm_usage_qty -= $listResult['picking_qty'];
                    array_push($push, array(
                        'inven_uniq' => $listResult['inven_uniq'],
                        'raw_code' => $listResult['picking_rm_code'],
                        'pallet_id' => $listResult['pallet_id'],
                        'location_name_en' => $listResult['pick_location'],
                        'rm_descript' => $listResult['item_descript'],
                        'qty' => $listResult['picking_qty'],
                        'rm_color' => $listResult['rm_color'],
                        'rm_type' => $listResult['rm_type']
                    ));

                    if($rm_usage_qty <= 0){
                        $rm_usage_qty = 0;
                        break;
                    }
                }

                $stList = $db_con->query("SELECT * FROM tbl_stock_inven_mst WHERE raw_code = '$rm_code' AND area = 'Storage' AND stock_qty > 0 $concast ORDER BY receive_date, receive_time");
                while($stListResult = $stList->fetch(PDO::FETCH_ASSOC)){
                    if($stListResult['stock_qty'] <= $rm_usage_qty){
                        array_push($push, array(
                            'inven_uniq' => $stListResult['inven_uniq'],
                            'raw_code' => $stListResult['raw_code'],
                            'pallet_id' => $stListResult['pallet_id'],
                            'location_name_en' => $stListResult['location_name_en'],
                            'rm_descript' => $stListResult['rm_descript'],
                            'qty' => $stListResult['stock_qty'],
                            'current' => $stListResult['stock_qty'],
                            'rm_color' => $stListResult['rm_color'],
                            'rm_type' => $stListResult['rm_type']
                        ));
                        $rm_usage_qty -= $stListResult['stock_qty'];
                    }else if($rm_usage_qty != 0){
                        array_push($push, array(
                            'inven_uniq' => $stListResult['inven_uniq'],
                            'raw_code' => $stListResult['raw_code'],
                            'pallet_id' => $stListResult['pallet_id'],
                            'location_name_en' => $stListResult['location_name_en'],
                            'rm_descript' => $stListResult['rm_descript'],
                            'qty' => $rm_usage_qty,
                            'current' => $stListResult['stock_qty'],
                            'rm_color' => $stListResult['rm_color'],
                            'rm_type' => $stListResult['rm_type']
                        ));
                        $rm_usage_qty = 0;
                    }
                }

                $json = array('code'=>200, 'datas'=>$push);
            }else{
                $json = array('code'=>2001, 'datas'=>[]);
            }

            echo json_encode($json);
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->GetMessage()));
            $db_con = null;
            return;
        }

        echo json_encode($json);
        sqlsrv_close($db_con);
        return;
    }
?>