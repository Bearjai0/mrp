<?php
    function SetPrefix($prefix, $count){
        if($count == 0){
            $prefix .= '0001';
        }else if($count < 9){
            $prefix .= '000' . ($count+1);
        }else if($count < 99){
            $prefix .= '00' . ($count+1);
        }else if($count < 999){
            $prefix .= '0' . ($count+1);
        }else{
            $prefix .= ($count+1);
        }

        return $prefix;
    }

    function SetPrefix5Digit($prefix, $count){
        if($count == 0){
            $prefix .= '00001';
        }else if($count < 9){
            $prefix .= '0000' . ($count+1);
        }else if($count < 99){
            $prefix .= '000' . ($count+1);
        }else if($count < 999){
            $prefix .= '00' . ($count+1);
        }else if($count < 9999){
            $prefix .= '0' . ($count+1);
        }else{
            $prefix .= ($count+1);
        }

        return $prefix;
    }

    function PadNumber($num, $desiredLength) {
        return str_pad($num, $desiredLength, '0', STR_PAD_LEFT);
    }

    function GenerateRandomString($length = 5){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generate_bom_uniq($db_con, $package_type, $fg_type){
        $prefix = '';
        $prodlist = $db_con->prepare("SELECT TOP(1) prod_code FROM tbl_product_type_mst WHERE prod_type = :prod_type AND topic = 'T'");
        $prodlist->bindParam(':prod_type', $package_type);
        $prodlist->execute();
        $prodlistResult = $prodlist->fetch(PDO::FETCH_ASSOC);

        if($fg_type == 'SET'){
            $prefix = 'FG0010' . '-';
        }else{
            $prefix = 'FG' . $prodlistResult['prod_code'] . '-';
        }

        $getuniq = $db_con->query("SELECT TOP(1) bom_uniq FROM tbl_bom_mst WHERE bom_uniq LIKE '%$prefix%' ORDER BY bom_uniq DESC");
        $getuniqResult = $getuniq->fetch(PDO::FETCH_ASSOC);

        $padlist = PadNumber(intval(explode("-", $getuniqResult['bom_uniq'])[1]) + 1, 5);

        return $prefix . $padlist;
    }
?>