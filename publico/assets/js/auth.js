/**
 * JavaScript para funcionalidad de autenticación - CareCenter
 * Validación, AJAX, UX mejorada para formularios de auth
 */

// ====================================================================
// CONFIGURACIÓN GLOBAL
// ====================================================================
const AuthConfig = {
    // Endpoints
    endpoints: {
        login: '/login',
        logout: '/logout',
        register: '/registro',
        recovery: '/recuperar-contrasena',
        resetPassword: '/restablecer-contrasena'
    },
    
    // Configuración de validación
    validation: {
        minPasswordLength: 6,
        emailPattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        phonePattern: /^[\+]?[0-9]{10,15}$/
    },
    
    // Configuración de UI
    ui: {
        loadingDelay: 300,
        redirectDelay: 1500,
        alertAutoHide: 5000
    }
};

// ====================================================================
// UTILIDADES
// ====================================================================
class AuthUtils {
    /**
     * Hacer petición AJAX
     */
    static async makeRequest(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || `HTTP error! status: ${response.status}`);
            }
            
            return data;
        } catch (error) {
            console.error('Request error:', error);
            throw error;
        }
    }
    
    /**
     * Validar email
     */
    static validateEmail(email) {
        return AuthConfig.validation.emailPattern.test(email);
    }
    
    /**
     * Validar contraseña
     */
    static validatePassword(password) {
        return password && password.length >= AuthConfig.validation.minPasswordLength;
    }
    
    /**
     * Validar teléfono
     */
    static validatePhone(phone) {
        return AuthConfig.validation.phonePattern.test(phone.replace(/\s/g, ''));
    }
    
    /**
     * Mostrar/ocultar loading en botón
     */
    static toggleButtonLoading(button, loading = true) {
        const textElement = button.querySelector('.btn-text');
        const loadingElement = button.querySelector('.btn-loading');
        
        if (loading) {
            button.disabled = true;
            if (textElement) textElement.classList.add('d-none');
            if (loadingElement) loadingElement.classList.remove('d-none');
        } else {
            button.disabled = false;
            if (textElement) textElement.classList.remove('d-none');
            if (loadingElement) loadingElement.classList.add('d-none');
        }
    }
    
    /**
     * Mostrar alerta
     */
    static showAlert(type, message, container = null) {
        const alertTypes = {
            success: { icon: 'check-circle', class: 'alert-success' },
            error: { icon: 'exclamation-circle', class: 'alert-danger' },
            warning: { icon: 'exclamation-triangle', class: 'alert-warning' },
            info: { icon: 'info-circle', class: 'alert-info' }
        };
        
        const alertConfig = alertTypes[type] || alertTypes.info;
        
        // Buscar contenedor
        if (!container) {
            container = document.querySelector('.auth-form-header') || 
                       document.querySelector('.auth-form-container') ||
                       document.body;
        }
        
        // Remover alerta existente
        const existingAlert = container.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        // Crear nueva alerta
        const alert = document.createElement('div');
        alert.className = `alert ${alertConfig.class} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${alertConfig.icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        `;
        
        // Insertar alerta
        if (container.classList.contains('auth-form-header')) {
            container.insertAdjacentElement('afterend', alert);
        } else {
            container.insertAdjacentElement('afterbegin', alert);
        }
        
        // Auto-ocultar después de un tiempo
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, AuthConfig.ui.alertAutoHide);
        
        return alert;
    }
    
    /**
     * Obtener datos del formulario
     */
    static getFormData(form) {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        return data;
    }
    
    /**
     * Validar formulario con Bootstrap
     */
    static validateForm(form) {
        form.classList.add('was-validated');
        return form.checkValidity();
    }
    
    /**
     * Limpiar validación del formulario
     */
    static clearValidation(form) {
        form.classList.remove('was-validated');
        
        // Limpiar estados de campos individuales
        const fields = form.querySelectorAll('.form-control');
        fields.forEach(field => {
            field.classList.remove('is-valid', 'is-invalid');
        });
    }
}

// ====================================================================
// MANEJADOR DE FORMULARIOS
// ====================================================================
class AuthFormHandler {
    constructor() {
        this.initializeEventListeners();
    }
    
