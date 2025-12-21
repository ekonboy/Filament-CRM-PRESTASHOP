<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking ProductGroupDiscount records...\n";
$count = App\Models\ProductGroupDiscount::count();
echo "Total: {$count} records\n";

if ($count > 0) {
    echo "\nRecords:\n";
    $records = App\Models\ProductGroupDiscount::all();
    foreach ($records as $r) {
        echo "ID: {$r->id}, Product: {$r->id_product}, Group: {$r->id_group}, Discount: {$r->discount_percentage}%\n";
    }
}

echo "\nChecking BulkDiscountHistory records...\n";
$historyCount = App\Models\BulkDiscountHistory::count();
echo "Total: {$historyCount} history records\n";

if ($historyCount > 0) {
    echo "\nHistory:\n";
    $history = App\Models\BulkDiscountHistory::all();
    foreach ($history as $h) {
        echo "Batch: {$h->batch_id}, Discount: {$h->discount_percent}%, Status: {$h->status}, Products: {$h->products_affected}\n";
    }
}
