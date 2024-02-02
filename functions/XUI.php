<?php
function login()
{
    return auth();
}
function auth()
{
    global $XUI;
    if (!file_exists($XUI['cookie'])) {
        $url = $XUI['url'] . 'login';
        $data = ['username' => $XUI['username'], 'password' => $XUI['password']];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        if (strpos($response, '"success":false') !== false) throw new Exception($response);
        curl_close($ch);

        return $response;
    } else {
        return "file exists!";
    }
}
function curl($action, $body = [], $POST = false, $option = [])
{

    global $XUI;
    $method_request = ($POST == true) ? "POST" : "GET";

    switch ($action) {

        default:
            $final_url = $XUI['url'] . "panel/api/inbounds/" . $action;
            break;
    }

    $ch = curl_init($final_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method_request);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($ch, CURLOPT_COOKIEFILE, $XUI['cookie']);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $XUI['cookie']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    curl_close($ch);

    return $response;
}
function jsonEncode($json)
{
    return json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
function getInfo($uuid)
{
    $response = json_decode(curl('list', [], false, []), true);
    $time = time();

    $res = [];

    foreach ($response['obj'] as $inbound) {
        $count_settings = json_decode($inbound["settings"], true)['clients'];
        for ($i = 0; $i < count($count_settings); $i++) {
            if ($count_settings[$i]['id'] == $uuid) {
                $email = $count_settings[$i]['email'];
                for ($i = 0; $i < count($inbound["clientStats"]); $i++) {
                    $setting_client = json_decode($inbound['settings'], true)['clients'];
                    if ($inbound["clientStats"][$i]['email'] == $email and $setting_client[$i]['email'] == $email) {
                        $count_clientStats = $inbound["clientStats"];

                        if ($inbound['expiryTime'] == 0) {
                            $time_expire = "نامحدود";
                            $remaining_days = "نامحدود";
                            if ($count_clientStats[$i]['expiryTime'] == 0) {
                                $time_expire = "نامحدود";
                                $remaining_days = "نامحدود";
                            } else {
                                $time_exp = date('Y-m-d', $count_clientStats[$i]['expiryTime'] / 1000);
                                $time_now = jdate('Y/m/d', $time);
                                $time_expire = jdate('Y/m/d', strtotime($time_exp));
                                $remaining_days = DateDifference($time_now, $time_expire);
                            }
                        } else {
                            $time_exp = date('Y-m-d', $inbound['expiryTime'] / 1000);
                            $time_now = jdate('Y/m/d', $time);
                            $time_expire = jdate('Y/m/d', strtotime($time_exp));
                            $remaining_days = DateDifference($time_now, $time_expire);
                        }

                        if ($time_expire != "نامحدود") {
                            if (($inbound['expiryTime'] / 1000) < $time) {
                                $status = false;
                            } else {
                                if ($inbound['enable'] == 0) {
                                    $status = false;
                                } else {
                                    if ($setting_client[$i]['enable'] == 0) {
                                        $status = false;
                                    } else {
                                        $status = true;
                                    }
                                }
                            }
                        } else {
                            if ($inbound['enable'] == 0) {
                                $status = false;
                            } else {
                                if ($setting_client[$i]['enable'] == 0) {
                                    $status = false;
                                } else {
                                    $status = true;
                                }
                            }
                        }

                        if ($count_clientStats[$i]['total'] !== "نامحدود") {
                            $remaining_trafic = formatBytes($count_clientStats[$i]['total'] - ($count_clientStats[$i]['up'] + $count_clientStats[$i]['down']));
                        } else {
                            $remaining_trafic = "نامحدود";
                        }

                        $res = [
                            'msg' => "ok",
                            'name' => $inbound['remark'] . " - " . $count_clientStats[$i]['email'],
                            'uuid' => $count_settings[$i]['id'],
                            'id' => $count_clientStats[$i]['id'],
                            'inboundId' => $count_clientStats[$i]['inboundId'],
                            'email' => $count_clientStats[$i]['email'],
                            'up' => formatBytes($count_clientStats[$i]['up']),
                            'down' => formatBytes($count_clientStats[$i]['down']),
                            'using_all' => formatBytes($count_clientStats[$i]['down'] + $count_clientStats[$i]['up']),
                            'remaining_trafic' => $remaining_trafic,
                            'expiryTime' => $time_expire,
                            'remaining_days' => $remaining_days,
                            'total' => ($count_clientStats[$i]['total'] == 0) ? "نامحدود" : formatBytes($count_clientStats[$i]['total']),
                            'reset' => $count_clientStats[$i]['reset'],
                            'status' => ($status == 1) ? "فعال" : "غیرفعال",
                            'status2' => $inbound['enable'],
                        ];
                        break;
                    }
                }
                break;
            }
        }
    }
    return $res;
}
function DateDifference($firstDate, $secondDate)
{
    list($fdY, $fdM, $fdD) = explode('/', $firstDate);
    list($sdY, $sdM, $sdD) = explode('/', $secondDate);
    $fts = jmktime(0, 0, 0, $fdM, $fdD, $fdY);
    $sts = jmktime(0, 0, 0, $sdM, $sdD, $sdY);
    $diff = $sts - $fts;
    return round($diff / 86400);
}
function redirect($url)
{
    if (!headers_sent()){
        header("Location: $url");
    }else{
        echo "<script type='text/javascript'>window.location.href='$url'</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=$url'/></noscript>";
    }
    exit;
}
function GetUUID($link) {
    if (strstr($link, "vmess://")) {
        $without_wmess = substr($link, 8);
        $decode_link = json_decode(base64_decode($without_wmess,448),true);
        $success = ($decode_link['id'] == NULL) ? false : true;
        $result = ($decode_link['id'] == NULL) ? NULL : $decode_link['id'];
    } else {
        $success = false;
        $result = NULL;
    }
    return [
        'success' => $success,
        'result' => $result
    ];
}
function formatBytes($size, $precision = 2)
{
    if ($size <= 0) {
        return "0";
    } else {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
} 