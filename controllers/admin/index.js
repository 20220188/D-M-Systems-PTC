// Constante para establecer el formulario de registro del primer usuario.
const SIGNUP_FORM = document.getElementById('signupForm');
// Constante para establecer el formulario de inicio de sesión.
const LOGIN_FORM = document.getElementById('loginForm');

const CLAVE = document.getElementById('claveAdministrador');
const CONFIRMAR_CLAVE = document.getElementById('confirmarClave');

const CLAVE_LOGIN = document.getElementById('clave');


const CLAVE_ACTUAL = document.getElementById('claveActual'),
CONFIRMAR_CLAVE_NUEVA = document.getElementById('confirmarClave'),
CLAVE_NUEVA = document.getElementById('claveNueva');
// Constante para establecer la modal de cambiar contraseña.
const PASSWORD_MODAL = new bootstrap.Modal('#passwordModal');
// Constante para establecer el formulario de cambiar contraseña.
const PASSWORD_FORM = document.getElementById('passwordForm');

// Constantes para los mensajes de error
const PASSWORD_ERROR = document.getElementById('passwordError');
const CONFIRM_PASSWORD_ERROR = document.getElementById('confirmPasswordError');
const PASSWORD_ERROR_LOGIN = document.getElementById('passwordErrorLogin');

const TOGGLE_PASSWORD = document.getElementById('togglePassword');
const TOGGLE_CONFIRM_PASSWORD = document.getElementById('toggleConfirmPassword');
const TOGGLE_PASSWORD_LOGIN = document.getElementById('togglePasswordLogin');

//Constantes para manejar los errores del modal de contraseña
const PASSWORD_ERROR_FORM = document.getElementById('passwordErrorFrom');
const NEW_PASSWORD_ERROR_FORM = document.getElementById('NewPasswordError');
const CONFIRM_PASSWORD_ERROR_FORM = document.getElementById('confirmPasswordError');

const TOGGLE_PASSWORD_FORM = document.getElementById('togglePasswordFrom');
const TOGGLE_NEW_PASSWORD_FORM = document.getElementById('toggleNewPassword');
const TOGGLE_CONFIRM_PASSWORD_FORM = document.getElementById('toggleConfirmPassword');

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


// Evento para mostrar/ocultar la contraseña en el formulario de contraseña
TOGGLE_PASSWORD_FORM.addEventListener('click', () => togglePasswordVisibility(CLAVE_ACTUAL, TOGGLE_PASSWORD_FORM));
TOGGLE_NEW_PASSWORD_FORM.addEventListener('click', () => togglePasswordVisibility(CLAVE_NUEVA, TOGGLE_NEW_PASSWORD_FORM));
TOGGLE_CONFIRM_PASSWORD_FORM.addEventListener('click', () => togglePasswordVisibility(CONFIRMAR_CLAVE_NUEVA, TOGGLE_CONFIRM_PASSWORD_FORM));

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

// Evento para validar la contraseña mientras se escribe (formulario de registro)
CLAVE_ACTUAL.addEventListener('input', () => {
    const userData = getUserDataFromForm();
    const errorMessage = isPasswordStrong(CLAVE_ACTUAL.value, userData);
    if (errorMessage) {
        showError(PASSWORD_ERROR, errorMessage);
    } else {
        hideError(PASSWORD_ERROR);
    }
});

// Evento para validar la confirmación de contraseña mientras se escribe (formulario de registro)
CONFIRMAR_CLAVE_NUEVA.addEventListener('input', () => {
    if (CLAVE.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
    } else {
        hideError(CONFIRM_PASSWORD_ERROR);
    }
});

