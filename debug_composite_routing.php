<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG COMPOSITE ROUTING ===\n\n";

// Test the ProductLang resolveRouteBinding method
echo "Testing ProductLang resolveRouteBinding:\n";

$productLang = new \App\Models\ProductLang();

// Test composite key: 16-1-2 (product 16, shop 1, lang 2 - French)
$testKey = '16-1-2';
echo "Testing key: $testKey\n";

try {
    $record = $productLang->resolveRouteBinding($testKey);
    if ($record) {
        echo "SUCCESS: Found record - Product: {$record->id_product}, Lang: {$record->id_lang}, Shop: {$record->id_shop}\n";
        echo "Name: {$record->name}\n";
    } else {
        echo "FAILED: No record found\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nTesting other keys:\n";
$keys = ['16-1-1', '16-1-3', '15-1-1'];
foreach ($keys as $key) {
    try {
        $record = $productLang->resolveRouteBinding($key);
        if ($record) {
            echo "- $key: Product {$record->id_product}, Lang {$record->id_lang}\n";
        } else {
            echo "- $key: Not found\n";
        }
    } catch (Exception $e) {
        echo "- $key: ERROR - " . $e->getMessage() . "\n";
    }
}
