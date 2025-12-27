<?php

// Script to add the missing tags column
echo "=== Adding tags column to soft_product_lang table ===\n";

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
    
    // Check if tags column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM soft_product_lang LIKE 'tags'");
    $exists = $stmt->rowCount() > 0;
    
    if ($exists) {
        echo "Tags column already exists.\n";
    } else {
        echo "Adding tags column...\n";
        
        // Add the tags column
        $sql = "ALTER TABLE soft_product_lang ADD COLUMN tags TEXT NULL AFTER delivery_out_stock";
        $pdo->exec($sql);
        
        echo "Tags column added successfully.\n";
    }
    
    // Verify the column was added
    echo "\nVerifying column structure:\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM soft_product_lang LIKE 'tags'");
    $column = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($column) {
        echo "- {$column['Field']} ({$column['Type']}) " . ($column['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    } else {
        echo "ERROR: Column not found after creation.\n";
    }
    
    // Test updating a record with tags
    echo "\nTesting update with tags...\n";
    $stmt = $pdo->prepare("UPDATE soft_product_lang SET tags = ? WHERE id_product = ? AND id_shop = ? AND id_lang = ?");
    $result = $stmt->execute(['test,tag1,tag2', 1, 1, 1]);
    
    if ($result) {
        echo "Test update successful.\n";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT tags FROM soft_product_lang WHERE id_product = ? AND id_shop = ? AND id_lang = ?");
        $stmt->execute([1, 1, 1]);
        $tags = $stmt->fetchColumn();
        echo "Tags value: " . ($tags ?? 'NULL') . "\n";
    } else {
        echo "Test update failed.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General error: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
