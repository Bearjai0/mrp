<?php
    function HTMLForm($CFG, $user_code, $user_name, $fg_codeset, $cus_code, $owner_name, $package_type, $volume, $sell_target, $dwg_code, $cost_total, $due_date, $remarks){
        $dest = $CFG->path_main . $CFG->cost_request . '/ini_cost_request';
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
                                                        <td style="width:118px;"><img height="auto" src="https://kb.albatrosslogistic.com/library/images/company_logo/gdj_v1.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="118"></td>
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
                                                    <div style="font-family:Inter, Helvetica, sans-serif;font-size:32px;font-weight:600;line-height:140%;text-align:left;color:#000000;">Approval Costing.</div>
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
                                                                <p>Hello <b>'.$user_name.'</b>,</p>
                                                                <p>Please approve New Costing on the MRP system with the following details...</p>
                                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                                    <tr>
                                                                        <td>FG Codeset &nbsp;: <b>'.$fg_codeset.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Customer &nbsp;&nbsp;&nbsp;&nbsp;: <b>'.$cus_code.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Due Date &nbsp;&nbsp;&nbsp;&nbsp;: <b>'.$due_date.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Package Type&nbsp;: <b>'.$package_type.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Volume &nbsp;: <b>'.$volume.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Sell Target : <b>'.$sell_target.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Cost Total &nbsp;&nbsp; : <b>'.$cost_total.'</b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Remarks &nbsp;: <b>'.nl2br($remarks).'</b></td>
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
                                                                <a target="_blank" href="'.$dest.'" style="display:inline-block;background:#000000;color:#ffffff;font-family:Inter, Helvetica, sans-serif;font-size:16px;font-weight:500;line-height:120%;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:6px;" target="_blank">Proceed to logging-in for approval here</a>
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