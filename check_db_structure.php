<?php

// Simple script to check database structure
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking soft_product_lang table structure ===\n";

try {
    $columns = DB::select("SHOW COLUMNS FROM soft_product_lang");
    
    echo "Table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) " . ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    
    // Check if tags column exists
    $hasTagsColumn = false;
    foreach ($columns as $column) {
        if ($column->Field === 'tags') {
            $hasTagsColumn = true;
            break;
        }
    }
    
    echo "\nTags column exists: " . ($hasTagsColumn ? 'YES' : 'NO') . "\n";
    
    // Test a sample record
    echo "\n=== Sample record ===\n";
    $sample = DB::table('soft_product_lang')
        ->where('id_product', 1)
        ->where('id_shop', 1)
        ->where('id_lang', 1)
        ->first();
    
    if ($sample) {
        echo "Sample record found:\n";
        foreach ((array)$sample as $key => $value) {
            if (is_string($value) && strlen($value) > 100) {
                echo "- $key: " . substr($value, 0, 100) . "...\n";
            } else {
                echo "- $key: " . ($value ?? 'NULL') . "\n";
            }
        }
    } else {
        echo "No sample record found for id_product=1, id_shop=1, id_lang=1\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
