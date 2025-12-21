<?php

namespace App\Jobs;

use App\Models\PriceChangeLog;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RollbackPriceDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $batchId,
    ) {}

    public function handle(): void
    {
        PriceChangeLog::query()
            ->where('batch_id', $this->batchId)
            ->where('status', 'applied')
            ->chunkById(100, function ($logs) {
                foreach ($logs as $log) {
                    Product::query()
                        ->where('id_product', $log->id_product)
                        ->update(['price' => $log->old_price]);

                    $log->update(['status' => 'rolled_back']);
                }
            });
    }
}
