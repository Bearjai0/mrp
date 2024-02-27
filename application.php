<?php
    //todo >>>>>>>>>>>>>>>>>>>> Connect Detail of MRP Manufacturing >>>>>>>>>>>>>>>>>>>>
    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $servername = "203.150.199.177";
    $username = "kbdb";
    $password = "rJmBXTW2";
    $dbname = "db_mrp";

    // if(isset($_COOKIE['mrp_user_type_code_mst']) == "T005"){
    //     $dbname = "db_mrp_dev";
    // }

    //todo >>>>>>>>>>>>>>>>>>>> Connect Detail of ERP - PUR & VMI >>>>>>>>>>>>>>>>>>>>
    //todo >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $pu_servername = "172.24.4.115";
    $pu_username = "sa";
    $pu_password = "P09iQA!WaT_?#R41!eXO";
    $pu_dbname = "PUR";
    $vmi_dbname = "VMI";

    try{
        $db_con = new PDO("sqlsrv:server=$servername ; Database = $dbname", $username, $password);
        $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db_con->beginTransaction();

        $pu_con = new PDO("sqlsrv:server=$pu_servername ; Database = $pu_dbname", $pu_username, $pu_password);
        $pu_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pu_con->beginTransaction();

        $vmi_con = new PDO("sqlsrv:server=$pu_servername ; Database = $vmi_dbname", $pu_username, $pu_password);
        $vmi_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $vmi_con->beginTransaction();

        $buffer_day = date("d");
        $buffer_month = date("m");
        $buffer_year = date("Y");
        $buffer_year_2digit = date("y");
        $buffer_date = date("Y-m-d");
        $buffer_time = date("H:i:s"); //24H
        $buffer_datetime = date("Y-m-d H:i:s");
        
        $CFG = new stdClass();

        $CFG->wwwroot = "https://mrp.albatrosslogistic.com";
        $CFG->wwwlib = "https://lib.albatrosslogistic.com";
        
        $CFG->dir_js = "$CFG->wwwroot/js";
        $CFG->dir_lib = "$CFG->wwwroot/lib";
        $CFG->dir_css = "$CFG->wwwroot/css";
        $CFG->dir_font = "$CFG->wwwroot/webfonts";
        $CFG->dir_modules = "$CFG->wwwroot/modules";
        $CFG->dir_printed = "$CFG->wwwroot/printed";

        $CFG->sub_gif = "$CFG->wwwlib/assets/gif";
        $CFG->sub_images = "$CFG->wwwlib/assets/images";
        $CFG->fol_company_logo = "$CFG->sub_images/company_logo";

        $CFG->mod_user = "$CFG->dir_modules/user";
        $CFG->mod_sale = "$CFG->dir_modules/sale";
        $CFG->mod_master = "$CFG->dir_modules/master";
        $CFG->mod_tooling = "$CFG->dir_modules/tooling";
        $CFG->mod_planning = "$CFG->dir_modules/planning";
        $CFG->mod_dashboard = "$CFG->dir_modules/dashboard";
        $CFG->mod_production = "$CFG->dir_modules/production";
        $CFG->mod_fulfillment = "$CFG->dir_modules/fulfillment";
        $CFG->mod_wip_inven = "$CFG->dir_modules/wip-inven";
        $CFG->mod_mateiral_inven = "$CFG->dir_modules/material-inven";

        $CFG->mod_report_production = "$CFG->dir_modules/report-production";
        $CFG->mod_report_accounting = "$CFG->dir_modules/report-accounting";

        $CFG->fol_pd_work = "$CFG->mod_production/work-order";
        $CFG->fol_pd_combine = "$CFG->mod_production/combine-set";
        $CFG->fol_pd_outside = "$CFG->mod_production/outside-plan";
        $CFG->fol_pd_tigthing = "$CFG->mod_production/tightening-cv";
        $CFG->fol_pd_station = "$CFG->mod_production/station-confirm";

        $CFG->fol_planning_upload = "$CFG->mod_planning/upload";
        $CFG->fol_planning_manage = "$CFG->mod_planning/manage";

        $CFG->fol_ffmc_manage = "$CFG->mod_fulfillment/manage-order";
        $CFG->fol_ffmc_upload = "$CFG->mod_fulfillment/upload-order";
        $CFG->fol_ffmc_report = "$CFG->mod_fulfillment/report-order";

        $CFG->fol_master_bom = "$CFG->mod_master/bom";
        $CFG->fol_master_material = "$CFG->mod_master/material";

        $CFG->func_bom_issue = "$CFG->fol_master_bom/issue";
        $CFG->func_bom_master = "$CFG->fol_master_bom/master";
        $CFG->func_bom_manual = "$CFG->fol_master_bom/manual";

        $CFG->func_material_rm = "$CFG->fol_master_material/rm";
        $CFG->func_material_sm = "$CFG->fol_master_material/sm";
        
        $CFG->fol_handling = "$CFG->mod_tooling/handling";
        $CFG->fol_toollist = "$CFG->mod_tooling/toollist";

        $CFG->fol_rep_inform = "$CFG->mod_report_production/inform";
        $CFG->fol_rep_simulate = "$CFG->mod_report_production/simulate";
        $CFG->fol_rep_shipping = "$CFG->mod_report_production/shipping";

        $CFG->fol_material_inbound = "$CFG->mod_mateiral_inven/inbound";
        $CFG->fol_material_outbound = "$CFG->mod_mateiral_inven/outbound";
        $CFG->fol_material_stock_inven = "$CFG->mod_mateiral_inven/stock-inven";
        $CFG->fol_material_requisition = "$CFG->mod_mateiral_inven/requisition";

        $CFG->fol_sale_incentive = "$CFG->mod_sale/incentive";
        $CFG->fol_sale_incenrate = "$CFG->mod_sale/incenrate";
        $CFG->fol_sale_quotations = "$CFG->mod_sale/quotations";
        $CFG->fol_sale_saleorder = "$CFG->mod_sale/sale-order";

        $CFG->printed_job = "https://kb.albatrosslogistic.com/mrp/printed/work-order/wp";
        $CFG->printed_lot_cover = "$CFG->wwwroot/printed/fg-inven/print_lot_cover";
        $CFG->printed_fg_pallet = "$CFG->wwwroot/printed/fg-inven/print_pallet";


        $CFG->mail_host = 'smtp.office365.com';
        $CFG->mail_port = '587';
        $CFG->user_smtp_mail = 'abt.automail@all2gether.net';
        $CFG->password_smtp_mail = '365arIR5dReya0APez!S';
        $CFG->from_mail = 'abt.automail@all2gether.net';

        

        require_once("lib/classlib.php");
        require_once("lib/dblib.php");
    }catch(Exception $e){
        die(print_r($e->getMessage()));
    }
?>
