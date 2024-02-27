<?php 
    require_once("../../../../session.php");

    $bom_uniq = isset($_POST['bom_uniq']) ? $_POST['bom_uniq'] : '';

    $list = $db_con->query("SELECT cus_code, ctn_code_normal, dwg_code, dwg_ref FROM tbl_bom_mst WHERE bom_uniq = '$bom_uniq'");
    $listResult = $list->fetch(PDO::FETCH_ASSOC);

    if($listResult['dwg_ref'] == "CRM"){
        $path = $db_crm_con->query(
            "SELECT gdj_con_file_dwg
             FROM tbl_gdj_confirm
             LEFT JOIN tbl_gdj_ffic_design ON gdj_design_drawing_no = gdj_con_dwg_no AND gdj_design_status = 'active'
             INNER JOIN tbl_gdj_customer_part_code ON gdj_code_id = gdj_con_code_id
             INNER JOIN tbl_gdj_customer ON gdj_cus_id = gdj_code_cus_id
             WHERE gdj_cus_code = '".$listResult['cus_code']."' AND gdj_code_part_code = '".$listResult['ctn_code_normal']."' AND gdj_design_drawing_no = '".$listResult['dwg_code']."'"
        );
        $pathResult = $path->fetch(PDO::FETCH_ASSOC);
        $gdj_con_file_dwg = json_decode($pathResult['gdj_con_fiel_dwg'], TRUE);
        $dwg_ref = $gdj_con_file_dwg[0]['path'];

        if($dwg_ref != ''){
            header('Location: https://crm.albatrosslogistic.com/crm/gdj_file_confirm/' . trim($dwg_ref));
            $db_con = null;
            $db_crm_con = nulll;
        }
    }else if(isset($listResult['dwg_ref'])){
        header('Location: http://qmr.glong-duang-jai.com/open_doc.php?f=' . $listResult['dwg_code'] . '.pdf');
        $db_con = null;
        $db_crm_con = nulll;
    }else{
        echo 'file not found';
        $db_con = null;
    }
?>