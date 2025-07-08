<?php

namespace Controllers;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    public static function logout() {
        echo 'Desde logout';
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
                    // Crear un nuevo usuario
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
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Muestra la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvidé Contraseña'
        ]);
    }

    
    public static function reestablecer(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        }

        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Contraseña'
        ]);
    }

    public static function mensaje(Router $router) {
        // Muestra la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Mensaje'
        ]);
    }


    public static function confirmar(Router $router) {
        // Muestra la vista
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar Cuenta'
        ]);
    }
}