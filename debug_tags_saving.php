<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG TAGS SAVING ===\n\n";

// Check current state of soft_product_tag table
echo "Current soft_product_tag records:\n";
$productTags = DB::table('soft_product_tag')->get();
if ($productTags->isEmpty()) {
    echo "No records found in soft_product_tag table!\n";
} else {
    foreach ($productTags as $pt) {
        echo "- Product: {$pt->id_product}, Tag: {$pt->id_tag}, Lang: {$pt->id_lang}\n";
    }
}

echo "\nTesting saveTagsForLanguage method manually:\n";
$productId = 16;
$languageId = 2; // French
$testTags = ['test-tag-fr', 'another-tag-fr'];

echo "Would save tags for product $productId, language $languageId:\n";
echo "Tags: " . implode(', ', $testTags) . "\n";

// Simulate the saveTagsForLanguage method
echo "\n1. Deleting existing relationships...\n";
$deleted = DB::table('soft_product_tag')
    ->where('id_product', $productId)
    ->where('id_lang', $languageId)
    ->delete();
echo "Deleted $deleted existing relationships\n";

echo "\n2. Creating new tags and relationships...\n";
foreach ($testTags as $tagName) {
    $tagName = trim($tagName);
    if (!empty($tagName)) {
        $tagName = substr($tagName, 0, 32);
        
        // Check if tag exists
        $existingTag = DB::table('soft_tag')
            ->where('id_lang', $languageId)
            ->where('name', $tagName)
            ->first();
        
        if ($existingTag) {
            $tagId = $existingTag->id_tag;
            echo "- Using existing tag: $tagName (ID: $tagId)\n";
        } else {
            $tagId = DB::table('soft_tag')->insertGetId([
                'id_lang' => $languageId,
                'name' => $tagName,
            ]);
            echo "- Created new tag: $tagName (ID: $tagId)\n";
        }
        
        // Create product-tag relationship
        DB::table('soft_product_tag')->insert([
            'id_product' => $productId,
            'id_tag' => $tagId,
            'id_lang' => $languageId,
        ]);
        echo "- Created relationship: Product $productId -> Tag $tagId (Lang $languageId)\n";
    }
}

echo "\n3. Verification:\n";
$newProductTags = DB::table('soft_product_tag')
    ->where('id_product', $productId)
    ->where('id_lang', $languageId)
    ->get();

foreach ($newProductTags as $pt) {
    $tagName = DB::table('soft_tag')->where('id_tag', $pt->id_tag)->value('name');
    echo "- Product {$pt->id_product}, Tag {$pt->id_tag} ($tagName), Lang {$pt->id_lang}\n";
}
