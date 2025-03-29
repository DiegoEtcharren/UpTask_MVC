<?php 

namespace Controllers;

use MVC\Router;
use Model\Proyecto;

class DashboardController {
    public static function index(Router $router) {
        isSession();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos', 
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router) {
        isSession();
        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);
            
            // Validacion:
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar URL unica:
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar creador del proyecto: 
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar proyecto: 
                $proyecto->guardar();
                header('Location: /proyecto?url=' . $proyecto->url);
                exit();
            }
        }
        
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {
        isSession();
        isAuth();

        // Revisar si la persona que creo el proyecto es la que lo esta visitando: 
        $url = $_GET['url'] ?? '';
        if (!$url) {
            Header('Location: /dashboard');
        }

        $id = $_SESSION['id'];
        $proyecto = Proyecto::where('url', $url);
        if ($proyecto->propietarioId != $id) {
            Header('Location: /');
        }


        $alertas = [];

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto, 
            'alertas' => $alertas
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