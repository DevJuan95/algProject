<?php

namespace App\Contracts;

use Illuminate\Http\Client\ConnectionException;

interface Marketplace
{
    /**
     * @param string $name
     * @return int
     * @throws ConnectionException
     */
    public function buy(string $name): int;
}
