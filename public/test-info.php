<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='font-family: sans-serif; padding: 30px; background: #111827; color: #fff; min-height: 100vh;'>";
echo "<h1 style='color: #bef264;'>PadelHub Debug Info</h1>";
echo "<hr style='border: 1px solid #1f2937; margin: 20px 0;'>";

echo "<strong>PHP Version:</strong> " . phpversion() . " (Laravel 11 requires >= 8.2)<br><br>";
echo "<strong>vendor/autoload.php exists:</strong> " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? "<span style='color: #10b981;'>YES</span>" : "<span style='color: #ef4444;'>NO (Vendor folder is missing or not uploaded)</span>") . "<br><br>";
echo "<strong>storage directory writable:</strong> " . (is_writable(__DIR__ . '/../storage') ? "<span style='color: #10b981;'>YES</span>" : "<span style='color: #ef4444;'>NO (Check storage permissions)</span>") . "<br><br>";
echo "<strong>bootstrap/cache writable:</strong> " . (is_writable(__DIR__ . '/../bootstrap/cache') ? "<span style='color: #10b981;'>YES</span>" : "<span style='color: #ef4444;'>NO (Check bootstrap/cache permissions)</span>") . "<br><br>";

$envPath = __DIR__ . '/../.env';
$envExists = file_exists($envPath);
echo "<strong>.env file exists:</strong> " . ($envExists ? "<span style='color: #10b981;'>YES</span>" : "<span style='color: #ef4444;'>NO (.env file is missing)</span>") . "<br><br>";

echo "<hr style='border: 1px solid #1f2937; margin: 20px 0;'>";
echo "<h3>PHP Extensions Status:</h3>";
$extensions = ['pdo_mysql', 'mbstring', 'openssl', 'xml', 'curl', 'gd', 'zip'];
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "Extension <strong>$ext</strong>: " . ($loaded ? "<span style='color: #10b981;'>Loaded</span>" : "<span style='color: #ef4444;'>Not Loaded</span>") . "<br>";
}

echo "<hr style='border: 1px solid #1f2937; margin: 20px 0;'>";
echo "<h3>Database Connection Test from .env:</h3>";

if ($envExists) {
    // Parse .env manually
    $envContent = file_get_contents($envPath);
    $lines = explode("\n", $envContent);
    $config = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            // Remove quotes if any
            $val = trim($val, '"\'');
            $config[$key] = $val;
        }
    }

    $dbHost = $config['DB_HOST'] ?? '127.0.0.1';
    $dbPort = $config['DB_PORT'] ?? '3306';
    $dbName = $config['DB_DATABASE'] ?? '';
    $dbUser = $config['DB_USERNAME'] ?? '';
    $dbPass = $config['DB_PASSWORD'] ?? '';
    $appKey = $config['APP_KEY'] ?? '';

    echo "<strong>APP_KEY configured:</strong> " . (!empty($appKey) ? "<span style='color: #10b981;'>YES</span>" : "<span style='color: #ef4444;'>NO (Application Key is empty!)</span>") . "<br>";
    echo "<strong>DB Host:</strong> $dbHost<br>";
    echo "<strong>DB Database Name:</strong> $dbName<br>";
    echo "<strong>DB Username:</strong> $dbUser<br><br>";

    if (empty($dbName) || empty($dbUser)) {
        echo "<span style='color: #ef4444;'>Database credentials are empty in .env!</span><br>";
    } else {
        try {
            $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5 // 5 seconds timeout
            ];
            $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
            echo "<span style='color: #10b981; font-weight: bold;'>✔ SUCCESS: Connected to the database successfully!</span><br><br>";
            
            // Check if tables are migrated
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<strong>Total Tables Migrated:</strong> " . count($tables) . "<br>";
            if (count($tables) > 0) {
                echo "<strong>Tables list:</strong> " . implode(', ', $tables) . "<br>";
            } else {
                echo "<span style='color: #yellow-400; font-weight: bold;'>⚠️ WARNING: Database connection works, but NO tables found! Please migrate database.</span><br>";
            }
        } catch (PDOException $e) {
            echo "<span style='color: #ef4444; font-weight: bold;'>❌ CONNECTION FAILED:</span> " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }
} else {
    echo "<span style='color: #ef4444;'>Cannot test database because .env file is missing.</span><br>";
}

echo "</div>";
