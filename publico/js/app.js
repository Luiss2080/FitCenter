/**
 * JavaScript principal de CareCenter
 * Funcionalidades comunes de la aplicación
 */

// Configuración global
const CareCenter = {
    baseUrl: '/care_center',
    apiUrl: '/care_center/api',
    csrfToken: null,
    
    // Inicializar aplicación
    init() {
        this.setupCSRF();
        this.setupAjax();
        this.setupEventListeners();
        this.setupValidations();
        console.log('CareCenter inicializado correctamente');
    },
    
    // Configurar CSRF token
    setupCSRF() {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            this.csrfToken = csrfMeta.getAttribute('content');
        }
    },
    
    // Configurar Ajax global
    setupAjax() {
        // Configurar headers por defecto para fetch
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (typeof args[0] === 'string' || args[0] instanceof Request) {
                if (typeof args[1] === 'undefined') {
                    args[1] = {};
                }
                if (typeof args[1].headers === 'undefined') {
                    args[1].headers = {};
                }
                
                // Agregar CSRF token si existe
                if (CareCenter.csrfToken && 
                    (args[1].method === 'POST' || args[1].method === 'PUT' || args[1].method === 'DELETE')) {
                    args[1].headers['X-CSRF-Token'] = CareCenter.csrfToken;
                }
                
                // Agregar content-type por defecto
                if (!args[1].headers['Content-Type'] && args[1].body && typeof args[1].body === 'string') {
                    args[1].headers['Content-Type'] = 'application/json';
                }
            }
            
            return originalFetch.apply(this, args);
        };
    },
    
    // Configurar event listeners globales
    setupEventListeners() {
        // Confirmar eliminación
        document.addEventListener('click', (e) => {
            if (e.target.matches('.btn-delete, .delete-btn')) {
                e.preventDefault();
                this.confirmDelete(e.target);
            }
        });
        
        // Auto-hide de alertas
        document.addEventListener('DOMContentLoaded', () => {
            this.autoHideAlerts();
        });
        
        // Sidebar toggle
        document.addEventListener('click', (e) => {
            if (e.target.matches('.sidebar-toggle')) {
                this.toggleSidebar();
            }
        });
        
        // Dropdown menus
        document.addEventListener('click', (e) => {
            if (e.target.matches('.dropdown-toggle')) {
                this.toggleDropdown(e.target);
            }
        });
        
        // Cerrar dropdowns al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown')) {
                this.closeAllDropdowns();
            }
        });
    },
    
    // Configurar validaciones de formularios
    setupValidations() {
        // Validación en tiempo real
        document.addEventListener('blur', (e) => {
            if (e.target.matches('input, textarea, select')) {
                this.validateField(e.target);
            }
        }, true);
        
        // Validar formularios al enviar
        document.addEventListener('submit', (e) => {
            if (!e.target.matches('.no-validate')) {
                if (!this.validateForm(e.target)) {
                    e.preventDefault();
                }
            }
        });
    },
    
    // Confirmar eliminación
    confirmDelete(element) {
        const message = element.getAttribute('data-confirm') || '¿Estás seguro de que quieres eliminar este elemento?';
        const url = element.getAttribute('href') || element.getAttribute('data-url');
        
        if (confirm(message)) {
            if (element.tagName === 'A') {
                window.location.href = url;
            } else {
                // Enviar petición DELETE via Ajax
                this.deleteRequest(url);
            }
        }
    },
    
    // Petición DELETE via Ajax
    async deleteRequest(url) {
        try {
            this.showLoading();
            
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert('Elemento eliminado correctamente', 'success');
                // Recargar página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showAlert(data.message || 'Error al eliminar', 'error');
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Error de conexión', 'error');
        } finally {
            this.hideLoading();
        }
    },
    
    // Auto-ocultar alertas
    autoHideAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                this.fadeOut(alert);
            }, 5000);
        });
    },
    
    // Toggle sidebar
    toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.toggle('open');
        }
        
        if (overlay) {
            overlay.classList.toggle('show');
        }
        
        document.body.classList.toggle('sidebar-open');
    },
    
    // Toggle dropdown
    toggleDropdown(trigger) {
        const dropdown = trigger.nextElementSibling;
        
        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
            // Cerrar otros dropdowns
            this.closeAllDropdowns();
            
            // Toggle el dropdown actual
            dropdown.classList.toggle('show');
            trigger.setAttribute('aria-expanded', dropdown.classList.contains('show'));
        }
    },
    
    // Cerrar todos los dropdowns
    closeAllDropdowns() {
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        dropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
            const trigger = dropdown.previousElementSibling;
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    },
    
    // Validar campo individual
    validateField(field) {
        const rules = field.getAttribute('data-validate');
        if (!rules) return true;
        
        const value = field.value.trim();
        const ruleList = rules.split('|');
        let isValid = true;
        let errorMessage = '';
        
        for (const rule of ruleList) {
            const [ruleName, ...params] = rule.split(':');
            
            switch (ruleName) {
                case 'required':
                    if (!value) {
                        isValid = false;
                        errorMessage = 'Este campo es requerido';
                    }
                    break;
                    
                case 'email':
                    if (value && !this.isValidEmail(value)) {
                        isValid = false;
                        errorMessage = 'Ingresa un email válido';
                    }
                    break;
                    
                case 'min':
                    if (value && value.length < parseInt(params[0])) {
                        isValid = false;
                        errorMessage = `Mínimo ${params[0]} caracteres`;
                    }
                    break;
                    
                case 'max':
                    if (value && value.length > parseInt(params[0])) {
                        isValid = false;
                        errorMessage = `Máximo ${params[0]} caracteres`;
                    }
                    break;
                    
                case 'numeric':
                    if (value && !this.isNumeric(value)) {
                        isValid = false;
                        errorMessage = 'Solo se permiten números';
                    }
                    break;
            }
            
            if (!isValid) break;
        }
        
        this.updateFieldValidation(field, isValid, errorMessage);
        return isValid;
    },
    
    // Validar formulario completo
    validateForm(form) {
        const fields = form.querySelectorAll('[data-validate]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    // Actualizar estado visual de validación
    updateFieldValidation(field, isValid, errorMessage) {
        // Remover clases previas
        field.classList.remove('is-valid', 'is-invalid');
        
        // Agregar clase apropiada
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        
        // Mostrar/ocultar mensaje de error
        let errorElement = field.parentNode.querySelector('.form-error');
        
        if (!isValid && errorMessage) {
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'form-error';
                field.parentNode.appendChild(errorElement);
            }
            errorElement.textContent = errorMessage;
        } else if (errorElement) {
            errorElement.remove();
        }
    },
    
    // Utilidades de validación
    isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    },
    
    isNumeric(value) {
        return !isNaN(value) && !isNaN(parseFloat(value));
    },
    
    // Mostrar alerta
    showAlert(message, type = 'info', duration = 5000) {
        const alertContainer = document.querySelector('.alert-container') || document.body;
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.innerHTML = `
            <span>${message}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                &times;
            </button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-ocultar después del tiempo especificado
        if (duration > 0) {
            setTimeout(() => {
                this.fadeOut(alert);
            }, duration);
        }
    },
    
    // Mostrar loading
    showLoading(message = 'Cargando...') {
        let loader = document.querySelector('.global-loader');
        
        if (!loader) {
            loader = document.createElement('div');
            loader.className = 'global-loader';
            loader.innerHTML = `
                <div class="loader-backdrop">
                    <div class="loader-content">
                        <div class="spinner"></div>
                        <p>${message}</p>
                    </div>
                </div>
            `;
            document.body.appendChild(loader);
        }
        
        loader.style.display = 'flex';
    },
    
    // Ocultar loading
    hideLoading() {
        const loader = document.querySelector('.global-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    },
    
    // Fade out effect
    fadeOut(element, callback) {
        element.style.transition = 'opacity 0.3s ease';
        element.style.opacity = '0';
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
            if (callback) callback();
        }, 300);
    },
    
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Format currency
    formatCurrency(amount) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN'
        }).format(amount);
    },
    
    // Format date
    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        
        return new Intl.DateTimeFormat('es-MX', {
            ...defaultOptions,
            ...options
        }).format(new Date(date));
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    CareCenter.init();
});

// Exponer globalmente
window.CareCenter = CareCenter;