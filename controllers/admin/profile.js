// Constantes para establecer los elementos del formulario de editar perfil.
const PROFILE_FORM = document.getElementById('profileForm'),
    NOMBRE_ADMINISTRADOR = document.getElementById('nombreAdministrador'),
    APELLIDO_ADMINISTRADOR = document.getElementById('apellidoAdministrador'),
    CORREO_ADMINISTRADOR = document.getElementById('correoAdministrador'),
    ALIAS_ADMINISTRADOR = document.getElementById('aliasAdministrador');

    const CLAVE_ACTUAL = document.getElementById('claveActual'),
    CONFIRMAR_CLAVE = document.getElementById('confirmarClaveForm'),
    CLAVE_NUEVA = document.getElementById('claveNueva');
// Constante para establecer la modal de cambiar contraseña.
const PASSWORD_MODAL = new bootstrap.Modal('#passwordModal');
// Constante para establecer el formulario de cambiar contraseña.
const PASSWORD_FORM = document.getElementById('passwordForm');

// Constantes para los mensajes de error
const PASSWORD_ERROR = document.getElementById('passwordError');
const NEW_PASSWORD_ERROR = document.getElementById('NewPasswordError');
const CONFIRM_PASSWORD_ERROR = document.getElementById('confirmPasswordError');

const TOGGLE_PASSWORD = document.getElementById('togglePassword');
const TOGGLE_NEW_PASSWORD = document.getElementById('toggleNewPassword');
const TOGGLE_CONFIRM_PASSWORD = document.getElementById('toggleConfirmPassword');

vanillaTextMask.maskInput({
    inputElement: document.getElementById('apellidoAdministrador'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});

// Función para alternar la visibilidad de la contraseña
const togglePasswordVisibility = (inputField, toggleButton) => {
    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
    inputField.setAttribute('type', type);
    toggleButton.querySelector('i').classList.toggle('fa-eye');
    toggleButton.querySelector('i').classList.toggle('fa-eye-slash');
};

// Evento para mostrar/ocultar la contraseña
TOGGLE_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CLAVE_ACTUAL, TOGGLE_PASSWORD));
TOGGLE_CONFIRM_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CONFIRMAR_CLAVE, TOGGLE_CONFIRM_PASSWORD));
TOGGLE_NEW_PASSWORD.addEventListener('click', () => togglePasswordVisibility(CLAVE_NUEVA, TOGGLE_NEW_PASSWORD));

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
        telefono: document.getElementById('apellidoAdministrador').value,
        correo: document.getElementById('correoAdministrador').value,
        alias: document.getElementById('aliasAdministrador').value,
    };
}

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
CONFIRMAR_CLAVE.addEventListener('input', () => {
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
    // Se establece el título del contenido principal.
    MAIN_TITLE.textContent = 'Editar perfil';
    // Petición para obtener los datos del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'readProfile');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se inicializan los campos del formulario con los datos del usuario que ha iniciado sesión.
        const ROW = DATA.dataset;
        NOMBRE_ADMINISTRADOR.value = ROW.nombre;
        APELLIDO_ADMINISTRADOR.value = ROW.telefono;
        CORREO_ADMINISTRADOR.value = ROW.correo;
        ALIAS_ADMINISTRADOR.value = ROW.usuario;
    } else {
        sweetAlert(2, DATA.error, null);
    }
});

// Método del evento para cuando se envía el formulario de editar perfil.
PROFILE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(PROFILE_FORM);
    // Petición para actualizar los datos personales del usuario.
    const DATA = await fetchData(USER_API, 'editProfile', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true);
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

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