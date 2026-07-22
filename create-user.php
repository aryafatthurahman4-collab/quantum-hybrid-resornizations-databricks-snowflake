<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'aryafatthurahman4@gmail.com')->first();
if (!$user) {
    $user = new User;
    $user->name = 'Arya Fatturahman';
    $user->email = 'aryafatthurahman4@gmail.com';
    $user->password = Hash::make('268456Arya');
    $user->role = 'admin';
    $user->save();
    echo "created\n";
} else {
    $user->name = 'Arya Fatturahman';
    $user->password = Hash::make('268456Arya');
    $user->role = 'admin';
    $user->save();
    echo "updated\n";
}
