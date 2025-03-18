<?php  

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

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

        $usuario = new Usuario;
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            if (empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'Este usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password: 
                    $usuario->hashPassword();

                    // Eliminar password2: 
                    unset($usuario->password2);

                    // Generar Token: 
                    $usuario->crearToken();

                    // Crear nuevo usuario:
                    $resultado = $usuario->guardar();

                    // Enviar email: 
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                        exit();
                    }

                }
            } 
        }

        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta',
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contrasena'
        ]);
    }

    public static function reestablecer(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Mi Contrasena'
        ]);
    }

    public static function mensaje(Router $router) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);

    }

    public static function confirmar(Router $router) {
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu Cuenta en UpTask'
        ]);
    }
}

?>
