<?php
    require_once("../../session.php");

    require_once("../../../library/PHPSpreadSheet/vendor/autoload.php");

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Shared\Date;
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setReadEmptyCells(false);

    $protocol = isset($_REQUEST['protocol']) ? $_REQUEST['protocol'] : '';

    if($protocol == "UpdateBOM"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow(); //จำนวน row ทั้งหมดใน Active Sheet นั้นๆ
            $data_file = $_FILES['upfile']['tmp_name'];
    
            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq = trim($data->getCell("F$i")->getValue());
                $box_type = trim($data->getCell("C$i")->getValue());
                $sale_type = trim($data->getCell("B$i")->getValue());
                $fg_codeset = trim($data->getCell("H$i")->getValue());
                
                if($bom_uniq != ''){
                    $up = $db_con->query(
                        "UPDATE tbl_bom_mst SET box_type = '$box_type', sale_type = '$sale_type', fg_codeset = '$fg_codeset' WHERE bom_uniq = '$bom_uniq';
                         UPDATE tbl_job_mst SET job_fg_codeset = '$fg_codeset' WHERE job_bom_id = '$bom_uniq'
                        "
                    );
                }
            }
            
            echo 'complete';
            // $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            var_dump($e);
            $db_con = null;
            return;
        }
    }else if($protocol == "UploadBOMSet"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow(); //จำนวน row ทั้งหมดใน Active Sheet นั้นๆ
            $data_file = $_FILES['upfile']['tmp_name'];
            
            for($i=2;$i<=$highestRow;$i++){
                $fg_code            = trim($data->getCell("F$i")->getValue());
                $set_type           = trim($data->getCell("D$i")->getValue());
                $set_code           = trim($data->getCell("G$i")->getValue());
                $comp_code          = trim($data->getCell("H$i")->getValue());
                $cus_code           = trim($data->getCell("J$i")->getValue());
                $project            = trim($data->getCell("K$i")->getValue());
                $part_customer      = trim($data->getCell("N$i")->getValue());
                $ctn_code_normal    = trim($data->getCell("O$i")->getValue());
                $set_description    = trim($data->getCell("P$i")->getValue());
                $set_dwg_code       = trim($data->getCell("T$i")->getValue());
                $set_ft2            = floatval(trim($data->getCell("X$i")->getValue()));
                $cost_rm            = floatval(trim($data->getCell("AO$i")->getValue()));
                $cost_dl            = floatval(trim($data->getCell("AP$i")->getValue()));
                $cost_oh            = floatval(trim($data->getCell("AQ$i")->getValue()));
                $cost_total         = floatval(trim($data->getCell("AR$i")->getValue()));
                $cost_total_oh      = floatval(trim($data->getCell("AS$i")->getValue()));
                $selling_price      = floatval(trim($data->getCell("AT$i")->getValue()));
                $prod_time          = intval(trim($data->getCell("AU$i")->getValue()));
                $snp                = intval(trim($data->getCell("AV$i")->getValue()));
                $moq                = intval(trim($data->getCell("AW$i")->getValue()));

                $set_status = 'Active';
                
                if($fg_code == "" && $set_type = "SET"){
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
                            ,:set_status
                            ,:set_create_datetime
                            ,'System'
                            ,:set_update_datetime
                            ,'System'
                          )"
                    );
                    $in->bindParam(':set_code', $set_code);
                    $in->bindParam(':set_cus_code', $cus_code);
                    $in->bindParam(':set_project', $project);
                    $in->bindParam(':set_comp_code', $comp_code);
                    $in->bindParam(':set_part_customer', $part_customer);
                    $in->bindParam(':set_ctn_code_normal', $ctn_code_normal);
                    $in->bindParam(':set_description', $set_description);
                    $in->bindParam(':set_dwg_code', $set_dwg_code);
                    $in->bindParam(':set_ft2', $set_ft2);
                    $in->bindParam(':set_cost_rm', $cost_rm);
                    $in->bindParam(':set_cost_dl', $cost_dl);
                    $in->bindParam(':set_cost_oh', $cost_oh);
                    $in->bindParam(':set_cost_total', $cost_total);
                    $in->bindParam(':set_cost_total_oh', $cost_total_oh);
                    $in->bindParam(':set_selling_price', $selling_price);
                    $in->bindParam(':set_prod_time', $prod_time);
                    $in->bindParam(':set_snp', $snp);
                    $in->bindParam(':set_moq', $moq);
                    $in->bindParam(':set_status', $set_status);
                    $in->bindParam(':set_create_datetime', $buffer_datetime);
                    $in->bindParam(':set_update_datetime', $buffer_datetime);
                    $in->execute();
                }
            }
            
            echo 'completex';
            // $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            var_dump($e);
            $db_con = null;
            return;
        }
    }else if($protocol == "UploadMixFile"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow(); //จำนวน row ทั้งหมดใน Active Sheet นั้นๆ
            $data_file = $_FILES['upfile']['tmp_name'];
            
            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq = trim($data->getCell("E$i")->getValue());
                $set_code = trim($data->getCell("G$i")->getValue());

                if($bom_uniq != ''){
                    // $up = $db_con->prepare("UPDATE tbl_bom_mst SET fg_codeset = :fg_codeset WHERE bom_uniq = :bom_uniq");
                    // $up->bindParam(':fg_codeset', $set_code);
                    // $up->bindParam(':bom_uniq', $bom_uniq);
                    // $up->execute();
                    $up = $db_con->prepare("UPDATE tbl_job_mst SET job_fg_codeset = '$fg_codeset' WHERE job_bom_id = '$bom_uniq' AND job_status != 'Cancel'");
                    $up->execute();

                    // $upvm = $vmi_con->prepare("UPDATE tbl_bom_mst SET bom_fg_code_set_abt = :fg_codeset WHERE bom_uniq = :bom_uniq");
                    // $upvm->bindParam(':fg_codeset', $set_code);
                    // $upvm->bindParam(':bom_uniq', $bom_uniq);
                    // $upvm->execute();
                }
            }
            
            echo 'complete';
            // $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            $db_con = null;
            return;
        }
    }else if($protocol == "UpdateSaleNotSale"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow(); //จำนวน row ทั้งหมดใน Active Sheet นั้นๆ
            $data_file = $_FILES['upfile']['tmp_name'];
            
            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq = trim($data->getCell("A$i")->getValue());
                $sale_type = trim($data->getCell("B$i")->getValue());

                $up = $db_con->prepare("UPDATE tbl_bom_mst SET sale_type = :sale_type WHERE bom_uniq = :bom_uniq");
                $up->bindParam(':sale_type', $sale_type);
                $up->bindParam(':bom_uniq', $bom_uniq);
                $up->execute();
            }
            
            echo 'complete';
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo 'fuck';
            $db_con = null;
            return;
        }
    }else if($protocol == "RollbackBoxtype"){
        try {
            $spreadsheet = $reader->load($_FILES['upfile']['tmp_name']);
            $data = $spreadsheet->getActiveSheet();
            $highestRow = $data->getHighestRow(); //จำนวน row ทั้งหมดใน Active Sheet นั้นๆ
            $data_file = $_FILES['upfile']['tmp_name'];
            
            for($i=2;$i<=$highestRow;$i++){
                $bom_uniq = trim($data->getCell("A$i")->getValue());
                $box_type = trim($data->getCell("B$i")->getValue());

                $up = $db_con->prepare("UPDATE tbl_bom_mst SET box_type = :box_type WHERE bom_uniq = :bom_uniq");
                $up->bindParam(':box_type', $box_type);
                $up->bindParam(':bom_uniq', $bom_uniq);
                $up->execute();
            }
            
            echo 'complete';
            $db_con->commit();
            $db_con = null;
            return;
        } catch(Exception $e) {
            echo 'fuck';
            $db_con = null;
            return;
        }
    }
?>