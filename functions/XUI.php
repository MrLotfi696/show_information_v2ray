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
function curl($body = [], $POST = false)
{

    global $XUI;
    $method_request = ($POST == true) ? "POST" : "GET";

    $ch = curl_init($XUI['url']);
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