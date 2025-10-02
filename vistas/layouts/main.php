<?php
/**
 * Layout principal de la aplicación
 */

// Incluir header
include __DIR__ . '/header.php';
?>

<!-- Contenido principal -->
<main class="main-content">
    <?php
    // Mostrar alertas flash si existen
    $alertas = ['success', 'error', 'warning', 'info'];
    foreach ($alertas as $tipo) {
        $mensaje = Sesion::flash($tipo);
        if ($mensaje) {
            echo "<div class='alert alert-{$tipo}'>";
            echo "<span>" . htmlspecialchars($mensaje) . "</span>";
            echo "<button type='button' class='alert-close' onclick='this.parentElement.remove()'>&times;</button>";
            echo "</div>";
        }
    }
    ?>
    
    <!-- Aquí se incluye el contenido específico de cada página -->
    <?php
    if (isset($contenido)) {
        echo $contenido;
    }
    ?>
</main>

<?php
// Incluir footer
include __DIR__ . '/footer.php';
?>