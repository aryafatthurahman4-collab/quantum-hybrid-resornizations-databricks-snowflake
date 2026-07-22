<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Check if user already exists
$existing = User::where('email', 'admin@admin.com')->first();
if ($existing) {
    $existing->password = Hash::make('admin123');
    $existing->save();
    echo "Updated: admin@admin.com / admin123" . PHP_EOL;
} else {
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
    ]);
    echo "Created: admin@admin.com / admin123" . PHP_EOL;
}

// Also ensure demo users are correct
echo PHP_EOL . "All users:" . PHP_EOL;
$users = User::all(['id','name','email','role']);
foreach ($users as $u) {
    echo "  [$u->role] $u->name - $u->email" . PHP_EOL;
}

echo PHP_EOL . "Try login with: admin@admin.com / admin123" . PHP_EOL;
