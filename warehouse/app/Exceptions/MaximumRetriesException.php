<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class MaximumRetriesException extends Exception
{
    public function report(): void
    {
        Log::error($this->message);
    }
}
