<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all(['id','name','email','password']);
echo 'Total users: ' . $users->count() . PHP_EOL . PHP_EOL;
foreach ($users as $u) {
    echo "ID: $u->id | Name: $u->name | Email: $u->email" . PHP_EOL;
    $common = ['password','admin','123456','admin123','password123'];  
    foreach ($common as $p) {
        if (Hash::check($p, $u->password)) {
            echo "  ==> PASSWORD FOUND: '$p'" . PHP_EOL;
            break;
        }
    }
    echo PHP_EOL;
}

// Also check what auth driver uses
echo "--- Auth config ---" . PHP_EOL;
echo "Guard: " . config('auth.defaults.guard') . PHP_EOL;
echo "Provider: " . config('auth.guards.web.provider') . PHP_EOL;
echo "User model: " . config('auth.providers.users.model') . PHP_EOL;
echo "Password field: " . config('auth.providers.users.password_field', 'default: password') . PHP_EOL;
