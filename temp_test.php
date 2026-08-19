<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    '/admin/scan', 'POST',
    ['nim' => '123456', 'event_id' => 1]
);
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);
echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
