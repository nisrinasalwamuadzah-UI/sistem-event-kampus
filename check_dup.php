<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dupNims = App\Models\Mahasiswa::select('nim')->groupBy('nim')->havingRaw('COUNT(*) > 1')->count();
$dupNames = App\Models\Mahasiswa::select('nama')->groupBy('nama')->havingRaw('COUNT(*) > 1')->count();

echo "Duplicate NIMs: $dupNims\n";
echo "Duplicate Names: $dupNames\n";
