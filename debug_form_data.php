<?php

// Debug script to check form data and database operations
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\ProductLang;

echo "=== DEBUG FORM DATA ===\n\n";

// Check if we can connect to database
try {
    $records = DB::table('soft_product_lang')->limit(5)->get();
    echo "Database connection: OK\n";
    echo "Found " . $records->count() . " records in soft_product_lang\n\n";
    
    foreach ($records as $record) {
        echo "Record ID: {$record->id_product}-{$record->id_shop}-{$record->id_lang}\n";
        echo "Name: " . ($record->name ?? 'NULL') . "\n";
        echo "Meta Title: " . ($record->meta_title ?? 'NULL') . "\n";
        echo "Meta Description: " . ($record->meta_description ?? 'NULL') . "\n";
        echo "Description Short: " . ($record->description_short ?? 'NULL') . "\n";
        echo "Description: " . (strlen($record->description ?? '') > 50 ? substr($record->description, 0, 50) . "..." : ($record->description ?? 'NULL')) . "\n";
        echo "Tags: " . ($record->tags ?? 'NULL') . "\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
}

// Check the table structure
echo "\n=== TABLE STRUCTURE ===\n";
try {
    $columns = DB::select("SHOW COLUMNS FROM soft_product_lang");
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) " . ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "Error getting table structure: " . $e->getMessage() . "\n";
}

// Test a simple update
echo "\n=== TEST UPDATE ===\n";
try {
    $testData = [
        'name' => 'Test Name ' . date('Y-m-d H:i:s'),
        'meta_title' => 'Test Meta Title',
        'meta_description' => 'Test Meta Description',
        'description_short' => 'Test Short Description',
        'description' => 'Test Long Description',
        'tags' => 'tag1,tag2,tag3'
    ];
    
    $result = DB::table('soft_product_lang')
        ->where('id_product', 1)
        ->where('id_shop', 1)
        ->where('id_lang', 1)
        ->update($testData);
    
    echo "Update result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    echo "Affected rows: " . $result . "\n";
    
    // Verify the update
    $updated = DB::table('soft_product_lang')
        ->where('id_product', 1)
        ->where('id_shop', 1)
        ->where('id_lang', 1)
        ->first();
    
    if ($updated) {
        echo "Verification - Name: " . $updated->name . "\n";
        echo "Verification - Meta Title: " . $updated->meta_title . "\n";
        echo "Verification - Tags: " . $updated->tags . "\n";
    }
    
} catch (Exception $e) {
    echo "Update test error: " . $e->getMessage() . "\n";
}

echo "\n=== MODEL TEST ===\n";
try {
    $model = ProductLang::where('id_product', 1)
        ->where('id_shop', 1)
        ->where('id_lang', 1)
        ->first();
    
    if ($model) {
        echo "Model found\n";
        echo "Model fillable: " . implode(', ', $model->getFillable()) . "\n";
        echo "Model attributes: " . implode(', ', array_keys($model->getAttributes())) . "\n";
        
        // Test model save
        $model->name = 'Model Test ' . date('Y-m-d H:i:s');
        $model->meta_title = 'Model Meta Title';
        $saveResult = $model->save();
        echo "Model save result: " . ($saveResult ? 'SUCCESS' : 'FAILED') . "\n";
    } else {
        echo "Model not found\n";
    }
} catch (Exception $e) {
    echo "Model test error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG COMPLETE ===\n";
