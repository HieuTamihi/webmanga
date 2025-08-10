<?php
require __DIR__ . '/vendor/autoload.php';

use Google_Service_Drive;

$client = new Google_Client();
$client->setClientId('605253876823-2o2r0oei146spea2b4tm4mmp9m24c9vv.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost');
$client->addScope(Google_Service_Drive::DRIVE);
$client->setAccessType('offline');
$client->setPrompt('consent');

// Lấy link đăng nhập
$authUrl = $client->createAuthUrl();
echo "Mở link này trong trình duyệt:\n$authUrl\n";

// Nhập code thủ công
echo "Nhập code từ URL sau khi đăng nhập: ";
$authCode = trim(fgets(STDIN));

// Lấy token
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
echo "Refresh Token: " . $accessToken['refresh_token'] . "\n";
