<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG CURRENT TAGS QUERY ===\n\n";

// Test the exact query used in EditMeta
$productId = 16;
$languageId = 2; // French

echo "Testing query for product $productId, language $languageId:\n";

$productTags = DB::table('soft_product_tag')
    ->join('soft_tag', 'soft_product_tag.id_tag', '=', 'soft_tag.id_tag')
    ->where('soft_product_tag.id_product', $productId)
    ->where('soft_product_tag.id_lang', $languageId)
    ->pluck('soft_tag.name')
    ->toArray();

echo "Tags found: " . implode(', ', $productTags) . "\n";
echo "Count: " . count($productTags) . "\n";

echo "\nAll product-tag relationships for product $productId:\n";
$allTags = DB::table('soft_product_tag')
    ->join('soft_tag', 'soft_product_tag.id_tag', '=', 'soft_tag.id_tag')
    ->where('soft_product_tag.id_product', $productId)
    ->select('soft_product_tag.id_lang', 'soft_tag.name')
    ->get();

foreach($allTags as $tag) {
    echo "- Lang {$tag->id_lang}: {$tag->name}\n";
}

echo "\nAll tags in database:\n";
$allDbTags = DB::table('soft_tag')->get();
foreach($allDbTags as $tag) {
    echo "- ID {$tag->id_tag}, Lang {$tag->id_lang}: {$tag->name}\n";
}
