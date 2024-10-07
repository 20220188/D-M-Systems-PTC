<?php
// Se incluye la clase del modelo.
require_once('../../models/data/administrador_data.php');
require_once('../../services/admin/mail_config.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $administrador = new AdministradorData;
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);

    if (isset($_SESSION['idAdministrador'])) {
        $result['session'] = 1;

        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $administrador->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) ||
                    !$administrador->setDUI($_POST['duiUsuario']) ||
                    !$administrador->setCorreo($_POST['correoAdministrador']) ||
                    !$administrador->setAlias($_POST['aliasAdministrador']) ||
                    !$administrador->setTelefono($_POST['telefonoUsuario']) ||
                    !$administrador->setClave($_POST['claveAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['claveAdministrador'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el administrador';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen administradores registrados';
                }
                break;
            case 'readOne':
                if (!$administrador->setId($_POST['idAdministrador'])) {
                    $result['error'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $administrador->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Administrador inexistente';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setId($_POST['idAdministrador']) ||
                    !$administrador->setNombre($_POST['nombreAdministrador']) ||
                    !$administrador->setApellido($_POST['apellidoAdministrador']) ||
                    !$administrador->setCorreo($_POST['correoAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el administrador';
                }
                break;
            case 'deleteRow':
                if ($_POST['idAdministrador'] == $_SESSION['idAdministrador']) {
                    $result['error'] = 'No se puede eliminar a sí mismo';
                } elseif (!$administrador->setId($_POST['idAdministrador'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el administrador';
                }
                break;
            case 'getUser':
                if (isset($_SESSION['aliasAdministrador'])) {
                    $db = new Database();
                    $alias = $_SESSION['aliasAdministrador'];
                    $sql = 'SELECT id_nivel_usuario FROM tb_usuarios WHERE usuario = ?';
                    $params = array($alias);
                    $data = $db->getRow($sql, $params);

                    if ($data) {
                        $result['status'] = 1;
                        $result['username'] = $alias;
                        $result['user_level'] = $data['id_nivel_usuario'];
                    } else {
                        $result['error'] = 'No se pudo obtener el nivel de usuario';
                    }
                } else {
                    $result['error'] = 'Alias de administrador indefinido';
                }
                break;
            case 'logOut':
            case 'logOutInactividad':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión cerrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $administrador->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }
                break;
            case 'editProfile':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) ||
                    !$administrador->setTelefono($_POST['telefonoAdministrador']) ||
                    !$administrador->setCorreo($_POST['correoAdministrador']) ||
                    !$administrador->setAlias($_POST['aliasAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->editProfile()) {
                    $result['status'] = 1;
                    $result['message'] = 'Perfil modificado correctamente';
                    $_SESSION['aliasAdministrador'] = $_POST['aliasAdministrador'];
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el perfil';
                }
                break;
            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->checkPassword($_POST['claveActual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarClaveForm']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setClave($_POST['claveNueva'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->changePassword()) {
                    $administrador->updateLastPasswordChange();
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        switch ($_GET['action']) {
            case 'readUsers':
                if ($administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['error'] = 'Debe crear un administrador para comenzar';
                }
                break;
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) ||
                    !$administrador->setDUI($_POST['duiUsuario']) ||
                    !$administrador->setCorreo($_POST['correoAdministrador']) ||
                    !$administrador->setAlias($_POST['aliasAdministrador']) ||
                    !$administrador->setTelefono($_POST['telefonoUsuario']) ||
                    !$administrador->setClave($_POST['claveAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['claveAdministrador'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar el administrador';
                }
                break;
            case 'signUpMovil':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) ||
                    !$administrador->setDUI($_POST['duiUsuario']) ||
                    !$administrador->setCorreo($_POST['correoAdministrador']) ||
                    !$administrador->setAlias($_POST['aliasAdministrador']) ||
                    !$administrador->setTelefono($_POST['telefonoUsuario']) ||
                    !$administrador->setClave($_POST['claveAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['claveAdministrador'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createAdminApp()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cuenta registrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar la cuenta';
                }
                break;

            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                $checkUserResult = $administrador->checkUser($_POST['alias'], $_POST['clave']);

                // Si las credenciales son correctas
                if ($checkUserResult['status']) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';

                    // Verificar si han pasado más de 90 días desde el último cambio de contraseña
                    $lastPasswordChange = $checkUserResult['ultimo_cambio_clave'];
                    $daysSinceLastChange = (time() - strtotime($lastPasswordChange)) / (60 * 60 * 24);

                    if ($daysSinceLastChange >= 90) {
                        $result['passwordExpired'] = true;
                        $result['message'] = 'La contraseña ha expirado. Debe cambiarla.';
                    } else {
                        $result['passwordExpired'] = false;

                        // Verificar si se ha omitido el 2FA
                        $omit2FA = isset($_POST['omit2FA']) && $_POST['omit2FA'] == '1';
                        if ($omit2FA) {
                            $_SESSION['idAdministrador'] = $checkUserResult['id_usuario'];
                            $_SESSION['aliasAdministrador'] = $administrador->getAliasById($checkUserResult['id_usuario']);
                            $result['status'] = 1;
                            $result['message'] = 'Inicio de sesión exitoso. Bienvenido.';
                        } else {
                            // Si no se ha omitido, proceder con 2FA
                            $email = $administrador->getEmailById($checkUserResult['id_usuario']);
                            $subject = "Código de verificación 2FA - D-M-System";
                            $body = "Tu código de verificación es: {$checkUserResult['codigo2FA']}";
                            if (sendEmail($email, $subject, $body)) {
                                $result['status'] = 1;
                                $result['message'] = 'Primer paso de autenticación correcto. Se ha enviado un código a tu correo.';
                                $result['id_administrador'] = $checkUserResult['id_usuario'];
                                $result['twoFactorRequired'] = true;
                            } else {
                                $result['error'] = 'Error al enviar el código de verificación. Inténtalo de nuevo.';
                            }
                        }
                    }
                } else {
                    // Contraseña o usuario incorrectos
                    $alias = $_POST['alias'];

                    // Verificar si el usuario está bloqueado
                    $checkBlockSql = 'SELECT intentos_fallidos, tiempo_bloqueo FROM tb_usuarios WHERE usuario = ?';
                    $checkBlockParams = array($alias);
                    $blockData = Database::getRow($checkBlockSql, $checkBlockParams);

                    if ($blockData && is_array($blockData)) {
                        if ($blockData['tiempo_bloqueo'] && new DateTime() < new DateTime($blockData['tiempo_bloqueo'])) {
                            $result['error'] = 'Cuenta bloqueada. Intenta de nuevo después de ' . (new DateTime($blockData['tiempo_bloqueo']))->diff(new DateTime())->format('%H:%I:%S') . ' horas.';
                        } else {
                            $newAttempts = $blockData['intentos_fallidos'] + 1;

                            if ($newAttempts >= 3) {
                                $bloqueoHasta = (new DateTime())->modify('+24 hours')->format('Y-m-d H:i:s');
                                $updateSql = 'UPDATE tb_usuarios SET intentos_fallidos = ?, tiempo_bloqueo = ? WHERE usuario = ?';
                                Database::executeRow($updateSql, array($newAttempts, $bloqueoHasta, $alias));
                                $result['error'] = 'Cuenta bloqueada por 24 horas debido a múltiples intentos fallidos. Intenta de nuevo después de 24 horas.';
                            } else {
                                $updateSql = 'UPDATE tb_usuarios SET intentos_fallidos = ? WHERE usuario = ?';
                                Database::executeRow($updateSql, array($newAttempts, $alias));
                                $result['error'] = 'Credenciales incorrectas. Intentos fallidos: ' . $newAttempts . '/3';
                            }
                        }
                    } else {
                        $result['error'] = 'Ocurrió un error, verifique las credenciales.';
                    }
                }
                break;

            case 'verify2FA':
                $_POST = Validator::validateForm($_POST);
                $id_administrador = $_POST['id_administrador'];
                $codigo = $_POST['codigo2FA'];

                if ($administrador->verify2FACode($id_administrador, $codigo)) {
                    $_SESSION['idAdministrador'] = $id_administrador;
                    $_SESSION['aliasAdministrador'] = $administrador->getAliasById($id_administrador);
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación completa. Bienvenido.';
                } else {
                    $result['error'] = 'Código 2FA inválido o expirado.';
                }
                break;

            case 'logInApp':
                $_POST = Validator::validateForm($_POST);
                if ($administrador->checkUser($_POST['alias'], $_POST['clave'])) {
                    $db = new Database();
                    $alias = $_POST['alias'];
                    $sql = 'SELECT id_nivel_usuario FROM tb_usuarios WHERE usuario = ?';
                    $params = array($alias);
                    $data = $db->getRow($sql, $params);

                    if ($data) {
                        $_SESSION['aliasAdministrador'] = $alias;
                        $_SESSION['user_level'] = $data['id_nivel_usuario'];

                        $result['status'] = 1;
                        $result['message'] = 'Autenticación correcta';
                        $result['user_level'] = $data['id_nivel_usuario'];
                    } else {
                        $result['error'] = 'No se pudo obtener el nivel de usuario';
                    }
                } else {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
        }
    }

    $result['exception'] = Database::getException();
    header('Content-type: application/json; charset=utf-8');
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
