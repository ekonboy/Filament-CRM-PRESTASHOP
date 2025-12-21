<?php

namespace App\Jobs;

use App\Models\BulkDiscountHistory;
use App\Models\PriceChangeLog;
use App\Models\Product;
use App\Models\ProductGroupDiscount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApplyPriceDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $batchId,
        public array $groupIds,
        public float $discountPercent,
        public ?int $userId = null,
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        // Create history record
        $history = BulkDiscountHistory::create([
            'batch_id' => $this->batchId,
            'group_ids' => $this->groupIds,
            'discount_percent' => $this->discountPercent,
            'user_id' => $this->userId,
            'status' => 'processing',
            'notes' => 'Procesando descuentos masivos',
        ]);

        $totalProducts = 0;

        foreach ($this->groupIds as $groupId) {
            Product::query()
                ->where('active', 1)
                ->where('price', '>', 0)
                ->chunkById(100, function ($products) use ($groupId, &$totalProducts) {
                    foreach ($products as $product) {
                        // Get next available ID for the new record
                        $maxId = ProductGroupDiscount::max('id') ?? 0;
                        
                        // Create or update product group discount
                        ProductGroupDiscount::updateOrCreate(
                            ['id_product' => $product->id_product, 'id_group' => $groupId],
                            [
                                'id' => $maxId + 1,
                                'discount_percentage' => $this->discountPercent
                            ]
                        );

                        // Log the price change
                        $oldPrice = (float) $product->price;
                        $newPrice = round($oldPrice * (1 - ($this->discountPercent / 100)), 6);

                        PriceChangeLog::create([
                            'batch_id' => $this->batchId,
                            'id_product' => $product->id_product,
                            'old_price' => $oldPrice,
                            'new_price' => $newPrice,
                            'discount_percent' => $this->discountPercent,
                            'group_ids' => $this->groupIds,
                            'status' => 'applied',
                            'user_id' => $this->userId,
                        ]);

                        $totalProducts++;
                    }
                }, 'id_product');
        }

        // Update history record with final status
        $history->update([
            'products_affected' => $totalProducts,
            'status' => 'completed',
            'notes' => "Descuentos aplicados a {$totalProducts} productos",
        ]);
    }
}
