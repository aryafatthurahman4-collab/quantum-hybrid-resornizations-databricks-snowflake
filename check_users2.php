<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo 'User table: ' . (new User())->getTable() . PHP_EOL;
echo 'User fillable: ' . json_encode((new User())->getFillable()) . PHP_EOL;

$user = User::where('email', 'admin@hr.com')->first();
if ($user) {
    echo 'Found user: ' . $user->email . PHP_EOL;
    echo 'Hash check(password): ' . (Hash::check('password', $user->password) ? 'PASS' : 'FAIL') . PHP_EOL;

    if (Auth::attempt(['email' => 'admin@hr.com', 'password' => 'password'])) {
        echo 'Auth::attempt SUCCESS' . PHP_EOL;
    } else {
        echo 'Auth::attempt FAILED' . PHP_EOL;
        echo 'Check password field config...' . PHP_EOL;
    }
}

echo PHP_EOL . '--- Session config ---' . PHP_EOL;
echo 'Driver: ' . config('session.driver') . PHP_EOL;
echo 'Domain: ' . config('session.domain') . PHP_EOL;
echo 'Cookie: ' . config('session.cookie') . PHP_EOL;
