<?php

namespace Core\Providers;

interface ICriptografiaProvider
{
    public function encrypt(string $d): string;
    public function decrypt(string $d): string;
}
