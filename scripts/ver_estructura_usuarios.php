<?php
require_once 'config/conexion.php';

echo "Estructura de la tabla usuarios:\n";
echo "===============================\n";

$stmt = $pdo->query('DESCRIBE usuarios');
while($row = $stmt->fetch()) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}
?>