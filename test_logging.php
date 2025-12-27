<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING LOGGING ===\n\n";

// Clear the log file first
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    file_put_contents($logFile, '');
    echo "Log file cleared.\n";
}

// Simulate what should happen when editing product 16, language 2
echo "Simulating edit for product 16, language 2 (French):\n";

// Test the tag loading query
$productTags = DB::table('soft_product_tag')
    ->join('soft_tag', 'soft_product_tag.id_tag', '=', 'soft_tag.id_tag')
    ->where('soft_product_tag.id_product', 16)
    ->where('soft_product_tag.id_lang', 2)
    ->pluck('soft_tag.name')
    ->toArray();

echo "Tags found: " . implode(', ', $productTags) . "\n";

// Test what would happen if we save some tags
$testTags = ['test-tag-fr', 'another-tag-fr'];
echo "Would save tags: " . implode(', ', $testTags) . "\n";

echo "\nNow try editing in the browser and check the log file.\n";
echo "Expected log entries should show:\n";
echo "- TAGS LOAD DEBUG with correct product and language\n";
echo "- TAGS SAVE DEBUG with correct product and language\n";
