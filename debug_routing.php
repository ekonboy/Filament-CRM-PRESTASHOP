<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG ROUTING ===\n\n";

// Check what records exist in ProductLang table
echo "ProductLang records:\n";
$records = DB::table('soft_product_lang')
    ->select('id_product', 'id_lang', 'id_shop', 'name')
    ->where('id_product', 16) // Example product
    ->orderBy('id_lang')
    ->get();

foreach($records as $record) {
    echo "- Product: {$record->id_product}, Lang: {$record->id_lang}, Shop: {$record->id_shop}, Name: {$record->name}\n";
}

echo "\nChecking if composite key method works:\n";
// Test the getKey method from ProductLang model
$productLang = new \App\Models\ProductLang();
$productLang->id_product = 16;
$productLang->id_lang = 2; // French
$productLang->id_shop = 1;
echo "Composite key: " . $productLang->getKey() . "\n";

echo "\nActual records in database:\n";
$allRecords = DB::table('soft_product_lang')
    ->where('id_product', 16)
    ->get();

foreach($allRecords as $record) {
    $key = $record->id_product . '-' . $record->id_shop . '-' . $record->id_lang;
    echo "Record key: $key (ID: $record->id_product, Lang: $record->id_lang)\n";
}