    /**
     * Inicializar event listeners
     */
    initializeEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initLoginForm();
            this.initRegisterForm();
            this.initRecoveryForm();
            this.initPasswordToggle();
            this.initFormValidation();
        });
    }
    
    /**
     * Inicializar formulario de login
     */
    initLoginForm() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;
        
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!AuthUtils.validateForm(loginForm)) {
                return;
            }
            
            const submitButton = loginForm.querySelector('button[type="submit"]');
            
            try {
                AuthUtils.toggleButtonLoading(submitButton, true);
                
                const formData = new FormData(loginForm);
                const response = await AuthUtils.makeRequest(AuthConfig.endpoints.login, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.success) {
                    AuthUtils.showAlert('success', response.mensaje || 'Login exitoso');
                    
                    // Redirigir después del delay
                    setTimeout(() => {
                        window.location.href = response.redirect || '/dashboard';
                    }, AuthConfig.ui.redirectDelay);
                } else {
                    throw new Error(response.error || 'Error desconocido');
                }
                
            } catch (error) {
                AuthUtils.showAlert('error', error.message);
                AuthUtils.toggleButtonLoading(submitButton, false);
            }
        });
    }
    
    /**
     * Inicializar formulario de registro
     */
    initRegisterForm() {
        const registerForm = document.getElementById('registerForm');
        if (!registerForm) return;
        
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!this.validateRegisterForm(registerForm)) {
                return;
            }
            
            const submitButton = registerForm.querySelector('button[type="submit"]');
            
            try {
                AuthUtils.toggleButtonLoading(submitButton, true);
                
                const formData = new FormData(registerForm);
                const response = await AuthUtils.makeRequest(AuthConfig.endpoints.register, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.success) {
                    AuthUtils.showAlert('success', response.mensaje || 'Registro exitoso');
                    
                    setTimeout(() => {
                        window.location.href = response.redirect || '/dashboard';
                    }, AuthConfig.ui.redirectDelay);
                } else {
                    throw new Error(response.error || 'Error en el registro');
                }
                
            } catch (error) {
                AuthUtils.showAlert('error', error.message);
                AuthUtils.toggleButtonLoading(submitButton, false);
            }
        });
    }
    
    /**
     * Inicializar formulario de recuperación
     */
    initRecoveryForm() {
        const recoveryForm = document.getElementById('recoveryForm');
        if (!recoveryForm) return;
        
        recoveryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!AuthUtils.validateForm(recoveryForm)) {
                return;
            }
            
            const submitButton = recoveryForm.querySelector('button[type="submit"]');
            
            try {
                AuthUtils.toggleButtonLoading(submitButton, true);
                
                const formData = new FormData(recoveryForm);
                const response = await AuthUtils.makeRequest(AuthConfig.endpoints.recovery, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.success) {
                    AuthUtils.showAlert('success', response.mensaje || 'Instrucciones enviadas');
                    
                    // Limpiar formulario
                    recoveryForm.reset();
                    AuthUtils.clearValidation(recoveryForm);
                } else {
                    throw new Error(response.error || 'Error al recuperar contraseña');
                }
                
            } catch (error) {
                AuthUtils.showAlert('error', error.message);
            } finally {
                AuthUtils.toggleButtonLoading(submitButton, false);
            }
        });
    }
    
    /**
     * Inicializar toggle de contraseña
     */
    initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const input = button.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
                const icon = button.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    button.setAttribute('aria-label', 'Ocultar contraseña');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                    button.setAttribute('aria-label', 'Mostrar contraseña');
                }
            });
        });
    }
    
    /**
     * Inicializar validación en tiempo real
     */
    initFormValidation() {
        // Validación de email en tiempo real
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', (e) => {
                this.validateEmailField(e.target);
            });
        });
        
        // Validación de contraseña en tiempo real
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        passwordInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.validatePasswordField(e.target);
            });
        });
        
        // Confirmación de contraseña
        const confirmPasswordInput = document.getElementById('password_confirmacion');
        if (confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', (e) => {
                this.validatePasswordConfirmation(e.target);
            });
        }
    }
    
    /**
     * Validar campo de email
     */
    validateEmailField(input) {
        const isValid = AuthUtils.validateEmail(input.value);
        this.setFieldValidation(input, isValid, 'Ingresa un email válido');
        return isValid;
    }
    
    /**
     * Validar campo de contraseña
     */
    validatePasswordField(input) {
        const isValid = AuthUtils.validatePassword(input.value);
        const message = `La contraseña debe tener al menos ${AuthConfig.validation.minPasswordLength} caracteres`;
        this.setFieldValidation(input, isValid, message);
        return isValid;
    }
    
    /**
     * Validar confirmación de contraseña
     */
    validatePasswordConfirmation(input) {
        const passwordInput = document.getElementById('password');
        const isValid = passwordInput && input.value === passwordInput.value;
        this.setFieldValidation(input, isValid, 'Las contraseñas no coinciden');
        return isValid;
    }
    
    /**
     * Establecer estado de validación de campo
     */
    setFieldValidation(input, isValid, message) {
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        
        if (isValid) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            
            if (feedback) {
                feedback.textContent = message;
            }
        }
    }
    
    /**
     * Validar formulario de registro completo
     */
    validateRegisterForm(form) {
        let isValid = true;
        
        // Validar campos básicos
        const emailInput = form.querySelector('input[type="email"]');
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmPasswordInput = form.querySelector('input[name="password_confirmacion"]');
        
        if (emailInput && !this.validateEmailField(emailInput)) {
            isValid = false;
        }
        
        if (passwordInput && !this.validatePasswordField(passwordInput)) {
            isValid = false;
        }
        
        if (confirmPasswordInput && !this.validatePasswordConfirmation(confirmPasswordInput)) {
            isValid = false;
        }
        
        // Validar formulario completo con Bootstrap
        if (!AuthUtils.validateForm(form)) {
            isValid = false;
        }
        
        return isValid;
    }
}

