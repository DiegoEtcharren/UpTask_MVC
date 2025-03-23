<?php  

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController { 
    
    public static function login(Router $router) {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();
            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'Usuario no encontrado o no confirmado');
                } else {
                    // El usuario existe: 
                    if (password_verify($_POST['password'], $usuario->password)) { 
                        // Iniciar sesion: 
                        isSession();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('Location: /proyectos');
                        exit;
    
                    } else {
                        Usuario::setAlerta('error', 'Password Incorrecta'); 
                    }
                }
            }
        }



        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion', 
            'alertas' => $alertas
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

        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // Buscar el usuario: 
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado) {
                    // Generar token:
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar usuario:
                    $usuario->guardar();

                    // Enviar email:
                    // $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    // $email->enviarInstrucciones();

                    // Imprimir alerta:
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu emaail');
                } else {
                    // No encontro usuario:
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado'); 
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contrasena', 
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router) {
        
        $token = s($_GET['token'] ?? '');
        $mostrar = true;

        if (!$token) {
            Header('Location: /');
            exit;
        }

        // Encontrar el usuario: 
        $usuario = Usuario::where('token', $token);

        // Encontrar el usuario: 
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Valido');
            $mostrar = false;
        } 

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Anadir nuevo password: 
            $usuario->sincronizar($_POST);

            // Validar password: 
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                //Hashear password:
                $usuario->hashPassword();
                unset($usuario->password2);

                // Eliminar token: 
                $usuario->token = '';

                // Guardar en BD:
                $resultado = $usuario->guardar();
                if ($resultado) {
                    Header('Location: /');
                    exit;
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Mi Contrasena',
            'alertas' => $alertas, 
            'mostrar' => $mostrar
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

        $token = s($_GET['token'] ?? '') ;

        if (!$token) {
            Header('Location: /');
            exit;
        }

        // Encontrar el usuario: 
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no Valido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = ''; 
            unset($usuario->password2);
            
            // Guardar en la BD: 
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Verficiada Correctamente');

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu Cuenta en UpTask',
            'alertas' => $alertas,
        ]);
    }
}

?>
