<?php
    function HTMLForm($db_con, $CFG, $rev_uniq){
        $dest = $CFG->path_main . $CFG->func_bom_issue . '/ini_revise_selling';

        $mail_head = $db_con->prepare("SELECT * FROM tbl_rev_selling WHERE rev_uniq = :rev_uniq");
        $mail_head->bindParam(':rev_uniq', $rev_uniq);
        $mail_head->execute();
        $mail_head_result = $mail_head->fetch(PDO::FETCH_ASSOC);


        $html = '
            <head>
                <style>
                    table, td, th {
                        border: 3px solid black;
                        padding: 10px;
                    }
                    table {
                        border-collapse: collapse;
                    }
                </style>
            </head>
            <body>
                '.nl2br($mail_head_result['rev_content']).'

                <table>
                    <thead>
                        <tr>
                            <th>BOM Uniq</th>
                            <th>FG Code</th>
                            <th>Part Customer</th>
                            <th>Old Price</th>
                            <th>New Price</th>
                        </tr>
                    </thead>
                    <tbody>';

                    $rev_content = $db_con->prepare("SELECT A.*, B.fg_code, B.part_customer FROM tbl_rev_selling_detail AS A LEFT JOIN tbl_bom_mst AS B ON det_bom_uniq = bom_uniq WHERE det_rev_uniq = :rev_uniq");
                    $rev_content->bindParam(':rev_uniq', $rev_uniq);
                    $rev_content->execute();
                    while($rev_result = $rev_content->fetch(PDO::FETCH_ASSOC)){
                        $html .= '
                            <tr>
                                <td>'.$rev_result['det_bom_uniq'].'</td>
                                <td>'.$rev_result['fg_code'].'</td>
                                <td>'.$rev_result['part_customer'].'</td>
                                <td>'.number_format($rev_result['det_old_price'], 2).'</td>
                                <td>'.number_format($rev_result['det_new_price'], 2).'</td>
                            </tr>
                        ';
                    }

                $html .= '
                    </tbody>
                </table>
                <a href="'.$CFG->wwwroot.'/bypass?usercode=GDJ00258&by_route='.$dest.'" style="margin-top: 20px; border-radius: 8px; background-color: #04AA6D; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;">Proceed to logging-in for approve here</a>

                <div style="font-family:Inter, Helvetica, sans-serif;font-size:12px;line-height:160%;text-align:left;">
                    <p>
                        If you have any questions please contact the system administrator (Head Office) /<br>Information Technology Department, Digitalize platform team. 
                        <br>Tel. +66 3811 0910-2, +66 3811 0915 Fax. +66 3811 0916
                    </p>
                    <p>
                        Yours sincerely,<br>
                        MRP Manufacturing
                    </p>
                    <p>
                        ----- It is only an automated notification email. ----- <br>
                        ----- Please, do not reply this e-mail address. --------
                    </p>
                </div>
            </body>
        ';

    return $html;
    }
?>