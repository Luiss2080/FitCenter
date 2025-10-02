<?php
// Verificar que no haya sesión activa
if (Sesion::estaLogueado()) {
    header('Location: /dashboard');
    exit;
}

$titulo = 'Registro - CareCenter';
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
                        <h2 class="text-white mt-3">Únete a CareCenter</h2>
                        <p class="text-white-50 lead">Comienza tu viaje hacia una alimentación más saludable</p>
                    </div>
                    
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-user-md text-primary"></i>
                            <div>
                                <h5 class="text-white">Asesoría Profesional</h5>
                                <p class="text-white-75">Nutriólogos certificados para guiarte</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <i class="fas fa-utensils text-primary"></i>
                            <div>
                                <h5 class="text-white">Comidas Personalizadas</h5>
                                <p class="text-white-75">Planes alimenticios adaptados a ti</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <i class="fas fa-truck text-primary"></i>
                            <div>
                                <h5 class="text-white">Entrega a Domicilio</h5>
                                <p class="text-white-75">Recibe tus comidas donde estés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel derecho - Formulario de registro -->
            <div class="col-lg-6 d-flex align-items-center">
                <div class="auth-form-container">
                    <div class="auth-form-header text-center mb-4">
                        <h1 class="h3 text-gray-900">Crear Cuenta</h1>
                        <p class="text-muted">Completa los datos para registrarte</p>
                    </div>
                    
                    <!-- Mostrar errores -->
                    <div id="error-container" class="alert alert-danger d-none"></div>
                    
                    <form id="registro-form" class="auth-form" method="POST" action="/registro">
                        <input type="hidden" name="csrf_token" value="<?php echo Sesion::generarCsrf(); ?>">
                        
                        <!-- Información Personal -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title">Información Personal</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           placeholder="Tu nombre completo" required maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           placeholder="555-123-4567" required maxlength="15">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="genero" class="form-label">Género</label>
                                    <select class="form-select" id="genero" name="genero">
                                        <option value="">Seleccionar...</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                        <option value="O">Otro</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Cuenta -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title">Información de Cuenta</h6>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="tu@email.com" required maxlength="150">
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <div class="password-input">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Mínimo 8 caracteres" required minlength="8">
                                        <button type="button" class="password-toggle" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength mt-2">
                                        <div class="password-strength-bar"></div>
                                        <small class="password-strength-text">Fortaleza de contraseña</small>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                                    <div class="password-input">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                               placeholder="Repetir contraseña" required minlength="8">
                                        <button type="button" class="password-toggle" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dirección -->
                        <div class="form-section mb-4">
                            <h6 class="form-section-title">Dirección de Entrega</h6>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección Completa *</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2" 
                                          placeholder="Calle, número, colonia..." required maxlength="255"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ciudad" class="form-label">Ciudad *</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                           placeholder="Tu ciudad" required maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal *</label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                           placeholder="12345" required maxlength="10">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Términos y condiciones -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terminos" name="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="/terminos" target="_blank">Términos y Condiciones</a> 
                                    y la <a href="/privacidad" target="_blank">Política de Privacidad</a> *
                                </label>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    Deseo recibir información sobre promociones y noticias
                                </label>
                            </div>
                        </div>
                        
                        <!-- Botón de registro -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="btn-registro">
                            <span class="btn-text">Crear Cuenta</span>
                            <span class="btn-spinner d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...
                            </span>
                        </button>
                        
                        <!-- Enlaces adicionales -->
                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes una cuenta? 
                                <a href="/login" class="text-primary fw-medium">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </form>
                    
                    <!-- Información de contacto -->
                    <div class="auth-help mt-4 text-center">
                        <small class="text-muted">
                            ¿Necesitas ayuda? 
                            <a href="/contacto" class="text-primary">Contáctanos</a> o 
                            llama al <strong><?php echo TELEFONO_CONTACTO; ?></strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registro-form');
    const btnRegistro = document.getElementById('btn-registro');
    const errorContainer = document.getElementById('error-container');
    
    // Validación de contraseñas
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
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
    password.addEventListener('input', function() {
        const strength = checkPasswordStrength(this.value);
        updatePasswordStrength(strength);
    });
    
    // Validar coincidencia de contraseñas
    passwordConfirmation.addEventListener('input', function() {
        if (this.value !== password.value) {
            this.setCustomValidity('Las contraseñas no coinciden');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
    
    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity()) {
            btnRegistro.disabled = true;
            btnRegistro.querySelector('.btn-text').classList.add('d-none');
            btnRegistro.querySelector('.btn-spinner').classList.remove('d-none');
            
            // Enviar formulario por AJAX
            const formData = new FormData(this);
            
            fetch('/registro', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirigir o mostrar mensaje de éxito
                    window.location.href = data.redirect || '/login?registered=1';
                } else {
                    // Mostrar errores
                    showErrors(data.errors || ['Error al crear la cuenta']);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrors(['Error de conexión. Inténtalo de nuevo.']);
            })
            .finally(() => {
                btnRegistro.disabled = false;
                btnRegistro.querySelector('.btn-text').classList.remove('d-none');
                btnRegistro.querySelector('.btn-spinner').classList.add('d-none');
            });
        }
        
        this.classList.add('was-validated');
    });
    
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
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>