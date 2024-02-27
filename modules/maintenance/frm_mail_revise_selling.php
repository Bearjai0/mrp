<?php
    $v = '
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
            Dear K.Suphot
            CC: N.Arm
            
            เนื่องจากมีการปรับลดราคา ของลูกค้า TIT International Co,.Ltd.
            ตามที่ลูกค้าร้องขอ ทาง GDJ ได้ปรับลดราคาลง Modelละ 1% ตรงนี้จะทะยอยแก้ไขนะคะ
            เนื่องจากบางรายการยังมี PO. เก่าค้างอยู่ค่ะ
            รบกวนพี่สุพจน์ อนุมัติการแก้ไขราคาใน BOM ให้เป็นปัจจุบัน เพื่อออก Inv. ให้ตรงกับ PO.ให้ด้วยค่ะ
            รายละเอียด ตามด้านล่างค่ะ

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
                <tbody>
                    <tr>
                        <td>FG0001-03317</td>
                        <td>BAA23DC010R</td>
                        <td>TSESA-EB3Z16874B(FOT)-A</td>
                        <td>16.63</td>
                        <td>18.63</td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top: 20px; border-radius: 8px; background-color: #04AA6D; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px;">Proceed to logging-in for approve here</a>

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

    echo $v;
?>