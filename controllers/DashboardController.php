<?php 

namespace Controllers;

use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        isSession();
        isAuth();
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos'
        ]);
    }

    public static function crear_proyecto(Router $router) {
        isSession();
        isAuth();
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto'
        ]);
    }

    public static function perfil(Router $router) {
        isSession();
        isAuth();
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}

?>