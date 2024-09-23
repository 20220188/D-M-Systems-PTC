// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signupForm');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('loginForm');

const CLAVE = document.getElementById('claveAdministrador');
const CONFIRMAR_CLAVE = document.getElementById('confirmarClave');

const CLAVE_LOGIN = document.getElementById('clave');

// Constantes para los mensajes de error
const PASSWORD_ERROR = document.getElementById('passwordError');
const CONFIRM_PASSWORD_ERROR = document.getElementById('confirmPasswordError');
const PASSWORD_ERROR_LOGIN = document.getElementById('passwordErrorLogin');

const TOGGLE_PASSWORD = document.getElementById('togglePassword');
const TOGGLE_CONFIRM_PASSWORD = document.getElementById('toggleConfirmPassword');
const TOGGLE_PASSWORD_LOGIN = document.getElementById('togglePasswordLogin');

vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefonoUsuario'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});
// Llamada a la función para establecer la mascara del campo DUI.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('duiUsuario'),
    mask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/]
});

// Función para alternar la visibilidad de la contraseña
const togglePasswordVisibility = (inputField, toggleButton) => {
    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
    inputField.setAttribute('type', type);
    toggleButton.querySelector('i').classList.toggle('fa-eye');
    toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
};

// Evento para mostrar/ocultar la contraseña
TOGGLE_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CLAVE, TOGGLE_PASSWORD));
TOGGLE_CONFIRM_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CONFIRMAR_CLAVE, TOGGLE_CONFIRM_PASSWORD));
TOGGLE_PASSWORD_LOGIN.addEventListener('click', () => togglePasswordVisibility(CLAVE_LOGIN, TOGGLE_PASSWORD_LOGIN));

// Función para verificar la fortaleza de la contraseña
const isPasswordStrong = (password, userData = {}) => {
    if (password.length < 8 || password.length > 24) {
        return 'La contraseña debe tener entre 8 y 24 caracteres.';
    }
    
    if (/\s/.test(password)) {
        return 'La contraseña no debe contener espacios en blanco.';
    }

    const hasLowerCase = /[a-z]/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/.test(password);
    
    if (!(hasLowerCase && hasUpperCase && hasNumber && hasSpecialChar)) {
        return 'La contraseña debe incluir mayúsculas, minúsculas, números y caracteres especiales.';
    }

    // Verificar que la contraseña no contenga datos del usuario
    for (const [key, value] of Object.entries(userData)) {
        if (typeof value === 'string' && value.length > 2) {
            const lowercaseValue = value.toLowerCase();
            const lowercasePassword = password.toLowerCase();
            if (lowercasePassword.includes(lowercaseValue)) {
                return `La contraseña no debe contener información personal (${key}).`;
            }
        }
    }

    return ''; // Contraseña válida
}

// Función para mostrar mensajes de error
const showError = (element, message) => {
    element.textContent = message;
    element.style.display = 'block';
}

// Función para ocultar mensajes de error
const hideError = (element) => {
    element.textContent = '';
    element.style.display = 'none';
}

// Función para obtener los datos del usuario del formulario de registro
const getUserDataFromForm = () => {
    return {
        nombre: document.getElementById('nombreAdministrador').value,
        dui: document.getElementById('duiUsuario').value,
        correo: document.getElementById('correoAdministrador').value,
        alias: document.getElementById('aliasAdministrador').value,
        telefono: document.getElementById('telefonoUsuario').value
    };
}

// Evento para validar la contraseña mientras se escribe (formulario de registro)
CLAVE.addEventListener('input', () => {
    const userData = getUserDataFromForm();
    const errorMessage = isPasswordStrong(CLAVE.value, userData);
    if (errorMessage) {
        showError(PASSWORD_ERROR, errorMessage);
    } else {
        hideError(PASSWORD_ERROR);
    }
});

// Evento para validar la confirmación de contraseña mientras se escribe (formulario de registro)
CONFIRMAR_CLAVE.addEventListener('input', () => {
    if (CLAVE.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
    } else {
        hideError(CONFIRM_PASSWORD_ERROR);
    }
});

// Evento para validar la contraseña mientras se escribe (formulario de inicio de sesión)
CLAVE_LOGIN.addEventListener('input', () => {
    const errorMessage = isPasswordStrong(CLAVE_LOGIN.value);
    if (errorMessage) {
        showError(PASSWORD_ERROR_LOGIN, errorMessage);
    } else {
        hideError(PASSWORD_ERROR_LOGIN);
    }
});

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Petición para consultar los usuarios registrados.
    const DATA = await fetchData(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (DATA.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'dashboard.html';
    } else if (DATA.status) {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Iniciar sesión';
        // Se muestra el formulario para iniciar sesión.
        LOGIN_FORM.classList.remove('d-none');
        sweetAlert(4, DATA.message, true);
    } else {
        // Se establece el título del contenido principal.
        MAIN_TITLE.textContent = 'Registrar primer usuario';
        // Se muestra el formulario para registrar el primer usuario.
        SIGNUP_FORM.classList.remove('d-none');
        sweetAlert(4, DATA.error, true);
    }
});

// Método del evento para cuando se envía el formulario de registro del primer usuario.
SIGNUP_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    const userData = getUserDataFromForm();
    const passwordError = isPasswordStrong(CLAVE.value, userData);
    if (passwordError) {
        showError(PASSWORD_ERROR, passwordError);
        return;
    }
    
    if (CLAVE.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
        return;
    }
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SIGNUP_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const DATA = await fetchData(USER_API, 'signUp', FORM);
    
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true, 'index.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

LOGIN_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const passwordError = isPasswordStrong(CLAVE_LOGIN.value);
    if (passwordError) {
        showError(PASSWORD_ERROR_LOGIN, passwordError);
        return;
    }

    const FORM = new FormData(LOGIN_FORM);
    const DATA = await fetchData(USER_API, 'logIn', FORM);
    
    if (DATA.status) {
        if (DATA.passwordExpired) {
            sweetAlert(2, 'Tu contraseña ha expirado. Por favor, cámbiala ahora.', false);
            showPasswordChangeModal();
        } else {
            sweetAlert(1, DATA.message, true, 'dashboard.html');
        }
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Función para mostrar el modal de cambio de contraseña
function showPasswordChangeModal() {
    const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    passwordModal.show();
}

// Evento para manejar el envío del formulario de cambio de contraseña
document.getElementById('passwordForm').addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const newPassword = document.getElementById('claveNueva').value;
    const confirmPassword = document.getElementById('confirmarClave').value;
    
    if (newPassword !== confirmPassword) {
        showError(document.getElementById('confirmPasswordError'), 'Las contraseñas no coinciden.');
        return;
    }
    
    const passwordError = isPasswordStrong(newPassword);
    if (passwordError) {
        showError(document.getElementById('NewPasswordError'), passwordError);
        return;
    }
    
    const FORM = new FormData(event.target);
    const DATA = await fetchData(USER_API, 'changePassword', FORM);
    
    if (DATA.status) {
        sweetAlert(1, 'Contraseña cambiada con éxito. Por favor, inicia sesión nuevamente.', true, 'index.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});