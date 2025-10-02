<?php
$titulo = 'Recuperar Contraseña - CareCenter';
$css_adicional = ['/assets/css/login.css'];
?>

<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<div class="auth-container">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Panel izquierdo - Información -->
            <div class="col-lg-6 d-none d-lg-flex auth-info-panel">
                <div class="auth-info-content">
                    <div class="text-center mb-5">
                        <img src="/assets/img/logos/logo-white.svg" alt="CareCenter" height="60">
                        <h2 class="text-white mt-3">Recupera tu Acceso</h2>
                        <p class="text-white-50 lead">Te ayudamos a restablecer tu contraseña de forma segura</p>
                    </div>
                    
                    <div class="recovery-steps">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h6 class="text-white">Ingresa tu email</h6>
                                <p class="text-white-75 small">El email asociado a tu cuenta</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h6 class="text-white">Revisa tu bandeja</h6>
                                <p class="text-white-75 small">Te enviaremos un enlace seguro</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h6 class="text-white">Crea nueva contraseña</h6>
                                <p class="text-white-75 small">Establece una contraseña segura</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel derecho - Formularios -->
            <div class="col-lg-6 d-flex align-items-center">
                <div class="auth-form-container">
                    
                    <!-- Formulario: Solicitar recuperación -->
                    <div id="form-solicitar" class="recovery-form">
                        <div class="auth-form-header text-center mb-4">
                            <i class="fas fa-lock text-primary mb-3" style="font-size: 2rem;"></i>
                            <h1 class="h3 text-gray-900">¿Olvidaste tu contraseña?</h1>
                            <p class="text-muted">Ingresa tu email y te enviaremos un enlace para restablecerla</p>
                        </div>
                        
                        <div id="error-container" class="alert alert-danger d-none"></div>
                        <div id="success-container" class="alert alert-success d-none"></div>
                        
                        <form id="recovery-form" class="auth-form" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Sesion::generarCsrf(); ?>">
                            <input type="hidden" name="action" value="solicitar">
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Email registrado</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                           placeholder="tu@email.com" required maxlength="150" autocomplete="email">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="btn-solicitar">
                                <span class="btn-text">Enviar enlace de recuperación</span>
                                <span class="btn-spinner d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Enviando...
                                </span>
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">¿Recordaste tu contraseña? 
                                <a href="/login" class="text-primary fw-medium">Volver al login</a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Formulario: Nueva contraseña (solo se muestra si hay token válido) -->
                    <?php if (isset($_GET['token']) && !empty($_GET['token'])): ?>
                    <div id="form-restablecer" class="recovery-form">
                        <div class="auth-form-header text-center mb-4">
                            <i class="fas fa-key text-success mb-3" style="font-size: 2rem;"></i>
                            <h1 class="h3 text-gray-900">Establecer nueva contraseña</h1>
                            <p class="text-muted">Crea una contraseña segura para tu cuenta</p>
                        </div>
                        
                        <div id="reset-error-container" class="alert alert-danger d-none"></div>
                        
                        <form id="reset-form" class="auth-form" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo Sesion::generarCsrf(); ?>">
                            <input type="hidden" name="action" value="restablecer">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                            
                            <div class="mb-3">
                                <label for="nueva_password" class="form-label">Nueva contraseña</label>
                                <div class="password-input">
                                    <input type="password" class="form-control form-control-lg" id="nueva_password" name="nueva_password" 
                                           placeholder="Mínimo 8 caracteres" required minlength="8">
                                    <button type="button" class="password-toggle" data-target="nueva_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar"></div>
                                    <small class="password-strength-text">Fortaleza de contraseña</small>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirmar_password" class="form-label">Confirmar contraseña</label>
                                <div class="password-input">
                                    <input type="password" class="form-control form-control-lg" id="confirmar_password" name="confirmar_password" 
                                           placeholder="Repetir contraseña" required minlength="8">
                                    <button type="button" class="password-toggle" data-target="confirmar_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100 mb-3" id="btn-restablecer">
                                <span class="btn-text">Establecer nueva contraseña</span>
                                <span class="btn-spinner d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Actualizando...
                                </span>
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Información de contacto -->
                    <div class="auth-help mt-4 text-center">
                        <small class="text-muted">
                            ¿Sigues teniendo problemas? 
                            <a href="/contacto" class="text-primary">Contáctanos</a> o 
                            llama al <strong><?php echo TELEFONO_CONTACTO; ?></strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recoveryForm = document.getElementById('recovery-form');
    const resetForm = document.getElementById('reset-form');
    const errorContainer = document.getElementById('error-container');
    const successContainer = document.getElementById('success-container');
    
    // Formulario de solicitud de recuperación
    if (recoveryForm) {
        recoveryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnSolicitar = document.getElementById('btn-solicitar');
            const formData = new FormData(this);
            
            btnSolicitar.disabled = true;
            btnSolicitar.querySelector('.btn-text').classList.add('d-none');
            btnSolicitar.querySelector('.btn-spinner').classList.remove('d-none');
            
            fetch('/recuperar-contrasena', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successContainer.innerHTML = data.message || 'Se ha enviado un enlace de recuperación a tu email.';
                    successContainer.classList.remove('d-none');
                    errorContainer.classList.add('d-none');
                    recoveryForm.reset();
                } else {
                    showErrors(data.errors || ['Error al procesar la solicitud']);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrors(['Error de conexión. Inténtalo de nuevo.']);
            })
            .finally(() => {
                btnSolicitar.disabled = false;
                btnSolicitar.querySelector('.btn-text').classList.remove('d-none');
                btnSolicitar.querySelector('.btn-spinner').classList.add('d-none');
            });
        });
    }
    
    // Formulario de restablecimiento
    if (resetForm) {
        const nuevaPassword = document.getElementById('nueva_password');
        const confirmarPassword = document.getElementById('confirmar_password');
        
        // Mostrar/ocultar contraseña
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                const icon = this.querySelector('i');
                
                if (target.type === 'password') {
                    target.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    target.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Verificar fortaleza de contraseña
        nuevaPassword.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updatePasswordStrength(strength);
        });
        
        // Validar coincidencia de contraseñas
        confirmarPassword.addEventListener('input', function() {
            if (this.value !== nuevaPassword.value) {
                this.setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (this.checkValidity()) {
                const btnRestablecer = document.getElementById('btn-restablecer');
                const formData = new FormData(this);
                
                btnRestablecer.disabled = true;
                btnRestablecer.querySelector('.btn-text').classList.add('d-none');
                btnRestablecer.querySelector('.btn-spinner').classList.remove('d-none');
                
                fetch('/recuperar-contrasena', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirigir al login con mensaje de éxito
                        window.location.href = '/login?reset=1';
                    } else {
                        showResetErrors(data.errors || ['Error al restablecer la contraseña']);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showResetErrors(['Error de conexión. Inténtalo de nuevo.']);
                })
                .finally(() => {
                    btnRestablecer.disabled = false;
                    btnRestablecer.querySelector('.btn-text').classList.remove('d-none');
                    btnRestablecer.querySelector('.btn-spinner').classList.add('d-none');
                });
            }
            
            this.classList.add('was-validated');
        });
    }
    
    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }
    
    function updatePasswordStrength(strength) {
        const bar = document.querySelector('.password-strength-bar');
        const text = document.querySelector('.password-strength-text');
        
        const levels = ['Muy débil', 'Débil', 'Regular', 'Buena', 'Excelente'];
        const colors = ['#dc3545', '#fd7e14', '#ffc107', '#198754', '#0d6efd'];
        
        bar.style.width = (strength * 20) + '%';
        bar.style.backgroundColor = colors[strength - 1] || '#dee2e6';
        text.textContent = levels[strength - 1] || 'Ingresa una contraseña';
        text.style.color = colors[strength - 1] || '#6c757d';
    }
    
    function showErrors(errors) {
        errorContainer.innerHTML = '<ul class="mb-0">' + 
            errors.map(error => '<li>' + error + '</li>').join('') + 
            '</ul>';
        errorContainer.classList.remove('d-none');
        successContainer.classList.add('d-none');
    }
    
    function showResetErrors(errors) {
        const resetErrorContainer = document.getElementById('reset-error-container');
        resetErrorContainer.innerHTML = '<ul class="mb-0">' + 
            errors.map(error => '<li>' + error + '</li>').join('') + 
            '</ul>';
        resetErrorContainer.classList.remove('d-none');
        resetErrorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>