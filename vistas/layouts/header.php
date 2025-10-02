<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CareCenter - Sistema de gestión nutricional y catering">
    <meta name="author" content="CareCenter Development Team">
    <meta name="csrf-token" content="<?php echo Validador::generarCsrfToken(); ?>">
    
    <title><?php echo $titulo ?? 'CareCenter - Gestión Nutricional'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo ASSETS_URL; ?>/img/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/app.css">
    <?php if (isset($cssAdicional) && is_array($cssAdicional)): ?>
        <?php foreach ($cssAdicional as $css): ?>
            <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/<?php echo $css; ?>.css">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">
    <!-- Loader global -->
    <div class="global-loader" style="display: none;">
        <div class="loader-backdrop">
            <div class="loader-content">
                <div class="spinner"></div>
                <p>Cargando...</p>
            </div>
        </div>
    </div>
    
    <!-- Container de alertas -->
    <div class="alert-container"></div>
    
    <!-- Header de navegación -->
    <?php if (!isset($ocultarNav) || !$ocultarNav): ?>
        <header class="navbar">
            <div class="container-fluid">
                <div class="navbar-brand">
                    <img src="<?php echo ASSETS_URL; ?>/img/logos/logo.png" alt="CareCenter" class="logo">
                    <span class="brand-text">CareCenter</span>
                </div>
                
                <?php if (Sesion::estaLogueado()): ?>
                    <nav class="navbar-nav">
                        <div class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-user"></i>
                                <?php echo Sesion::obtener('usuario_nombre'); ?>
                            </button>
                            <div class="dropdown-menu">
                                <a href="/perfil" class="dropdown-item">
                                    <i class="fas fa-user"></i> Mi Perfil
                                </a>
                                <a href="/configuracion" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="/logout" method="POST" class="dropdown-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo Validador::generarCsrfToken(); ?>">
                                    <button type="submit" class="dropdown-item text-error">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </nav>
                <?php endif; ?>
            </div>
        </header>
    <?php endif; ?>