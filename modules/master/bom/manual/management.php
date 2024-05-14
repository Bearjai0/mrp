<?php
    require_once("../../../../session.php");
    require_once("../../../../../library/PHPSpreadSheet/vendor/autoload.php");
    require_once('../../../../../library/PHPMailer/class.phpmailer.php');
    require_once("../../../../../library/PHPMailer/sender.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);
    
    $protocol = isset($_POST['protocol']) ? $_POST['protocol'] : '';

    $column_list = ['No#','BOM Uniq','sale_type','fg_codeset','fg_code','part_customer','ctn_code_normal','comp_code','fg_description','package_code','cus_type','cus_code','project','project_type','dwg_code','dwg_ref','package_type','box_type','fg_size_width','fg_size_long','fg_size_height','fg_ft2','pd_usage','ffmc_usage','fg_perpage','wip','FG Usage','packing_usage','fg_type','rm_code','rm_spec','rm_flute','rm_w','rm_l','rm_ft2','rm_moq_min','machine_order','machine_mp','snp','moq','vmi_app','ship_to_type','wms_max','wms_min','vmi_max','vmi_min','bom_status','sup_code','cost_rm','cost_dl','cost_oh','cost_total','cost_total_oh','selling_price','production_time','bom_group_set'];

    if($protocol == "UploadBOM"){
        try {
            $upfile = isset($_POST['upfile']) ? $_POST['upfile'] : '';

            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow();

            $fg_type_check = ['SET','COMPONENT'];
            $fac_type_check = ['FG','CORNER','TRADING'];

            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq           = trim($data->getCell("B$i")->getValue());
                $sale_type          = trim($data->getCell("C$i")->getValue());
                $fg_codeset         = trim($data->getCell("D$i")->getValue());
                $fg_code            = trim($data->getCell("E$i")->getValue());
                $part_customer      = trim($data->getCell("F$i")->getValue());
                $ctn_code_normal    = trim($data->getCell("G$i")->getValue());
                $comp_code          = trim($data->getCell("H$i")->getValue());
                $fg_description     = trim($data->getCell("I$i")->getValue());
                $package_code       = trim($data->getCell("J$i")->getValue());
                $cus_type           = trim($data->getCell("K$i")->getValue());
                $cus_code           = trim($data->getCell("L$i")->getValue());
                $project            = trim($data->getCell("M$i")->getValue());
                $project_type       = trim($data->getCell("N$i")->getValue());
                $dwg_code           = trim($data->getCell("O$i")->getValue());
                $dwg_ref            = trim($data->getCell("P$i")->getValue());
                $package_type       = trim($data->getCell("Q$i")->getValue());
                $box_type           = trim($data->getCell("R$i")->getValue());
                $fg_w               = floatval(trim($data->getCell("S$i")->getValue()));
                $fg_l               = floatval(trim($data->getCell("T$i")->getValue()));
                $fg_h               = floatval(trim($data->getCell("U$i")->getValue()));
                $fg_ft2             = floatval(trim($data->getCell("V$i")->getValue()));
                $pd_usage           = intval(trim($data->getCell("W$i")->getValue()));
                $ffmc_usage         = intval(trim($data->getCell("X$i")->getValue()));
                $fg_perpage         = intval(trim($data->getCell("Y$i")->getValue()));
                $wip                = intval(trim($data->getCell("Z$i")->getValue()));
                $fg_usage           = intval(trim($data->getCell("AA$i")->getValue()));
                $packing_usage      = intval(trim($data->getCell("AB$i")->getValue()));
                $fg_type            = trim($data->getCell("AC$i")->getValue());
                $fac_type           = trim($data->getCell("AD$i")->getValue());
                $rm_code            = trim($data->getCell("AE$i")->getValue());
                $rm_spec            = trim($data->getCell("AF$i")->getValue());
                $rm_flute           = trim($data->getCell("AG$i")->getValue());
                $rm_w               = floatval(trim($data->getCell("AH$i")->getValue()));
                $rm_l               = floatval(trim($data->getCell("AI$i")->getValue()));
                $rm_ft2             = floatval(trim($data->getCell("AJ$i")->getValue()));
                $rm_moq_min         = intval(trim($data->getCell("AK$i")->getValue()));
                $machine_order      = trim($data->getCell("AL$i")->getValue());
                $machine_mp         = trim($data->getCell("AM$i")->getValue());
                $snp                = intval(trim($data->getCell("AN$i")->getValue()));
                $moq                = intval(trim($data->getCell("AO$i")->getValue()));
                $vmi_app            = trim($data->getCell("AP$i")->getValue());
                $ship_to_type       = trim($data->getCell("AQ$i")->getValue());
                $wms_max            = intval(trim($data->getCell("AR$i")->getValue()));
                $wms_min            = intval(trim($data->getCell("AS$i")->getValue()));
                $vmi_max            = intval(trim($data->getCell("AT$i")->getValue()));
                $vmi_min            = intval(trim($data->getCell("AU$i")->getValue()));
                $bom_status         = trim($data->getCell("AV$i")->getValue());
                $sup_code           = trim($data->getCell("AW$i")->getValue());
                $cost_rm            = floatval(trim($data->getCell("AX$i")->getValue()));
                $cost_dl            = floatval(trim($data->getCell("AY$i")->getValue()));
                $cost_oh            = floatval(trim($data->getCell("AZ$i")->getValue()));
                $cost_total         = floatval(trim($data->getCell("BA$i")->getValue()));
                $cost_total_oh      = floatval(trim($data->getCell("BB$i")->getValue()));
                $selling_price      = floatval(trim($data->getCell("BC$i")->getValue()));
                $production_time    = floatval(trim($data->getCell("BD$i")->getValue()));
                $bom_group_set      = trim($data->getCell("BE$i")->getValue());

                if(checkParamType($bom_uniq, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล BOM Uniq ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($sale_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Sale Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_codeset, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Codeset ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($part_customer, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Part customer ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($ctn_code_normal, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล CTN Code Normal ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($comp_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Component Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_description, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Description ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($package_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Package Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cus_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Customer Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cus_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Customer Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($project, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Project ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($project_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Project Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($dwg_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Drawing Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($dwg_ref, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Drawing Ref# ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($package_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Package Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($box_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Box Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_w, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG W ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_l, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG L ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_h, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG H ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_ft2, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Ft2 ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($pd_usage, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล PD Usage รายการที่ $i ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($ffmc_usage, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FFMC Usage ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_perpage, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Perpage ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($wip, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล WIP ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_usage, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Usage ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($packing_usage, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Packing Usage ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fg_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FG Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($fac_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล FAC Type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล RM Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_spec, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล RM Spec ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_flute, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล RM Flute ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_w, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Packing Usage ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_l, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Packing Usage ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_ft2, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Packing Usage ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($rm_moq_min, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล RM MOQ Min ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($machine_order, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Machine Order ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($machine_mp, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Machine MP ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($snp, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล SNP ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($moq, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล MOQ ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($vmi_app, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล VMI App ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($ship_to_type, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Ship to type ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($wms_max, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล WMS MAX ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($wms_min, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล WMS MIN ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($vmi_max, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล VMI MAX ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($vmi_min, 'integer')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล VMI MIN ไม่ใช่ Integer ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($bom_status, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล BOM Status ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($sup_code, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Supplier Code ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cost_rm, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Cost RM ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cost_dl, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Cost DL ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cost_oh, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Cost OH ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cost_total, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Cost Total ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($cost_total_oh, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Cost Total + OH ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($selling_price, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Selling Price ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($production_time, 'double')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Production Time ไม่ใช่ Double ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }
                if(checkParamType($bom_group_set, 'string')){ echo json_encode(array('code'=>400, 'message'=>"ประเภทของข้อมูล Group Set ไม่ใช่ String ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง")); $db_con = null; $vmi_con = null; return; }

                $rm_w_mm = $rm_w * 25.4;
                $rm_l_mm = $rm_l * 25.4;

                if($fg_code == ""){
                    $in = $db_con->prepare(
                        "INSERT INTO tbl_bom_set_mst(
                             set_code
                            ,set_cus_code
                            ,set_project
                            ,set_comp_code
                            ,set_part_customer
                            ,set_ctn_code_normal
                            ,set_description
                            ,set_dwg_code
                            ,set_ft2
                            ,set_cost_rm
                            ,set_cost_dl
                            ,set_cost_oh
                            ,set_cost_total
                            ,set_cost_total_oh
                            ,set_selling_price
                            ,set_prod_time
                            ,set_snp
                            ,set_moq
                            ,set_status
                            ,set_create_datetime
                            ,set_create_by
                            ,set_update_datetime
                            ,set_update_by
                          )VALUES(
                             :set_code
                            ,:set_cus_code
                            ,:set_project
                            ,:set_comp_code
                            ,:set_part_customer
                            ,:set_ctn_code_normal
                            ,:set_description
                            ,:set_dwg_code
                            ,:set_ft2
                            ,:set_cost_rm
                            ,:set_cost_dl
                            ,:set_cost_oh
                            ,:set_cost_total
                            ,:set_cost_total_oh
                            ,:set_selling_price
                            ,:set_prod_time
                            ,:set_snp
                            ,:set_moq
                            ,'Active'
                            ,:set_create_datetime
                            ,:set_create_by
                            ,:set_update_datetime
                            ,:set_update_by
                          )"
                    );
                    $in->bindParam(':set_code', $fg_codeset);
                    $in->bindParam(':set_cus_code', $cus_code);
                    $in->bindParam(':set_project', $project);
                    $in->bindParam(':set_comp_code', $comp_code);
                    $in->bindParam(':set_part_customer', $part_customer);
                    $in->bindParam(':set_ctn_code_normal', $ctn_code_normal);
                    $in->bindParam(':set_description', $fg_description);
                    $in->bindParam(':set_dwg_code', $dwg_code);
                    $in->bindParam(':set_ft2', $fg_ft2);
                    $in->bindParam(':set_cost_rm', $cost_rm);
                    $in->bindParam(':set_cost_dl', $cost_dl);
                    $in->bindParam(':set_cost_oh', $cost_oh);
                    $in->bindParam(':set_cost_total', $cost_total);
                    $in->bindParam(':set_cost_total_oh', $cost_total_oh);
                    $in->bindParam(':set_selling_price', $selling_price);
                    $in->bindParam(':set_prod_time', $production_time);
                    $in->bindParam(':set_snp', $snp);
                    $in->bindParam(':set_moq', $moq);
                    // $in->bindParam(':set_status', $bom_status);
                    $in->bindParam(':set_create_datetime', $buffer_datetime);
                    $in->bindParam(':set_create_by', $mrp_user_name_mst);
                    $in->bindParam(':set_update_datetime', $buffer_datetime);
                    $in->bindParam(':set_update_by', $mrp_user_name_mst);
                    // $in->bindParam(':bom_group_set', $bom_group_set);
                    $in->execute();
                }else{
                    $subs = $db_con->prepare("SELECT * FROM tbl_supplier_mst WHERE sup_code = :sup_code");
                    $subs->bindParam(':sup_code', $sup_code);
                    $subs->execute();
                    $supsResult = $subs->fetch(PDO::FETCH_ASSOC);

                    if($supsResult['sup_code'] == ''){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Supplier $sup_code บนระบบ"));
                        $db_con = null;
                        return;
                    }

                    $crmlist = $db_con->prepare("SELECT * FROM tbl_rm_mst WHERE rm_code = :rm_code");
                    $crmlist->bindParam(':rm_code', $rm_code);
                    $crmlist->execute();
                    $crmlistResult = $crmlist->fetch(PDO::FETCH_ASSOC);
                    if($crmlistResult['rm_code'] == ''){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Master Raw Material $rm_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if(!in_array($fg_type, $fg_type_check)){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code พบข้อมูล FG Type ไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if(!in_array($fac_type, $fac_type_check)){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code พบข้อมูล Fac Type ไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $clist = $db_con->prepare("SELECT cus_code, cus_type, cus_name_en, cus_status FROM tbl_customer_mst WHERE cus_code = :cus_code");
                    $clist->bindParam(':cus_code', $cus_code);
                    $clist->execute();
                    $clistResult = $clist->fetch(PDO::FETCH_ASSOC);

                    if($clistResult['cus_code'] == ""){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code ไม่พบ Customer code $cus_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if($clistResult['cus_status'] != "Active"){
                        echo json_encode(array('code'=>400, 'message'=>"Customer Code ==> $cus_code ไม่อยู่ในสถานะ Active ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $slist = $db_con->prepare("SELECT sup_code, sup_name_en FROM tbl_supplier_mst WHERE sup_code = :sup_code");
                    $slist->bindParam(':sup_code', $sup_code);
                    $slist->execute();
                    $slistResult = $slist->fetch(PDO::FETCH_ASSOC);
                    
                    if($slistResult['sup_code'] == ""){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Supplier $sup_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $pkglist = $db_con->prepare("SELECT TOP(1) prod_code FROM tbl_product_type_mst WHERE prod_type = :prod_type AND topic = 'T'");
                    $pkglist->bindParam(':prod_type', $package_type);
                    $pkglist->execute();
                    $pkglistResult = $pkglist->fetch(PDO::FETCH_ASSOC);
                    if($pkglistResult['prod_code'] == ''){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบ Master Package Type $package_type ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    
                    $blist = $db_con->prepare("SELECT COUNT(fg_code) AS blist FROM tbl_bom_mst WHERE fg_code = :fg_code AND fg_codeset = :fg_codeset AND part_customer = :part_customer AND comp_code = :comp_code AND project = :project AND ship_to_type = :ship_to_type");
                    $blist->bindParam(':fg_code', $fg_code);
                    $blist->bindParam(':fg_codeset', $fg_codeset);
                    $blist->bindParam(':part_customer', $part_customer);
                    $blist->bindParam(':comp_code', $comp_code);
                    $blist->bindParam(':project', $project);
                    $blist->bindParam(':ship_to_type', $ship_to_type);
                    $blist->execute();
                    $blistResult = $blist->fetch(PDO::FETCH_ASSOC);

                    if($blistResult['blist'] > 0){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code พบข้อมูลที่ตรงกัน 6 Column บนระบบอยู่แล้ว ไม่สามารถดำเนินการได้ "));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $bom_uniq = generate_bom_uniq($db_con, $package_type, $fg_type);
                    
                    try {
                        $newblist = $db_con->prepare(
                            "INSERT INTO tbl_bom_mst(bom_uniq, sale_type, fg_codeset, fg_code, part_customer, ctn_code_normal, comp_code, fg_description, package_code, cus_type, cus_code, project, project_type, dwg_code, dwg_ref, package_type, box_type, fg_w, fg_l, fg_h, fg_ft2, pd_usage, ffmc_usage, fg_perpage, wip, fg_usage, packing_usage, fg_type, fac_type, rm_code, rm_spec, rm_flute, rm_w, rm_l, rm_ft2, rm_moq_min, machine_order, machine_mp, snp, moq, vmi_app, ship_to_type, wms_max, wms_min, vmi_max, vmi_min, bom_status, bom_sup_code, cost_rm, cost_dl, cost_oh, cost_total, cost_total_oh, selling_price, production_time, create_datetime, create_by, update_datetime, update_by, bom_group_set)
                             VALUES('$bom_uniq', '$sale_type', '$fg_codeset', '$fg_code', '$part_customer', '$ctn_code_normal', '$comp_code', '$fg_description', '$package_code', '$cus_type', '$cus_code', '$project', '$project_type', '$dwg_code', '$dwg_ref', '$package_type', '$box_type', '$fg_w', '$fg_l', '$fg_h', '$fg_ft2', '$pd_usage', '$ffmc_usage', '$fg_perpage', '$wip', '$laminate', '$packing_usage', '$fg_type', '$fac_type', '$rm_code', '$rm_spec', '$rm_flute', $rm_w, $rm_l, $rm_ft2, $rm_moq_min, '$machine_order', '$machine_mp', $snp, $moq, '$vmi_app', '$ship_to_type', $wms_max, $wms_min, $vmi_max, $vmi_min, 'Active', '".$supsResult['sup_uniq']."', $cost_rm, $cost_dl, $cost_oh, $cost_total, $cost_total_oh, $selling_price, '$production_time', '$buffer_datetime', '$mrp_user_name_mst', '$buffer_datetime', '$mrp_user_name_mst', '$bom_group_set')");
                        // var_dump($newblist);
                        // echo "<br>";
                        // $newblist = $db_con->prepare("INSERT INTO tbl_bom_mst(bom_uniq, sale_type, fg_codeset, fg_code, part_customer, ctn_code_normal, comp_code, fg_description, package_code, cus_type, cus_code, project, project_type, dwg_code,dwg_ref, package_type, box_type, fg_w, fg_l, fg_h, fg_ft2, pd_usage, ffmc_usage, fg_perpage, wip, fg_usage, packing_usage, fg_type, fac_type, rm_code, rm_spec, rm_flute, rm_w, rm_l, rm_ft2, rm_moq_min, machine_order, machine_mp, snp, moq, vmi_app, ship_to_type, wms_max, wms_min, vmi_max, vmi_min, bom_status, bom_sup_code, cost_rm, cost_dl, cost_oh, cost_total, cost_total_oh, selling_price, production_time, create_datetime, create_by, update_datetime, update_by, bom_group_set) VALUES(:bom_uniq, :sale_type, :fg_codeset, :fg_code, :part_customer, :ctn_code_normal, :comp_code, :fg_description, :package_code, :cus_type, :cus_code, :project, :project_type, :dwg_code, :dwg_ref, :package_type, :box_type, :fg_w, :fg_l, :fg_h, :fg_ft2, :pd_usage, :ffmc_usage, :fg_perpage, :wip, :laminate, :packing_usage, :fg_type, :fac_type, :rm_code, :rm_spec, :rm_flute, :rm_w, :rm_l, :rm_ft2, :rm_moq_min, :machine_order, :machine_mp, :snp, :moq, :vmi_app, :ship_to_type, :wms_max, :wms_min, :vmi_max, :vmi_min, 'Active', :sup_code, :cost_rm, :cost_dl, :cost_oh, :cost_total, :cost_total_oh, :selling_price, :production_time, :create_datetime, :create_by, :update_datetime, :update_by, :bom_group_set)");
                        // $newblist->bindParam(':bom_uniq', $bom_uniq);
                        // $newblist->bindParam(':sale_type', $sale_type);
                        // $newblist->bindParam(':fg_codeset', $fg_codeset);
                        // $newblist->bindParam(':fg_code', $fg_code);
                        // $newblist->bindParam(':part_customer', $part_customer);
                        // $newblist->bindParam(':ctn_code_normal', $ctn_code_normal);
                        // $newblist->bindParam(':comp_code', $comp_code);
                        // $newblist->bindParam(':fg_description', $fg_description);
                        // $newblist->bindParam(':package_code', $package_code);
                        // $newblist->bindParam(':cus_type', $cus_type);
                        // $newblist->bindParam(':cus_code', $cus_code);
                        // $newblist->bindParam(':project', $project);
                        // $newblist->bindParam(':project_type', $project_type);
                        // $newblist->bindParam(':dwg_code', $dwg_code);
                        // $newblist->bindParam(':dwg_ref', $dwg_ref);
                        // $newblist->bindParam(':package_type', $package_type);
                        // $newblist->bindParam(':box_type', $box_type);
                        // $newblist->bindParam(':fg_w', $fg_w);
                        // $newblist->bindParam(':fg_l', $fg_l);
                        // $newblist->bindParam(':fg_h', $fg_h);
                        // $newblist->bindParam(':fg_ft2', $fg_ft2);
                        // $newblist->bindParam(':pd_usage', $pd_usage);
                        // $newblist->bindParam(':ffmc_usage', $ffmc_usage);
                        // $newblist->bindParam(':fg_perpage', $fg_perpage);
                        // $newblist->bindParam(':wip', $wip);
                        // $newblist->bindParam(':fg_usage', $fg_usage);
                        // $newblist->bindParam(':packing_usage', $packing_usage);
                        // $newblist->bindParam(':fg_type', $fg_type);
                        // $newblist->bindParam(':fac_type', $fac_type);
                        // $newblist->bindParam(':rm_code', $rm_code);
                        // $newblist->bindParam(':rm_spec', $rm_spec);
                        // $newblist->bindParam(':rm_flute', $rm_flute);
                        // $newblist->bindParam(':rm_w', $rm_w);
                        // $newblist->bindParam(':rm_l', $rm_l);
                        // $newblist->bindParam(':rm_ft2', $rm_ft2);
                        // $newblist->bindParam(':rm_moq_min', $rm_moq_min);
                        // $newblist->bindParam(':machine_order', $machine_order);
                        // $newblist->bindParam(':machine_mp', $machine_mp);
                        // $newblist->bindParam(':snp', $snp);
                        // $newblist->bindParam(':moq', $moq);
                        // $newblist->bindParam(':vmi_app', $vmi_app);
                        // $newblist->bindParam(':ship_to_type', $ship_to_type);
                        // $newblist->bindParam(':wms_max', $wms_max);
                        // $newblist->bindParam(':wms_min', $wms_min);
                        // $newblist->bindParam(':vmi_max', $vmi_max);
                        // $newblist->bindParam(':vmi_min', $vmi_min);
                        // $newblist->bindParam(':sup_code', $supsResult['sup_uniq']);
                        // $newblist->bindParam(':cost_rm', $cost_rm);
                        // $newblist->bindParam(':cost_dl', $cost_dl);
                        // $newblist->bindParam(':cost_oh', $cost_oh);
                        // $newblist->bindParam(':cost_total', $cost_total);
                        // $newblist->bindParam(':cost_total_oh', $cost_total_oh);
                        // $newblist->bindParam(':selling_price', $selling_price);
                        // $newblist->bindParam(':production_time', $production_time);
                        // $newblist->bindParam(':create_datetime', $buffer_datetime);
                        // $newblist->bindParam(':create_by', $mrp_user_name_mst);
                        // $newblist->bindParam(':update_datetime', $buffer_datetime);
                        // $newblist->bindParam(':update_by', $mrp_user_name_mst);
                        // $newblist->bindParam(':bom_group_set', $bom_group_set);
                        $newblist->execute();
                    } catch(Exception $e) {
                        echo json_encode(array('code'=>400, 'message'=>'ไม่สามารถบันทึกข้อมูลรายการ ' . $fg_code . ' ได้ ' . $e->getMessage()));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>> VMI CHecking area details >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    if($fac_type != "TRADING"){
                        $vmList = $vmi_con->prepare("SELECT COUNT(bom_fg_code_gdj) AS count_list FROM tbl_bom_mst WHERE bom_fg_code_set_abt = :fg_codeset AND bom_fg_code_gdj = :fg_code AND bom_fg_sku_code_abt = :comp_code AND bom_part_customer = :part_customer AND bom_ship_type = :ship_to_type AND bom_pj_name = :project");
                        $vmList->bindParam(':fg_codeset', $fg_codeset);
                        $vmList->bindParam(':fg_code', $fg_code);
                        $vmList->bindParam(':comp_code', $fg_code);
                        $vmList->bindParam(':part_customer', $part_customer);
                        $vmList->bindParam(':ship_to_type', $ship_to_type);
                        $vmList->bindParam(':project', $project);
                        $vmList->execute();
                        $vmListResult = $vmList->fetch(PDO::FETCH_ASSOC);

                        if($vmListResult['count_list'] > 0){
                            echo json_encode(array('code'=>400, 'message'=>"ไม่สามารถดำเนินการได้ รายการ $fg_code มีข้อมูลนี้อยู่บนระบบอยู่แล้ว กรุณาติดต่อ IT"));
                            $db_con = null;
                            $vmi_con = null;
                            return;
                        }

                        $vmnewblist = $vmi_con->prepare("INSERT INTO tbl_bom_mst(bom_fg_code_set_abt, bom_fg_sku_code_abt, bom_fg_code_gdj, bom_fg_desc, bom_cus_code, bom_cus_name, bom_pj_name, bom_ctn_code_normal, bom_snp, bom_sku_code, bom_ship_type, bom_pckg_type, bom_dims_w, bom_dims_l, bom_dims_h, bom_usage, bom_space_paper, bom_flute, bom_packing, bom_wms_min, bom_wms_max, bom_vmi_min, bom_vmi_max, bom_vmi_app, bom_part_customer, bom_cost, bom_cost_per_pcs, bom_price_sale_per_pcs, bom_status, bom_issue_by, bom_issue_date, bom_issue_time, bom_issue_datetime, bom_uniq, bom_pj_type, bom_fg_type) VALUES(:bom_fg_code_set_abt, :bom_fg_sku_code_abt, :bom_fg_code_gdj, :bom_fg_desc, :bom_cus_code, :bom_cus_name, :bom_pj_name, :bom_ctn_code_normal, :bom_snp, :bom_sku_code, :bom_ship_type, :bom_pckg_type, :bom_dims_w, :bom_dims_l, :bom_dims_h, :bom_usage, :bom_space_paper, :bom_flute, :bom_packing, :bom_wms_min, :bom_wms_max, :bom_vmi_min, :bom_vmi_max, :bom_vmi_app, :bom_part_customer, :bom_cost, :bom_cost_per_pcs, :bom_price_sale_per_pcs, 'Active', :bom_issue_by, :bom_issue_date, :bom_issue_time, :bom_issue_datetime, :bom_uniq, :bom_pj_type, :bom_fg_type)");
                        $vmnewblist->bindParam(':bom_fg_code_set_abt', $fg_codeset);
                        $vmnewblist->bindParam(':bom_fg_sku_code_abt', $comp_code);
                        $vmnewblist->bindParam(':bom_fg_code_gdj', $fg_code);
                        $vmnewblist->bindParam(':bom_fg_desc', $fg_description);
                        $vmnewblist->bindParam(':bom_cus_code', $cus_code);
                        $vmnewblist->bindParam(':bom_cus_name', $clistResult['cus_name_en']);
                        $vmnewblist->bindParam(':bom_pj_name', $project);
                        $vmnewblist->bindParam(':bom_ctn_code_normal', $ctn_code_normal);
                        $vmnewblist->bindParam(':bom_snp', $snp);
                        $vmnewblist->bindParam(':bom_sku_code', $box_type);
                        $vmnewblist->bindParam(':bom_ship_type', $ship_to_type);
                        $vmnewblist->bindParam(':bom_pckg_type', $package_type);
                        $vmnewblist->bindParam(':bom_dims_w', $fg_w);
                        $vmnewblist->bindParam(':bom_dims_l', $fg_l);
                        $vmnewblist->bindParam(':bom_dims_h', $fg_h);
                        $vmnewblist->bindParam(':bom_usage', $ffmc_usage);
                        $vmnewblist->bindParam(':bom_space_paper', $rm_spec);
                        $vmnewblist->bindParam(':bom_flute', $rm_flute);
                        $vmnewblist->bindParam(':bom_packing', $packing_usage);
                        $vmnewblist->bindParam(':bom_wms_min', $wms_min);
                        $vmnewblist->bindParam(':bom_wms_max', $wms_max);
                        $vmnewblist->bindParam(':bom_vmi_min', $vmi_min);
                        $vmnewblist->bindParam(':bom_vmi_max', $vmi_max);
                        $vmnewblist->bindParam(':bom_vmi_app', $vmi_app);
                        $vmnewblist->bindParam(':bom_part_customer', $part_customer);
                        $vmnewblist->bindParam(':bom_cost', $cost_total);
                        $vmnewblist->bindParam(':bom_cost_per_pcs', $cost_total_oh);
                        $vmnewblist->bindParam(':bom_price_sale_per_pcs', $selling_price);
                        $vmnewblist->bindParam(':bom_issue_by', $mrp_user_name_mst);
                        $vmnewblist->bindParam(':bom_issue_date', $buffer_date);
                        $vmnewblist->bindParam(':bom_issue_time', $buffer_time);
                        $vmnewblist->bindParam(':bom_issue_datetime', $buffer_datetime);
                        $vmnewblist->bindParam(':bom_uniq', $bom_uniq);
                        $vmnewblist->bindParam(':bom_pj_type', $project_type);
                        $vmnewblist->bindParam(':bom_fg_type', $fg_type);
                        $vmnewblist->execute();

                        $chfg2 = $vmi_con->prepare("DELETE FROM tbl_fg_ft2_mst WHERE ft2_fg_code = :fg_code");
                        $chfg2->bindParam(':fg_code', $fg_code);
                        $chfg2->execute();

                        $newft2 = $vmi_con->query("INSERT INTO tbl_fg_ft2_mst(ft2_fg_code, ft2_value, ft2_issue_by, ft2_issue_date, ft2_issue_time, ft2_issue_datetime) VALUES('$fg_code', $fg_ft2, '$mrp_user_name_mst', '$buffer_date', '$buffer_time', '$buffer_datetime')");
                    }
                }
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการบันทึกข้อมูล New Master BOM สำเร็จ'));
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
    }else if($protocol == "UpdateByColumn"){
        try {
            $upfile = isset($_POST['upfile']) ? $_POST['upfile'] : '';

            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow();

            $fg_type_check = ['SET','COMPONENT'];
            $fac_type_check = ['FG','CORNER','TRADING'];

            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq           = trim($data->getCell("B$i")->getValue());
                $sale_type          = trim($data->getCell("C$i")->getValue());
                $fg_codeset         = trim($data->getCell("D$i")->getValue());
                $fg_code            = trim($data->getCell("E$i")->getValue());
                $part_customer      = trim($data->getCell("F$i")->getValue());
                $ctn_code_normal    = trim($data->getCell("G$i")->getValue());
                $comp_code          = trim($data->getCell("H$i")->getValue());
                $fg_description     = trim($data->getCell("I$i")->getValue());
                $package_code       = trim($data->getCell("J$i")->getValue());
                $cus_type           = trim($data->getCell("K$i")->getValue());
                $cus_code           = trim($data->getCell("L$i")->getValue());
                $project            = trim($data->getCell("M$i")->getValue());
                $project_type       = trim($data->getCell("N$i")->getValue());
                $dwg_code           = trim($data->getCell("O$i")->getValue());
                $dwg_ref            = trim($data->getCell("P$i")->getValue());
                $package_type       = trim($data->getCell("Q$i")->getValue());
                $box_type           = trim($data->getCell("R$i")->getValue());
                $fg_w               = trim($data->getCell("S$i")->getValue());
                $fg_l               = trim($data->getCell("T$i")->getValue());
                $fg_h               = trim($data->getCell("U$i")->getValue());
                $fg_ft2             = trim($data->getCell("V$i")->getValue());
                $pd_usage           = trim($data->getCell("W$i")->getValue());
                $ffmc_usage         = trim($data->getCell("X$i")->getValue());
                $fg_perpage         = trim($data->getCell("Y$i")->getValue());
                $wip                = trim($data->getCell("Z$i")->getValue());
                $fg_usage           = trim($data->getCell("AA$i")->getValue());
                $packing_usage      = trim($data->getCell("AB$i")->getValue());
                $fg_type            = trim($data->getCell("AC$i")->getValue());
                $fac_type           = trim($data->getCell("AD$i")->getValue());
                $rm_code            = trim($data->getCell("AE$i")->getValue());
                $rm_spec            = trim($data->getCell("AF$i")->getValue());
                $rm_flute           = trim($data->getCell("AG$i")->getValue());
                $rm_w               = trim($data->getCell("AH$i")->getValue());
                $rm_l               = trim($data->getCell("AI$i")->getValue());
                $rm_ft2             = trim($data->getCell("AJ$i")->getValue());
                $rm_moq_min         = trim($data->getCell("AK$i")->getValue());
                $machine_order      = trim($data->getCell("AL$i")->getValue());
                $machine_mp         = trim($data->getCell("AM$i")->getValue());
                $snp                = trim($data->getCell("AN$i")->getValue());
                $moq                = trim($data->getCell("AO$i")->getValue());
                $vmi_app            = trim($data->getCell("AP$i")->getValue());
                $ship_to_type       = trim($data->getCell("AQ$i")->getValue());
                $wms_max            = trim($data->getCell("AR$i")->getValue());
                $wms_min            = trim($data->getCell("AS$i")->getValue());
                $vmi_max            = trim($data->getCell("AT$i")->getValue());
                $vmi_min            = trim($data->getCell("AU$i")->getValue());
                $bom_status         = trim($data->getCell("AV$i")->getValue());
                $sup_code           = trim($data->getCell("AW$i")->getValue());
                $cost_rm            = trim($data->getCell("AX$i")->getValue());
                $cost_dl            = trim($data->getCell("AY$i")->getValue());
                $cost_oh            = trim($data->getCell("AZ$i")->getValue());
                $cost_total         = trim($data->getCell("BA$i")->getValue());
                $cost_total_oh      = trim($data->getCell("BB$i")->getValue());
                $selling_price      = trim($data->getCell("BC$i")->getValue());
                $production_time    = trim($data->getCell("BD$i")->getValue());
                $bom_group_set      = trim($data->getCell("BE$i")->getValue());

                $rm_w_mm = $rm_w * 25.4;
                $rm_l_mm = $rm_l * 25.4;

                if($bom_uniq == ""){
                    echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล $bom_uniq ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                    $db_con = null;
                    return;
                }else{
                    $crmlist = $db_con->prepare("SELECT * FROM tbl_rm_mst WHERE rm_code = :rm_code");
                    $crmlist->bindParam(':rm_code', $rm_code);
                    $crmlist->execute();
                    $crmlistResult = $crmlist->fetch(PDO::FETCH_ASSOC);
                    if($crmlistResult['rm_code'] == ''){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Master Raw Material $rm_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if(!in_array($fg_type, $fg_type_check)){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code พบข้อมูล FG Type ไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if(!in_array($fac_type, $fac_type_check)){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code พบข้อมูล Fac Type ไม่ถูกต้อง ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $clist = $db_con->prepare("SELECT cus_code, cus_type, cus_name_en, cus_status FROM tbl_customer_mst WHERE cus_code = :cus_code");
                    $clist->bindParam(':cus_code', $cus_code);
                    $clist->execute();
                    $clistResult = $clist->fetch(PDO::FETCH_ASSOC);

                    if($clistResult['cus_code'] == ""){
                        echo json_encode(array('code'=>400, 'message'=>"รายการ $fg_code ไม่พบ Customer code $cus_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    if($clistResult['cus_status'] != "Active"){
                        echo json_encode(array('code'=>400, 'message'=>"Customer Code ==> $cus_code ไม่อยู่ในสถานะ Active ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $slist = $db_con->prepare("SELECT sup_uniq, sup_code, sup_name_en FROM tbl_supplier_mst WHERE sup_code = :sup_code");
                    $slist->bindParam(':sup_code', $sup_code);
                    $slist->execute();
                    $slistResult = $slist->fetch(PDO::FETCH_ASSOC);
                    
                    if($slistResult['sup_code'] == ""){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบข้อมูล Supplier $sup_code ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }

                    $pkglist = $db_con->prepare("SELECT TOP(1) prod_code FROM tbl_product_type_mst WHERE prod_type = :prod_type AND topic = 'T'");
                    $pkglist->bindParam(':prod_type', $package_type);
                    $pkglist->execute();
                    $pkglistResult = $pkglist->fetch(PDO::FETCH_ASSOC);
                    if($pkglistResult['prod_code'] == ''){
                        echo json_encode(array('code'=>400, 'message'=>"ไม่พบ Master Package Type $package_type ตรวจสอบข้อมูลและดำเนินการใหม่อีกครั้ง"));
                        $db_con = null;
                        $vmi_con = null;
                        return;
                    }
                    
                    $newblist = $db_con->query(
                        "UPDATE tbl_bom_mst
                         SET sale_type = '$sale_type',
                             ctn_code_normal = '$ctn_code_normal',
                             fg_description = '$fg_description',
                             project_type = '$project_type',
                             dwg_code = '$dwg_code',
                             dwg_ref = '$dwg_ref',
                             box_type = '$box_type',
                             fg_w = '$fg_w',
                             fg_l = '$fg_l',
                             fg_h = '$fg_h',
                             fg_ft2 = '$fg_ft2',
                             pd_usage = $pd_usage,
                             ffmc_usage = $ffmc_usage,
                             fg_perpage = $fg_perpage,
                             wip = $wip,
                             fg_usage = $fg_usage,
                             packing_usage = $packing_usage,
                             fg_type = '$fg_type',
                             fac_type = '$fac_type',
                             rm_code = '$rm_code',
                             rm_spec = '$rm_spec',
                             rm_flute = '$rm_flute',
                             rm_w = $rm_w,
                             rm_l = $rm_l,
                             rm_ft2 = $rm_ft2,
                             rm_moq_min = $rm_moq_min,
                             machine_order = '$machine_order',
                             machine_mp = '$machine_mp',
                             snp = $snp,
                             moq = $moq,
                             vmi_app = '$vmi_app',
                             wms_max = $wms_max,
                             wms_min = $wms_min,
                             vmi_max = $vmi_max,
                             vmi_min = $vmi_min,
                             bom_status = '$bom_status',
                             bom_sup_code = '".$slistResult['sup_uniq']."',
                             cost_rm = $cost_rm,
                             cost_dl = $cost_dl,
                             cost_oh = $cost_oh,
                             cost_total = $cost_total,
                             cost_total_oh = $cost_total_oh,
                             production_time = $production_time,
                             update_datetime = '$buffer_datetime',
                             update_by = '$mrp_user_name_mst',
                             bom_group_set = '$bom_group_set'
                         WHERE bom_uniq = '$bom_uniq'");

                    // $newblist->bindParam(':bom_uniq', $bom_uniq);
                    // $newblist->bindParam(':sale_type', $sale_type);
                    // $newblist->bindParam(':ctn_code_normal', $ctn_code_normal);
                    // $newblist->bindParam(':fg_description', $fg_description);
                    // $newblist->bindParam(':project_type', $project_type);
                    // $newblist->bindParam(':dwg_code', $dwg_code);
                    // $newblist->bindParam(':dwg_ref', $dwg_ref);
                    // $newblist->bindParam(':box_type', $box_type);
                    // $newblist->bindParam(':fg_w', $fg_w);
                    // $newblist->bindParam(':fg_l', $fg_l);
                    // $newblist->bindParam(':fg_h', $fg_h);
                    // $newblist->bindParam(':fg_ft2', $fg_ft2);
                    // $newblist->bindParam(':pd_usage', $pd_usage);
                    // $newblist->bindParam(':ffmc_usage', $ffmc_usage);
                    // $newblist->bindParam(':fg_perpage', $fg_perpage);
                    // $newblist->bindParam(':wip', $wip);
                    // $newblist->bindParam(':laminate', $laminate);
                    // $newblist->bindParam(':packing_usage', $packing_usage);
                    // $newblist->bindParam(':fg_type', $fg_type);
                    // $newblist->bindParam(':fac_type', $fac_type);
                    // $newblist->bindParam(':rm_code', $rm_code);
                    // $newblist->bindParam(':rm_spec', $rm_spec);
                    // $newblist->bindParam(':rm_flute', $rm_flute);
                    // $newblist->bindParam(':rm_w', $rm_w);
                    // $newblist->bindParam(':rm_l', $rm_l);
                    // $newblist->bindParam(':rm_ft2', $rm_ft2);
                    // $newblist->bindParam(':rm_moq_min', $rm_moq_min);
                    // $newblist->bindParam(':machine_order', json_encode($machine_order));
                    // $newblist->bindParam(':machine_mp', $machine_mp);
                    // $newblist->bindParam(':snp', $snp);
                    // $newblist->bindParam(':moq', $moq);
                    // $newblist->bindParam(':vmi_app', $vmi_app);
                    // $newblist->bindParam(':wms_max', $wms_max);
                    // $newblist->bindParam(':wms_min', $wms_min);
                    // $newblist->bindParam(':vmi_max', $vmi_max);
                    // $newblist->bindParam(':vmi_min', $vmi_min);
                    // $newblist->bindParam(':sup_code', $sup_code);
                    // $newblist->bindParam(':cost_rm', $cost_rm);
                    // $newblist->bindParam(':cost_dl', $cost_dl);
                    // $newblist->bindParam(':cost_oh', $cost_oh);
                    // $newblist->bindParam(':cost_total', $cost_total);
                    // $newblist->bindParam(':cost_total_oh', $cost_total_oh);
                    // $newblist->bindParam(':production_time', $production_time);
                    // $newblist->bindParam(':update_datetime', $buffer_datetime);
                    // $newblist->bindParam(':update_by', $mrp_user_name_mst);
                    // $newblist->execute();

                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>> VMI CHecking area details >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

                    $vmnewblist = $vmi_con->query(
                        "UPDATE tbl_bom_mst
                         SET bom_fg_desc = '$fg_description',
                             bom_ctn_code_normal = '$ctn_code_normal', 
                             bom_snp = $snp, 
                             bom_sku_code = '$box_type', 
                             bom_dims_w = $fg_w, 
                             bom_dims_l = $fg_l, 
                             bom_dims_h = $fg_h, 
                             bom_usage = $ffmc_usage, 
                             bom_space_paper = '$rm_spec', 
                             bom_flute = '$rm_flute', 
                             bom_wms_min = $wms_min, 
                             bom_wms_max = $wms_max, 
                             bom_vmi_min = $vmi_min, 
                             bom_vmi_max = $vmi_max, 
                             bom_vmi_app = '$vmi_app', 
                             bom_part_customer = '$part_customer', 
                             bom_cost = $cost_total, 
                             bom_cost_per_pcs = $cost_total_oh,
                             bom_pj_type = '$project_type', 
                             bom_fg_type = '$fg_type',
                             bom_packing = '$packing_usage'
                         WHERE bom_uniq = '$bom_uniq'");
                    // $vmnewblist->execute();
                    // $vmnewblist->bindParam(':bom_fg_desc', $fg_description);
                    // $vmnewblist->bindParam(':bom_ctn_code_normal', $ctn_code_normal);
                    // $vmnewblist->bindParam(':bom_snp', $snp);
                    // $vmnewblist->bindParam(':bom_sku_code', $box_type);
                    // $vmnewblist->bindParam(':bom_dims_w', $fg_w);
                    // $vmnewblist->bindParam(':bom_dims_l', $fg_l);
                    // $vmnewblist->bindParam(':bom_dims_h', $fg_h);
                    // $vmnewblist->bindParam(':bom_usage', $ffmc_usage);
                    // $vmnewblist->bindParam(':bom_space_paper', $rm_spec);
                    // $vmnewblist->bindParam(':bom_flute', $rm_flute);
                    // $vmnewblist->bindParam(':bom_packing', $packing_usage);
                    // $vmnewblist->bindParam(':bom_wms_min', $wms_min);
                    // $vmnewblist->bindParam(':bom_wms_max', $wms_max);
                    // $vmnewblist->bindParam(':bom_vmi_min', $vmi_min);
                    // $vmnewblist->bindParam(':bom_vmi_max', $vmi_max);
                    // $vmnewblist->bindParam(':bom_vmi_app', $vmi_app);
                    // $vmnewblist->bindParam(':bom_part_customer', $part_customer);
                    // $vmnewblist->bindParam(':bom_cost', $cost_total);
                    // $vmnewblist->bindParam(':bom_cost_per_pcs', $cost_total_oh);
                    // $vmnewblist->bindParam(':bom_pj_type', $project_type);
                    // $vmnewblist->bindParam(':bom_fg_type', $fg_type);
                    // $vmnewblist->bindParam(':bom_uniq', $bom_uniq);
                    // $vmnewblist->execute();

                    $chfg2 = $vmi_con->prepare("DELETE FROM tbl_fg_ft2_mst WHERE ft2_fg_code = :fg_code");
                    $chfg2->bindParam(':fg_code', $fg_code);
                    $chfg2->execute();

                    $newft2 = $vmi_con->query("INSERT INTO tbl_fg_ft2_mst(ft2_fg_code, ft2_value, ft2_issue_by, ft2_issue_date, ft2_issue_time, ft2_issue_datetime) VALUES('$fg_code', $fg_ft2, '$mrp_user_name_mst', '$buffer_date', '$buffer_time', '$buffer_datetime')");
                }
            }

            echo json_encode(array('code'=>200, 'message'=>'ดำเนินการบันทึกข้อมูล Update Master BOM สำเร็จ'));
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
    }


    function checkParamType($val, $type){
        $v = false;

        if(gettype($val) != $type){
            $v = true;
        }

        return $v;
    }
?>