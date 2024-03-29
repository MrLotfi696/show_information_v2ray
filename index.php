<?php
session_start(['name' => "Mrlotfi"]);
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

    <body class="container">
        <?php if (isset($_SESSION['notfound']) && $_SESSION['notfound'] == true) { ?>
        <div class="box-alert position-absolute start-50 translate-middle w-75">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>خطا!</strong> سرویس شما یافت نشد، لطفا در وارد کردن لینک دقت فرمایید.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['notfound']); } ?>
        <?php if (isset($_SESSION['errorUUID']) && $_SESSION['errorUUID'] == true) { ?>
        <div class="box-alert position-absolute start-50 translate-middle w-75">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>خطا!</strong> لینک وارد شده صحیح نمی باشد.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($_SESSION['errorUUID']); } ?>
        <div class="position-absolute top-50 start-50 translate-middle w-75 rounded p-5 shadow-sm bg-box">
            <h1 class="text-center mb-4 font-yekan-ExtraBold">نمایش اطلاعات سرویس</h1>
            <hr>
            <form action="result.php" method="post" class="mt-4 mb-2">
                <div class="mb-3">
                    <label for="input_link" class="form-label color-text fs-5 font-yekan-bold font-size-label">لینک را وارد کنید</label>
                    <input type="text" class="form-control" id="input_link" name="input_link" aria-describedby="input_link_des">
                    <div id="input_link_des" class="form-text font-yekan-reqular font-size-help">لینک را به درستی وارد کنید.</div>
                </div>
                <button type="submit" class="btn btn-primary font-yekan-bold font-size-button">بررسی</button>
            </form>
            <div class="button_bottom">
                <a href="connection-training.html" class="btn btn-warning font-yekan-bold font-size-button">آموزش اتصال</a>
                <a href="connection-software.html" class="btn btn-warning font-yekan-bold font-size-button">دانلود نرم افزار های اتصال</a>
            </div>
            
        </div>
        
        <script src="./assets/js/bootstrap.bundle.min.js"></script>
    </body>

</html>