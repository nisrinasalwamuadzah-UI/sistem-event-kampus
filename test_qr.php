<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
        ->size(250)
        ->errorCorrection('H')
        ->margin(1)
        ->merge(public_path('images/logo.png'), 0.25, true)
        ->generate('12345678');
    echo "SUCCESS";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
} catch (\Error $e) {
    echo "FATAL: " . $e->getMessage();
}
