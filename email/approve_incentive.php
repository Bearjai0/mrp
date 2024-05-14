<?php
    function HTMLForm($db_con, $CFG, $inc_uniq){
        
        $mail_head = $db_con->prepare("SELECT A.*, B.user_code, B.user_name_en, B.user_position, B.user_email FROM tbl_sale_incentive AS A LEFT JOIN tbl_user AS B ON A.inc_now_in = B.user_code WHERE A.inc_uniq = :inc_uniq");
        $mail_head->bindParam(':inc_uniq', $inc_uniq);
        $mail_head->execute();
        $mail_head_result = $mail_head->fetch(PDO::FETCH_ASSOC);

        $dest = $CFG->path_main . $CFG->fol_sale_incentive . '/load_approve_incentive?user_code='.$mail_head_result['inc_now_in'] . '&inc_uniq=' . $mail_head_result['inc_uniq'];

        $html = '
            <!doctype html>
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
                    <head>
                        <title></title>
                    
                        <!--[if !mso]>
                    
                    <!-->
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    
                        <!--<![endif]-->
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <style type="text/css">
                        #outlook a {
                            padding: 0;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }
                        table,
                        td {
                            border-collapse: collapse;
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                        }
                        img {
                            border: 0;
                            height: auto;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                            -ms-interpolation-mode: bicubic;
                        }
                        p {
                            display: block;
                            margin: 13px 0;
                        }
                        </style>
                    
                        <!--[if mso]>
                            <noscript>
                            <xml>
                            <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                            </xml>
                            </noscript>
                            <![endif]-->
                    
                        <!--[if lte mso 11]>
                            <style type="text/css">
                            .mj-outlook-group-fix { width:100% !important; }
                            </style>
                            <![endif]-->
                    
                        <!--[if !mso]>
                    
                    <!-->
                        <link href="https://rsms.me/inter/inter.css" rel="stylesheet" type="text/css">
                        <style type="text/css">
                        @import url(https://rsms.me/inter/inter.css);
                        </style>
                    
                        <!--<![endif]-->
                        <style type="text/css">
                        @media only screen and (min-width:480px) {
                            .mj-column-per-100 {
                            width: 100% !important;
                            max-width: 100%;
                            }
                        }
                        </style>
                        <style media="screen and (min-width:480px)">
                        .moz-text-html .mj-column-per-100 {
                            width: 100% !important;
                            max-width: 100%;
                        }
                        </style>
                        <style type="text/css">
                        @media only screen and (max-width:480px) {
                            table.mj-full-width-mobile {
                            width: 100% !important;
                            }
                            td.mj-full-width-mobile {
                            width: auto !important;
                            }
                        }
                        </style>
                        <style type="text/css">
                        a,
                        span,
                        td,
                        th {
                            -webkit-font-smoothing: antialiased !important;
                            -moz-osx-font-smoothing: grayscale !important;
                        }
                        a {
                            color: #5865F2;
                            text-decoration: none;
                            font-weight: 500
                        }
                        a:hover {
                            color: #5865F2;
                            text-decoration: underline;
                        }
                        </style>
                    </head>
                    <body style="word-spacing:normal;background-color:#ffffff;">
                        <div style="background-color:#ffffff;">
                    
                        <!-- Polymer header -->
                    
                        <!-- Polymer header -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:13px 13px 37px;text-align:left;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:574px;" ><![endif]-->
                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="vertical-align:top;padding:0;">
                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                        <tbody>
                                                        <tr>
                                                            <td style="width:118px;"><img height="auto" src="https://lib.albatrosslogistic.com/library/images/company_logo/gdj_v1.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="118"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Title -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px 8px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                        <div style="font-family:Inter, Helvetica, sans-serif;font-size:32px;font-weight:600;line-height:140%;text-align:left;color:#000000;">Approve Incentive of '. date('F Y', strtotime($mail_head_result['inc_period'])) .'</div>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Message -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0 0 4px;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td style="vertical-align:top;padding:0;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                        <tbody>
                                                            <tr>
                                                            <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                                <div style="font-family:Inter, Helvetica, sans-serif;font-size:16px;line-height:160%;text-align:left;color:#000000;">
                                                                    <p>Hello <b>'.$mail_head_result['user_name_en'].'</b>,</p>
                                                                    <p>Please approve sale incentive on the MRP system with the following details...</p>
                                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                                        <tr>
                                                                            <td>Period &nbsp;: <b>'.date('F Y', strtotime($mail_head_result['inc_period'])).'</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Incentive &nbsp;&nbsp;&nbsp;: <b>'.number_format($mail_head_result['inc_total_incentive'], 0, '.', ',').'</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Remarks &nbsp;: <b>'.nl2br($mail_head_result['inc_remarks']).'</b></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- CTA -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:16px 0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td align="left" vertical-align="middle" style="font-size:0px;padding:0;word-break:break-word;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" role="presentation" style="border:none;border-radius:6px;cursor:auto;height:40px;mso-padding-alt:10px 25px;" valign="middle">
                                                                    <a target="_blank" href="'.$dest.'" style="display:inline-block;background:#000000;color:#ffffff;font-family:Inter, Helvetica, sans-serif;font-size:16px;font-weight:500;line-height:120%;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:6px;" target="_blank">Proceed to approve here</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Message -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td style="vertical-align:top;padding:0;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                        <tbody>
                                                            <tr>
                                                            <td align="left" style="font-size:0px;padding:0;word-break:break-word;color:#4d4d4d;">
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
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </body>
                </html>
            ';
        // echo $html;
        return $html;
    }


    function HTMLFormComplete($db_con, $CFG, $inc_uniq){
        $mail_head = $db_con->prepare("SELECT A.*, B.user_code, B.user_name_en, B.user_position, B.user_email FROM tbl_sale_incentive AS A LEFT JOIN tbl_user AS B ON A.inc_now_in = B.user_code WHERE A.inc_uniq = :inc_uniq");
        $mail_head->bindParam(':inc_uniq', $inc_uniq);
        $mail_head->execute();
        $mail_head_result = $mail_head->fetch(PDO::FETCH_ASSOC);

        $dest = $CFG->path_main . $CFG->fol_sale_incentive . '/load_approve_incentive?user_code='.$mail_head_result['inc_now_in'] . '&inc_uniq=' . $mail_head_result['inc_uniq'];

        $html = '
            <!doctype html>
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
                    <head>
                        <title></title>
                    
                        <!--[if !mso]>
                    
                    <!-->
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    
                        <!--<![endif]-->
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <style type="text/css">
                        #outlook a {
                            padding: 0;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }
                        table,
                        td {
                            border-collapse: collapse;
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                        }
                        img {
                            border: 0;
                            height: auto;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                            -ms-interpolation-mode: bicubic;
                        }
                        p {
                            display: block;
                            margin: 13px 0;
                        }
                        </style>
                    
                        <!--[if mso]>
                            <noscript>
                            <xml>
                            <o:OfficeDocumentSettings>
                            <o:AllowPNG/>
                            <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                            </xml>
                            </noscript>
                            <![endif]-->
                    
                        <!--[if lte mso 11]>
                            <style type="text/css">
                            .mj-outlook-group-fix { width:100% !important; }
                            </style>
                            <![endif]-->
                    
                        <!--[if !mso]>
                    
                    <!-->
                        <link href="https://rsms.me/inter/inter.css" rel="stylesheet" type="text/css">
                        <style type="text/css">
                        @import url(https://rsms.me/inter/inter.css);
                        </style>
                    
                        <!--<![endif]-->
                        <style type="text/css">
                        @media only screen and (min-width:480px) {
                            .mj-column-per-100 {
                            width: 100% !important;
                            max-width: 100%;
                            }
                        }
                        </style>
                        <style media="screen and (min-width:480px)">
                        .moz-text-html .mj-column-per-100 {
                            width: 100% !important;
                            max-width: 100%;
                        }
                        </style>
                        <style type="text/css">
                        @media only screen and (max-width:480px) {
                            table.mj-full-width-mobile {
                            width: 100% !important;
                            }
                            td.mj-full-width-mobile {
                            width: auto !important;
                            }
                        }
                        </style>
                        <style type="text/css">
                        a,
                        span,
                        td,
                        th {
                            -webkit-font-smoothing: antialiased !important;
                            -moz-osx-font-smoothing: grayscale !important;
                        }
                        a {
                            color: #5865F2;
                            text-decoration: none;
                            font-weight: 500
                        }
                        a:hover {
                            color: #5865F2;
                            text-decoration: underline;
                        }
                        </style>
                    </head>
                    <body style="word-spacing:normal;background-color:#ffffff;">
                        <div style="background-color:#ffffff;">
                    
                        <!-- Polymer header -->
                    
                        <!-- Polymer header -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:13px 13px 37px;text-align:left;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:574px;" ><![endif]-->
                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="vertical-align:top;padding:0;">
                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                        <tbody>
                                                        <tr>
                                                            <td style="width:118px;"><img height="auto" src="https://lib.albatrosslogistic.com/library/images/company_logo/gdj_v1.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="118"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Title -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px 8px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                        <div style="font-family:Inter, Helvetica, sans-serif;font-size:32px;font-weight:600;line-height:140%;text-align:left;color:#000000;">Approve Incentive of '. date('F Y', strtotime($mail_head_result['inc_period'])) .'</div>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Message -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0 0 4px;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td style="vertical-align:top;padding:0;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                        <tbody>
                                                            <tr>
                                                            <td align="left" style="font-size:0px;padding:0;word-break:break-word;">
                                                                <div style="font-family:Inter, Helvetica, sans-serif;font-size:16px;line-height:160%;text-align:left;color:#000000;">
                                                                    <p>Hello <b>Wanida Thongsuk</b>,</p>
                                                                    <p>The Sale Incentive information for the period of March 2024 has been approved. Please find the attached documents.</p>
                                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                                        <tr>
                                                                            <td>Period &nbsp;: <b>'.date('F Y', strtotime($mail_head_result['inc_period'])).'</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Incentive &nbsp;&nbsp;&nbsp;: <b>'.number_format($mail_head_result['inc_total_incentive'], 0, '.', ',').'</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Remarks &nbsp;: <b>'.nl2br($mail_head_result['inc_remarks']).'</b></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- CTA -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:16px 0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td align="left" vertical-align="middle" style="font-size:0px;padding:0;word-break:break-word;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" role="presentation" style="border:none;border-radius:6px;cursor:auto;height:40px;mso-padding-alt:10px 25px;" valign="middle">
                                                                    <a target="_blank" href="https://lib.albatrosslogistic.com/print/document/mrp/print_sale_incentive?inc_uniq='.$inc_uniq.'" style="display:inline-block;background:#000000;color:#ffffff;font-family:Inter, Helvetica, sans-serif;font-size:16px;font-weight:500;line-height:120%;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:6px;" target="_blank">Open summary file .pdf</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    
                        <!--[if mso | IE]></td></tr></table><![endif]-->
                    
                        <!-- Message -->
                    
                        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                        <div style="margin:0px auto;max-width:600px;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                            <tbody>
                                <tr>
                                <td style="direction:ltr;font-size:0px;padding:0 16px;text-align:center;">
                    
                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:568px;" width="568" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                    <div style="margin:0px auto;max-width:568px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                        <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
                    
                                            <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:568px;" ><![endif]-->
                                            <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                <tbody>
                                                    <tr>
                                                    <td style="vertical-align:top;padding:0;">
                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                        <tbody>
                                                            <tr>
                                                            <td align="left" style="font-size:0px;padding:0;word-break:break-word;color:#4d4d4d;">
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
                                                            </td>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </div>
                    
                                            <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                    
                                    <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </body>
                </html>
            ';
        // echo $html;
        return $html;
    }
?>