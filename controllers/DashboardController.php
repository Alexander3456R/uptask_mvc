<?php
namespace Controllers;

use Model\Proyecto;
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
    
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas
        ]);
    }
}