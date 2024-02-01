<?php
require "config.php";

if (isset($_POST['link']) and !empty($_POST['link'])) {
    $Get_uuid = GetUUID($_POST['link']);
    if ($Get_uuid['success'] == true) {
        $result = getInfo($Get_uuid['result']);
        if ($result['msg'] !== "ok") {
            die(redirect("./index.php"));
        }
    } else {
        die(redirect("./index.php"));
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>page two</title>
        <style>
            table, th, td {
                border:1px solid black;
            }
        </style>
    </head>
    <body>
        <h1>نمایش اطلاعات</h1>
        <table>
            <tr>
                <th>نام</th>
                <th>ایمیل</th>
                <th>حجم دانلود</th>
                <th>حجم آپلود</th>
                <th>حجم کل</th>
                <th>زمان پایان</th>
                <th>ترافیک باقی مانده</th>
                <th>زمان باقی مانده</th>
                <th>حجم کل مصرف</th>
            </tr>
            <tr>
                <td><?php echo $result['name']; ?></td>
                <td><?php echo $result['email']; ?></td>
                <td><?php echo $result['down']; ?></td>
                <td><?php echo $result['up']; ?></td>
                <td><?php echo $result['total']; ?></td>
                <td><?php echo $result['expiryTime']; ?></td>
                <td><?php echo $result['remaining_trafic']; ?></td>
                <td><?php echo $result['remaining_days']; ?></td>
                <td><?php echo $result['using_all']; ?></td>
            </tr>
        </table>
    </body>
</html>