<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="<?php echo ASSETS_URL; ?>/img/logos/logo.png" alt="CareCenter" class="login-logo">
            <h1>Iniciar Sesión</h1>
            <p>Accede a tu cuenta de CareCenter</p>
        </div>
        
        <form class="login-form" action="/login" method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo Validador::generarCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       data-validate="required|email"
                       placeholder="tu@email.com"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <div class="input-group">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           data-validate="required|min:6"
                           placeholder="Tu contraseña"
                           required>
                    <button type="button" class="btn-toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group form-check">
                <input type="checkbox" id="remember" name="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Recordarme</label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
            
            <div class="login-links">
                <a href="/recuperar-contrasena" class="forgot-password">¿Olvidaste tu contraseña?</a>
                <div class="register-link">
                    ¿No tienes cuenta? <a href="/registro">Regístrate aquí</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: var(--spacing-lg);
}

.login-card {
    background: var(--white);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    padding: var(--spacing-xxl);
    width: 100%;
    max-width: 400px;
}

.login-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.login-logo {
    width: 80px;
    height: auto;
    margin-bottom: var(--spacing-md);
}

.login-header h1 {
    color: var(--dark-color);
    margin-bottom: var(--spacing-sm);
}

.login-header p {
    color: var(--gray-medium);
    margin-bottom: 0;
}

.form-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.input-group {
    position: relative;
}

.btn-toggle-password {
    position: absolute;
    right: var(--spacing-sm);
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-medium);
    cursor: pointer;
    padding: var(--spacing-xs);
}

.btn-login {
    width: 100%;
    margin: var(--spacing-lg) 0;
}

.login-links {
    text-align: center;
}

.forgot-password {
    display: block;
    margin-bottom: var(--spacing-md);
    color: var(--primary-color);
    font-size: 0.875rem;
}

.register-link {
    font-size: 0.875rem;
    color: var(--gray-medium);
}

.register-link a {
    color: var(--primary-color);
    font-weight: var(--font-weight-medium);
}

@media (max-width: 480px) {
    .login-card {
        padding: var(--spacing-lg);
        margin: var(--spacing-md);
    }
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Manejar envío del formulario
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('.btn-login');
    
    // Mostrar loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
    
    try {
        const response = await fetch('/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            CareCenter.showAlert('Sesión iniciada correctamente', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '/dashboard';
            }, 1000);
        } else {
            throw new Error(data.error || 'Error al iniciar sesión');
        }
        
    } catch (error) {
        CareCenter.showAlert(error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión';
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>