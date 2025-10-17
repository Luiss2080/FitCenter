<?php
/**
 * Header común para todas las páginas
 * Incluye el favicon y meta tags básicos
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - FitCenter' : 'FitCenter - Sistema de Gestión para Gimnasios'; ?></title>
    <link rel="icon" type="image/png" href="<?php echo isset($favicon_path) ? $favicon_path : '../../public/img/LogoFitCenter.png'; ?>">
    <link rel="stylesheet" href="<?php echo isset($css_path) ? $css_path : '../../public/css/app.css'; ?>">
    <?php if (isset($additional_css)): ?>
        <?php echo $additional_css; ?>
    <?php endif; ?>
</head>
