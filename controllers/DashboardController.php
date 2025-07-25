<?php
namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;


class DashboardController {
    public static function index(Router $router) {
        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioid', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }


    public static function crear_proyecto(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar un URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el creador del proyecto
                $proyecto->propietarioid = $_SESSION['id'];


                // Guardar el proyecto
                $proyecto->guardar();

                // Mostrar alerta y redirigir desde la vista
                $proyectoCreado = true;
                $urlProyecto = $proyecto->url;
            }
        }
        
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas,
            'proyectoCreado' => $proyectoCreado ?? false,
            'urlProyecto' => $urlProyecto ?? ''
        ]);
    }

    public static function eliminar_proyecto() {
    session_start();
    isAuth();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['id'] ?? null;
        if(!$token) {
            header('Location: /dashboard');
            exit;
        }
        $proyecto = Proyecto::where('url', $token);
        if($proyecto && $proyecto->propietarioid === $_SESSION['id']) {
            $proyecto->eliminar();
            header('Location: /dashboard?eliminado=1');
            exit;
        } else {
            header('Location: /dashboard');
            exit;
        }
    }
}

    public static function proyecto(Router $router) {
        session_start();
        isAuth();

        $token = $_GET['id'];

        if(!$token) header('Location: /dashboard');
        // Revisar que la persona que visita, sea quien la creo
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioid !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }


    public static function perfil(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_perfil();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de error
                    Usuario::setAlerta('error', 'E-mail no valido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else {
                    // Guardar el registro
                     // Guardar usuario
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();
                    // Asignar el nombre nuevo
                    $_SESSION['nombre'] = $usuario->nombre;

                }
            }
        }
    
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function cambiar_password(Router $router) {
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            // Sincronizar con los datos de usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar  propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear el nuevo password
                    $usuario->hashPassword();

                    // Actualizar
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        Usuario::setAlerta('exito', 'Contraseña guardada correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                    $alertas = $usuario->getAlertas();
                }
            }

        }
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}