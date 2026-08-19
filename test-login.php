<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://sistem-event.pbjt.web.id/admin/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}

preg_match('/name="_token" value="([^"]+)"/', $response, $tokenMatch);
$token = $tokenMatch[1] ?? '';

$cookieStr = http_build_query($cookies, '', '; ');

$postFields = http_build_query([
    '_token' => $token,
    'username' => 'admin',
    'password' => '123'
]);

curl_setopt($ch, CURLOPT_URL, "https://sistem-event.pbjt.web.id/admin/login");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_COOKIE, $cookieStr);
$response2 = curl_exec($ch);

echo "--- HTTP RESPONSE HEADERS ---\n";
echo substr($response2, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
