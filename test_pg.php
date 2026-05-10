<?php
include 'db.php';

try {
    $pg = conectarPG();
    echo "✅ PostgreSQL conectado OK\n";
    
    // Verificar que las tablas existen
    $stmt = $pg->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tablas = $stmt->fetchAll();
    echo "Tablas encontradas:\n";
    foreach ($tablas as $t) {
        echo "  - " . $t['table_name'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
