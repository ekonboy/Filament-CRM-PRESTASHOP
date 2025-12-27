<?php

// Load Laravel environment
require_once 'vendor/autoload.php';

// Simple database structure check
echo "=== Database Structure Check ===\n";

// Load .env file manually
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Connect to database using environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$database = $_ENV['DB_DATABASE'] ?? 'prestashop';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection: SUCCESS\n\n";
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE soft_product_lang");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "soft_product_lang table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']}) " . ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . " " . ($column['Key'] ? "KEY: {$column['Key']}" : "") . "\n";
    }
    
    // Check if specific columns exist
    $requiredColumns = ['name', 'meta_description', 'description_short', 'description', 'meta_title', 'tags'];
    $existingColumns = array_column($columns, 'Field');
    
    echo "\nColumn check:\n";
    foreach ($requiredColumns as $col) {
        $exists = in_array($col, $existingColumns);
        echo "- $col: " . ($exists ? "EXISTS" : "MISSING") . "\n";
    }
    
    // Check sample data
    echo "\nSample data check:\n";
    $stmt = $pdo->prepare("SELECT * FROM soft_product_lang WHERE id_product = ? AND id_shop = ? AND id_lang = ? LIMIT 1");
    $stmt->execute([1, 1, 1]);
    $sample = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($sample) {
        echo "Sample record found for (1,1,1):\n";
        foreach ($sample as $key => $value) {
            if (is_string($value) && strlen($value) > 50) {
                echo "- $key: " . substr($value, 0, 50) . "...\n";
            } else {
                echo "- $key: " . ($value ?? 'NULL') . "\n";
            }
        }
    } else {
        echo "No record found for id_product=1, id_shop=1, id_lang=1\n";
        
        // Try to find any record
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM soft_product_lang");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        echo "Total records in table: $count\n";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT id_product, id_shop, id_lang FROM soft_product_lang LIMIT 5");
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "First 5 records:\n";
            foreach ($records as $rec) {
                echo "- Product: {$rec['id_product']}, Shop: {$rec['id_shop']}, Lang: {$rec['id_lang']}\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}

echo "\n=== Check Complete ===\n";
