<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check all tables that contain 'tag'
$tables = DB::select('SHOW TABLES');
echo "Tables containing 'tag':\n";
foreach($tables as $table) {
    foreach($table as $value) {
        if(strpos($value, 'tag') !== false) {
            echo "- $value\n";
            
            // Show structure of tag-related tables
            if(strpos($value, 'product_tag') !== false || strpos($value, 'tag_product') !== false) {
                echo "  Structure:\n";
                $columns = DB::select("DESCRIBE $value");
                foreach($columns as $col) {
                    echo "    - {$col->Field} ({$col->Type})\n";
                }
            }
        }
    }
}

// Also check if there's a direct relationship in product_lang table
echo "\nChecking product_lang table for tag references:\n";
$columns = DB::select("DESCRIBE soft_product_lang");
foreach($columns as $col) {
    if(strpos($col->Field, 'tag') !== false) {
        echo "- {$col->Field} ({$col->Type})\n";
    }
}