// Evento para validar la contraseña mientras se escribe (formulario de inicio de sesión)
CLAVE_NUEVA.addEventListener('input', () => {
    const userData = getUserDataFromForm();
    const errorMessage = isPasswordStrong(CLAVE_NUEVA.value, userData);
    if (errorMessage) {
        showError(NEW_PASSWORD_ERROR, errorMessage);
    } else {
        hideError(NEW_PASSWORD_ERROR);
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

const TWO_FACTOR_FORM = document.getElementById('twoFactorForm');
let currentAdminId = null; // Almacena temporalmente el id del usuario autenticado

// Método del evento para cuando se envía el formulario de inicio de sesión.
LOGIN_FORM.addEventListener('submit', async (event) => {
    event.preventDefault(); // Previene el comportamiento predeterminado antes de hacer la petición.
    
    const FORM = new FormData(LOGIN_FORM);
    const omit2FA = document.getElementById('omit2FA').checked;
    FORM.append('omit2FA', omit2FA ? '1' : '0');

    // Verificar la fortaleza de la contraseña antes de continuar.
    const passwordError = isPasswordStrong(CLAVE_LOGIN.value);
    if (passwordError) {
        showError(PASSWORD_ERROR_LOGIN, passwordError);
        return;
    }

    // Petición para iniciar sesión.
    const DATA = await fetchData(USER_API, 'logIn', FORM);

    if (DATA.status) {
        // Si la contraseña ha expirado, mostrar el modal de cambio de contraseña.
        if (DATA.passwordExpired) {
            sweetAlert(2, 'Tu contraseña ha expirado. Por favor, cámbiala ahora.', false);
            showPasswordChangeModal();  // Mostrar modal para cambiar la contraseña.
        } 
        // Si se requiere 2FA, cambiar la vista al formulario de 2FA.
        else if (DATA.twoFactorRequired) {
            currentAdminId = DATA.id_usuario;  // Guardar el ID del usuario autenticado
            sweetAlert(1, 'Autenticación exitosa. Se requiere verificación de 2FA.', false);
            LOGIN_FORM.classList.add('d-none');  // Ocultar el formulario de inicio de sesión.
            TWO_FACTOR_FORM.classList.remove('d-none');  // Mostrar el formulario de 2FA.
        } 
        // Si no es necesario el cambio de contraseña ni el 2FA, inicio exitoso.
        else {
            sweetAlert(1, DATA.message, true, 'dashboard.html');
        }
    } else {
        sweetAlert(2, DATA.error || 'Error durante el inicio de sesión', false);
    }
});

// Nuevo método para manejar la verificación del código 2FA
TWO_FACTOR_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();
 
    // Redirigir directamente al dashboard
    sweetAlert(1, 'Autenticación exitosa. Redirigiendo...', true, 'dashboard.html');
});


// Función para mostrar el modal de cambio de contraseña
function showPasswordChangeModal() {
    const passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    passwordModal.show();
}

// Mètodo del evento para cuando se envía el formulario de cambiar contraseña.
PASSWORD_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    const userData = getUserDataFromForm();
    const passwordError = isPasswordStrong(CLAVE_ACTUAL.value, userData);
    if (passwordError) {
        showError(PASSWORD_ERROR, passwordError);
        return;
    }

    const userData1 = getUserDataFromForm();
    const passwordError1 = isPasswordStrong(CLAVE_NUEVA.value, userData1);
    if (passwordError1) {
        showError(NEW_PASSWORD_ERROR, passwordError1);
        return;
    }
    
    if (CLAVE_NUEVA.value !== CONFIRMAR_CLAVE.value) {
        showError(CONFIRM_PASSWORD_ERROR, 'Las contraseñas no coinciden.');
        return;
    }
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(PASSWORD_FORM);
    // Petición para actualizar la constraseña.
    const DATA = await fetchData(USER_API, 'changePassword', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        PASSWORD_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función para preparar el formulario al momento de cambiar la constraseña.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openPassword = () => {
    // Se abre la caja de diálogo que contiene el formulario.
    PASSWORD_MODAL.show();
    // Se restauran los elementos del formulario.
    PASSWORD_FORM.reset();
}