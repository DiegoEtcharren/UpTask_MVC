<?php  

namespace Controllers;

use MVC\Router;

class LoginController { 
    
    public static function login(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion'
        ]);
    }

    public static function logut() {

    }

    public static function crear(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta'
        ]);
    }

    public static function olvide(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contrasena'
        ]);
    }

    public static function restablecer() {
        echo 'Desde restablecer';

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }
    }

    public static function mensaje() {

    }

    public static function confirmar() {

    }
}

?>
