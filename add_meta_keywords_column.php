<?php

// Script to add the missing meta_keywords column
echo "=== Adding meta_keywords column to soft_product_lang table ===\n";

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

// Connect to database
$host = $_ENV['DB_HOST'] ?? 'localhost';
$database = $_ENV['DB_DATABASE'] ?? 'prestashop';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection: SUCCESS\n\n";
    
    // Check if meta_keywords column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM soft_product_lang LIKE 'meta_keywords'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "meta_keywords column already exists.\n";
    } else {
        echo "Adding meta_keywords column...\n";
        
        // Add the meta_keywords column
        $sql = "ALTER TABLE soft_product_lang ADD COLUMN meta_keywords VARCHAR(255) NULL AFTER meta_description";
        $pdo->exec($sql);
        
        echo "meta_keywords column added successfully.\n";
    }
    
    // Verify the column was added
    echo "\nVerifying column structure:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM soft_product_lang LIKE 'meta_keywords'");
    $column = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($column) {
        echo "- {$column['Field']} ({$column['Type']}) " . ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    } else {
        echo "ERROR: Column not found after creation.\n";
    }
    
    // Test updating a record with meta_keywords
    echo "\nTesting update with meta_keywords...\n";
    $stmt = $pdo->prepare("UPDATE soft_product_lang SET meta_keywords = ? WHERE id_product = ? AND id_shop = ? AND id_lang = ?");
    $result = $stmt->execute(['test,keyword1,keyword2', 1, 1, 1]);
    
    if ($result) {
        echo "Test update successful.\n";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT meta_keywords FROM soft_product_lang WHERE id_product = ? AND id_shop = ? AND id_lang = ?");
        $stmt->execute([1, 1, 1]);
        $meta_keywords = $stmt->fetchColumn();
        echo "meta_keywords value: " . ($meta_keywords ?? 'NULL') . "\n";
    } else {
        echo "Test update failed.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
