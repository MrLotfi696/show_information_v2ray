<?php
session_start(['name' => "Mrlotfi"]);
require "config.php";

if (! isset($_POST['input_link']) || empty($_POST['input_link'])) {
    $_SESSION['errorUUID'] = true;
    die(redirect("./index.php"));
}
$Get_uuid = GetUUID($_POST['input_link']);
    if ($Get_uuid['success']) {
        $result = getInfo($Get_uuid['result']);
        if ($result['msg'] !== "ok") {
            $_SESSION['notfound'] = true;
            die(redirect("./index.php"));
        }
    } else {
        $_SESSION['errorUUID'] = true;
        die(redirect("./index.php"));
    }
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>information</title>
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/css/style.css">
        <link rel="stylesheet" href="./assets/css/font-style.css">
    </head>

    <body class="container mb-5 mt-5">

        <div class="box-info position-absolute top-50 start-50 translate-middle w-75 rounded shadow-sm bg-box">

            <h1 class="text-center mb-4 font-yekan-ExtraBold">نمایش اطلاعات سرویس</h1>
            <hr>

            <div class="boxs-inforamtion p-2">

                <div class="box-one-inforamtion border w-100 rounded shadow-sm col">

                    <div class="d-flex justify-content-between mt-3 p-3">

                        <div class="key">
                            <p>نام سرویس</p>
                            <p>وضعیت سرویس</p>
                            <p>پروتکل سرویس</p>
                            <p>تاریخ انقضا</p>
                            <p>حجم کل</p>
                        </div>
                        <div class="value">
                            <p><?= $result['name']; ?></p>
                            <p><?= $result['status']; ?></p>
                            <p><?= $result['protocol']; ?></p>
                            <p><?= $result['expiryTime']; ?></p>
                            <p><?= $result['total']; ?></p>
                        </div>
                        
                    </div>
                
                </div>
                
                <div class="box-two-inforamtion border w-100 rounded shadow-sm col">
                    
                    <div class="d-flex justify-content-between mt-3 p-3">

                        <div class="key">
                            <p>حجم دانلود</p>
                            <p>حجم آپلود</p>
                            <p>حجم کل مصرف</p>
                            <p>ترافیک باقی مانده</p>
                            <p>زمان باقی مانده (روز)</p>
                        </div>
                        <div class="value">
                            <p><?= $result['down']; ?></p>
                            <p><?= $result['up']; ?></p>
                            <p><?= $result['using_all']; ?></p>
                            <p><?= $result['remaining_trafic']; ?></p>
                            <p><?= $result['remaining_days']; ?></p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <script src="./assets/js/bootstrap.bundle.min.js"></script>
    </body>

</html>