// ====================================================================
// FUNCIONES DE SESIÓN
// ====================================================================
class AuthSession {
    /**
     * Logout con confirmación
     */
    static async logout(showConfirmation = true) {
        if (showConfirmation) {
            const confirmed = confirm('¿Estás seguro de que deseas cerrar sesión?');
            if (!confirmed) return;
        }
        
        try {
            const response = await AuthUtils.makeRequest(AuthConfig.endpoints.logout, {
                method: 'POST'
            });
            
            if (response.success) {
                AuthUtils.showAlert('success', 'Sesión cerrada correctamente');
                
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1000);
            }
        } catch (error) {
            console.error('Error al cerrar sesión:', error);
            // Forzar redirección en caso de error
            window.location.href = '/login';
        }
    }
    
    /**
     * Verificar si la sesión está activa
     */
    static async checkSession() {
        try {
            const response = await AuthUtils.makeRequest('/api/session/check');
            return response.active;
        } catch (error) {
            return false;
        }
    }
    
    /**
     * Refrescar token de sesión
     */
    static async refreshSession() {
        try {
            const response = await AuthUtils.makeRequest('/api/session/refresh', {
                method: 'POST'
            });
            return response.success;
        } catch (error) {
            return false;
        }
    }
}

// ====================================================================
// INICIALIZACIÓN
// ====================================================================

// Instanciar manejador de formularios
const authFormHandler = new AuthFormHandler();

// Exponer funciones globales para uso en HTML
window.AuthUtils = AuthUtils;
window.AuthSession = AuthSession;
window.AuthConfig = AuthConfig;

// Funciones de conveniencia para uso global
window.logout = AuthSession.logout;
window.showAuthAlert = AuthUtils.showAlert;

// Event listeners globales
document.addEventListener('DOMContentLoaded', function() {
    // Auto-cerrar alertas al hacer clic en el botón close
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-close')) {
            const alert = e.target.closest('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }
    });
    
    // Manejar enlaces de logout
    const logoutLinks = document.querySelectorAll('.logout-link');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            AuthSession.logout(true);
        });
    });
    
    // Auto-focus en primer campo visible
    const firstInput = document.querySelector('.auth-form input:not([type="hidden"]):first-of-type');
    if (firstInput) {
        firstInput.focus();
    }
    
    console.log('CareCenter Auth System initialized');
});