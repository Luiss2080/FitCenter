<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'Iniciar Sesión - CareCenter'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Meta tags de seguridad -->
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="row min-vh-100 no-gutters">
            <!-- Panel izquierdo - Información de la empresa -->
            <div class="col-lg-6 d-none d-lg-flex auth-left-panel">
                <div class="auth-left-content">
                    <div class="auth-brand">
                        <img src="/assets/img/logo-white.png" alt="CareCenter" class="auth-logo">
                        <h1 class="auth-brand-title">CareCenter</h1>
                        <p class="auth-brand-subtitle">Asesoramiento nutricional y catering profesional</p>
                    </div>
                    
                    <div class="auth-features">
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-apple-alt"></i>
                            </div>
                            <div class="auth-feature-content">
                                <h3>Nutrición Personalizada</h3>
                                <p>Planes nutricionales adaptados a cada necesidad individual</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="auth-feature-content">
                                <h3>Catering Saludable</h3>
                                <p>Comidas balanceadas preparadas por expertos culinarios</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <div class="auth-feature-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="auth-feature-content">
                                <h3>Entrega Garantizada</h3>
                                <p>Servicio de entrega confiable y puntual a domicilio</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel derecho - Formulario de login -->
            <div class="col-lg-6 d-flex align-items-center">
                <div class="auth-form-container">
                    <div class="auth-form-header">
                        <!-- Logo para dispositivos móviles -->
                        <div class="d-lg-none text-center mb-4">
                            <img src="/assets/img/logo.png" alt="CareCenter" class="auth-logo-mobile">
                            <h2 class="auth-title-mobile">CareCenter</h2>
                        </div>
                        
                        <h2 class="auth-form-title">Iniciar Sesión</h2>
                        <p class="auth-form-subtitle">Accede a tu cuenta para continuar</p>
                    </div>
                    
                    <!-- Mensajes de alerta -->
                    <?php if ($mensaje): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($mensaje); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulario de login -->
                    <form id="loginForm" method="POST" action="/login" class="auth-form" novalidate>
                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?php echo Sesion::generarCsrf(); ?>">
                        
                        <!-- Campo Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="tu.email@ejemplo.com"
                                    required 
                                    autocomplete="email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                >
                                <div class="invalid-feedback">
                                    Por favor ingresa un email válido.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campo Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Tu contraseña"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    La contraseña es requerida.
                                </div>
                            </div>
                        </div>
                        
                        <!-- Opciones adicionales -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="recordar" 
                                        name="recordar"
                                    >
                                    <label class="form-check-label" for="recordar">
                                        Recordarme
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a href="/recuperar-contrasena" class="auth-link">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        </div>
                        
                        <!-- Botón de envío -->
                        <button type="submit" class="btn btn-primary btn-login w-100" id="btnLogin">
                            <span class="btn-text">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>Iniciando sesión...
                            </span>
                        </button>
                        
                        <!-- Enlaces adicionales -->
                        <div class="auth-footer mt-4">
                            <p class="text-center text-muted">
                                ¿Eres nuevo en CareCenter?
                                <br>
                                <small class="text-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contacta con nuestro administrador para obtener una cuenta
                                </small>
                            </p>
                        </div>
                    </form>
                    
                    <!-- Información de contacto -->
                    <div class="auth-contact mt-4 pt-4 border-top">
                        <div class="row text-center">
                            <div class="col-4">
                                <a href="tel:<?php echo TELEFONO_CONTACTO; ?>" class="auth-contact-item">
                                    <i class="fas fa-phone"></i>
                                    <small>Llámanos</small>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="mailto:<?php echo EMAIL_CONTACTO; ?>" class="auth-contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <small>Email</small>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="<?php echo WHATSAPP_URL; ?>" class="auth-contact-item" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    <small>WhatsApp</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/auth.js"></script>
    
    <script>
        // Configuración del formulario de login
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const btnLogin = document.getElementById('btnLogin');
            const togglePassword = document.querySelector('.toggle-password');
            const passwordField = document.getElementById('password');
            
            // Toggle mostrar/ocultar contraseña
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
            
            // Validación del formulario
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!this.checkValidity()) {
                    e.stopPropagation();
                    this.classList.add('was-validated');
                    return;
                }
                
                // Mostrar estado de carga
                btnLogin.disabled = true;
                btnLogin.querySelector('.btn-text').classList.add('d-none');
                btnLogin.querySelector('.btn-loading').classList.remove('d-none');
                
                // Enviar formulario
                const formData = new FormData(this);
                
                fetch('/login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostrar mensaje de éxito
                        showAlert('success', data.mensaje || 'Login exitoso');
                        
                        // Redirigir después de un breve delay
                        setTimeout(() => {
                            window.location.href = data.redirect || '/dashboard';
                        }, 1000);
                    } else {
                        throw new Error(data.error || 'Error desconocido');
                    }
                })
                .catch(error => {
                    showAlert('danger', error.message || 'Error al iniciar sesión');
                    
                    // Restaurar botón
                    btnLogin.disabled = false;
                    btnLogin.querySelector('.btn-text').classList.remove('d-none');
                    btnLogin.querySelector('.btn-loading').classList.add('d-none');
                });
            });
            
            // Función para mostrar alertas
            function showAlert(type, message) {
                const alertsContainer = document.querySelector('.auth-form-header');
                const existingAlert = alertsContainer.querySelector('.alert');
                
                if (existingAlert) {
                    existingAlert.remove();
                }
                
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                alertsContainer.insertAdjacentElement('afterend', alert);
            }
        });
    </script>
</body>
</html>