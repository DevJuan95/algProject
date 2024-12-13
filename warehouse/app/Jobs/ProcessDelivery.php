<?php

namespace App\Jobs;

use App\Services\Purchases\PurchasesProcessor;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessDelivery implements ShouldQueue
{
    use Queueable;

    /**
     * Get the delivery data.
     * @return array
     */
    public function delivery(): array
    {
        return $this->delivery;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $delivery
    )
    {
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(PurchasesProcessor $processor): void
    {
        $processor->run($this->delivery);
    }

}
