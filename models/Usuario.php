<?php
namespace Model;
use Model\ActiveRecord;
 
class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'email',
        'password',
        'token',
        'confirmado'
    ];
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar Login: 
    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        } else if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es no valido';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es necesario';
        }

        

        return self::$alertas;
    }

    // Validacion Cuentas nuevas: 
    public function validarNuevaCuenta(){
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del usuario es necesario';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es necesario';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe ser mayor de 6 o mas  caracteres';
        } else if ($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los password deben coincidir';
        }

        return self::$alertas;
    }

    // Validar cambios en el perfil: 
    public function validar_perfil()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del usuario es necesario';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        }

        return self::$alertas;
    }

    // Valida un email:
    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es necesario';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email es no valido';
        }
        return self::$alertas;
    } 

    // Valida password: 
    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es necesario';
        } else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe ser mayor de 6 o mas  caracteres';
        }
        return self::$alertas;
    }

    // Comprueba si la password es correcta: 
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password);
    }

    // Valida cambio de password: 
    public function nuevo_password() : array {
        if (!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual es necesario';
        }

        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo es necesario';
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    // Hashea el password: 
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    // Generar un Token:
    public function crearToken() : void {
        $this->token = uniqid();
    }

}

