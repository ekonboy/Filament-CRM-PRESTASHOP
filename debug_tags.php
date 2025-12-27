<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG TAGS ===\n\n";

// Check soft_tag table
echo "Tags in soft_tag table:\n";
$tags = DB::table('soft_tag')->get();
foreach($tags as $tag) {
    echo "- ID: {$tag->id_tag}, Lang: {$tag->id_lang}, Name: {$tag->name}\n";
}

echo "\nProduct-Tag relationships in soft_product_tag table:\n";
$productTags = DB::table('soft_product_tag')->get();
foreach($productTags as $pt) {
    echo "- Product: {$pt->id_product}, Tag: {$pt->id_tag}, Lang: {$pt->id_lang}\n";
}

echo "\nChecking specific product (example: product 15):\n";
$productId = 15;
$productTags = DB::table('soft_product_tag')
    ->join('soft_tag', 'soft_product_tag.id_tag', '=', 'soft_tag.id_tag')
    ->where('soft_product_tag.id_product', $productId)
    ->get();

echo "Tags for product {$productId}:\n";
foreach($productTags as $pt) {
    echo "- Tag: {$pt->name} (Lang: {$pt->id_lang})\n";
}

echo "\nTotal counts:\n";
echo "- Total tags: " . DB::table('soft_tag')->count() . "\n";
echo "- Total product-tag relationships: " . DB::table('soft_product_tag')->count() . "\n";
