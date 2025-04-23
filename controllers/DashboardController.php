<?php 

namespace Controllers;

use MVC\Router;
use Model\Usuario;
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
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();
            if (empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    // Mensaje de Error:
                    Usuario::setAlerta('error', 'El correo ya esta dado de alta');
                } else {
                    // Guardar cambios:
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Cambios guardados correctamente');

                    // Actualizar los valores de la sesion
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;

                }

                $alertas = Usuario::getAlertas();

            }
        }
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas, 
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password(Router $router) {
        isSession();
        isAuth();
        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();
            if (empty($alertas)) {
                $resultado = $usuario->comprobar_password();
                if ($resultado) {
                    // Asignar el nuevo password:
                    
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar propiedades no necesarias: 
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear password: 
                    $usuario->hashPassword();

                    // Guardar en BD:
                    $resultado  = $usuario->guardar();

                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password cambiada correctamente');
                    }
                } else {
                    Usuario::setAlerta('error', 'El password actual no es correcta');
                }

                $alertas = $usuario->getAlertas();
            }
        }
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas, 
            'usuario' => $usuario
        ]);
    }

}

?>