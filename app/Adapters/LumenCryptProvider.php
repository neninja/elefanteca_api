<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Crypt;
use Core\Providers\ICriptografiaProvider;

class LumenCryptProvider implements ICriptografiaProvider
{
    public function encrypt(string $d): string {
        return Crypt::encrypt($d);
    }

    public function decrypt(string $d): string {
        return Crypt::decrypt($d);
    }
}
