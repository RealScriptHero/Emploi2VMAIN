<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "vendor/autoload.php not found\n";
    exit(1);
}
require $autoload;
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Group;

$names = ['DEV101','NET202','RES303','DEV102','DEV201','NET101','RES101'];
foreach ($names as $n) {
    Group::updateOrCreate(['name' => $n], ['description' => 'Group ' . $n]);
}
echo "Groups seeded\n";
