<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking group_discount_rules table...\n";
$rules = App\Models\GroupDiscountRule::all();
echo "Total: {$rules->count()} rules\n";

if ($rules->count() > 0) {
    echo "\nRules:\n";
    foreach ($rules as $r) {
        echo "ID: {$r->id}, Group: {$r->id_group}, Name: {$r->group_name}, Discount: {$r->discount_percentage}%, Status: {$r->status}\n";
    }
} else {
    echo "No rules found. Creating test rule...\n";
    
    // Create test rule
    $testRule = App\Models\GroupDiscountRule::create([
        'id_group' => 1,
        'group_name' => 'VIP Test',
        'discount_percentage' => 15.00,
        'status' => 'active',
        'user_id' => 1,
    ]);
    
    echo "Test rule created with ID: {$testRule->id}\n";
}

echo "\nChecking bulk_discount_history table...\n";
$historyCount = App\Models\BulkDiscountHistory::count();
echo "Total: {$historyCount} history records\n";
