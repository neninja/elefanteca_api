<?php

namespace Core\Traits;

use DateTime;
use DateTimeImmutable;

trait Clock
{
    protected function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
