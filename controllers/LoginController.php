<?php

namespace Controllers;
use Model\Usuario;
use Classes\Email;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                // Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No Existe o no esta confirmado');
                } else {
                    // El usuario existe
                    if(password_verify($_POST['password'], $usuario->password)) {
                       // Iniciar la session
                       session_start();
                       $_SESSION['id'] = $usuario->id;
                       $_SESSION['nombre'] = $usuario->nombre;
                       $_SESSION['email'] = $usuario->email;
                       $_SESSION['login'] = true;

                       // Redireccionar
                       header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Contraseña incorrecta');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router) {
        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarCuentaNueva();
            
            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar el token
                    $usuario->crearToken();
                    
                    // Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        // Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crear tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);
                if($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu e-mail');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                   
                }
            }
        }
        $alertas = Usuario::getAlertas();
        // Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé Contraseña',
            'alertas' => $alertas
        ]);
    }

    
    public static function reestablecer(Router $router) {
    $token = s($_GET['token']);
    $mostrar = true;
    $sweetAlert = false;

    if(!$token) {
        header('Location: /');
    }
    // Identificar al usuario con ese token
    $usuario = Usuario::where('token', $token);
    if(empty($usuario)) {
        Usuario::setAlerta('error', 'Token no valido');
        $mostrar = false;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Añadir nuevo password
        $usuario->sincronizar($_POST);

        // validar password
        $alertas = $usuario->validarPassword();

        if(empty($alertas)) {
            // Hashear password
            $usuario->hashPassword();
            unset($usuario->password2);

            // Eliminar el token
            $usuario->token = null;
            // Guardar al usuario en la base de datos
            $resultado = $usuario->guardar();
            if($resultado) {
                $sweetAlert = true;
                $mostrar = false; // Oculta el formulario
                // No redirigir aquí
            }
        }
    }
    $alertas = Usuario::getAlertas();
    // Muestra la vista
    $router->render('auth/reestablecer', [
        'titulo' => 'Reestablecer Contraseña',
        'alertas' => $alertas,
        'mostrar' => $mostrar,
        'sweetAlert' => $sweetAlert
    ]);
}

    public static function mensaje(Router $router) {
        // Muestra la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Mensaje'
        ]);
    }


    public static function confirmar(Router $router) {

        $token = s($_GET['token']);

        if(!$token) header('Location: /');

        // Encontrar al usuario
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // Confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            // Guardar en la base de datos
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');

        }
        $alertas = Usuario::getAlertas();
        // Muestra la vista
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta',
            'alertas' => $alertas
        ]);
    }
}