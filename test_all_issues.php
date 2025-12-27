<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING ALL 3 ISSUES ===\n\n";

// Issue 1: Test routing - check if composite key URLs work
echo "1. ROUTING TEST:\n";
echo "Current URL structure should be: /admin/metas/{record}/edit\n";
echo "Composite key format: id_product-id_shop-id_lang\n";

$productRecords = DB::table('soft_product_lang')
    ->where('id_product', 16)
    ->orderBy('id_lang')
    ->get();

foreach($productRecords as $record) {
    $compositeKey = $record->id_product . '-' . ($record->id_shop ?? 1) . '-' . $record->id_lang;
    echo "- Record: Lang {$record->id_lang}, Composite Key: $compositeKey\n";
}

echo "\n2. TAGS TEST:\n";
// Check current tags state
echo "Current tags in database:\n";
$allTags = DB::table('soft_tag')->orderBy('id_lang')->get();
$langCounts = [];
foreach($allTags as $tag) {
    $langCounts[$tag->id_lang] = ($langCounts[$tag->id_lang] ?? 0) + 1;
}
foreach($langCounts as $lang => $count) {
    echo "- Language $lang: $count tags\n";
}

echo "\nProduct-tag relationships:\n";
$productTags = DB::table('soft_product_tag')->orderBy('id_lang')->get();
$langProductCounts = [];
foreach($productTags as $pt) {
    $langProductCounts[$pt->id_lang] = ($langProductCounts[$pt->id_lang] ?? 0) + 1;
}
foreach($langProductCounts as $lang => $count) {
    echo "- Language $lang: $count product-tag relationships\n";
}

echo "\n3. FILTER TEST:\n";
echo "Current filter configuration in MetasResource:\n";
echo "Should have placeholder('Todos los idiomas') and default(null)\n";

// Test if we can find any records with different languages
echo "\nLanguage distribution in ProductLang table:\n";
$langDistribution = DB::table('soft_product_lang')
    ->select('id_lang', DB::raw('count(*) as count'))
    ->groupBy('id_lang')
    ->orderBy('id_lang')
    ->get();

foreach($langDistribution as $dist) {
    echo "- Language {$dist->id_lang}: {$dist->count} records\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. For routing: Check if resolveRouteBinding in ProductLang is working\n";
echo "2. For tags: Ensure saveTagsForLanguage uses correct language ID\n";
echo "3. For filter: Verify SelectFilter configuration is applied\n";
