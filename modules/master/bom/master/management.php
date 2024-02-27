<?php 
    require_once("../../../../session.php");

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';
    $bom_uniq = isset($_POST['bom_uniq']) ? $_POST['bom_uniq'] : '';
    $fg_type = isset($_POST['fg_type']) ? $_POST['fg_type'] : '';
    $choice = isset($_POST['choice']) ? $_POST['choice'] : '';
    $bom_status = isset($_POST['bom_status']) ? $_POST['bom_status'] : '';
    $bom_project = isset($_POST['bom_project']) ? $_POST['bom_project'] : '';
    
    $fg_codeset = isset($_POST['fg_codeset']) ? $_POST['fg_codeset'] : '';
    $fg_code = isset($_POST['fg_code']) ? $_POST['fg_code'] : '';
    $part_customer = isset($_POST['part_customer']) ? $_POST['part_customer'] : '';
    $comp_code = isset($_POST['comp_code']) ? $_POST['comp_code'] : '';
    $ctn_code_normal = isset($_POST['ctn_code_normal']) ? $_POST['ctn_code_normal'] : '';
    $fg_description = isset($_POST['fg_description']) ? $_POST['fg_description'] : '';
    $sale_type = isset($_POST['sale_type']) ? $_POST['sale_type'] : '';
    $dwg_code = isset($_POST['dwg_code']) ? $_POST['dwg_code'] : '';
    $box_type = isset($_POST['box_type']) ? $_POST['box_type'] : '';
    $cus_code = isset($_POST['cus_code']) ? $_POST['cus_code'] : '';
    $project_type = isset($_POST['project_type']) ? $_POST['project_type'] : '';
    $project = isset($_POST['project']) ? $_POST['project'] : '';
    $fg_w = isset($_POST['fg_w']) ? $_POST['fg_w'] : '';
    $fg_l = isset($_POST['fg_l']) ? $_POST['fg_l'] : '';
    $fg_h = isset($_POST['fg_h']) ? $_POST['fg_h'] : '';
    $fg_ft2 = isset($_POST['fg_ft2']) ? $_POST['fg_ft2'] : '';
    $bom_status = isset($_POST['bom_status']) ? $_POST['bom_status'] : '';

    $pd_usage = isset($_POST['pd_usage']) ? $_POST['pd_usage'] : '';
    $ffmc_usage = isset($_POST['ffmc_usage']) ? $_POST['ffmc_usage'] : '';
    $fg_perpage = isset($_POST['fg_perpage']) ? $_POST['fg_perpage'] : '';
    $wip = isset($_POST['wip']) ? $_POST['wip'] : '';
    $laminate = isset($_POST['laminate']) ? $_POST['laminate'] : '';
    $packing_usage = isset($_POST['packing_usage']) ? $_POST['packing_usage'] : '';
    $moq = isset($_POST['moq']) ? $_POST['moq'] : '';

    $rm_code = isset($_POST['rm_code']) ? $_POST['rm_code'] : '';
    $rm_spec = isset($_POST['rm_spec']) ? $_POST['rm_spec'] : '';
    $rm_flute = isset($_POST['rm_flute']) ? $_POST['rm_flute'] : '';
    $rm_ft2 = isset($_POST['rm_ft2']) ? $_POST['rm_ft2'] : '';
    
    $wms_max = isset($_POST['wms_max']) ? $_POST['wms_max'] : '';
    $wms_min = isset($_POST['wms_min']) ? $_POST['wms_min'] : '';
    $vmi_max = isset($_POST['vmi_max']) ? $_POST['vmi_max'] : '';
    $vmi_min = isset($_POST['vmi_min']) ? $_POST['vmi_min'] : '';
    $vmi_app = isset($_POST['vmi_app']) ? $_POST['vmi_app'] : '';

    
    $cost_rm = isset($_POST['cost_rm']) ? $_POST['cost_rm'] : '';
    $cost_dl = isset($_POST['cost_dl']) ? $_POST['cost_dl'] : '';
    $cost_oh = isset($_POST['cost_oh']) ? $_POST['cost_oh'] : '';
    $cost_total = isset($_POST['cost_total']) ? $_POST['cost_total'] : '';
    $cost_total_oh = isset($_POST['cost_total_oh']) ? $_POST['cost_total_oh'] : '';
    $selling_price = isset($_POST['selling_price']) ? $_POST['selling_price'] : '';

    $machine_type_name = isset($_POST['machine_type_name']) ? $_POST['machine_type_name'] : '';
    $machine_in = isset($_POST['machine_in']) ? $_POST['machine_in'] : '';
    $machine_out = isset($_POST['machine_out']) ? $_POST['machine_out'] : '';

    
    $remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

    $columns = [];
    $json = [];

    if($protocol == "MultipleBomFiltering"){
        $_pem = $mrp_user_code_mst == "GDJ00216" ? "" : "disabled";

        $uscm = $db_con->prepare("SELECT user_cost_access FROM tbl_user WHERE user_code = :user_code");
        $uscm->bindParam(':user_code', $mrp_user_code_mst);
        $uscm->execute();
        $uscmResult = $uscm->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT ROW_NUMBER() OVER(ORDER BY fg_code, bom_status) AS list, ";
        array_push($columns, array('data'=>'list', 'title'=> 'list'));
        array_push($columns, array('data'=>'actions', 'title'=> 'actions'));
        foreach($choice as $id=>$item){ 
            $sql .= "CAST($item AS NVARCHAR) AS $item, ";
            
            array_push($columns, array('data'=>$item, 'title'=> $item));
        }
        array_push($columns, array('data'=>'create_datetime', 'title'=> 'create_datetime'));
        array_push($columns, array('data'=>'create_by', 'title'=> 'create_by'));

        $sql .= "class_color, class_txt_color, FORMAT(A.create_datetime, 'dd/MM/yyyy HH:mm:ss') AS create_datetime, A.create_by FROM tbl_bom_mst AS A LEFT JOIN tbl_supplier_mst AS B ON A.sup_code = B.sup_code LEFT JOIN tbl_status_color AS C ON A.bom_status = C.hex_status WHERE bom_uniq IS NOT NULL";
        $sql .= $bom_status != "" ? " AND bom_status = '$bom_status'" : '';
        $sql .= $bom_project != "" ? " AND project = '$bom_project'" : '';
        $sql .= " ORDER BY fg_codeset, fg_code, project_type, project, cus_code, comp_code, A.create_datetime";

        $list = $db_con->query($sql);

        while($listResult = $list->fetch(PDO::FETCH_ASSOC)){
            $btnActions = '<input type="checkbox" id="'.$listResult['bom_uniq'].'" name="CheckList[]" class="me-2">' .
                          '<button '.$_pem.' onclick="OpenViewDetail(\'#load_view_detail\', \'load_bom_details\', \''.$listResult['bom_uniq'].'\')" class="btn badge bg-gradient-blue text-white fw-600">Update</button>' .
                          '<button '.$_pem.' onclick="OpenViewDetail(\'#load_view_detail\', \'load_master_process\', \''.$listResult['bom_uniq'].'\')" class="btn badge bg-gradient-yellow text-dark fw-600 ms-2">Machine</button>' .
                          '<button onclick="" class="btn badge bg-gradient-dark text-white fw-600 ms-2">DWG</button>' .
                          '<button onclick="" class="btn badge bg-gradient-red text-dark fw-600 ms-2">Transfer</button>';
            $listResult['actions'] = $btnActions;
            $listResult['bom_status'] = '<span class="badge '.$listResult['class_color'].' '.$listResult['class_txt_color'].'">'.$listResult['bom_status'].'</span>';

            //***************** CHeck permission access cost and selling price ********************//
            //************************************************************************************ //
            if($uscmResult['user_cost_access'] == 0){
                $listResult['cost_rm'] = '';
                $listResult['cost_dl'] = '';
                $listResult['cost_oh'] = '';
                $listResult['cost_total'] = '';
                $listResult['cost_total_oh'] = '';
                $listResult['selling_price'] = '';
            }else{
                $listResult['cost_rm'] = number_format($listResult['cost_rm'], 2);
                $listResult['cost_dl'] = number_format($listResult['cost_dl'], 2);
                $listResult['cost_oh'] = number_format($listResult['cost_oh'], 2);
                $listResult['cost_total'] = number_format($listResult['cost_total'], 2);
                $listResult['cost_total_oh'] = number_format($listResult['cost_total_oh'], 2);
                $listResult['selling_price'] = number_format($listResult['selling_price'], 2);
            }

            array_push($json, $listResult);
        }

        echo json_encode(array('code'=>200, 'message'=>'ok', 'datas'=>$json, 'column'=> $columns));
        $db_con = null;
        return;
    }else if($protocol == "MasterBOMSet"){
        try {
            $list = $db_con->query(
                "SELECT ROW_NUMBER() OVER(ORDER BY set_uniq DESC) AS list, A.*, B.class_txt_color, B.class_color
                 FROM tbl_bom_set_mst AS A 
                 LEFT JOIN tbl_status_color AS B ON A.set_status = B.hex_status
                 ORDER BY set_uniq DESC"
            );

            echo json_encode(array('code'=>200, 'datas'=>$list->fetchAll(PDO::FETCH_ASSOC)));
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateBOMDetails"){
        try {
            $list = $db_con->prepare(
                "UPDATE tbl_bom_mst
                 SET ctn_code_normal = :ctn_code_normal,
                     fg_description = :fg_description,
                     project_type = :project_type,
                     sale_type = :sale_type,
                     dwg_code = :dwg_code,
                     box_type = :box_type,
                     fg_type = :fg_type,
                     fg_size_width = :fg_w,
                     fg_size_long = :fg_l,
                     fg_size_height = :fg_h,
                     fg_ft2 = :fg_ft2,
                     rm_code = :rm_code,
                     rm_spec = :rm_spec,
                     rm_flute = :rm_flute,
                     pd_usage = :pd_usage,
                     ffmc_usage = :ffmc_usage,
                     fg_perpage = :fg_perpage,
                     wip = :wip,
                     laminate = :laminate,
                     packing_usage = :packing_usage,
                     moq = :moq,
                     wms_max = :wms_max,
                     wms_min = :wms_min,
                     vmi_max = :vmi_max,
                     vmi_min = :vmi_min,
                     vmi_app = :vmi_app,
                     cost_rm = :cost_rm,
                     cost_dl = :cost_dl,
                     cost_oh = :cost_oh,
                     cost_total = :cost_total,
                     cost_total_oh = :cost_total_oh,
                     selling_price = :selling_price,
                     bom_status = :bom_status,
                     update_datetime = :update_datetime,
                     update_by = :update_by
                 WHERE bom_uniq = :bom_uniq"
            );
            $list->bindParam(':ctn_code_normal', $ctn_code_normal);
            $list->bindParam(':fg_description', $fg_description);
            $list->bindParam(':project_type', $project_type);
            $list->bindParam(':sale_type', $sale_type);
            $list->bindParam(':dwg_code', $dwg_code);
            $list->bindParam(':box_type', $box_type);
            $list->bindParam(':fg_type', $fg_type);
            $list->bindParam(':fg_w', $fg_w);
            $list->bindParam(':fg_l', $fg_l);
            $list->bindParam(':fg_h', $fg_h);
            $list->bindParam(':fg_ft2', $fg_ft2);
            $list->bindParam(':rm_code', $rm_code);
            $list->bindParam(':rm_spec', $rm_spec);
            $list->bindParam(':rm_flute', $rm_flute);
            $list->bindParam(':pd_usage', $pd_usage);
            $list->bindParam(':ffmc_usage', $ffmc_usage);
            $list->bindParam(':fg_perpage', $fg_perpage);
            $list->bindParam(':wip', $wip);
            $list->bindParam(':laminate', $laminate);
            $list->bindParam(':packing_usage', $packing_usage);
            $list->bindParam(':moq', $moq);
            $list->bindParam(':wms_max', $wms_max);
            $list->bindParam(':wms_min', $wms_min);
            $list->bindParam(':vmi_max', $vmi_max);
            $list->bindParam(':vmi_min', $vmi_min);
            $list->bindParam(':vmi_app', $vmi_app);
            $list->bindParam(':cost_rm', $cost_rm);
            $list->bindParam(':cost_dl', $cost_dl);
            $list->bindParam(':cost_oh', $cost_oh);
            $list->bindParam(':cost_total', $cost_total);
            $list->bindParam(':cost_total_oh', $cost_total_oh);
            $list->bindParam(':selling_price', $selling_price);
            $list->bindParam(':bom_status', $bom_status);
            $list->bindParam(':update_datetime', $buffer_datetime);
            $list->bindParam(':update_by', $mrp_user_name_mst);
            $list->bindParam(':bom_uniq', $bom_uniq);
            $list->execute();

            $vmList = $vmi_con->prepare(
                "UPDATE tbl_bom_mst
                 SET bom_pj_type = :project_type,
                     bom_ctn_code_normal = :ctn_code_normal,
                     bom_fg_type = :fg_type,
                     bom_usage = :ffmc_usage,
                     bom_dims_w = :fg_w,
                     bom_dims_l = :fg_l,
                     bom_dims_h = :fg_h,
                     bom_wms_max = :wms_max,
                     bom_wms_min = :wms_min,
                     bom_vmi_max = :vmi_max,
                     bom_vmi_min = :vmi_min,
                     bom_vmi_app = :vmi_app,
                     bom_space_paper = :rm_spec,
                     bom_flute = :rm_flute,
                     bom_packing = :packing_usage,
                     bom_cost = :cost_total,
                     bom_cost_per_pcs = :cost_total_oh,
                     bom_price_sale_per_pcs = :selling_price,
                     bom_status = :bom_status,
                     bom_update_datetime = :update_datetime,
                     bom_update_by = :update_by
                 WHERE bom_uniq = :bom_uniq"
            );
            $vmList->bindParam(':project_type', $project_type);
            $vmList->bindParam(':ctn_code_normal', $ctn_code_normal);
            $vmList->bindParam(':fg_type', $fg_type);
            $vmList->bindParam(':ffmc_usage', $ffmc_usage);
            $vmList->bindParam(':fg_w', $fg_w);
            $vmList->bindParam(':fg_l', $fg_l);
            $vmList->bindParam(':fg_h', $fg_h);
            $vmList->bindParam(':wms_max', $wms_max);
            $vmList->bindParam(':wms_min', $wms_min);
            $vmList->bindParam(':vmi_max', $vmi_max);
            $vmList->bindParam(':vmi_min', $vmi_min);
            $vmList->bindParam(':vmi_app', $vmi_app);
            $vmList->bindParam(':rm_spec', $rm_spec);
            $vmList->bindParam(':rm_flute', $rm_flute);
            $vmList->bindParam(':packing_usage', $packing_usage);
            $vmList->bindParam(':cost_total', $cost_total);
            $vmList->bindParam(':cost_total_oh', $cost_total_oh);
            $vmList->bindParam(':selling_price', $selling_price);
            $vmList->bindParam(':bom_status', $bom_status);
            $vmList->bindParam(':update_datetime', $buffer_datetime);
            $vmList->bindParam(':update_by', $mrp_user_name_mst);
            $vmList->bindParam(':bom_uniq', $bom_uniq);
            $vmList->execute();

            echo json_encode(array('code'=>200, 'message'=>'อัพเดทข้อมูลสำเร็จ'));
            $db_con->commit();
            $vmi_con->commit();
            $db_con = null;
            $vmi_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            $vmi_con = null;
            return;
        }
    }else if($protocol == "UpdateBOMProcess"){
        try {
            if(end($machine_type_name) != 'TG'){
                echo json_encode(array('code'=>400, 'message'=>'เครื่องจักรเครื่องสุดท้ายไม่ใช่เครื่องมัด ไม่สามารถดำเนินการได้'));
                return;
            }

            $vc = array_count_values($machine_type_name);
            $vc_max = max($vc);
            if($vc_max > 1){
                echo json_encode(array('code'=>400, 'message'=>'มีเครื่องจักรประเภทเดียวมากกว่า 1 รายการ ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง'));
                return;
            }

            $master_label = '';
            $master_process = [];
            foreach($machine_type_name as $id => $machine_name){
                $mlist = $db_con->prepare("SELECT machine_type_name FROM tbl_machine_type_mst WHERE machine_type_code = :machine_type_code");
                $mlist->bindParam(':machine_type_code', $machine_name);
                $mlist->execute();
                $mlistResult = $mlist->fetch(PDO::FETCH_ASSOC);

                $order = $id + 1;
                $master_label .= $order . "." . $mlistResult['machine_type_name'] . " >>> ";


                array_push($master_process, array(
                    'order' => $order,
                    'machine_code' => $machine_name,
                    'in' => $machine_in[$id],
                    'out' => $machine_out[$id]
                ));
            }

            $up = $db_con->prepare("UPDATE tbl_bom_mst SET machine_order = :machine_order, machine_mp = :machine_mp, update_datetime = :update_datetime, update_by = :update_by WHERE bom_uniq = :bom_uniq");
            $up->bindParam(':machine_order', json_encode($master_process));
            $up->bindParam(':machine_mp', $master_label);
            $up->bindParam(':update_datetime', $buffer_datetime);
            $up->bindParam(':update_by', $mrp_user_name_mst);
            $up->bindParam(':bom_uniq', $bom_uniq);
            $up->execute();

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการบันทึกข้อมูล Master Process สำเร็จ'));
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถดำเนินการได้ ' . $e->getMessage()));
            $db_con = null;
            return;
        }
    }
?>