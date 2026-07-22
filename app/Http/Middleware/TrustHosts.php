<?php
namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    public function hosts(): array
    {
        $hosts = [
            'aryafatthurahman.my.id',
            'www.aryafatthurahman.my.id',
            'hris-itk-ijk.test',
            '*.hris-itk-ijk.test',
            'localhost',
            '127.0.0.1',
            '[::1]',
        ];

        $configured = env('TRUSTED_HOSTS', '');
        if ($configured) {
            $hosts = array_values(array_unique(array_merge($hosts, array_filter(array_map('trim', explode(',', $configured))))));
        }

        return $hosts;
    }
}
